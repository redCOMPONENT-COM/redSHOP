<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       [version> [<description>]
 */

class RedshopTableCountry extends JTable
{
	/**
	 * Function construct
	 *
	 * @param   object  &$db  Database Object
	 * 
	 * @since   1.x
	 */

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'country', 'id', $db);
	}

	/**
	 * Function bind data
	 *
	 * @param   array   $array   params in array
	 * @param   string  $ignore  flag to know that ignore or not
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Function display template
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function check()
	{
		$db = JFactory::getDbo();

		$query = "SELECT id,country_3_code  FROM " . $this->_table_prefix . "country"
			. " WHERE country_3_code = " . $db->quote($this->country_3_code)
			. " AND id !=  " . (int) $this->id;

		$db->setQuery($query);

		$xid = intval($db->loadResult());

		if ($xid)
		{
			$this->error = JText::_('COM_REDSHOP_COUNTRY_CODE_3_ALREADY_EXISTS');
			JError::raiseWarning('', $this->error);

			return false;
		}
		else
		{
			$query = "SELECT id,country_3_code,country_2_code  FROM " . $this->_table_prefix . "country"
				. " WHERE country_2_code = " . $db->quote($this->country_2_code)
				. " AND id !=  " . (int) $this->id;

			$db->setQuery($query);
			$xid = intval($db->loadResult());

			if ($xid)
			{
				$this->error = JText::_('COM_REDSHOP_COUNTRY_CODE_2_ALREADY_EXISTS');
				JError::raiseWarning('', $this->error);

				return false;
			}
		}

		return true;
	}
}
