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
 * Troubleshoot overrides class
 *
 * @package     Redshop.Library
 * @subpackage  Troubleshoot
 * @since       2.1
 */
class RedshopTroubleshootOverrides extends RedshopTroubleshootBase
{
	/**
	 * Format original file
	 *
	 * @return  void
	 *
	 * @since   2.1
	 */
	protected function init()
	{
		parent::init();

		// If this's not admin component
		if (!$this->checkAdminComponentPath())
		{
			// If this's not site component
			if (!$this->checkSiteComponentPath())
			{
				// If this's not frontend module
				if (!$this->checkSiteModulePath())
				{
					// If this's not site plugin
					if (!$this->checkSitePluginPath())
					{
						// If this's not libraries
						if (!$this->checkSiteLibrariesPath())
						{
							// If this's not media
							if (!$this->checkSiteMediaPath())
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

		$this->processOverrides();
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	protected function checkAdminComponentPath()
	{
		// Administrator file
		if ($this->is('component/admin/') !== false)
		{
			// Is this Administrator file
			$this->inputFile->isAdmin = true;
			$this->inputFile->type[]  = 'admin';
			$this->inputFile->type[]  = 'component';

			$this->inputFile->extension = 'component';

			// Generate original physical file path
			$this->setOriginal('fullpath', str_replace('component/admin/', JPATH_ADMINISTRATOR . '/components/com_redshop/', $this->inputFile->path));
			// Generate relative physical file path
			$this->setOriginal('relativepath', str_replace('component/admin/', '', $this->inputFile->path));

			// This file already detected as admin component
			$templateDir = $this->getJTemplateDir('admin');

			// Assets
			if ($this->is('/assets/'))
			{
				$this->inputFile->type[] = 'assets';

				// Adding possible override directories here
			}
			// Model
			elseif ($this->is('/models/'))
			{
				$this->inputFile->type[] = 'models';

				// Adding possible override directories here
			}
			// View
			elseif ($this->is('/views/'))
			{
				$this->inputFile->type[] = 'views';

				// Detect tmpl file and get view
				$tmpl = $this->getTmpl();

				if ($tmpl !== false)
				{
					// Template override
					$this->inputFile->overrides[$templateDir . '/html/com_redshop/' . $tmpl] = false;
					$this->inputFile->type[]                                                 = 'tmpl';
				}

				// Adding possible override directories here
			}
			// Controller
			elseif ($this->is('/controllers/'))
			{
				$this->inputFile->type[] = 'controllers';
			}
			// Helper
			elseif ($this->is('/helpers/'))
			{
				$this->inputFile->type[] = 'helpers';
			}
			// Language
			elseif ($this->is('/language/'))
			{
				// @TODO sh404sef/language exception
				// @TODO en-GB/index.html  exception
				$this->setOriginal('fullpath', str_replace('component/admin/', JPATH_ADMINISTRATOR . '/', $this->inputFile->path));

				// @TODO Language override
				$this->inputFile->type[] = 'language';
			}
			// Layout
			elseif ($this->is('/layouts/'))
			{
				// Template override
				$this->inputFile->overrides[$templateDir . '/html/layouts'] = false;
				$this->inputFile->type[]                                    = 'layouts';
			}

			// MVC override
			$this->inputFile->overrides = array_merge($this->inputFile->overrides, $this->getAdminMvcOverrideDirs());

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
	protected function checkSiteComponentPath()
	{
		// Administrator file
		if ($this->is('component/site/') !== false)
		{
			// Is this Administrator file
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'site';
			$this->inputFile->type[]  = 'component';

			// Component file
			$this->inputFile->extension = 'component';

			// Generate original physical file path
			$this->setOriginal('fullpath', str_replace('component/site/', JPATH_ROOT . '/components/com_redshop/', $this->inputFile->path));
			// Generate relative physical file path
			$this->setOriginal('relativepath', str_replace('component/site/', '', $this->inputFile->path));

			// This file already detected as site component
			$templateDir = $this->getJTemplateDir('site');

			// Assets
			if ($this->is('/assets/'))
			{
				$this->inputFile->type[] = 'assets';

				// Adding possible override directories here
			}
			// Model
			elseif ($this->is('/models/'))
			{
				$this->inputFile->type[] = 'models';

				// Adding possible override directories here
			}
			// View
			elseif ($this->is('/views/'))
			{
				$this->inputFile->type[] = 'views';

				// Detect tmpl file and get view
				$tmpl = $this->getTmpl();

				if ($tmpl !== false)
				{
					// Template override
					$this->inputFile->overrides[$templateDir . '/html/com_redshop/' . $tmpl] = false;
					$this->inputFile->type[]                                                 = 'tmpl';
				}
			}
			// Controller
			elseif ($this->is('/controllers/'))
			{
				$this->inputFile->type[] = 'controllers';
			}
			// Helper
			elseif ($this->is('/helpers/'))
			{
				// MVC override
				$this->inputFile->overrides = $this->getSiteMvcOverrideDirs();
				$this->inputFile->type[]    = 'helpers';
			}
			// Language
			elseif ($this->is('/language/'))
			{
				// @TODO sh404sef/language exception
				// @TODO en-GB/index.html  exception
				$this->setOriginal('fullpath', str_replace('component/site/', JPATH_ADMINISTRATOR . '/', $this->inputFile->path));

				// @TODO Language override
				$this->inputFile->type[] = 'language';
			}
			// Layout
			elseif ($this->is('/layouts/'))
			{
				$this->inputFile->type[] = 'layouts';
			}

			$this->inputFile->overrides = array_merge($this->inputFile->overrides, $this->getSiteMvcOverrideDirs());

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
	protected function checkSiteModulePath()
	{
		// Frontend file
		if ($this->is('modules/site/') !== false)
		{
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'site';
			$this->inputFile->type[]  = 'module';

			$this->inputFile->extension = 'module';

			// Generate relative physical file path
			$this->setOriginal('fullpath', str_replace('modules/site/', JPATH_ROOT . '/modules/', $this->inputFile->path));
			$this->setOriginal('relativepath', str_replace('modules/site/', '', $this->inputFile->path));

			// Language file
			if ($this->is('/language/'))
			{
				// Frontend module language stored inside module folder
				$this->setOriginal('fullpath', JPATH_ROOT . '/modules/' . $this->getOriginal('relativepath'));

				// @TODO Language override
				$this->inputFile->type[] = 'language';
			}
			else
			{
				// This file already detected as frontend file
				$templateDir = $this->getJTemplateDir('site');

				// Detect tmpl
				$tmpl = $this->getTmpl();

				if ($tmpl !== false)
				{
					// Template override
					$this->inputFile->overrides[$templateDir . '/html/' . $tmpl] = false;
					$this->inputFile->type[]                                     = 'tmpl';
					$this->inputFile->name                                       = $tmpl;
				}
			}

			$this->inputFile->overrides = array_merge($this->inputFile->overrides, $this->getSiteMvcOverrideDirs());

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
	protected function checkSitePluginPath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'plugins/') !== false)
		{
			$this->inputFile->isAdmin   = false;
			$this->inputFile->type[]    = 'plugin';
			$this->inputFile->extension = 'plugin';

			$this->setOriginal('fullpath', JPATH_ROOT . '/' . $this->inputFile->path);

			// Try to get plugin manifest
			// Detect view
			$paths = explode('/', $this->getOriginal('fullpath'));
			$index = array_search('plugins', $paths);
			if ($index !== false)
			{
				$this->setOriginal('type', $paths[$index + 1]);
				$this->setOriginal('plugin', $paths[$index + 2]);

				$this->inputFile->name = $paths[$index + 2];
			}

			// Module language
			if ($this->is('/language/'))
			{
				// RedShop store language directly inside extension folder
				$this->setOriginal('relativepath', $this->inputFile->path);
				$this->inputFile->type[] = 'language';
			}

			// Are we crazy enough to do override plugin ?

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
	protected function checkSiteLibrariesPath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'libraries/') !== false)
		{
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'site';
			$this->inputFile->type[]  = 'libraries';

			$this->setOriginal('fullpath', str_replace('libraries/', JPATH_ROOT . '/libraries/', $this->inputFile->path));

			// Are we crazy enough to do override plugin ?

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
	protected function checkSiteMediaPath()
	{
		// Frontend file
		if (strpos($this->inputFile->path, 'media/') !== false)
		{
			$this->inputFile->isAdmin = false;
			$this->inputFile->type[]  = 'site';
			$this->inputFile->type[]  = 'media';

			$this->setOriginal('fullpath', str_replace('media/', JPATH_ROOT . '/media/', $this->inputFile->path));

			return true;
		}

		return false;
	}

	protected function getAdminMvcOverrideDirs()
	{
		// @TODO Get MVC Overrides from plugin params
		return array(
			JPATH_ADMINISTRATOR . '/code/com_redshop'             => '',
			$this->getJTemplateDir('admin') . '/code/com_redshop' => ''
		);
	}

	protected function getSiteMvcOverrideDirs()
	{
		// @TODO Get MVC Overrides from plugin params
		return array(
			JPATH_ROOT . '/code/com_redshop'                     => '',
			$this->getJTemplateDir('site') . '/code/com_redshop' => ''
		);
	}

	protected function processOverrides()
	{

		foreach ($this->inputFile->overrides as $overrideDir => $value)
		{
			// Found override
			if (JFile::exists($overrideDir . '/' . $this->getOriginal('relativepath')))
			{
				// Add full path of overrided file
				$this->inputFile->overrides[$overrideDir]['filepath'] = $overrideDir . '/' . $this->inputFile->original['relativepath'];
				// Add compare checksum
				$this->inputFile->overrides[$overrideDir]['match'] = $this->inputFile->md5 == md5_file($this->inputFile->overrides[$overrideDir]['filepath']);
				// Turn on override flag
				$this->inputFile->isOverrided = true;
			}
		}

	}
}