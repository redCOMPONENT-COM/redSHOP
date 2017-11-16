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
 * Epay Payment gateway for redSHOP Payments
 *
 * @since  1.4
 */
class PlgRedshop_Paymentrs_Payment_Epayv2 extends JPlugin
{
	/**
	 * Epay SOAP Client Object
	 *
	 * @var  object
	 *
	 * @since  1.4
	 */
	private $_epaySoapClient;

	/**
	 * Prepare payment data to send Epay
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $data     Order Information
	 *
	 * @return  void    Set HTML on fly
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_epayv2')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}


		$producthelper  = productHelper::getInstance();
		$uri            = JURI::getInstance();
		$url            = $uri->root();
		$user           = JFactory::getUser();
		$app            = JFactory::getApplication();
		$itemId         = $app->input->getInt('Itemid', 0);

		$formdata = array(
			'merchantnumber'  => $this->params->get("merchant_id"),
			'amount'          => number_format($data['carttotal'], 2, '.', '') * 100,
			'currency'        => RedshopHelperCurrency::getISOCode(Redshop::getConfig()->get('CURRENCY_CODE')),
			'orderid'         => $data['order_id'],
			'instantcapture'  => $this->params->get("auth_type"),
			'instantcallback' => 1,
			'language'        => $this->params->get("language"),
			'windowstate'     => $this->params->get("epay_window_state"),
			'windowid'        => $this->params->get("windowid"),
			'ownreceipt'      => $this->params->get("ownreceipt"),
			'googletracker'   => $this->params->get('googletracker', '')
		);

		// Payment Group is an optional
		if ($this->params->get('payment_group'))
		{
			$formdata['group'] = $this->params->get('payment_group');
		}

		// Epay will send email receipt to given email
		if (trim($this->params->get('mailreceipt')))
		{
			$formdata['mailreceipt'] = $this->params->get('mailreceipt');
		}

		if ($cardTypes = $this->params->get('paymenttype'))
		{
			// Remove ALL keyword
			$unsetIndex = array_search('ALL', $cardTypes);

			if ($unsetIndex !== false)
			{
				unset($cardTypes[$unsetIndex]);
			}

			$formdata['paymenttype'] = implode(',', $cardTypes);
		}

		if ((int) $this->params->get('activate_callback', 0) == 1)
		{
			$formdata['cancelurl']   = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=0&Itemid=' . $itemId;
			$formdata['callbackurl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=1&Itemid=' . $itemId;
			$formdata['accepturl']   = JURI::base() . 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $data['order_id'] . '&Itemid=' . $itemId;
		}
		else
		{
			$formdata['cancelurl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=0&Itemid=' . $itemId;
			$formdata['accepturl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=1&Itemid=' . $itemId;
		}

		// Create hash value to post
		$formdata['hash'] = md5(implode("", array_values($formdata)) . $this->params->get("epay_paymentkey"));

		// New Code
		$json_pass_string = json_encode($formdata);

		$html = '';
		$html .= '<script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript"></script>';
		$html .= '<div id="payment-div"></div>';
		$html .= '<script type="text/javascript">';
			$html .= 'paymentwindow = new PaymentWindow(' . $json_pass_string . ');';
			$html .= 'paymentwindow.append(\'payment-div\');';
			$html .= 'paymentwindow.open();';
		$html .= '</script>';
		$html .= '<input onclick="javascript: paymentwindow.open()" type="button" value="Go to payment">';

		echo $html;
	}

	/**
	 * Handle Payment notification from Epay
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $request  Request data sent from Epay
	 *
	 * @return  object  Status Object
	 */
	public function onNotifyPaymentrs_payment_epayv2($element, $request)
	{
		if ($element != 'rs_payment_epayv2')
		{
			return false;
		}

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$values          = new stdClass;

		$accept         = $request["accept"];
		$tid            = $request["txnid"];
		$order_id       = $request["orderid"];
		$Itemid         = $request["Itemid"];
		$order_amount   = $request["amount"];
		$order_ekey     = $request["hash"];
		$error          = $request["error"];
		$order_currency = $request["currency"];
		$transfee       = $request["txnfee"];
		$transfee       = $transfee / 100;

		JPlugin::loadLanguage('com_redshop');

		$verify_status   = $this->params->get('verify_status', '');
		$invalid_status  = $this->params->get('invalid_status', '');
		$auth_type       = $this->params->get('auth_type', '');
		$epay_paymentkey = $this->params->get('epay_paymentkey', '');
		$epay_md5        = $this->params->get('epay_md5', '');

		$var = "";

		foreach ($request as $key => $value)
		{
			if ($key != "hash")
			{
				$var .= $value;
			}
		}

		// Generated Hash
		$genstamp = md5($var . $epay_paymentkey);

		// Now validat on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled

		if (($order_ekey == $genstamp) || $epay_md5 == 0)
		{
			// Switch on the order accept code
			// accept = 1 (standard redirect) accept = 2 (callback)

			if (empty($request['errorcode']) && ($accept == "1" || $accept == "2"))
			{
				// Only update the order information once
				if ($this->orderPaymentNotYetUpdated($order_id, $tid))
				{
					// UPDATE THE ORDER STATUS to 'VALID'
					$transaction_id                    = $tid;
					$values->order_status_code         = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
					$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');

					// Add history callback info
					if ($accept == "2")
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CALLBACK');
					}

					// Payment fee
					if ($request["transfee"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_FEE');
					}

					// Payment date
					if ($request["date"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_DATE');
					}

					// Payment fraud control
					if (@$request["fraud"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_FRAUD');
					}

					// Card id
					if ($request["cardid"])
					{
						$cardname = "Unknown";
						$cardimage = "c" . $_REQUEST["cardid"] . ".gif";

						switch ($_REQUEST["cardid"])
						{
							case 1:
								$cardname = 'Dankort (DK)';
								break;
							case 2:
								$cardname = 'Visa/Dankort (DK)';
								break;
							case 3:
								$cardname = 'Visa Electron (Udenlandsk)';
								break;
							case 4:
								$cardname = 'Mastercard (DK)';
								break;
							case 5:
								$cardname = 'Mastercard (Udenlandsk)';
								break;
							case 6:
								$cardname = 'Visa Electron (DK)';
								break;
							case 7:
								$cardname = 'JCB (Udenlandsk)';
								break;
							case 8:
								$cardname = 'Diners (DK)';
								break;
							case 9:
								$cardname = 'Maestro (DK)';
								break;
							case 10:
								$cardname = 'American Express (DK)';
								break;
							case 11:
								$cardname = 'Ukendt';
								break;
							case 12:
								$cardname = 'eDankort (DK)';
								break;
							case 13:
								$cardname = 'Diners (Udenlandsk)';
								break;
							case 14:
								$cardname = 'American Express (Udenlandsk)';
								break;
							case 15:
								$cardname = 'Maestro (Udenlandsk)';
								break;
							case 16:
								$cardname = 'Forbrugsforeningen (DK)';
								break;
							case 17:
								$cardname = 'eWire';
								break;
							case 18:
								$cardname = 'VISA';
								break;
							case 19:
								$cardname = 'IKANO';
								break;
							case 20:
								$cardname = 'Andre';
								break;
							case 21:
								$cardname = 'Nordea';
								break;
							case 22:
								$cardname = 'Danske Bank';
								break;
							case 23:
								$cardname = 'Danske Bank';
								break;
						}

						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CARDTYPE');
					}

					// Creation information
					$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_LOG_TID');
					$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_TRANSACTION_SUCCESS');
				}
			}
			elseif ($accept == "0")
			{
				$values->order_status_code         = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg                               = JText::_('COM_REDSHOP_EPAY_PAYMENT_ERROR');
			}
			else
			{
				$values->order_status_code         = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			}
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}

		$values->transaction_id = $tid;
		$values->order_id       = $order_id;
		$values->transfee       = $transfee;

		return $values;
	}

	/**
	 * Check Order payment is set for specific transaction Id
	 *
	 * @param   integer  $orderId  Order Id
	 * @param   string   $tid      Payment Transaction Id from payment gateway
	 *
	 * @return  boolean  True is not found any order with passed transaction id.
	 */
	public function orderPaymentNotYetUpdated($orderId, $tid)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('count(*)')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId)
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->q($tid));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$orderPayment = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$res = false;

