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

class Tablediscount_detail extends JTable
{
	var $discount_id = 0;
	var $amount = null;
	var $condition = null;
	var $discount_amount = null;
	var $discount_type = null;
	var $start_date = null;
	var $end_date = null; 	
	var $published = null;
		
	function Tablediscount_detail(& $db) 
	{
	 	$this->_table_prefix = '#__redshop_';
			
		parent::__construct($this->_table_prefix.'discount', 'discount_id', $db);
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
	
}
?>