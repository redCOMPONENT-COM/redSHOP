<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

/**
 * text_library
 *
 * @package     RedSHOP
 * @subpackage  Helper
 * @since       1.0
 */
class text_library
{
	public  $_data = null;
	public  $_table_prefix = null;
	public  $_db = null;

	/**
	 * __construct
	 */
	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
		$this->_db = JFactory::getDbo();
	}

	/**
	 * getTextLibraryData
	 */
	public function getTextLibraryData()
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "textlibrary "
			. "WHERE published=1 ";
		$this->_db->setQuery($query);
		$textdata = $this->_db->loadObjectlist();

		return $textdata;
	}

	/**
	 * getTextLibraryTagArray
	 */
	public function getTextLibraryTagArray()
	{
		$result = array();
		$textdata = $this->getTextLibraryData();

		for ($i = 0; $i < count($textdata); $i++)
		{
			$result[] = $textdata[$i]->text_name;
		}

		return $result;
	}

	/**
	 * replace_texts
	 *
	 * @param $data
	 *
	 */
	public function replace_texts($data)
	{
		$textdata = $this->getTextLibraryData();

		for ($i = 0; $i < count($textdata); $i++)
		{
			$textname = "{" . $textdata[$i]->text_name . "}";
			$textreplace = $textdata[$i]->text_field;
			$data = str_replace($textname, $textreplace, $data);
		}

		return $data;
	}
}
