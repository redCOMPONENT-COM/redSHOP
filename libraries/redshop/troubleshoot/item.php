<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Troubleshoot item
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Troubleshoot item class
 *
 * @package     Redshop.Library
 * @subpackage  Troubleshoot
 * @since       2.0.6
 */
class RedshopTroubleshootItem
{
	/**
	 * @var   object
	 * @since 2.0.6
	 */
	public $originalFile = null;

	/**
	 * @var   array
	 * @since 2.0.6
	 */
	public $overrideDirs = null;

	/**
	 * @var   boolean
	 * @since 2.0.6
	 */
	private $isOverride = false;

	/**
	 * @var   boolean
	 * @since 2.0.6
	 */
	private $isModified = false;

	/**
	 * @var   boolean
	 * @since 2.0.6
	 */
	private $isMissing = false;

	/**
	 * RedshopTroubleshootItem constructor.
	 *
	 * @param   object $originalFile Object file from json
	 * @param   array  $overrideDirs Array of override-able directories
	 */
	public function __construct($originalFile, $overrideDirs)
	{
		$this->originalFile = $originalFile;
		$this->overrideDirs = $overrideDirs;

		$this->init();
	}

	/**
	 * Init function
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function init()
	{
		// Format input file
		$this->originalFile = $this->formatOriginalFile($this->originalFile);

		// Check overrides
		foreach ($this->overrideDirs as $index => $overrideDir)
		{
			$overridePath = $overrideDir . $this->originalFile->trim;

			// Store override files
			if (JFile::exists($overridePath))
			{
				$this->setOverrided(true);
				$overridePaths['overrides'][$index] = $overridePath;
			}
		}

		// Check modified
		if (JFile::exists($this->originalFile->original))
		{
			$md5 = md5_file($this->originalFile->original);

			if ($md5 != $this->originalFile->md5)
			{
				$this->setModified(true);
			}
		}

		// Check if original file is missing
		$this->setMissing(!JFile::exists($this->getOriginalFile(false)));

	}

	/**
	 * Get original file path
	 *
	 * @param   bool $clean Clean output file
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getOriginalFile($clean = true)
	{
		if ($clean)
		{
			return trim(str_replace(JPATH_ROOT, '/', $this->originalFile->original), '/');
		}

		return $this->originalFile->original;
	}

	/**
	 * Set modified value
	 *
	 * @param   bool $value isModified
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function setModified($value)
	{
		$this->isModified = $value;
	}

	/**
	 * Get isModified
	 *
	 * @return  bool
	 *
	 * @since   2.0.6
	 */
	public function isModified()
	{
		return $this->isModified;
	}

	/**
	 * Set overrided value
	 *
	 * @param   bool $value isOverrided
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function setOverrided($value)
	{
		$this->isOverride = $value;
	}

	/**
	 * Get isOverrided
	 *
	 * @return  bool
	 *
	 * @since   2.0.6
	 */
	public function isOverrided()
	{
		return $this->isOverride;
	}

	/**
	 * Set missing value
	 *
	 * @param   bool $value isMissing
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function setMissing($value)
	{
		$this->isMissing = $value;
	}

	/**
	 * Get isMissing
	 *
	 * @return  bool
	 *
	 * @since   2.0.6
	 */
	public function isMissing()
	{
		return $this->isMissing;
	}

	/**
	 * Format original file
	 *
	 * @param   string $originalFile Original file from json
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	private function formatOriginalFile($originalFile)
	{
		$return = $originalFile;

		// Administrator file
		if (strpos($originalFile->path, 'component/admin/') !== false)
		{
			$return->original = str_replace('component/admin/', JPATH_ADMINISTRATOR . '/components/com_redshop/', $originalFile->path);
		}

		// Frontend file
		if (strpos($originalFile->path, 'component/site/') !== false)
		{
			$return->original = str_replace('component/site/', JPATH_ROOT . '/components/com_redshop/', $originalFile->path);
		}

		// Frontend file
		if (strpos($originalFile->path, 'modules/site/') !== false)
		{
			$return->original = str_replace('modules/site/', JPATH_ROOT . '/modules/', $originalFile->path);
		}
		else
		{
			// Another file
			if (strpos($originalFile->path, 'component/admin') === false && strpos($originalFile->path, 'component/site') === false)
			{
				// Administrator
				if (strpos($originalFile->path, '/') === false)
				{
					$return->original = JPATH_ADMINISTRATOR . '/components/com_redshop/' . $originalFile->path;
				}
				else
				{
					$return->original = JPATH_ROOT . '/' . $originalFile->path;
				}
			}
		}

		// Get trimmed file
		$originalFile = str_replace('component/admin/', '', $originalFile->path);
		$originalFile = str_replace('component/site/', '', $originalFile);
		$originalFile = str_replace('modules/site/', '', $originalFile);
		$return->trim = '/' . $originalFile;

		// Clean up
		$return->trim     = str_replace('//', '/', $return->trim);
		$return->original = str_replace('//', '/', $return->original);

		return $return;
	}

	/**
	 * Get formatted modified datetime
	 *
	 * @return  false|string
	 *
	 * @since   2.0.6
	 */
	public function getModifiedTime()
	{
		return date('Y-m-d H:i:s', filemtime($this->getOriginalFile(false)));
	}

	/**
	 * Get missing tr class
	 *
	 * @return   string
	 *
	 * @since    2.0.6
	 */
	public function getMissingClass()
	{
		if ($this->isMissing())
		{
			return 'danger';
		}

		return '';
	}

	/**
	 * Return HTML to render check mark
	 *
	 * @return string
	 *
	 * @since  2.0.6
	 */
	public function renderCheckMark()
	{
		return '<span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i></span>';
	}
}