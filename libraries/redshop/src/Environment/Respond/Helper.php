<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

namespace Redshop\Environment\Respond;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Environment\Helper
 *
 * @since       __DEPLOY_VERSION__
 */
class Helper
{
	/**
	 * @param   string  $fileName  Filename
	 *
	 * @return  void
	 * @since   __DEPLOY_VERSION__
	 */
	public static function download($fileName)
	{
		$ext = strtolower(\JFile::getExt($fileName));

		switch ($ext)
		{
			case 'csv':
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-type: text/x-csv");
				header("Content-type: text/csv");
				header("Content-type: application/csv");
				header('Content-Disposition: attachment; filename=' . $fileName);
				break;
		}
	}
}
