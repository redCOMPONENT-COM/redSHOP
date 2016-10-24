<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class shipping Helper
 *
 * @deprecated  2.0.0.3
 */
class shipping
{
	protected static $instance = null;

	/**
	 * Returns the shipping object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  shipping  The shipping object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Get Shipping rate for cart
	 *
	 * @param   array  $d  Shipping data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getDefaultShipping($d) instead
	 */
	public function getDefaultShipping($d)
	{
		return RedshopHelperShipping::getDefaultShipping($d);
	}

	/**
	 * Get Shipping rate for xmlexport
	 *
	 * @param   array  $d  Shipping data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getDefaultShippingXmlExport($d) instead
	 */
	public function getDefaultShipping_xmlexport($d)
	{
		return RedshopHelperShipping::getDefaultShippingXmlExport($d);
	}

	/**
	 * Return only one shipping rate on cart page...
	 * this function is called by ajax
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingRateCalc() instead
	 */
	public function getShippingrate_calc()
	{
		return RedshopHelperShipping::getShippingRateCalc();
	}

	/**
	 * Encrypt Shipping
	 *
	 * @param   string  $strMessage  String to encrypt
	 *
	 * @deprecated 1.6  Use RedshopShippingRate::encrypt(array);
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::encryptShipping($strMessage) instead
	 */
	public function encryptShipping($strMessage)
	{
		return RedshopHelperShipping::encryptShipping($strMessage);
	}

	/**
	 * Decrypt Shipping
	 *
	 * @param   string  $strMessage  String to decrypt
	 *
	 * @deprecated 1.6  Use RedshopShippingRate::decrypt(string);
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::decryptShipping($strMessage) instead
	 */
	public function decryptShipping($strMessage)
	{
		return RedshopHelperShipping::decryptShipping($strMessage);
	}

	/**
	 * Get shipping address
	 *
	 * @param   int  $userInfoId  User info id
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingAddress($userInfoId) instead
	 */
	public function getShippingAddress($userInfoId)
	{
		return RedshopHelperShipping::getShippingAddress($userInfoId);
	}

	/**
	 * Get shipping method class
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingMethodByClass($shippingClass) instead
	 */
	public function getShippingMethodByClass($shippingClass = '')
	{
		return RedshopHelperShipping::getShippingMethodByClass($shippingClass);
	}

	/**
	 * Get shipping method by id
	 *
	 * @param   int  $id  Shipping id
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingMethodById($id) instead
	 */
	public function getShippingMethodById($id = 0)
	{
		return RedshopHelperShipping::getShippingMethodById($id);
	}

	/**
	 * Get shipping rates
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingRates($shippingClass) instead
	 */
	public function getShippingRates($shippingClass)
	{
		return RedshopHelperShipping::getShippingRates($shippingClass);
	}

	/**
	 * Apply VAT on shipping rate
	 *
	 * @param   object  $shippingRate  Shipping Rate information
	 * @param   array   $data          Shipping Rate user information from cart or checkout selection.
	 *
	 * @return  object  Shipping Rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::applyVatOnShippingRate($shippingRate, $data) instead
	 */
	public function applyVatOnShippingRate($shippingRate, $data)
	{
		return RedshopHelperShipping::applyVatOnShippingRate($shippingRate, $data);
	}

	/**
	 * List shipping rates
	 *
	 * @param   object  $shippingClass  Shipping class
	 * @param   array   $usersInfoId    User info id
	 * @param   array   &$data          Shipping data
	 *
	 * @return  object  Shipping Rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::listShippingRates($shippingClass, $usersInfoId, &$data) instead
	 */
	public function listshippingrates($shippingClass, $usersInfoId, &$data)
	{
		return RedshopHelperShipping::listShippingRates($shippingClass, $usersInfoId, $data);
	}

	/**
	 * Get shipping vat rates based on either billing or shipping user
	 *
	 * @param   int    $shippingTaxGroupId  Shipping Default Tax Gorup ID
	 * @param   array  $data                Shipping User Information array
	 *
	 * @return  object Shipping VAT rates
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingVatRates($shippingTaxGroupId, $data) instead
	 */
	public function getShippingVatRates($shippingTaxGroupId, $data = array())
	{
		return RedshopHelperShipping::getShippingVatRates($shippingTaxGroupId, $data);
	}

