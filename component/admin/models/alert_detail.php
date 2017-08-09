<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelAlert_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_shoppers = null;

	public function __construct()
	{
		parent::__construct();

		$array = JFactory::getApplication()->input->get('cid', 0, 'array');

		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$db = $this->_db;

			$conditions = array(
				$db->qn('id') . ' IN (' . $cids . ' )'
			);

			$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_alerts'))
				->where($conditions);

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function read($cid = array(), $read = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$db = $this->_db;

			$fields = array(
				$db->qn('read') . ' = ' . $db->q((int) $read)
			);

			// Conditions for which records should be updated.
			$conditions = array(
				$db->qn('id') . ' IN (' . $cids . ')'
			);

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_alerts'))
				->set($fields)
				->where($conditions);

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
