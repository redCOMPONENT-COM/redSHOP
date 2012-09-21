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

class Tablexmlexport_detail extends JTable
{
	var $xmlexport_id 		= null;
	var $filename 			= null;
	var $display_filename 	= null;
	var $parent_name		= null; 
	var $element_name		= null; 
	var $section_type 		= null;
	var $sync_on_request	= 0;
	var $auto_sync 			= 0;
	var $auto_sync_interval = 0;
	var $xmlexport_date		= null;
	var $xmlexport_filetag	= null;
	var $xmlexport_billingtag=null;
	var $billing_element_name=null;
	var $xmlexport_shippingtag=null;
	var $shipping_element_name=null;
	var $xmlexport_orderitemtag=null;
	var $orderitem_element_name=null;
	var $xmlexport_stocktag	= null;
	var $stock_element_name	= null;
	var $xmlexport_prdextrafieldtag = null;
	var $prdextrafield_element_name = null; 
	var $published 			= 0;
	var $use_to_all_users	= 1;
	var $xmlexport_on_category=null; 
	
	function Tablexmlexport_detail(& $db) 
	{
	 	$this->_table_prefix = '#__redshop_';
			
		parent::__construct($this->_table_prefix.'xml_export', 'xmlexport_id', $db);
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