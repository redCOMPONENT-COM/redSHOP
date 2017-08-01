<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopModelMedia_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_mediadata = null;

	public $_mediatypedata = null;

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
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'media '
				. 'WHERE media_id = "' . $this->_id . '" '
				. 'order by section_id';
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
			$detail->media_id = 0;
			$detail->media_title = null;
			$detail->media_type = null;
			$detail->media_name = null;
			$detail->media_alternate_text = null;
			$detail->media_section = null;
			$detail->section_id = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable();

		if (empty($data['media_alternate_text']))
		{
			$data['media_alternate_text'] = preg_replace('#\.[^/.]+$#', '', $data['media_name']);
		}

		if (isset($data['cid'][0]))
		{
			$data['media_id'] = $data['cid'][0];
		}

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$db = JFactory::getDbo();
		$condition = 'section_id = ' . $db->q($row->section_id) . ' AND media_section = ' . $db->q($row->media_section);
		$row->reorder($condition);

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$q = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE media_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($q);
			$this->_data = $this->_db->loadObjectList();

			foreach ($this->_data as $mediadata)
			{
				$ntsrc = JPATH_ROOT . '/components/com_redshop/assets/' . $mediadata->media_type . '/'
					. $mediadata->media_section . '/thumb/' . $mediadata->media_name;
				$nsrc = JPATH_ROOT . '/components/com_redshop/assets/' . $mediadata->media_type . '/'
					. $mediadata->media_section . '/' . $mediadata->media_name;

				if (JFile::exists($nsrc))
				{
					JFile::delete($nsrc);
				}

				if (JFile::exists($ntsrc))
				{
					JFile::delete($ntsrc);
				}

				if ($mediadata->media_section == 'manufacturer')
				{
					$query = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE section_id IN ( ' . $mediadata->section_id . ' )';
					$this->_db->setQuery($query);
					$this->_db->execute();
				}

				$query = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE media_id IN ( ' . $mediadata->media_id . ' )';
				$this->_db->setQuery($query);

				if (!$this->_db->execute())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'media'
				. ' SET published = ' . intval($publish)
				. ' WHERE media_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getSection($id, $type)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$search = ' = ' . (int) $id;

		switch ($type)
		{
			case 'category':
				$query->select(
					array(
						$db->qn('id'),
						$db->qn('name')
					)
				)
					->from($db->qn('#__redshop_category'))
					->where($db->qn('id') . $search);
				break;
			case 'property':
				$query->select(
					array(
						$db->qn('property_id', 'id'),
						$db->qn('property_name', 'name')
					)
				)
					->from($db->qn('#__redshop_product_attribute_property'))
					->where($db->qn('property_id') . $search);
				break;
			case 'subproperty':
				$query->select(
					array(
						$db->qn('subattribute_color_id', 'id'),
						$db->qn('subattribute_color_name', 'name')
					)
				)
					->from($db->qn('#__redshop_product_subattribute_color'))
					->where($db->qn('subattribute_color_id') . $search);
				break;
			case 'manufacturer':
				$query->select(
					array(
						$db->qn('manufacturer_id', 'id'),
						$db->qn('manufacturer_name', 'name')
					)
				)
					->from($db->qn('#__redshop_manufacturer'))
					->where($db->qn('manufacturer_id') . $search);
				break;
			case 'catalog':
				$query->select(
					array(
						$db->qn('catalog_id', 'id'),
						$db->qn('catalog_name', 'name')
					)
				)
					->from($db->qn('#__redshop_catalog'))
					->where('catalog_id' . $search);
				break;
			case 'product':
			default:
				$query->select(
					array(
						$db->qn('product_id', 'id'),
						$db->qn('product_name', 'name')
					)
				)
					->from($db->qn('#__redshop_product'))
					->where($db->qn('product_id') . $search);
				break;
		}

		return $db->setQuery($query)->loadObject();
	}

	public function defaultmedia($media_id = 0, $section_id = 0, $media_section = "")
	{
		if ($media_id && $media_section)
		{
			$query = "SELECT * FROM " . $this->_table_prefix . "media "
				. "WHERE `section_id`='" . $section_id . "' "
				. "AND `media_section` = '" . $media_section . "' "
				. "AND `media_id` = '" . $media_id . "'";
			$this->_db->setQuery($query);
			$rs = $this->_db->loadObject();

			if (count($rs) > 0)
			{
				if ($rs->media_type == "images")
				{
					switch ($media_section)
					{
						case "product":
							$query = "UPDATE `" . $this->_table_prefix . "product` "
								. "SET `product_thumb_image` = '', `product_full_image` = '" . $rs->media_name . "' "
								. "WHERE `product_id`='" . $section_id . "' ";
							$this->_db->setQuery($query);

							if (!$this->_db->execute())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
							break;
						case "property":
							$query = "UPDATE `" . $this->_table_prefix . "product_attribute_property` "
								. "SET `property_main_image` = '" . $rs->media_name . "' "
								. "WHERE `property_id`='" . $section_id . "' ";
							$this->_db->setQuery($query);

							if (!$this->_db->execute())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
							break;
						case "subproperty":
							$query = "UPDATE `" . $this->_table_prefix . "product_subattribute_color` "
								. "SET `subattribute_color_main_image` = '" . $rs->media_name . "' "
								. "WHERE `subattribute_color_id`='" . $section_id . "' ";
							$this->_db->setQuery($query);

							if (!$this->_db->execute())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
							break;
					}
				}
				else
				{
					JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_REDSHOP_ERROR_SET_DEFAULT_MEDIA', $rs->media_type), 'warning');

					return false;
				}
			}
		}

		return true;
	}

	public function saveorder($cid = array(), $order)
	{
		$row = $this->getTable();
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$conditions = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				// Remember to updateOrder this group
				$condition = 'section_id = ' . (int) $row->section_id . ' AND media_section = "' . $row->media_section . '"';
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$conditions[] = array($row->media_id, $condition);
				}
			}
		}

		// Execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}
	}

	public function orderup()
	{
		$row = $this->getTable();
		$row->load($this->_id);
		$row->move(-1, 'section_id = ' . (int) $row->section_id . ' AND media_section = "' . $row->media_section . '"');
		$row->store();

		return true;
	}

	public function orderdown()
	{
		$row = $this->getTable();
		$row->load($this->_id);
		$row->move(1, 'section_id = ' . (int) $row->section_id . ' AND media_section = "' . $row->media_section . '"');
		$row->store();

		return true;
	}
}
