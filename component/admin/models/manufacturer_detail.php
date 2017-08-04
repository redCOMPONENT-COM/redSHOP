<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelManufacturer_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public $_templatedata = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JFactory::getApplication()->input->get('cid', 0, 'array');

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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id = ' . $this->_id;
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
			$detail->manufacturer_id = 0;
			$detail->manufacturer_name = null;
			$detail->manufacturer_desc = null;
			$detail->manufacturer_email = null;
			$detail->manufacturer_url = null;
			$detail->product_per_page = 0;
			$detail->template_id = 0;
			$detail->metakey = null;
			$detail->metadesc = null;
			$detail->metalanguage_setting = null;
			$detail->metarobot_info = null;
			$detail->pagetitle = null;
			$detail->pageheading = null;
			$detail->sef_url = null;
			$detail->excluding_category_list = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}
		return true;
	}

	public function store($data)
	{
		$order_functions = order_functions::getInstance();
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
		{
			$data['excluding_category_list'] = @ implode(',', $data['excluding_category_list']);
		}

		$row = $this->getTable();

		if ($data['manufacturer_id'] == 0)
		{
			$data['ordering'] = $this->MaxOrdering();
		}

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$isNew = ($row->manufacturer_id > 0) ? false : true;
		JPluginHelper::importPlugin('redshop_product');

		$result = JDispatcher::getInstance()->trigger('onBeforeManufacturerSave', array(&$row, $isNew));

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
		{
			if (!$row->excluding_category_list)
			{
				$row->excluding_category_list = '';
			}
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		JDispatcher::getInstance()->trigger('onAfterManufacturerSave', array(&$row, $isNew));

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			JPluginHelper::importPlugin('redshop_product');
			JDispatcher::getInstance()->trigger('onAfterManufacturerDelete', array($cid));
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'manufacturer'
				. ' SET published = ' . intval($publish)
				. ' WHERE manufacturer_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		return true;
	}

	public function copy($cid = array())
	{

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$this->_copydata = $this->_db->loadObjectList();
		}

		foreach ($this->_copydata as $cdata)
		{
			$post['manufacturer_id'] = 0;
			$post['manufacturer_name'] = $this->renameToUniqueValue('manufacturer_name', $cdata->manufacturer_name);
			$post['manufacturer_desc'] = $cdata->manufacturer_desc;
			$post['manufacturer_email'] = $cdata->manufacturer_email;
			$post['product_per_page'] = $cdata->product_per_page;
			$post['template_id'] = $cdata->template_id;
			$post['metakey'] = $cdata->metakey;
			$post['metadata'] = $cdata->metadata;
			$post['metadesc'] = $cdata->metadesc;
			$post['excluding_category_list'] = $cdata->excluding_category_list;
			$post['published'] = $cdata->published;

			$this->store($post);
		}

		return true;
	}

	public function TemplateData()
	{
		$query = "SELECT template_id as value,template_name as text FROM " . $this->_table_prefix
			. "template WHERE template_section ='manufacturer_products' and published=1";
		$this->_db->setQuery($query);
		$this->_templatedata = $this->_db->loadObjectList();

		return $this->_templatedata;
	}

	public function getMediaId($mid)
	{
		$query = 'SELECT media_id,media_name,media_alternate_text FROM ' . $this->_table_prefix . 'media '
			. 'WHERE media_section="manufacturer" AND section_id = ' . $mid;
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function saveOrder(&$cid, $order = array())
	{
		$db = JFactory::getDbo();
		$row = $this->getTable();

		$total = count($cid);
		$order = (empty($order)) ? JFactory::getApplication()->input->post->get('order', array(0), 'array') : $order;
		JArrayHelper::toInteger($order, array(0));

		// Update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
		}
		$row->reorder();

		return true;
	}

	/**
	 * Method to get max ordering
	 *
	 * @access public
	 * @return boolean
	 */
	public function MaxOrdering()
	{
		$query = "SELECT (max(ordering)+1) FROM " . $this->_table_prefix . "manufacturer";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function move($direction)
	{
		$row = JTable::getInstance('manufacturer_detail', 'Table');

		if (!$row->load($this->_id))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		if (!$row->move($direction))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}
}
