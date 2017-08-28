<?php
/**
 * @package     RedShop
 * @subpackage  Currency
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Currency;

use RedshopEntityCurrency;

defined('_JEXEC') or die;

/**
 * Currency class
 *
 * @since  2.0.6
 */
class Currency
{
	/**
	 * @var boolean
	 */
	public $archive = true;

	/**
	 * @var string
	 */
	public $lastUpdated = '';

	/**
	 * @var string
	 */
	public $documentAddress = 'http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml';

	/**
	 * @var string
	 */
	public $inforAddress = 'http://www.ecb.int/stats/eurofxref/';

	/**
	 * @var string
	 */
	public $supplier = 'European Central Bank';

	/**
	 * @var  array
	 */
	protected $convertedCurrencies;

	/**
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * Returns the CurrencyHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  self  This class instance
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Initializes the global currency converter array
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function init()
	{
		$app = \JFactory::getApplication();

		setlocale(LC_TIME, "en-GB");

		// Time in ECB (Germany) is GMT + 1 hour (3600 seconds)
		$now = time() + 3600;

		if (date("I"))
		{
			// Adjust for daylight saving time
			$now += 3600;
		}

		// Week day, important: week starts with sunday (= 0) !!
		$dateNowLocal  = gmdate('Ymd', $now);
		$timeNowLocal  = gmdate('Hi', $now);
		$timeECBUpdate = '1415';

		$storePath = JPATH_SITE . '/components/com_redshop/helpers';
		$storeFile = $storePath . '/currency.xml';

		$ecbFile = $this->documentAddress;

		if (file_exists($storeFile) && filesize($storeFile) > 0)
		{
			// Timestamp for the Filename
			$storeFileDate = date('Ymd', filemtime($storeFile));

			/*
			 * Check if today is a weekday - no updates on weekends
			 * Compare file date and actual date
			 * If localtime is greater then ecb-update-time go on to update and write files
			 */
			if (date('w') > 0 && date('w') < 6 && $storeFileDate != $dateNowLocal && $timeNowLocal > $timeECBUpdate)
			{
				$currentFile = $ecbFile;
			}
			else
			{
				$currentFile = $storeFile;

				$this->lastUpdated = $storeFileDate;
				$this->archive     = false;
			}
		}
		else
		{
			$currentFile = $ecbFile;
		}

		if (!is_writable($storePath))
		{
			$this->archive = false;
		}

		$contents = false;

		if ($currentFile == $ecbFile)
		{
			try
			{
				// Fetch the file from the internet
				$contents = file_get_contents($currentFile);
			}
			catch (\Exception $e)
			{
			}

			if (!$contents)
			{
				$app->enqueueMessage("ERROR_RESOLVING_HOST");
			}
			else
			{
				$this->lastUpdated = date('Ymd');
			}
		}
		else
		{
			try
			{
				// Fetch the file from the internet
				$contents = file_get_contents($currentFile);
			}
			catch (\Exception $e)
			{
			}
		}

		if (!$contents)
		{
			$this->convertedCurrencies = array();

			return;
		}

		// If archive file does not exist
		$contents = str_replace("<Cube currency='USD'", " <Cube currency='EUR' rate='1'/> <Cube currency='USD'", $contents);

		if ($this->archive)
		{
			// Now write new file
			file_put_contents($storeFile, $contents);
		}

		// XML Parse
		$xml = simplexml_load_file($storeFile);

		// Access a given node's CDATA
		$currencies = $xml->Cube->Cube->Cube;

		$results = array();

		// Loop through the Currency List
		for ($i = 0, $in = count($currencies); $i < $in; $i++)
		{
			$currNode                              = $currencies[$i]->attributes();
			$results[(string) $currNode->currency] = (string) $currNode->rate;
			unset($currNode);
		}

		$this->convertedCurrencies = $results;
	}

	/**
	 * Method for get converted currencies list
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public function getConvertedCurrencies()
	{
		if (is_null($this->convertedCurrencies))
		{
			$this->init();
		}

		return $this->convertedCurrencies;
	}

	/**
	 * Convert currency
	 *
	 * @param   float  $amount         Amount to convert
	 * @param   string $sourceCurrency Base Currency code
	 * @param   string $targetCurrency Currency code in which need amount to be converted
	 *
	 * @return  float             Converted amount
	 *
	 * @since   2.0.6
	 */
	public function convert($amount, $sourceCurrency = '', $targetCurrency = '')
	{
		$session = \JFactory::getSession();

		if (!$sourceCurrency)
		{
			$sourceCurrency = \Redshop::getConfig()->get('CURRENCY_CODE');
		}

		if (!$targetCurrency)
		{
			$targetCurrency = RedshopEntityCurrency::getInstance($session->get('product_currency'))->get('currency_code');
		}

		// Make sure data is correct format.
		$sourceCurrency = trim($sourceCurrency);
		$targetCurrency = trim($targetCurrency);
		$amount = (float) $amount;

		// If both currency codes match, do nothing
		if ($sourceCurrency == $targetCurrency)
		{
			return $amount;
		}

		$convertedCurrencies = $this->getConvertedCurrencies();

		if (empty($convertedCurrencies))
		{
			$session->set('product_currency', \Redshop::getConfig()->get('CURRENCY_CODE'));

			return $amount;
		}

		$valueA = isset($convertedCurrencies[$sourceCurrency]) ? $convertedCurrencies[$sourceCurrency] : 1;
		$valueB = isset($convertedCurrencies[$targetCurrency]) ? $convertedCurrencies[$targetCurrency] : 1;

		return $amount * $valueB / $valueA;
	}

	/**
	 * Method to get Currency Numeric code / ISO code
	 *
	 * @param   string  $code  Currency Code
	 *
	 * @TODO    Add numeric code into table #_redshop_currency "redSHOP Currency Detail"
	 *
	 * @return  int     Currency Numeric Code
	 *
	 * @since   2.0.6
	 */
	public function getISOCode($code)
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
			default:
				return '000';
				break;
		}
	}
}
