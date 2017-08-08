<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Plugin;

use Redshop\Filesystem\File;
use Redshop\Filesystem\File\Parser;
use Redshop\String\Helper;

/**
 * Import & Export abstract base class
 *
 * @package     Redshop\Plugin
 *
 * @since       version
 */
class ImportExport extends AbstractBase
{
	/**
	 * @var  string
	 *
	 * @since  2.0.3
	 */
	protected $defaultExtension = 'csv';

	/**
	 * @var  string
	 *
	 * @since  2.0.3
	 */
	protected $separator = ',';

	/**
	 * @var string
	 *
	 * @since  2.0.3
	 */
	protected $folder = '';

	/**
	 * @var  \JDatabaseDriver
	 *
	 * @since  2.0.3
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param   object  $subject     The object to observe
	 * @param   array   $config      An optional associative array of configuration settings.
	 *                              Recognized key values include 'name', 'group', 'params', 'language'
	 *                              (this list is not meant to be comprehensive).
	 *
	 * @since   2.0.3
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);

		$this->db = \JFactory::getDbo();
		$this->separator = \JFactory::getApplication()->input->getString('separator');
	}

	/**
	 * Generate random temporary name
	 * Name will stored into session and re-use if it's created
	 *
	 * @param   string   $key    Key
	 * @param   boolean  $renew  Generate new value
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	protected function getTemporaryName($key = 'default', $renew = false)
	{
		return Helper::getUserRandomStringByKey('plugin.' . $this->_type . '.' . $this->_name . '.' . $key, $renew);
	}

	/**
	 * Get temporary folder using in this plugin
	 *
	 * @return string
	 *
	 * @since   2.0.3
	 */
	protected function getTemporaryFolder()
	{
		return JPATH_ROOT . '/tmp/redshop/' . $this->_type . '/' . $this->_name;
	}

	/**
	 * Get working folder ( sub folder of temporary ) using in this plugin
	 *
	 * @return string
	 *
	 * @since   2.0.3
	 */
	public function getWorkingFolder()
	{
		return $this->getTemporaryFolder() . '/' . $this->folder;
	}

	/**
	 * Method for get path of temporary file.
	 *
	 * @param   string   $key    Key to generate or get random filename
	 * @param   boolean  $renew  Generate new string
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	protected function getTemporaryFile($key = 'default', $renew = false)
	{
		return $this->getTemporaryFolder() . '/' . $this->_name . '_' . $key . '_' . $this->getTemporaryName($key, $renew);
	}

	/**
	 * Load excel file
	 *
	 * @param   string  $file  File path
	 *
	 * @return  boolean|Parser\Excel
	 *
	 * @since   2.0.7
	 */
	protected function loadFile($file)
	{
		if (\JFile::exists($file))
		{
			return Parser\Excel::load($file, $this->separator);
		}

		return false;
	}
}
