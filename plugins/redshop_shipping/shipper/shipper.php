<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */
include_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'shipping.php');
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