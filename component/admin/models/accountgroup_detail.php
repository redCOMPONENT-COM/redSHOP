<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelAccountgroup_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public function __construct()
	{
		parent::__construct();

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
			$db = JFactory::getDbo();
			$query = 'SELECT * FROM #__redshop_economic_accountgroup '
				. 'WHERE accountgroup_id = ' . (int) $this->_id . ' ';
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

			$detail->accountgroup_id = 0;
			$detail->accountgroup_name = null;
			$detail->economic_vat_account = null;
			$detail->economic_nonvat_account = null;
			$detail->economic_discount_vat_account = null;
			$detail->economic_discount_nonvat_account = null;
			$detail->economic_shipping_vat_account = null;
			$detail->economic_shipping_nonvat_account = null;
			$detail->economic_discount_product_number = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			// Sanitise ids
			JArrayHelper::toInteger($cid);
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_economic_accountgroup '
				. 'WHERE accountgroup_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$db = JFactory::getDbo();

			// Sanitise ids
			JArrayHelper::toInteger($cid);
			$cids = implode(',', $cid);

			$query = 'UPDATE #__redshop_economic_accountgroup'
				. ' SET published = ' . (int) $publish . ' '
				. ' WHERE accountgroup_id IN ( ' . $cids . ' )';
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
