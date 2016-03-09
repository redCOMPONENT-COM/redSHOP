<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

class text_library
{
	public function getTextLibraryData()
	{
		$db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_textlibrary "
			. "WHERE published=1 ";
		$db->setQuery($query);
		$textdata = $db->loadObjectlist();

		return $textdata;
	}

	public function getTextLibraryTagArray()
	{
		$result = array();
		$textdata = $this->getTextLibraryData();

		for ($i = 0, $in = count($textdata); $i < $in; $i++)
		{
			$result[] = $textdata[$i]->text_name;
		}

		return $result;
	}

	public function replace_texts($data)
	{
		$textdata = $this->getTextLibraryData();

		for ($i = 0, $in = count($textdata); $i < $in; $i++)
		{
			$textname = "{" . $textdata[$i]->text_name . "}";
			$textreplace = $textdata[$i]->text_field;
			$data = str_replace($textname, $textreplace, $data);
		}

		return $data;
	}
}
