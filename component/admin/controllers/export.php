<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\File\Helper;

defined('_JEXEC') or die;

/**
 * Controller Export Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.3
 */
class RedshopControllerExport extends RedshopControllerAdmin
{
	/**
	 * Download exported file
	 *
	 * @since  2.0.7
	 */
	public function download()
	{
		$file = $this->input->getRaw('file_path');
		$file = JPath::clean($file);

		if (JFile::exists($file))
		{
			Helper::download($file);
		}
	}
}
