<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class SignatureUtils
 *
 * @since  1.5
 */
class SignatureUtils
{
	/**
	 * Computes RFC 2104-compliant HMAC signature for request parameters
	 * Implements AWS Signature, as per following spec:
	 *
	 * In Signature Version 2, string to sign is based on following:
	 *
	 *    1. The HTTP Request Method followed by an ASCII newline (%0A)
	 *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
	 *    3. The URL encoded HTTP absolute path component of the URI
	 *       (up to but not including the query string parameters);
	 *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
	 *    4. The concatenation of all query string components (names and values)
	 *       as UTF-8 characters which are URL encoded as per RFC 3986
	 *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
	 *       Parameter names are separated from their values by the '=' character
	 *       (ASCII character 61), even if the value is empty.
	 *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
	 *
	 */

	/**
	 * This function call appropriate functions for calculating signature
	 *
	 * @param   array   $parameters  Request parameters
	 * @param   string  $key         Secret key
	 * @param   string  $httpMethod  httpMethos used
	 * @param   string  $host        Host
	 * @param   string  $requestURI  Request URI
	 * @param   string  $algorithm   Algorithm
	 *
	 * @return  string
	 */
	public static function signParameters(array $parameters, $key, $httpMethod, $host, $requestURI, $algorithm)
	{
		$stringToSign = null;
		$stringToSign = self::_calculateStringToSignV2($parameters, $httpMethod, $host, $requestURI);

		return self::_sign($stringToSign, $key, $algorithm);
	}

	/**
	 * Calculate String to Sign for SignatureVersion 2
	 *
	 * @param   array   $parameters  Request parameters
	 * @param   string  $httpMethod  httpMethos used
	 * @param   string  $hostHeader  HostHeader
	 * @param   string  $requestURI  Request URI
	 *
	 * @return  string  to Sign
	 *
	 * @throws Exception
	 */
	private static function _calculateStringToSignV2(array $parameters, $httpMethod, $hostHeader, $requestURI)
	{
		if ($httpMethod == null)
		{
			throw new Exception("HttpMethod cannot be null");
		}

		$data = $httpMethod;
		$data .= "\n";

		if ($hostHeader == null)
		{
			$hostHeader = "";
		}

		$data .= $hostHeader;
		$data .= "\n";

		if (!isset ($requestURI))
		{
			$requestURI = "/";
		}

		$uriencoded = implode("/", array_map(array("SignatureUtils", "_urlencode"), explode("/", $requestURI)));
		$data .= $uriencoded;
		$data .= "\n";

		uksort($parameters, 'strcmp');
		$data .= self::_getParametersAsString($parameters);

		return $data;
	}

	/**
	 * URL encode
	 *
	 * @param   string  $value  Value for encode
	 *
	 * @return  mixed
	 */
	private static function _urlencode($value)
	{
		return str_replace('%7E', '~', rawurlencode($value));
	}

	/**
	 * Convert parameters to Url encoded query string
	 *
	 * @param   array  $parameters  Request parameters
	 *
	 * @return  string
	 */
	public static function _getParametersAsString(array $parameters)
	{
		$queryParameters = array();

		foreach ($parameters as $key => $value)
		{
			$queryParameters[] = $key . '=' . self::_urlencode($value);
		}

		return implode('&', $queryParameters);
	}

	/**
	 * Computes RFC 2104-compliant HMAC signature.
	 *
	 * @param   string  $data       Data
	 * @param   string  $key        Key
	 * @param   string  $algorithm  Algorithm
	 *
	 * @return   string
	 *
	 * @throws Exception
	 */
	private static function _sign($data, $key, $algorithm)
	{
		if ($algorithm === 'HmacSHA1')
		{
			$hash = 'sha1';
		}
		elseif ($algorithm === 'HmacSHA256')
		{
			$hash = 'sha256';
		}
		else
		{
			throw new Exception("Non-supported signing method specified");
		}

		return base64_encode(
			hash_hmac($hash, $data, $key, true)
		);
	}
}
