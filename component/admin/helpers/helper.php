<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class reddesignhelper
{
	/**
	 *   reddesign
	 */
	public function __construct()
	{
		global $mainframe, $context;
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
	}

	/**
	 * Check That reddesign is installed or not
	 */
	public function CheckIfRedDesign()
	{
		$db = JFactory::getDBO();
		$query = "SELECT extension_id FROM `#__extensions` WHERE `element` LIKE '%com_reddesign%'";
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Check That reddesigh is assigned to product or not & only redirect if user belongs to that shopper.
	 */
	public function CheckIfRedProduct($product_id)
	{
		$user = JFactory::getUser();

		if (!$user->guest)
		{
			$db = JFactory::getDBO();
			$query = "SELECT * FROM `#__reddesign_redshop` WHERE `product_id` = '" . $product_id . "'";
			$db->setQuery($query);
			$redproinfo = $db->loadObjectList();

			if (count($redproinfo) > 0)
			{
				$progrouplist = explode(",", $redproinfo[0]->shoppergroups);

				$usershopper = $this->GetUserShopperGroup($user->id);

				if (in_array($usershopper, $progrouplist))
				{
					return $redproinfo;
				}
			}
		}

		return false;
	}

	/*  get shopper group for logged in user */
	public function GetUserShopperGroup($uid)
	{
		$db = JFactory::getDBO();
		$query = "SELECT shopper_group_id FROM `#__redshop_users_info` WHERE `user_id` = '" . $uid . "'";
		$db->setQuery($query);
		$usershopper = $db->loadResult();

		if ($usershopper > 0)
		{
			return $usershopper;
		}

		else
		{
			return 1;
		}
	}

	public function CheckRedDesignpro($order_id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT o.*,image_name FROM  #__reddesign_order AS o LEFT JOIN #__reddesign_image AS i
		ON (o.image_id=i.image_id) WHERE o.order_id=" . $order_id;
		$db->setQuery($query);
		$orderdesign = $db->loadObjectlist();

		return $orderdesign;
	}

	public function geticonarray()
	{
		$icon_array = array("products" => array('product', 'category', 'manufacturer', 'media'),
			"orders" => array('order', 'quotation', 'stockroom', 'container'),
			"discounts" => array('discount', 'giftcard', 'voucher', 'coupon'),
			"communications" => array('mail', 'newsletter'),
			"shippings" => array('shipping', 'shipping_box', 'shipping_detail', 'wrapper'),
			"users" => array('user', 'shopper_group', 'accessmanager'),
			"vats" => array('tax_group', 'currency', 'country', 'state'),
			"importexport" => array('import', 'xmlimport'),
			"altration" => array('fields', 'template', 'textlibrary'),
			"customerinput" => array('question', 'rating'),
			"accountings" => array('accountgroup'),
			"popular" => array(),

			"prodimages" => array('products48.png', 'categories48.png', 'manufact48.png', 'media48.png'),
			"orderimages" => array('order48.png', 'quotation_48.jpg', 'stockroom48.png', 'container48.png'),
			"discountimages" => array('discountmanagmenet48.png', 'giftcard_48.png', 'voucher48.png', 'coupon48.png'),
			"commimages" => array('mailcenter48.png', 'newsletter48.png'),
			"shippingimages" => array('shipping48.png', 'shipping_boxes48.png', 'shipping48.png', 'wrapper48.png'),
			"userimages" => array('user48.png', 'manufact48.png', 'catalogmanagement48.png'),
			"vatimages" => array('vatgroup_48.png', 'currencies_48.png', 'country_48.png', 'region_48.png'),
			"importimages" => array('importexport48.png', 'importexport48.png'),
			"altrationimages" => array('fields48.png', 'templates48.png', 'textlibrary48.png'),
			"customerinputimages" => array('question_48.jpg', 'rating48.png'),
			"accimages" => array('accounting_group48.png'),
			"popularimages" => array(),


			"prodtxt" => array('PRODUCTS', 'CATEGORIES', 'MANUFACTURERS', 'MEDIA'),
			"ordertxt" => array('ORDER', 'QUOTATION', 'STOCKROOM', 'CONTAINER'),
			"discounttxt" => array('DISCOUNT_MANAGEMENT', 'GIFTCARD', 'VOUCHER', 'COUPON_MANAGEMENT'),
			"commtxt" => array('MAIL_CENTER', 'NEWSLETTER'),
			"shippingtxt" => array('SHIPPING', 'SHIPPING_BOX', 'SHIPPING_DETAIL', 'WRAPPER'),
			"usertxt" => array('USER', 'SHOPPER_GROUP', 'ACCESS_MANAGER'),
			"vattxt" => array('TAX_GROUP', 'CURRENCY', 'COUNTRY', 'STATE'),
			"importtxt" => array('IMPORT_EXPORT', 'XML_IMPORT_EXPORT'),
			"altrationtxt" => array('FIELDS', 'TEMPLATES', 'TEXT_LIBRARY'),
			"customerinputtxt" => array('QUESTION', 'REVIEW'),
			"acctxt" => array('ECONOMIC_ACCOUNT_GROUP'),
			"populartxt" => array());

		return $icon_array;
	}
}
