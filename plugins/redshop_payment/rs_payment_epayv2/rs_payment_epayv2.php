<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Epay Payment gateway for redSHOP Payments
 *
 * @since  1.4
 */
class PlgRedshop_PaymentRs_Payment_Epayv2 extends JPlugin
{
	/**
	 * Epay SOAP Client Object
	 *
	 * @var  object
	 *
	 * @since  1.4
	 */
	private $epaySoapClient;

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

		$currencyHelper = CurrencyHelper::getInstance();
		$app            = JFactory::getApplication();
		$itemId         = $app->input->getInt('Itemid', 0);

		$formData = array(
			'merchantnumber'  => $this->params->get("merchant_id"),
			'amount'          => number_format($data['carttotal'], 2, '.', '') * 100,
			'currency'        => $currencyHelper->get_iso_code(Redshop::getConfig()->get('CURRENCY_CODE')),
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
			$formData['group'] = $this->params->get('payment_group');
		}

		// Epay will send email receipt to given email
		if (trim($this->params->get('mailreceipt')))
		{
			$formData['mailreceipt'] = $this->params->get('mailreceipt');
		}

		if ($cardTypes = $this->params->get('paymenttype'))
		{
			// Remove ALL keyword
			$unsetIndex = array_search('ALL', $cardTypes);

			if ($unsetIndex !== false)
			{
				unset($cardTypes[$unsetIndex]);
			}

			$formData['paymenttype'] = implode(',', $cardTypes);
		}

		if ((int) $this->params->get('activate_callback', 0) == 1)
		{
			$formData['cancelurl']   = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=0&Itemid=' . $itemId;

			$formData['callbackurl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=1&Itemid=' . $itemId;

			$formData['accepturl']   = JURI::base() . 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $data['order_id'] . '&Itemid=' . $itemId;
		}
		else
		{
			$formData['cancelurl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=0&Itemid=' . $itemId;

			$formData['accepturl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=1&Itemid=' . $itemId;
		}

		// Create hash value to post
		$formData['hash'] = md5(implode("", array_values($formData)) . $this->params->get("epay_paymentkey"));

		// New Code
		$jsonPassString = json_encode($formData);

		require_once JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_epayv2');
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
		$request        = JFactory::getApplication()->input;
		$values         = new stdClass;

		$accept        = $request->get("accept", false);
		$tranId        = $request->get("txnid", 0, "int");
		$orderId       = $request->get("orderid", 0, "int");
		$orderEkey     = $request->get("hash", "");
		$orderCurrency = $request->get("currency");
		$transFee      = $request->get("txnfee") / 100;

		JPlugin::loadLanguage('com_redshop');

		$verifyStatus   = $this->params->get('verify_status', '');
		$invalidStatus  = $this->params->get('invalid_status', '');
		$epayPaymentKey = $this->params->get('epay_paymentkey', '');
		$epayMd5        = $this->params->get('epay_md5', '');

		$var = "";

		foreach ($request as $key => $value)
		{
			if ($key != "hash")
			{
				$var .= $value;
			}
		}

		// Generated Hash
		$genStamp = md5($var . $epayPaymentKey);

		// Now validat on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled

		if (($orderEkey == $genStamp) || $epayMd5 == 0)
		{
			// Switch on the order accept code
			// accept = 1 (standard redirect) accept = 2 (callback)

			if (empty($request['errorcode']) && ($accept == "1" || $accept == "2"))
			{
				// Only update the order information once
				if ($this->orderPaymentNotYetUpdated($orderId, $tranId))
				{
					// UPDATE THE ORDER STATUS to 'VALID'
					$values->order_status_code         = $verifyStatus;
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
						$cardName = "Unknown";

						switch ($_REQUEST["cardid"])
						{
							case 1:
								$cardName = 'Dankort (DK)';
								break;
							case 2:
								$cardName = 'Visa/Dankort (DK)';
								break;
							case 3:
								$cardName = 'Visa Electron (Udenlandsk)';
								break;
							case 4:
								$cardName = 'Mastercard (DK)';
								break;
							case 5:
								$cardName = 'Mastercard (Udenlandsk)';
								break;
							case 6:
								$cardName = 'Visa Electron (DK)';
								break;
							case 7:
								$cardName = 'JCB (Udenlandsk)';
								break;
							case 8:
								$cardName = 'Diners (DK)';
								break;
							case 9:
								$cardName = 'Maestro (DK)';
								break;
							case 10:
								$cardName = 'American Express (DK)';
								break;
							case 11:
								$cardName = 'Ukendt';
								break;
							case 12:
								$cardName = 'eDankort (DK)';
								break;
							case 13:
								$cardName = 'Diners (Udenlandsk)';
								break;
							case 14:
								$cardName = 'American Express (Udenlandsk)';
								break;
							case 15:
								$cardName = 'Maestro (Udenlandsk)';
								break;
							case 16:
								$cardName = 'Forbrugsforeningen (DK)';
								break;
							case 17:
								$cardName = 'eWire';
								break;
							case 18:
								$cardName = 'VISA';
								break;
							case 19:
								$cardName = 'IKANO';
								break;
							case 20:
								$cardName = 'Andre';
								break;
							case 21:
								$cardName = 'Nordea';
								break;
							case 22:
								$cardName = 'Danske Bank';
								break;
							case 23:
								$cardName = 'Danske Bank';
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
				$values->order_status_code         = $invalidStatus;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg                               = JText::_('COM_REDSHOP_EPAY_PAYMENT_ERROR');
			}
			else
			{
				$values->order_status_code         = $invalidStatus;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			}
		}
		else
		{
			$values->order_status_code         = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}

		$values->transaction_id = $tranId;
		$values->order_id       = $orderId;
		$values->transfee       = $transFee;

		return $values;
	}

	/**
	 * Check Order payment is set for specific transaction Id
	 *
	 * @param   integer  $orderId  Order Id
	 * @param   string   $tranId   Payment Transaction Id from payment gateway
	 *
	 * @return  boolean  True is not found any order with passed transaction id.
	 */
	public function orderPaymentNotYetUpdated($orderId, $tranId)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('count(*)')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId)
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->q($tranId));

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
		if (!$this->epaySoapClient)
		{
			try
			{
				$this->epaySoapClient = new SoapClient(
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

		return $this->epaySoapClient;
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
