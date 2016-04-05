<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

class text_library
{
	public function getTextLibraryData()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_textlibrary'))
			->where('published = 1');

		return $db->setQuery($query)->loadObjectlist();
	}

	public function getTextLibraryTagArray()
	{
		$result = array();

		if ($textData = $this->getTextLibraryData())
		{
			foreach ($textData as $oneData)
			{
				$result[] = $oneData->text_name;
			}
		}

		return $result;
	}

	public function replace_texts($data)
	{
		if ($textData = $this->getTextLibraryData())
		{
			foreach ($textData as $oneData)
			{
				$data = str_replace("{" . $oneData->text_name . "}", $oneData->text_field, $data);
			}
		}

		return $data;
	}
}
