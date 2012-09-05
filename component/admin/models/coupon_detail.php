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

class coupon_detailModelcoupon_detail extends JModel
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
		{

		}else  $this->_initData();

	   	return $this->_data;
	}

	function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'coupons WHERE coupon_id='.$this->_id;
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
			$detail->coupon_id				= null;
			$detail->coupon_code			= null;
			$detail->start_date				= 0;
			$detail->end_date				= 0;
			$detail->percent_or_total		= null;
			$detail->free_shipping			= 0;
			$detail->coupon_value			= null;
			$detail->coupon_type			= null;
			$detail->userid					= null;
			$detail->coupon_left					= null;
			$detail->published				= 1;
			$this->_data		 			= $detail;
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

		if (!$row->store()) {
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

			$query = 'DELETE FROM '.$this->_table_prefix.'coupons WHERE coupon_id IN ( '.$cids.' )';
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

			$query = 'UPDATE '.$this->_table_prefix.'coupons'
				. ' SET published = ' . intval( $publish )
				. ' WHERE coupon_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	function getuserslist()
	{
		$query = 'SELECT u.id as value,u.name as text FROM  #__users as u,'.$this->_table_prefix.'users_info ru WHERE u.id=ru.user_id AND ru.address_type like "BT"';
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectlist();
	}
	function getproducts()
	{
		$product_id = JRequest::getVar('pid');
		if($product_id){
			$query = 'SELECT product_id,product_name FROM '.$this->_table_prefix.'product WHERE product_id ='.$product_id;
			$this->_db->setQuery( $query );
			return $this->_db->loadObject();
		}
	}
	function getuserfullname2($uid)
	{
		$query = "SELECT firstname,lastname,username FROM ".$this->_table_prefix."users_info as uf, #__users as u WHERE user_id=".$uid." AND address_type like 'BT' AND uf.user_id=u.id";
		$this->_db->setQuery($query);
		$this->_username = $this->_db->loadObject();
		$fullname ='';
		if($this->_username)
		$fullname=$this->_username->firstname." ".$this->_username->lastname." (".$this->_username->username.")";
		return $fullname;
	}

	function checkduplicate($discount_code){

		$query="SELECT count(*) as code from ".$this->_table_prefix."coupons"
			." LEFT JOIN ".$this->_table_prefix."product_voucher ON coupon_code=voucher_code"
			." where voucher_code='".$discount_code."' OR coupon_code='".$discount_code."'";

		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
}
?>