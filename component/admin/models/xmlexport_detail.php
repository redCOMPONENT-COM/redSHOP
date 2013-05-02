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

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/xmlhelper.php';

class xmlexport_detailModelxmlexport_detail extends JModel
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
		$query = "SELECT x.* FROM " . $this->_table_prefix . "xml_export AS x "
			. "WHERE x.xmlexport_id=" . $this->_id;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();

		return (boolean) $this->_data;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->xmlexport_id = 0;
			$detail->filename = null;
			$detail->display_filename = null;
			$detail->parent_name = null;
			$detail->element_name = null;
			$detail->section_type = null;
			$detail->auto_sync = 0;
			$detail->sync_on_request = 0;
			$detail->auto_sync_interval = null;
			$detail->xmlexport_filetag = null;
			$detail->stock_element_name = null;
			$detail->xmlexport_stocktag = null;
			$detail->xmlexport_billingtag = null;
			$detail->billing_element_name = null;
			$detail->xmlexport_shippingtag = null;
			$detail->shipping_element_name = null;
			$detail->xmlexport_orderitemtag = null;
			$detail->orderitem_element_name = null;
			$detail->xmlexport_prdextrafieldtag = null;
			$detail->prdextrafield_element_name = null;
			$detail->published = 0;
			$detail->use_to_all_users = 1;
			$detail->xmlexport_on_category = null;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	 * Method to store the information
	 *
	 * @access public
	 * @return boolean
	 */
	public function store($data, $export = 0)
	{
		$resarray = array();
		$xmlhelper = new xmlHelper;

		$data['xmlexport_on_category'] = @ implode(',', $data['xmlexport_on_category']);
		$row =& $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->xmlexport_on_category)
		{
			$row->xmlexport_on_category = '';
		}

		$row->published = $data['xmlpublished'];

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$xmlexport_ip_id = $data['xmlexport_ip_id'];
		$access_ipaddress = $data['access_ipaddress'];

		for ($i = 0; $i < count($xmlexport_ip_id); $i++)
		{
			if ($access_ipaddress[$i] != "")
			{
				if ($xmlexport_ip_id[$i] != 0)
				{
					$query = "UPDATE " . $this->_table_prefix . "xml_export_ipaddress "
						. "SET access_ipaddress='" . $access_ipaddress[$i] . "' "
						. "WHERE xmlexport_ip_id='" . $xmlexport_ip_id[$i] . "' ";
				}
				else
				{
					$query = "INSERT INTO " . $this->_table_prefix . "xml_export_ipaddress "
						. "(xmlexport_id, access_ipaddress) "
						. "VALUES "
						. "('" . $row->xmlexport_id . "', '" . $access_ipaddress[$i] . "') ";
				}

				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		if ($export == 1)
		{
			$filename = $xmlhelper->writeXMLExportFile($row->xmlexport_id);
		}

		return $row;
	}

	/**
	 * Method to delete the records
	 *
	 * @access public
	 * @return boolean
	 */
	public function delete($cid = array())
	{
		$xmlhelper = new xmlHelper;

		if (count($cid))
		{
			$cids = implode(',', $cid);

			for ($i = 0; $i < count($cid); $i++)
			{
				$result = $xmlhelper->getXMLExportInfo($cid[$i]);
				$rootpath = JPATH_COMPONENT_SITE . "/assets/xmlfile/export/" .$result->filename;

				if (is_file($rootpath))
				{
					unlink($rootpath);
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'xml_export_log '
				. 'WHERE xmlexport_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'xml_export_ipaddress '
				. 'WHERE xmlexport_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'xml_export '
				. 'WHERE xmlexport_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function deleteIpAddress($xmlexport_ip_id = 0)
	{
		$query = 'DELETE FROM ' . $this->_table_prefix . 'xml_export_ipaddress '
			. 'WHERE xmlexport_ip_id IN (' . $xmlexport_ip_id . ')';
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function auto_syncpublish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'xml_export '
				. ' SET auto_sync = ' . intval($publish)
				. ' WHERE xmlexport_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function usetoallpublish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'xml_export '
				. ' SET use_to_all_users = ' . intval($publish)
				. ' WHERE xmlexport_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	/**
	 * Method to publish the records
	 *
	 * @access public
	 * @return boolean
	 */
	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = ' UPDATE ' . $this->_table_prefix . 'xml_export '
				. ' SET published = ' . intval($publish)
				. ' WHERE xmlexport_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getCategoryList()
	{
		$query = 'SELECT category_name AS text,category_id AS value FROM ' . $this->_table_prefix . 'category '
			. 'WHERE published=1 ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}
}
