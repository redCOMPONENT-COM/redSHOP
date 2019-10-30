<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * Event onPrePayment Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element  plugin name
	 * @param   array   $data     data params
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_banktransfer')
		{
			return false;
		}

		// Send the Order mail
		if (Redshop::getConfig()->get('ORDER_MAIL_AFTER'))
		{
			Redshop\Mail\Order::sendMail($data['order_id']);
		}

		return true;
	}
}
