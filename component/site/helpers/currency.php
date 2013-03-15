<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.utilities.simplexml');

require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php';

/*
 * price converter
 */
class convertPrice
{
	var $archive = true;
	var $last_updated = '';

	var $document_address = 'http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml';

	var $info_address = 'http://www.ecb.int/stats/eurofxref/';
	var $supplier = 'European Central Bank';

	/**
	 * Initializes the global currency converter array
	 *
	 * @return mixed
	 */
	public function init()
	{
		global $mainframe;

		if (!is_array(@$GLOBALS['converter_array']) && @$GLOBALS['converter_array'] !== -1)
		{
			setlocale(LC_TIME, "en-GB");

			$now = time() + 3600; // Time in ECB (Germany) is GMT + 1 hour (3600 seconds)
			if (date("I"))
			{
				$now += 3600; // Adjust for daylight saving time
			}
			$weekday_now_local = gmdate('w', $now); // week day, important: week starts with sunday (= 0) !!
			$date_now_local    = gmdate('Ymd', $now);
			$time_now_local    = gmdate('Hi', $now);
			$time_ecb_update   = '1415';

			$store_path       = JPATH_SITE . "/components/com_redshop/helpers";
			$archivefile_name = $store_path . '/currency.xml';

			$ecb_filename = $this->document_address;
			$val          = '';

			if (file_exists($archivefile_name) && filesize($archivefile_name) > 0)
			{
				// timestamp for the Filename
				$file_datestamp = date('Ymd', filemtime($archivefile_name));

				// check if today is a weekday - no updates on weekends
				if (date('w') > 0 && date('w') < 6
					// compare filedate and actual date
					&& $file_datestamp != $date_now_local
					// if localtime is greater then ecb-update-time go on to update and write files
					&& $time_now_local > $time_ecb_update
				)
				{
					$curr_filename = $ecb_filename;

				}
				else
				{
					$curr_filename      = $archivefile_name;
					$this->last_updated = $file_datestamp;
					$this->archive      = false;
				}

			}
			else
			{

				$curr_filename = $ecb_filename;
			}

			if (!is_writable($store_path))
			{
				$this->archive = false;
			}

			if ($curr_filename == $ecb_filename)
			{

				// Fetch the file from the internet
				$contents = @file_get_contents($curr_filename);
				if (!$contents)
				{
					$mainframe->enqueuemessage("ERROR_RESOLVING_HOST");
				}
				else
				{
					$this->last_updated = date('Ymd');
				}

			}
			else
			{
				$contents = @file_get_contents($curr_filename);
			}

			if ($contents)
			{

				// if archivefile does not exist
				$contents = str_replace("<Cube currency='USD'", " <Cube currency='EUR' rate='1'/> <Cube currency='USD'", $contents);
				if ($this->archive)
				{
					// now write new file
					file_put_contents($archivefile_name, $contents);
				}

				/* XML Parsing */
				$xml = JFactory::getXMLParser('Simple');
				@$xml->loadFile($archivefile_name);

				// access a given node's CDATA
				$currency_list = $xml->document->Cube[0]->_children;

				// Loop through the Currency List
				for ($i = 0; $i < count($currency_list); $i++)
				{
					$currNode                        = $currency_list[$i]->_attributes;
					$currency[$currNode['currency']] = $currNode['rate'];
					unset($currNode);
				}

				$GLOBALS['converter_array'] = $currency;
			}
			else
			{
				$GLOBALS['converter_array'] = -1;

				return false;
			}
		}

		return true;
	}

	public function convert($amountA, $currA = '', $currB = '')
	{

		$config = new Redconfiguration();

		$session = JFactory::getSession('product_currency');

		// global $vendor_currency is DEFAULT!
		if (!$currA)
		{
			$currA = CURRENCY_CODE;
		}

		if (!$currB)
		{
			$currB = $session->get('product_currency');
		}

		// If both currency codes match, do nothing
		if ($currA == $currB)
		{
			return $amountA;
		}

		if (!$this->init())
		{
			$session->set('product_currency', CURRENCY_CODE);

			return $amountA;
		}

		$valA = isset($GLOBALS['converter_array'][$currA]) ? $GLOBALS['converter_array'][$currA] : 1;
		$valB = isset($GLOBALS['converter_array'][$currB]) ? $GLOBALS['converter_array'][$currB] : 1;

		$val = $amountA * $valB / $valA;

		return $val;
	}

}
