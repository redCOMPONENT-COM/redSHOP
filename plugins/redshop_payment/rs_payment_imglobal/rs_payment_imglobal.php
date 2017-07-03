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

/**
 * PlgRedshop_PaymentRs_Payment_ImGlobal installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_ImGlobal extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * [onPrePayment_rs_payment_imglobal ]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [obj]     $values
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

		$formData = array(
			'type'     => 'sale',
			'username' => $this->params->get("username"),
			'password' => $this->params->get("password"),
			'orderid'  => $data['order_number'],
			'amount'   => $data['order_total'],
			'ccnumber' => $ccdata['order_payment_number'],
			'cvv'      => $ccdata['credit_card_code'],
			'ccexp'    => ($ccdata['order_payment_expire_month']) . ($ccdata['order_payment_expire_year'])
		);
		$postString = '';

		foreach ($formData AS $key => $val)
		{
			$postString .= urlencode($key) . "=" . urlencode($val) . "&";
		}

		$postString = substr($postString, 0, -1);
		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, $url);
		curl_setopt($CR, CURLOPT_TIMEOUT, 30);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);

		if ($postString)
		{
			curl_setopt($CR, CURLOPT_POSTFIELDS, $postString);
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

	/**
	 * [onCapture_Paymentrs_payment_imglobal description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onCapture_Paymentrs_payment_imglobal($element, $data)
	{
		return;
	}
}
