<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgRedshop_PaymentRs_Payment_EanTransfer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_EanTransfer extends JPlugin
{
	/**
	 * [onPrePayment]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [bool]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_eantransfer')
		{
			return;
		}

		// Send the Order mail
		if (Redshop::getConfig()->get('ORDER_MAIL_AFTER'))
		{
			$redshopMail = redshopMail::getInstance();
			$redshopMail->sendOrderMail($data['order_id']);
		}

		return true;
	}
}
