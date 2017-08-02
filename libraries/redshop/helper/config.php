<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redshop Configuration
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopHelperConfig
{
	/**
	 * javascript strings for configuration variables
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected static $jsStrings = array();

	/**
	 * Check if script declaration of config js store is loaded or not.
	 *
	 * @var  boolean
	 */
	private static $isLoadScriptDeclaration = false;

	/**
	 * Configuration
	 *
	 * @var  Registry
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param   mixed  $namespace  Namespace.
	 */
	public function __construct($namespace = '')
	{
		$this->loadConfig($namespace);
	}

	/**
	 * Magic method to transparently use registry methods on config
	 *
	 * @param   string  $name       Name of the function.
	 * @param   array   $arguments  [0] The name of the variable [1] The default value.
	 *
	 * @return  mixed
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this->config, $name))
		{
			return call_user_func_array(array($this->config, $name), $arguments);
		}

		trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);

		return false;
	}

	/**
	 * Get the path to this station configuration file
	 *
	 * @return  string
	 */
	protected function getConfigurationFilePath()
	{
		return JPATH_ADMINISTRATOR . '/components/com_redshop/config/config.php';
	}

	/**
	 * Get the path to this configuration distinct file
	 *
	 * @return  string
	 */
	protected function getConfigurationDistFilePath()
	{
		return JPATH_ADMINISTRATOR . '/components/com_redshop/config/config.dist.php';
	}

	/**
	 * Check config file is exist
	 *
	 * @return  boolean  Returns TRUE if the file or directory specified by filename exists; FALSE otherwise.
	 */
	public function isExists()
	{
		return file_exists($this->getConfigurationFilePath());
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   mixed  $namespace  Namespace.
	 *
	 * @return  self
	 */
	public function loadConfig($namespace = '')
	{
		$this->config = new Registry;

		$file = $this->getConfigurationFilePath();

		if (!JFile::exists($file))
		{
			return $this;
		}

		include_once $file;

		// Sanitize the namespace.
		$namespace = ucfirst((string) preg_replace('/[^A-Z_]/i', '', $namespace));

		// Build the config name.
		$name = 'RedshopConfig' . $namespace;

		// Handle the PHP configuration type.
		if (class_exists($name))
		{
			// Create the JConfig object
			$class = new $name;

			// Load the configuration values into the registry
			$this->config->loadObject($class);
		}

		return $this;
	}

	/**
	 * Save configuration to file
	 *
	 * @param   mixed  $config  Null to avoid binding any data | JRegistry to bind config and save
	 *
	 * @throws  Exception
	 * @return  boolean
	 */
	public function save($config = null)
	{
		if ($config instanceof JRegistry || $config instanceof Registry)
		{
			$this->config->merge($config);
		}

		jimport('joomla.filesystem.path');
		jimport('joomla.filesystem.file');

		// Set the configuration file path.
		$file         = $this->getConfigurationFilePath();
		$configFolder = dirname($file);

		if (!is_dir($configFolder) && !mkdir($configFolder, 0755, true))
		{
			throw new Exception('Unable to create configuration folder');
		}

		$app = JFactory::getApplication();

		// Attempt to make the file writable if using FTP.
		if (file_exists($file) && JPath::isOwner($file) && !JPath::setPermissions($file, '0644'))
		{
			$app->enqueueMessage(JText::_('LIB_REDSHOP_ERROR_CONFIGURATION_PHP_NOTWRITABLE'), 'notice');
		}

		// Attempt to write the configuration file as a PHP class named RedshopConfig.
		$configuration = $config->toString('PHP', array('class' => 'RedshopConfig', 'closingtag' => false));

		if (!JFile::write($file, $configuration))
		{
			throw new RuntimeException(JText::_('LIB_REDSHOP_ERROR_WRITE_FAILED'));
		}

		// Attempt to make the file unwriteable if using FTP.
		if (JPath::isOwner($file) && !JPath::setPermissions($file, '0444'))
		{
			$app->enqueueMessage(JText::_('LIB_REDSHOP_ERROR_CONFIGURATION_PHP_NOTUNWRITABLE'), 'notice');
		}

		return true;
	}

	/**
	 * Save new config file using legacy or legacy styled custom configuration files.
	 *
	 * @param   string  $configFile  Path to legacy styled configuration file
	 *
	 * @throws  exception  Throw invalid argument and exception if file is not exist and invalid.
	 * @return  boolean    True on success
	 */
	public function loadLegacy($configFile = null)
	{
		if ($this->isExists())
		{
			return false;
		}

		// Try to migrate old configuration
		if ($this->loadOldConfig())
		{
			return true;
		}

		// Check if custom file path is given and exist
		if ($configFile && !file_exists($configFile))
		{
			throw new InvalidArgumentException(JText::sprintf('LIB_REDSHOP_FILE_IS_NOT_EXIST', $configFile));
		}

		// Priority to custom file given in method argument
		if (!$configFile)
		{
			$legacyConfig = new Redconfiguration;

			// Load from old version configuration
			if (file_exists($legacyConfig->configPath))
			{
				$configFile = $legacyConfig->configPath;
			}

			// Check for distinct configuration file
			elseif (file_exists($legacyConfig->configDistPath))
			{
				$configFile = $legacyConfig->configDistPath;
			}
			else
			{
				throw new Exception(JText::_('LIB_REDSHOP_LEGACY_CONFIG_FILE_IS_NOT_EXIST'));
			}
		}

		require_once $configFile;

		$allDefinedConstants = get_defined_constants(true);
		$configDataArray     = $allDefinedConstants['user'];

		if (empty($configDataArray))
		{
			throw new Exception(JText::sprintf('LIB_REDSHOP_LEGACY_CONFIG_FILE_IS_NOT_VALID', $configFile));
		}

		try
		{
			$this->save(new Registry($configDataArray));

			return true;
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

			return false;
		}
	}

	/**
	 * Load Distinct configuration file
	 *
	 * @since  1.7
	 *
	 * @return  boolean  True on success
	 */
	public function loadDist()
	{
		// Only load dist file when config file is not exist.
		if (!$this->isExists())
		{
			jimport('joomla.filesystem.file');

			if ($this->loadOldConfig())
			{
				return true;
			}

			return JFile::copy($this->getConfigurationDistFilePath(), $this->getConfigurationFilePath());
		}

		return true;
	}

	/**
	 * Load previous configuration
	 *
	 * @return  boolean
	 */
	protected function loadOldConfig()
	{
		// Since 1.6 we started moving to new config than try to migrate it
		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.6', '<'))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_TRY_TO_MIGRATE_PREVIOUS_CONFIGURATION'), 'notice');

			$oldConfigFile = JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

			// Old configuration file
			if (JFile::exists($oldConfigFile))
			{
				// New configuration file
				require_once JPATH_ADMINISTRATOR . '/components/com_redshop/config/config.dist.php';

				// Old configuration file
				require_once $oldConfigFile;

				// Get new configuration properties
				$configClass = new RedshopConfig;
				$properties  = get_object_vars($configClass);

				// Get old configiration properties
				$defined = get_defined_constants();

				// Replace new configuration values with old one
				foreach ($properties as $name => $value)
				{
					if (in_array($name, $defined))
					{
						if (isset($defined[$name]))
						{
							$properties[$name] = $defined[$name];
						}
					}
				}

				// Save to config file
				$this->save(new Registry($properties));
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MIGRATED_PREVIOUS_CONFIGURATION'), 'notice');

				return JFile::delete($oldConfigFile);
			}

			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_PREVIOUS_CONFIGURATION_NOT_FOUND'), 'warning');

			return false;
		}

		return false;
	}

	/**
	 * Stores redshop configuration strings in the JavaScript language store.
	 *
	 * @param   string  $key    The Javascript config string key.
	 * @param   string  $value  The Javascript config string value.
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function script($key = null, $value = null)
	{
		// Add the key to the array if not null.
		if ($key !== null)
		{
			// Assign key to the value
			self::$jsStrings[strtoupper($key)] = $value;
		}

		return self::$jsStrings;
	}

	/**
	 * Set javascript strings
	 *
	 * @return  void
	 */
	public static function scriptDeclaration()
	{
		if (self::$isLoadScriptDeclaration)
		{
			return;
		}

		// Load redshop script
		JHtml::script('com_redshop/redshop.js', false, true);

		JFactory::getDocument()->addScriptDeclaration('
			(function($) {
				var RedshopStrings = ' . json_encode(self::script()) . ';
				if (typeof redSHOP == "undefined") {
					redSHOP = {};
					redSHOP.RSConfig = {};
					redSHOP.RSConfig.strings = RedshopStrings;
				}
				else {
					redSHOP.RSConfig.load(RedshopStrings);
				}

				$(document).ready(function(){
					var bootstrapLoaded = (typeof $().carousel == "function");
					var mootoolsLoaded = (typeof MooTools != "undefined");
					if (bootstrapLoaded && mootoolsLoaded) {
						Element.implement({
							hide: function () {
								return this;
							},
							show: function (v) {
								return this;
							},
							slide: function (v) {
								return this;
							}
						});
					}
				});
			})(jQuery);
		');

		self::$isLoadScriptDeclaration = true;
	}

	/**
	 * Method for get config variable of redshop
	 *
	 * @param   string  $name     Name of variable.
	 * @param   mixed   $default  Default data if not found.
	 *
	 * @return  mixed
	 *
	 * @since  2.0.3
	 */
	public function get($name = '', $default = null)
	{
		if (empty($this->config))
		{
			return $default;
		}

		return $this->config->get($name, $default);
	}

	/**
	 * Method for get config variable of redshop
	 *
	 * @param   string  $name   Name of variable.
	 * @param   mixed   $value  Value of configuration
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function set($name = '', $value = null)
	{
		if (empty($this->config))
		{
			return;
		}

		$this->config->set($name, $value);
	}

	/**
	 * Method for get config force boolean variable of redshop
	 *
	 * @param   string  $name     Name of variable.
	 * @param   mixed   $default  Default data if not found.
	 *
	 * @return  mixed
	 *
	 * @since  2.0.3
	 */
	public function getBool($name = '', $default = false)
	{
		if (empty($this->config))
		{
			return boolval($default);
		}

		return boolval($this->config->get($name, $default));
	}

	/**
	 * Method for return all config in array format
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	public function toArray()
	{
		if (empty($this->config))
		{
			return array();
		}

		return $this->config->toArray();
	}
}
