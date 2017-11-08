<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
JLoader::import('redshop.library');

/**
 * Plgredshop_Shippingshipper
 *
 * @since  1.5
 */
class Plgredshop_Shippingshipper extends JPlugin
{
	public $payment_code = "shipper";

	public $classname = "shipper";

	/**
	 * onShowconfig
	 *
	 * @return  bool
	 */
	public function onShowconfig()
	{
		return true;
	}

	/**
	 * onWriteconfig
	 *
	 * @param   array  $values  Values
	 *
	 * @return  bool
	 */
	public function onWriteconfig($values)
	{
		return true;
	}

	/**
	 * onListRates
	 *
	 * @param   array  &$d  Array values
	 *
	 * @return array
	 */
	public function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);

		$shippingrate = array();

		$ratelist = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);

		if ($ratelist)
		{
			foreach ($ratelist as $rs)
			{
				$shippingRateInt = $rs->shipping_rate_value;
				$rs->shipping_rate_value = $shippinghelper->applyVatOnShippingRate($rs, $d);
				$shippingVatRate = $rs->shipping_rate_value - $shippingRateInt;
				$economic_displaynumber = $rs->economic_displaynumber;
				$shipping_rate_id = RedshopShippingRate::encrypt(
										array(
											__CLASS__,
											$shipping->name,
											$rs->shipping_rate_name,
											number_format($rs->shipping_rate_value, 2, '.', ''),
											$rs->shipping_rate_id,
											'single',
											$shippingVatRate,
											$economic_displaynumber
										)
									);

				$oneShippingRate = new stdClass;
				$oneShippingRate->text = $rs->shipping_rate_name;
				$oneShippingRate->value = $shipping_rate_id;
				$oneShippingRate->rate = $rs->shipping_rate_value;
				$oneShippingRate->vat = $shippingVatRate;
				$shippingrate[] = $oneShippingRate;
			}
		}

		return $shippingrate;
	}
}
