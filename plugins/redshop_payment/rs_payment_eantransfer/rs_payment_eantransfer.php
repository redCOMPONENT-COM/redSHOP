<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_eantransfer extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_eantransfer')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
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
