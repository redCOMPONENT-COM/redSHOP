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

define('ENTER', "\r\n");
define('DOUBLE_ENTER', ENTER . ENTER);
// Max size of the Google Messsage string
define('GOOGLE_MESSAGE_LENGTH', 254);

class GoogleRequest
{
	var $merchant_id;
	var $merchant_key;
	var $currency;
	var $server_url;
	var $schema_url;
	var $base_url;
	var $checkout_url;
	var $checkout_diagnose_url;
	var $request_url;
	var $request_diagnose_url;
	var $merchant_checkout;
	var $proxy = array();

	var $log;

	function GoogleRequest($id, $key, $server_type = "sandbox", $currency = "USD")
	{
		$this->merchant_id = $id;
		$this->merchant_key = $key;
		$this->currency = $currency;

		if ($server_type == "sandbox")
			$this->server_url = "https://sandbox.google.com/checkout/";
		else
			$this->server_url = "https://checkout.google.com/";

		$this->schema_url = "http://checkout.google.com/schema/2";
		$this->base_url = $this->server_url . "cws/v2/Merchant/" .
			$this->merchant_id;
		$this->checkout_url = $this->base_url . "/checkout";
		$this->checkout_diagnose_url = $this->base_url . "/checkout/diagnose";
		$this->request_url = $this->base_url . "/request";
		$this->request_diagnose_url = $this->base_url . "/request/diagnose";
		$this->merchant_checkout = $this->base_url . "/merchantCheckout";

		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '.');
		require_once 'googlelog.php';
		$this->log = new GoogleLog('', '', L_OFF);

	}

	function SetLogFiles($errorLogFile, $messageLogFile, $logLevel = L_ERR_RQST)
	{
		$this->log = new GoogleLog($errorLogFile, $messageLogFile, $logLevel);
	}

	function SendServer2ServerCart($xml_cart, $die = true)
	{
		list($status, $body) = $this->SendReq($this->merchant_checkout,
			$this->GetAuthenticationHeaders(), $xml_cart);

		if ($status != 200)
		{
			return array($status, $body);
		}
		else
		{
			ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '.');
			require_once 'xml-processing/xmlparser.php';

			$xml_parser = new XmlParser($body);
			$root = $xml_parser->GetRoot();
			$data = $xml_parser->GetData();

			$this->log->logRequest("Redirecting to: " .
				$data[$root]['redirect-url']['VALUE']);
			header('Location: ' . $data[$root]['redirect-url']['VALUE']);

			if ($die)
			{
				die($data[$root]['redirect-url']['VALUE']);
			}
			else
			{
				return array(200, $data[$root]['redirect-url']['VALUE']);
			}
		}
	}

	function SendChargeOrder($google_order, $amount = '')
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <charge-order xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">";

		if ($amount != '')
		{
			$postargs .= "<amount currency=\"" . $this->currency . "\">" .
				$amount . "</amount>";
		}

		$postargs .= "</charge-order>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendRefundOrder($google_order, $amount, $reason,
	                         $comment)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <refund-order xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">
                  <reason>" . $reason . "</reason>
                  <amount currency=\"" . $this->currency . "\">" .
			htmlentities($amount) . "</amount>
                  <comment>" . htmlentities($comment) . "</comment>
                  </refund-order>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendCancelOrder($google_order, $reason, $comment)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <cancel-order xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">
                  <reason>" . htmlentities($reason) . "</reason>
                  <comment>" . htmlentities($comment) . "</comment>
                  </cancel-order>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendTrackingData($google_order, $carrier,
	                          $tracking_no)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <add-tracking-data xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">
                  <tracking-data>
                  <carrier>" . htmlentities($carrier) . "</carrier>
                  <tracking-number>" . $tracking_no . "</tracking-number>
                  </tracking-data>
                  </add-tracking-data>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendMerchantOrderNumber($google_order,
	                                 $merchant_order)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <add-merchant-order-number xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">
                  <merchant-order-number>" . $merchant_order .
			"</merchant-order-number>
			  </add-merchant-order-number>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendBuyerMessage($google_order, $message,
	                          $send_mail = "true")
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <send-buyer-message xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">
                  <message>" .
			(substr(htmlentities(strip_tags($message)), 0, GOOGLE_MESSAGE_LENGTH))
			. "</message>
                  <send-email>" . $send_mail . "</send-email>
                  </send-buyer-message>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendProcessOrder($google_order)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <process-order xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\"/> ";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendDeliverOrder($google_order, $carrier = "",
	                          $tracking_no = "", $send_mail = "true")
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <deliver-order xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\">";

		if ($carrier != "" && $tracking_no != "")
		{
			$postargs .= "<tracking-data>
                  <carrier>" . htmlentities($carrier) . "</carrier>
            <tracking-number>" . htmlentities($tracking_no) . "</tracking-number>
                  </tracking-data>";
		}

		$postargs .= "<send-email>" . $send_mail . "</send-email>
                  </deliver-order>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendArchiveOrder($google_order)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <archive-order xmlns=\"" . $this->schema_url .
			"\" google-order-number=\"" . $google_order . "\"/>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function SendUnarchiveOrder($google_order)
	{
		$postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                  <unarchive-order xmlns=\"" .
			$this->schema_url . "\" google-order-number=\"" .
			$google_order . "\"/>";

		return $this->SendReq($this->request_url,
			$this->GetAuthenticationHeaders(), $postargs);
	}

	function GetAuthenticationHeaders()
	{
		$headers = array();
		$headers[] = "Authorization: Basic " . base64_encode(
			$this->merchant_id . ':' . $this->merchant_key);
		$headers[] = "Content-Type: application/xml; charset=UTF-8";
		$headers[] = "Accept: application/xml; charset=UTF-8";
		$headers[] = "User-Agent: GC-PHP-Sample_code (v1.2beta/ropu)";

		return $headers;
	}

	/**
	 * SetProxy
	 *
	 * @param $proxy: Array('host' => 'proxy-host', 'port' => 'proxy-port');
	 *
	 */
	function SetProxy($proxy = array())
	{
		if (is_array($proxy) && count($proxy))
		{
			$this->proxy['host'] = $proxy['host'];
			$this->proxy['port'] = $proxy['port'];
		}
	}

	function SendReq($url, $header_arr, $postargs)
	{
		// Get the curl session object
		$session = curl_init($url);
		$this->log->LogRequest($postargs);
		// Set the POST options.
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_HTTPHEADER, $header_arr);
		curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
		curl_setopt($session, CURLOPT_HEADER, true);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

		if (is_array($this->proxy) && count($this->proxy))
		{
			curl_setopt($session, CURLOPT_PROXY,
				$this->proxy['host'] . ":" . $this->proxy['port']);
		}
		// Do the POST and then close the session
		$response = curl_exec($session);

		if (curl_errno($session))
		{
			$this->log->LogError($response);

			return array("CURL_ERR", curl_error($session));
		}
		else
		{
			curl_close($session);
		}

		$heads = $this->parse_headers($response);
		$body = $this->get_body_x($response);

