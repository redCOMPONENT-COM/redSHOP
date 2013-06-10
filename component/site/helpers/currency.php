<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.utilities.simplexml');

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/configuration.php';

/**
 * price converter
 *
 * @since  2.5
 */
class CurrencyHelper
{
	public $archive = true;

	public $last_updated = '';

	public $document_address = 'http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml';

	public $info_address = 'http://www.ecb.int/stats/eurofxref/';

	public $supplier = 'European Central Bank';

	/**
	 * Initializes the global currency converter array
	 *
	 * @return mixed
	 */
	public function init()
	{
		$app = JFactory::getApplication();

		if (!is_array(@$GLOBALS['converter_array']) && @$GLOBALS['converter_array'] !== -1)
		{
			setlocale(LC_TIME, "en-GB");

			// Time in ECB (Germany) is GMT + 1 hour (3600 seconds)
			$now = time() + 3600;

			if (date("I"))
			{
				// Adjust for daylight saving time
				$now += 3600;
			}

			// Week day, important: week starts with sunday (= 0) !!
			$weekday_now_local = gmdate('w', $now);
			$date_now_local    = gmdate('Ymd', $now);
			$time_now_local    = gmdate('Hi', $now);
			$time_ecb_update   = '1415';

			$store_path       = JPATH_SITE . "/components/com_redshop/helpers";
			$archivefile_name = $store_path . '/currency.xml';

			$ecb_filename = $this->document_address;
			$val          = '';

			if (file_exists($archivefile_name) && filesize($archivefile_name) > 0)
			{
				// Timestamp for the Filename
				$file_datestamp = date('Ymd', filemtime($archivefile_name));

				/*
				 * Check if today is a weekday - no updates on weekends
				 * Compare filedate and actual date
				 * If localtime is greater then ecb-update-time go on to update and write files
				 */
				if (date('w') > 0 && date('w') < 6
					&& $file_datestamp != $date_now_local
					&& $time_now_local > $time_ecb_update)
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
					$app->enqueuemessage("ERROR_RESOLVING_HOST");
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
				// If archivefile does not exist
				$contents = str_replace("<Cube currency='USD'", " <Cube currency='EUR' rate='1'/> <Cube currency='USD'", $contents);

				if ($this->archive)
				{
					// Now write new file
					file_put_contents($archivefile_name, $contents);
				}

				/* XML Parsing */
				$xml = JFactory::getXMLParser('Simple');
				@$xml->loadFile($archivefile_name);

				// Access a given node's CDATA
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

	/**
	 * Convert currency
	 *
	 * @param   float   $amountA  Amount to convert
	 * @param   string  $currA    Base Currency code
	 * @param   string  $currB    Currency code in which need amount to be converted
	 *
	 * @return  float             Converted amount
	 */
	public function convert($amountA, $currA = '', $currB = '')
	{
		$config = new Redconfiguration;

		$session = JFactory::getSession('product_currency');

		// Global $vendor_currency is DEFAULT!
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

	/**
	 * Method to get Currency Numeric code / ISO code
	 *
	 * @param   string  $code  Currency Code
	 *
	 * @todo    Add numeric code into table #_redshop_currency "redSHOP Currency Detail"
	 *
	 * @return  int     Currency Numeric Code
	 */
	function get_iso_code($code)
	{
		switch ($code)
		{
			case "ADP":
				return "020";
				break;
			case "AED":
				return "784";
				break;
			case "AFA":
				return "004";
				break;
			case "ALL":
				return "008";
				break;
			case "AMD":
				return "051";
				break;
			case "ANG":
				return "532";
				break;
			case "AOA":
				return "973";
				break;
			case "ARS":
				return "032";
				break;
			case "AUD":
				return "036";
				break;
			case "AWG":
				return "533";
				break;
			case "AZM":
				return "031";
				break;
			case "BAM":
				return "977";
				break;
			case "BBD":
				return "052";
				break;
			case "BDT":
				return "050";
				break;
			case "BGL":
				return "100";
				break;
			case "BGN":
				return "975";
				break;
			case "BHD":
				return "048";
				break;
			case "BIF":
				return "108";
				break;
			case "BMD":
				return "060";
				break;
			case "BND":
				return "096";
				break;
			case "BOB":
				return "068";
				break;
			case "BOV":
				return "984";
				break;
			case "BRL":
				return "986";
				break;
			case "BSD":
				return "044";
				break;
			case "BTN":
				return "064";
				break;
			case "BWP":
				return "072";
				break;
			case "BYR":
				return "974";
				break;
			case "BZD":
				return "084";
				break;
			case "CAD":
				return "124";
				break;
			case "CDF":
				return "976";
				break;
			case "CHF":
				return "756";
				break;
			case "CLF":
				return "990";
				break;
			case "CLP":
				return "152";
				break;
			case "CNY":
				return "156";
				break;
			case "COP":
				return "170";
				break;
			case "CRC":
				return "188";
				break;
			case "CUP":
				return "192";
				break;
			case "CVE":
				return "132";
				break;
			case "CYP":
				return "196";
				break;
			case "CZK":
				return "203";
				break;
			case "DJF":
				return "262";
				break;
			case "DKK":
				return "208";
				break;
			case "DOP":
				return "214";
				break;
			case "DZD":
				return "012";
				break;
			case "ECS":
				return "218";
				break;
			case "ECV":
				return "983";
				break;
			case "EEK":
				return "233";
				break;
			case "EGP":
				return "818";
				break;
			case "ERN":
				return "232";
				break;
			case "ETB":
				return "230";
				break;
			case "EUR":
				return "978";
				break;
			case "FJD":
				return "242";
				break;
			case "FKP":
				return "238";
				break;
			case "GBP":
				return "826";
				break;
			case "GEL":
				return "981";
				break;
			case "GHC":
				return "288";
				break;
			case "GIP":
				return "292";
				break;
			case "GMD":
				return "270";
				break;
			case "GNF":
				return "324";
				break;
			case "GTQ":
				return "320";
				break;
			case "GWP":
				return "624";
				break;
			case "GYD":
				return "328";
				break;
			case "HKD":
				return "344";
				break;
			case "HNL":
				return "340";
				break;
			case "HRK":
				return "191";
				break;
			case "HTG":
				return "332";
				break;
			case "HUF":
				return "348";
				break;
			case "IDR":
				return "360";
				break;
			case "ILS":
				return "376";
				break;
			case "INR":
				return "356";
				break;
			case "IQD":
				return "368";
				break;
			case "IRR":
				return "364";
				break;
			case "ISK":
				return "352";
				break;
			case "JMD":
				return "388";
				break;
			case "JOD":
				return "400";
				break;
			case "JPY":
				return "392";
				break;
			case "KES":
				return "404";
				break;
			case "KGS":
				return "417";
				break;
			case "KHR":
				return "116";
				break;
			case "KMF":
				return "174";
				break;
			case "KPW":
				return "408";
				break;
			case "KRW":
				return "410";
				break;
			case "KWD":
				return "414";
				break;
			case "KYD":
				return "136";
				break;
			case "KZT":
				return "398";
				break;
			case "LAK":
				return "418";
				break;
			case "LBP":
				return "422";
				break;
			case "LKR":
				return "144";
				break;
			case "LRD":
				return "430";
				break;
			case "LSL":
				return "426";
				break;
			case "LTL":
				return "440";
				break;
			case "LVL":
				return "428";
				break;
			case "LYD":
				return "434";
				break;
			case "MAD":
				return "504";
				break;
			case "MDL":
				return "498";
				break;
			case "MGF":
				return "450";
				break;
			case "MKD":
				return "807";
				break;
			case "MMK":
				return "104";
				break;
			case "MNT":
				return "496";
				break;
			case "MOP":
				return "446";
				break;
			case "MRO":
				return "478";
				break;
			case "MTL":
				return "470";
				break;
			case "MUR":
				return "480";
				break;
			case "MVR":
				return "462";
				break;
			case "MWK":
				return "454";
				break;
			case "MXN":
				return "484";
				break;
			case "MXV":
				return "979";
				break;
			case "MYR":
				return "458";
				break;
			case "MZM":
				return "508";
				break;
			case "NAD":
				return "516";
				break;
			case "NGN":
				return "566";
				break;
			case "NIO":
				return "558";
				break;
			case "NOK":
				return "578";
				break;
			case "NPR":
				return "524";
				break;
			case "NZD":
				return "554";
				break;
			case "OMR":
				return "512";
				break;
			case "PAB":
				return "590";
				break;
			case "PEN":
				return "604";
				break;
			case "PGK":
				return "598";
				break;
			case "PHP":
				return "608";
				break;
			case "PKR":
				return "586";
				break;
			case "PLN":
				return "985";
				break;
			case "PYG":
				return "600";
				break;
			case "QAR":
				return "634";
				break;
			case "ROL":
				return "642";
				break;
			case "RUB":
				return "643";
				break;
			case "RUR":
				return "810";
				break;
			case "RWF":
				return "646";
				break;
			case "SAR":
				return "682";
				break;
			case "SBD":
				return "090";
				break;
			case "SCR":
				return "690";
				break;
			case "SDD":
				return "736";
				break;
			case "SEK":
				return "752";
				break;
			case "SGD":
				return "702";
				break;
			case "SHP":
				return "654";
				break;
			case "SIT":
				return "705";
				break;
			case "SKK":
				return "703";
				break;
			case "SLL":
				return "694";
				break;
			case "SOS":
				return "706";
				break;
			case "SRG":
				return "740";
				break;
			case "STD":
				return "678";
				break;
			case "SVC":
				return "222";
				break;
			case "SYP":
				return "760";
				break;
			case "SZL":
				return "748";
				break;
			case "THB":
				return "764";
				break;
			case "TJS":
				return "972";
				break;
			case "TMM":
				return "795";
				break;
			case "TND":
				return "788";
				break;
			case "TOP":
				return "776";
				break;
			case "TPE":
				return "626";
				break;
			case "TRL":
				return "792";
				break;
			case "TRY":
				return "949";
				break;
			case "TTD":
				return "780";
				break;
			case "TWD":
				return "901";
				break;
			case "TZS":
				return "834";
				break;
			case "UAH":
				return "980";
				break;
			case "UGX":
				return "800";
				break;
			case "USD":
				return "840";
				break;
			case "UYU":
				return "858";
				break;
			case "UZS":
				return "860";
				break;
			case "VEB":
				return "862";
				break;
			case "VND":
				return "704";
				break;
			case "VUV":
				return "548";
				break;
			case "XAF":
				return "950";
				break;
			case "XCD":
				return "951";
				break;
			case "XOF":
				return "952";
				break;
			case "XPF":
				return "953";
				break;
			case "YER":
				return "886";
				break;
			case "YUM":
				return "891";
				break;
			case "ZAR":
				return "710";
				break;
			case "ZMK":
				return "894";
				break;
			case "ZWD":
				return "716";
				break;
		}

		return "000";
	}
}
