<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');

class plgredshop_paymentrs_payment_bbs extends JPlugin
{
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgredshop_paymentrs_payment_bbs(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_bbs');
		$this->_params = new JRegistry($this->_plugin->params);
	}

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

		$db = JFactory::getDbo();
		$request = JRequest::get('request');
		$order_id = $request['orderid'];
		JPlugin::loadLanguage('com_redshop');

		$amazon_parameters = $this->getparameters('rs_payment_bbs');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$access_id = $paymentparams->get('access_id', '');
		$token_id = $paymentparams->get('token_id', '');
		$is_test = $paymentparams->get('is_test', '');
		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');

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
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
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

		if ($this->_params->get("is_test") == "TRUE")
		{
			$bbsurl = "https://epayment-test.bbs.no/Netaxept/Process.aspx?";
			$bbsQueryurl = "https://epayment-test.bbs.no/Netaxept/Query.aspx?";
		}
		else
		{
			$bbsurl = "https://epayment.bbs.no/Netaxept/Process.aspx?";
			$bbsQueryurl = "https://epayment.bbs.no/Netaxept/Query.aspx?";
		}

		$bbsQueryurl .= "merchantId=" . urlencode($this->_params->get("access_id")) . "&token=" . urlencode($this->_params->get("token_id")) . "&transactionId=" . $data['order_transactionid'];

		// 	Create a curl handle to a non-existing location
		$ch = curl_init($bbsQueryurl);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$Query_data = curl_exec($ch);

		$Query_data = new SimpleXMLElement($Query_data);

		$data = $bbsurl . "merchantId=" . urlencode($this->_params->get("access_id")) . "&token=" . urlencode($this->_params->get("token_id")) . "&transactionId=" . $data['order_transactionid'] . "&transactionAmount=" . $Query_data->OrderInformation->Total . "&operation=CAPTURE";

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
