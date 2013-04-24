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

require_once JPATH_COMPONENT . '/helpers/thumbnail.php';
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class tax_detailModeltax_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_tax_group_id = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';

		$array = JRequest::getVar('cid', 0, '', 'array');


		$_tax_group_id = JRequest::getVar('tax_group_id', 0, '');
		$this->setId((int) $array[0], $_tax_group_id);

	}

	public function setId($id, $_tax_group_id)
	{
		$this->_id = $id;
		$this->_tax_group_id = $_tax_group_id;
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
			$query = ' SELECT tr.*,tg.tax_group_name  '
				. ' FROM ' . $this->_table_prefix . 'tax_rate as tr'
				. ' LEFT JOIN ' . $this->_table_prefix . 'tax_group as tg ON tr.tax_group_id = tg.tax_group_id '
				. ' WHERE tr.tax_rate_id = ' . $this->_id;
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
			$detail->tax_rate_id = 0;
			$detail->tax_state = null;
			$detail->tax_country = null;
			$detail->mdate = 0;
			$detail->tax_rate = null;
			$detail->tax_group_id = $this->_tax_group_id;
			$detail->is_eu_country = 0;

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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'tax_rate WHERE tax_rate_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
