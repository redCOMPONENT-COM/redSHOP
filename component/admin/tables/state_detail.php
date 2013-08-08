<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablestate_detail extends JTable
{
	public $state_id = null;

	public $state_name = null;

	public $state_3_code = null;

	public $state_2_code = null;

	public $show_state = 2;

	public $country_id = null;

	/**
	 * @var boolean
	 */
	public $checked_out = 0;

	/**
	 * @var time
	 */
	public $checked_out_time = 0;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'state', 'state_id', $db);
	}

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

	public function check()
	{
		$db = JFactory::getDBO();

		$q = "SELECT state_id,state_3_code  FROM " . $this->_table_prefix . "state"
			. " WHERE state_3_code = '" . $this->state_3_code
			. "' AND state_id !=  " . (int) $this->state_id
			. " AND country_id ='" . $this->country_id . "'";

		$db->setQuery($q);

		$xid = intval($db->loadResult());

		if ($xid)
		{
			$this->_error = JText::_('COM_REDSHOP_STATE_CODE3_ALREADY_EXISTS');
			JError::raiseWarning('', $this->_error);

			return false;
		}
		else
		{
			$q = "SELECT state_id,state_3_code,state_2_code  FROM " . $this->_table_prefix . "state"
				. " WHERE state_2_code = '" . $this->state_2_code
				. "' AND state_id !=  " . (int) $this->state_id
				. " AND country_id ='" . $this->country_id . "'";

			$db->setQuery($q);
			$xid = intval($db->loadResult());

			if ($xid)
			{
				$this->_error = JText::_('COM_REDSHOP_STATE_CODE2_ALREADY_EXISTS');
				JError::raiseWarning('', $this->_error);

				return false;
			}
		}

		return true;
	}
}

