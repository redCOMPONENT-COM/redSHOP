<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;


class RedshopModelCurrency_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'currency WHERE currency_id = ' . $this->_id;
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

			$detail->currency_id = 0;
			$detail->currency_name = null;
			$detail->currency_code = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
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

		return $row;
	}

	public function delete($cid = array())
	{
		if (empty($cid))
		{
			return false;
		}

		$app = JFactory::getApplication();

		if (is_string($cid))
		{
			$cid = implode(',', $cid);
		}
		elseif (is_numeric($cid))
		{
			$cid = array($cid);
		}

		$cid = ArrayHelper::toInteger($cid);
		$notAllow = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');

		foreach ($cid as $currencyId)
		{
			$table = $this->getTable();

			if (!$table->load($currencyId))
			{
				return false;
			}

			if ($notAllow && $table->currency_code == $notAllow)
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_CURRENCY_ERROR_DELETE_CURRENCY_SET_IN_CONFIG'), 'error');

				return false;
			}

			if (!$table->delete())
			{
				$app->enqueueMessage(JText::sprintf('COM_REDSHOP_CURRENCY_ERROR_DELETE', $table->currency_name), 'error');

				return false;
			}
		}

		return true;
	}
}
