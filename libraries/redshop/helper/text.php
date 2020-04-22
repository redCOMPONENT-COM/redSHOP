<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @var  array
	 */
	protected static $data = array();

	/**
	 * Get data of text library
	 *
	 * @param   string  $section  Section of text library
	 *
	 * @return  array<object>
	 *
	 * @since   2.0.3
	 */
	public static function getTextLibraryData($section = null)
	{
		$key = null === $section ? '0' : $section;

		if (!array_key_exists($key, self::$data))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_textlibrary'))
				->where($db->qn('published') . ' = 1');

			if (null !== $section)
			{
				$query->where($db->qn('section') . ' = ' . $db->q($section));
			}

			self::$data[$key] = $db->setQuery($query)->loadObjectList();
		}

		return self::$data[$key];
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
		$result   = array();
		$textData = self::getTextLibraryData();

		if (!empty($textData))
		{
			foreach ($textData as $oneData)
			{
				$result[] = $oneData->name;
			}
		}

		return $result;
	}

	/**
	 * Replace data with data of text library
	 *
	 * @param   string  $data  Data to replace with
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function replaceTexts($data)
	{
		$textData = self::getTextLibraryData();

		if (!empty($textData))
		{
			foreach ($textData as $oneData)
			{
				$data = str_replace("{" . $oneData->name . "}", $oneData->content, $data);
			}
		}

		return $data;
	}
}
