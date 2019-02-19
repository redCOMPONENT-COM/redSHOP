<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Environment;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Environment
 *
 * @since       2.1.0
 */
class Directory extends \JFolder
{
	/**
	 * Create a folder -- and all necessary parent folders.
	 *
	 * @param   string  $path A path to create from the base path.
	 * @param   integer $mode Directory permissions to set for folders created. 0755 by default.
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public static function create($path = '', $mode = 0755)
	{
		if (self::exists($path))
		{
			return true;
		}

		if (!parent::create($path, $mode))
		{
			return false;
		}

		if (!\JFile::exists($path . '/index.html'))
		{
			// Avoid 'pass by reference' error in J1.6+
			$content = '<html><body bgcolor="#ffffff"></body></html>';

			return \JFile::write($path . '/index.html', $content);
		}

		return true;
	}

	/**
	 * @param   string $dir Directory
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function count($dir)
	{
		$totalFile = 0;
		$totalDir  = 0;

		if (!is_dir($dir))
		{
			return array($totalFile, $totalDir);
		}

		$it = new \DirectoryIterator($dir);

		foreach ($it as $fileinfo)
		{
			if ($fileinfo->isDot())
			{
				continue;
			}

			if ($fileinfo->isFile())
			{
				$totalFile++;
			}
			elseif ($fileinfo->isDir())
			{
				$totalDir++;
			}
		}

		return array($totalFile, $totalDir);
	}
}
