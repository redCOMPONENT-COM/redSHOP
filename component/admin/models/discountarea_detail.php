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

class discountarea_detailModeldiscountarea_detail extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';		
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}
	
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if ($this->_loadData())
		{}else  $this->_initData();
	   	return $this->_data;
	}
	
	function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'discountArea WHERE discountAreaid="'.$this->_id.'" ';
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();
			
			$detail->discountAreaid		= 0;
			$detail->area_start			= 0;
			$detail->area_end 			= 0;
			$detail->discount_on		= 0; 
			$detail->amount				= 0;
			$detail->product_id			= null;
			$detail->discountstart_date	= 0;
			$detail->discountend_date	= 0;
			$detail->category_id		= null;
			$detail->published			= 1;
			$this->_data		 		= $detail;
			
			return (boolean) $this->_data;
		}
		return true;
	}
	
  	function store($data)
	{
		$data['product_id'] =@ implode(',',$data['product_id']);
		$data['category_id'] =@ implode(',',$data['category_id']);
		
		$row =& $this->getTable('discountarea_detail');
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if(!$row->product_id) $row->product_id='';
		if(!$row->category_id) $row->category_id='';
		if (!$row->store()) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $row;
	}

	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM '.$this->_table_prefix.'discount WHERE discountAreaid IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	function publish($cid = array(), $publish = 1)
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE '.$this->_table_prefix.'discountArea '
					. ' SET published = ' . intval( $publish )
					. ' WHERE discountAreaid IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	
	function GetCategoryList()
	{
		$query = 'SELECT category_name as text,category_id as value FROM '.$this->_table_prefix.'category WHERE published = 1';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function GetProductList($product_id='')
	{
		$and = '';
		if($product_id!='')
		{
			$and .= 'AND product_id IN ('.$product_id.')';
		}
	 	$query = 'SELECT product_name as text,product_id as value FROM '.$this->_table_prefix.'product '
				.'WHERE published=1 '
				.$and;
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
?>