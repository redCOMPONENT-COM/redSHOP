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
jimport('joomla.filesystem.file');
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

class wrapper_detailModelwrapper_detail extends JModel
{
	public $_id = null;

	public $_productid = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->_sectionid = JRequest::getVar('product_id', 0, '', 'int');
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
			$query = 'SELECT p.*, w.* '
				. 'FROM ' . $this->_table_prefix . 'wrapper as w '
				. 'LEFT JOIN ' . $this->_table_prefix . 'product as p ON p.product_id = w.product_id '
				. 'WHERE w.wrapper_id = ' . $this->_id;
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
			$detail->wrapper_id = 0;
			$detail->product_id = $this->_productid;
			$detail->category_id = 0;
			$detail->wrapper_price = 0.00;
			$detail->wrapper_name = null;
			$detail->wrapper_image = null;
			$detail->published = 1;
			$detail->wrapper_use_to_all = 0;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function getProductName($productid)
	{
		$q = 'SELECT product_name '
			. 'FROM ' . $this->_table_prefix . 'product '
			. 'WHERE product_id = ' . $productid;
		$this->_db->setQuery($q);
		$pname = $this->_db->loadResult();

		return $pname;
	}

	public function getProductInfo($productid = 0)
	{
		$query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix .
			'product WHERE published = 1 and product_id in   (' . $productid . ')';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getCategoryName($categoryid)
	{
		$q = 'SELECT category_name '
			. 'FROM ' . $this->_table_prefix . 'category '
			. 'WHERE category_id = ' . $categoryid;
		$this->_db->setQuery($q);
		$name = $this->_db->loadResult();

		return $name;
	}

	public function getCategoryInfo($categoryid = 0)
	{
		$and = '';

		if ($categoryid != 0)
		{
			$and = 'WHERE category_id = ' . $categoryid;
		}

		$q = 'SELECT * '
			. 'FROM ' . $this->_table_prefix . 'category '
			. $and;
		$this->_db->setQuery($q);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getProductInfowrapper($productid = 0)
	{
		if ($productid)
		{
			$query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix
				. 'product WHERE published = 1 and product_id in   (' . $productid . ')';
		}
		else
		{
			$query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix .
				'product WHERE published = 1 and product_id =""';
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getMultiselectBox($name, $list, $sellist, $displayid, $displayname, $multiple = false)
	{
		$multiple = $multiple ? "multiple='multiple'" : "";
		$id = str_replace('[]', '', $name);
		$html = "<select class='inputbox' size='10' " . $multiple . " name='" . $name . "' id='" . $id . "'>";

		for ($i = 0; $i < count($list); $i++)
		{
			$selected = '';

			for ($j = 0; $j < count($sellist); $j++)
			{
				if ($sellist[$j] == $list[$i]->$displayid)
				{
					$selected = 'selected';
					break;
				}
			}

			$html .= "<option $selected value='" . $list[$i]->$displayid . "'>" . $list[$i]->$displayname . "</option>";
		}

		$html .= "</select>";

		return $html;
	}

	public function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$wrapperfile = JRequest::getVar('wrapper_image', '', 'files', 'array');
		$wrapperimg = "";

		if ($wrapperfile['name'] != "")
		{
			$wrapperimg = JPath::clean(time() . '_' . $wrapperfile['name']);

			$src = $wrapperfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/wrapper/' . $wrapperimg;

			if ($data['wrapper_name'] == "")
			{
				$data['wrapper_name'] = $wrapperimg;
			}

			$row->wrapper_image = $wrapperimg;
			JFile::upload($src, $dest);
		}

		if ($row->wrapper_id)
		{
			$productobj = new producthelper;
			$wrapper = $productobj->getWrapper($row->product_id, $row->wrapper_id);

			if (count($wrapper) > 0 && $wrapperimg != "")
			{
				$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/thumb/' . $wrapper[0]->wrapper_image;

				if (is_file($unlink_path))
				{
					unlink($unlink_path);
				}

				$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $wrapper[0]->wrapper_image;

				if (is_file($unlink_path))
				{
					unlink($unlink_path);
				}
			}
		}

		$categoryid = 0;

		if (count(JRequest::getvar('categoryid')) > 0)
		{
			$categoryid = implode(",", $_POST['categoryid']);
		}

		$row->category_id = $categoryid;

		$productid = $data['product_id'];

		if (count($productid) > 0)
		{
			$productid = implode(",", $productid);
		}

		$row->product_id = $productid;

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
			$query = 'DELETE FROM ' . $this->_table_prefix . 'wrapper '
				. 'WHERE wrapper_id IN ( ' . $cids . ' )';
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

			$query = ' UPDATE ' . $this->_table_prefix . 'wrapper '
				. ' SET published = ' . intval($publish)
				. ' WHERE wrapper_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function enable_defaultpublish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = ' UPDATE ' . $this->_table_prefix . 'wrapper '
				. ' SET wrapper_use_to_all = ' . intval($publish)
				. ' WHERE wrapper_id IN ( ' . $cids . ' )';
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
