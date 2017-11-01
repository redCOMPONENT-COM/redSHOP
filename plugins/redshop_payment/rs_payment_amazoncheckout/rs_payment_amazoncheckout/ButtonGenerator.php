<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once 'SignatureUtils.php';

/**
 * Class ButtonGenerator
 *
 * @since  1.5
 */
class ButtonGenerator
{
	const SIGNATURE_KEYNAME = "signature";
	const SIGNATURE_METHOD_KEYNAME = "signatureMethod";
	const SIGNATURE_VERSION_KEYNAME = "signatureVersion";
	const HMAC_SHA1_ALGORITHM = "HmacSHA1";
	const HMAC_SHA256_ALGORITHM = "HmacSHA256";
	const SIGNATURE_VERSION = "2";
	const COBRANDING_STYLE = "logo";

	private static $httpMethod = "POST";

	public static $SANDBOX_END_POINT = "https://authorize.payments-sandbox.amazon.com/pba/paypipeline";

	public static $SANDBOX_IMAGE_LOCATION = "https://authorize.payments-sandbox.amazon.com/pba/images/payNowButton.png";

	public static $PROD_END_POINT = "https://authorize.payments.amazon.com/pba/paypipeline";

	public static $PROD_IMAGE_LOCATION = "https://authorize.payments.amazon.com/pba/images/payNowButton.png";

	/**
	 * Function creates a Map of key-value pairs for all valid values passed to the function
	 *
	 * @param   string  $accessKey               Put your Access Key here
	 * @param   string  $amount                  Enter the amount you want to collect for the item
	 * @param   string  $description             description - Enter a description of the item
	 * @param   string  $referenceId             Optionally enter an ID that uniquely identifies this transaction for your records
	 * @param   string  $immediateReturn         Optionally, enter "1" if you want to skip the final status page in Amazon Payments,
	 * @param   string  $returnUrl               Optionally enter the URL where buyers should be redirected after they complete the transaction
	 * @param   string  $abandonUrl              Optionally, enter the URL where senders should be redirected if they cancel their transaction
	 * @param   string  $processImmediate        Optionally, enter "1" if you want to settle the transaction immediately else "0". Default value is "1"
	 * @param   string  $ipnUrl                  Optionally, type the URL of your host page to which Amazon Payments should send the IPN transaction information.
	 * @param   string  $collectShippingAddress  Optionally, enter "1" if you want Amazon Payments to return the buyer's shipping address as part of the transaction information.
	 * @param   string  $signatureMethod         Valid values are  HmacSHA256 and HmacSHA1
	 * @param   array   $formHiddenInputs        Optionally form params
	 *
	 * @return  array  A map of key of key-value pair for all non null parameters
	 *
	 * @throws Exception
	 */
	public static function getSimplePayStandardParams($accessKey, $amount, $description, $referenceId, $immediateReturn,
		$returnUrl, $abandonUrl, $processImmediate, $ipnUrl, $collectShippingAddress,
		$signatureMethod, $formHiddenInputs = array())
	{
		$cobrandingStyle = self::COBRANDING_STYLE;

		if ($accessKey != null)
		{
			$formHiddenInputs["accessKey"] = $accessKey;
		}
		else
		{
			throw new Exception("Accesskey is Required");
		}

		if ($amount != null)
		{
			$formHiddenInputs["amount"] = $amount;
		}
		else
		{
			throw new Exception("Amount is required");
		}

		if ($description != null)
		{
			$formHiddenInputs["description"] = $description;
		}
		else
		{
			throw new Exception("Description is required");
		}

		if ($signatureMethod != null)
		{
			$formHiddenInputs[self::SIGNATURE_METHOD_KEYNAME] = $signatureMethod;
		}
		else
		{
			throw new Exception("Signature Method is required");
		}

		if ($referenceId != null)
		{
			$formHiddenInputs["referenceId"] = $referenceId;
		}

		if ($immediateReturn != null)
		{
			$formHiddenInputs["immediateReturn"] = $immediateReturn;
		}

		if ($returnUrl != null)
		{
			$formHiddenInputs["returnUrl"] = $returnUrl;
		}

		if ($abandonUrl != null)
		{
			$formHiddenInputs["abandonUrl"] = $abandonUrl;
		}

		if ($processImmediate != null)
		{
			$formHiddenInputs["processImmediate"] = $processImmediate;
		}

		if ($ipnUrl != null)
		{
			$formHiddenInputs["ipnUrl"] = $ipnUrl;
		}

		if ($cobrandingStyle != null)
		{
			$formHiddenInputs["cobrandingStyle"] = $cobrandingStyle;
		}

		if ($collectShippingAddress != null)
		{
			$formHiddenInputs["collectShippingAddress"] = $collectShippingAddress;
		}

		$formHiddenInputs[self::SIGNATURE_VERSION_KEYNAME] = self::SIGNATURE_VERSION;

		return $formHiddenInputs;
	}

