<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 *  PlgRedshop_PaymentRs_Payment_BankTransfer installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_BankTransfer extends JPlugin
{
	/**
	 * [onPrePayment Plugin method with the same name as the event will be called automatically.]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [bool]
	 */
	public function onPrePayment($element, $data)
	{
		return true;
	}
}
