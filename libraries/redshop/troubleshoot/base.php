<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Troubleshoot item
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Troubleshoot base class
 *
 * @package     Redshop.Library
 * @subpackage  Troubleshoot
 *
 * @since       2.1
 */
abstract class RedshopTroubleshootBase
{
	/**
	 * @var    JObject
	 *
	 * @since  2.0.6
	 */
	protected $inputFile;

	/**
	 * RedshopTroubleshootBase constructor.
	 *
	 * @param   object $inputFile File from json
	 *
	 * @since  2.0.6
	 */
	public function __construct($inputFile)
	{
		$this->inputFile = new JObject ($inputFile);

		$this->init();
	}

	/**
	 *
	 *
	 * @since  2.0.6
	 */
	protected function init()
	{
		// Init default variables
		$this->inputFile->def('type', array());
		// List of override directories
		$this->inputFile->def('overrides', array());
		// Flag detect override
		$this->inputFile->def('isOverrided', false);
		// Flag detect hacked
		$this->inputFile->def('isHacked', false);
		// Flag detect missed
		$this->inputFile->def('isMissed', false);
		// Original data
		$this->inputFile->def('original', array('filename' => basename($this->inputFile->path)));
	}

	/**
	 * @param   string $type Type
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	protected function is($type)
	{
		if (strpos($this->inputFile->path, $type) !== false)
		{
			return true;
		}

		return false;
	}

	/**
	 * @param    $id
	 *
	 * @return   string
	 *
	 * @since    2.0.6
	 */
	protected function getJTemplateDir($id)
	{
		if ($id == 'admin')
		{
			$app      = JFactory::getApplication('admin');
			$template = $app->getTemplate();

			return JPATH_ADMINISTRATOR . '/templates/' . $template;
		}
		else
		{
			$app      = JFactory::getApplication('site');
			$template = $app->getTemplate();

			return JPATH_ROOT . '/templates/' . $template;
		}
	}

	/**
	 *
	 * @return  bool|string
	 *
	 * @since   2.0.6
	 */
	protected function getTmpl()
	{
		// Detect view
		$paths = explode('/', $this->getOriginal('fullpath'));
		$index = array_search('tmpl', $paths);

		if ($index !== false)
		{
			return $paths[$index - 1];
		}

		return false;
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getPath()
	{
		return trim($this->inputFile->get('path'));
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getName()
	{
		return trim($this->inputFile->get('name'));
	}

	/**
	 * @param   string  $name
	 *
	 * @since   2.0.6
	 */
	public function getOriginal($name)
	{
		if (isset($this->inputFile->original[$name]))
		{
			return $this->inputFile->original[$name];
		}

		return;
	}

	/**
	 * @param   string  $name
	 * @param   mixed   $value
	 *
	 * @since   2.0.6
	 */
	public function setOriginal($name, $value)
	{
		$this->inputFile->original[$name] = $value;
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getExtension()
	{
		return $this->inputFile->get('extension', false);
	}

	/**
	 * Check if extension ( module or plugin ) is installed
	 *
	 * @return  bool|void
	 *
	 * @since   2.0.6
	 */
	public function isInstalled()
	{
		if ($this->inputFile->extension == 'plugin')
		{
			return JPluginHelper::isEnabled($this->getOriginal('type'), $this->getOriginal('plugin'));
		}

		if ($this->inputFile->extension == 'module')
		{
			return JModuleHelper::isEnabled($this->getName());
		}

		return;
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getType()
	{
		return trim(implode('.', is_array($this->inputFile->type) ? $this->inputFile->type : array()));
	}
}