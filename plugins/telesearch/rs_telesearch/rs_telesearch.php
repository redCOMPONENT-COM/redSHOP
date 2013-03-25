<?php
/**
 * @version        $Id: rs_telesearch.php 2011-11-21 19:34:56Z ian $
 * @package        Joomla
 * @copyright      Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 *                 Joomla! is free software. This version may have been modified pursuant
 *                 to the GNU General Public License, and as distributed it includes or
 *                 is derivative of works licensed under the GNU General Public License or
 *                 other free or open source software licenses.
 *                 See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
//JPlugin::loadLanguage( 'plg_telesearch_rs_telesearch');

class plgtelesearchrs_telesearch extends JPlugin
{
	/**
	 * specific redform plugin parameters
	 *
	 * @var JParameter object
	 */
	function plgtelesearchrs_telesearch(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Method to connect with telesearch
	 *
	 * @access public
	 * @return array
	 */
	function findByTelephoneNumber($tele)
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
?>
