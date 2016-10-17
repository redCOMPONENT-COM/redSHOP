<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperMedia instead
 */

defined('_JEXEC') or die;

/**
 * Class redMedia Helper
 *
 * @deprecated  __DEPLOY_VERSION__
 */
class redMediahelper
{
	/**
	 * Checks if the file is an image
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperMedia::isImage() instead
	 */
	public function isImage($fileName)
	{
		return RedshopHelperMedia::isImage($fileName);
	}

	/**
	 * Checks if the file is an image
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperMedia::getTypeIcon() instead
	 */
	public function getTypeIcon($fileName)
	{
		return RedshopHelperMedia::getTypeIcon($fileName);
	}

	/**
	 * Print size of an file to Kb or Mb
	 *
	 * @param   integer  $size  Size of file
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperMedia::parseSize() instead
	 */
	public function parseSize($size)
	{
		return RedshopHelperMedia::parseSize($size);
	}

	/**
	 * Resize current resolution of an image to new width and height
	 *
	 * @param   integer  $width   New width in pixel
	 * @param   integer  $height  New height in pixel
	 * @param   integer  $target  Current resolution
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperMedia::imageResize() instead
	 */
	public function imageResize($width, $height, $target)
	{
		return RedshopHelperMedia::imageResize($width, $height, $target);
	}

	/**
	 * Checks amount of files in a directory
	 *
	 * @param   string  $dir  Directory need to be checked
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperMedia::countFiles() instead
	 */
	public function countFiles($dir)
	{
		return RedshopHelperMedia::countFiles($dir);
	}
}
