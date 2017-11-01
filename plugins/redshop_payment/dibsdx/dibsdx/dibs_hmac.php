<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * DIBSDX Hmac helper
 *
 * @since  1.5
 */
class Dibs_Hmac
{
	/**
	 * This function converts an array holding the form key values to a string.
	 * The generated string represents the message to be signed by the MAC.
	 *
	 * @param   array  $formKeyValues  Form key values.
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	public function createMessage($formKeyValues)
	{
		if (!is_array($formKeyValues))
		{
			return '';
		}

		$string = '';

		// Sort the posted values by alphanumeric
		ksort($formKeyValues);

		foreach ($formKeyValues as $key => $value)
		{
			if (in_array($key, array('MAC', 'view', 'task')))
			{
				continue;
			}

			// Don't include the MAC in the calculation of the MAC.
			if (strlen($string) > 0)
			{
				$string .= '&';
			}

			// Create string representation
			$string .= $key . '=' . $value;
		}

		return $string;
	}

	/**
	 * This function converts from a hexadecimal representation to a string representation.
	 *
	 * @param   string  $hex  Hexadecimal string.
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	public function hexToStr($hex)
	{
		$string = "";
		$hex    = explode("\n", trim(chunk_split($hex, 2)));

		foreach ($hex as $h)
		{
			$string .= chr(hexdec($h));
		}

		return $string;
	}

	/**
	 * This function calculates the MAC for an array holding the form key values. The $logfile is optional.
	 *
	 * @param   array   $formKeyValues  Form key values.
	 * @param   string  $hmacKey        Form key values.
	 * @param   string  $logfile        File path for store log.
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	public function calculateMac($formKeyValues, $hmacKey, $logfile = null)
	{
		if (!is_array($formKeyValues))
		{
			return '';
		}

		// Create the message to be signed.
		$messageToBeSigned = $this->createMessage($formKeyValues);

		// Calculate the MAC.
		$mac = hash_hmac("sha256", $messageToBeSigned, $this->hexToStr($hmacKey));

		if (empty($logfile))
		{
			return $mac;
		}

		// Following is only relevant if you wan't to log the calculated MAC to a log file.
		$fp = fopen($logfile, 'a') or exit("Can't open $logfile!");

		fwrite(
			$fp,
			"messageToBeSigned: " . $messageToBeSigned . PHP_EOL . " HmacKey: " . $hmacKey . PHP_EOL . " generated MAC: " . $mac . PHP_EOL
		);

		if (isset($formKeyValues["MAC"]) && $formKeyValues["MAC"] != "")
		{
			fwrite($fp, " posted MAC:    " . $formKeyValues["MAC"] . PHP_EOL);
		}

		return $mac;
	}
}
