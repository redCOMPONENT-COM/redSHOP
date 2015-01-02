<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

class EwayPayment
{
	var $myGatewayURL;
	var $myCustomerID;
	var $myTransactionData = array();
	var $myCurlPreferences = array();

	public $params = null;

	/***********************************************************************
	 *** SET values to send to eWAY                                      ***
	 ***********************************************************************/
	public function setCustomerID($customerID)
	{
		$this->myCustomerID = $customerID;
	}

	function setTotalAmount($totalAmount)
	{
		$this->myTotalAmount = $totalAmount;
	}

	function setCustomerFirstname($customerFirstname)
	{
		$this->myCustomerFirstname = $customerFirstname;
	}

	function setCustomerLastname($customerLastname)
	{
		$this->myCustomerLastname = $customerLastname;
	}

	function setCustomerEmail($customerEmail)
	{
		$this->myCustomerEmail = $customerEmail;
	}

	function setCustomerAddress($customerAddress)
	{
		$this->myCustomerAddress = $customerAddress;
	}

	function setCustomerPostcode($customerPostcode)
	{
		$this->myCustomerPostcode = $customerPostcode;
	}

	function setCustomerInvoiceDescription($customerInvoiceDescription)
	{
		$this->myCustomerInvoiceDescription = $customerInvoiceDescription;
	}

	function setCustomerInvoiceRef($customerInvoiceRef)
	{
		$this->myCustomerInvoiceRef = $customerInvoiceRef;
	}

	function setCardHoldersName($cardHoldersName)
	{
		$this->myCardHoldersName = $cardHoldersName;
	}

	function setCardNumber($cardNumber)
	{
		$this->myCardNumber = $cardNumber;
	}

	function setCardExpiryMonth($cardExpiryMonth)
	{
		$this->myCardExpiryMonth = $cardExpiryMonth;
	}

	function setCardExpiryYear($cardExpiryYear)
	{
		$this->myCardExpiryYear = $cardExpiryYear;
	}

	function setTrxnNumber($trxnNumber)
	{
		$this->myTrxnNumber = $trxnNumber;
	}

	function setOption1($option1)
	{
		$this->myOption1 = $option1;
	}

	function setOption2($option2)
	{
		$this->myOption2 = $option2;
	}

	function setOption3($option3)
	{
		$this->myOption3 = $option3;
	}

	function setCVN($CVN)
	{
		$this->myCVN = $CVN;
	}

	/***********************************************************************
	 *** GET values returned by eWAY                                     ***
	 ***********************************************************************/
	public function getTrxnStatus()
	{
		return $this->myResultTrxnStatus;
	}

	function getTrxnNumber()
	{
		return $this->myResultTrxnNumber;
	}

	function getTrxnOption1()
	{
		return $this->myResultTrxnOption1;
	}

	function getTrxnOption2()
	{
		return $this->myResultTrxnOption2;
	}

	function getTrxnOption3()
	{
		return $this->myResultTrxnOption3;
	}

	function getTrxnReference()
	{
		return $this->myResultTrxnReference;
	}

	function getTrxnError()
	{
		return $this->myResultTrxnError;
	}

	function getAuthCode()
	{
		return $this->myResultAuthCode;
	}

	function getReturnAmount()
	{
		return $this->myResultReturnAmount;
	}

	function getCVN()
	{
		return $this->myCVN;
	}

	//Class Constructor
	function EwayPayment($customerID = '', $method = '', $liveGateway = '')
	{
		$plugin           = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_eway');
		$this->params     = new JRegistry($plugin->params);

		$eway_customer_id = $this->params->get('eway_customer_id', '');
		$method           = $this->params->get('eway_method_type');
		$liveGateway      = $this->params->get('eway_live_gateway');
		$debug_mode       = $this->params->get('debug_mode');

		$this->myCustomerID = $eway_customer_id;

		switch ($method)
		{
			case 'REAL-TIME';

				if ($liveGateway)
					$this->myGatewayURL = $this->params->get('eway_live_url');
				else
					$this->myGatewayURL = $this->params->get('eway_testing_url');
				break;
			case 'REAL-TIME-CVN';

				if ($liveGateway)
					$this->myGatewayURL = $this->params->get('eway_cvn_live_url');
				else
					$this->myGatewayURL = $this->params->get('eway_cvn_testing_url');
				break;
			case 'GEO-IP-ANTI-FRAUD';

				if ($liveGateway)
					$this->myGatewayURL = $this->params->get('eway_antifraud_live_url');
				else
					// In testing mode process with REAL-TIME
					$this->myGatewayURL = $this->params->get('eway_antifraud_testing_url');
				break;
		}

	}

