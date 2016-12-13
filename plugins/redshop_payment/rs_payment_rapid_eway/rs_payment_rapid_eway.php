<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class PlgRedshop_PaymentRs_Payment_Rapid_Eway
 *
 * @since  1.5
 */
class PlgRedshop_PaymentRs_Payment_Rapid_Eway extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.7.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element  Element name
	 * @param   array   $data     Array data values
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_rapid_eway')
		{
			return;
		}

		include_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * onNotifyPaymentrs_payment_rapid_eway
	 *
	 * @param   string  $element  Element name
	 * @param   array   $request  Array request values
	 *
	 * @return stdClass|void
	 */
	public function onNotifyPaymentrs_payment_rapid_eway($element, $request)
	{
		if ($element != 'rs_payment_rapid_eway')
		{
			return;
		}

		$AccessCode = $request["AccessCode"];
		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/RapidAPI.php';

		$eway_params = array('sandbox' => $this->params->get('test_mode', true));
		$service = new eWAY\RapidAPI($this->params->get("APIKey"), $this->params->get("APIPassword"), $eway_params);

		$req             = new eWAY\GetAccessCodeResultRequest;
		$req->AccessCode = $AccessCode;
		$result          = $service->GetAccessCodeResult($req);
		$orderId         = $request['orderid'];
		$lblError        = '';

		// Check if any error returns
		if (isset($result->Errors))
		{
			// Get Error Messages from Error Code. Error Code Mappings are in the Config.ini file
			$ErrorArray = explode(",", $result->Errors);
			$lblError = "";

			foreach ($ErrorArray as $error)
			{
				$lblError .= $service->APIConfig[$error] . "<br>";
			}
		}

		$values = new stdClass;

		if ($lblError && $result->ResponseCode != 00)
		{
			$values->order_status_code = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';

			if ($lblError != "")
			{
				$values->log = $lblError;
				$values->msg = $lblError;
			}
			else
			{
				$values->log = JText::_('PLG_RS_PAYMENT_RAPID_EWAY_ORDER_NOT_PLACED');
				$values->msg = JText::_('PLG_RS_PAYMENT_RAPID_EWAY_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$values->transaction_id = $result->TransactionID;
			$values->order_status_code = $this->params->get('verify_status', '');
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('PLG_RS_PAYMENT_RAPID_EWAY_ORDER_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_RAPID_EWAY_ORDER_PLACED');
		}

		$values->order_id = $orderId;

		return $values;
	}

	/**
	 * onCapture_Paymentrs_payment_rapid_eway
	 *
	 * @param   string  $element  Element name
	 * @param   array   $data     Array data values
	 *
	 * @return  void
	 */
	public function onCapture_Paymentrs_payment_rapid_eway($element, $data)
	{
		return;
	}
}
