<?php
/**
* @version		$Id: log.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! System Logging Plugin
 *
 * @package		Joomla
 * @subpackage	System
 */
include_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'shipping.php');
class  plgredshop_shippingdefault_shipping extends JPlugin
{
	var $payment_code = "default_shipping";
	var $classname = "default_shipping";

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.5
	 */
	function onShowconfig(){
		return true;
	}

	function onWriteconfig($values)
	{
		  return true;
	}
	
	function onListRates(&$d)
	{
		$shippinghelper = new shipping();
		$shippingrate = array();
		$rate = 0;
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		$shippingArr = $shippinghelper->getShopperGroupDefaultShipping();
		if(!empty($shippingArr) )
		{
			$shopper_shipping			= $shippingArr['shipping_rate'];
			$shippingVatRate			= $shippingArr['shipping_vat'];
			$default_shipping 			= JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_SHIPPING');
			$shopper_shipping_id 		= $shippinghelper->encryptShipping( __CLASS__."|".$shipping->name."|".$default_shipping."|".number_format( $shopper_shipping, 2, '.', '' )."|".$default_shipping."|single|".$shippingVatRate);
			$shippingrate[$rate]->text 	= $default_shipping;
			$shippingrate[$rate]->value = $shopper_shipping_id;
			$shippingrate[$rate]->rate 	= $shopper_shipping;
			$rate++;
		}

		$ratelist = $shippinghelper->listshippingrates($shipping->element,$d['users_info_id'],$d);
		for($i=0; $i<count($ratelist); $i++)
		{
			$rs = $ratelist[$i];
			$shippingRate 			 	= $rs->shipping_rate_value;
			$rs->shipping_rate_value 	= $shippinghelper->applyVatOnShippingRate($rs,$d['user_id']);
			$shippingVatRate		 	= $rs->shipping_rate_value - $shippingRate;
			$economic_displaynumber		= $rs->economic_displaynumber;
			$shipping_rate_id 			= $shippinghelper->encryptShipping( __CLASS__."|".$shipping->name."|".$rs->shipping_rate_name."|".number_format( $rs->shipping_rate_value, 2, '.', '' )."|".$rs->shipping_rate_id."|single|".$shippingVatRate.'|'.$economic_displaynumber) ;
			$shippingrate[$rate]->text 	= $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate 	= $rs->shipping_rate_value;
			$shippingrate[$rate]->vat 	= $shippingVatRate;
			$rate++;
		}
		return $shippingrate;
    }
}	?>