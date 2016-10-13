<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Media
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperMedia
{
	/**
	 * Checks if the file is an image
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function isImage($fileName)
	{
		static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';

		return preg_match("/$imageTypes/i", $fileName);
	}

	/**
	 * Checks if the file is an image
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getTypeIcon($fileName)
	{
		// Get file extension
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Print size of an file to Kb or Mb
	 *
	 * @param   integer  $size  Size of file
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function parseSize($size)
	{
		if ($size < 1024)
		{
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024)
			{
				return sprintf('%01.2f', $size / 1024.0) . ' Kb';
			}

			else
			{
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
			}
		}
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
	 * @since   __DEPLOY_VERSION__
	 */
	public static function imageResize($width, $height, $target)
	{
		/**
		 * takes the larger size of the width and height and applies the
		 * formula accordingly...this is so this script will work
		 * dynamically with any size image
		 */
		if ($width > $height)
		{
			$percentage = ($target / $width);
		}

		else
		{
			$percentage = ($target / $height);
		}

		// Gets the new value and applies the percentage, then rounds the value
		$width = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	/**
	 * Checks amount of files in a directory
	 *
	 * @param   string  $dir  Directory need to be checked
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function countFiles($dir)
	{
		$total_file = 0;
		$total_dir = 0;

		if (is_dir($dir))
		{
			$d = dir($dir);

			while (false !== ($entry = $d->read()))
			{
				if (substr($entry, 0, 1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry)
					&& strpos($entry, '.html') === false && strpos($entry, '.php') === false)
				{
					$total_file++;
				}

				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry))
				{
					$total_dir++;
				}
			}

			$d->close();
		}

		return array($total_file, $total_dir);
	}
}
