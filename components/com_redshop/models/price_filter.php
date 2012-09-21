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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class price_filterModelprice_filter extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	
	function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	function _buildQuery()
	{
		$category = JRequest::getVar('category');
		$catfld = '';
		if($category!=0)
		{
			$catfld .= " AND cx.category_id IN ($category) ";
		}
		
		$sql = "SELECT DISTINCT(p.product_id),p.* FROM ".$this->_table_prefix."product AS p "
			  ."LEFT JOIN ".$this->_table_prefix."product_category_xref AS cx ON cx.product_id = p.product_id "
			  ."WHERE p.published=1 "
			  .$catfld
			  ."ORDER BY p.product_price "
			  ;
		return $sql;
	}
	function getData()
	{
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}
		return $this->_data;
	}
}?>