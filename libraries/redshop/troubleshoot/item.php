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
 * Troubleshoot item class
 *
 * @package     Redshop.Library
 * @subpackage  Troubleshoot
 * @since       2.1
 */
class RedshopTroubleshootItem
{
	/**
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	private $inputFile;

	/**
	 * RedshopTroubleshootItem constructor.
	 *
	 * @param   object $inputFile Object file
	 *
	 * @since  2.0.6
	 */
	public function __construct($inputFile)
	{
		$this->inputFile = new JObject ($inputFile);

		// Init default variables
		$this->inputFile->def('type', array());
		$this->inputFile->def('overrides', array());
		$this->inputFile->def('isOverrided', false);
		$this->inputFile->def('isHacked', false);
		$this->inputFile->def('original', array('filename' => basename($this->inputFile->path)));

		$this->formatOriginalFile();
	}

	/**
	 * @param   string $type Type
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function is($type)
	{
		if (strpos($this->inputFile->path, '/' . $type . '/') !== false)
		{
			return true;
		}

		return false;
	}


	public function getPath()
	{
		return trim($this->inputFile->path);
	}

	/**
	 * @param $name
	 *
	 *
	 * @since version
	 */
	public function getOriginal($name)
	{
		if (isset($this->inputFile->original[$name]))
		{
			return $this->inputFile->original[$name];
		}

		return;
	}

	public function setOriginal($name, $value)
	{
		$this->inputFile->original[$name] = $value;
	}

