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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );

class catalogModelcatalog extends JModel
{
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}
	
	function catalogStore($data)
	{
		$row =& $this->getTable('catalog_request');
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	function catalogSampleStore($data)
	{
		$row =& $this->getTable('sample_request');
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
			
	function getCatalogList()
	{
		$query = "SELECT c.*,c.catalog_id AS value,c.catalog_name AS text FROM ".$this->_table_prefix."catalog AS c "
				."WHERE c.published = 1 "
				;
		$catalog = $this->_getList($query);
		return $catalog;
	}
	
	function getCatalogSampleList()
	{
		$query = "SELECT c.* FROM ".$this->_table_prefix."catalog_sample AS c "
				."WHERE c.published = 1 "
				;
		$catalog = $this->_getList($query);
		return $catalog;
	}
	
	function getCatalogSampleColorList($sample_id=0)
	{
		$and = "";
		if($sample_id!=0)
		{
			$and = "AND c.sample_id='".$sample_id."' ";
		}
		$query = "SELECT c.* FROM ".$this->_table_prefix."catalog_colour AS c "
				."WHERE 1=1 "
				.$and
				;
		$catalog = $this->_getList($query);
		return $catalog;
	}
}?>