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

class categoryModelcategory extends JModel
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
		
		$this->_context = 'category_id';
	  	$this->_table_prefix = '#__'.TABLE_PREFIX.'_';
		$limit	= $mainframe->getUserStateFromRequest( $this->_context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $this->_context.'limitstart', 'limitstart', 0 );
		$category_main_filter = $mainframe->getUserStateFromRequest( $this->_context.'category_main_filter','category_main_filter',0);
	 	$category_id     = $mainframe->getUserStateFromRequest( $this->_context.'category_id','category_id',0);

		$this->setState('category_main_filter', $category_main_filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('category_id', $category_id);
	}
	
	function getData()
	{
		if (empty($this->_data))
		{
			$this->_data = $this->_buildQuery();
		}
		return $this->_data;
	}
	function getPagination()
	{
		if ($this->_pagination == null) {
			$this->_buildQuery();
		}
		return $this->_pagination;
	}

	function _buildQuery()
	{
		global $mainframe;
		$view =  JRequest::getVar ( 'view' );
		$db = jFactory::getDBO();
		
		$category_id = $this->getState('category_id');
		$category_main_filter = $this->getState('category_main_filter');
		$limit	= $this->getState('limit');
		$limitstart = $this->getState('limitstart');
		
		$orderby	= $this->_buildContentOrderBy();
        $and = "";
		if($category_main_filter)
		{
			$and .= " AND category_name like '%".$category_main_filter."%' ";
		}
		if($category_id!=0)
		{
//			$and .= " AND cx.category_parent_id='$category_id' ";
		}
		$q = "SELECT c.category_id, cx.category_child_id, cx.category_child_id AS id, cx.category_parent_id, cx.category_parent_id AS parent_id,c.category_name, c.category_name AS title,c.category_description,c.published,ordering "
			."FROM ".$this->_table_prefix."category AS c, ".$this->_table_prefix."category_xref AS cx "
			."WHERE c.category_id=cx.category_child_id "
			.$and
			.$orderby;
		$db->setQuery($q);
		$rows = $db->loadObjectList();
		
		if(!$category_main_filter)
		{
			// establish the hierarchy of the menu
			$children = array();
			// first pass - collect children
			foreach ($rows as $v )
			{
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
			// second pass - get an indent list of the items
			$treelist = JHTML::_('menu.treerecurse', $category_id, '', array(), $children, 9999 );
			
			$total = count( $treelist );
		} else {
			$total = count( $rows );
			$treelist = $rows;
		}

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $total, $limitstart, $limit );

		// slice out elements based on limits
		$items = array_slice( $treelist, $this->_pagination->limitstart, $this->_pagination->limit );
		return $items;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order     = $mainframe->getUserStateFromRequest( $this->_context.'filter_order',      'filter_order', 	  'c.ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $this->_context.'filter_order_Dir',  'filter_order_Dir', '' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		return $orderby;
	}

 	function getProducts($cid)
 	{
 		$query = 'SELECT count(category_id) FROM '.$this->_table_prefix.'product_category_xref WHERE category_id="'.$cid.'" ';
		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
 	}

 	/*
	 * assign template to multiple categories
	 * @prams: $data, post variable	array
	 * @return: boolean
	 */
	function assignTemplate($data){

		$cid = $data['cid'];

		$category_template = $data['category_template'];

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE '.$this->_table_prefix.'category'
				. ' SET `category_template` = "' . intval( $category_template ).'" '
				. ' WHERE category_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable('category_detail');
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			// track categories
			$groupings[] = $row->category_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		// execute updateOrder for each parent group
		/*$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}*/
		return true;
	}
}	?>