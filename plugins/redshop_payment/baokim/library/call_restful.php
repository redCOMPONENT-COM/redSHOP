<?php/** * @package     RedSHOP * @subpackage  Plugin * * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved. * @license     GNU General Public License version 2 or later; see LICENSE */class CallRestful{	/**	 * Gọi API Bảo Kim thực hiện thanh toán với thẻ ngân hàng	 *	 * @param $method Sử dụng phương thức GET, POST cho với từng API	 * @param $data Dữ liệu gửi đên Bảo Kim	 * @param $api API được gọi sang Bảo Kim	 * @param $object WC_Gateway_Baokim_Pro	 * @var $object WC_Gateway_Baokim_Pro	 * @return mixed	 */	function call_API($method, $data, $api)	{		$business = EMAIL_BUSINESS;		$username = API_USER;		$password = API_PWD;		$private_key = PRIVATE_KEY_BAOKIM;		$server = BAOKIM_URL;		$arrayPost = array();		$arrayGet = array();		ksort($data);		if ($method == 'GET')		{			$arrayGet = $data;		}		else		{			$arrayPost = $data;		}		$signature = $this->makeBaoKimAPISignature($method, $api, $arrayGet, $arrayPost, $private_key);		$url = $server . $api . '?' . 'signature=' . $signature . (($method == "GET") ? $this->createRequestUrl($data) : '');		$curl = curl_init($url);		// Form		curl_setopt($curl, CURLOPT_HEADER, 0);		curl_setopt($curl, CURLINFO_HEADER_OUT, 1);		curl_setopt($curl, CURLOPT_TIMEOUT, 30);		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST | CURLAUTH_BASIC);		curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);		if ($method == 'POST')		{			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->httpBuildQuery($arrayPost));		}		$result = curl_exec($curl);		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);		$error = curl_error($curl);		if (empty($result))		{			return array(				'status' => $status,				'error' => $error			);		}		return $result;	}	/**	 * Hàm thực hiện việc tạo chữ ký với dữ liệu gửi đến Bảo Kim	 *	 * @param $method	 * @param $url	 * @param array $getArgs	 * @param array $postArgs	 * @param $priKeyFile	 * @return string	 */	private function makeBaoKimAPISignature($method, $url, $getArgs = array(), $postArgs = array(), $priKeyFile)	{		if (strpos($url, '?') !== false)		{			list($url, $get) = explode('?', $url);			parse_str($get, $get);			$getArgs = array_merge($get, $getArgs);		}		ksort($getArgs);		ksort($postArgs);		$method = strtoupper($method);		$data = $method . '&' . urlencode($url) . '&' . urlencode(http_build_query($getArgs)) . '&' . urlencode(http_build_query($postArgs));		$priKey = openssl_get_privatekey($priKeyFile);		assert('$priKey !== false');		$x = openssl_sign($data, $signature, $priKey, OPENSSL_ALGO_SHA1);		assert('$x !== false');		return urlencode(base64_encode($signature));	}	private function httpBuildQuery($formData, $numericPrefix = '', $argSeparator = '&', $arrName = '')	{		$query = array();		foreach ($formData as $k => $v)		{			if (is_int($k))			{				$k = $numericPrefix . $k;			}			if (is_array($v))			{				$query[] = httpBuildQuery($v, $numericPrefix, $argSeparator, $k);			}			else			{				$query[] = rawurlencode(empty($arrName) ? $k : ($arrName . '[' . $k . ']')) . '=' . rawurlencode($v);			}		}		return implode($argSeparator, $query);	}	private function createRequestUrl($data)	{		$params = $data;		ksort($params);		$url_params = '';		foreach ($params as $key => $value)		{			if ($url_params == '')				$url_params .= $key . '=' . urlencode($value);			else				$url_params .= '&' . $key . '=' . urlencode($value);		}		return "&" . $url_params;	}}