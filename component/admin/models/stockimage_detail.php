<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelStockimage_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		$array = JFactory::getApplication()->input->get('cid', 0, 'array');
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom_amount_image AS si '
				. 'WHERE stock_amount_id="' . $this->_id . '" ';
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->stock_amount_id = 0;
			$detail->stockroom_id = 0;
			$detail->stock_option = null;
			$detail->stock_quantity = 0;
			$detail->stock_amount_image = null;
			$detail->stock_amount_image_tooltip = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable('stockimage_detail');
		$file = JFactory::getApplication()->input->files->get('stock_amount_image', '', 'array');

		if ($_FILES['stock_amount_image']['name'] != "")
		{
			$ext = explode(".", $file['name']);
			$filetmpname = substr($file['name'], 0, strlen($file['name']) - strlen($ext[count($ext) - 1]));

			$filename = RedShopHelperImages::cleanFileName($filetmpname . 'jpg');
			$row->stock_amount_image = $filename;

			$src = $file['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom/' . $filename;
			JFile::upload($src, $dest);

			if (isset($data['stock_image']) != "" && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom/' . $data['stock_image']))
			{
				JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom/' . $data['stock_image']);
			}
		}

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			for ($i = 0, $in = count($cid); $i < $in; $i++)
			{
				$query = 'SELECT stock_amount_image FROM ' . $this->_table_prefix . 'stockroom_amount_image AS si '
					. 'WHERE stock_amount_id="' . $cid[$i] . '" ';
				$this->_db->setQuery($query);
				$stock_amount_image = $this->_db->loadResult();

				if ($stock_amount_image != "" && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom/' . $stock_amount_image))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'stockroom/' . $stock_amount_image);
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'stockroom_amount_image '
				. 'WHERE stock_amount_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getStockAmountOption($select = 0)
	{
		$option = array();
		$option[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$option[] = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_HIGHER_THAN'));
		$option[] = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_EQUAL'));
		$option[] = JHTML::_('select.option', 3, JText::_('COM_REDSHOP_LOWER_THAN'));

		if ($select != 0)
		{
			$option = $option[$select]->text;
		}

		return $option;
	}

	public function getStockRoomList()
	{
		$query = 'SELECT s.stockroom_id AS value, s.stockroom_name AS text,s.* FROM ' . $this->_table_prefix . 'stockroom AS s ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}
}
