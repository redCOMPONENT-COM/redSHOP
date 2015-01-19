<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_banktransfer extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		$tag = JFactory::getLanguage()->getTag();

		if ($element != 'rs_payment_banktransfer')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		// Send the Order mail
		if (ORDER_MAIL_AFTER)
		{
			JLoader::load('RedshopHelperAdminMail');
			$redshopMail = new redshopMail;
			$redshopMail->sendOrderMail($data['order_id']);
		}

		return true;
	}
}
