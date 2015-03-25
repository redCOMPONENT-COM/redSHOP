<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_ewayuk extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_payment_rs_payment_ewayuk', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_ewayuk')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_ewayuk($element, $request)
	{
		if ($element != 'rs_payment_ewayuk')
		{
			return;
		}

		$UserName = $this->params->get('username', '');
		$CustomerID = $this->params->get('customer_id', '');
		$querystring = "CustomerID=" . $CustomerID . "&UserName=" . $UserName . "&AccessPaymentCode=" . $request['AccessPaymentCode'];
		$posturl = "https://payment.ewaygateway.com/Result/?" . $querystring;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$response = curl_exec($ch);

		$responsecode = $this->fetch_data($response, '<responsecode>', '</responsecode>');
		$auth_code = $this->fetch_data($response, '<authcode>', '</authcode>');
		$order_id = $this->fetch_data($response, '<merchantoption1>', '</merchantoption1>');
		$trxnresponsemessage = $this->fetch_data($response, '<trxnresponsemessage>', '</trxnresponsemessage>');
		$values = new stdClass;
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$debug_mode = $this->params->get('debug_mode', 0);

		// Response Success Message
		if ($responsecode == "00" || $responsecode == "08" || $responsecode == "10" || $responsecode == "11" || $responsecode == "16")
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';

			if ($debug_mode == 1)
			{
				$values->log = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_PLACED') . "  " . $trxnresponsemessage;
				$values->msg = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_PLACED') . "  " . $trxnresponsemessage;
			}
			else
			{
				$values->log = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_PLACED');
				$values->msg = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_PLACED');
			}

			$values->order_id = $order_id;
			$values->transaction_id = $auth_code;
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';

			if ($debug_mode == 1)
			{
				$values->log = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_NOT_PLACED') . "  " . $trxnresponsemessage;
				$values->msg = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_NOT_PLACED') . "  " . $trxnresponsemessage;
			}
			else
			{
				$values->log = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_NOT_PLACED');
				$values->msg = JText::_('PLG_RS_PAYMENT_EWAYUK_ORDER_NOT_PLACED');
			}

			$values->order_id = $order_id;
			$values->transaction_id = '';
		}

		return $values;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
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

	public function onCapture_Paymentrs_payment_ewayuk($element, $data)
	{
		return;
	}

	public function fetch_data($string, $start_tag, $end_tag)
	{
		$position = stripos($string, $start_tag);

		$str = substr($string, $position);

		$str_second = substr($str, strlen($start_tag));

		$second_positon = stripos($str_second, $end_tag);

		$str_third = substr($str_second, 0, $second_positon);

		$fetch_data = trim($str_third);

		return $fetch_data;
	}
}
