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
// textlibrary
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class ratingModelrating extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	var $_context = null;
	
	function __construct()
	{
		parent::__construct();

		global $mainframe;
		$this->_context='rating_id';
	  	$this->_table_prefix = '#__redshop_';			
		$limit			= $mainframe->getUserStateFromRequest( $this->_context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $this->_context.'limitstart', 'limitstart', 0 );
		$comment_filter     = $mainframe->getUserStateFromRequest( $this->_context.'comment_filter','comment_filter',0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('comment_filter', $comment_filter);
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
		$comment_filter = $this->getState('comment_filter');
	    
	    $where='';
	    
	    if($comment_filter) 
	    {
	    	$where = " WHERE username like '%".$comment_filter."%' "; 
	    	$where .= " OR comment LIKE '%".$comment_filter."%' ";
	    	$where .= " OR product_name LIKE '%".$comment_filter."%' "; 
	    }	
	
		$orderby	= $this->_buildContentOrderBy();
		
	    $query = ' SELECT p.product_name,u.username,r.* '
			. ' FROM '.$this->_table_prefix.'product_rating r LEFT JOIN '.$this->_table_prefix.'product p ON p.product_id = r.product_id  LEFT JOIN #__users u ON u.id = r.userid '.$where.$orderby;
		
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe;
	 
		$filter_order     = $mainframe->getUserStateFromRequest( $this->_context.'filter_order',      'filter_order', 	  'rating_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $this->_context.'filter_order_Dir',  'filter_order_Dir', '' );		
					
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;			
		 		
		return $orderby;
	}
}	?>