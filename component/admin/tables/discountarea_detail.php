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

class Tablediscountarea_detail extends JTable
{
	var $discountAreaid = 0;
	var $amount = 0;
	var $area_start = null;
	var $area_end = null;
	var $discount_on = null;
	var $product_id = null;
	var $discountstart_date = 0;
	var $discountend_date = 0;
	var $category_id = null;
	var $published = null;
		
	function Tablediscountarea_detail(& $db) 
	{
	 	$this->_table_prefix = '#__redshop_';
			
		parent::__construct($this->_table_prefix.'discountArea', 'discountAreaid', $db);
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