	/**
	 * Get shopper group default shipping
	 *
	 * @param   int  $userId  User id
	 *
	 * @return  arrays
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShopperGroupDefaultShipping($userId) instead
	 */
	public function getShopperGroupDefaultShipping($userId = 0)
	{
		return RedshopHelperShipping::getShopperGroupDefaultShipping($userId);
	}

	/**
	 * Find first number position
	 *
	 * @param   string  $haystack  string to find
	 * @param   array   $needles   array to find
	 * @param   int     $offset    position
	 *
	 * @return  arrays
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::strposa($haystack, $needles, $offset) instead
	 */
	public function strposa($haystack, $needles = array(), $offset = 0)
	{
		return RedshopHelperShipping::strposa($haystack, $needles, $offset);
	}

	/**
	 * Filter Shipping rates based on their priority
	 * Only show Higher priority rates (In [1,2,3,4] take 1 as a high priority)
	 * Rates with same priority will shown as radio button list in checkout
	 *
	 * @param   array  $shippingRates  Array shipping rates
	 *
	 * @return array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::filterRatesByPriority($shippingRates) instead
	 */
	public static function filterRatesByPriority($shippingRates)
	{
		return RedshopHelperShipping::filterRatesByPriority($shippingRates);
	}

	/**
	 * Function to get product volume shipping
	 *
	 * @return array $cases , 3cases of shipping
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getProductVolumeShipping() instead
	 */
	public function getProductVolumeShipping()
	{
		return RedshopHelperShipping::getProductVolumeShipping();
	}

	/**
	 * Function to get cart item dimension
	 *
	 * @return array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getCartItemDimention() instead
	 */
	public function getCartItemDimention()
	{
		return RedshopHelperShipping::getCartItemDimention();
	}

	/**
	 * Get available shipping boxes according to cart items
	 *
	 * @return object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingBox() instead
	 */
	public function getShippingBox()
	{
		return RedshopHelperShipping::getShippingBox();
	}

	/**
	 * Get selected shipping BOX dimensions
	 *
	 * @param   int  $boxId  Shipping Box id
	 *
	 * @return  array  box dimensions
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getBoxDimensions($boxId) instead
	 */
	public function getBoxDimensions($boxId = 0)
	{
		return RedshopHelperShipping::getBoxDimensions($boxId);
	}

	/**
	 * Get Shipping rate error
	 *
	 * @param   array  &$data  Shipping rate data
	 *
	 * @return  string  error text
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingRateError($data) instead
	 */
	public function getShippingRateError(&$data)
	{
		return RedshopHelperShipping::getShippingRateError($data);
	}

	/**
	 * Get Shipping rate error
	 *
	 * @param   array  &$data  Cart data
	 *
	 * @return  string  error text
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::isCartDimentionMatch($data) instead
	 */
	public function isCartDimentionMatch(&$data)
	{
		return RedshopHelperShipping::isCartDimentionMatch($data);
	}

