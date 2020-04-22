<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopHelperText instead
 */

defined('_JEXEC') or die;

/**
 * Class Text Library
 *
 * @since  1.6.0
 *
 * @deprecated  2.0.3  Use RedshopHelperText instead
 */
class text_library
{
	/**
	 * Get data of text library
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperText::getTextLibraryData() instead
	 */
	public function getTextLibraryData()
	{
		return RedshopHelperText::getTextLibraryData();
	}

	/**
	 * Get data of text library
	 *
	 * @return array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperText::getTextLibraryTagArray() instead
	 */
	public function getTextLibraryTagArray()
	{
		return RedshopHelperText::getTextLibraryTagArray();
	}

	/**
	 * Replace data with data of text library
	 *
	 * @param   string  $data  Data to replace with
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperText::replaceTexts() instead
	 */
	public function replace_texts($data)
	{
		return RedshopHelperText::replaceTexts($data);
	}
}
