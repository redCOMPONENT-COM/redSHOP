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


class Tablecurrency_detail extends JTable
{
	var $currency_id = null;
	var $currency_name = null;	
	var $currency_code = null;
	var $dynamic_country_id = null;
	
		
	function Tablecurrency_detail(& $db) 
	{
	  $this->_table_prefix = '#__redshop_';
			
		parent::__construct($this->_table_prefix.'currency', 'currency_id', $db);
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
	
	function check() {
		
		$db = JFactory::getDBO(); 
		
		$q =  "SELECT dynamic_country_id,currency_id,currency_code  FROM ".$this->_table_prefix."currency"." WHERE currency_code = '".$this->currency_code."' AND currency_id !=  ".$this->currency_id;  
		$db->setQuery($q);	

		$xid = intval($db->loadResult()); 
		if ($xid) 
		{	
	
			 $this->_error = JText::_( 'CURRENCY_CODE_ALREADY_EXISTS' );
			 JError::raiseWarning('', $this->_error );
			 return false;
	  	}
		
  		return true; 
 
	}
}
?>

