<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Config\App;

/**
 * redSHOP configuration class
 *
 * @since       1.6
 *
 * @deprecated  2.0.6  Use Redshop\App instead
 */
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

	/**
	 * @var   App
	 */
	protected static $instance = null;

	/**
	 * Returns the RedConfiguration object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  RedConfiguration  The RedConfiguration object
	 *
	 * @since   1.6
	 *
	 * @deprecated  2.0.6  Use Redshop\App class instead
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
	 * Check configuration file exist or not
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.6
	 */
	public function isCFGFile()
	{
		return App::getInstance()->isConfigurationFile();
	}

	/**
	 * check table exist
	 *
	 * @return   boolean
	 *
	 * @deprecated  2.0.6
	 */
	public function isCFGTable()
	{
		return App::getInstance()->isConfigurationTable();
	}

	/**
	 * write configuration table data to file
	 *
	 * @param   array  $org  Config additional variables to merge
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.6
	 */
	public function setCFGTableData($org = array())
	{
		App::getInstance()->storeConfigurationTable($org);
	}

	/**
	 * load Default configuration file
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.6
	 */
	public function loadDefaultCFGFile()
	{
		return App::getInstance()->loadDefaultConfigurationFile();
	}

	/**
	 * manage configuration file during installation
	 *
	 * @param   array  $org  Config additional variables to merge
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.6
	 */
	public function manageCFGFile($org = array())
	{
		return App::getInstance()->manageConfigurationFile($org);
	}

	/**
	 * Define Configuration file. We are preparing define text on this function.
	 *
	 * @param   array    $data    Configuration Data associative array
	 * @param   boolean  $bypass  Don't write anything and simply bypass if it is set to true.
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.6
	 */
	public function defineCFGVars($data, $bypass = false)
	{
		App::getInstance()->defineConfigurationVariables($data, $bypass);
	}

	/**
	 * Write prepared data into a file.
	 *
	 * @return  boolean  True when file successfully saved.
	 *
	 * @deprecated  2.0.6
	 */
	public function writeCFGFile()
	{
		return App::getInstance()->writeConfigurationFile();
	}

	/**
	 * Update Configuration file with new parameter.
	 * This function is specially use during upgrading redSHOP and need to put new configuration params.
	 *
	 * @return  boolean  True when file successfully updated.
	 *
	 * @deprecated  2.0.6
	 */
	public function updateCFGFile()
	{
		return App::getInstance()->updateConfigurationFile();
	}

	/**
	 * Backup Configuration file before running wizard.
	 *
	 * @return  boolean  True on successfully backed up.
	 *
	 * @deprecated  2.0.6
	 */
	public function backupCFGFile()
	{
		return App::getInstance()->backupConfigurationFile();
	}

	/**
	 * Try to find if temp configuration file is available. This function is for wizard.
	 *
	 * @return  boolean  True when file is exist.
	 *
	 * @deprecated  2.0.6
	 */
	public function isTmpFile()
	{
		return App::getInstance()->checkTemporaryConfigFile();
	}

	/**
	 * Check if temp file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 */
	public function isTMPFileWritable()
	{
		return App::getInstance()->isTemporaryConfigFileCanWrite();
	}

	/**
	 * Check if definition file is available or not.
	 *
	 * @return  boolean  True if file is exist.
	 */
	public function isDEFFile()
	{
		if (file_exists($this->configDefPath))
		{
			if ($this->isDEFFileWritable())
			{
				require_once $this->configDefPath;

				return true;
			}
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_DEF_FILE_NOT_FOUND'), 'error');
		}

		return false;
	}

	/**
	 * Check for def file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 */
	public function isDEFFileWritable()
	{
		if (!is_writable($this->configDefPath))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_DEF_FILE_NOT_WRITABLE'), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Restore configuration file from temp file.
	 *
	 * @return  boolean  True if file is restored.
	 *
	 * @deprecated  2.0.6
	 */
	public function storeFromTMPFile()
	{
		global $temparray;
		global $defaultarray;

		if ($this->isTmpFile() && $this->isDEFFile())
		{
			$ncfgdata     = array_merge($defaultarray, $temparray);
			$config_array = $this->redshopCFGData($ncfgdata);
			$this->defineCFGVars($config_array, true);
			$this->backupCFGFile();

			if (!$this->writeCFGFile())
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * This function will define relation between keys and define variables.
	 * This needs to be updated when you want new variable in configuration.
	 *
	 * @param   array  $data  Associative array of values. Typically a from $_POST.
	 *
	 * @return  array         Associative array of configuration variables which are ready to write.
	 *
	 * @deprecated  2.0.6
	 */
	public function redshopCFGData($data)
	{
		return App::getInstance()->prepareConfigData($data);
	}

	/**
	 * We are using file for saving configuration variables
	 * We need some variables that can be uses as dynamically
	 * Here is the logic to define that variables
	 *
	 * IMPORTANT: we need to call this function in plugin or module manually to see the effect of this variables
	 *
	 * @return void
	 */
	public function defineDynamicVars()
	{
		RedshopHelperUtility::defineDynamicVariables();
	}

	/**
	 * Method for limit chars
	 *
	 * @param   string  $desc      Description
	 * @param   int     $maxChars  Maximum chars
	 * @param   string  $suffix    Suffix
	 *
	 * @return string
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::maxChars() instead
	 */
	public function maxchar($desc = '', $maxChars = 0, $suffix = '')
	{
		return RedshopHelperUtility::maxChars($desc, $maxChars, $suffix);
	}

	/**
	 * Method for sub-string with length.
	 *
	 * @param   string   $text          Text for sub-string
	 * @param   int      $length        Maximum chars
	 * @param   string   $ending        Ending text
	 * @param   boolean  $exact         Exact
	 * @param   boolean  $considerHtml  Consider HTML
	 *
	 * @return string
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::limitText() instead
	 */
	public function substrws($text, $length = 50, $ending = '...', $exact = false, $considerHtml = true)
	{
		return RedshopHelperUtility::limitText($text, $length, $ending, $exact, $considerHtml);
	}

	/**
	 * Method to get date format
	 *
	 * @access    public
	 *
	 * @return    array
	 *
	 * @since     1.5
	 *
	 * @deprecated  2.0.6  Use RedshopHelperDatetime::getDateFormat() instead.
	 */
	public function getDateFormat()
	{
		return RedshopHelperDatetime::getDateFormat();
	}

	/**
	 * Method to convert date according to format
	 *
	 * @param   int  $date  Date time (Unix format).
	 *
	 * @return  string
	 *
	 * @since   1.5
	 *
	 * @deprecated  2.0.6  Use RedshopHelperDatetime::convertDateFormat() instead,
	 */
	public function convertDateFormat($date)
	{
		return RedshopHelperDatetime::convertDateFormat($date);
	}

	/**
	 * Method to get Country by country 3 code.
	 *
	 * @param   int  $conId  Country 3 code
	 *
	 * @return  int
	 *
	 * @deprecated  2.0.6  Use RedshopHelperWorld::getCountryId() instead.
	 */
	public function getCountryId($conId)
	{
		return RedshopHelperWorld::getCountryId($conId);
	}

	/**
	 * Method to get Country 2 code by Country 3 code.
	 *
	 * @param   int  $country3code  Country 3 code
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.6  Use RedshopHelperWorld::getCountryCode2() instead.
	 */
	public function getCountryCode2($country3code)
	{
		return RedshopHelperWorld::getCountryCode2($country3code);
	}

	/**
	 * Method to get State code 2 by State code 3.
	 *
	 * @param   int  $stateCode  State 3 code
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.6  Use RedshopHelperWorld::getStateCode2() instead.
	 */
	public function getStateCode2($stateCode)
	{
		return RedshopHelperWorld::getStateCode2($stateCode);
	}

	/**
	 * Method for get State Code
	 *
	 * @param   int     $id         ID of state.
	 * @param   string  $stateCode  State code 2
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.6  Use RedshopHelperWorld::getStateCode() instead.
	 */
	public function getStateCode($id, $stateCode)
	{
		return RedshopHelperWorld::getStateCode($id, $stateCode);
	}
}
