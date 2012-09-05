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

class templateModeltemplate extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	function __construct()
	{
		parent::__construct();

		global $mainframe, $context;

	  	$this->_table_prefix = '#__redshop_';
		$limit			= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		$template_section     = $mainframe->getUserStateFromRequest( $context.'template_section','template_section',0);
		$filter = $mainframe->getUserStateFromRequest( $context.'filter','filter',0);
		
		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('template_section', $template_section);
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
	    $orderby	= $this->_buildContentOrderBy();
	    $filter = $this->getState('filter');
	    $template_section = $this->getState('template_section');
	    
	    $where='';
	    if($filter)
	    {
	    	$where .= "AND t.template_name LIKE '".$filter."%' ";
	    }
	    if($template_section) 
	    {
    		$where .= "AND t.template_section='".$template_section."' ";
	    }
	    $query = 'SELECT distinct(t.template_id),t.* FROM '.$this->_table_prefix.'template AS t '
	    		.'WHERE 1=1 '
	    		.$where
	    		.$orderby;
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $context;

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'template_id' );

		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}
}	?>