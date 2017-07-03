<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgRedshop_PaymentRs_Payment_Eway3dSecure class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Eway3dSecure extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_eway3dsecure', __DIR__);

		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element  Name element
	 * @param   array   $data     Array values
	 *
	 * @return  null
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_eway3dsecure')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * onNotifyPaymentrs_payment_eway3dsecure
	 *
	 * @param   string  $element  Name element
	 * @param   array   $request  Array values
	 *
	 * @return  null|array
	 */
	public function onNotifyPaymentrs_payment_eway3dsecure($element, $request)
	{
		if ($element != 'rs_payment_eway3dsecure')
		{
			return;
		}

		$app 			= JFactory::getApplication();
		$input 			= $app->input;
		$accessCode 	= $input->getString('AccessCode', '');
		$orderId 		= $input->getString('orderid', '');
		$verifyStatus   = $this->params->get('verify_status', '');
		$invalidStatus  = $this->params->get('invalid_status', '');

		$values 							= new stdClass;
		$values->order_status_code 			= $invalidStatus;
		$values->order_payment_status_code 	= 'UNPAID';
		$values->log 						= JText::_('PLG_RS_PAYMENT_EWAY3DSECURE_ORDER_NOT_PLACED.');
		$values->msg 						= JText::_('PLG_RS_PAYMENT_EWAY3DSECURE_ORDER_NOT_PLACED');

		if ($accessCode)
		{
			// Include RapidAPI Library
			require 'rs_payment_eway3dsecure/RapidAPI.php';

			// Call RapidAPI
			$ewayParams = array('sandbox' => $this->params->get('test_mode', true));
			$service = new eWAY\RapidAPI($this->params->get("APIKey"), $this->params->get("APIPassword"), $ewayParams);

			$request = new eWAY\GetAccessCodeResultRequest;
			$request->AccessCode = $accessCode;
			$result = $service->GetAccessCodeResult($request);

			if (isset($result->Errors))
			{
				$errors = explode(",", $result->Errors);

				foreach ($errors as $error)
				{
					$error = $service->getMessage($error);
					$app->enqueueMessage($error, 'warning');
				}
			}
			else
			{
				$values->order_status_code = $verifyStatus;
				$values->order_payment_status_code = 'PAID';
				$values->log = JText::_('PLG_RS_PAYMENT_EWAY3DSECURE_ORDER_PLACED');
				$values->msg = JText::_('PLG_RS_PAYMENT_EWAY3DSECURE_ORDER_PLACED');
				$values->transaction_id = $result->TransactionID;
			}
		}

		$values->order_id = $orderId;

		return $values;
	}

	/**
	 * [onCapture_Paymentrs_payment_eway3dsecure]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onCapture_Paymentrs_payment_eway3dsecure($element, $data)
	{
		return;
	}
}
