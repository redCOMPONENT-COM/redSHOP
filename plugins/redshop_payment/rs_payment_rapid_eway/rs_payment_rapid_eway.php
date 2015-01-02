<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_rapid_eway extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_rapid_eway')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	function onNotifyPaymentrs_payment_rapid_eway($element, $request)
	{
		if ($element != 'rs_payment_rapid_eway')
		{
			return;
		}

		$user = JFActory::getUser();
		$user_id = $user->id;

		// Get Plugin params
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$auth_type      = $this->params->get('auth_type', '');
		$eWAYusername   = $this->params->get("username");
		$eWAYpassword   = $this->params->get("password");
		$test_mode      = $this->params->get("test_mode");

		$AccessCode = $request["AccessCode"];
		$api_path   = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/Rapid.php';
		include $api_path;
		$service    = new RapidAPI;

		// Call RapidAPI to get the result
		$service->setTestMode($test_mode);
		$service->getAuthorizeData($eWAYusername, $eWAYpassword);
		$req             = new GetAccessCodeResultRequest;
		$req->AccessCode = $AccessCode;
		$result          = $service->GetAccessCodeResult($req);
		$order_id        = $request['orderid'];

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

		if (isset($lblError) && $response->ResponseCode != 00)
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
		else
		{
			$tid = $result->TransactionID;
			$transaction_id = $tid;
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function onCapture_Paymentrs_payment_rapid_eway($element, $data)
	{
		return;
	}
}
