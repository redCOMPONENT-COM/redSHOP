<?php
/**
 * @package     Redshop\Environment\Remote
 * @subpackage  Curl
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Environment\Remote;

/**
 * @package     Redshop\Environment\Remote
 *
 * @since       2.1.0
 */
class Curl
{
	/**
	 * @param   string $source      Source
	 * @param   string $destination Source
	 *
	 * @return  boolean
	 * @since   2.1.0
	 */
	public static function downloadFile($source, $destination)
	{
		chmod($destination, 0777);
		$curl   = curl_init($source);
		$handle = fopen($destination, "w");

		if (!is_resource($curl) || !is_resource($handle))
		{
			return false;
		}

		curl_setopt($curl, CURLOPT_FILE, $handle);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
		curl_exec($curl);
		curl_close($curl);

		return fclose($handle);
	}
}
