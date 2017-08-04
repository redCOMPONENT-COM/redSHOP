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

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Text
 *
 * @since  2.0.3
 */
class RedshopHelperText
{
	/**
	 * Get data of text library
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getTextLibraryData()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_textlibrary'))
			->where($db->qn('published') . ' = 1');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get array of tag for text library
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getTextLibraryTagArray()
	{
		$result = array();

		if ($textData = self::getTextLibraryData())
		{
			foreach ($textData as $oneData)
			{
				$result[] = $oneData->text_name;
			}
		}

		return $result;
	}

	/**
	 * Replace data with data of text library
	 *
	 * @param   array  $data  Data to replace with
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function replaceTexts($data)
	{
		if ($textData = self::getTextLibraryData())
		{
			foreach ($textData as $oneData)
			{
				$data = str_replace("{" . $oneData->text_name . "}", $oneData->text_field, $data);
			}
		}

		return $data;
	}
}
