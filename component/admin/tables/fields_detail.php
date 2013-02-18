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

class Tablefields_detail extends JTable
{
	var $field_id = null;
	var $field_title = null;
	var $wysiwyg = null;
	var $field_name = null;
	var $field_type = null;
	var $field_desc = null;
	var $field_class = null;
	var $field_section = null;
	var $field_maxlength = null;
	var $field_size = null;
	var $field_cols = null;
	var $field_rows = null;
	var $required   = 0;
	var $ordering = null;
	var $field_show_in_front = null;
	var $display_in_product = null;
	var $display_in_checkout = null;

	var $published = null;

	function Tablefields_detail(& $db)
	{
	  $this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix.'fields', 'field_id', $db);
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