//      // Get HTTP Status code from the response
		$status_code = array();
		preg_match('/\d\d\d/', $heads[0], $status_code);

		// Check for errors
		switch ($status_code[0])
		{
			case 200:
				// Success
				$this->log->LogResponse($response);

				return array(200, $body);
				break;
			case 503:
				$this->log->LogError($response);

				return array(503, htmlentities($body));
				break;
			case 403:
				$this->log->LogError($response);

				return array(403, htmlentities($body));
				break;
			case 400:
				$this->log->LogError($response);

				return array(400, htmlentities($body));
				break;
			default:
				$this->log->LogError($response);

				return array("ERR", htmlentities($body));
				break;
		}
	}

// Private functions
// Function to get HTTP headers,
// Will also work with HTTP 200 status added by some proxy servers
	function parse_headers($message)
	{
		$head_end = strpos($message, DOUBLE_ENTER);
		$headers = $this->get_headers_x(substr($message, 0,
			$head_end + strlen(DOUBLE_ENTER)));

		if (!is_array($headers) || empty($headers))
		{
			return null;
		}

		if (!preg_match('%[HTTP/\d\.\d] (\d\d\d)%', $headers[0], $status_code))
		{
			return null;
		}
		switch ($status_code[1])
		{
			case '200':
				$parsed = $this->parse_headers(substr($message,
					$head_end + strlen(DOUBLE_ENTER)));

				return is_null($parsed) ? $headers : $parsed;
				break;
			default:
				return $headers;
				break;
		}
	}

	function get_headers_x($heads, $format = 0)
	{
		$fp = explode(ENTER, $heads);

		foreach ($fp as $header)
		{
			if ($header == "")
			{
				$eoheader = true;
				break;
			}
			else
			{
				$header = trim($header);
			}

			if ($format == 1)
			{
				$key = array_shift(explode(':', $header));

				if ($key == $header)
				{
					$headers[] = $header;
				}
				else
				{
					$headers[$key] = substr($header, strlen($key) + 2);
				}
				unset($key);
			}
			else
			{
				$headers[] = $header;
			}
		}

		return $headers;
	}

	function get_body_x($heads)
	{
		$fp = explode(DOUBLE_ENTER, $heads, 2);

		return $fp[1];
	}
}

?>