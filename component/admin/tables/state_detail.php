<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');


class Tablestate_detail extends JTable
{
	var $state_id = null;
	var $state_name = null;
	var $state_3_code = null;
	var $state_2_code = null;
	var $show_state = 2;
	var $country_id=null;

	/**
	 * @var boolean
	 */
	var $checked_out = 0;

	/**
	 * @var time
	 */
	var $checked_out_time = 0;


	function Tablestate_detail(& $db)
	{
	  $this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix.'state', 'state_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

	function check()
	{

		$db = JFactory::getDBO();

		$q =  "SELECT state_id,state_3_code  FROM ".$this->_table_prefix."state"." WHERE state_3_code = '".$this->state_3_code."' AND state_id !=  ".$this->state_id." AND country_id ='".$this->country_id."'";

		$db->setQuery($q);

		$xid = intval($db->loadResult());
		if ($xid)
		{

			 $this->_error = JText::_( 'STATE_CODE3_ALREADY_EXISTS' );
			 JError::raiseWarning('', $this->_error );
			 return false;
	  	}else{

			$q =  "SELECT state_id,state_3_code,state_2_code  FROM ".$this->_table_prefix."state"." WHERE state_2_code = '".$this->state_2_code."' AND state_id !=  ".$this->state_id." AND country_id ='".$this->country_id."'";

			$db->setQuery($q);
			$xid = intval($db->loadResult());
			if ($xid)
			{
				 $this->_error = JText::_( 'STATE_CODE2_ALREADY_EXISTS' );
				 JError::raiseWarning('', $this->_error );
				 return false;
			 }
		}
  		return true;

	}




}
?>

