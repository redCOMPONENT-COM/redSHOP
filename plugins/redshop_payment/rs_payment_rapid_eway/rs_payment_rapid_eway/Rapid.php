<?php
/**
 * Description of RapidAPI
 *
 * @author eWAY
 */
class RapidAPI
{
	var $APIConfig;

	function __construct()
	{
		//Load the configuration
		$this->APIConfig = parse_ini_file("config.ini");
	}

	public function setTestMode($testmode = 0)
	{
		$this->testmode = $testmode;
	}

	public function getAuthorizeData($api_username, $api_password)
	{
		$this->api_username = $api_username;
		$this->api_password = $api_password;
	}

	/**
	 * Description: Create Access Code
	 *
	 * @param CreateAccessCodeRequest $request
	 *
	 * @return StdClass An PHP Ojbect
	 */
	public function CreateAccessCode($request)
	{
		//Convert An Object to Target Formats
		if ($this->APIConfig['Request:Method'] != "SOAP")
		{
			if ($this->APIConfig['Request:Format'] == "XML")
			{
				if ($this->APIConfig['Request:Method'] != "RPC")
				{
					$request = Parser::Obj2XML($request);
				}
				else
				{
					$request = Parser::Obj2RPCXML("CreateAccessCode", $request);
				}
			}
			else
			{
				$i = 0;
				$tempClass = new stdClass;
				foreach ($request->Options->Option as $Option)
				{
					$tempClass->Options[$i] = $Option;
					$i++;
				}
				$request->Options = $tempClass->Options;
				$i = 0;
				$tempClass = new stdClass;
				foreach ($request->Items->LineItem as $LineItem)
				{
					$tempClass->Items[$i] = $LineItem;
					$i++;
				}
				$request->Items = $tempClass->Items;
				if ($this->APIConfig['Request:Method'] != "RPC")
				{
					$request = Parser::Obj2JSON($request);
				}
				else
				{
					$request = Parser::Obj2JSONRPC("CreateAccessCode", $request);
				}
			}
		}
		else
		{
			$request = Parser::Obj2ARRAY($request);
		}
		$method = 'CreateAccessCode' . $this->APIConfig['Request:Method'];
		$response = $this->$method($request);
		//Convert Response Back TO An Object
		if ($this->APIConfig['Request:Method'] != "SOAP")
		{
			if ($this->APIConfig['Request:Format'] == "XML")
			{
				if ($this->APIConfig['Request:Method'] != "RPC")
				{
					$result = Parser::XML2Obj($response);
				}
				else
				{
					$result = Parser::RPCXML2Obj($response);
				}
			}
			else
			{
				if ($this->APIConfig['Request:Method'] != "RPC")
				{
					$result = Parser::JSON2Obj($response);
				}
				else
				{
					$result = Parser::JSONRPC2Obj($response);
				}
			}
		}
		else
		{
			$result = $response;
		}

		return $result;
	}

	/**
	 * Description: Get Result with Access Code
	 *
	 * @param GetAccessCodeResultRequest $request
	 *
	 * @return StdClass An PHP Ojbect
	 */
	public function GetAccessCodeResult($request)
	{
		if ($this->APIConfig['ShowDebugInfo'])
		{
			echo "GetAccessCodeResult Request Object";
			var_dump($request);
		}
		//Convert An Object to Target Formats
		if ($this->APIConfig['Request:Method'] != "SOAP")
			if ($this->APIConfig['Request:Format'] == "XML")
				if ($this->APIConfig['Request:Method'] != "RPC")
					$request = Parser::Obj2XML($request);
				else
					$request = Parser::Obj2RPCXML("GetAccessCodeResult", $request);
			else
				if ($this->APIConfig['Request:Method'] != "RPC")
					$request = Parser::Obj2JSON($request);
				else
					$request = Parser::Obj2JSONRPC("GetAccessCodeResult", $request);
		else
			$request = Parser::Obj2ARRAY($request);

		//Build method name
		$method = 'GetAccessCodeResult' . $this->APIConfig['Request:Method'];
		//Is Debug Mode
		if ($this->APIConfig['ShowDebugInfo'])
		{
			echo "GetAccessCodeResult Request String";
			var_dump($request);
		}
		//Call to the method
		$response = $this->$method($request);
		//Is Debug Mode
		if ($this->APIConfig['ShowDebugInfo'])
		{
			echo "GetAccessCodeResult Response String";
			var_dump($response);
		}

		//Convert Response Back TO An Object
		if ($this->APIConfig['Request:Method'] != "SOAP")
			if ($this->APIConfig['Request:Format'] == "XML")
				if ($this->APIConfig['Request:Method'] != "RPC")
					$result = Parser::XML2Obj($response);
				else
				{
					$result = Parser::RPCXML2Obj($response);

					//Tweak the Options Obj to $obj->Options->Option[$i]->Value instead of $obj->Options[$i]->Value
					if (isset($result->Options))
					{
						$i = 0;
						$tempClass = new stdClass;
						foreach ($result->Options as $Option)
						{
							$tempClass->Option[$i]->Value = $Option->Value;
							$i++;
						}
						$result->Options = $tempClass;
					}
				}
			else
			{
				if ($this->APIConfig['Request:Method'] == "RPC")
					$result = Parser::JSONRPC2Obj($response);
				else
					$result = Parser::JSON2Obj($response);

				//Tweak the Options Obj to $obj->Options->Option[$i]->Value instead of $obj->Options[$i]->Value
				if (isset($result->Options))
				{
					$i = 0;
					$tempClass = new stdClass;
					foreach ($result->Options as $Option)
					{
						$tempClass->Option[$i]->Value = $Option->Value;
						$i++;
					}
					$result->Options = $tempClass;
				}
			}
		else
			$result = $response;

		//Is Debug Mode
		if ($this->APIConfig['ShowDebugInfo'])
		{
			echo "GetAccessCodeResult Response Object";
			var_dump($result);
		}

		return $result;
	}

