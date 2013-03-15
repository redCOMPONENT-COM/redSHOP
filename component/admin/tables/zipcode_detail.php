<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class Tablezipcode_detail extends JTable
{
	var $zipcode_id = null;
	var $state_code = null;
	var $city_name = null;
	var $zipcode = null;
	var $country_code = null;


	function Tablezipcode_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'zipcode', 'zipcode_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	function check()
	{

		$db = JFactory::getDBO();

		$q = "SELECT *  FROM " . $this->_table_prefix . "zipcode" . " WHERE zipcode = '" . $this->zipcode . "' AND zipcode_id !=  " . $this->zipcode_id . " AND country_code ='" . $this->country_code . "'";

		$db->setQuery($q);

		$xid = intval($db->loadResult());
		if ($xid)
		{

			$this->_error = JText::_('COM_REDSHOP_ZIPCODE_ALREADY_EXISTS') . ": " . $this->zipcode;
			JError::raiseWarning('', $this->_error);

			return false;
		}

		return true;

	}


}