	public function isUserInfoMatch(&$d)
	{
		$userhelper   = rsUserHelper::getInstance();
		$shippingrate = array();
		$db = JFactory::getDbo();

		$userInfo     = $this->getShippingAddress($d['users_info_id']);
		$country      = $userInfo->country_code;
		$state        = $userInfo->state_code;
		$zip          = $userInfo->zipcode;
		$is_company   = $userInfo->is_company;

		$whereshopper = '';
		$wherestate   = '';

		if ($is_company)
		{
			$where = "AND ( company_only = 1 or company_only = 0) ";
		}
		else
		{
			$where = "AND ( company_only = 2 or company_only = 0) ";
		}

		if ($country)
		{
			$wherecountry = "AND (FIND_IN_SET(" . $db->quote($country) . ", shipping_rate_country ) OR shipping_rate_country='0' OR shipping_rate_country='') ";
		}
		else
		{
			$wherecountry = "AND (FIND_IN_SET(" . $db->quote(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ", shipping_rate_country)) ";
		}

		$shoppergroup = $userhelper->getShoppergroupData($userInfo->user_id);

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper = ' AND (FIND_IN_SET(' . (int) $shopper_group_id . ', shipping_rate_on_shopper_group ) OR shipping_rate_on_shopper_group="") ';
		}

		if ($state)
		{
			$wherestate = "AND (FIND_IN_SET(" . $db->quote($state) . ", shipping_rate_state ) OR shipping_rate_state='0' OR shipping_rate_state='') ";
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");
		$zipCond = "";
		$zip     = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = "AND ( ( shipping_rate_zip_start <= " . $db->quote($zip) . " AND shipping_rate_zip_end >= " . $db->quote($zip) . " ) "
				. "OR (shipping_rate_zip_start='0' AND shipping_rate_zip_end='0') "
				. "OR (shipping_rate_zip_start='' AND shipping_rate_zip_end='') ) ";
		}

		$query = "SELECT * FROM #__redshop_shipping_rate "
			. "WHERE (shipping_class = 'default_shipping' OR shipping_class = 'shipper') "
			. $wherecountry
			. $wherestate
			. $whereshopper
			. $zipCond
			. $where
			. " ORDER BY shipping_rate_priority ";
		$db->setQuery($query);
		$shippingrate = $db->loadObjectList();

		if (count($shippingrate) > 0)
		{
			return true;
		}

		return false;
	}

	public function isProductDetailMatch()
	{
		$db = JFactory::getDbo();
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$idx     = (int) ($cart['idx']);

		$pwhere  = "";
		$cwhere  = "";

		if ($idx)
		{
			$pwhere = 'OR ( ';

			for ($i = 0; $i < $idx; $i++)
			{
				$product_id = $cart [$i] ['product_id'];
				$pwhere .= 'FIND_IN_SET(' . (int) $product_id . ', shipping_rate_on_product)';

				if ($i != $idx - 1)
				{
					$pwhere .= " OR ";
				}
			}

			$pwhere .= ")";
		}

		$acwhere = array();

		for ($i = 0; $i < $idx; $i++)
		{
			$product_id = $cart[$i]['product_id'];
			$sel = 'SELECT category_id FROM #__redshop_product_category_xref WHERE product_id = ' . (int) $product_id;
			$db->setQuery($sel);
			$categorydata = $db->loadObjectList();

			for ($c = 0, $cn = count($categorydata); $c < $cn; $c++)
			{
				$acwhere[] = " FIND_IN_SET(" . (int) $categorydata [$c]->category_id . ", shipping_rate_on_category) ";
			}
		}

		if (isset($acwhere) && count($acwhere) > 0)
		{
			$acwhere = implode(' OR ', $acwhere);
			$cwhere = ' OR (' . $acwhere . ')';
		}

		$query = "SELECT * FROM #__redshop_shipping_rate "
			. "WHERE (shipping_class = 'default_shipping' OR shipping_class = 'shipper') "
			. "AND (shipping_rate_on_product = '' $pwhere) AND (shipping_rate_on_category = '' $cwhere ) "
			. "ORDER BY shipping_rate_priority ";
		$db->setQuery($query);
		$shippingrate = $db->loadObjectList();

		if (count($shippingrate) > 0)
		{
			return true;
		}

		return false;
	}

