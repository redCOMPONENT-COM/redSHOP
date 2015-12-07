<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelAttributeprices_detail extends RedshopModel
{
	public $_id = null;

	public $_sectionid = null;

	public $_section = null;

	public $_data = null;


	public function __construct()
	{
		parent::__construct();

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
		$db = JFactory::getDbo();

		if (empty($this->_data))
		{
			if ($this->_section == "property")
			{
				$field = "ap.property_name ";
				$q = 'LEFT JOIN #__redshop_product_attribute_property AS ap ON p.section_id = ap.property_id ';
			}
			else
			{
				$field = "ap.subattribute_color_name AS property_name ";
				$q = 'LEFT JOIN #__redshop_product_subattribute_color AS ap ON p.section_id = ap.subattribute_color_id ';
			}

			$query = 'SELECT p.*, g.shopper_group_name, ' . $field . ' '
				. 'FROM #__redshop_product_attribute_price as p '
				. 'LEFT JOIN #__redshop_shopper_group as g ON p.shopper_group_id = g.shopper_group_id '
				. $q
				. 'WHERE p.price_id = ' . $this->_id;
			$db->setQuery($query);
			$this->_data = $db->loadObject();

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
		$db = JFactory::getDbo();
		$propertyid = $this->_sectionid;

		if ($this->_section == "property")
		{
			$q = 'SELECT * '
				. 'FROM #__redshop_product_attribute_property AS ap '
				. 'WHERE property_id = ' . $propertyid;
		}
		else
		{
			$q = 'SELECT ap.subattribute_color_name AS property_name '
				. 'FROM #__redshop_product_subattribute_color AS ap '
				. 'WHERE subattribute_color_id = ' . $propertyid;
		}

		$db->setQuery($q);
		$rs = $db->loadObject();

		return $rs;
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'DELETE FROM #__redshop_product_attribute_price '
				. 'WHERE price_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
