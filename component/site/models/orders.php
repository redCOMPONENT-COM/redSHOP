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

class ordersModelorders extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	var $_template = null;
	var $_limitstart = null;
	var $_limit = null;

	function __construct()
	{
		parent::__construct();
		global $mainframe,$option;

		$this->_table_prefix = '#__redshop_';
		$this->_limitstart = JRequest::getVar( 'limitstart', 0 );
		$this->_limit = $mainframe->getUserStateFromRequest($option.'limit','limit',10,'int');
	}

	function _buildQuery()
	{
		$user =& JFactory::getUser();
		$query = "SELECT * FROM  ".$this->_table_prefix."orders "
				."WHERE user_id='".$user->id."' "
				;
		return $query;
	}

	function getData()
	{
//		if (empty( $this->_data ))
//		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query , $this->_limitstart, $this->_limit);
//		}
		return $this->_data;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new redPagination( $this->getTotal(), $this->_limitstart, $this->_limit );
		}
		return $this->_pagination;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount ( $query );
		}
		return $this->_total;
	}
}	?>