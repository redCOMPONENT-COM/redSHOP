<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminThumbnail');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class RedshopModelTax_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_tax_group_id = null;

	public function __construct()
	{
		parent::__construct();

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
				. ' FROM #__redshop_tax_rate as tr'
				. ' LEFT JOIN #__redshop_tax_group as tg ON tr.tax_group_id = tg.tax_group_id '
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

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_tax_rate WHERE tax_rate_id IN ( ' . $cids . ' )';
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