	/**
	 * Format original file
	 *
	 * @return  void
	 *
	 * @since   2.1
	 */
	public function formatOriginalFile()
	{
		if (!$this->checkAdminComponentPath())
		{
			if (!$this->checkFrontendComponentPath())
			{
				if (!$this->checkFrontendModulePath())
				{
					if (!$this->checkFrontendPluginPath())
					{
						if (!$this->checkFrontendLibrariesPath())
						{
							if (!$this->checkFrontendMediaPath())
							{
								// Something else
								// Administrator
								if (strpos($this->inputFile->path, '/') === false)
								{
									$this->inputFile->original['fullpath'] = JPATH_ADMINISTRATOR . '/components/com_redshop/' . $this->inputFile->path;
									$this->inputFile->isAdmin              = true;
									$this->inputFile->type[]               = 'misc';
								}
								else
								{
									$this->inputFile->original['fullpath'] = JPATH_ROOT . '/' . $this->inputFile->path;
									$this->inputFile->type[]               = 'misc';
								}
							}
						}
					}
				}
			}
		}

		$this->inputFile->isMissed = !JFile::exists($this->inputFile->original['fullpath']);
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function checkAdminComponentPath()
	{
		// Administrator file
		if (strpos($this->inputFile->path, 'component/admin/') !== false)
		{
			// Generate original physical file path
			$this->setOriginal('fullpath', str_replace('component/admin/', JPATH_ADMINISTRATOR . '/components/com_redshop/', $this->inputFile->path));
			// Generate relative physical file path
			$this->setOriginal('relativepath', str_replace('component/admin/', '', $this->inputFile->path));
			// Is this Administrator file
			$this->inputFile->isAdmin = true;
			//
			$this->inputFile->type[] = 'admin';
			$this->inputFile->type[] = 'component';

			$this->processAdminComponent();

			return true;
		}

		return false;
	}

	/**
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	private function processAdminComponent()
	{
		// This file already detected as admin component
		$app         = JFactory::getApplication('admin');
		$template    = $app->getTemplate();
		$templateDir = JPATH_ADMINISTRATOR . '/templates/' . $template;
		// @TODO Get MVC Overrides from plugin params

		// Assets
		if ($this->is('assets'))
		{
			// MVC override
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/code/com_redshop/assets']                             = false;
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/templates/' . $template . '/code/com_redshop/assets'] = false;
			// Adding possible override directories here
			$this->inputFile->type[] = 'assets';
		}
		// Model
		elseif ($this->is('models'))
		{
			// MVC override
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/code/com_redshop/models']                             = false;
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/templates/' . $template . '/code/com_redshop/models'] = false;
			// Adding possible override directories here
			// @TODO Get MVC Overrides from plugin params
			$this->inputFile->type[] = 'models';
		}
		// View
		elseif ($this->is('views'))
		{

			// Detect view
			$paths = explode('/', $this->getOriginal('fullpath'));
			$index = array_search('views', $paths);
			if ($index !== false)
			{
				$view                                                   = $paths[$index + 1];
				$this->inputFile->overrides[$templateDir . '/' . $view] = false;
			}
			$this->inputFile->type[] = 'views';
		}
		// Controller
		elseif ($this->is('controllers'))
		{
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/code/com_redshop/controllers']                             = false;
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/templates/' . $template . '/code/com_redshop/controllers'] = false;
			$this->inputFile->type[]                                                                                       = 'controllers';
		}
		// Helper
		elseif ($this->is('helpers'))
		{
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/code/com_redshop/helpers']                             = false;
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/templates/' . $template . '/code/com_redshop/helpers'] = false;
			$this->inputFile->type[]                                                                                   = 'helpers';
		}
		// Layout
		elseif ($this->is('layouts'))
		{
			$this->inputFile->overrides[$templateDir . '/html/layouts'] = false;
			$this->inputFile->type[]                                    = 'layouts';
		}
		else
		{
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/code/com_redshop']                             = false;
			$this->inputFile->overrides[JPATH_ADMINISTRATOR . '/templates/' . $template . '/code/com_redshop'] = false;
		}

		$this->processOverrides();
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function checkFrontendComponentPath()
	{
		// Frontend component
		if (strpos($this->inputFile->path, 'component/site/') !== false)
		{
			$this->setOriginal('fullpath', str_replace('component/site/', JPATH_ROOT . '/components/com_redshop/', $this->inputFile->path));
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'component';

			return true;
		}

		return false;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function checkFrontendModulePath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'modules/site/') !== false)
		{
			$this->setOriginal('fullpath', str_replace('modules/site/', JPATH_ROOT . '/modules/', $this->inputFile->path));
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'module';

			return true;
		}

		return false;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function checkFrontendPluginPath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'plugins/') !== false)
		{
			$this->setOriginal('fullpath', str_replace('plugins/', JPATH_ROOT . '/plugins/', $this->inputFile->path));
			$this->inputFile->isAdmin = false;
			$this->inputFile->type    = 'plugin';

			return true;
		}

		return false;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function checkFrontendLibrariesPath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'libraries/') !== false)
		{
			$this->setOriginal('fullpath', str_replace('libraries/', JPATH_ROOT . '/libraries/', $this->inputFile->path));
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'libraries';

			return true;
		}

		return false;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	private function checkFrontendMediaPath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'media/') !== false)
		{
			$this->setOriginal('fullpath', str_replace('media/', JPATH_ROOT . '/media/', $this->inputFile->path));
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'media';

			return true;
		}

		return false;
	}

	private function processOverrides()
	{
		if (is_array($this->inputFile->overrides))
		{
			foreach ($this->inputFile->overrides as $overrideDir => $value)
			{
				if (JFile::exists($overrideDir . '/' . $this->inputFile->original['relativepath']))
				{
					$this->inputFile->overrides[$overrideDir]['filepath'] = $overrideDir . '/' . $this->inputFile->original['relativepath'];
					$this->inputFile->overrides[$overrideDir]['match']    = $this->inputFile->md5 == md5_file($this->inputFile->overrides[$overrideDir]['filepath']);
					$this->inputFile->isOverrided                         = true;
				}
			}
		}
	}

	public function getModifiedTime()
	{
		if ($this->inputFile->isMissed === false)
		{
			return date("F d Y H:i:s.", filemtime($this->getOriginal('fullpath')));
		}
	}

	public function getType()
	{
		return trim(implode('.', is_array($this->inputFile->type) ? $this->inputFile->type : array()));
	}

	public function isHacked()
	{
		if (!$this->inputFile->isOverrided)
		{
			// There is no overrided than compare with it self
			if ($this->inputFile->isMissed === false)
			{
				$this->inputFile->isHacked     = $this->inputFile->md5 == md5_file($this->inputFile->original['fullpath']);
				$this->inputFile->isCoreHacked = $this->inputFile->isHacked;
			}
		}

		return $this->inputFile->isHacked;
	}

	public function isOverrided()
	{
		return $this->inputFile->isOverrided;
	}

	public function isMissing()
	{
		return $this->inputFile->isMissed;
	}
}
