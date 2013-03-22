<?php
/**
 * Desc    This is saple class to use for posting an order through chase payment gateway,
 *         Also it contains functions for making request VOID or REFUND.
 * @author Roshan P. Shahare, Pune, India.
 */
class Chase
{
	private $_IndustryType;
	private $_MessageType;
	private $_BIN;
	private $_MerchantID;
	private $_TerminalID;

	// Currency Information
	private $_CurrencyCode;
	private $_CurrencyExponent;

	// Chase settings url etc
	private $_chase_gateway_url;

	// Card Details
	public $CardBrand;
	public $AccountNum;
	public $Exp;
	public $CardSecValInd;
	public $CardSecVal;
	public $CCtype;

	// AVS Information
	public $AVSname;
	public $AVSzip; // 25541
	public $AVSaddress1; // 123 Test Street
	public $AVSaddress2; // Suite 350
	public $AVScity; // Test City
	public $AVSstate; //
	public $AVSphoneNum; // 800456451212

	// OREDER Information
	public $OrderID;
	public $Amount;
	public $OrbitalConnectionUsername;

	// OTHER Information
	public $Comments;
	public $Email;
	public $Phone;

	public $error;
	public $email_msg_to_send = '';
	public $receipt_msg_to_show = '';

	// Varibales to track
	public $chase_QuickResponse = '';
	public $chase_ProcStatus = '';
	public $chase_StatusMsg = '';

	//Responses from varius request like NewOrder, Reversal(VOID), REFUND
	public $arr_NewOrder_response; // New Order
	public $arr_Reversal_response; // VOID operation
	public $arr_Refund_response; // Refund type

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $currency
	 */
	public function __construct()
	{
	}

	/**
	 * parse response of gateway
	 *
	 * @param string $xmlResponse
	 *
	 * @return array
	 */

