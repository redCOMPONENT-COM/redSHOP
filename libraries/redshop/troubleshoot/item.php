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
class RedshopTroubleshootItem extends RedshopTroubleshootOverrides
{
	/**
	 * Init for item
	 *
	 * @since   2.0.6
	 */
	protected function init()
	{
		parent::init();

		if ($this->getOriginal('filename') === 'index.html')
		{
			$this->inputFile->type[] = 'index';
		}
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public function isHacked()
	{
		// If this is plugin or module we will need if it's installed
		if ($this->getExtension() == 'plugin' || $this->getExtension() == 'module')
		{
			if ($this->isInstalled())
			{
				// There is no overrided than compare with it self
				if (!$this->isOverrided())
				{
					if ($this->isMissed() === false)
					{
						$this->inputFile->isHacked = $this->inputFile->md5 != md5_file($this->getOriginal('fullpath'));
					}
				}
			}
		}
		else
		{
			// There is no overrided than compare with it self
			if (!$this->isOverrided())
			{
				if ($this->isMissed() === false)
				{
					$this->inputFile->isHacked = $this->inputFile->md5 != md5_file($this->getOriginal('fullpath'));
				}
			}
		}

		return (bool) $this->inputFile->isHacked;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public function isOverrided()
	{
		return (bool) $this->inputFile->isOverrided;
	}

	/**
	 * Check if core file is missed
	 *
	 * @return  bool
	 *
	 * @since   2.0.6
	 */
	public function isMissed()
	{
		if ($this->getExtension() == 'plugin' || $this->getExtension() == 'module')
		{
			if ($this->isInstalled())
			{
				$this->inputFile->isMissed = !JFile::exists($this->getOriginal('fullpath'));
			}
		}

		$this->inputFile->isMissed = !JFile::exists($this->getOriginal('fullpath'));

		return (bool) $this->inputFile->isMissed;
	}

	/**
	 * @param   int $index
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function render($index)
	{
		// Create a layout object and ask it to render the sidebar
		$layout = new JLayoutFile('troubleshoots.item', $basePath = JPATH_ADMINISTRATOR . '/components/com_redshop/layouts');

		return $layout->render(array('item' => $this, 'index' => $index));
	}
}
