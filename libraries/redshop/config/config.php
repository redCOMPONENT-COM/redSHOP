<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Redshop Configuration
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopConfig
{
	/**
	 * javascript strings for configuration variables
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected static $jsStrings = array();

	private static $isLoad = false;

	/**
	 * Configuration
	 *
	 * @var  stdClass
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		$this->loadConfig();
	}

	/**
	 * Magic method to transparently use registry methods on config
	 *
	 * @param   string  $name       Name of the function.
	 * @param   array   $arguments  [0] The name of the variable [1] The default value.
	 *
	 * @return  mixed   The filtered input value.
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this->config, $name))
		{
			return call_user_func_array(array($this->config, $name), $arguments);
		}

		trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
	}

	/**
	 * Get the path to this station configuration file
	 *
	 * @return  string
	 */
	protected function getConfigurationFilePath()
	{
		return JPATH_SITE . '/redshop/conf/config.conf';
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @return  self
	 */
	public function loadConfig()
	{
		$this->config = new JRegistry;

		if (!file_exists($this->getConfigurationFilePath()))
		{
			return $this;
		}

		$this->config->loadFile($this->getConfigurationFilePath(), 'INI');

		return $this;
	}

	/**
	 * Save configuration to file
	 *
	 * @param   mixed  $config  Null to avoid binding any data | JRegistry to binf config and save
	 *
	 * @return  boolean
	 */
	public function save($config = null)
	{
		if ($config instanceof JRegistry || $config instanceof \Joomla\Registry\Registry)
		{
			$this->config->merge($config);
		}

		$content      = $this->config->toString('ini');
		$configFolder = dirname($this->getConfigurationFilePath());

		if (!is_dir($configFolder) && !mkdir($configFolder, 0755, true))
		{
			throw new Exception('Unable to create configuration folder');
		}

		$file = fopen($this->getConfigurationFilePath(), 'w');

		if ($file === false)
		{
			throw new Exception('Unable to save station configuration');
		}

		$result = fwrite($file, $content);

		if ($result === false)
		{
			throw new Exception('Unable to save station configuration');
		}

		fclose($file);

		return true;
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
		if (self::$isLoad)
		{
			return;
		}

		// Load redshop script
		JHtml::script('com_redshop/redshop.js', false, true);

		JFactory::getDocument()->addScriptDeclaration('
			(function() {
				var RedshopStrings = ' . json_encode(self::script()) . ';
				if (typeof redSHOP == "undefined") {
					redSHOP = {};
					redSHOP.RSConfig.strings = RedshopStrings;
				}
				else {
					redSHOP.RSConfig.load(RedshopStrings);
				}
			})();
		');
		self::$isLoad = true;
	}
}