	public function parseXmlResponse($xmlResponse)
	{
		$newResArr = array();

		foreach ($xmlResponse as $val)
		{
			$tagval = $val['tag'];

			if (($val['tag'] != 'Response') && ($val['tag'] != 'NewOrderResp'))
			{
				if (isset($val['value']))
				{
					$newResArr[$tagval] = $val['value'];
				}
				else
				{
					$newResArr[$tagval] = '';
				}
			}
		}

		return $newResArr;
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	private function generate_order_xml()
	{
//echo $this->Amount;//die();
		$xml =
			'<?xml version="1.0" encoding="UTF-8"?>
			<Request>
			   <NewOrder>
			       <OrbitalConnectionUsername>' . $this->OrbitalConnectionUsername . '</OrbitalConnectionUsername>
			       <OrbitalConnectionPassword>' . $this->OrbitalConnectionPassword . '</OrbitalConnectionPassword>
			       <IndustryType>' . $this->IndustryType . '</IndustryType>
			       <MessageType>' . $this->MessageType . '</MessageType>
			       <BIN>' . $this->BIN . '</BIN>
			       <MerchantID>' . $this->MerchantID . '</MerchantID>
			       <TerminalID>' . $this->TerminalID . '</TerminalID>
			       <CardBrand></CardBrand>
			       <AccountNum>' . $this->AccountNum . '</AccountNum>
			       <Exp>' . $this->Exp . '</Exp>
			       <CurrencyCode>840</CurrencyCode>
			       <CurrencyExponent>2</CurrencyExponent>
			       <AVSzip>' . $this->AVSzip . '</AVSzip>
			       <AVSaddress1>' . $this->AVSaddress1 . '</AVSaddress1>
			       <AVSaddress2>' . $this->AVSaddress2 . '</AVSaddress2>
			       <AVScity>' . $this->AVScity . '</AVScity>
			       <AVSstate>' . $this->AVSstate . '</AVSstate>
			       <AVSphoneNum>' . $this->AVSphoneNum . '</AVSphoneNum>
			       <OrderID>' . $this->OrderID . '</OrderID>
			       <Amount>' . $this->Amount . '</Amount>
			   </NewOrder>
			</Request>';

		//echo $xml;die();
		return $xml;
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	public function post_an_order()
	{
		$xml = $this->generate_order_xml();
		$header = "POST /AUTHORIZE HTTP/1.0\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: application/PTI46\r\n";
		$header .= "Content-length: " . strlen($xml) . "\r\n";
		$header .= "Content-transfer-encoding: text\r\n";
		$header .= "Request-number: 1\r\n";
		$header .= "Document-type: Request\r\n";
		$header .= "Interface-Version: Test 1.4\r\n";
		$header .= "Connection: close \r\n\r\n";

		$header .= $xml;

		/** CURL Implementation **/

		$ch = curl_init();
		$this->chase_gateway_url;
		//curl_setopt($ch, CURLOPT_URL, "https://orbitalvar1.paymentech.net/authorize");
		curl_setopt($ch, CURLOPT_URL, $this->chase_gateway_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		if (curl_errno($ch))
		{
			//print curl_error($ch);
		}
		else
		{
			curl_close($ch);
		}

		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($xml_parser, $response, $vals, $index);
		xml_parser_free($xml_parser);

		/*****************/
		$parsedResArr = $this->parseXmlResponse($vals);

		$this->arr_NewOrder_response = $parsedResArr;
		$response_arr = array();

		if ($parsedResArr['ProcStatus'] == '0' && $parsedResArr['ApprovalStatus'] == '1' && $parsedResArr['RespCode'] == '00')
		{
			/*
			It is the only element that is returned in
			all response scenarios. It identifies whether transactions
			have successfully passed all of the Gateway edit checks.
			0 � Success
			*/
			//echo "successful";
			$response_arr['TxRefNum'] = $parsedResArr['TxRefNum'];
			$response_arr['transaction_sts'] = "success";
			$response_arr['message'] = $parsedResArr['StatusMsg'];

			//print_r($response_arr);
			return $response_arr;
		}
		else
		{
			// First Track Varibales
			$this->chase_QuickResponse = $parsedResArr['QuickResponse'];
			$this->chase_ProcStatus = $parsedResArr['ProcStatus'];
			$this->chase_StatusMsg = $parsedResArr['StatusMsg'];

			//echo "unsuccessful";
			switch ($parsedResArr['RespCode'])
			{
				case '04' :
				{
					$this->error[] = 'Card is in Decline State';
					break;
				}
				case '05' :
				{
					$this->error[] = 'Card is not Honored';
					break;
				}
				case '06' :
				{
					$this->error[] = 'Unsupported Error in Cart';
					break;
				}
				case '13' :
				{
					$this->error[] = 'Bad Amount';
					break;
				}
				case '42' :
				{
					$this->error[] = 'Account Not Active';
					break;
				}
				case '33' :
				{
					$this->error[] = 'Card is expired';
					break;
				}
				case '68' :
				{
					$this->error[] = 'Invalid CC Number';
					break;
				}
				default    :
					{
					break;
					//$this->error[] ='Resp Code - '.$parsedResArr['RespCode'];
					}
			}

			if ($parsedResArr['CVV2RespCode'] != 'M')
			{
				switch ($parsedResArr['CVV2RespCode'])
				{
					case 'N' :
					{
						$this->error[] = 'The CVV is not matched  with the Card No';
						break;
					}
					case 'P' :
					{
						$this->error[] = 'The CVV is Not Processed';
						break;
					}
					case 'S' :
					{
						$this->error[] = 'The CVV Should have been present';
						break;
					}
					case 'U' :
					{
						$this->error[] = 'The CVV is Unsupported by Issuer/Issuer unable to process request';
						break;
					}
					case 'I' :
					{
						break;
					}
					case 'Y' :
					{
						$this->error[] = 'The CVV is Invalid';
						break;
					}
					default    :
						break; //$this->error[] = 'CVV2RespCode Code - '.$parsedResArr['CVV2RespCode'];

				}
			}

			switch ($parsedResArr['AVSRespCode'])
			{
				case 'D' :
				{
					$this->error[] = 'The Zipcode is not Match with the Card';
					break;
				}
				case 'G' :
				{
					$this->error[] = 'The Zipcode is not Match with the Card';
					break;
				}
				case 4 :
				{
					$this->error[] = 'Issuer does not participate in AVS';
					break;
				}
				default    :
					break; //$this->error[] = 'AVSRespCode Code - '.$parsedResArr['AVSRespCode'];
			}

			$response_arr['TxRefNum'] = 0;
			$response_arr['transaction_sts'] = "fail";
			$response_arr['message'] = $parsedResArr['StatusMsg'];

			return $response_arr;
		}
	}

// Function to capture

	public function capture_an_order()
	{
		$xml =
			'<?xml version="1.0" encoding="UTF-8"?>
			<Request>
			   <MarkForCapture>
			       <OrbitalConnectionUsername>' . $this->OrbitalConnectionUsername . '</OrbitalConnectionUsername>
			       <OrbitalConnectionPassword>' . $this->OrbitalConnectionPassword . '</OrbitalConnectionPassword>
			       <OrderID>' . $this->OrderID . '</OrderID>
			       <Amount>' . $this->Amount . '</Amount>
			       <BIN>' . $this->BIN . '</BIN>
			       <MerchantID>' . $this->MerchantID . '</MerchantID>
			       <TerminalID>' . $this->TerminalID . '</TerminalID>
			       <TxRefNum>' . $this->TxRefNum . '</TxRefNum>
			   </MarkForCapture>
			</Request>';

		$header = "POST /AUTHORIZE HTTP/1.0\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: application/PTI46\r\n";
		$header .= "Content-length: " . strlen($xml) . "\r\n";
		$header .= "Content-transfer-encoding: text\r\n";
		$header .= "Request-number: 1\r\n";
		$header .= "Document-type: Request\r\n";
		$header .= "Interface-Version: Test 1.4\r\n";
		$header .= "Connection: close \r\n\r\n";

		$header .= $xml;

		/** CURL Implementation **/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->chase_gateway_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		if (curl_errno($ch))
		{
			//print curl_error($ch);
		}
		else
		{
			curl_close($ch);
		}

		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($xml_parser, $response, $vals, $index);
		xml_parser_free($xml_parser);

		/*****************/
		$parsedResArr = $this->parseXmlResponse($vals);

		$this->arr_Reversal_response = $parsedResArr;

		//print_r($this->arr_Reversal_response);
		$response_arr = array();

		if ($parsedResArr['ProcStatus'] == 0)
		{
			$response_arr['ProcStatus'] = 0;
			$response_arr['StatusMsg'] = $parsedResArr['StatusMsg'];

			return $response_arr;
			//return true;
		}
		else
		{
			$response_arr['ProcStatus'] = 1;
			$response_arr['StatusMsg'] = $parsedResArr['StatusMsg'];

			return $response_arr;
			//return false;
		}
	}

}