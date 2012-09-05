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

class shipping_rateModelShipping_rate extends JModel
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
		$id     = $mainframe->getUserStateFromRequest( $context.'id',      'id', 	  '0' );
	
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('id', $id);
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
		$id = $this->getState('id');
	
		$query = 'SELECT r.*,p.id,p.element,p.folder FROM '.$this->_table_prefix.'shipping_rate AS r '
				.'LEFT JOIN #__plugins AS p ON CONVERT(p.element USING utf8)= CONVERT(r.shipping_class USING utf8) '
	    		.'WHERE p.id="'.$id.'" '
	    		.$orderby;
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $context;
		$filter_order = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'shipping_rate_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;			
		return $orderby;
	}
}?>