	/**
	 * Description: Create Access Code Via SOAP
	 *
	 * @param Array $request
	 *
	 * @return StdClass An PHP Ojbect
	 */
	public function CreateAccessCodeSOAP($request)
	{
		try
		{
			if ($this->testmode)
			{
				$soapurl = $this->APIConfig["PaymentServiceSand.Soap"];
			}
			else
			{
				$soapurl = $this->APIConfig["PaymentService.Soap"];
			}
			$client = new SoapClient($soapurl, array(
				'trace'      => true,
				'exceptions' => true,
				'login'      => $this->api_username,
				'password'   => $this->api_password,
			));
			$result = $client->CreateAccessCode(array('request' => $request));
			//echo(htmlspecialchars($client->__getLastRequest()));
		}
		catch (Exception $e)
		{
			$lblError = $e->getMessage();
		}
		if (isset($lblError))
		{
			echo "<h2>CreateAccessCode SOAP Error: $lblError</h2><pre>";
			die();
		}
		else
		{
			return $result->CreateAccessCodeResult;
		}
	}

	/**
	 * Description: Get Result with Access Code Via SOAP
	 *
	 * @param Array $request
	 *
	 * @return StdClass An PHP Ojbect
	 */
	public function GetAccessCodeResultSOAP($request)
	{
		if ($this->testmode)
		{
			$soapurl = $this->APIConfig["PaymentServiceSand.Soap"];
		}
		else
		{
			$soapurl = $this->APIConfig["PaymentService.Soap"];
		}
		try
		{
			$client = new SoapClient($soapurl, array(
				'trace'      => true,
				'exceptions' => true,
				'login'      => $this->api_username,
				'password'   => $this->api_password,
			));
			$result = $client->GetAccessCodeResult(array('request' => $request));
		}
		catch (Exception $e)
		{
			$lblError = $e->getMessage();
		}

		if (isset($lblError))
		{
			echo "<h2>GetAccessCodeResult SOAP Error: $lblError</h2><pre>";
			die();
		}
		else
			return $result->GetAccessCodeResultResult;
	}


	/**
	 * Description: Create Access Code Via REST POST
	 *
	 * @param XML/JSON Format $request
	 *
	 * @return XML/JSON Format Response
	 */
	public function CreateAccessCodeREST($request)
	{
		if ($this->testmode)
		{
			$resturl = $this->APIConfig["PaymentServiceSand.REST"];
		}
		else
		{
			$resturl = $this->APIConfig["PaymentService.REST"];
		}
		$response = $this->PostToRapidAPI($resturl . "s", $request);

		return $response;
	}

	/**
	 * Description: Get Result with Access Code Via REST GET
	 *
	 * @param XML/JSON Format $request
	 *
	 * @return XML/JSON Format Response
	 */
	public function GetAccessCodeResultREST($request)
	{
		if ($this->testmode)
		{
			$resturl = $this->APIConfig["PaymentServiceSand.REST"];
		}
		else
		{
			$resturl = $this->APIConfig["PaymentService.REST"];
		}
		$response = $this->PostToRapidAPI($resturl . "/" . $_GET['AccessCode'], $request, false);

		return $response;
	}

