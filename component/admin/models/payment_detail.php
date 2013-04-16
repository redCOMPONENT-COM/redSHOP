<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');

class payment_detailModelpayment_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');

		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{

		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'payment_method WHERE payment_method_id = ' . $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->payment_method_id = 0;
			$detail->payment_method_name = null;
			$detail->payment_class = null;
			$detail->payment_method_code = null;
			$detail->is_creditcard = null;
			$detail->accepted_credict_card = null;
			$detail->payment_extrainfo = null;
			$detail->payment_passkey = null;
			$detail->published = 1;
			$detail->ordering = 0;
			$detail->shopper_group = null;
			$detail->payment_oprand = '+';
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$row->shopper_group = @implode(",", $data['shopper_group']);
		$row->shopper_group = $row->shopper_group ? $row->shopper_group : '';

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$adminpath = JPATH_ADMINISTRATOR . '/components/com_redshop';

		$paymentfile = $adminpath . '/helpers/payments/' . $row->plugin . DS . $row->plugin . '.php';

		$paymentcfg = $adminpath . '/helpers/payments/' . $row->plugin . DS . $row->plugin . '.cfg.php';

		include_once ($paymentfile);

		$ps = new $row->payment_class;

		if (file_exists($paymentcfg))
		{
			if (method_exists($ps, 'write_configuration'))
			{
				$data['cfgfile'] = $paymentcfg;

				$ps->write_configuration($data);
			}
		}

		return true;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'payment_method WHERE payment_method_id  IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'payment_method'
				. ' SET published = ' . intval($publish)
				. ' WHERE  payment_method_id 	 IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function uninstall($eid = array())
	{
		$app = JFactory::getApplication();

		// Initialize variables
		$failed = array();

		/*
		 * Ensure eid is an array of extension ids in the form id => client_id
		 * TODO: If it isn't an array do we want to set an error and fail?
		 */
		if (!is_array($eid))
		{
			$eid = array($eid => 0);
		}

		// Get a database connector
		$db = JFactory::getDBO();

		// Get an installer object for the extension type
		jimport('joomla.installer.installer');
		$installer = JInstaller::getInstance();

		// Uninstall the chosen extensions
		foreach ($eid as $id => $clientId)
		{
			$id = trim($id);
			$result = $installer->uninstall('payment', $id, $clientId);

			// Build an array of extensions that failed to uninstall
			if ($result === false)
			{
				$failed[] = $id;
			}
		}

		if (count($failed))
		{
			// There was an error in uninstalling the package
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('COM_REDSHOP_Error'));
			$result = false;
		}
		else
		{
			// Package uninstalled sucessfully
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('COM_REDSHOP_Success'));
			$result = true;
		}

		$app->enqueueMessage($msg);
		$this->setState('action', 'remove');
		$this->setState('name', $installer->get('name'));
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		return $result;
	}

	public function install()
	{
		$app = JFactory::getApplication();

		$this->setState('action', 'install');

		$package = $this->_getPackageFromUpload();

		if ($package['type'] != 'payment')
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_INVALID_PACKAGE'));

			return false;
		}

		$installer = JInstaller::getInstance();


		if (!$installer->install($package['dir']))
		{
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('COM_REDSHOP_Error'));
			$result = false;
		}
		else
		{
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('COM_REDSHOP_Success'));
			$result = true;
		}

		$app->enqueueMessage($msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		// Cleanup the install files
		if (!is_file($package['packagefile']))
		{
			$config = JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path') . DS . $package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $result;
	}

	public function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('install_package', null, 'files', 'array');


		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads'))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_WARNINSTALLFILE'));

			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib'))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_WARNINSTALLZLIB'));

			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_NO_FILE_SELECTED'));

			return false;
		}

		// Check if there was a problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_WARNINSTALLUPLOADERROR'));

			return false;
		}

		// Build the appropriate paths
		$config = JFactory::getConfig();
		$tmp_dest = $config->getValue('config.tmp_path') . DS . $userfile['name'];
		$tmp_src = $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);

		return $package;
	}


	// Payment ordering
	public function saveOrder(&$cid)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$row =& $this->getTable();

		$total = count($cid);
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		JArrayHelper::toInteger($order, array(0));

		// Update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
		}

		$row->reorder();

		return true;
	}

	/**
	 * Method to get max ordering
	 *
	 * @access public
	 * @return boolean
	 */
	public function MaxOrdering()
	{
		$query = "SELECT (max(ordering)+1) FROM " . $this->_table_prefix . "payment_method";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * Method to move
	 *
	 * @access  public
	 * @return  boolean True on success
	 * @since 0.9
	 */
	public function move($direction)
	{
		$row = JTable::getInstance('payment_detail', 'Table');

		if (!$row->load($this->_id))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->move($direction))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

}
class JInstaller extends JObject
{
	/**
	 * Array of paths needed by the installer
	 * @public array
	 */
	public $_paths = array();

