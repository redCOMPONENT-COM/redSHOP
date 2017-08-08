<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\File;

class Helper
{
	/**
	 * @param   string   $filePath  File path
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function download($filePath)
	{
		if (!\JFile::exists($filePath))
		{
			return false;
		}

		$filePath = \JPath::clean($filePath);
		$fileName = basename($filePath);

		header('Content-Type: mime/type');

		header('Content-Encoding: UTF-8');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		if (\Redshop\Environment\Helper::isIe())
		{
			header('Content-Disposition: inline; filename="' . $fileName . '"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else
		{
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Pragma: no-cache');
		}

		readfile($filePath);

		\JFile::delete($filePath);
		\JFactory::getApplication()->close();
	}
}
