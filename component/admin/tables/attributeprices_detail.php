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
jimport('joomla.application.component.model');

class Tableattributeprices_detail extends JTable
{
	var $price_id = 0;
	var $section_id = null;
	var $section = null;
	var $product_price = null;	
	var $product_currency = null;
	var $cdate = null; 	
	var $shopper_group_id  = null;
	var $price_quantity_start = 0; 
	var $price_quantity_end = 0;
	var $discount_price = 0;
	var $discount_start_date = 0;
	var $discount_end_date = 0; 
		
	function Tableattributeprices_detail(& $db) 
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix.'product_attribute_price', 'price_id', $db);
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
		/**** check for valid name *****/
		$query = 'SELECT price_id FROM '.$this->_table_prefix.'product_attribute_price '
				.' WHERE shopper_group_id = "'.$this->shopper_group_id.'" '
				.'AND section_id = ' .$this->section_id
				.' AND price_quantity_end >= '.$this->price_quantity_start;
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->price_id)) {
			
			$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('PRICE ALREADY EXISTS'));
			return false;
		}
		return true;
	}
}?>