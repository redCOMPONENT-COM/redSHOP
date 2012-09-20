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

class pricesModelprices extends JModel
{
	var $_prodid = 0;
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	function __construct()
	{
		parent::__construct();
		global $mainframe, $context;
		
		$context = 'price';
		
	  	$this->_table_prefix = '#__'.TABLE_PREFIX.'_';			
		$limit	= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		 
		$pid = JRequest::getVar('product_id');
		$this->setProductId((int)$pid);
	}
	function setProductId($id)
	{
		// Set employees_detail id and wipe data
	 	$this->_prodid	= $id;
		$this->_data	= null;
	}
	
	function getProductId()
	{
		return $this->_prodid;
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
		$where = $this->_buildContentWhere();
		$query = ' SELECT p.*, '
				 . ' g.shopper_group_name, prd.product_name '
				 . ' FROM '.$this->_table_prefix.'product_price as p '
				 . ' LEFT JOIN '.$this->_table_prefix.'shopper_group as g ON p.shopper_group_id = g.shopper_group_id '
				 . ' LEFT JOIN '.$this->_table_prefix.'product as prd ON p.product_id = prd.product_id '
				 . $where
				 ;
		return $query;
	}
	
	function _buildContentWhere()
	{
		global $mainframe, $context;
		$where = 'WHERE p.product_id = \''.$this->_prodid.'\' ';
		
		return $where;
	}
}	?>