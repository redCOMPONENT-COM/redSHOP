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
jimport('joomla.application.component.model');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'thumbnail.php' );
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class prices_detailModelprices_detail extends JModel
{
	var $_id = null;
	var $_prodid = null;
	var $_prodname = null;
	var $_data = null;
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__'.TABLE_PREFIX.'_';		

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->_prodid = JRequest::getVar('product_id',  0, '', 'int');
		
		$this->setId((int)$array[0]);
		$this->setProductName();
	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function setProductName()
	{
		$query = ' SELECT prd.product_name '
				 . ' FROM ' . $this->_table_prefix.'product as prd '
				 . ' WHERE prd.product_id = '. $this->_prodid
				 ;
		$this->_db->setQuery($query);
		$this->_prodname = $this->_db->loadObject()->product_name;
	}

	function &getData()
	{
		if ($this->_loadData())
		{	}
		else  $this->_initData();

	   	return $this->_data;
	}
	
	function _loadData()
	{
		if (empty($this->_data))
		{
			$query = ' SELECT p.*, '
				 . ' g.shopper_group_name, prd.product_name '
				 . ' FROM '.$this->_table_prefix.'product_price as p '
				 . ' LEFT JOIN '.$this->_table_prefix.'shopper_group as g ON p.shopper_group_id = g.shopper_group_id '
				 . ' LEFT JOIN '.$this->_table_prefix.'product as prd ON p.product_id = prd.product_id '
				 . ' WHERE p.price_id = '. $this->_id
				 ;
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
			$detail->price_id			= 0;
			$detail->product_id			= $this->_prodid;
			$detail->product_name		= $this->_prodname;
			$detail->product_price		= 0.00;
			$detail->product_currency	= null;
			$detail->shopper_group_id	= 0;
			$detail->price_quantity_start = 0;
			$detail->price_quantity_end = 0;
			$detail->shopper_group_name	= null;
			$detail->discount_price = 0;
			$detail->discount_start_date = 0;
			$detail->discount_end_date = 0;
			$this->_data		 		= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	function store($data)
	{
		$row =& $this->getTable();
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			 
			$query = 'DELETE FROM '.$this->_table_prefix.'product_price WHERE price_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}?>