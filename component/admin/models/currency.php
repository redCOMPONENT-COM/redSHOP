<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');

class currencyModelcurrency extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	function __construct()
	{
		parent::__construct();

		global $mainframe, $context;
		$currencyobject = JRequest::getVar( 'object' );
		$context = ($currencyobject=='cid') ? 'mod_currency_id' : 'currency_id';
		$this->_table_prefix = '#__redshop_';	
	  	$limit	= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData()
	{		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
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
  	
	function _buildQuery()
	{		
		 //$filter = $this->getState('filter');
		$orderby = $this->_buildContentOrderBy();
		$query = "SELECT distinct(c.currency_id),c.*  FROM ".$this->_table_prefix."currency AS c "
				."WHERE 1=1 "
				.$orderby;
		return $query;
	}

	function _buildQuery_shivani()
	{		
		 //$filter = $this->getState('filter');
		$orderby	= $this->_buildContentOrderBy();
		$where='';
		$limit = "";	
		require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'currency.php');
		
		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$convertPrice = new convertPrice();
		
		$convertPrice->init();

		$currency = array();	
		
		if (count($GLOBALS['converter_array'])>0){
			foreach ($GLOBALS['converter_array'] as $key=>$val){
				 $currency[] = $key;
			}
			
			$currency = implode("','",$currency);
		}		
		$get = JRequest::get('get');
		if ($this->getState('limit') >0)
		{
			 $limit = " LIMIT ".$this->getState('limitstart').",".$this->getState('limit');
		}
		if($where==''){
 			$query =  "SELECT distinct(c.currency_id),c.*  FROM ".$this->_table_prefix."currency c WHERE 1=1 ".$orderby . $limit;
		}
		/*if ($currency && $get['object']=="cid"){
			$query = "SELECT distinct(c.currency_id),c.*  FROM ".$this->_table_prefix."currency c WHERE currency_code IN ('".$currency."')".$orderby;
		}*/

		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $context;
	
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'currency_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );	
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		return $orderby;
	}
}?>