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

class customprintModelcustomprint extends JModel
{
	var $_data = null;
	var $_table_prefix = null;
	
	function __construct()
	{ 
		parent::__construct();
		global $mainframe, $context;

	  	$this->_table_prefix = '#__';			
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
	
	function _buildQuery()
	{
		$where=" where folder='redshop_custom_views' and published=1";	   
		$query = ' SELECT p.* FROM '.$this->_table_prefix.'plugins p'.$where;
		return $query;
	}
	

}