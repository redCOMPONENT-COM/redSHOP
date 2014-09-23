<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');

class plgredshop_productstock_notifyemail extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgredshop_productstock_notifyemail(&$subject)
	{
		parent::__construct($subject);

		// Load plugin parameters
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_product', 'stock_notifyemail');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param    object        The Product Template Data
	 * @param    object        The product params
	 * @param    object        The product object
	 */
	public function afterUpdateStock($stockroom_data)
	{
		$redshopMail = new redshopMail;

		if ($stockroom_data['regular_stock'] || $stockroom_data['preorder_stock'])
		{
			$userData = $this->getNotifyUsers($stockroom_data);

			if (count($userData) > 0)
			{
				for ($u = 0; $u < count($userData); $u++)
				{
					$productData = $this->getProductData($userData[$u]);
					$productDetail = $productData['product_detail'];
					$productName = $productData['product_name'];
					$notify_template = $redshopMail->getMailtemplate(0, "notify_stock_mail");

					if (count($notify_template) > 0)
					{
						$message = $notify_template[0]->mail_body;
						$mail_subject = $notify_template[0]->mail_subject;
					}
					else
					{
						return;
					}

					$message = str_replace("{stocknotify_intro_text}", JText::_('COM_REDSHOP_STOCK_NOTIFY_INTRO_TEXT'), $message);
					$message = str_replace("{product_detail}", $productDetail, $message);
					$mail_subject = str_replace("{product_name}", $productName, $mail_subject);

					if ($userData[$u]->user_email)
					{
						JUtility::sendMail(SHOP_NAME, SHOP_NAME, $userData[$u]->user_email, $mail_subject, $message, 1);
					}

					$this->deleteNotifiedUsers($userData[$u]);
				}
			}

		}

	}

	function getNotifyUsers($stockroom_data)
	{
		$section_id = $stockroom_data['section_id'];
		$db = JFactory::getDbo();
		$query = "";

		if ($stockroom_data['section'] == "product")
		{
			$query = 'SELECT nu.*, u.user_email FROM ' . $this->_table_prefix . 'notifystock_users nu LEFT join ' . $this->_table_prefix . 'users_info u on nu.user_id = u.user_id WHERE nu.product_id = ' . $section_id . ' and nu.property_id = 0 and nu.subproperty_id = 0 and nu.notification_status=0';
		}
		else if ($stockroom_data['section'] == "property")
		{
			$query = 'SELECT nu.*, u.user_email FROM ' . $this->_table_prefix . 'notifystock_users nu LEFT join ' . $this->_table_prefix . 'users_info u on nu.user_id = u.user_id WHERE nu.property_id = ' . $section_id . ' and nu.subproperty_id = 0 and nu.notification_status=0';
		}
		else if ($stockroom_data['section'] == "subproperty")
		{
			$query = 'SELECT nu.*, u.user_email FROM ' . $this->_table_prefix . 'notifystock_users nu LEFT join ' . $this->_table_prefix . 'users_info u on nu.user_id = u.user_id WHERE nu.subproperty_id = ' . $section_id . ' and nu.notification_status=0';
		}

		if ($query != "")
		{
			$db->setQuery($query);
			$userData = $db->loadObjectlist();
		}

		return $userData;
	}

	function getProductData($userData)
	{
		$producthelper = new producthelper;

		if ($userData->product_id)
		{
			$productDetail = "";
			$product_data = $producthelper->getProductById($userData->product_id);

			if ($userData->subproperty_id)
			{
				$subproperty_data = $producthelper->getAttibuteSubProperty($userData->subproperty_id);
				$property_data = $producthelper->getAttibuteProperty($userData->property_id);
			}
			else if ($userData->property_id)
			{
				$property_data = $producthelper->getAttibuteProperty($userData->property_id);
			}

			$productData = array();
			$productDetail = $product_data->product_name;

			if (count($property_data) > 0)
			{
				$productDetail .= "<br/>" . $property_data['property_name'];
			}

			if (count($subproperty_data) > 0)
			{
				$productDetail .= "<br/>" . $subproperty_data['subproperty_name'];
			}

			$productData['product_name'] = $product_data->product_name;
			$productData['product_detail'] = $productDetail;

			return $productData;
		}
	}

	function deleteNotifiedUsers($userData)
	{
		$db = JFactory::getDbo();
		$query = "DELETE FROM " . $this->_table_prefix . "notifystock_users WHERE product_id=" . $userData->product_id . " and property_id=" . $userData->property_id . " and subproperty_id=" . $userData->subproperty_id . " and user_id =" . $userData->user_id . "";
		$db->setQuery($query);
		$db->Query();
	}
}
?>
