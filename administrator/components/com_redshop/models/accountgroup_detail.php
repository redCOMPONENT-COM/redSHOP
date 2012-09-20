<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');


class accountgroup_detailModelaccountgroup_detail extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__'.TABLE_PREFIX.'_';		
	  
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
			$query = 'SELECT * FROM '.$this->_table_prefix.'economic_accountgroup '
					.'WHERE accountgroup_id="'.$this->_id.'" ';
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
			
			$detail->accountgroup_id					= 0;
			$detail->accountgroup_name					= null;
			$detail->economic_vat_account	 			= null;
			$detail->economic_nonvat_account			= null;
			$detail->economic_discount_vat_account		= null;
			$detail->economic_discount_nonvat_account	= null; 
			$detail->economic_shipping_vat_account		= null;
			$detail->economic_shipping_nonvat_account	= null;
			$detail->economic_discount_product_number	= null;
			$detail->published							= 1;
			$this->_data		 		= $detail;
			
			return (boolean) $this->_data;
		}
		
		return true;
	}
  	function store($data)
	{
		$row =& $this->getTable();
		if (!$row->bind($data)) 
		{
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
			 
			$query = 'DELETE FROM '.$this->_table_prefix.'economic_accountgroup '
					.'WHERE accountgroup_id IN ( '.$cids.' )';
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

			$query = 'UPDATE '.$this->_table_prefix.'economic_accountgroup'
				. ' SET published = "' . intval( $publish ).'" '
				. ' WHERE accountgroup_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
?>