	/**
	 * Description: Create Access Code Via HTTP POST
	 *
	 * @param XML/JSON Format $request
	 *
	 * @return XML/JSON Format Response
	 */
	public function CreateAccessCodePOST($request)
	{
		if ($this->testmode)
		{
			$posturl = $this->APIConfig["PaymentServiceSand.POST.CreateAccessCode"];
		}
		else
		{
			$posturl = $this->APIConfig["PaymentService.POST.CreateAccessCode"];
		}
		$response = $this->PostToRapidAPI($posturl, $request);

		return $response;
	}

	/**
	 * Description: Get Result with Access Code Via HTTP POST
	 *
	 * @param XML/JSON Format $request
	 *
	 * @return XML/JSON Format Response
	 */
	public function GetAccessCodeResultPOST($request)
	{
		if ($this->testmode)
		{
			$posturl = $this->APIConfig["PaymentServiceSand.POST.CreateAccessCode"];
		}
		else
		{
			$posturl = $this->APIConfig["PaymentService.POST.CreateAccessCode"];
		}
		$response = $this->PostToRapidAPI($posturl, $request);

		return $response;
	}

	/**
	 * Description: Create Access Code Via HTTP POST
	 *
	 * @param XML/JSON Format $request
	 *
	 * @return XML/JSON Format Response
	 */
	public function CreateAccessCodeRPC($request)
	{
		if ($this->testmode)
		{
			$rpcurl = $this->APIConfig["PaymentServiceSand.RPC"];
		}
		else
		{
			$rpcurl = $this->APIConfig["PaymentService.RPC"];
		}
		$response = $this->PostToRapidAPI($rpcurl, $request);

		return $response;
	}

	/**
	 * Description: Get Result with Access Code Via HTTP POST
	 *
	 * @param XML/JSON Format $request
	 *
	 * @return XML/JSON Format Response
	 */
	public function GetAccessCodeResultRPC($request)
	{
		if ($this->testmode)
		{
			$rpcurl = $this->APIConfig["PaymentServiceSand.RPC"];
		}
		else
		{
			$rpcurl = $this->APIConfig["PaymentService.RPC"];
		}
		$response = $this->PostToRapidAPI($rpcurl, $request);

		return $response;
	}

	/*
	 * Description A Function for doing a Curl GET/POST
	 */
	private function PostToRapidAPI($url, $request, $IsPost = true)
	{
		$ch = curl_init($url);
		if ($this->APIConfig['Request:Format'] == "XML")
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		else
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_username . ":" . $this->api_password);
		if ($IsPost)
			curl_setopt($ch, CURLOPT_POST, true);
		else
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);

		if (curl_errno($ch) != CURLE_OK)
		{
			echo "<h2>POST Error: " . curl_error($ch) . " URL: $url</h2><pre>";
			die();
		}
		else
		{
			curl_close($ch);

			return $response;
		}
	}
}
/**
 * Description of CreateAccessCodeRequest
 *
 *
 */
class CreateAccessCodeRequest
{
	/**
	 * @var Customer $Customer
	 */
	public $Customer;
	/**
	 * @var ShippingAddress $ShippingAddress
	 */
	public $ShippingAddress;
	public $Items;
	public $Options;
	/**
	 * @var Payment $Payment
	 */
	public $Payment;
	public $RedirectUrl;
	public $Method;
	private $CustomerIP;
	private $DeviceID;

	function __construct()
	{
		$this->Customer = new Customer();
		$this->ShippingAddress = new ShippingAddress();
		$this->Payment = new Payment();
		$this->CustomerIP = $_SERVER["SERVER_NAME"];
	}

}
/**
 * Description of Customer
 */
class Customer
{
	public $TokenCustomerID;
	public $Reference;
	public $Title;
	public $FirstName;
	public $LastName;
	public $CompanyName;
	public $JobDescription;
	public $Street1;
	public $Street2;
	public $City;
	public $State;
	public $PostalCode;
	public $Country;
	public $Email;
	public $Phone;
	public $Mobile;
	public $Comments;
	public $Fax;
	public $Url;
}

class ShippingAddress
{
	public $FirstName;
	public $LastName;
	public $Street1;
	public $Street2;
	public $City;
	public $State;
	public $Country;
	public $PostalCode;
	public $Email;
	public $Phone;
	public $ShippingMethod;
}

class Items
{
	public $LineItem = array();
}

class LineItem
{
	public $SKU;
	public $Description;
}

class Options
{
	public $Option = array();
}

class Option
{
	public $Value;
}

