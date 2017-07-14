<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopModelXmlimport_detail extends RedshopModel
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
		$post = JRequest::get('post');

		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		if (isset($post['display_filename']))
		{
			$this->_data->display_filename = $post['display_filename'];
		}

		if (isset($post['auto_sync']))
		{
			$this->_data->auto_sync = $post['auto_sync'];
		}

		if (isset($post['sync_on_request']))
		{
			$this->_data->sync_on_request = $post['sync_on_request'];
		}

		if (isset($post['auto_sync_interval']))
		{
			$this->_data->auto_sync_interval = $post['auto_sync_interval'];
		}

		if (isset($post['xmlpublished']))
		{
			$this->_data->published = $post['xmlpublished'];
		}

		if (isset($post['override_existing']))
		{
			$this->_data->override_existing = $post['override_existing'];
		}

		if (isset($post['add_prefix_for_existing']))
		{
			$this->_data->add_prefix_for_existing = $post['add_prefix_for_existing'];
		}

		if (isset($post['element_name']))
		{
			$this->_data->element_name = $post['element_name'];
		}

		if (isset($post['billing_element_name']))
		{
			$this->_data->billing_element_name = $post['billing_element_name'];
		}

		if (isset($post['shipping_element_name']))
		{
			$this->_data->shipping_element_name = $post['shipping_element_name'];
		}

		if (isset($post['orderitem_element_name']))
		{
			$this->_data->orderitem_element_name = $post['orderitem_element_name'];
		}

		if (isset($post['stock_element_name']))
		{
			$this->_data->stock_element_name = $post['stock_element_name'];
		}

		if (isset($post['prdextrafield_element_name']))
		{
			$this->_data->prdextrafield_element_name = $post['prdextrafield_element_name'];
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$query = "SELECT x.* FROM " . $this->_table_prefix . "xml_import AS x "
			. "WHERE x.xmlimport_id=" . $this->_id;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();

		return (boolean) $this->_data;
	}

	public function getXMLImporturl()
	{
		return $this->_data->xmlimport_url;
	}

	public function updateFile()
	{
		$post = JRequest::get('post');

		$xmlimport_url = $this->_data->xmlimport_url;
		$file = JRequest::getVar('filename_url', '', 'files', 'array');

		if (array_key_exists("xmlimport_url", $post) && $post["xmlimport_url"] != "")
		{
			$xmlimport_url = $post['xmlimport_url'];
			$this->_data->xmlimport_url = $post['xmlimport_url'];
		}
		elseif (array_key_exists("name", $file) && $file['name'] != "" && $file['type'] == "text/xml")
		{
			$src = $file['tmp_name'];
			$destpath = JPATH_COMPONENT_SITE . "/assets/xmlfile/import";

			$filename = JPath::clean($file['name']);
			$dest = $destpath . '/' . $filename;

			JFile::upload($src, $dest);
			$xmlimport_url = $dest;
		}

		elseif ($this->_data->filename != "" && JFile::exists(JPATH_COMPONENT_SITE . '/assets/xmlfile/import/' .$this->_data->filename))
		{
			$xmlimport_url = JPATH_COMPONENT_SITE . '/assets/xmlfile/import/' .$this->_data->filename;
		}

		return $xmlimport_url;
	}

	public function _initData()
	{
		$user = JFactory::getUser();

		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->xmlimport_id = 0;
			$detail->filename = null;
			$detail->display_filename = null;
			$detail->xmlimport_url = null;
			$detail->section_type = null;
			$detail->auto_sync = 0;
			$detail->sync_on_request = 0;
			$detail->element_name = null;
			$detail->billing_element_name = null;
			$detail->shipping_element_name = null;
			$detail->orderitem_element_name = null;
			$detail->stock_element_name = null;
			$detail->prdextrafield_element_name = null;
			$detail->auto_sync_interval = null;
			$detail->override_existing = 0;
			$detail->add_prefix_for_existing = null;
			$detail->xmlimport_filetag = null;
			$detail->xmlimport_billingtag = null;
			$detail->xmlimport_shippingtag = null;
			$detail->xmlimport_orderitemtag = null;
			$detail->xmlimport_stocktag = null;
			$detail->xmlimport_prdextrafieldtag = null;
			$detail->published = 0;
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
	public function store($data, $import = 0)
	{
		$xmlhelper = new xmlHelper;
		$resarray = array();

		if (array_key_exists("xmlfiletag", $data))
		{
			$xmlfiletag = $data['xmlfiletag'];
			$updatefiletag = (isset($data['updatefiletag'])) ? $data['updatefiletag'] : array();

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$xmltag = trim($data[$xmlfiletag[$i]]);
				$updatetag = (isset($updatefiletag[$i]) && $updatefiletag[$i] == 1) ? 1 : 0;
				$resarray[] = $xmlfiletag[$i] . "=" . $xmltag . "=" . $updatetag;
			}
		}

		$data['xmlimport_filetag'] = implode(";", $resarray);

		$resarray = array();

		if (array_key_exists("xmlbillingtag", $data))
		{
			$xmlfiletag = $data['xmlbillingtag'];
			$updatefiletag = (isset($data['updatebillingtag'])) ? $data['updatebillingtag'] : array();

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$xmltag = trim($data["bill_" . $xmlfiletag[$i]]);
				$updatetag = (isset($updatefiletag[$i]) && $updatefiletag[$i] == 1) ? 1 : 0;
				$resarray[] = $xmlfiletag[$i] . "=" . $xmltag . "=" . $updatetag;
			}
		}

		$data['xmlimport_billingtag'] = implode(";", $resarray);

		$resarray = array();

		if (array_key_exists("xmlshippingtag", $data))
		{
			$xmlfiletag = $data['xmlshippingtag'];
			$updatefiletag = (isset($data['updateshippingtag'])) ? $data['updateshippingtag'] : array();

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$xmltag = trim($data["shipp_" . $xmlfiletag[$i]]);
				$updatetag = (isset($updatefiletag[$i]) && $updatefiletag[$i] == 1) ? 1 : 0;
				$resarray[] = $xmlfiletag[$i] . "=" . $xmltag . "=" . $updatetag;
			}
		}

		$data['xmlimport_shippingtag'] = implode(";", $resarray);

		$resarray = array();

		if (array_key_exists("xmlitemtag", $data))
		{
			$xmlfiletag = $data['xmlitemtag'];
			$updatefiletag = (isset($data['updateitemtag'])) ? $data['updateitemtag'] : array();

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$xmltag = trim($data["item_" . $xmlfiletag[$i]]);
				$updatetag = (isset($updatefiletag[$i]) && $updatefiletag[$i] == 1) ? 1 : 0;
				$resarray[] = $xmlfiletag[$i] . "=" . $xmltag . "=" . $updatetag;
			}
		}

		$data['xmlimport_orderitemtag'] = implode(";", $resarray);

		$resarray = array();

		if (array_key_exists("xmlstocktag", $data))
		{
			$xmlfiletag = $data['xmlstocktag'];
			$updatefiletag = (isset($data['updatestocktag'])) ? $data['updatestocktag'] : array();

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$xmltag = trim($data["stock_" . $xmlfiletag[$i]]);
				$updatetag = (isset($updatefiletag[$i]) && $updatefiletag[$i] == 1) ? 1 : 0;
				$resarray[] = $xmlfiletag[$i] . "=" . $xmltag . "=" . $updatetag;
			}
		}

		$data['xmlimport_stocktag'] = implode(";", $resarray);

		$resarray = array();

		if (array_key_exists("xmlprdextrafieldtag", $data))
		{
			$xmlfiletag = $data['xmlprdextrafieldtag'];
			$updatefiletag = (isset($data['updateprdexttag'])) ? $data['updateprdexttag'] : array();

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$xmltag = trim($data["prdext_" . $xmlfiletag[$i]]);
				$updatetag = (isset($updatefiletag[$i]) && $updatefiletag[$i] == 1) ? 1 : 0;
				$resarray[] = $xmlfiletag[$i] . "=" . $xmltag . "=" . $updatetag;
			}
		}

		$data['xmlimport_prdextrafieldtag'] = implode(";", $resarray);

		if ($data['override_existing'] == 0 && trim($data['add_prefix_for_existing']) == "")
		{
			$data['add_prefix_for_existing'] = "xml_";
		}

		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$row->published = $data['xmlpublished'];

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$filename = $xmlhelper->writeXMLImportFile($row->xmlimport_id, $data['tmpxmlimport_url']);

		if ($import == 1)
		{
			$xmlhelper->importXMLFile($row->xmlimport_id);
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

			for ($i = 0, $in = count($cid); $i < $in; $i++)
			{
				$result = $xmlhelper->getXMLImportInfo($cid[$i]);
				$rootpath = JPATH_COMPONENT_SITE . "/assets/xmlfile/import/" .$result->filename;

				if (JFile::exists($rootpath))
				{
					JFile::delete($rootpath);
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'xml_import_log '
				. 'WHERE xmlimport_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'xml_import '
				. 'WHERE xmlimport_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function auto_syncpublish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'xml_import '
				. ' SET auto_sync = ' . intval($publish)
				. ' WHERE xmlimport_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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

			$query = ' UPDATE ' . $this->_table_prefix . 'xml_import '
				. ' SET published = ' . intval($publish)
				. ' WHERE xmlimport_id IN ( ' . $cids . ' )';
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
