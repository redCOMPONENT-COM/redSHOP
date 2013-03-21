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

class GoogleResponse
{
	var $merchant_id;
	var $merchant_key;
	var $schema_url;

	var $log;
	var $response;
	var $root = '';
	var $data = array();
	var $xml_parser;

	function GoogleResponse($id = null, $key = null)
	{
		$this->merchant_id = $id;
		$this->merchant_key = $key;
		$this->schema_url = "http://checkout.google.com/schema/2";
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '.');
		require_once('googlelog.php');
		$this->log = new GoogleLog('', '', L_OFF);
	}

	function SetMerchantAuthentication($id, $key)
	{
		$this->merchant_id = $id;
		$this->merchant_key = $key;
	}

	function SetLogFiles($errorLogFile, $messageLogFile, $logLevel = L_ERR_RQST)
	{
		$this->log = new GoogleLog($errorLogFile, $messageLogFile, $logLevel);
	}

	function HttpAuthentication($headers = null, $die = true)
	{
		if (!is_null($headers))
		{
			$_SERVER = $headers;
		}
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
		{
			$compare_mer_id = $_SERVER['PHP_AUTH_USER'];
			$compare_mer_key = $_SERVER['PHP_AUTH_PW'];
		}
		//  IIS Note::  For HTTP Authentication to work with IIS,
		// the PHP directive cgi.rfc2616_headers must be set to 0 (the default value).
		else if (isset($_SERVER['HTTP_AUTHORIZATION']))
		{
			list($compare_mer_id, $compare_mer_key) = explode(':',
				base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'],
					strpos($_SERVER['HTTP_AUTHORIZATION'], " ") + 1)));
		}
		else if (isset($_SERVER['Authorization']))
		{
			list($compare_mer_id, $compare_mer_key) = explode(':',
				base64_decode(substr($_SERVER['Authorization'],
					strpos($_SERVER['Authorization'], " ") + 1)));
		}
		else
		{
			$this->SendFailAuthenticationStatus(
				"Failed to Get Basic Authentication Headers", $die);

			return false;
		}
		if ($compare_mer_id != $this->merchant_id
			|| $compare_mer_key != $this->merchant_key
		)
		{
			$this->SendFailAuthenticationStatus("Invalid Merchant Id/Key Pair", $die);

			return false;
		}

		return true;
	}

	function ProcessMerchantCalculations($merchant_calc)
	{
		$this->SendOKStatus();
		$result = $merchant_calc->GetXML();
		echo $result;
	}

// Notification API
	function ProcessNewOrderNotification()
	{
		$this->SendAck();
	}

	function ProcessRiskInformationNotification()
	{
		$this->SendAck();
	}

	function ProcessOrderStateChangeNotification()
	{
		$this->SendAck();
	}

//   Amount Notifications
	function ProcessChargeAmountNotification()
	{
		$this->SendAck();
	}

	function ProcessRefundAmountNotification()
	{
		$this->SendAck();
	}

	function ProcessChargebackAmountNotification()
	{
		$this->SendAck();
	}

	function ProcessAuthorizationAmountNotification()
	{
		$this->SendAck();
	}

	function SendOKStatus()
	{
		header('HTTP/1.0 200 OK');
	}

	function SendFailAuthenticationStatus($msg = "401 Unauthorized Access",
	                                      $die = true)
	{
		$this->log->logError($msg);
		header('HTTP/1.0 401 Unauthorized');
		if ($die)
		{
			die($msg);
		}
		else
		{
			echo $msg;
		}
	}

	function SendBadRequestStatus($msg = "400 Bad Request", $die = true)
	{
		$this->log->logError($msg);
		header('HTTP/1.0 400 Bad Request');
		if ($die)
		{
			die($msg);
		}
		else
		{
			echo $msg;
		}
	}

	function SendServerErrorStatus($msg = "500 Internal Server Error",
	                               $die = true)
	{
		$this->log->logError($msg);
		header('HTTP/1.0 500 Internal Server Error');
		if ($die)
		{
			die($msg);
		}
		else
		{
			echo $msg;
		}
	}

	function SendAck($die = true)
	{
		$this->SendOKStatus();
		$acknowledgment = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
			"<notification-acknowledgment xmlns=\"" .
			$this->schema_url . "\"/>";
		$this->log->LogResponse($acknowledgment);
		if ($die)
		{
			die($acknowledgment);
		}
		else
		{
			echo $acknowledgment;
		}
	}

	function GetParsedXML($request = null)
	{
		if (!is_null($request))
		{
			$this->log->LogRequest($request);
			$this->response = $request;
			ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '.');
			require_once('xml-processing/xmlparser.php');

			$this->xml_parser = new XmlParser($request);
			$this->root = $this->xml_parser->GetRoot();
			$this->data = $this->xml_parser->GetData();
		}

		return array($this->root, $this->data);
	}
}

?>