class Payment
{
	public $TotalAmount;
	/// <summary>The merchant's invoice number</summary>
	public $InvoiceNumber;
	/// <summary>merchants invoice description</summary>
	public $InvoiceDescription;
	/// <summary>The merchant's invoice reference</summary>
	public $InvoiceReference;
	/// <summary>The merchant's currency</summary>
	public $CurrencyCode;
}

class GetAccessCodeResultRequest
{
	public $AccessCode;
}

/*
 * Description A Class for conversion between different formats
 */

class Parser
{
	public static function Obj2JSON($obj)
	{
		return json_encode($obj);
	}

	public static function Obj2JSONRPC($APIAction, $obj)
	{
		if ($APIAction == "CreateAccessCode")
		{
			//Tweak the request object in order to generate a valid JSON-RPC format for RapidAPI.
			$obj->Payment->TotalAmount = (int) $obj->Payment->TotalAmount;
		}
		$tempClass = new stdClass;
		$tempClass->id = 1;
		$tempClass->method = $APIAction;
		$tempClass->params->request = $obj;

		return json_encode($tempClass);
	}

	public static function Obj2ARRAY($obj)
	{
		//var_dump($obj);
		return get_object_vars($obj);
	}

	public static function Obj2XML($obj)
	{
		$xml = new XmlWriter();
		$xml->openMemory();
		$xml->setIndent(true);
		$xml->startElement(get_class($obj));
		$xml->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
		$xml->writeAttribute("xmlns:xsd", "http://www.w3.org/2001/XMLSchema");
		self::getObject2XML($xml, $obj);
		$xml->endElement();
		$xml->endElement();

		return $xml->outputMemory(true);
	}

	public static function Obj2RPCXML($APIAction, $obj)
	{
		if ($APIAction == "CreateAccessCode")
		{
			//Tweak the request object in order to generate a valid XML-RPC format for RapidAPI.
			$obj->Payment->TotalAmount = (int) $obj->Payment->TotalAmount;
			$obj->Items = $obj->Items->LineItem;
			$obj->Options = $obj->Options->Option;
			$obj->Customer->TokenCustomerID = (float) (isset($obj->Customer->TokenCustomerID) ? $obj->Customer->TokenCustomerID : null);

			return str_replace("double>", "long>", xmlrpc_encode_request($APIAction, get_object_vars($obj)));
		}

		if ($APIAction == "GetAccessCodeResult")
		{
			return xmlrpc_encode_request($APIAction, get_object_vars($obj));
		}
	}

	public static function JSON2Obj($obj)
	{
		return json_decode($obj);
	}

	public static function JSONRPC2Obj($obj)
	{
		$tempClass = json_decode($obj);
		if (isset($tempClass->error))
		{
			$tempClass->Errors = $tempClass->error->data;

			return $tempClass;
		}

		return $tempClass->result;
	}

	public static function XML2Obj($obj)
	{
		//Strip the empty JSON object
		return json_decode(str_replace("{}", "null", json_encode(simplexml_load_string($obj))));
	}

	public static function RPCXML2Obj($obj)
	{
		return json_decode(json_encode(xmlrpc_decode($obj)));
	}

	public static function HasProperties($obj)
	{
		if (is_object($obj))
		{
			$reflect = new ReflectionClass($obj);
			$props = $reflect->getProperties();

			return !empty($props);
		}
		else
			return true;
	}

	private static function getObject2XML(XMLWriter $xml, $data)
	{
		foreach ($data as $key => $value)
		{
			if ($key == "TokenCustomerID" && $value == "")
			{
				$xml->startElement("TokenCustomerID");
				$xml->writeAttribute("xsi:nil", "true");
				$xml->endElement();
			}
			if (is_object($value))
			{
				$xml->startElement($key);
				self::getObject2XML($xml, $value);
				$xml->endElement();
				continue;
			}
			else if (is_array($value))
			{
				self::getArray2XML($xml, $key, $value);
			}

			if (is_string($value))
			{
				$xml->writeElement($key, $value);
			}
		}
	}

	private static function getArray2XML(XMLWriter $xml, $keyParent, $data)
	{
		foreach ($data as $key => $value)
		{
			if (is_string($value))
			{
				$xml->writeElement($keyParent, $value);
				continue;
			}

			if (is_numeric($key))
			{
				$xml->startElement($keyParent);
			}

			if (is_object($value))
			{
				self::getObject2XML($xml, $value);
			}
			else if (is_array($value))
			{
				$this->getArray2XML($xml, $key, $value);
				continue;
			}

			if (is_numeric($key))
			{
				$xml->endElement();
			}
		}
	}
}

?>
