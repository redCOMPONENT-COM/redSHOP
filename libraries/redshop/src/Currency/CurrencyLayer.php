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
 * CurrencyLayer class
 *
 * @since  2.0.6
 */
class CurrencyLayer
{
	/**
	 * @var null
	 */
	protected static $instance = null;
	/**
	 * @var boolean
	 */
	public $archive = true;
	/**
	 * @var string
	 */
	public $lastUpdated = '';
	/**
	 * @var  array
	 */
	protected $convertedCurrencies;

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
		}

		$valueA = isset($convertedCurrencies[$sourceCurrency]) ? $convertedCurrencies[$sourceCurrency] : 1;
		$valueB = isset($convertedCurrencies[$targetCurrency]) ? $convertedCurrencies[$targetCurrency] : 1;

		return $amount * $valueB / $valueA;
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
		if (empty($this->convertedCurrencies))
		{
			$this->init();
		}

		return $this->convertedCurrencies;
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
		$storeFile = $storePath . '/currency.json';

		if (\JFile::exists($storeFile) && filesize($storeFile) > 0)
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
				$currentFile = $this->initializeCurl();
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
			$currentFile = $this->initializeCurl();
		}

		if (!is_writable($storePath))
		{
			$this->archive = false;
		}

		$contents = false;

		if (\JFile::exists($storeFile))
		{
			try
			{
				$contents    = json_decode(file_get_contents($storeFile), true);
				$currentFile = $contents;
			}
			catch (\Exception $e)
			{
			}

			if (empty($contents))
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
				$contents = $currentFile;
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

		if ($this->archive)
		{
			// Now write new file
			file_put_contents($storeFile, json_encode($contents));
		}

		$currencies = $currentFile['quotes'];
		$result     = array();

		foreach ($currencies as $currency => $rate)
		{
			$currency          = substr($currency, 3);
			$result[$currency] = $rate;
		}

		$this->convertedCurrencies = $result;
	}

	/**
	 * initialize CURL
	 *
	 * @return  array  Currencies
	 *
	 * @since   2.0.6
	 */
	public function initializeCurl()
	{
		$accessKey = \Redshop::getConfig()->get('CURRENCY_LAYER_ACCESS_KEY');
		$source    = 'USD';
		$layerApi  = 'http://apilayer.net/api/live?access_key=' . $accessKey . '&source=' . $source . '&format=1';

		$ch = curl_init($layerApi);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($ch);
		curl_close($ch);

		return json_decode($json, true);
	}
}
