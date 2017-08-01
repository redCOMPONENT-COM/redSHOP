<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die;

#******************************************************************************
#* Name          : PxPay_Curl.inc.php
#* Description   : Classes used interact with the PxPay interface using PHP with the cURL extension installed
#* Copyright	 : Direct Payment Solutions 2009(c)
#* Date          : 2009-04-10
#* Version		 : 1.0
#* Author		 : Thomas Treadwell
#******************************************************************************

# Use this class to parse an XML document
class MifMessage
{
	var $xml_;
	var $xml_index_;
	var $xml_value_;

	# Constructor:
	# Create a MifMessage with the specified XML text.
	# The constructor returns a null object if there is a parsing error.
	function MifMessage($xml)
	{
		$p = xml_parser_create();
		xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
		$ok = xml_parse_into_struct($p, $xml, $value, $index);
		xml_parser_free($p);

		if ($ok)
		{
			$this->xml_ = $xml;
			$this->xml_value_ = $value;
			$this->xml_index_ = $index;
		}
	}

	# Return the value of the specified top-level attribute.
	# This method can only return attributes of the root element.
	# If the attribute is not found, return "".
	function get_attribute($attribute)
	{
		$attributes = $this->xml_value_[0]["attributes"];

		return $attributes[$attribute];
	}

	# Return the text of the specified element.
	# The element is given as a simplified XPath-like name.
	# For example, "Link/ServerOk" refers to the ServerOk element
	# nested in the Link element (nested in the root element).
	# If the element is not found, return "".
	function get_element_text($element)
	{
		$index = $this->get_element_index($element, 0);

		if ($index == 0)
		{
			return "";
		}
		else
		{
			#When element existent but empty
			$elementObj = $this->xml_value_[$index];

			if (!array_key_exists("value", $elementObj))
				return "";

			return $this->xml_value_[$index]["value"];
		}
	}

	# (internal method)
	# Return the index of the specified element,
	# relative to some given root element index.
	#
	function get_element_index($element, $rootindex = 0)
	{
		#$element = strtoupper($element);
		$pos = strpos($element, "/");

		if ($pos !== false)
		{
			# element contains '/': find first part
			$start_path = substr($element, 0, $pos);
			$remain_path = substr($element, $pos + 1);
			$index = $this->get_element_index($start_path, $rootindex);

			if ($index == 0)
			{
				# couldn't find first part give up.
				return 0;
			}

			# recursively find rest
			return $this->get_element_index($remain_path, $index);
		}
		else
		{
			# search from the parent across all its children
			# i.e. until we get the parent's close tag.
			$level = $this->xml_value_[$rootindex]["level"];

			if ($this->xml_value_[$rootindex]["type"] == "complete")
			{
				return 0; # no children
			}

			$index = $rootindex + 1;
			while ($index < count($this->xml_value_) &&
				!($this->xml_value_[$index]["level"] == $level &&
					$this->xml_value_[$index]["type"] == "close"))
			{
				# if one below parent and tag matches, bingo
				if ($this->xml_value_[$index]["level"] == $level + 1 &&
					$this->xml_value_[$index]["tag"] == $element
				)
				{
					return $index;
				}

				$index++;
			}

			return 0;
		}
	}
}

class PxPay_Curl
{
	var $PxPay_Key;
	var $PxPay_Url;
	var $PxPay_Userid;

	function PxPay_Curl($Url, $UserId, $Key)
	{
		error_reporting(E_ERROR);
		$this->PxPay_Key = $Key;
		$this->PxPay_Url = $Url;
		$this->PxPay_Userid = $UserId;
	}

	#******************************************************************************
	# Create a request for the PxPay interface
	#******************************************************************************
	function makeRequest($request)
	{
		#Validate the Request
		if ($request->validData() == false) return "";

		$request->setUserId($this->PxPay_Userid);
		$request->setKey($this->PxPay_Key);

		$xml = $request->toXml();

		$result = $this->submitXml($xml);

		return $result;
	}

	#******************************************************************************
	# Return the transaction outcome details
	#******************************************************************************
	function getResponse($result)
	{
		$inputXml = "<ProcessResponse><PxPayUserId>" . $this->PxPay_Userid . "</PxPayUserId><PxPayKey>" . $this->PxPay_Key .
			"</PxPayKey><Response>" . $result . "</Response></ProcessResponse>";

		$outputXml = $this->submitXml($inputXml);

		$pxresp = new PxPayResponse($outputXml);

		return $pxresp;
	}

