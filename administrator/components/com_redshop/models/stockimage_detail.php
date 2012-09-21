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


class stockimage_detailModelstockimage_detail extends JModel
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
			$query = 'SELECT * FROM '.$this->_table_prefix.'stockroom_amount_image AS si '
					.'WHERE stock_amount_id="'.$this->_id.'" ';
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
			$detail->stock_amount_id			= 0;
			$detail->stockroom_id				= 0;
			$detail->stock_option				= null;
			$detail->stock_quantity				= 0;
			$detail->stock_amount_image			= null;
			$detail->stock_amount_image_tooltip	= null;
			$this->_data		 				= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	function store($data)
	{
		$row =& $this->getTable('stockimage_detail');
		$file =& JRequest::getVar('stock_amount_image', '', 'files', 'array' );
		if($_FILES['stock_amount_image']['name']!="")
		{
			$ext = explode(".",$file['name']);
			$filetmpname = substr( $file['name'], 0, strlen($file['name'])-strlen($ext[count($ext)-1]) );

			$filename = JPath::clean(time().'_'.$filetmpname."jpg"); //Make the filename unique
			$row->stock_amount_image=$filename;

			$src=$file['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH.'stockroom'.DS.$filename;
			JFile::upload($src,$dest);
			
			if(isset($data['stock_image'])!="" && is_file(REDSHOP_FRONT_IMAGES_RELPATH.'stockroom'.DS.$data['stock_image']))
			{
				unlink(REDSHOP_FRONT_IMAGES_RELPATH.'stockroom'.DS.$data['stock_image'] );
			}
		}
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
			
			for($i=0;$i<count($cid);$i++)
			{
				$query = 'SELECT stock_amount_image FROM '.$this->_table_prefix.'stockroom_amount_image AS si '
						.'WHERE stock_amount_id="'.$cid[$i].'" ';
				$this->_db->setQuery($query);
				$stock_amount_image = $this->_db->loadResult();
				if($stock_amount_image!="" && is_file(REDSHOP_FRONT_IMAGES_RELPATH.'stockroom'.DS.$stock_amount_image))
				{
					unlink(REDSHOP_FRONT_IMAGES_RELPATH.'stockroom'.DS.$stock_amount_image);
				}
			}
			
			$query = 'DELETE FROM '.$this->_table_prefix.'stockroom_amount_image '
					.'WHERE stock_amount_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	
	function getStockAmountOption($select=0)
	{
		$option = array();
		$option[]   = JHTML::_('select.option', 0,JText::_('COM_REDSHOP_SELECT'));
		$option[]   = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_HIGHER_THAN'));
		$option[]   = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_EQUAL'));
		$option[]   = JHTML::_('select.option', 3, JText::_('COM_REDSHOP_LOWER_THAN'));
		if($select!=0)
		{
			$option = $option[$select]->text;
		}
		return $option;
	}
	
	function getStockRoomList()
	{
		$query = 'SELECT s.stockroom_id AS value, s.stockroom_name AS text,s.* FROM '.$this->_table_prefix.'stockroom AS s ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();
		return $list;
	}

	/*function publish($cid = array(), $publish = 1)
	{		
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			$query = 'UPDATE '.$this->_table_prefix.'stockroom_amount_image '
					.'SET published="'.intval( $publish ).'" '
					.'WHERE stock_amount_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}*/
}?>