		if ($orderPayment == 0)
		{
			$res = true;
		}

		return $res;
	}

	/**
	 * Capture Confirmed payment
	 *
	 * @param   string  $element  Plugin name
	 * @param   array   $data     Order Information
	 *
	 * @return  object  Status information object.
	 *                  'message' and 'responsestatus' as a key.
	 *                  'type' = 'error' or 'message'.
	 */
	public function onCapture_Paymentrs_payment_epayv2($element, $data)
	{
		// Capture Paramteres
		$captureParams = array(
			'merchantnumber' => (int) $this->params->get('merchant_id'),
			'transactionid'  => $data['order_transactionid'],
			'amount'         => (int) round($data['order_amount'] * 100, 2),
			'epayresponse'   => -1,
			'pbsResponse'    => -1
		);

		if ($paymentGroup = $this->params->get('payment_group', false))
		{
			$captureParams['group'] = $paymentGroup;
		}

		// Capturing payment
		$captureResult = $this->_getEpaySoapClient()->capture($captureParams);

		$app    = JFactory::getApplication();
		$values = new stdClass;

		if ($captureResult->captureResult == true)
		{
			$values->responsestatus = 'Success';
			$values->type           = 'message';
			$values->message        = JText::_('COM_REDSHOP_ORDER_CAPTURED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$values->type           = 'error';
			$values->message        = JText::_('COM_REDSHOP_ORDER_NOT_CAPTURED')
									. '<br />' . $this->_getEpayError($captureResult->epayresponse)
									. '<br />' . $this->_getPbsError($captureResult->pbsResponse);
		}

		$app->enqueueMessage($values->message, $values->type);

		return $values;
	}

	/**
	 * Method triggers on status change from shop
	 *
	 * @param   string  $element  Plugin name
	 * @param   array   $data     Order Information
	 *
	 * @return  object  Status information object.
	 *                  'message' and 'responsestatus' as a key.
	 *                  'type' = 'error' or 'message'.
	 */
	public function onStatus_Paymentrs_payment_epayv2($element, $data)
	{
		$app             = JFactory::getApplication();
		$values          = new stdClass;
		$transactionInfo = $this->_getEpaySoapClient()
								->gettransaction(
									array(
										'merchantnumber' => (int) $this->params->get('merchant_id'),
										'transactionid'  => $data['order_transactionid'],
										'epayresponse'   => -1,
										'pbsresponse'    => -1
									)
								);

		if (1 == $transactionInfo->gettransactionResult)
		{
			if ("PAYMENT_NEW" == $transactionInfo->transactionInformation->status)
			{
				$values = $this->onCancel_Paymentrs_payment_epayv2($element, $data);
			}
			elseif ("PAYMENT_CAPTURED" == $transactionInfo->transactionInformation->status)
			{
				$values = $this->onRefund_Paymentrs_payment_epayv2($element, $data);
			}
		}
		else
		{
			$values->responsestatus = 'Fail';
			$values->type           = 'error';
			$values->message        = JText::_('COM_REDSHOP_ORDER_NOT_REFUND')
									. '<br />' . $this->_getEpayError($transactionInfo->epayresponse);
		}

		$app->enqueueMessage($values->message, $values->type);

		return $values;
	}

	/**
	 * On Cancel Payment status Remove Epay Order to maintain duplicate order issue
	 *
	 * @param   string  $element  Plugin name
	 * @param   array   $data     Order Information
	 *
	 * @return  object  Status information object.
	 *                  'message' and 'responsestatus' as a key.
	 *                  'type' = 'error' or 'message'.
	 */
	public function onCancel_Paymentrs_payment_epayv2($element, $data)
	{
		$deleteResponse = $this->_getEpaySoapClient()
								->delete(
									array(
										'merchantnumber' => (int) $this->params->get('merchant_id'),
										'transactionid'  => $data['order_transactionid'],
										'epayresponse'   => -1,
										'pbsresponse'    => -1
									)
								);

		$values = new stdClass;

		if ($deleteResponse->deleteResult)
		{
			$values->responsestatus = 'Success';
			$values->type           = 'message';
			$values->message = JText::_('COM_REDSHOP_ORDER_REFUND');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$values->type           = 'error';
			$values->message        = JText::_('COM_REDSHOP_ORDER_NOT_REFUND')
									. '<br />' . $this->_getEpayError($deleteResponse->epayresponse);
		}

		return $values;
	}

	/**
	 * Refund money to customer if payment set as a refund in shop
	 *
	 * @param   string  $element  Plugin name
	 * @param   array   $data     Order Information
	 *
	 * @return  object  Status information object.
	 *                  'message' and 'responsestatus' as a key.
	 *                  'type' = 'error' or 'message'.
	 */
	public function onRefund_Paymentrs_payment_epayv2($element, $data)
	{
		$creditResponse = $this->_getEpaySoapClient()
								->credit(
									array(
										'merchantnumber' => (int) $this->params->get('merchant_id'),
										'transactionid'  => $data['order_transactionid'],
										'amount'         => round($data['order_amount'] * 100, 2),
										'epayresponse'   => -1,
										'pbsresponse'    => -1
									)
								);

		$values = new stdClass;

		if ($creditResponse->creditResult)
		{
			$values->responsestatus = 'Success';
			$values->type           = 'message';
			$values->message = JText::_('COM_REDSHOP_ORDER_REFUND');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$values->type           = 'error';
			$values->message        = JText::_('COM_REDSHOP_ORDER_NOT_REFUND')
									. '<br />' . $this->_getEpayError($creditResponse->epayresponse)
									. '<br />' . $this->_getPbsError($creditResponse->pbsResponse);
		}

		return $values;
	}

	/**
	 * Epay Soal Client setup
	 *
	 * @param   integer  $trace  Trace
	 *
	 * @return  SoapObject   Soap Client Information Object
	 */
	private function _getEpaySoapClient($trace = 0)
	{
		if (!$this->_epaySoapClient)
		{
			try
			{
				$this->_epaySoapClient = new SoapClient(
					'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL',
					array(
						'trace' => $trace
					)
				);
			}
			catch (RuntimeException $e)
			{
				throw new RuntimeException($e->getMessage(), $e->getCode());
			}
		}

		return $this->_epaySoapClient;
	}

	/**
	 * Get Epay Error in Human Readable format
	 *
	 * @param   integer  $epayResponse  Epay Response Code
	 *
	 * @return  string   Epay Response Error Message
	 */
	private function _getEpayError($epayResponse)
	{
		$language = 1;

		if ('en-GB' == JFactory::getLanguage()->get('tag'))
		{
			$language = 2;
		}

		$response = $this->_getEpaySoapClient()
					->getEpayError(
						array(
							'merchantnumber'   => (int) $this->params->get('merchant_id'),
							'language'         => $language,
							'epayresponsecode' => $epayResponse,
							'epayresponse'     => -1,
							'pbsresponse'      => -1
						)
					);

		if ($response->getEpayErrorResult)
		{
			return $response->epayresponsestring;
		}

		return $epayResponse;
	}

	/**
	 * Get PBS Error in Human Readable format
	 *
	 * @param   integer  $pbsResponse  PBS Response Code
	 *
	 * @return  string   PBS Response Error Message
	 */
	private function _getPbsError($pbsResponse)
	{
		$language = 1;

		if ('en-GB' == JFactory::getLanguage()->get('tag'))
		{
			$language = 2;
		}

		$response = $this->_getEpaySoapClient()
					->getPbsError(
						array(
							'merchantnumber'  => (int) $this->params->get('merchant_id'),
							'language'        => $language,
							'pbsresponsecode' => (int) $pbsResponse,
							'epayresponse'    => -1,
							'pbsresponse'     => -1
						)
					);

		if ($response->getPbsErrorResult)
		{
			return $response->pbsResponseString;
		}

		return $pbsResponse;
	}
}
