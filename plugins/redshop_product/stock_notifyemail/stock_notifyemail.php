<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
JLoader::import('redshop.library');

/**
 * Plgredshop_Productstock_Notifyemail Class
 *
 * @since  1.5
 */
class Plgredshop_Productstock_Notifyemail extends JPlugin
{
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang          = JFactory::getLanguage();
		$lang->load('plg_redshop_product_stock_notifyemail', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Method is called by the product view
	 *
	 * @param   array  $stockroom_data  Stockroomdata
	 *
	 * @return  void
	 */
	public function onAfterUpdateStock($stockroom_data)
	{
		if ($stockroom_data['regular_stock'] || $stockroom_data['preorder_stock'])
		{
			$userData = $this->getNotifyUsers($stockroom_data);

			if (count($userData) > 0)
			{
				$redshopMail = redshopMail::getInstance();
				$notify_template = $redshopMail->getMailtemplate(0, "notify_stock_mail");

				for ($u = 0, $countUserData = count($userData); $u < $countUserData; $u++)
				{
					$productData = $this->getProductData($userData[$u]);

					if (count($productData) > 0 && count($notify_template) > 0 && $userData[$u]->user_email)
					{
						$productDetail = $productData['product_detail'];
						$productName = $productData['product_name'];
						$message = $notify_template[0]->mail_body;
						$mail_subject = $notify_template[0]->mail_subject;

						$message = str_replace("{stocknotify_intro_text}", JText::_('PLG_REDSHOP_PRODUCT_STOCK_NOTIFYEMAIL_NOTIFY_INTRO_TEXT'), $message);
						$message = str_replace("{product_detail}", $productDetail, $message);
						$mail_subject = str_replace("{product_name}", $productName, $mail_subject);
						$message = $redshopMail->imginmail($message);
						JFactory::getMailer()->sendMail(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'), Redshop::getConfig()->get('SHOP_NAME'), $userData[$u]->user_email, $mail_subject, $message, 1);
					}

					$this->deleteNotifiedUsers($userData[$u]);
				}
			}
		}
	}

	/**
	 * Get Notify Users
	 *
	 * @param   array  $stockroom_data  Stockroom data
	 *
	 * @return  mixed
	 */
	public function getNotifyUsers($stockroom_data)
	{
		$section_id = $stockroom_data['section_id'];
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('nu.*, u.user_email')
			->from($db->qn('#__redshop_notifystock_users', 'nu'))
			->leftJoin($db->qn('#__redshop_users_info', 'u') . ' ON nu.user_id = u.user_id')
			->where('nu.notification_status = 0')
			->group('nu.id');

		switch ($stockroom_data['section'])
		{
			case "property":
				$query->where('nu.property_id = ' . (int) $section_id)
					->where('nu.subproperty_id = 0');
				break;
			case "subproperty":
				$query->where('nu.subproperty_id = ' . (int) $section_id);
				break;
			case "product":
			default:
				$query->where('nu.product_id = ' . (int) $section_id)
					->where('nu.property_id = 0')
					->where('nu.subproperty_id = 0');
				break;
		}

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 * Get Product data
	 *
	 * @param   object  $userData  User data
	 *
	 * @return  array
	 */
	public function getProductData($userData)
	{
		$productData = array();

		if ($userData->product_id)
		{
			if ($product_data = RedshopHelperProduct::getProductById($userData->product_id))
			{
				$productDetail = $product_data->product_name;
				$producthelper = productHelper::getInstance();

				if ($userData->property_id)
				{
					if ($property_data = $producthelper->getAttibuteProperty($userData->property_id))
					{
						$productDetail .= "<br/>" . $property_data->property_name;
					}
				}

				if ($userData->subproperty_id)
				{
					if ($subproperty_data = $producthelper->getAttibuteSubProperty($userData->subproperty_id))
					{
						$productDetail .= "<br/>" . $subproperty_data->subattribute_color_name;
					}
				}

				$productData['product_name'] = $product_data->product_name;
				$productData['product_detail'] = $productDetail;
			}
		}

		return $productData;
	}

	/**
	 * Delete Notified Users
	 *
	 * @param   object  $userData  User data
	 *
	 * @return  void
	 */
	public function deleteNotifiedUsers($userData)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_notifystock_users'))
			->where('product_id = ' . (int) $userData->product_id)
			->where('property_id = ' . (int) $userData->property_id)
			->where('subproperty_id = ' . (int) $userData->subproperty_id)
			->where('user_id = ' . (int) $userData->user_id);
		$db->setQuery($query)->execute();
	}
}
