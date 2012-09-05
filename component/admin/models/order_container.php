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

class order_containerModelorder_container extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	function __construct()
	{
		parent::__construct();

		global $mainframe, $context; 
		$context='order_id';
	  	$this->_table_prefix = '#__redshop_';			
		$limit			= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		
		$filter_status	  = $mainframe->getUserStateFromRequest( $context.'filter_status',		'filter_status',		'',			'word' );
		$filter     = $mainframe->getUserStateFromRequest( $context.'filter','filter',0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('filter_status', $filter_status);

	}
	function getData()
	{		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
}
	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
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
		$where = "";    
	    
	   	
	    $filter = $this->getState('filter');
		$filter_status = $this->getState('filter_status');
		
		
		$where = array();
		
		if ( $filter_status ) {
		 
			$where[] = "o.order_status like '%".$filter_status."%'";
		 
		}
				
		if($filter) 
	    {
	    	$where[] = "(  uf.firstname like '%".$filter."%' OR uf.lastname like '%".$filter."%')";	    	    	
	    }
			
		$where		= count( $where ) ? ' AND ' . implode( ' AND ', $where ) : '';
		
	    $orderby	= $this->_buildContentOrderBy();
		
		$query = ' SELECT * '
			. ' FROM '.$this->_table_prefix.'orders as o, '.$this->_table_prefix.'users_info as uf WHERE o.order_id IN ( SELECT  DISTINCT (`order_id`) FROM '.$this->_table_prefix.'order_item WHERE `container_id` < 1 ) AND  o.user_id=uf.user_id and address_type Like "BT" '.$where.$orderby;
			
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $context;
	
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'order_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
					
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;			
		 		
		return $orderby;
	}
	function update_status()
	{
	
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'order.php' );
		$order_function = new order_functions();
	
		$order_function->update_status();
	
		
	}
	function export_data(){
				
		$query = ' SELECT * '
			. ' FROM '.$this->_table_prefix.'orders as o, '.$this->_table_prefix.'users_info as uf WHERE  o.user_id=uf.user_id and address_type Like "BT" ';
			
		$query = $this->_buildQuery();
		return $this->_getList($query);
	}
	
}?>