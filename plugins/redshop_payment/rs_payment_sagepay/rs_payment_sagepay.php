<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_sagepay extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_sagepay')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
		include $paymentpath;
	}

	function onNotifyPaymentrs_payment_sagepay($element, $request)
	{
		if ($element != 'rs_payment_sagepay')
		{
			return;
		}

		$strCrypt = $request["crypt"];

		if (strlen($strCrypt) == 0)
		{
			ob_end_flush();
		}
		// Now decode the Crypt field and extract the results
		$strDecoded     = $this->simpleXor($this->Base64Decode($strCrypt), $this->params->get("sagepay_encryptpass"));
		$responsevalues = $this->getToken($strDecoded);

		$debug_mode     = $this->params->get("debug_mode");
		$verify_status  = $this->params->get("verify_status");
		$invalid_status = $this->params->get("invalid_status");

		// Split out the useful information into variables we can use
		$strStatus         = $responsevalues['Status'];
		$strStatusDetail   = $responsevalues['StatusDetail'];
		$strVendorTxCode   = $responsevalues["VendorTxCode"];
		$strVPSTxId        = str_replace("{", "", $responsevalues["VPSTxId"]);
		$strVPSTxId        = str_replace("}", "", $strVPSTxId);
		$strTxAuthNo       = $responsevalues["TxAuthNo"];
		$strAmount         = $responsevalues["Amount"];
		$strAVSCV2         = $responsevalues["AVSCV2"];
		$strAddressResult  = $responsevalues["AddressResult"];
		$strPostCodeResult = $responsevalues["PostCodeResult"];
		$strCV2Result      = $responsevalues["CV2Result"];
		$strGiftAid        = $responsevalues["GiftAid"];
		$str3DSecureStatus = $responsevalues["3DSecureStatus"];
		$strCAVV           = $responsevalues["CAVV"];
		$strCardType       = $responsevalues["CardType"];
		$strLast4Digits    = $responsevalues["Last4Digits"];

		// Update the database and redirect the user appropriately
		if ($strStatus == "OK")
		{
			// "AUTHORISED - The transaction was successfully authorised with the bank.";
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_AUTHORISED");
		}
		// "MALFORMED - The StatusDetail was:" . mysql_real_escape_string(substr($strStatusDetail,0,255));
		elseif ($strStatus == "MALFORMED")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_MALFORMED") . mysql_real_escape_string(substr($strStatusDetail, 0, 255));
		}
		// "INVALID - The StatusDetail was:" . mysql_real_escape_string(substr($strStatusDetail,0,255));
		elseif ($strStatus == "INVALID")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_INVALID") . mysql_real_escape_string(substr($strStatusDetail, 0, 255));
		}
		// "DECLINED - The transaction was not authorised by the bank.";
		elseif ($strStatus == "NOTAUTHED")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_DECLINED");
		}
		// "REJECTED - The transaction was failed by your 3D-Secure or AVS/CV2 rule-bases.";
		elseif ($strStatus == "REJECTED")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_REJECTED");
		}
		// "AUTHENTICATED - The transaction was successfully 3D-Secure Authenticated and can now be Authorised.";
		elseif ($strStatus == "AUTHENTICATED")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_AUTHENTICATED");
		}
		// "REGISTERED - The transaction was could not be 3D-Secure Authenticated, but has been registered to be Authorised.";
		elseif ($strStatus == "REGISTERED")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_REGISTERED");
		}
		// "ERROR - There was an error during the payment process.  The error details are: " . mysql_real_escape_string($strStatusDetail);
		elseif ($strStatus == "ERROR")
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_ERROR") . mysql_real_escape_string($strStatusDetail);
		}
		// "UNKNOWN - An unknown status was returned from Sage Pay.  The Status was: " . mysql_real_escape_string($strStatus) . ", with StatusDetail:" . mysql_real_escape_string($strStatusDetail);
		else
		{
			$strDBStatus = JText::_("COM_REDSHOP_SAGEPAY_UNKNOWN") . mysql_real_escape_string($strStatus) . ", with StatusDetail:" . mysql_real_escape_string($strStatusDetail);
		}

		// UPDATE THE ORDER STATUS to 'CONFIRMED'
		if (($strStatus == "OK") || ($strStatus == "AUTHENTICATED") || ($strStatus == "REGISTERED"))
		{
			if ($debug_mode == 1)
			{
				$payment_message = $strStatusDetail;
			}
			else
			{
				$payment_message = JText::_('COM_REDSHOP_ORDER_PLACED');
			}

			// SUCCESS: UPDATE THE ORDER STATUS to 'CONFIRMED'
			$values->order_status_code         = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->transaction_id            = $strVPSTxId;
			$values->order_id                  = $request['orderid'];
		}
		else
		{
			if ($debug_mode == 1)
			{
				$payment_message = $strStatusDetail;
			}
			else
			{
				$payment_message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}

			// FAILED: UPDATE THE ORDER STATUS to 'PENDING'
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->order_id                  = $request['orderid'];
		}

		$values->log = $payment_message;
		$values->msg = $payment_message;

		return $values;
	}

	function onCapture_Paymentrs_payment_sagepay($element, $data)
	{
		return;
	}

	function requestPost($url, $data)
	{
		ob_clean();
		ob_get_clean();

		// Set a one-minute timeout for this script
		set_time_limit(60);

		// Initialise output variable
		$output = array();

		// Open the cURL session
		$curlSession = curl_init();

		// Set the URL
		curl_setopt($curlSession, CURLOPT_URL, $url);

		// No headers, please
		curl_setopt($curlSession, CURLOPT_HEADER, 0);

		// It's a POST request
		curl_setopt($curlSession, CURLOPT_POST, 1);

		// Set the fields for the POST
		curl_setopt($curlSession, CURLOPT_POSTFIELDS, $data);

		// Return it direct, don't print it out
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);

		// This connection will timeout in 30 seconds
		curl_setopt($curlSession, CURLOPT_TIMEOUT, 30);

		// The next two lines must be present for the kit to work with newer version of cURL
		// You should remove them if you have any problems in earlier versions of cURL
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1);

		// Send the request and store the result in an array
		$rawresponse = curl_exec($curlSession);

		// Store the raw response for later as it's useful to see for integration and understanding
		$_SESSION["rawresponse"] = $rawresponse;

		// Split response into name=value pairs
		$response = preg_split(chr(10), $rawresponse);

		// Check that a connection was made
		if (curl_error($curlSession))
		{
			// If it wasn't...
			$output['Status'] = "FAIL";
			$output['StatusDetail'] = curl_error($curlSession);
		}

		// Close the cURL session
		curl_close($curlSession);

		// Tokenized the response
		for ($i = 0; $i < count($response); $i++)
		{
			// Find position of first "=" character
			$splitAt = strpos($response[$i], "=");

			// Create an associative (hash) array with key/value pairs ('trim' strips excess whitespace)
			$output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt + 1)));
		}

		// Return the output
		return $output;
	}

	function getToken($thisString)
	{
		// List the possible tokens
		$Tokens = array(
			"Status",
			"StatusDetail",
			"VendorTxCode",
			"VPSTxId",
			"TxAuthNo",
			"Amount",
			"AVSCV2",
			"AddressResult",
			"PostCodeResult",
			"CV2Result",
			"GiftAid",
			"3DSecureStatus",
			"CAVV",
			"AddressStatus",
			"CardType",
			"Last4Digits",
			"PayerStatus", "CardType");

		// Initialize arrays
		$output = array();
		$resultArray = array();

		// Get the next token in the sequence
		for ($i = count($Tokens) - 1; $i >= 0; $i--)
		{
			// Find the position in the string
			$start = strpos($thisString, $Tokens[$i]);

			// If it's present
			if ($start !== false)
			{
				// Record position and token name
				$resultArray[$i]->start = $start;
				$resultArray[$i]->token = $Tokens[$i];
			}
		}

		// Sort in order of position
		sort($resultArray);

		// Go through the result array, getting the token values
		for ($i = 0; $i < count($resultArray); $i++)
		{
			// Get the start point of the value
			$valueStart = $resultArray[$i]->start + strlen($resultArray[$i]->token) + 1;

			// Get the length of the value
			if ($i == (count($resultArray) - 1))
			{
				$output[$resultArray[$i]->token] = substr($thisString, $valueStart);
			}
			else
			{
				$valueLength = $resultArray[$i + 1]->start - $resultArray[$i]->start - strlen($resultArray[$i]->token) - 2;
				$output[$resultArray[$i]->token] = substr($thisString, $valueStart, $valueLength);
			}
		}

		// Return the ouput array
		return $output;
	}

	function base64Encode($plain)
	{
		// Initialise output variable
		$output = "";

		// Do encoding
		$output = base64_encode($plain);

		// Return the result
		return $output;
	}

	function base64Decode($scrambled)
	{
		// Initialise output variable
		$output = "";

		// Fix plus to space conversion issue
		$scrambled = str_replace(" ", "+", $scrambled);

		// Do encoding
		$output = base64_decode($scrambled);

		// Return the result
		return $output;
	}

	function simpleXor($InString, $Key)
	{
		// Initialize key array
		$KeyList = array();

		// Initialize out variable
		$output = "";

		// Convert $Key into array of ASCII values
		for ($i = 0; $i < strlen($Key); $i++)
		{
			$KeyList[$i] = ord(substr($Key, $i, 1));
		}

		// Step through string a character at a time
		for ($i = 0; $i < strlen($InString); $i++)
		{
			// Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
			// % is MOD (modulus), ^ is XOR
			$output .= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
		}

		// Return the result
		return $output;
	}
}
