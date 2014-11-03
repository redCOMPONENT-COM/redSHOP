<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_2checkout extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_2checkout')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
		include $paymentpath;
	}

	public function onNotifyPaymentrs_payment_2checkout($element, $request)
	{
		if ($element != 'rs_payment_2checkout')
		{
			return;
		}

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$order_id       = $request['merchant_order_id'];
		$invoice_id     = $request['invoice_id'];
		$is_test        = $this->params->get("is_test");
		$vendor_id      = $this->params->get("vendor_id");
		$verify_status  = $this->params->get("verify_status");
		$invalid_status = $this->params->get("invalid_status");
		$secret_words   = $this->params->get("secret_words");
		$order_amount   = $request["total"];
		$order_ekey     = $request["key"];
		$accept         = $_REQUEST["sid"];
		$Itemid         = $request["Itemid"];

		// Load plugin language
		JPlugin::loadLanguage('com_redshop');

		// Get value from xml file
		$string_to_hash = $secret_words;

		// This should be YOUR vendor number
		$string_to_hash .= $request['sid'];

		// Append the order number, in this script it will always be 1
		if ($is_test)
		{
			$string_to_hash .= 1;
		}
		else
		{
			$string_to_hash .= $request['order_number'];
		}

		// Append the sale total
		$string_to_hash .= $request["total"];

		// Get a md5 hash of the string, uppercase it to match the returned key
		$hash_to_check = strtoupper(md5($string_to_hash));

		// Now validate on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		if ($order_ekey === $hash_to_check)
		{
			$transaction_id                    = $invoice_id;
			$values->order_status_code         = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->transaction_id            = $transaction_id;
			$values->order_id                  = $order_id;
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}

		return $values;
	}

	public function onCapture_Paymentrs_payment_2checkout($element, $data)
	{
		return;
	}
}
