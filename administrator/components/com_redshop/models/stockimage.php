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

class stockimageModelstockimage extends JModel
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
		 
		$this->_context='stock_amount_id';
	  	$this->_table_prefix = '#__redshop_';			
		
	  	$limit	= $mainframe->getUserStateFromRequest( $this->_context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $this->_context.'limitstart', 'limitstart', 0 );
		$filter = $mainframe->getUserStateFromRequest( $this->_context.'filter','filter',0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
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
			$this->_total =  $this->_getListCount($query);
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
		$filter = $this->getState('filter');
		$orderby	= $this->_buildOrderBy();
		
		$where='';
		if($filter) 
		{
			$where = " WHERE stockroom_id='".$filter."' ";
		}
		$query = "SELECT * FROM ".$this->_table_prefix."stockroom_amount_image AS si "
				."LEFT JOIN ".$this->_table_prefix."stockroom AS s ON s.stockroom_id=si.stockroom_id "
				.$where
				.$orderby
				;
		return $query;
	}
	
	function _buildOrderBy()
	{
		global $mainframe;
	
		$filter_order     = $mainframe->getUserStateFromRequest( $this->_context.'filter_order',      'filter_order', 	  'stock_amount_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $this->_context.'filter_order_Dir',  'filter_order_Dir', '' );		
					
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;				
		 		
		return $orderby;
	}
	
	function getStockAmountOption($select=0)
	{
		$option = array();
		$option[]   = JHTML::_('select.option', 0,JText::_('COM_REDSHOP_SELECT'));
		$option[]   = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_HIGHER_THAN'));
		$option[]   = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_EQUAL'));
		$option[]   = JHTML::_('select.option', 3, JText::_('COM_REDSHOP_LOWER_THAN'));
		if($select!=0)
		{
			$option = $option[$select]->text;
		}
		return $option;
	}
}	?>