<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class giftcard_detailModelgiftcard_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');

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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'giftcard WHERE giftcard_id = ' . $this->_id;
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
			$detail->giftcard_id = 0;
			$detail->giftcard_name = null;
			$detail->giftcard_validity = null;
			$detail->giftcard_date = null;
			$detail->giftcard_bgimage = null;
			$detail->giftcard_image = null;
			$detail->giftcard_price = 0;
			$detail->giftcard_value = 0;
			$detail->published = 1;
			$detail->customer_amount = 0;
			$detail->giftcard_desc = null;
			$this->_data = $detail;


			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$giftcardfile = JRequest::getVar('giftcard_image', '', 'files', 'array');
		$giftcardimg = "";

		if ($giftcardfile['name'] != "")
		{
			$giftcardfile['name'] = str_replace(" ", "_", $giftcardfile['name']);
			$giftcardimg = JPath::clean(time() . '_' . $giftcardfile['name']);

			$src = $giftcardfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftcardimg;

			$row->giftcard_image = $giftcardimg;
			JFile::upload($src, $dest);
		}

		$giftcardbgfile = JRequest::getVar('giftcard_bgimage', '', 'files', 'array');
		$giftcardbgimg = "";

		if ($giftcardbgfile['name'] != "")
		{
			$giftcardbgfile['name'] = str_replace(" ", "_", $giftcardbgfile['name']);
			$giftcardbgimg = JPath::clean(time() . '_' . $giftcardbgfile['name']);
			$src = $giftcardbgfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftcardbgimg;

			$row->giftcard_bgimage = $giftcardbgimg;
			JFile::upload($src, $dest);
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic;

			$giftdata = new stdClass;
			$giftdata->product_id = $row->giftcard_id;
			$giftdata->product_number = "gift_" . $row->giftcard_id . "_" . $row->giftcard_name;
			$giftdata->product_name = $row->giftcard_name;
			$giftdata->product_price = $row->giftcard_price;
			$giftdata->accountgroup_id = $row->accountgroup_id;
			$giftdata->product_volume = 0;

			$ecoProductNumber = $economic->createProductInEconomic($giftdata);

		}

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'giftcard WHERE giftcard_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'giftcard'
				. ' SET published = ' . intval($publish)
				. ' WHERE giftcard_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function copy($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'giftcard WHERE giftcard_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$this->_copydata = $this->_db->loadObjectList();
		}

		foreach ($this->_copydata as $cdata)
		{
			$post['giftcard_id'] = 0;
			$post['giftcard_name'] = JText::_('COM_REDSHOP_COPY_OF') . ' ' . $cdata->giftcard_name;
			$post['giftcard_validity'] = $cdata->giftcard_validity;
			$post['giftcard_date'] = $cdata->giftcard_date;
			$post['giftcard_bgimage'] = $cdata->giftcard_bgimage;
			$post['giftcard_image'] = $cdata->giftcard_image;
			$post['published'] = $cdata->published;
			$post['giftcard_price'] = $cdata->giftcard_price;
			$post['giftcard_value'] = $cdata->giftcard_value;
			$post['giftcard_desc'] = $cdata->giftcard_desc;
			$post['customer_amount'] = $cdata->customer_amount;

			$this->store($post);
		}

		return true;
	}
}


