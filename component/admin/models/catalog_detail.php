<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelCatalog_detail extends RedshopModel
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
		$db = JFactory::getDbo();
		$layout = JRequest::getVar('layout', 'default');

		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__redshop_catalog WHERE catalog_id=' . $this->_id;

			$db->setQuery($query);
			$this->_data = $db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		$layout = JRequest::getVar('layout', 'default');

		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->catalog_id = null;
			$detail->catalog_name = null;

			$detail->published = 1;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();
		$layout = JRequest::getVar('layout');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_catalog WHERE catalog_id IN ( ' . $cids . ' )';

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
		$db = JFactory::getDbo();
		$layout = JRequest::getVar('layout');

		if (count($cid))
		{
			$cids = implode(',', $cid);


			$query = 'UPDATE #__redshop_catalog'
				. ' SET published = ' . intval($publish)
				. ' WHERE catalog_id IN ( ' . $cids . ' )';

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function color_Data($sample_id)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM #__redshop_catalog_colour  WHERE sample_id=' . $sample_id;
		$db->setQuery($query);

		return $db->loadObjectlist();
	}
}
