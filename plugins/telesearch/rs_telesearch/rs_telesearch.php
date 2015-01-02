<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgtelesearchrs_telesearch extends JPlugin
{
	/**
	 * specific redform plugin parameters
	 *
	 * @var JRegistry object
	 */
	public function plgtelesearchrs_telesearch(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Method to connect with telesearch
	 *
	 * @access public
	 * @return array
	 */
	public function findByTelephoneNumber($tele)
	{
		$returnarr = array();

		$query = "";
		$query .= '&phone=' . $tele['phone'];
		$query .= '&remoteadd=' . $_SERVER['SERVER_ADDR'];

		$plugin = JPluginHelper::getPlugin('telesearch', 'rs_telesearch');
		$pluginParams = new JRegistry($plugin->params);
		$serverurl = $pluginParams->get('telesearch_serverurl', 'http://opslag.redhost.dk/');

		$myfile = $serverurl . '/lookup.php?' . $query;

		if (function_exists("curl_init"))
		{
			$CR = curl_init();
			curl_setopt($CR, CURLOPT_URL, $myfile);
			curl_setopt($CR, CURLOPT_TIMEOUT, 30);
			curl_setopt($CR, CURLOPT_FAILONERROR, true);

			if ($query)
			{
				curl_setopt($CR, CURLOPT_POSTFIELDS, $query);
				curl_setopt($CR, CURLOPT_POST, 1);
			}

			curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
			$return = curl_exec($CR);
			$error = curl_error($CR);
			curl_close($CR);

			if ($return)
			{
				$returnarr = explode("`_`", $return);
				$result = array();

				if (isset($returnarr[0]))
				{
					$result['company_name'] = $returnarr[0];
				}

				if (isset($returnarr[1]))
				{
					$result['address'] = $returnarr[1];
				}

				if (isset($returnarr[2]))
				{
					$result['zipcode'] = $returnarr[2];
				}

				if (isset($returnarr[3]))
				{
					$result['city'] = $returnarr[3];
				}

				if (isset($returnarr[4]))
				{
					$result['url'] = $returnarr[4];
				}

				if (isset($returnarr[5]))
				{
					$result['phone'] = $returnarr[5];
				}

				if (isset($returnarr[6]))
				{
					$result['firstname'] = $returnarr[6];
				}

				if (isset($returnarr[7]))
				{
					$result['lastname'] = $returnarr[7];
				}

				return $result;
			}
		}

		return $returnarr;
	}
}

