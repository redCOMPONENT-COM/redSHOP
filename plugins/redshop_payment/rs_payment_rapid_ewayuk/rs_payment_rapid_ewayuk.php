<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_rapid_ewayuk extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_rapid_ewayuk')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	function onNotifyPaymentrs_payment_rapid_ewayuk($element, $request)
	{
		if ($element != 'rs_payment_rapid_ewayuk')
		{
			return;
		}

		$db         = JFactory::getDbo();
		$AccessCode = $request["AccessCode"];

		JPlugin::loadLanguage('com_redshop');

		$verify_status   = $this->params->get('verify_status', '');
		$invalid_status  = $this->params->get('invalid_status', '');
		$auth_type       = $this->params->get('auth_type', '');
		$eWAYcustomer_id = $this->params->get("customer_id");
		$eWAYusername = $this->params->get("username");
		$eWAYpassword = $this->_params->get("password");
		$order_id     = $request['orderid'];

		// For transaction status
		$request = array(
			'Authentication' => array(
				'Username'   => $eWAYusername,
				'Password'   => $eWAYpassword,
				'CustomerID' => $eWAYcustomer_id,
			),
			'AccessCode'     => $AccessCode
		);

		try
		{
			$client = new SoapClient(
				"https://uk.ewaypayments.com/hotpotato/soap.asmx?WSDL",
				array(
					'trace'      => false,
					'exceptions' => true,
				)
			);
			$result = $client->GetAccessCodeResult(array('request' => $request));
		}
		catch (Exception $e)
		{
			$lblError = $e->getMessage();
		}

		$response = $result->GetAccessCodeResultResult;

		$values = new stdClass;

		if ($response->ResponseCode == 00)
		{
			$tid = $response->TransactionID;

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';

			if ($lblError != "")
			{
				$values->log = $lblError;
				$values->msg = $lblError;
			}
			else
			{
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) FROM #__redshop_order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_ewayuk($element, $data)
	{
		return;
	}
}
