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

class Tableproduct_attribute extends JTable
{
	var $attribute_id = null;
	var $attribute_set_id = 0;
	var $attribute_name = null;
	var $attribute_required = null;
	var $allow_multiple_selection = 0;
	var $hide_attribute_price = 0;
	var $product_id = null;
	var $ordering = null;
	var $attribute_published = 1;
	var $display_type = null;
	
	function Tableproduct_attribute(& $db)
	{
	  $this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix.'product_attribute', 'attribute_id', $db);
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