	// Payment Function
	function doPayment($order_id)
	{
		$app = JFactory::getApplication();
		$debug_mode = $this->params->get('debug_mode');

		$xmlRequest = "<ewaygateway>" .
			"<ewayCustomerID>" . htmlentities($this->myCustomerID) . "</ewayCustomerID>" .
			"<ewayTotalAmount>" . htmlentities($this->myTotalAmount) . "</ewayTotalAmount>" .
			"<ewayCustomerFirstName>" . htmlentities($this->myCustomerFirstname) . "</ewayCustomerFirstName>" .
			"<ewayCustomerLastName>" . htmlentities($this->myCustomerLastname) . "</ewayCustomerLastName>" .
			"<ewayCustomerEmail>" . htmlentities($this->myCustomerEmail) . "</ewayCustomerEmail>" .
			"<ewayCustomerAddress>" . htmlentities($this->myCustomerAddress) . "</ewayCustomerAddress>" .
			"<ewayCustomerPostcode>" . htmlentities($this->myCustomerPostcode) . "</ewayCustomerPostcode>" .
			"<ewayCustomerInvoiceDescription>" . htmlentities($this->myCustomerInvoiceDescription) . "</ewayCustomerInvoiceDescription>" .
			"<ewayCustomerInvoiceRef>" . htmlentities($this->myCustomerInvoiceRef) . "</ewayCustomerInvoiceRef>" .
			"<ewayCardHoldersName>" . htmlentities($this->myCardHoldersName) . "</ewayCardHoldersName>" .
			"<ewayCardNumber>" . htmlentities($this->myCardNumber) . "</ewayCardNumber>" .
			"<ewayCardExpiryMonth>" . htmlentities($this->myCardExpiryMonth) . "</ewayCardExpiryMonth>" .
			"<ewayCardExpiryYear>" . htmlentities($this->myCardExpiryYear) . "</ewayCardExpiryYear>" .
			"<ewayTrxnNumber>" . htmlentities($this->myTrxnNumber) . "</ewayTrxnNumber>" .
			"<ewayCVN>" . htmlentities($this->myCVN) . "</ewayCVN>" .
			"<ewayOption1>" . htmlentities($this->myOption1) . "</ewayOption1>" .
			"<ewayOption2>" . htmlentities($this->myOption2) . "</ewayOption2>" .
			"<ewayOption3>" . htmlentities($this->myOption3) . "</ewayOption3>" .
			"</ewaygateway>";

		/* Use CURL to execute XML POST and write output into a string */

		$ch = curl_init($this->myGatewayURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 240);
		$xmlResponse = curl_exec($ch);

		$xml = new SimpleXMLElement($xmlResponse);
		$AUTH_Responsecode = isset($xml->ewayTrxnStatus) ? $xml->ewayTrxnStatus : $xml->Result->ewayTrxnStatus;

		// Check whether the curl_exec worked.
		if ($AUTH_Responsecode == 'True')
		{
			if ($debug_mode == 1)
			{
				$message = $xml->ewayTrxnError;
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_PLACED');
			}

			$transaction_id         = isset($xml->ewayTrxnNumber) ? $xml->ewayTrxnNumber : '';
			$node                   = $xml->children();
			$transaction_id         = $node->ewayTrxnNumber;
			$tid                    = strip_tags($transaction_id[0]);
			$values->responsestatus = 'Success';
		}
		else
		{
			if ($debug_mode == 1)
			{
				$message = isset($xml->ewayTrxnError) ? $xml->ewayTrxnError : $xml->Result->ewayTrxnError;
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}

			$tid = 0;
			$values->responsestatus = 'Fail';
		}

		$values->transaction_id = $tid;
		$values->message = (string) $message;

		return $values;
	}

	// Send XML Transaction Data and receive XML response
	function sendTransactionToEway($xmlRequest)
	{
		$ch = curl_init($this->myGatewayURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		foreach ($this->myCurlPreferences as $key => $value)
			curl_setopt($ch, $key, $value);

		$xmlResponse = curl_exec($ch);

		if (curl_errno($ch) == CURLE_OK)
			return $xmlResponse;
	}

	// Parse XML response from eway and place them into an array
	function parseResponse($xmlResponse)
	{
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $xmlResponse, $xmlData, $index);
		$responseFields = array();

		foreach ($xmlData as $data)
			if ($data["level"] == 2)
				$responseFields[$data["tag"]] = $data["value"];

		return $responseFields;
	}

	// Set Transaction Data
	// Possible fields: "TotalAmount", "CustomerFirstName", "CustomerLastName", "CustomerEmail", "CustomerAddress", "CustomerPostcode", "CustomerInvoiceDescription", "CustomerInvoiceRef",
	// "CardHoldersName", "CardNumber", "CardExpiryMonth", "CardExpiryYear", "TrxnNumber", "Option1", "Option2", "Option3", "CVN", "CustomerIPAddress", "CustomerBillingCountry"
	function setTransactionData($field, $value)
	{
		$this->myTransactionData["eway" . $field] = htmlentities(trim($value));
	}

	// Receive special preferences for Curl
	function setCurlPreferences($field, $value)
	{
		$this->myCurlPreferences[$field] = $value;
	}

	// Obtain visitor IP even if is under a proxy
	function getVisitorIP()
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		$proxy = $_SERVER["HTTP_X_FORWARDED_FOR"];

		if (preg_match("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $proxy))
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

		return $ip;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
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
}
