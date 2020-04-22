<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.0.9
 */
class RedshopUpdate209 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.9
	 */
	protected function getOldFiles()
	{
		return array();
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.9
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_SITE . '/components/com_redshop/templates'
		);
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.9
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

		$templates = $this->migrateOldTemplate($templates);

		if (empty($templates))
		{
			return;
		}

		$this->migrateOverrideTemplate($templates);
	}

	/**
	 * Template View selector
	 *
	 * @param   array $templates Templates
	 *
	 * @return  array              List of template table which already migrate correct data.
	 *
	 * @since   2.0.9
	 */
	protected function migrateOldTemplate($templates = array())
	{
		$oldPaths = array();
		$tables   = array();

		// Copy old template files to new structure.
		foreach ($templates as $template)
		{
			/** @var RedshopTableTemplate $table */
			$table = RedshopTable::getAdminInstance('Template', array('ignore_request' => true), 'com_redshop');
			$table->bind((array) $template);

			// Skip if template already migrate
			if (!empty($template->file_name))
			{
				$tables[] = $table;

				continue;
			}

			$table->file_name = $table->generateTemplateFileName($table->id, $table->name);

			if (!$table->store())
			{
				continue;
			}

			$view       = $this->getTemplateView($template->section);
			$oldPaths[] = JPath::clean(JPATH_SITE . '/components/com_redshop/views/' . $view . '/tmpl/' . $template->section);
			$sourceFile = JPATH_SITE . '/components/com_redshop/views/' . $view . '/tmpl/' . $template->section . '/' . $template->name . '.php';
			$sourceFile = JPath::clean($sourceFile);

			if (!JFile::exists($sourceFile))
			{
				$sourceFile = JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $table->section . '/default.php');
			}

			$targetFile = JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $table->section . '/' . $table->file_name . '.php');

			if (JFile::exists($sourceFile))
			{
				if (JFile::exists($targetFile))
				{
					JFile::delete($targetFile);
				}

				JFile::copy($sourceFile, $targetFile);
			}

			$tables[] = $table;
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

		return $tables;
	}

	/**
	 * Template View selector
	 *
	 * @param   array $templates Templates
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.9
	 */
	protected function migrateOverrideTemplate($templates = array())
	{
		$joomlaTemplate = $this->getActiveSiteTemplate();

		foreach ($templates as $template)
		{
			/** @var RedshopTableTemplate $template */
			$view         = $this->getTemplateView($template->section);
			$overrideFile = JPATH_SITE . '/templates/' . $joomlaTemplate . '/html/com_redshop/';

			if ($template->section != 'categoryproduct')
			{
				$overrideFile .= $view . '/' . $template->section . '/' . $template->name . '.php';
			}
			else
			{
				$overrideFile .= $template->section . '/' . $template->name . '.php';
			}

			$overrideFile = JPath::clean($overrideFile);

			if (!JFile::exists($overrideFile))
			{
				continue;
			}

			$target = JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $template->section . '/' . $template->file_name . '.php');

			if (JFile::exists($target))
			{
				JFile::delete($target);
			}

			JFile::move($overrideFile, $target);
		}
	}

	/**
	 * Template View selector
	 *
	 * @param   string  $section  Template section
	 *
	 * @return  string            Template Joomla view name
	 *
	 * @since   2.0.9
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

	/**
	 * Method for get "default" template use on Front-end
	 *
	 * @return  string
	 *
	 * @since   2.0.9
	 */
	protected function getActiveSiteTemplate()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('template'))
			->from($db->qn('#__template_styles'))
			->where($db->qn('client_id') . ' = 0')
			->where($db->qn('home') . ' = 1');

		return $db->setQuery($query)->loadResult();
	}
}
