<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');


class stateModelstate extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	function __construct()
	{
		parent::__construct();

		global $mainframe, $context;

		$context = 'state_id';

	  	$this->_table_prefix = '#__'.TABLE_PREFIX.'_';
		$limit	= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
                $country_id_filter = $mainframe->getUserStateFromRequest( $this->_context.'country_id_filter','country_id_filter',0);
                $country_main_filter= $mainframe->getUserStateFromRequest( $this->_context.'country_main_filter','country_main_filter','');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

                $this->setState('country_id_filter', $country_id_filter);
                $this->setState('country_main_filter', $country_main_filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}
	function getData()
	{
		if (empty($this->_data))
		{
			//$query = $this->_buildQuery();
			//$this->_data = $this->_getList($query);
                           $this->_data = $this->_buildQuery();
		}
		return $this->_data;
	}
	function getTotal()
	{
		$query = 'SELECT count(*) FROM '.$this->_table_prefix.'state s ';
		if (empty($this->_total))
		{
		 	$this->_db->setQuery( $query );
			  $this->_total =  $this->_db->loadResult();
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


                $orderby	= $this->_buildContentOrderBy();
                $country_id_filter = $this->getState('country_id_filter');
                $country_main_filter=$this->getState('country_main_filter');
                $limitstart = $this->getState('limitstart');
		$limit = $this->getState('limit');
                $andcondition='1=1';
                $country_main_filter	=	addslashes($country_main_filter);
                    if($country_id_filter > 0 &&  $country_main_filter=='')
                {
                   $andcondition='c.country_id = '.$country_id_filter;
                }
               else if($country_id_filter > 0 && $country_main_filter!='')
                {

                  $andcondition="c.country_id = ".$country_id_filter." and (s.state_name like '".$country_main_filter."%' || s.state_3_code = '".$country_main_filter."' || s.state_2_code = '".$country_main_filter."')" ;
                }
               else if($country_id_filter == 0 && $country_main_filter!='')
               {
                      $andcondition="s.state_name like '".$country_main_filter."%' || s.state_3_code = '".$country_main_filter."' || s.state_2_code='".$country_main_filter."'" ;
               }
		$query = 'SELECT distinct(s.state_id),s . * , c.country_name FROM `'.$this->_table_prefix.'state` AS s '
			.'LEFT JOIN '.$this->_table_prefix.'country AS c ON s.country_id = c.country_id WHERE '.$andcondition.$orderby;

                $this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectlist();
                $list = $rows;
                $total = count( $list );

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $total, $limitstart, $limit );

		// slice out elements based on limits
		$list = array_slice( $list, $this->_pagination->limitstart, $this->_pagination->limit );
		$items = $list;
		return $items;
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $context;

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'state_id' );


		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	function getCountryName($country_id)
	{
		$query="SELECT  c.country_name from ".$this->_table_prefix."country AS c where c.country_id=".$country_id;
		$this->_db->setQuery($query);
	    return $this->_db->loadResult();
	}

}

?>