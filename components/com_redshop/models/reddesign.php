<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class reddesignModelreddesign extends JModel
{	

	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	
	function __construct()
	{
		// redshop product detail
		$this->_pid =(int)JRequest::getVar('pid',  0);
		$this->_cid = (int)JRequest::getVar('cid',  0);
	
		$this->_table_prefix = '#__reddesign_';			
		parent::__construct();
	}
	function getDesignTypeImages($designtype_id)
	{
		$db = & JFactory :: getDBO();
		
		$table = $this->_table_prefix ."image";
		$query = "SELECT * FROM ".$table." WHERE designtype_id = ".$designtype_id ." order by ordering";
		$db->setQuery($query);
		
		return $db->loadObjectlist();
	}
	
	function getProductDetail($product_id,$field_name="")
	{
		
		$db = & JFactory :: getDBO();
		if(!$field_name)
		{
			$query = 'SELECT * FROM `#__redshop_product` WHERE product_id = '. $product_id;
		}
		else
		{
			$query = 'SELECT $field_name FROM `#__redshop_product` WHERE product_id = '. $product_id;
		}
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function getProductDesign($product_id)
	{		
		$db = & JFactory :: getDBO();
		
		$query = "SELECT * FROM `#__reddesign_redshop` WHERE `product_id` = '".$product_id."'";
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
}
?>