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


class container_detailModelcontainer_detail extends JModel
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
			$query = 'SELECT * FROM '.$this->_table_prefix.'container WHERE container_id = '. $this->_id;
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
			$detail->container_id				= 0;
			$detail->container_name				= null;
			$detail->container_desc				= null;
			$detail->min_del_time 				= 0;
			$detail->max_del_time 				= 0;
			$detail->container_volume			= 0;
			$detail->stockroom_id				= 0;
			$detail->published					= 1;
			$detail->manufacture_id			= null;
			$detail->supplier_id			= null;
			$detail->creation_date			= null;			
			$this->_data		 				= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
	function cancel(){
	
			$sql="DELETE FROM  ".$this->_table_prefix."container_product_xref   where container_id= 0";
			$this->_db->setQuery($sql);
			$this->_db->query();
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
		
		$container_id = $row->container_id;
		
		
		
		$sql="delete from ".$this->_table_prefix."container_product_xref where container_id='".$container_id."' ";
		$this->_db->setQuery($sql);
		$this->_db->query();
		if(isset($data["container_product_2"]))
			$container_product = $data["container_product_2"];
		else $container_product = array();
		
		
		if(count($container_product)>0)
		{
			$h=0;
			foreach($container_product as $cp)
			{
			
				$sql="insert into ".$this->_table_prefix."container_product_xref (container_id,product_id,quantity) value ('".$container_id."','".$cp."','".$data["quantity3"][$h]."')";
				$this->_db->setQuery($sql);					
				if(!$this->_db->query()) {
				$sql="update ".$this->_table_prefix."container_product_xref  set  quantity=quantity+'".$data["quantity3"][$h]."'  where container_id='".$container_id."' and product_id='".$cp."' ";
				$this->_db->setQuery($sql);
				$this->_db->query();
				}
 			
				////////// Start Add Container in orderitem ////////////////
				/*if($data["container_porder"][$h]==1)
				{
				
				$sql="select  order_item_id,product_id,product_quantity from ".$this->_table_prefix."order_item where container_id < 1 and product_id= ".$cp;
				$this->_db->setQuery($sql);
				$porder_data=$this->_db->loadObjectList();
				$add_quantity=$data["quantity"][$h];
				for($p=0;$p<count($porder_data);$p++)
				 {
				 
					if($add_quantity>=$porder_data[$p]->product_quantity)
					{
						$sql="update   ".$this->_table_prefix."order_item set container_id='".$container_id."' where order_item_id= ".$porder_data[$p]->order_item_id;
						$this->_db->setQuery($sql);
						$this->_db->query();						
						$add_quantity=$add_quantity-$porder_data[$p]->product_quantity;
					}					 
				 }				
				}*/
				////////// end Container orderitem ////////////////				
				$h++;
			}
		}
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		for($c=0;$c<count($cid);$c++)
		{
			$sql="update   ".$this->_table_prefix."order_item set container_id='".$container_id."' where order_item_id= ".$cid[$c];
			$this->_db->setQuery($sql);
			$this->_db->query();
		}
		return $row;
	}
	function saveanddisplay($data)
	{	
		 
		$container_id = $data['container_id'];
		
		
		
		$sql="delete from ".$this->_table_prefix."container_product_xref where container_id='".$container_id."' ";
		$this->_db->setQuery($sql);
		$this->_db->query();
		 	
		
	 
			foreach($data as $key => $value)
			{ 
				if(!strstr($key,'quantity2')){
				 
					continue;
				
				}
				$var = explode("_",$key);
				$product_id = $var[1];
				$quantity =  $value;
				 
			
				$sql="insert into ".$this->_table_prefix."container_product_xref (container_id,product_id,quantity) value ('".$container_id."','".$product_id."','".$quantity."')";
				$this->_db->setQuery($sql);	
								
				if(!$this->_db->query()) {
				$sql="update ".$this->_table_prefix."container_product_xref  set  quantity=quantity+'".$quantity."'  where container_id='".$container_id."' and product_id='".$product_id."' ";
				$this->_db->setQuery($sql);
				$this->_db->query();
				}
 				
				 		
				$h++;
			}
		 
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		for($c=0;$c<count($cid);$c++)
		{
			$sql="update   ".$this->_table_prefix."order_item set container_id='".$container_id."' where order_item_id= ".$cid[$c];
			$this->_db->setQuery($sql);
			$this->_db->query();
		}
		
		 return $container_id;
		 
		
	}
function deleteProduct($data)
	{	
		 
		$container_id = $data['container_id'];
		$product_id = $data['product_id'];
		
		
		$sql="delete from ".$this->_table_prefix."container_product_xref where container_id='".$container_id."' AND product_id='".$product_id."'";
		$this->_db->setQuery($sql);
		$this->_db->query();
		 	
		 
		 
		
	}
	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			$query = 'DELETE FROM '.$this->_table_prefix.'container WHERE container_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$query_stockroom = 'DELETE FROM '.$this->_table_prefix.'stockroom_container_xref WHERE container_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query_stockroom );
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
			
			$query = 'UPDATE '.$this->_table_prefix.'container'
				. ' SET published = ' . intval( $publish )
				. ' WHERE container_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}		
	function Container_Product_Data($container_id)
	{
	
			$query = "SELECT cp.product_id,cp.quantity,p.product_name,p.product_volume,cp.container_id FROM ".$this->_table_prefix."product as p , ".$this->_table_prefix."container_product_xref as cp  WHERE cp.container_id=$container_id and cp.product_id=p.product_id ";
			$this->_db->setQuery($query);
			$this->_productdata = $this->_db->loadObjectList();
			return $this->_productdata;		
	}
	function Container_newProduct($conid)
	{
			$conid=implode(",",$conid);
			$query = "SELECT  op.order_item_id, op.product_id,p.product_name,p.product_volume,op.product_quantity as quantity,p.supplier_id FROM ".$this->_table_prefix."product as p, ".$this->_table_prefix."order_item as op WHERE   op.product_id = p.product_id  and op.order_item_id in ($conid)  ";
			$this->_db->setQuery($query);
			$this->_productdata = $this->_db->loadObjectList();
			return $this->_productdata;		
	}	
	function stockroom_Data($id)
	{
			if($id == 0){
				$query = "SELECT stockroom_id as value, stockroom_name as text FROM ".$this->_table_prefix."stockroom";
			}else{
				$query = "SELECT stockroom_id as value, stockroom_name as text FROM ".$this->_table_prefix."stockroom where stockroom_id =".$id;
			}
			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();
	}
function getmanufacturers()
	{
		$query = 'SELECT manufacturer_id as value,manufacturer_name as text FROM '.$this->_table_prefix.'manufacturer  WHERE published=1';
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectlist();
	}
function getsupplier()
	{
		$query = 'SELECT supplier_id as value,supplier_name as text FROM '.$this->_table_prefix.'supplier ';
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectlist();
	}
}

?>
