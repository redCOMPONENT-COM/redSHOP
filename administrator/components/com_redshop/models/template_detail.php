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
//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');

class template_detailModeltemplate_detail extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	var $_copydata	=	null;

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
		$red_template = new Redtemplate();
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'template WHERE template_id = '. $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			// read template from file and replace it with database template description
			if(isset($this->_data->template_section))
			{
				$this->_data->template_name = strtolower($this->_data->template_name);
				$this->_data->template_name = str_replace(" ","_",$this->_data->template_name);
				$template_desc = $this->_data->template_desc;

				$this->_data->template_desc = $red_template->readtemplateFile($this->_data->template_section,$this->_data->template_name,true);
				if($this->_data->template_desc=="")
				{
					$this->_data->template_desc = $template_desc;
				}
			}

			return (boolean) $this->_data;
		}
		return true;
	}

	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->template_id				= 0;
			$detail->template_name				= null;
			$detail->template_desc				= null;
			$detail->template_section			= null;
			$detail->published					= 1;
			$detail->payment_methods			= null;
			$detail->shipping_methods			= null;
			$detail->order_status				= null;
			$this->_data		 				= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	function store($data)
	{
		$red_template = new Redtemplate();

		$row =& $this->getTable();
		if(isset($data['payment_methods']) && count($data['payment_methods'])>0)
		{
			$data['payment_methods'] = implode(',',$data['payment_methods']);
		}
		if(isset($data['shipping_methods']) && count($data['shipping_methods'])>0)
		{
			$data['shipping_methods'] = implode(',',$data['shipping_methods']);
		}
		if(isset($data['order_status']) && count($data['order_status'])>0)
		{
			$data['order_status'] = implode(',',$data['order_status']);
		}
		$data['template_name'] = strtolower($data['template_name']);
		$data['template_name'] = str_replace(" ","_",$data['template_name']);

		$tempate_file = $red_template->getTemplatefilepath($data['template_section'],$data['template_name'],true);

        JFile::write($tempate_file,$data["template_desc"]);
		/*$fp = fopen($tempate_file,"w");
		fwrite($fp,$data["template_desc"]);
		fclose($fp);*/


		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->shipping_methods = $row->shipping_methods? $row->shipping_methods : '';
		$row->payment_methods = $row->payment_methods? $row->payment_methods : '';
		$row->order_status = $row->order_status? $row->order_status : '';

		if($row->template_id)
	 	{
	 		$this->_id = $row->template_id;
	 		$this->_loadData();
	 		if($row->template_name!=$this->_data->template_name)
	 		{
	 			$tempate_file = $red_template->getTemplatefilepath($this->_data->template_section,$this->_data->template_name,true);
	 			unlink($tempate_file);
	 		}
	 	}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $row;
	}

	function delete($cid = array())
	{
		$red_template = new Redtemplate();
		if (count( $cid ))
		{
			for($i=0;$i<count($cid);$i++)
			{
				$query = 'SELECT * FROM '.$this->_table_prefix.'template WHERE template_id = '.$cid[$i];
				$this->_db->setQuery( $query );
				$rs = $this->_db->loadObject();

				$tempate_file = $red_template->getTemplatefilepath($rs->template_section,$rs->template_name,true);

				unlink($tempate_file);
			}

			$cids = implode( ',', $cid );

			$query = 'DELETE FROM '.$this->_table_prefix.'template WHERE template_id IN ( '.$cids.' )';
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
			$query = 'UPDATE '.$this->_table_prefix.'template'
					  . ' SET published = ' . intval( $publish )
					  . ' WHERE template_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	function copy($cid = array()){

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'SELECT * FROM '.$this->_table_prefix.'template WHERE template_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			$this->_copydata = $this->_db->loadObjectList();
		}
		foreach ($this->_copydata as $cdata){

			$post['template_id'] = 0;
			$post['template_name'] = 'Copy Of '.$cdata->template_name;
			$post['template_section'] = $cdata->template_section;
			$post['template_desc'] = $cdata->template_desc;
			$post['order_status'] = $cdata->order_status;
			$post['payment_methods'] = $cdata->payment_methods;
			$post['published'] = $cdata->published;
			$post['shipping_methods'] = $cdata->shipping_methods;

			template_detailModeltemplate_detail::store($post);
		}
		return true;

	}
	function availabletexts($section)
	{
		$query = 'SELECT * FROM '.$this->_table_prefix.'textlibrary WHERE published=1 AND section like "'.$section.'"';
		$this->_db->setQuery( $query );
		$this->textdata = $this->_db->loadObjectList();
		return $this->textdata;
	}
	function availableaddtocart($section)
	{
		$query = 'SELECT template_name FROM '.$this->_table_prefix.'template WHERE published=1 AND template_section = "'.$section.'"';
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}

	/**
	 * Method to checkout/lock the template_detail
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the helloworl detail out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	= JFactory::getUser();
				$uid	= (int) $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$template_detail = & $this->getTable('template_detail');


			if(!$template_detail->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}
	/**
	 * Method to checkin/unlock the template_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$template_detail = & $this->getTable('template_detail');
			if(! $template_detail->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}
	/**
	 * Tests if template_detail is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.5
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}
}

?>
