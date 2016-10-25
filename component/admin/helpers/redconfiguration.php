<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class Redconfiguration
{
	public $defArray = null;

	public $configPath = null;

	public $configDistPath = null;

	public $configBkpPath = null;

	public $configTmpPath = null;

	public $configDefPath = null;

	public $cfgData = null;

	public $countryList = null;

	protected static $instance = null;

	/**
	 * Returns the RedConfiguration object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  RedConfiguration  The RedConfiguration object
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
	 * define default path
	 *
	 */
	public function __construct()
	{
		$basepath             = JPATH_SITE . '/administrator/components/com_redshop/helpers/';
		$this->configPath     = $basepath . 'redshop.cfg.php';
		$this->configDistPath = $basepath . 'wizard/redshop.cfg.dist.php';
		$this->configBkpPath  = $basepath . 'wizard/redshop.cfg.bkp.php';
		$this->configTmpPath  = $basepath . 'wizard/redshop.cfg.tmp.php';
		$this->configDefPath  = $basepath . 'wizard/redshop.cfg.def.php';

		if (!defined('JSYSTEM_IMAGES_PATH'))
		{
			define('JSYSTEM_IMAGES_PATH', JURI::root() . 'media/system/images/');
		}

		if (!defined('REDSHOP_ADMIN_IMAGES_ABSPATH'))
		{
			define('REDSHOP_ADMIN_IMAGES_ABSPATH', JURI::root() . 'administrator/components/com_redshop/assets/images/');
		}

		if (!defined('REDSHOP_FRONT_IMAGES_ABSPATH'))
		{
			define('REDSHOP_FRONT_IMAGES_ABSPATH', JURI::root() . 'components/com_redshop/assets/images/');
		}

		if (!defined('REDSHOP_FRONT_IMAGES_RELPATH'))
		{
			define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');
		}

		if (!defined('REDSHOP_FRONT_DOCUMENT_ABSPATH'))
		{
			define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JURI::root() . 'components/com_redshop/assets/document/');
		}

		if (!defined('REDSHOP_FRONT_DOCUMENT_RELPATH'))
		{
			define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');
		}
	}

	/**
	 * check configuration file exist or not
	 *
	 * @return boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::loadConfigFile() instead
	 */
	public function isCFGFile()
	{
		return RedshopAppConfiguration::loadConfigFile();
	}

	/**
	 * check table exist
	 *
	 * @return boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::checkConfigTableExist() instead
	 */
	public function isCFGTable()
	{
		return RedshopAppConfiguration::checkConfigTableExist();
	}

	/**
	 * write configuration table data to file
	 *
	 * @param   array  $org  Config additional variables to merge
	 *
	 * @return void
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::fetchConfigWriteToFile() instead
	 */
	public function setCFGTableData($org = array())
	{
		return RedshopAppConfiguration::fetchConfigWriteToFile($org);
	}

	/**
	 * load Default configuration file
	 *
	 * @return boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::loadDefaultConfigFile() instead
	 */
	public function loadDefaultCFGFile()
	{
		return RedshopAppConfiguration::loadDefaultConfigFile();
	}

	/**
	 * manage configuration file during installation
	 *
	 * @param   array  $org  Config additional variables to merge
	 *
	 * @return boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::loadOrUpdateConfigFile() instead
	 */
	public function manageCFGFile($org = array())
	{
		return RedshopAppConfiguration::loadOrUpdateConfigFile($org);
	}

	/**
	 * Define Configuration file. We are preparing define text on this function.
	 *
	 * @param   array    $data    Configuration Data associative array
	 * @param   boolean  $bypass  Don't write anything and simply bypass if it is set to true.
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::defineConfigFromData() instead
	 */
	public function defineCFGVars($data, $bypass = false)
	{
		$this->cfgData = RedshopAppConfiguration::defineConfigFromData($data, $bypass);
	}

	/**
	 * Write prepared data into a file.
	 *
	 * @return  boolean  True when file successfully saved.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::writeConfigToFile() instead
	 */
	public function writeCFGFile()
	{
		return RedshopAppConfiguration::writeConfigToFile($this->cfgData);
	}

	/**
	 * Update Configuration file with new parameter.
	 * This function is specially use during upgrading redSHOP and need to put new configuration params.
	 *
	 * @return  boolean  True when file successfully updated.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::updateConfigFile() instead
	 */
	public function updateCFGFile()
	{
		return RedshopAppConfiguration::updateConfigFile($this->cfgData);
	}

	/**
	 * Backup Configuration file before running wizard.
	 *
	 * @return  boolean  True on successfully backed up.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::backupConfigFile() instead
	 */
	public function backupCFGFile()
	{
		return RedshopAppConfiguration::backupConfigFile();
	}

	/**
	 * Try to find if temp configuration file is available. This function is for wizard.
	 *
	 * @return  boolean  True when file is exist.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::loadTempConfigFile() instead
	 */
	public function isTmpFile()
	{
		return RedshopAppConfiguration::loadTempConfigFile();
	}

	/**
	 * Check if temp file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::checkTemConfigFileIsWritable() instead
	 */
	public function isTMPFileWritable()
	{
		return RedshopAppConfiguration::checkTemConfigFileIsWritable();
	}

	/**
	 * Check if definition file is available or not.
	 *
	 * @return  boolean  True if file is exist.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::loadDefConfigFile() instead
	 */
	public function isDEFFile()
	{
		return RedshopAppConfiguration::loadDefConfigFile();
	}

	/**
	 * Check for def file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::checkDefConfigFileIsWritable() instead
	 */
	public function isDEFFileWritable()
	{
		return RedshopAppConfiguration::checkDefConfigFileIsWritable();
	}

	/**
	 * Restore configuration file from temp file.
	 *
	 * @return  boolean  True if file is restored.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::restoreFromTempConfigFile() instead
	 */
	public function storeFromTMPFile()
	{
		return RedshopAppConfiguration::restoreFromTempConfigFile();
	}

	/**
	 * This function will define relation between keys and define variables.
	 * This needs to be updated when you want new variable in configuration.
	 *
	 * @param   array  $d  Associative array of values. Typically a from $_POST.
	 *
	 * @return  array      Associative array of configuration variables which are ready to write.
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::prepareData() instead
	 */
	public function redshopCFGData($d)
	{
		return RedshopAppConfiguration::prepareData($d);
	}

	/**
	 * We are using file for saving configuration variables
	 * We need some variables that can be uses as dynamically
	 * Here is the logic to define that variables
	 *
	 * IMPORTANT: we need to call this function in plugin or module manually to see the effect of this variables
	 *
	 * @return void
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::defineDynamicVars() instead
	 */
	public function defineDynamicVars()
	{
		return RedshopAppConfiguration::defineDynamicVars();
	}

	/**
	 * Get config status for SHOW_PRICE_PRE
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::cfgShowPrice() instead
	 */
	public function showPrice()
	{
		return RedshopAppConfiguration::cfgShowPrice();
	}

	/**
	 * Get config status for PRE_USE_AS_CATALOG
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::cfgUseAsCatalog() instead
	 */
	public function getCatalog()
	{
		return RedshopAppConfiguration::cfgUseAsCatalog();
	}

	/**
	 * Get config status for DEFAULT_QUOTATION_MODE_PRE
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::cfgQuotationMode() instead
	 */
	public function setQuotationMode()
	{
		return RedshopAppConfiguration::cfgQuotationMode();
	}

	/**
	 * Truncate string or HTML and return a preview text with '...' by max length
	 *
	 * @param   string   $desc      Text or HTML input
	 * @param   integer  $maxchars  Length of character to truncate
	 * @param   string   $suffix    Ending '...'
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::truncateWithMaxLength() instead
	 */
	public function maxchar($desc = '', $maxchars = 0, $suffix = '')
	{
		return RedshopAppConfiguration::truncateWithMaxLength($desc, $maxchars, $suffix);
	}

	/**
	 * Truncate string or HTML and return a preview text with '...'
	 *
	 * @param   string   $text          Text or HTML input
	 * @param   integer  $length        Length of character to truncate
	 * @param   string   $ending        Ending '...'
	 * @param   boolean  $exact         If exact, string will be truncated exactly by length
	 * @param   boolean  $considerHtml  Pre-define $text is HTML or not, if not, tag <> will also be truncated
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::truncate() instead
	 */
	public function substrws($text, $length = 50, $ending = '...', $exact = false, $considerHtml = true)
	{
		return RedshopAppConfiguration::truncate($text, $length, $ending, $exact, $considerHtml);
	}

	/**
	 * Method to get date format
	 *
	 * @access    public
	 *
	 * @return    array
	 *
	 * @since    1.5
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::dropdownDateFormat() instead
	 */
	public function getDateFormat()
	{
		RedshopAppConfiguration::dropdownDateFormat();
	}

	/**
	 * Method to convert date according to format
	 *
	 * @param   string  $date  Date input in string format
	 *
	 * @return    array
	 *
	 * @since    1.5
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::stringToDate() instead
	 */
	public function convertDateFormat($date)
	{
		return RedshopAppConfiguration::stringToDate($date);
	}

	/**
	 * Method to get Country by ID
	 *
	 * @param   int  $conid  country id
	 *
	 * @return  country
	 *
	 * @deprecated  2.0.0.3  Use RedshopAppConfiguration::getCountryId() instead
	 */
	public function getCountryId($conid)
	{
		return RedshopAppConfiguration::getCountryId($conid);
	}

	/**
	 * Method to get Country by ID
	 *
	 * @param   int  $conid  country id
	 *
	 * @return  country
	 */
	public function getCountryCode2($conid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('country_2_code'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->q($conid));
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function getStateCode2($conid)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT state_2_code FROM #__redshop_state '
			. 'WHERE state_3_code LIKE ' . $db->quote($conid);
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function getStateCode($conid, $tax_code)
	{
		if (empty($tax_code))
		{
			return null;
		}

		$db = JFactory::getDbo();
		$query = 'SELECT  state_3_code , show_state FROM #__redshop_state '
		. 'WHERE state_2_code LIKE ' . $db->quote($tax_code)
		. ' AND id = ' . (int) $conid;
		$db->setQuery($query);
		$rslt_data = $db->loadObjectList();

		if ($rslt_data[0]->show_state == 3)
		{
			$state_code = $rslt_data[0]->state_3_code;

			return $state_code;
		}

		$state_code = $tax_code;

		return $state_code;
	}
}
