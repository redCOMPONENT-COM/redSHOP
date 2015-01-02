<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_certitrade extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_certitrade')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_certitrade($element, $request)
	{
		if ($element != 'rs_payment_certitrade')
		{
			return;
		}

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$order_id       = $request['merchant_order_id'];
		$Itemid         = $request['Itemid'];
		$vendor_id      = $this->params->get("vendor_id");
		$verify_status  = $this->params->get("verify_status");
		$invalid_status = $this->params->get("invalid_status");
		$secret_words   = $this->params->get("secret_words");
		$order_amount   = $request["total"];
		$order_ekey     = $request["key"];
		$accept         = $_REQUEST["sid"];

		JPlugin::loadLanguage('com_redshop');

		// Get value from xml file
		$string_to_hash = $secret;

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
			// Find the corresponding order in the database

			$db = JFactory::getDbo();
			$qv = "SELECT order_id, order_number FROM #__redshop_orders WHERE order_id='" . $data['order_id'] . "'";
			$db->setQuery($qv);
			$orders = $db->LoadObjectList();

			foreach ($orders as $order_detail)
			{
				$d['order_id'] = $order_detail->order_id;
			}

			// Only update the order information once
			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				// UPDATE THE ORDER STATUS to 'VALID'
				$transaction_id                    = $tid;
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id            = $transaction_id;
				$values->order_id                  = $order_id;
			}
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}

		return $values;
	}

	public function onCapture_Paymentrs_payment_certitrade($element, $data)
	{
		return;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM #__redshop_order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}
}
