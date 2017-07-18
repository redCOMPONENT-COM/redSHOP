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

class plgRedshop_paymentrs_payment_imglobal extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_imglobal($element, $data)
	{
		if ($element != 'rs_payment_imglobal')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app      = JFactory::getApplication();
		$session  = JFactory::getSession();
		$ccdata   = $session->get('ccdata');
		$url      = "https://secure.imglobalpayments.com/api/transact.php";
		$urlParts = parse_url($url);

		if (!isset($urlParts['scheme']))
		{
			$urlParts['scheme'] = 'http';
		}

		$formdata = array(
			'type'     => 'sale',
			'username' => $this->params->get("username"),
			'password' => $this->params->get("password"),
			'orderid'  => $data['order_number'],
			'amount'   => $data['order_total'],
			'ccnumber' => $ccdata['order_payment_number'],
			'cvv'      => $ccdata['credit_card_code'],
			'ccexp'    => ($ccdata['order_payment_expire_month']) . ($ccdata['order_payment_expire_year'])
		);
		$poststring = '';

		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}

		$poststring = substr($poststring, 0, -1);
		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, $url);
		curl_setopt($CR, CURLOPT_TIMEOUT, 30);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);

		if ($poststring)
		{
			curl_setopt($CR, CURLOPT_POSTFIELDS, $poststring);
			curl_setopt($CR, CURLOPT_POST, 1);
		}

		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);

		if ($urlParts['scheme'] == 'https')
		{
			curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$result = curl_exec($CR);
		$error = curl_error($CR);
		curl_close($CR);

		parse_str($result, $output);
		$verify_status = $this->params->get("verify_status");
		$invalid_status = $this->params->get("invalid_status");

		if (!empty($output['response']))
		{
			if ($output['response'] == '1')
			{
				$message = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->responsestatus = 'Success';
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->responsestatus = 'Fail';
			}

			$values->transaction_id = $output['transactionid'];
			$values->order_id = $data['order_id'];
		}
		else
		{
			$message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->responsestatus = 'Fail';
			$values->transaction_id = 0;
		}

		$values->message = $message;

		return $values;
	}

	public function onCapture_Paymentrs_payment_imglobal($element, $data)
	{
		return;
	}
}
