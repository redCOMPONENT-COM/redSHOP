<?php
/**
 * @package     Redshop\Filesystem
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Redshop\Filesystem\Folder;

/**
 * @package     Redshop\Filesystem
 *
 * @since       version
 */
class Helper
{
	/**
	 * @param $folder
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public static function create($folder)
	{
		if (!\JFolder::exists($folder))
		{
			if (!\JFolder::create($folder))
			{
				return false;
			}
		}

		if (!\JFile::exists($folder . '/index.html'))
		{
			// Create index file for basic protection
			return \JFile::write($folder . '/index.html', '<html><body bgcolor="#FFFFFF"></body></html>');
		}

		return true;
	}
}
