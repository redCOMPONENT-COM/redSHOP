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

class Tablequotation_item_detail extends JTable
{
	var $quotation_item_id = null;
	var $quotation_id = null;
	var $product_id = null;
	var $product_name = null;
	var $product_price = null;
	var $actualitem_price = null;
	var $product_excl_price = null;
	var $product_final_price = null;
	var $product_quantity = null;
	var $product_attribute = null;
	var $product_accessory = null;
	var $mycart_accessory = null;
	var $product_wrapperid = null;
	var $wrapper_price = null;
	var $is_giftcard = null;
		
	function Tablequotation_item_detail(& $db) 
	{
	 	$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix.'quotation_item', 'quotation_item_id', $db);
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