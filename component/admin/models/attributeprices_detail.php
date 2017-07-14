<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelAttributeprices_detail extends RedshopModel
{
	public $_id = null;

	public $_sectionid = null;

	public $_section = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->_sectionid = JRequest::getVar('section_id', 0, '', 'int');
		$this->_section = JRequest::getVar('section');

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
			if ($this->_section == "property")
			{
				$field = "ap.property_name ";
				$q = 'LEFT JOIN ' . $this->_table_prefix . 'product_attribute_property AS ap ON p.section_id = ap.property_id ';
			}
			else
			{
				$field = "ap.subattribute_color_name AS property_name ";
				$q = 'LEFT JOIN ' . $this->_table_prefix . 'product_subattribute_color AS ap ON p.section_id = ap.subattribute_color_id ';
			}

			$query = 'SELECT p.*, g.shopper_group_name, ' . $field . ' '
				. 'FROM ' . $this->_table_prefix . 'product_attribute_price as p '
				. 'LEFT JOIN ' . $this->_table_prefix . 'shopper_group as g ON p.shopper_group_id = g.shopper_group_id '
				. $q
				. 'WHERE p.price_id = ' . $this->_id;
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
			$detail->price_id = 0;
			$detail->section_id = $this->_sectionid;
			$detail->product_price = 0.00;
			$detail->product_currency = null;
			$detail->shopper_group_id = 0;
			$detail->price_quantity_start = 0;
			$detail->price_quantity_end = 0;
			$detail->discount_price = 0;
			$detail->discount_start_date = 0;
			$detail->discount_end_date = 0;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function getPropertyName()
	{
		$propertyid = $this->_sectionid;

		if ($this->_section == "property")
		{
			$q = 'SELECT * '
				. 'FROM ' . $this->_table_prefix . 'product_attribute_property AS ap '
				. 'WHERE property_id = ' . $propertyid;
		}
		else
		{
			$q = 'SELECT ap.subattribute_color_name AS property_name '
				. 'FROM ' . $this->_table_prefix . 'product_subattribute_color AS ap '
				. 'WHERE subattribute_color_id = ' . $propertyid;
		}

		$this->_db->setQuery($q);
		$rs = $this->_db->loadObject();

		return $rs;
	}

	public function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_price '
				. 'WHERE price_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
