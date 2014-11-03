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

class plgredshop_paymentrs_payment_bbs extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_bbs')
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

	public function onNotifyPaymentrs_payment_bbs($element, $request)
	{
		if ($element != 'rs_payment_bbs')
		{
			return;
		}

		$db                = JFactory::getDbo();
		$request           = JRequest::get('request');
		$order_id          = $request['orderid'];

		JPlugin::loadLanguage('com_redshop');

		$access_id         = $this->params->get('access_id', '');
		$token_id          = $this->params->get('token_id', '');
		$is_test           = $this->params->get('is_test', '');
		$verify_status     = $this->params->get('verify_status', '');
		$invalid_status    = $this->params->get('invalid_status', '');
		$auth_type         = $this->params->get('auth_type', '');

		if ($is_test == "TRUE")
		{
			$bbsurl = "https://epayment-test.bbs.no/Netaxept/Process.aspx?";
		}
		else
		{
			$bbsurl = "https://epayment.bbs.no/Netaxept/Process.aspx?";
		}

		$bbsurl .= "merchantId=" . urlencode($access_id) . "&token=" . urlencode($token_id) . "&transactionId=" . $request["transactionId"] . "&operation=" . $auth_type;
		$data = $bbsurl;
		$ch = curl_init($data);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		$xml = new SimpleXMLElement($data);

		$AUTH_Responsecode = isset($xml->ResponseCode) ? $xml->ResponseCode : $xml->Result->ResponseCode;

		$BBS_msg = isset($xml->Result->ResponseText) ? $xml->Result->ResponseText : $BBS_msg;

		$objOrder = new order_functions;

		if (strtoupper($AUTH_Responsecode) == 'OK')
		{
			$tid = isset($xml->Result->transactionid) ? $xml->Result->transactionid : $request["transactionId"];

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
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transaction_id;
		$values->order_id = $order_id;

		return $values;
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_bbs($element, $data)
	{
		if ($element != 'rs_payment_bbs')
		{
			return;
		}

		$order_id = $data['order_id'];
		$objOrder = new order_functions;
		$db = JFactory::getDbo();

		if ($this->params->get("is_test") == "TRUE")
		{
			$bbsurl = "https://epayment-test.bbs.no/Netaxept/Process.aspx?";
			$bbsQueryurl = "https://epayment-test.bbs.no/Netaxept/Query.aspx?";
		}
		else
		{
			$bbsurl = "https://epayment.bbs.no/Netaxept/Process.aspx?";
			$bbsQueryurl = "https://epayment.bbs.no/Netaxept/Query.aspx?";
		}

		$bbsQueryurl .= "merchantId=" . urlencode($this->params->get("access_id")) . "&token=" . urlencode($this->params->get("token_id")) . "&transactionId=" . $data['order_transactionid'];

		// 	Create a curl handle to a non-existing location
		$ch = curl_init($bbsQueryurl);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$Query_data = curl_exec($ch);

		$Query_data = new SimpleXMLElement($Query_data);

		$data = $bbsurl . "merchantId=" . urlencode($this->params->get("access_id")) . "&token=" . urlencode($this->params->get("token_id")) . "&transactionId=" . $data['order_transactionid'] . "&transactionAmount=" . $Query_data->OrderInformation->Total . "&operation=CAPTURE";

		// 	Create a curl handle to a non-existing location
		$ch = curl_init($data);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$CAPT_data = curl_exec($ch);

		$CAPT_xml = new SimpleXMLElement($CAPT_data);

		$CAPT_Responsecode = $CAPT_xml->ResponseCode;

		$BBS_msg = isset($CAPT_xml->Result->ResponseText) ? $CAPT_xml->Result->ResponseText : $BBS_msg;

		if ($CAPT_Responsecode == 'OK')
		{
			$values->responsestatus = 'Success';
			$message = $BBS_msg ? $BBS_msg : JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $BBS_msg ? $BBS_msg : JText::_('COM_REDSHOP_TRANSACTION_DECLINE');
		}

		$values->message = $message;

		return $values;
	}
}
