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

class account_shiptoModelaccount_shipto extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$infoid = JRequest::getInt('infoid');
		
		$this->setId($infoid);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if(!$this->_loadData())
		{
			$this->_initData();
		}
		return $this->_data;
	}
	
	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->users_info_id			= 0;
			$detail->user_id				= 0;
			$detail->firstname				= null;
			$detail->lastname				= null;
			$detail->company_name			= null;
			$detail->address				= null;
			$detail->state_code				= null;
			$detail->country_code			= null;
			$detail->city					= null;
			$detail->zipcode				= null;
			$detail->phone					= 0;
			$this->_data		 			= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function _loadData($users_info_id=0)
	{
		if($users_info_id)
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'users_info WHERE users_info_id="'.$users_info_id.'" ';
			$this->_db->setQuery($query);
			$list = $this->_db->loadObject();
			return $list;
		}
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'users_info WHERE users_info_id="'.$this->_id.'" ';
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return $this->_data;
		}
		return true;
	}

	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM '.$this->_table_prefix.'users_info WHERE users_info_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	function store($post)
	{
		$userhelper = new rsUserhelper();
		
	   	$post['user_email'] = $post['email1'] = $post['email'];
		$reduser = $userhelper->storeRedshopUserShipping($post);
		
		return $reduser;
	}

}	?>