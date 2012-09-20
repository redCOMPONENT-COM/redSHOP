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

class Tableorder_attribute_item extends JTable
{
	var $order_att_item_id = null;
	var $order_item_id = null;
	var $section_id = null;
	var $section = null;
	var $parent_section_id = null;	
	var $section_name = null;
	var $section_vat = null;
	var $section_price = null;
	var $section_oprand = null;
	var $is_accessory_att = null;
	var $stockroom_id = null;
	var $stockroom_quantity = null;
	 
		
	function Tableorder_attribute_item(& $db) 
	{
	 	$this->_table_prefix = '#__redshop_';
			
		parent::__construct($this->_table_prefix.'order_attribute_item', 'order_att_item_id', $db);
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