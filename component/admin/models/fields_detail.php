<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminImages');

class RedshopModelFields_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_fielddata = null;

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

		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__redshop_fields  WHERE field_id = ' . $this->_id;
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
			$detail                      = new stdClass;
			$detail->field_id            = 0;
			$detail->field_title         = null;
			$detail->wysiwyg             = null;
			$detail->field_type          = 0;
			$detail->field_name          = null;
			$detail->field_desc          = null;
			$detail->field_class         = null;
			$detail->field_section       = 0;
			$detail->field_maxlength     = 30;
			$detail->field_cols          = 10;
			$detail->field_rows          = 10;
			$detail->field_size          = 20;
			$detail->field_show_in_front = 0;
			$detail->required            = 0;
			$detail->published           = 1;
			$detail->display_in_product  = 0;
			$detail->display_in_checkout = 0;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$field_cid = $data['cid'][0];

		if (!$field_cid)
		{
			$data['ordering'] = $this->MaxOrdering();
		}

		return parent::store($data);
	}

	public function field_save($id, $post)
	{
		$db = JFactory::getDbo();
		$extra_field = new extra_field;
		$value_id = array();
		$extra_name = array();
		$extra_value = array();

		if (array_key_exists("value_id", $post))
		{
			$extra_value = JRequest::getVar('extra_value', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$value_id = $post["value_id"];

			if ($post["field_type"] == 11 || $post["field_type"] == 13)
			{
				$extra_name = JRequest::getVar('extra_name_file', '', 'files', 'array');
				$total = count($extra_name['name']);
			}
			else
			{
				$extra_name = JRequest::getVar('extra_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
				$total = count($extra_name);
			}
		}

		$filed_data_id = $extra_field->getFieldValue($id);

		if (count($filed_data_id) > 0)
		{
			$fid = array();

			foreach ($filed_data_id as $f)
			{
				$fid[] = $f->value_id;
			}

			$del_fid = array_diff($fid, $value_id);

			if (count($del_fid) > 0)
			{
				$this->field_delete($del_fid, 'value_id');
			}
		}

		for ($j = 0; $j < $total; $j++)
		{
			$set = "";

			if ($post["field_type"] == 11 || $post["field_type"] == 13)
			{
				if ($extra_value[$j] != "" && $extra_name['name'][$j] != "")
				{
					$filename = RedShopHelperImages::cleanFileName($extra_name['name'][$j]);

					$src = $extra_name['tmp_name'][$j];
					$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'extrafield/' . $filename;

					JFile::upload($src, $dest);

					$set = " field_name='" . $filename . "', ";
				}
			}
			else
			{
				$filename = $extra_name[$j];
				$set = " field_name='" . $filename . "', ";
			}

			if ($value_id[$j] == "")
			{
				$query = "INSERT INTO #__redshop_fields_value "
					. "(field_id,field_name,field_value) "
					. "VALUE ( '" . $id . "','" . $filename . "','" . $extra_value[$j] . "' ) ";
			}
			else
			{
				$query = "UPDATE #__redshop_fields_value "
					. "SET " . $set . " field_value='" . $extra_value[$j] . "' "
					. "WHERE value_id='" . $value_id[$j] . "' ";
			}

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}
	}

	public function field_delete($id, $field)
	{
		$db = JFactory::getDbo();
		$id = implode(',', $id);
		$query = 'DELETE FROM #__redshop_fields_value WHERE ' . $field . ' IN ( ' . $id . ' )';

		$db->setQuery($query);

		if (!$db->execute())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_fields WHERE field_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}

			// 	remove fields_data
			$query_field_data = 'DELETE FROM #__redshop_fields_data  WHERE fieldid IN ( ' . $cids . ' ) ';
			$db->setQuery($query_field_data);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());
			}
		}

		return true;
	}

	public function MaxOrdering()
	{
		$db = JFactory::getDbo();

		$query = "SELECT (count(*)+1) FROM #__redshop_fields";
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Method to get databse field name
	 *
	 * @access public
	 * @return boolean
	 */
	public function checkFieldname($field_name, $field_id)
	{
		$db = JFactory::getDbo();

		$query = "SELECT COUNT(*) AS cnt FROM #__redshop_fields "
			. "WHERE field_name='" . $field_name . "' "
			. "AND field_id!='" . $field_id . "' ";
		$db->setQuery($query);
		$result = $db->loadResult();

		return (boolean) $result;
	}
}
