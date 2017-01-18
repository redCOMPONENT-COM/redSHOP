<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * EwayPayment class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class EwayPayment
{
	/**
	 * [$myGatewayURL description]
	 *
	 * @var  [type]
	 */
	var $myGatewayURL;

	/**
	 * [$myCustomerID description]
	 *
	 * @var  [type]
	 */
	var $myCustomerID;

	/**
	 * [$myTransactionData description]
	 *
	 * @var  array
	 */
	var $myTransactionData = array();

	/**
	 * [$myCurlPreferences description]
	 *
	 * @var  array
	 */
	var $myCurlPreferences = array();

	/**
	 * [$params description]
	 *
	 * @var  null
	 */
	public $params = null;

	/***********************************************************************
	 *** SET values to send to eWAY                                      ***
	 ***********************************************************************/

	/**
	 * [setCustomerID description]
	 *
	 * @param   [int]  $customerID  [description]
	 *
	 * @return  [void]
	 */
	public function setCustomerID($customerID)
	{
		$this->myCustomerID = $customerID;
	}

	/**
	 * [setTotalAmount description]
	 *
	 * @param   [int]  $totalAmount  [description]
	 *
	 * @return   void
	 */
	function setTotalAmount($totalAmount)
	{
		$this->myTotalAmount = $totalAmount;
	}

	/**
	 * [setCustomerFirstname description]
	 *
	 * @param   [string]  $customerFirstname  [description]
	 *
	 * @return  void
	 */
	function setCustomerFirstname($customerFirstname)
	{
		$this->myCustomerFirstname = $customerFirstname;
	}

	/**
	 * [setCustomerLastname description]
	 *
	 * @param   [string]  $customerLastname  [description]
	 *
	 * @return  void
	 */
	function setCustomerLastname($customerLastname)
	{
		$this->myCustomerLastname = $customerLastname;
	}

	/**
	 * [setCustomerEmail]
	 *
	 * @param   [type]  $customerEmail  [description]
	 *
	 * @return  void
	 */
	function setCustomerEmail($customerEmail)
	{
		$this->myCustomerEmail = $customerEmail;
	}

	/**
	 * [setCustomerAddress description]
	 *
	 * @param   [type]  $customerAddress  [description]
	 *
	 * @return  void
	 */
	function setCustomerAddress($customerAddress)
	{
		$this->myCustomerAddress = $customerAddress;
	}

	/**
	 * [setCustomerPostcode description]
	 *
	 * @param   [string]  $customerPostcode  [description]
	 *
	 * @return  void
	 */
	function setCustomerPostcode($customerPostcode)
	{
		$this->myCustomerPostcode = $customerPostcode;
	}

	/**
	 * [setCustomerInvoiceDescription description]
	 *
	 * @param   [string]  $customerInvoiceDescription  [description]
	 *
	 * @return  void
	 */
	function setCustomerInvoiceDescription($customerInvoiceDescription)
	{
		$this->myCustomerInvoiceDescription = $customerInvoiceDescription;
	}

	/**
	 * [setCustomerInvoiceRef description]
	 *
	 * @param   [int]  $customerInvoiceRef  [description]
	 *
	 * @return  void
	 */
	function setCustomerInvoiceRef($customerInvoiceRef)
	{
		$this->myCustomerInvoiceRef = $customerInvoiceRef;
	}

	/**
	 * [setCardHoldersName description]
	 *
	 * @param   [string]  $cardHoldersName  [description]
	 *
	 * @return  void
	 */
	function setCardHoldersName($cardHoldersName)
	{
		$this->myCardHoldersName = $cardHoldersName;
	}

	/**
	 * [setCardNumber description]
	 *
	 * @param   [int]  $cardNumber  [description]
	 *
	 * @return  void
	 */
	function setCardNumber($cardNumber)
	{
		$this->myCardNumber = $cardNumber;
	}

	/**
	 * [setCardExpiryMonth description]
	 *
	 * @param   [int]  $cardExpiryMonth  [description]
	 *
	 * @return  void
	 */
	function setCardExpiryMonth($cardExpiryMonth)
	{
		$this->myCardExpiryMonth = $cardExpiryMonth;
	}

	/**
	 * [setCardExpiryYear description]
	 *
	 * @param   [int]  $cardExpiryYear  [description]
	 *
	 * @return  void
	 */
	function setCardExpiryYear($cardExpiryYear)
	{
		$this->myCardExpiryYear = $cardExpiryYear;
	}

	/**
	 * [setTrxnNumber description]
	 *
	 * @param   [string]  $trxnNumber  [description]
	 *
	 * @return  void
	 */
	function setTrxnNumber($trxnNumber)
	{
		$this->myTrxnNumber = $trxnNumber;
	}

	/**
	 * [setOption1 description]
	 *
	 * @param   [type]  $option1  [description]
	 *
	 * @return  void
	 */
	function setOption1($option1)
	{
		$this->myOption1 = $option1;
	}

	/**
	 * [setOption2 description]
	 *
	 * @param   [type]  $option2  [description]
	 *
	 * @return  void
	 */
	function setOption2($option2)
	{
		$this->myOption2 = $option2;
	}

	/**
	 * [setOption3 description]
	 *
	 * @param   [type]  $option3  [description]
	 *
	 * @return  void
	 */
	function setOption3($option3)
	{
		$this->myOption3 = $option3;
	}

	/**
	 * [setCVN description]
	 *
	 * @param   [string]  $CVN  [description]
	 *
	 * @return  void
	 */
	function setCVN($CVN)
	{
		$this->myCVN = $CVN;
	}

	/***********************************************************************
	 *** GET values returned by eWAY                                     ***
	 ***********************************************************************/

	/**
	 * [getTrxnStatus description]
	 *
	 * @return  [void]
	 */
	public function getTrxnStatus()
	{
		return $this->myResultTrxnStatus;
	}

	/**
	 * [getTrxnNumber description]
	 *
	 * @return  void
	 */
	function getTrxnNumber()
	{
		return $this->myResultTrxnNumber;
	}

	/**
	 * [getTrxnOption1 description]
	 *
	 * @return  void
	 */
	function getTrxnOption1()
	{
		return $this->myResultTrxnOption1;
	}

	/**
	 * [getTrxnOption2 description]
	 *
	 * @return  [void]
	 */
	function getTrxnOption2()
	{
		return $this->myResultTrxnOption2;
	}

	/**
	 * [getTrxnOption3 description]
	 *
	 * @return  [void]
	 */
	function getTrxnOption3()
	{
		return $this->myResultTrxnOption3;
	}

	/**
	 * [getTrxnReference description]
	 *
	 * @return  [void]
	 */
	function getTrxnReference()
	{
		return $this->myResultTrxnReference;
	}

	/**
	 * [getTrxnError description]
	 *
	 * @return  [void]
	 */
	function getTrxnError()
	{
		return $this->myResultTrxnError;
	}

	/**
	 * [getAuthCode description]
	 *
	 * @return  [void]
	 */
	function getAuthCode()
	{
		return $this->myResultAuthCode;
	}

	/**
	 * [getReturnAmount description]
	 *
	 * @return  [void]
	 */
	function getReturnAmount()
	{
		return $this->myResultReturnAmount;
	}

	/**
	 * [getCVN description]
	 *
	 * @return  [void]
	 */
	function getCVN()
	{
		return $this->myCVN;
	}

	/**
	 * [EwayPayment description]
	 *
	 * @param   string  $customerID   [description]
	 * @param   string  $method       [description]
	 * @param   string  $liveGateway  [description]
	 *
	 * @return  void
	 */
	function EwayPayment($customerID = '', $method = '', $liveGateway = '')
	{
		$plugin           = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_eway');
		$this->params     = new JRegistry($plugin->params);

		$ewayCustomerId = $this->params->get('eway_customer_id', '');
		$method         = $this->params->get('eway_method_type');
		$liveGateway    = $this->params->get('eway_live_gateway');
		$debugMode      = $this->params->get('debug_mode');

		$this->myCustomerID = $ewayCustomerId;

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

	/**
	 * [doPayment Payment Function]
	 *
	 * @param   [int]  $orderId  [description]
	 *
	 * @return  [obj]  $values
	 */
	function doPayment($orderId)
	{
		$app = JFactory::getApplication();
		$debugMode = $this->params->get('debugMode');

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
		$AuthResponseCode = isset($xml->ewayTrxnStatus) ? $xml->ewayTrxnStatus : $xml->Result->ewayTrxnStatus;

		// Check whether the curl_exec worked.
		if ($AuthResponseCode == 'True')
		{
			if ($debugMode == 1)
			{
				$message = $xml->ewayTrxnError;
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_PLACED');
			}

			$transactionId          = isset($xml->ewayTrxnNumber) ? $xml->ewayTrxnNumber : '';
			$node                   = $xml->children();
			$transactionId          = $node->ewayTrxnNumber;
			$tid                    = strip_tags($transactionId[0]);
			$values->responsestatus = 'Success';
		}
		else
		{
			if ($debugMode == 1)
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

		$values->transactionId = $tid;
		$values->message = (string) $message;

		return $values;
	}

	/**
	 * [sendTransactionToEway Send XML Transaction Data and receive XML response]
	 *
	 * @param   [type]  $xmlRequest  [description]
	 *
	 * @return  [type]
	 */
	function sendTransactionToEway($xmlRequest)
	{
		$ch = curl_init($this->myGatewayURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		foreach ($this->myCurlPreferences as $key => $value)
		{
			curl_setopt($ch, $key, $value);
		}

		$xmlResponse = curl_exec($ch);

		if (curl_errno($ch) == CURLE_OK)
		{
			return $xmlResponse;
		}
	}

	/**
	 * [parseResponse Parse XML response from eway and place them into an array]
	 *
	 * @param   [type]  $xmlResponse  [description]
	 *
	 * @return  [type]
	 */
	function parseResponse($xmlResponse)
	{
		$xmlParser = xml_parser_create();
		xml_parse_into_struct($xmlParser, $xmlResponse, $xmlData, $index);
		$responseFields = array();

		foreach ($xmlData as $data)
		{
			if ($data["level"] == 2)
			{
				$responseFields[$data["tag"]] = $data["value"];
			}
		}

		return $responseFields;
	}

	/* Set Transaction Data
	 Possible fields: "TotalAmount", "CustomerFirstName", "CustomerLastName", "CustomerEmail", "CustomerAddress", "CustomerPostcode", "CustomerInvoiceDescription", "CustomerInvoiceRef",
	 "CardHoldersName", "CardNumber", "CardExpiryMonth", "CardExpiryYear", "TrxnNumber", "Option1", "Option2", "Option3", "CVN", "CustomerIPAddress", "CustomerBillingCountry"
	 */

	/**
	 * [setTransactionData description]
	 *
	 * @param   [type]  $field  [description]
	 * @param   [type]  $value  [description]
	 *
	 * @return  void
	 */
	function setTransactionData($field, $value)
	{
		$this->myTransactionData["eway" . $field] = htmlentities(trim($value));
	}

	/**
	 * [setCurlPreferences Receive special preferences for Curl]
	 *
	 * @param   [type]  $field  [description]
	 * @param   [type]  $value  [description]
	 *
	 * @return  void
	 */
	function setCurlPreferences($field, $value)
	{
		$this->myCurlPreferences[$field] = $value;
	}

	/**
	 * [getVisitorIP Obtain visitor IP even if is under a proxy]
	 *
	 * @return  [type]
	 */
	function getVisitorIP()
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		$proxy = $_SERVER["HTTP_X_FORWARDED_FOR"];

		if (preg_match("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $proxy))
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

		return $ip;
	}

	/**
	 * [orderPaymentNotYetUpdated description]
	 *
	 * @param   [type]  $dbConn   [description]
	 * @param   [type]  $orderId  [description]
	 * @param   [type]  $tranId   [description]
	 *
	 * @return  [type]
	 */
	function orderPaymentNotYetUpdated($dbConn, $orderId, $tranId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('COUNT(' . $db->qn('payment_order_id') . ')')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . $db->getEscaped($orderId))
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->getEscaped($tranId));

		$db->setQuery($query);
		$orderPayment = $db->loadResult();

		return ($orderPayment == 0)? true: false;
	}
}
