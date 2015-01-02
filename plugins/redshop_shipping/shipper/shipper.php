<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminShipping');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */
class plgredshop_shippingshipper extends JPlugin
{
	var $payment_code = "shipper";
	var $classname = "shipper";

	function onShowconfig()
	{
		return true;
	}

	function onWriteconfig($values)
	{
		return true;
	}

	function onListRates(&$d)
	{
		$shippinghelper = new shipping;
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);

		$shippingrate = array();
		$rate = 0;

		$ratelist = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);

		for ($i = 0; $i < count($ratelist); $i++)
		{
			$rs = $ratelist[$i];
			$shippingRateInt = $rs->shipping_rate_value;
			$rs->shipping_rate_value = $shippinghelper->applyVatOnShippingRate($rs, $d['user_id']);
			$shippingVatRate = $rs->shipping_rate_value - $shippingRateInt;
			$economic_displaynumber = $rs->economic_displaynumber;
			$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $rs->shipping_rate_name . "|" . number_format($rs->shipping_rate_value, 2, '.', '') . "|" . $rs->shipping_rate_id . "|single|" . $shippingVatRate . '|' . $economic_displaynumber);
			$shippingrate[$rate]->text = $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate = $rs->shipping_rate_value;
			$shippingrate[$rate]->vat = $shippingVatRate;
			$rate++;
		}

		return $shippingrate;
	}
}

?>