	/**
	 * Creates a form from the provided key-value pairs
	 *
	 * @param   array   $formHiddenInputs  A map of key of key-value pair for all non null parameters
	 * @param   string  $endPoint          The Endpoint to be used based on environment selected
	 * @param   string  $imageLocation     The imagelocation based on environment
	 *
	 * @return  string  An html form created using the key-value pairs
	 */
	public static function getSimplePayStandardForm(array $formHiddenInputs, $endPoint, $imageLocation)
	{
		$form = "";
		$form .= "<form action=\"";
		$form .= $endPoint;
		$form .= "\" method=\"";
		$form .= self::$httpMethod . "\" id=\"amazoncheckout\">\n";
		$form .= "<input type=\"image\" src=\"" . $imageLocation . "\" border=\"0\">\n";

		foreach ($formHiddenInputs as $name => $value)
		{
			$form .= "<input type=\"hidden\" name=\"$name";
			$form .= "\" value=\"$value";
			$form .= "\" >\n";
		}

		$form .= "</form>\n";

		return $form;
	}

	/**
	 * Function Generates the html form
	 *
	 * @param   string  $accessKey               Put your Access Key here
	 * @param   string  $secretKey               Put your secret Key here
	 * @param   string  $amount                  Enter the amount you want to collect for the ite
	 * @param   string  $description             description - Enter a description of the item
	 * @param   string  $referenceId             Optionally enter an ID that uniquely identifies this transaction for your records
	 * @param   string  $immediateReturn         Optionally, enter "1" if you want to skip the final status page in Amazon Payments,
	 * @param   string  $returnUrl               Optionally enter the URL where buyers should be redirected after they complete the transaction
	 * @param   string  $abandonUrl              Optionally, enter the URL where senders should be redirected if they cancel their transaction
	 * @param   string  $processImmediate        Optionally, enter "1" if you want to settle the transaction immediately else "0". Default value is "1"
	 * @param   string  $ipnUrl                  Optionally, type the URL of your host page to which Amazon Payments should send the IPN transaction information.
	 * @param   string  $collectShippingAddress  Optionally, enter "1" if you want Amazon Payments to return the buyer's shipping address as part of the transaction information
	 * @param   string  $signatureMethod         Valid values are  HmacSHA256 and HmacSHA1
	 * @param   string  $environment             Sets the environment where your form will point to can be "sandbox" or "prod"
	 * @param   array   $otherParams             Optionally form params
	 *
	 * @return  void  A map of key of key-value pair for all non null parameters
	 *
	 * @throws Exception
	 */

	public static function GenerateForm($accessKey, $secretKey, $amount, $description, $referenceId, $immediateReturn,
		$returnUrl, $abandonUrl, $processImmediate, $ipnUrl, $collectShippingAddress,
		$signatureMethod, $environment, $otherParams = array())
	{
		if ($environment != "1")
		{
			$endPoint = self::$PROD_END_POINT;
			$imageLocation = self::$PROD_IMAGE_LOCATION;
		}
		else
		{
			$endPoint = self::$SANDBOX_END_POINT;
			$imageLocation = self::$SANDBOX_IMAGE_LOCATION;
		}

		$params = self::getSimplePayStandardParams(
			$accessKey, $amount, $description, $referenceId, $immediateReturn,
			$returnUrl, $abandonUrl, $processImmediate, $ipnUrl, $collectShippingAddress, $signatureMethod, $otherParams
		);

		$serviceEndPoint = parse_url($endPoint);
		$signature = SignatureUtils::signParameters(
			$params, $secretKey,
			self::$httpMethod, $serviceEndPoint['host'], $serviceEndPoint['path'], $signatureMethod
		);
		$params[self::SIGNATURE_KEYNAME] = $signature;
		$simplePayForm = self::getSimplePayStandardForm($params, $endPoint, $imageLocation);
		print $simplePayForm . "\n";
	}
}