	#******************************************************************************
	# Actual submission of XML using cURL. Returns output XML
	#******************************************************************************
	function submitXml($inputXml)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->PxPay_Url);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $inputXml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		#set up proxy, this may change depending on ISP, please contact your ISP to get the correct cURL settings
		#curl_setopt($ch,CURLOPT_PROXY , "proxy:8080");
		#curl_setopt($ch,CURLOPT_PROXYUSERPWD,"username:password");

		$outputXml = curl_exec($ch);

		curl_close($ch);

		return $outputXml;
	}

}

#******************************************************************************
# Class for PxPay request messages.
#******************************************************************************
class PxPayRequest extends PxPayMessage
{
	var $UrlFail, $UrlSuccess;
	var $AmountInput;
	var $EnableAddBillCard;
	var $PxPayUserId;
	var $PxPayKey;
	var $Opt;

	#Constructor
	function PxPayRequest()
	{
		$this->PxPayMessage();
	}

	function setEnableAddBillCard($EnableBillAddCard)
	{
		$this->EnableAddBillCard = $EnableBillAddCard;
	}

	function setUrlFail($UrlFail)
	{
		$this->UrlFail = $UrlFail;
	}

	function setUrlSuccess($UrlSuccess)
	{
		$this->UrlSuccess = $UrlSuccess;
	}

	function setAmountInput($AmountInput)
	{
		$this->AmountInput = sprintf("%9.2f", $AmountInput);
	}

	function setUserId($UserId)
	{
		$this->PxPayUserId = $UserId;
	}

	function setKey($Key)
	{
		$this->PxPayKey = $Key;
	}

	function setOpt($Opt)
	{
		$this->Opt = $Opt;
	}

	#******************************************************************
	#Data validation
	#******************************************************************
	function validData()
	{
		$msg = "";

		if ($this->TxnType != "Purchase")
			if ($this->TxnType != "Auth")
				$msg = "Invalid TxnType[$this->TxnType]<br>";

		if (strlen($this->MerchantReference) > 64)
			$msg = "Invalid MerchantReference [$this->MerchantReference]<br>";

		if (strlen($this->TxnId) > 16)
			$msg = "Invalid TxnId [$this->TxnId]<br>";

		if (strlen($this->TxnData1) > 255)
			$msg = "Invalid TxnData1 [$this->TxnData1]<br>";

		if (strlen($this->TxnData2) > 255)
			$msg = "Invalid TxnData2 [$this->TxnData2]<br>";

		if (strlen($this->TxnData3) > 255)
			$msg = "Invalid TxnData3 [$this->TxnData3]<br>";

		if (strlen($this->EmailAddress) > 255)
			$msg = "Invalid EmailAddress [$this->EmailAddress]<br>";

		if (strlen($this->UrlFail) > 255)
			$msg = "Invalid UrlFail [$this->UrlFail]<br>";

		if (strlen($this->UrlSuccess) > 255)
			$msg = "Invalid UrlSuccess [$this->UrlSuccess]<br>";

		if (strlen($this->BillingId) > 32)
			$msg = "Invalid BillingId [$this->BillingId]<br>";

		if ($msg != "")
		{
			trigger_error($msg, E_USER_ERROR);

			return false;
		}

		return true;
	}

}

#******************************************************************************
# Abstract base class for PxPay messages.
# These are messages with certain defined elements,  which can be serialized to XML.

#******************************************************************************
class PxPayMessage
{
	var $TxnType;
	var $CurrencyInput;
	var $TxnData1;
	var $TxnData2;
	var $TxnData3;
	var $MerchantReference;
	var $EmailAddress;
	var $BillingId;
	var $TxnId;

	function PxPayMessage()
	{
	}

	function setBillingId($BillingId)
	{
		$this->BillingId = $BillingId;
	}

	function getBillingId()
	{
		return $this->BillingId;
	}

	function setTxnType($TxnType)
	{
		$this->TxnType = $TxnType;
	}

	function getTxnType()
	{
		return $this->TxnType;
	}

	function setCurrencyInput($CurrencyInput)
	{
		$this->CurrencyInput = $CurrencyInput;
	}

	function getCurrencyInput()
	{
		return $this->CurrencyInput;
	}

	function setMerchantReference($MerchantReference)
	{
		$this->MerchantReference = $MerchantReference;
	}

