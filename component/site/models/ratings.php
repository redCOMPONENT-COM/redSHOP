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

class ratingsModelratings extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	
	function __construct()
	{
		global $mainframe;
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		
		$limit	= $mainframe->getUserStateFromRequest( 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( 'limitstart', 'limitstart', 0 );
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
			
	function _buildQuery()
	{	 
		$query = "SELECT distinct(p.product_id),p.product_name FROM  ".$this->_table_prefix."product p"
				.", ".$this->_table_prefix."product_rating AS r "
				."WHERE p.published=1 AND r.published=1 AND p.product_id=r.product_id "
				; 
		return $query;
	}
	 
	function getData()
	{
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
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
	
	function getProductreviews($pid)
	{
		$query = "SELECT pr.*,uf.firstname,uf.lastname FROM  ".$this->_table_prefix."product_rating as pr"
				.", ".$this->_table_prefix."users_info as uf "
				."WHERE published=1 AND product_id='".$pid."' "
				."AND uf.address_type LIKE 'BT' AND pr.userid=uf.user_id "
				."ORDER BY favoured DESC"; 
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObjectlist();
		return $this->_data;			
	}
}?>