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

class discountModeldiscount extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	function __construct()
	{
		parent::__construct();

		global $mainframe, $context;
		
		$layout = JRequest::getVar('layout');
   		
		if(isset($layout) && $layout == 'product')
			$context = 'discount_product_id';
		else
			$context = 'discount_id';
		
	  	$this->_table_prefix = '#__redshop_';			
		$limit	= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}
	function getData()
	{		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
		}
		return $this->_data;
	}
	function getTotal()
	{
		$layout = JRequest::getVar('layout');
		if(isset($layout) && $layout == 'product')
		{
			$query = 'SELECT count(*) FROM '.$this->_table_prefix.'discount_product p ';
		}
		else
		{
			$query = 'SELECT count(*) FROM '.$this->_table_prefix.'discount c ';
		}
		if (empty($this->_total))
		{
		 	$this->_db->setQuery( $query );		
			  $this->_total =  $this->_db->loadResult();
		}

		return $this->_total;
	}
	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
  	
	function _buildQuery()
	{
		$orderby	= $this->_buildContentOrderBy();
		$where='';	   
		$limit = "";	
		if ($this->getState('limit') >0)
		{
			 $limit = " LIMIT ".$this->getState('limitstart').",".$this->getState('limit');
		} 
		$layout = JRequest::getVar('layout');
   		
		if(isset($layout) && $layout == 'product')
		{
			$query = ' SELECT * '
				. ' FROM '.$this->_table_prefix.'discount_product '.$orderby.$limit;
			
		}else{
		
			$query = ' SELECT * '
				. ' FROM '.$this->_table_prefix.'discount '.$orderby.$limit;
		}
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $context;
		
		$layout = JRequest::getVar('layout');
   		
		if(isset($layout) && $layout == 'product')
			$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'discount_product_id' );
		else			
			$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'discount_id' );
			
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
					
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;			
		 		
		return $orderby;
	}
}