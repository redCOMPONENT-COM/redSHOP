<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.1.0
 */
class RedshopUpdate210 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/coupon/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/addressfields_listing.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/addressfields_listing.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/addressfields_listing',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/coupon_detail',
			JPATH_SITE . '/components/com_redshop/templates'
		);
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function migrateCoupons()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_coupons'))
			->order($db->qn('id'));

		$coupons = $db->setQuery($query)->loadColumn();

		if (empty($coupons))
		{
			return;
		}

		foreach ($coupons as $coupon)
		{
			$table = RedshopTable::getAdminInstance('Coupon');

			if (!$table->load($coupon->id))
			{
				continue;
			}

			$table->start_date = empty($coupon->start_date_old) ?
				'0000-00-00 00:00:00' : JFactory::getDate($coupon->start_date_old)->format('Y-m-d H:i:s');

			$table->end_date = empty($coupon->end_date_old) ?
				'0000-00-00 00:00:00' : JFactory::getDate($coupon->end_date_old)->format('Y-m-d H:i:s');

			if (!$table->store())
			{
				JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
			}
		}

		$query = 'CALL redSHOP_Column_Remove(' . $db->quote('#__redshop_coupons') . ',' . $db->quote('start_date_old') . ');';
		$db->setQuery($query)->execute();
		$query = 'CALL redSHOP_Column_Remove(' . $db->quote('#__redshop_coupons') . ',' . $db->quote('end_date_old') . ');';
		$db->setQuery($query)->execute();
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function migrateTemplateFiles()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_template'))
			->order($db->qn('section'));

		$templates = $db->setQuery($query)->loadObjectList();

		if (empty($templates))
		{
			return;
		}

		$oldPaths = array();

		// Copy old template files to new structure.
		foreach ($templates as $template)
		{
			// Skip if template already migrate
			if (!empty($template->file_name))
			{
				continue;
			}

			/** @var RedshopTableTemplate $table */
			$table = RedshopTable::getAdminInstance('Template', array('ignore_request' => true), 'com_redshop');
			$table->bind((array) $template);

			$table->file_name = $table->generateTemplateFileName($table->id, $table->name);

			if (!$table->store())
			{
				continue;
			}

			$view = $this->getTemplateView($template->section);

			$oldPaths[] = JPath::clean(JPATH_SITE . '/components/com_redshop/views/' . $view . '/tmpl/' . $template->section);

			$sourceFile = JPATH_SITE . '/components/com_redshop/views/' . $view . '/tmpl/' . $template->section . '/' . $template->name . '.php';
			$sourceFile = JPath::clean($sourceFile);

			$targetFile = JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $table->section . '/' . $table->file_name . '.php');

			if (JFile::exists($sourceFile))
			{
				if (JFile::exists($targetFile))
				{
					JFile::delete($targetFile);
				}

				JFile::copy($sourceFile, $targetFile);
			}
		}

		// Delete old folders.
		$oldPaths = array_unique($oldPaths);

		foreach ($oldPaths as $path)
		{
			if (JFolder::exists($path))
			{
				JFolder::delete($path);
			}
		}
	}

	/**
	 * Template View selector
	 *
	 * @param   string  $section  Template Section
	 *
	 * @return  string            Template Joomla view name
	 *
	 * @since   2.1.0
	 */
	protected function getTemplateView($section)
	{
		$section = strtolower($section);

		switch ($section)
		{
			case 'product':
			case 'related_product':
			case 'product_sample':
			case 'accessory_template':
			case 'attribute_template':
			case 'attributewithcart_template':
			case 'review':
			case 'wrapper_template':
			case 'compare_product':
				$view = "product";
				break;

			case 'categoryproduct':
			case 'category':
			case 'frontpage_category':
				$view = "category";
				break;

			case 'catalog':
			case 'catalog_sample':
				$view = "catalog";
				break;

			case 'manufacturer':
			case 'manufacturer_detail':
			case 'manufacturer_products':

				$view = "manufacturers";
				break;
			case 'cart':
			case 'add_to_cart':
			case 'ajax_cart_detail_box':
			case 'ajax_cart_box':
			case 'empty_cart':
				$view = "cart";
				break;

			case 'account_template':
				$view = "account";
				break;

			case 'private_billing_template':
			case 'company_billing_template':
			case 'billing_template':
			case 'shipping_template':
				$view = "registration";
				break;

			case 'wishlist_template':
			case 'wishlist_mail_template':
				$view = "wishlist";
				break;

			case 'newsletter':
			case 'newsletter_product':
				$view = "newsletter";
				break;

			case 'order_list':
			case 'order_detail':
			case 'order_receipt':
				$view = "orders";
				break;

			case 'giftcard':
				$view = "giftcard";
				break;

			case 'checkout':
			case 'onestep_checkout':
				$view = "checkout";
				break;

			case 'ask_question_template':
				$view = "ask_question";
				break;

			default:
				return '';
		}

		return $view;
	}
}