	/**
	 * The installation manifest XML object
	 * @var object
	 */
	public $_manifest = null;

	/**
	 * True if existing files can be overwritten
	 * @var boolean
	 */
	public $_overwrite = false;

	/**
	 * A database connector object
	 * @var object
	 */
	public $_db = null;

	/**
	 * Associative array of package installer handlers
	 * @var array
	 */
	public $_adapters = array();

	/**
	 * Stack of installation steps
	 *    - Used for installation rollback
	 * @var array
	 */
	public $_stepStack = array();

	/**
	 * The output from the install/uninstall scripts
	 * @var string
	 */
	public $message = null;

	/**
	 * Constructor
	 *
	 * @access protected
	 */
	public function __construct()
	{
		$this->_db = JFactory::getDBO();
	}


	public function &getInstance()
	{
		static $instance;

		if (!isset ($instance))
		{
			$instance = new JInstaller;
		}
		return $instance;
	}


	public function getOverwrite()
	{
		return $this->_overwrite;
	}


	public function setOverwrite($state = false)
	{
		$tmp = $this->_overwrite;

		if ($state)
		{
			$this->_overwrite = true;
		}
		else
		{
			$this->_overwrite = false;
		}
		return $tmp;
	}


	public function &getDBO()
	{
		return $this->_db;
	}


	public function &getManifest()
	{
		if (!is_object($this->_manifest))
		{
			$this->_findManifest();
		}
		return $this->_manifest;
	}

	/**
	 * Get an installer path by name
	 *
	 * @access    public
	 *
	 * @param    string $name        Path name
	 * @param    string $default     Default value
	 *
	 * @return    string    Path
	 * @since    1.5
	 */
	public function getPath($name, $default = null)
	{
		return (!empty($this->_paths[$name])) ? $this->_paths[$name] : $default;
	}

	/**
	 * Sets an installer path by name
	 *
	 * @access    public
	 *
	 * @param    string $name     Path name
	 * @param    string $value    Path
	 *
	 * @return    void
	 * @since    1.5
	 */
	public function setPath($name, $value)
	{
		$this->_paths[$name] = $value;
	}

	/**
	 * Pushes a step onto the installer stack for rolling back steps
	 *
	 * @access    public
	 *
	 * @param    array $step    Installer step
	 *
	 * @return    void
	 * @since    1.5
	 */
	public function pushStep($step)
	{
		$this->_stepStack[] = $step;
	}

	/**
	 * Set an installer adapter by name
	 *
	 * @access    public
	 *
	 * @param    string $name        Adapter name
	 * @param    object $adapter     Installer adapter object
	 *
	 * @return    boolean True if successful
	 * @since    1.5
	 */
	public function setAdapter($name, $adapter = null)
	{
		if (!is_object($adapter))
		{
			// Try to load the adapter object
			require_once dirname(__FILE__) . '/adapters/' . strtolower($name) . '.php';
			$class = 'JInstaller' . ucfirst($name);

			if (!class_exists($class))
			{
				return false;
			}
			$adapter = new $class($this);
			$adapter->parent =& $this;
		}
		$this->_adapters[$name] =& $adapter;

		return true;
	}

