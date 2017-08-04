<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class RedshopModelWrapper_detail extends RedshopModel
{
	public $_id = null;

	public $_productid = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';

		/**
		 * Only setup ID from cid if not add task
		 * TODO Refactor this form into right Joomla! standard
		 */
		$jinput = JFactory::getApplication()->input;

		if ($jinput->getCmd('task') != 'add')
		{
			$array = $jinput->getInt('cid', 0);

			// Set record Id from cid
			$this->setId((is_array($array)) ? (int) $array[0] : $array);
		}
		else
		{
			$this->setId(0);
		}

		$this->_sectionid = JRequest::getVar('product_id', 0, '', 'int');
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

	public function getCategoryName($categoryId)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('id') . ' = ' . $db->q((int) $categoryId));

		return $db->setQuery($query)->loadResult();
	}

	public function getCategoryInfo($categoryId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_category'))
			->where($db->qn('level') . ' > 0');

		if ($categoryId > 0)
		{
			$query->where($db->qn('id') . ' = ' . $db->q((int) $categoryId));
		}

		return $db->setQuery($query)->loadObjectList();
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

		for ($i = 0, $in = count($list); $i < $in; $i++)
		{
			$selected = '';

			for ($j = 0, $jn = count($sellist); $j < $jn; $j++)
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
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$jinput      = JFactory::getApplication()->input;
		$wrapperfile = $jinput->files->get('wrapper_image', '', 'array');
		$wrapperimg  = "";

		if ($wrapperfile['name'] != "")
		{
			$wrapperimg = RedShopHelperImages::cleanFileName($wrapperfile['name']);

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
			$productobj = productHelper::getInstance();
			$wrapper = $productobj->getWrapper($row->product_id, $row->wrapper_id);

			if (count($wrapper) > 0 && $wrapperimg != "")
			{
				$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/thumb/' . $wrapper[0]->wrapper_image;

				if (JFile::exists($unlink_path))
				{
					JFile::delete($unlink_path);
				}

				$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $wrapper[0]->wrapper_image;

				if (JFile::exists($unlink_path))
				{
					JFile::delete($unlink_path);
				}
			}
		}

		$categoryid = 0;

		if (count($jinput->get('categoryid')) > 0)
		{
			$categoryid = implode(",", $_POST['categoryid']);
		}

		$row->category_id = $categoryid;

		$row->product_id = $data['container_product'];

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

			$query = ' UPDATE ' . $this->_table_prefix . 'wrapper '
				. ' SET published = ' . intval($publish)
				. ' WHERE wrapper_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
