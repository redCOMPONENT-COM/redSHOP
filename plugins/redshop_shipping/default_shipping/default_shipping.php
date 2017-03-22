<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */
JLoader::import('redshop.library');

class  plgredshop_shippingdefault_shipping extends JPlugin
{
	public $payment_code = "default_shipping";

	public $classname = "default_shipping";

	public function onShowconfig()
	{
		return true;
	}

	function onWriteconfig($values)
	{
		return true;
	}

	function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$shippingrate = array();
		$rate = 0;
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		$shippingArr = $shippinghelper->getShopperGroupDefaultShipping();

		if (!empty($shippingArr))
		{
			$shopper_shipping = $shippingArr['shipping_rate'];
			$shippingVatRate = $shippingArr['shipping_vat'];
			$default_shipping = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_SHIPPING');
			$shopper_shipping_id = RedshopShippingRate::encrypt(
									array(
										__CLASS__,
										$shipping->name,
										$default_shipping,
										number_format($shopper_shipping, 2, '.', ''),
										$default_shipping,
										'single',
										$shippingVatRate,
										'0',
										'1'
									)
								);

			$shippingrate[$rate] = new stdClass;
			$shippingrate[$rate]->text = $default_shipping;
			$shippingrate[$rate]->value = $shopper_shipping_id;
			$shippingrate[$rate]->rate = $shopper_shipping;
			$rate++;
		}

		$ratelist = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);

		for ($i = 0, $in = count($ratelist); $i < $in; $i++)
		{
			$rs                      = $ratelist[$i];
			$shippingRate            = $rs->shipping_rate_value;
			$rs->shipping_rate_value = $shippinghelper->applyVatOnShippingRate($rs, $d);
			$shippingVatRate         = $rs->shipping_rate_value - $shippingRate;
			$economic_displaynumber  = $rs->economic_displaynumber;
			$shipping_rate_id        = RedshopShippingRate::encrypt(
										array(
											__CLASS__ ,
											$shipping->name ,
											$rs->shipping_rate_name ,
											number_format($rs->shipping_rate_value, 2, '.', '') ,
											$rs->shipping_rate_id ,
											'single',
											$shippingVatRate,
											$economic_displaynumber,
											$rs->deliver_type
										)
									);

			$shippingrate[$rate]        = new stdClass;
			$shippingrate[$rate]->text  = $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate  = $rs->shipping_rate_value;
			$shippingrate[$rate]->vat   = $shippingVatRate;
			$rate++;
		}

		return $shippingrate;
	}
}