	/**
	 * Installation abort method
	 *
	 * @access    public
	 *
	 * @param    string $msg     Abort message from the installer
	 * @param    string $type    Package type if defined
	 *
	 * @return    boolean    True if successful
	 * @since    1.5
	 */
	public function abort($msg = null, $type = null)
	{
		// Initialize variables
		$retval = true;
		$step = array_pop($this->_stepStack);

		// Raise abort warning
		if ($msg)
		{
			JError::raiseWarning(100, $msg);
		}

		while ($step != null)
		{
			switch ($step['type'])
			{
				case 'file' :
					// remove the file
					$stepval = JFile::delete($step['path']);
					break;

				case 'folder' :
					// remove the folder
					$stepval = JFolder::delete($step['path']);
					break;

				case 'query' :
					// placeholder in case this is necessary in the future
					break;

				default :
					if ($type && is_object($this->_adapters[$type]))
					{
						// Build the name of the custom rollback method for the type
						$method = '_rollback_' . $step['type'];
						// Custom rollback method handler
						if (method_exists($this->_adapters[$type], $method))
						{
							$stepval = $this->_adapters[$type]->$method($step);
						}
					}
					break;
			}

			// Only set the return value if it is false
			if ($stepval === false)
			{
				$retval = false;
			}

			// Get the next step and continue
			$step = array_pop($this->_stepStack);
		}

		return $retval;
	}

	/**
	 * Package installation method
	 *
	 * @access    public
	 *
	 * @param    string $path    Path to package source folder
	 *
	 * @return    boolean    True if successful
	 * @since    1.5
	 */
	public function install($path = null)
	{
		if ($path && JFolder::exists($path))
		{
			$this->setPath('source', $path);
		}
		else
		{
			$this->abort(JText::_('COM_REDSHOP_INSTALL_PATH_DOES_NOT_EXIST'));

			return false;
		}

		if (!$this->setupInstall())
		{
			$this->abort(JText::_('COM_REDSHOP_UNABLE_TO_DETECT_MANIFEST_FILE'));

			return false;
		}


		$root =& $this->_manifest->document;
		$version = $root->attributes('version');
		$rootName = $root->name();
		$config = JFactory::getConfig();

		$type = $root->attributes('type');

		// Needed for legacy reasons ... to be deprecated in next minor release
		if ($type == 'mambot')
		{
			$type = 'plugin';
		}

		if (is_object($this->_adapters[$type]))
		{
			return $this->_adapters[$type]->install();
		}
		return false;
	}

	/**
	 * Package update method
	 *
	 * @access    public
	 *
	 * @param    string $path    Path to package source folder
	 *
	 * @return    boolean    True if successful
	 * @since    1.5
	 */
	public function update($path = null)
	{
		if ($path && JFolder::exists($path))
		{
			$this->setPath('source', $path);
		}
		else
		{
			$this->abort(JText::_('COM_REDSHOP_UPDATE_PATH_DOES_NOT_EXIST'));
		}

		if (!$this->setupInstall())
		{
			return $this->abort(JText::_('COM_REDSHOP_UNABLE_TO_DETECT_MANIFEST_FILE'));
		}

		/*
		 * LEGACY CHECK
		 */
		$root =& $this->_manifest->document;
		$version = $root->attributes('version');
		$rootName = $root->name();
		$config = JFactory::getConfig();

		$type = $root->attributes('type');

		// Needed for legacy reasons ... to be deprecated in next minor release
		if ($type == 'mambot')
		{
			$type = 'plugin';
		}

		if (is_object($this->_adapters[$type]))
		{
			return $this->_adapters[$type]->update();
		}
		return false;
	}

