<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  Redshop.Shipping
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Redshop shipping gateway for GLS Business rates.
 *
 * @since  1.2
 */
class PlgRedshop_ShippingDefault_Shipping_GLSBusiness extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	public $classname = "default_shipping_glsbusiness";

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_shipping_default_shipping_glsbusiness', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Method will trigger on listing shipping rates.
	 *
	 * @param   array  &$d  Order Information array
	 *
	 * @return  array       Shipping Rates in array
	 */
	public function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$shippingrate   = array();
		$rate           = 0;
		$shipping       = $shippinghelper->getShippingMethodByClass($this->classname);

		$ratelist       = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);
		$countRate      = count($ratelist);

		for ($i = 0; $i < $countRate; $i++)
		{
			$rs = $ratelist[$i];
			$shippingRate = $rs->shipping_rate_value;
			$rs->shipping_rate_value = $shippinghelper->applyVatOnShippingRate($rs, $d);
			$shippingVatRate = $rs->shipping_rate_value - $shippingRate;
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

			$shippingrate[$rate] = new stdClass;
			$shippingrate[$rate]->text 	= $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate 	= $rs->shipping_rate_value;
			$shippingrate[$rate]->vat = $shippingVatRate;
			$rate++;
		}

		return $shippingrate;
	}
}
