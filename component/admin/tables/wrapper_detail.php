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

class Tablewrapper_detail extends JTable
{
	var $wrapper_id = 0;
	var $product_id = null;
	var $category_id= null;
	var $wrapper_price = null;	
	var $wrapper_name = null;
	var $wrapper_image = null;
	var $wrapper_use_to_all = 0;
	var $published = 1;
	var $createdate = 0;
		
	function Tablewrapper_detail(& $db) 
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix.'wrapper', 'wrapper_id', $db);
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
}?>