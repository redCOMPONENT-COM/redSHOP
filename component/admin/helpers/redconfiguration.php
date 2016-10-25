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

		return;
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
	 */
	public function backupCFGFile()
	{
		if ($this->isCFGFile())
		{
			if (!copy($this->configPath, $this->configBkpPath))
			{
				return false;
			}
		}

		else
		{
			if (!copy($this->configDistPath, $this->configPath))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Try to find if temp configuration file is available. This function is for wizard.
	 *
	 * @return  boolean  True when file is exist.
	 */
	public function isTmpFile()
	{
		if (file_exists($this->configTmpPath))
		{
			if ($this->isTMPFileWritable())
			{
				require_once $this->configTmpPath;

				return true;
			}
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_FOUND'), 'error');
		}

		return false;
	}

	/**
	 * Check if temp file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 */
	public function isTMPFileWritable()
	{
		if (!is_writable($this->configTmpPath))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_WRITABLE'), 'error');

			return false;
		}

		return true;
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
	 */
	public function defineDynamicVars()
	{
		$config = Redshop::getConfig();

		$config->set('SHOW_PRICE', $this->showPrice());
		$config->set('USE_AS_CATALOG', $this->getCatalog());

		$quotationModePre = (int) $config->get('DEFAULT_QUOTATION_MODE_PRE');

		$config->set('DEFAULT_QUOTATION_MODE', $quotationModePre);

		if ($quotationModePre == 1)
		{
			$config->set('DEFAULT_QUOTATION_MODE', (int) $this->setQuotationMode());
		}
	}

	public function showPrice()
	{
		$user       = JFactory::getUser();
		$userHelper = rsUserHelper::getInstance();
		$shopperGroupId = $userHelper->getShopperGroup($user->id);
		$list = $userHelper->getShopperGroupList($shopperGroupId);

		if ($list)
		{
			$list = $list[0];

			if (($list->show_price == "yes") || ($list->show_price == "global" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1)
				|| ($list->show_price == "" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return Redshop::getConfig()->get('SHOW_PRICE_PRE');
		}
	}

	public function getCatalog()
	{
		$user             = JFactory::getUser();
		$userHelper       = rsUserHelper::getInstance();
		$shopperGroupId = $userHelper->getShopperGroup($user->id);
		$list = $userHelper->getShopperGroupList($shopperGroupId);

		if ($list)
		{
			$list = $list[0];

			if (($list->use_as_catalog == "yes") || ($list->use_as_catalog == "global" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
				|| ($list->use_as_catalog == "" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}

		else
		{
			return Redshop::getConfig()->get('PRE_USE_AS_CATALOG');
		}
	}

	public function setQuotationMode()
	{
		$db = JFactory::getDbo();
		$user             = JFactory::getUser();
		$userhelper       = rsUserHelper::getInstance();
		$shopper_group_id = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

		if ($user->id)
		{
			$getShopperGroupID = $userhelper->getShopperGroup($user->id);

			if ($getShopperGroupID)
			{
				$shopper_group_id = $getShopperGroupID;
			}
		}

		$qurey = "SELECT * FROM #__redshop_shopper_group "
			. "WHERE shopper_group_id = " . (int) $shopper_group_id;
		$db->setQuery($qurey);
		$list = $db->loadObject();

		if ($list)
		{
			if ($list->shopper_group_quotation_mode)
			{
				return true;
			}

			return false;
		}

		return Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE_PRE');
	}

	public function maxchar($desc = '', $maxchars = 0, $suffix = '')
	{
		$strdesc = '';

		if ((int) $maxchars <= 0)
		{
			$strdesc = $desc;
		}
		else
		{
			$strdesc = $this->substrws($desc, $maxchars, $suffix);
		}

		return $strdesc;
	}

	public function substrws($text, $length = 50, $ending = '...', $exact = false, $considerHtml = true)
	{
		if ($considerHtml)
		{
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
			{
				return $text;
			}

			$totalLength = strlen(strip_tags($ending));
			$openTags    = array();
			$truncate    = '';

			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);

			foreach ($tags as $tag)
			{
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2]))
				{
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0]))
					{
						array_unshift($openTags, $tag[2]);
					}

					elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag))
					{
						$pos = array_search($closeTag[1], $openTags);

						if ($pos !== false)
						{
							array_splice($openTags, $pos, 1);
						}
					}
				}

				$truncate .= $tag[1];

				$contentLength = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));

				if ($contentLength + $totalLength > $length)
				{
					$left           = $length - $totalLength;
					$entitiesLength = 0;

					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE))
					{
						foreach ($entities[0] as $entity)
						{
							if ($entity[1] + 1 - $entitiesLength <= $left)
							{
								$left--;
								$entitiesLength += strlen($entity[0]);
							}
							else
							{
								break;
							}
						}
					}

					$truncate .= substr($tag[3], 0, $left + $entitiesLength);
					break;
				}
				else
				{
					$truncate .= $tag[3];
					$totalLength = $contentLength;
				}

				if ($totalLength >= $length)
				{
					break;
				}
			}
		}

		else
		{
			if (strlen($text) <= $length)
			{
				return $text;
			}
			else
			{
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}

		if (!$exact)
		{
			$spacepos = strrpos($truncate, ' ');

			if (isset($spacepos))
			{
				if ($considerHtml)
				{
					$bits = substr($truncate, $spacepos);
					preg_match_all('/<\/([a-z])>/', $bits, $droppedTags, PREG_SET_ORDER);

					if (!empty($droppedTags))
					{
						foreach ($droppedTags as $closingTag)
						{
							if (!in_array($closingTag[1], $openTags))
							{
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}

				$truncate = substr($truncate, 0, $spacepos);
			}
		}

		$truncate .= $ending;

		if ($considerHtml)
		{
			foreach ($openTags as $tag)
			{
				$truncate .= '</' . $tag . '>';
			}
		}

		return $truncate;
	}

	/**
	 * Method to get date format
	 *
	 * @access    public
	 *
	 * @return    array
	 *
	 * @since    1.5
	 */
	public function getDateFormat()
	{
		$option = array();
		$mon    = JText::_(strtoupper(date("M")));
		$month  = JText::_(strtoupper(date("F")));
		$wk     = JText::_(strtoupper(date("D")));
		$week   = JText::_(strtoupper(date("l")));

		$option[] = JHTML::_('select.option', '0', JText::_('SELECT'));
		$option[] = JHTML::_('select.option', 'Y-m-d', date("Y-m-d"));
		$option[] = JHTML::_('select.option', 'd-m-Y', date("d-m-Y"));
		$option[] = JHTML::_('select.option', 'd.m.Y', date("d.m.Y"));
		$option[] = JHTML::_('select.option', 'Y/m/d', date("Y/m/d"));
		$option[] = JHTML::_('select.option', 'd/m/Y', date("d/m/Y"));
		$option[] = JHTML::_('select.option', 'm/d/y', date("m/d/y"));
		$option[] = JHTML::_('select.option', 'm-d-y', date("m-d-y"));
		$option[] = JHTML::_('select.option', 'm.d.y', date("m.d.y"));
		$option[] = JHTML::_('select.option', 'm/d/Y', date("m/d/Y"));
		$option[] = JHTML::_('select.option', 'm-d-Y', date("m-d-Y"));
		$option[] = JHTML::_('select.option', 'm.d.Y', date("m.d.Y"));
		$option[] = JHTML::_('select.option', 'd/M/Y', date("d/") . $mon . date("/Y"));
		$option[] = JHTML::_('select.option', 'M d,Y', $mon . date(" d, Y"));
		$option[] = JHTML::_('select.option', 'd M Y', date("d ") . $mon . date(" Y"));
		$option[] = JHTML::_('select.option', 'd M Y, h:i:s', date("d ") . $mon . date(" Y, h:i:s"));
		$option[] = JHTML::_('select.option', 'd M Y, h:i A', date("d ") . $mon . date(" Y, h:i A"));
		$option[] = JHTML::_('select.option', 'd-m-Y, h:i:A', date("d-m-Y, h:i:A"));
		$option[] = JHTML::_('select.option', 'd.m.Y, h:i:A', date("d.m.Y, h:i:A"));
		$option[] = JHTML::_('select.option', 'd/m/Y, h:i:A', date("d/m/Y, h:i:A"));
		$option[] = JHTML::_('select.option', 'd M Y, H:i:s', date("d ") . $mon . date(" Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'd-m-Y, H:i:s', date("d-m-Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'd.m.Y, H:i:s', date("d.m.Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'd/m/Y, H:i:s', date("d/m/Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'F d, Y', $month . date(" d, Y"));
		$option[] = JHTML::_('select.option', 'D M d, Y', $wk . " " . $mon . date(" d, Y"));
		$option[] = JHTML::_('select.option', 'l F d, Y', $week . " " . $month . date(" d, Y"));

		return $option;
	}

	/**
	 * Method to convert date according to format
	 *
	 * @access    public
	 *
	 * @return    array
	 *
	 * @since    1.5
	 */
	public function convertDateFormat($date)
	{
		if ($date <= 0)
		{
			$date = time();
		}

		if (Redshop::getConfig()->get('DEFAULT_DATEFORMAT'))
		{
			$convertformat = date(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), $date);

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "M") !== false)
			{
				$convertformat = str_replace("Jan", JText::_('COM_REDSHOP_JAN'), $convertformat);
				$convertformat = str_replace("Feb", JText::_('COM_REDSHOP_FEB'), $convertformat);
				$convertformat = str_replace("Mar", JText::_('COM_REDSHOP_MAR'), $convertformat);
				$convertformat = str_replace("Apr", JText::_('COM_REDSHOP_APR'), $convertformat);
				$convertformat = str_replace("May", JText::_('COM_REDSHOP_MAY'), $convertformat);
				$convertformat = str_replace("Jun", JText::_('COM_REDSHOP_JUN'), $convertformat);
				$convertformat = str_replace("Jul", JText::_('COM_REDSHOP_JUL'), $convertformat);
				$convertformat = str_replace("Aug", JText::_('COM_REDSHOP_AUG'), $convertformat);
				$convertformat = str_replace("Sep", JText::_('COM_REDSHOP_SEP'), $convertformat);
				$convertformat = str_replace("Oct", JText::_('COM_REDSHOP_OCT'), $convertformat);
				$convertformat = str_replace("Nov", JText::_('COM_REDSHOP_NOV'), $convertformat);
				$convertformat = str_replace("Dec", JText::_('COM_REDSHOP_DEC'), $convertformat);
			}

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "F") !== false)
			{
				$convertformat = str_replace("January", JText::_('COM_REDSHOP_JANUARY'), $convertformat);
				$convertformat = str_replace("February", JText::_('COM_REDSHOP_FEBRUARY'), $convertformat);
				$convertformat = str_replace("March", JText::_('COM_REDSHOP_MARCH'), $convertformat);
				$convertformat = str_replace("April", JText::_('COM_REDSHOP_APRIL'), $convertformat);
				$convertformat = str_replace("May", JText::_('COM_REDSHOP_MAY'), $convertformat);
				$convertformat = str_replace("June", JText::_('COM_REDSHOP_JUNE'), $convertformat);
				$convertformat = str_replace("July", JText::_('COM_REDSHOP_JULY'), $convertformat);
				$convertformat = str_replace("August", JText::_('COM_REDSHOP_AUGUST'), $convertformat);
				$convertformat = str_replace("September", JText::_('COM_REDSHOP_SEPTEMBER'), $convertformat);
				$convertformat = str_replace("October", JText::_('COM_REDSHOP_OCTOBER'), $convertformat);
				$convertformat = str_replace("November", JText::_('COM_REDSHOP_NOVEMBER'), $convertformat);
				$convertformat = str_replace("December", JText::_('COM_REDSHOP_DECEMBER'), $convertformat);
			}

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "D") !== false)
			{
				$convertformat = str_replace("Mon", JText::_('COM_REDSHOP_MON'), $convertformat);
				$convertformat = str_replace("Tue", JText::_('COM_REDSHOP_TUE'), $convertformat);
				$convertformat = str_replace("Wed", JText::_('COM_REDSHOP_WED'), $convertformat);
				$convertformat = str_replace("Thu", JText::_('COM_REDSHOP_THU'), $convertformat);
				$convertformat = str_replace("Fri", JText::_('COM_REDSHOP_FRI'), $convertformat);
				$convertformat = str_replace("Sat", JText::_('COM_REDSHOP_SAT'), $convertformat);
				$convertformat = str_replace("Sun", JText::_('COM_REDSHOP_SUN'), $convertformat);
			}

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "l") !== false)
			{
				$convertformat = str_replace("Monday", JText::_('COM_REDSHOP_MONDAY'), $convertformat);
				$convertformat = str_replace("Tuesday", JText::_('COM_REDSHOP_TUESDAY'), $convertformat);
				$convertformat = str_replace("Wednesday", JText::_('COM_REDSHOP_WEDNESDAY'), $convertformat);
				$convertformat = str_replace("Thursday", JText::_('COM_REDSHOP_THURSDAY'), $convertformat);
				$convertformat = str_replace("Friday", JText::_('COM_REDSHOP_FRIDAY'), $convertformat);
				$convertformat = str_replace("Saturday", JText::_('COM_REDSHOP_SATURDAY'), $convertformat);
				$convertformat = str_replace("Sunday", JText::_('COM_REDSHOP_SUNDAY'), $convertformat);
			}
		}

		else
		{
			$convertformat = date("Y-m-d", $date);
		}

		return $convertformat;
	}

	/**
	 * Method to get Country by ID
	 *
	 * @param   int  $conid  country id
	 *
	 * @return  country
	 */
	public function getCountryId($conid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('id'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->q($conid));

		$db->setQuery($query);

		return $db->loadResult();
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