	function getMerchantReference()
	{
		return $this->MerchantReference;
	}

	function setEmailAddress($EmailAddress)
	{
		$this->EmailAddress = $EmailAddress;
	}

	function getEmailAddress()
	{
		return $this->EmailAddress;
	}

	function setTxnData1($TxnData1)
	{
		$this->TxnData1 = $TxnData1;
	}

	function getTxnData1()
	{
		return $this->TxnData1;
	}

	function setTxnData2($TxnData2)
	{
		$this->TxnData2 = $TxnData2;
	}

	function getTxnData2()
	{
		return $this->TxnData2;
	}

	function getTxnData3()
	{
		return $this->TxnData3;
	}

	function setTxnData3($TxnData3)
	{
		$this->TxnData3 = $TxnData3;
	}

	function setTxnId($TxnId)
	{
		$this->TxnId = $TxnId;
	}

	function getTxnId()
	{
		return $this->TxnId;
	}

	function toXml()
	{
		$arr = get_object_vars($this);

		$xml = "<GenerateRequest>";
		while (list($prop, $val) = each($arr))
			$xml .= "<$prop>$val</$prop>";

		$xml .= "</GenerateRequest>";

		return $xml;
	}

}

#******************************************************************************
# Class for PxPay response messages.
#******************************************************************************

class PxPayResponse extends PxPayMessage
{
	var $Success;
	var $AuthCode;
	var $CardName;
	var $CardHolderName;
	var $CardNumber;
	var $DateExpiry;
	var $ClientInfo;
	var $DpsTxnRef;
	var $DpsBillingId;
	var $AmountSettlement;
	var $CurrencySettlement;
	var $TxnMac;
	var $ResponseText;

	function PxPayResponse($xml)
	{
		$msg = new MifMessage($xml);
		$this->PxPayMessage();

		$this->Success = $msg->get_element_text("Success");
		$this->setTxnType($msg->get_element_text("TxnType"));
		$this->CurrencyInput = $msg->get_element_text("CurrencyInput");
		$this->setMerchantReference($msg->get_element_text("MerchantReference"));
		$this->setTxnData1($msg->get_element_text("TxnData1"));
		$this->setTxnData2($msg->get_element_text("TxnData2"));
		$this->setTxnData3($msg->get_element_text("TxnData3"));
		$this->AuthCode = $msg->get_element_text("AuthCode");
		$this->CardName = $msg->get_element_text("CardName");
		$this->CardHolderName = $msg->get_element_text("CardHolderName");
		$this->CardNumber = $msg->get_element_text("CardNumber");
		$this->DateExpiry = $msg->get_element_text("DateExpiry");
		$this->ClientInfo = $msg->get_element_text("ClientInfo");
		$this->MerchantTxnId = $msg->get_element_text("TxnId");
		$this->TxnId = $msg->get_element_text("TxnId");
		$this->setEmailAddress($msg->get_element_text("EmailAddress"));
		$this->DpsTxnRef = $msg->get_element_text("DpsTxnRef");
		$this->BillingId = $msg->get_element_text("BillingId");
		$this->DpsBillingId = $msg->get_element_text("DpsBillingId");
		$this->AmountSettlement = $msg->get_element_text("AmountSettlement");
		$this->CurrencySettlement = $msg->get_element_text("CurrencySettlement");
		$this->TxnMac = $msg->get_element_text("TxnMac");
		$this->ResponseText = $msg->get_element_text("ResponseText");
	}

	function getSuccess()
	{
		return $this->Success;
	}

	function getAuthCode()
	{
		return $this->AuthCode;
	}

	function getCardName()
	{
		return $this->CardName;
	}

	function getCardHolderName()
	{
		return $this->CardHolderName;
	}

	function getCardNumber()
	{
		return $this->CardNumber;
	}

	function getDateExpiry()
	{
		return $this->DateExpiry;
	}

	function getClientInfo()
	{
		return $this->ClientInfo;
	}

	function getDpsTxnRef()
	{
		return $this->DpsTxnRef;
	}

	function getDpsBillingId()
	{
		return $this->DpsBillingId;
	}

	function getAmountSettlement()
	{
		return $this->AmountSettlement;
	}

	function getCurrencySettlement()
	{
		$this->CurrencySettlement;
	}

	function getTxnMac()
	{
		return $this->TxnMac;
	}

	function getResponseText()
	{
		return $this->ResponseText;
	}
}

?>