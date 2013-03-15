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


class Tablecurrency_detail extends JTable
{
	var $currency_id = null;
	var $currency_name = null;
	var $currency_code = null;


	function Tablecurrency_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'currency', 'currency_id', $db);
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

		$q = "SELECT currency_id,currency_code  FROM " . $this->_table_prefix . "currency" . " WHERE currency_code = '" . $this->currency_code . "' AND currency_id !=  " . $this->currency_id;

		$db->setQuery($q);

		$xid = intval($db->loadResult());
		if ($xid)
		{

			$this->_error = JText::_('COM_REDSHOP_CURRENCY_CODE_ALREADY_EXISTS');
			JError::raiseWarning('', $this->_error);

			return false;
		}

		return true;

	}


}
?>