	/**
	 * Package uninstallation method
	 *
	 * @access    public
	 *
	 * @param    string $type          Package type
	 * @param    mixed  $identifier    Package identifier for adapter
	 * @param    int    $cid           Application ID
	 *
	 * @return    boolean    True if successful
	 * @since    1.5
	 */
	public function uninstall($type, $identifier, $cid = 0)
	{
		if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type]))
		{
			if (!$this->setAdapter($type))
			{
				return false;
			}
		}
		if (is_object($this->_adapters[$type]))
		{
			return $this->_adapters[$type]->uninstall($identifier, $cid);
		}
		return false;
	}

	/**
	 * Prepare for installation: this method sets the installation directory, finds
	 * and checks the installation file and verifies the installation type
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function setupInstall()
	{
		// We need to find the installation manifest file
		if (!$this->_findManifest())
		{
			return false;
		}

		// Load the adapter(s) for the install manifest
		$root =& $this->_manifest->document;
		$type = $root->attributes('type');


		// Lazy load the adapter
		if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type]))
		{
			if (!$this->setAdapter($type))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Backward compatible Method to parse through a queries element of the
	 * installation manifest file and take appropriate action.
	 *
	 * @access    public
	 *
	 * @param    object $element    The xml node to process
	 *
	 * @return    mixed    Number of queries processed or False on error
	 * @since    1.5
	 */
	public function parseQueries($element)
	{
		// Get the database connector object
		$db = & $this->_db;

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children()))
		{
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return 0;
		}

		// Get the array of query nodes to process
		$queries = $element->children();

		if (count($queries) == 0)
		{
			// No queries to process
			return 0;
		}

		// Process each query in the $queries array (children of $tagName).
		foreach ($queries as $query)
		{
			$db->setQuery($query->data());

			if (!$db->query())
			{
				JError::raiseWarning(1, 'JInstaller::install: ' . JText::_('COM_REDSHOP_SQL_ERROR') . " " . $db->stderr(true));

				return false;
			}
		}
		return (int) count($queries);
	}

	/**
	 * Method to extract the name of a discreet installation sql file from the installation manifest file.
	 *
	 * @access    public
	 *
	 * @param    object $element    The xml node to process
	 * @param    string $version    The database connector to use
	 *
	 * @return    mixed    Number of queries processed or False on error
	 * @since    1.5
	 */
	public function parseSQLFiles($element)
	{
		// Initialize variables
		$queries = array();
		$db = & $this->_db;
		$dbDriver = strtolower($db->get('name'));

		if ($dbDriver == 'mysqli')
		{
			$dbDriver = 'mysql';
		}
		$dbCharset = ($db->hasUTF()) ? 'utf8' : '';

		if (!is_a($element, 'JSimpleXMLElement'))
		{
			// The tag does not exist.
			return 0;
		}

		// Get the array of file nodes to process
		$files = $element->children();

		if (count($files) == 0)
		{
			// No files to process
			return 0;
		}

		// Get the name of the sql file to process
		$sqlfile = '';

		foreach ($files as $file)
		{
			$fCharset = (strtolower($file->attributes('charset')) == 'utf8') ? 'utf8' : '';
			$fDriver = strtolower($file->attributes('driver'));

			if ($fDriver == 'mysqli')
			{
				$fDriver = 'mysql';
			}

			if ($fCharset == $dbCharset && $fDriver == $dbDriver)
			{
				$sqlfile = $file->data();
				// Check that sql files exists before reading. Otherwise raise error for rollback
				if (!file_exists($this->getPath('extension_administrator') . DS . $sqlfile))
				{
					return false;
				}
				$buffer = file_get_contents($this->getPath('extension_administrator') . DS . $sqlfile);

				// Graceful exit and rollback if read not successful
				if ($buffer === false)
				{
					return false;
				}

				// Create an array of queries from the sql file
				jimport('joomla.installer.helper');
				$queries = JInstallerHelper::splitSql($buffer);

				if (count($queries) == 0)
				{
					// No queries to process
					return 0;
				}

				// Process each query in the $queries array (split out of sql file).
				foreach ($queries as $query)
				{
					$query = trim($query);

					if ($query != '' && $query{0} != '#')
					{
						$db->setQuery($query);

						if (!$db->query())
						{
							JError::raiseWarning(1, 'JInstaller::install: ' . JText::_('COM_REDSHOP_SQL_ERROR') . " " . $db->stderr(true));

							return false;
						}
					}
				}
			}
		}

		return (int) count($queries);
	}

	/**
	 * Method to parse through a files element of the installation manifest and take appropriate
	 * action.
	 *
	 * @access    public
	 *
	 * @param    object $element    The xml node to process
	 * @param    int    $cid        Application ID of application to install to
	 *
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function parseFiles($element, $cid = 0, $pFolder)
	{
		// Initialize variables
		$copyfiles = array();

		// Get the client info
		jimport('joomla.application.helper');
		$client =& JApplicationHelper::getClientInfo($cid);

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children()))
		{
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return 0;
		}

		// Get the array of file nodes to process
		$files = $element->children();

		if (count($files) == 0)
		{
			// No files to process
			return 0;
		}

		/*
		 * Here we set the folder we are going to remove the files from.
		 */
		if ($client)
		{
			$pathname = 'extension_' . $client->name;
			$destination = $this->getPath($pathname);
		}
		else
		{
			$pathname = 'extension_root';
			$destination = $this->getPath($pathname);
		}


		if ($folder = $element->attributes('folder'))
		{
			$source = $this->getPath('source') . DS . $folder;
		}
		else
		{
			$source = $this->getPath('source');
		}

		// Process each file in the $files array (children of $tagName).
		foreach ($files as $file)
		{
			$path['src'] = $source . DS . $file->data();
			$path['dest'] = $destination . DS . $pFolder . DS . $file->data();

			// Is this path a file or folder?
			$path['type'] = ($file->name() == 'folder') ? 'folder' : 'file';

			if (basename($path['dest']) != $path['dest'])
			{
				$newdir = dirname($path['dest']);

				if (!JFolder::create($newdir))
				{
					JError::raiseWarning(1, 'JInstaller::install: ' . JText::_('COM_REDSHOP_FAILED_TO_CREATE_DIRECTORY') . ' "' . $newdir . '"');

					return false;
				}
			}

			// Add the file to the copyfiles array
			$copyfiles[] = $path;
		}

		return $this->copyFiles($copyfiles);
	}


	public function getParams()
	{
		// Get the manifest document root element
		$root = & $this->_manifest->document;

		// Get the element of the tag names
		$element =& $root->getElementByPath('params');

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children()))
		{
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return null;
		}

		// Get the array of parameter nodes to process
		$params = $element->children();

		if (count($params) == 0)
		{
			// No params to process
			return null;
		}

		// Process each parameter in the $params array.
		$ini = null;

		foreach ($params as $param)
		{
			if (!$name = $param->attributes('name'))
			{
				continue;
			}

			if (!$value = $param->attributes('default'))
			{
				continue;
			}

			$ini .= $name . "=" . $value . "\n";
		}
		return $ini;
	}


	public function copyFiles($files, $overwrite = null)
	{

		if (is_null($overwrite) || !is_bool($overwrite))
		{
			$overwrite = $this->_overwrite;
		}


		if (is_array($files) && count($files) > 0)
		{
			foreach ($files as $file)
			{
				// Get the source and destination paths
				$filesource = JPath::clean($file['src']);
				$filedest = JPath::clean($file['dest']);
				$filetype = array_key_exists('type', $file) ? $file['type'] : 'file';

				$upgrade = array_key_exists('upgrade', $file) ? $file['upgrade'] : 1;

				if (!file_exists($filesource))
				{

					JError::raiseWarning(1, 'JInstaller::install: ' . JText::sprintf('File does not exist', $filesource));

					return false;
				}
				elseif (file_exists($filedest) && !$overwrite)
				{


					if ($this->getPath('manifest') == $filesource)
					{
						continue;
					}

					JError::raiseWarning(1, 'JInstaller::install: ' . JText::sprintf('WARNSAME', $filedest));

					return false;
				}
				else
				{

					// Copy the folder or file to the new location.
					if ($filetype == 'folder')
					{

						if (!(JFolder::copy($filesource, $filedest, null, $overwrite)))
						{
							JError::raiseWarning(1, 'JInstaller::install: ' . JText::sprintf('Failed to copy folder to', $filesource, $filedest));

							return false;
						}

						$step = array('type' => 'folder', 'path' => $filedest);
					}
					else
					{
						if (strstr($filesource, ".cfg.") && file_exists($filedest))
						{
							continue;
						}
						if (!(JFile::copy($filesource, $filedest)))
						{
							JError::raiseWarning(1, 'JInstaller::install: ' . JText::sprintf('Failed to copy file to', $filesource, $filedest));

							return false;
						}

						$step = array('type' => 'file', 'path' => $filedest);
					}


					$this->_stepStack[] = $step;
				}
			}
		}
		else
		{


			return false;
		}
		return count($files);
	}


	public function removeFiles($element, $cid = 0)
	{
		// Initialize variables
		$removefiles = array();
		$retval = true;

		// Get the client info
		jimport('joomla.application.helper');
		$client = JApplicationHelper::getClientInfo($cid);

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children()))
		{
			return true;
		}

		// Get the array of file nodes to process
		$files = $element->children();

		if (count($files) == 0)
		{
			// No files to process
			return true;
		}


		switch ($element->name())
		{
			case 'media':
				if ($element->attributes('destination'))
				{
					$folder = $element->attributes('destination');
				}
				else
				{
					$folder = '';
				}
				$source = $client->path . '/media/' . $folder;
				break;

			case 'languages':
				$source = $client->path . '/language';
				break;

			default:
				if ($client)
				{
					$pathname = 'extension_' . $client->name;
					$source = $this->getPath($pathname);
				}
				else
				{
					$pathname = 'extension_root';
					$source = $this->getPath($pathname);
				}
				break;
		}


		foreach ($files as $file)
		{

			if ($file->name() == 'language' && $file->attributes('tag') != '')
			{
				$path = $source . DS . $file->attributes('tag') . DS . basename($file->data());

				// If the language folder is not present, then the core pack hasn't been installed... ignore
				if (!JFolder::exists(dirname($path)))
				{
					continue;
				}
			}
			else
			{
				$path = $source . DS . $file->data();
			}

			if (is_dir($path))
			{
				$val = JFolder::delete($path);
			}
			else
			{
				$val = JFile::delete($path);
			}

			if ($val === false)
			{
				$retval = false;
			}
		}

		return $retval;
	}

	public function copyManifest($cid = 1)
	{
		// Get the client info
		jimport('joomla.application.helper');
		$client = JApplicationHelper::getClientInfo($cid);

		$path['src'] = $this->getPath('manifest');

		if ($client)
		{
			$pathname = 'extension_' . $client->name;
			$path['dest'] = $this->getPath($pathname) . DS . basename($this->getPath('manifest'));
		}
		else
		{
			$pathname = 'extension_root';
			$path['dest'] = $this->getPath($pathname) . DS . basename($this->getPath('manifest'));
		}
		return $this->copyFiles(array($path), true);
	}

	public function _findManifest()
	{
		// Get an array of all the xml files from teh installation directory
		$xmlfiles = JFolder::files($this->getPath('source'), '.xml$', 1, true);
		// If at least one xml file exists
		if (!empty($xmlfiles))
		{
			foreach ($xmlfiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);

				if (!is_null($manifest))
				{

					// If the root method attribute is set to upgrade, allow file overwrite
					$root =& $manifest->document;

					if ($root->attributes('method') == 'upgrade')
					{
						$this->_overwrite = true;
					}

					// Set the manifest object and path
					$this->_manifest =& $manifest;
					$this->setPath('manifest', $file);

					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));

					return true;
				}
			}

			// None of the xml files found were valid install files
			JError::raiseWarning(1, 'JInstaller::install: ' . JText::_('COM_REDSHOP_ERRORNOTFINDJOOMLAXMLSETUPFILE'));

			return false;
		}
		else
		{
			// No xml files were found in the install folder
			JError::raiseWarning(1, 'JInstaller::install: ' . JText::_('COM_REDSHOP_ERRORXMLSETUP'));

			return false;
		}
	}

	public function &_isManifest($file)
	{
		// Initialize variables
		$null = null;
		$xml = JFactory::getXMLParser('Simple');

		// If we cannot load the xml file return null
		if (!$xml->loadFile($file))
		{
			// Free up xml parser memory and return null
			unset ($xml);

			return $null;
		}


		$root =& $xml->document;

		if (!is_object($root) || ($root->name() != 'install' && $root->name() != 'mosinstall'))
		{
			// Free up xml parser memory and return null
			unset ($xml);

			return $null;
		}

		// Valid manifest file return the object
		return $xml;
	}
}


