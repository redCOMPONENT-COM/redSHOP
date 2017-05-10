<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Install
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Update\AbstractUpdate;

/**
 * Update class
 *
 * @since  2.0.6
 */
class RedshopInstallUpdate extends AbstractUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	protected function getOldFiles()
	{
		return array();
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	protected function getOldFolders()
	{
		return array();
	}

	/**
	 * Method clean old files
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function cleanOldFiles()
	{
		$this->deleteFolders($this->getOldFolders());
		$this->deleteFiles($this->getOldFiles());
	}
}
