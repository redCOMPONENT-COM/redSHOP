<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelManufacturer_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_copydata = null;

	public $_templatedata = null;

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
			$query = 'SELECT * FROM #__redshop_manufacturer WHERE manufacturer_id = ' . $this->_id;
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
		$order_functions  = new order_functions;
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
		{
			$data['excluding_category_list'] = @ implode(',', $data['excluding_category_list']);
		}

		if ($data['manufacturer_id'] == 0)
		{
			$data['ordering'] = $this->MaxOrdering();
		}

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
		{
			if (!$data['excluding_category_list'])
			{
				$data['excluding_category_list'] = '';
			}
		}

		return parent::store($data);
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
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

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE #__redshop_manufacturer'
				. ' SET published = ' . intval($publish)
				. ' WHERE manufacturer_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}
		return true;
	}

	public function copy($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'SELECT * FROM #__redshop_manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
			$db->setQuery($query);
			$this->_copydata = $db->loadObjectList();
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
		$db = JFactory::getDbo();

		$query = "SELECT template_id as value,template_name as text FROM #__redshop_template WHERE template_section ='manufacturer_products' and published=1";
		$db->setQuery($query);
		$this->_templatedata = $db->loadObjectList();

		return $this->_templatedata;
	}

	public function getMediaId($mid)
	{
		$db = JFactory::getDbo();

		$query = 'SELECT media_id,media_name FROM #__redshop_media '
			. 'WHERE media_section="manufacturer" AND section_id = ' . $mid;
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function saveOrder(&$cid)
	{
		$app = JFactory::getApplication();

		$db = JFactory::getDbo();
		$row = $this->getTable();

		$total = count($cid);
		$order = JRequest::getVar('order', array(0), 'post', 'array');
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
					JError::raiseError(500, $db->getErrorMsg());
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
		$db = JFactory::getDbo();

		$query = "SELECT (max(ordering)+1) FROM #__redshop_manufacturer";
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function move($direction)
	{
		$db = JFactory::getDbo();
		$row = JTable::getInstance('manufacturer_detail', 'Table');

		if (!$row->load($this->_id))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}
		if (!$row->move($direction))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}
}