	public function getfreeshippingRate($shipping_rate_id = 0)
	{
		$productHelper = productHelper::getInstance();
		$userhelper    = rsUserHelper::getInstance();
		$session       = JFactory::getSession();
		$cart          = $session->get('cart', null);
		$db            = JFactory::getDbo();

		$idx = 0;

		if (isset($cart ['idx']) === true)
		{
			$idx = (int) ($cart ['idx']);
		}

		$order_subtotal  = isset($cart['product_subtotal']) ? $cart['product_subtotal'] : null;
		$order_functions = order_functions::getInstance();
		$user            = JFactory::getUser();
		$user_id         = $user->id;

		if (!empty($idx))
		{
			$text = JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
		}
		else
		{
			return JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE_WHEN_NOPRODUCT_IN_CART');
		}

		$users_info_id = JRequest::getVar('users_info_id');

		// Try to load user information
		$userInfo     = null;
		$country      = null;
		$state        = null;
		$is_company   = null;
		$shoppergroup = null;
		$zip          = null;

		if ($user_id)
		{
			if ($users_info_id)
			{
				$userInfo = $this->getShippingAddress($users_info_id);
			}
			elseif ($userInfo = $order_functions->getShippingAddress($user_id))
			{
				$userInfo = $userInfo[0];
			}
		}

		$where        = '';
		$wherestate   = '';
		$whereshopper = '';

		if (!$is_company)
		{
			$where = " AND ( company_only = 2 or company_only = 0) ";
		}
		else
		{
			$where = " AND ( company_only = 1 or company_only = 0) ";
		}

		if ($userInfo)
		{
			$country      = $userInfo->country_code;
			$state        = $userInfo->state_code;
			$is_company   = $userInfo->is_company;
			$shoppergroup = $userhelper->getShoppergroupData($userInfo->user_id);
			$zip          = $userInfo->zipcode;
		}

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper = ' AND (FIND_IN_SET(' . (int) $shopper_group_id . ', shipping_rate_on_shopper_group )
			OR shipping_rate_on_shopper_group="") ';
		}

		$shippingrate = array();

		if ($country)
		{
			$wherecountry = 'AND (FIND_IN_SET(' . $db->quote($country) . ', shipping_rate_country ) OR shipping_rate_country="0"
			OR shipping_rate_country="" )';
		}
		else
		{
			$wherecountry = 'AND (FIND_IN_SET(' . $db->quote(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ', shipping_rate_country )
			OR shipping_rate_country="0" OR shipping_rate_country="")';
		}

		if ($state)
		{
			$wherestate = ' AND (FIND_IN_SET(' . $db->quote($state) . ', shipping_rate_state ) OR shipping_rate_state="0" OR shipping_rate_state="")';
		}

		$zipCond = "";
		$zip = trim($zip);

		if (preg_match('/^[0-9 ]+$/', $zip) && !empty($zip))
		{
			$zipCond = ' AND ( ( shipping_rate_zip_start <= ' . $db->quote($zip) . ' AND shipping_rate_zip_end >= ' . $db->quote($zip) . ' )
				OR (shipping_rate_zip_start = "0" AND shipping_rate_zip_end = "0")
				OR (shipping_rate_zip_start = "" AND shipping_rate_zip_end = "") ) ';
		}

		if ($shipping_rate_id)
		{
			$where .= ' AND sr.shipping_rate_id = ' . (int) $shipping_rate_id . ' ';
		}

		$sql = "SELECT * FROM #__redshop_shipping_rate as sr
								 LEFT JOIN #__extensions AS s
								 ON
								 sr.shipping_class = s.element
								 WHERE (shipping_rate_value =0 OR shipping_rate_value ='0')

				$wherecountry $wherestate $whereshopper $zipCond $where
				ORDER BY s.ordering,sr.shipping_rate_priority LIMIT 0,1";

		$db->setQuery($sql);
		$shippingrate = $db->loadObject();

		if ($shippingrate)
		{
			if ($shippingrate->shipping_rate_ordertotal_start > $order_subtotal)
			{
				$diff = $shippingrate->shipping_rate_ordertotal_start - $order_subtotal;
				$text = sprintf(JText::_('COM_REDSHOP_SHIPPING_TEXT_LBL'), $productHelper->getProductFormattedPrice($diff));
			}

			elseif ($shippingrate->shipping_rate_ordertotal_start <= $order_subtotal
				&& ($shippingrate->shipping_rate_ordertotal_end == 0 || $shippingrate->shipping_rate_ordertotal_end >= $order_subtotal))
			{
				$text = JText::_('COM_REDSHOP_FREE_SHIPPING_RATE_IS_IN_USED');
			}

			else
			{
				$text = JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
			}
		}

		return $text;
	}
}
