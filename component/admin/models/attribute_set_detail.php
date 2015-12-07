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

JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminImages');

class RedshopModelAttribute_set_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $attribute_data = null;

	public $_copydata = null;

	public $_copycategorydata = null;

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
			$query = 'SELECT * FROM #__redshop_attribute_set WHERE attribute_set_id = ' . $this->_id;
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
			$detail->attribute_set_id = 0;
			$detail->attribute_set_name = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();
		$producthelper = new producthelper;
		$option = JRequest::getVar('option', '', 'request', 'string');

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$property_image = $producthelper->getAttibuteProperty(0, 0, 0, $cids);

			foreach ($property_image as $imagename)
			{
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $imagename->property_image;

				$tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/thumb/' . $imagename->property_image;

				if (is_file($dest))
				{
					unlink($dest);
				}

				if (is_file($tsrc))
				{
					unlink($tsrc);
				}

				$attr_delete = 'DELETE FROM #__redshop_product_attribute WHERE attribute_id =' . $imagename->attribute_id;
				$db->setQuery($attr_delete);

				if (!$db->execute())
				{
					$this->setError($db->getErrorMsg());
				}

				$prop_delete = 'DELETE FROM #__redshop_product_attribute_property WHERE attribute_id =' . $imagename->attribute_id;
				$db->setQuery($prop_delete);

				if (!$db->execute())
				{
					$this->setError($db->getErrorMsg());
				}
			}

			$query = 'DELETE FROM #__redshop_attribute_set WHERE attribute_set_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$db = JFactory::getDbo();
			$cids = implode(',', $cid);
			$query = 'UPDATE #__redshop_attribute_set'
				. ' SET published = ' . intval($publish)
				. ' WHERE attribute_set_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getattributes()
	{
		$db = JFactory::getDbo();
		$attr = array();

		if ($this->_id != 0)
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_product_attribute'))
				->where('attribute_set_id = ' . (int) $this->_id)
				->order('ordering');
			$db->setQuery($query);
			$attr = $db->loadObjectlist();
		}

		$attribute_data = '';

		for ($i = 0, $in = count($attr); $i < $in; $i++)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from($db->quoteName('#__redshop_product_attribute_property'))
				->where($db->quoteName('attribute_id') . ' = ' . (int) $attr[$i]->attribute_id)
				->order($db->quoteName('ordering') . ' ASC');

			$db->setQuery($query);
			$prop = $db->loadObjectlist();

			$attribute_id = $attr[$i]->attribute_id;
			$attribute_name = $attr[$i]->attribute_name;
			$attribute_required = $attr[$i]->attribute_required;
			$allow_multiple_selection = $attr[$i]->allow_multiple_selection;
			$hide_attribute_price = $attr[$i]->hide_attribute_price;
			$attribute_published = $attr[$i]->attribute_published;
			$display_type = $attr[$i]->display_type;
			$ordering = $attr[$i]->ordering;

			for ($j = 0, $jn = count($prop); $j < $jn; $j++)
			{
				$query = $db->getQuery(true);
				$query->select('*')
					->from($db->quoteName('#__redshop_product_subattribute_color'))
					->where($db->quoteName('subattribute_id') . ' = ' . (int) $prop[$j]->property_id)
					->order($db->quoteName('ordering') . ' ASC');

				$db->setQuery($query);
				$subprop = $db->loadObjectlist();
				$prop[$j]->subvalue = $subprop;
			}

			$attribute_data[] = array('attribute_id' => $attribute_id, 'attribute_name' => $attribute_name,
				'attribute_required' => $attribute_required, 'ordering' => $ordering,
				'property' => $prop, 'allow_multiple_selection' => $allow_multiple_selection,
				'hide_attribute_price' => $hide_attribute_price, 'attribute_published' => $attribute_published,
				'display_type' => $display_type
			);
		}

		return $attribute_data;
	}

	public function getattributelist($data)
	{
		$db = JFactory::getDbo();
		$attribute_data = '';
		$producthelper = new producthelper;
		$attr = $producthelper->getProductAttribute(0, $data);

		for ($i = 0, $in = count($attr); $i < $in; $i++)
		{
			$query = $db->getQuery(true);
			$query->select('*')
				->from($db->quoteName('#__redshop_product_attribute_property'))
				->where($db->quoteName('attribute_id') . ' = ' . (int) $attr[$i]->attribute_id)
				->order($db->quoteName('property_id') . ' ASC');

			$db->setQuery($query);
			$prop = $db->loadObjectlist();
			$attribute_id = $attr[$i]->attribute_id;
			$attribute_name = $attr[$i]->attribute_name;
			$attribute_data[] = array('attribute_id' => $attribute_id, 'attribute_name' => $attribute_name, 'property' => $prop);
		}

		return $attribute_data;
	}

	public function getpropertylist($data)
	{
		$db = JFactory::getDbo();
		$producthelper = new producthelper;

		if (count($data))
		{
			$cids = implode(',', $data);
			$query = $db->getQuery(true);
			$query->select('*')
				->from($db->quoteName('#__redshop_product_attribute_property'))
				->where($db->quoteName('property_id') . ' IN ( ' . $cids . ' )');

			$db->setQuery($query);
			$prop = $db->loadObjectlist();
		}

		return $prop;
	}

	public function deleteattr($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$prop = $this->property_image_list($cids);

			foreach ($prop as $imagename)
			{
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $imagename->property_image;

				$tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/thumb/' . $imagename->property_image;

				if (file_exists($dest))
				{
					unlink($dest);
				}

				if (file_exists($tsrc))
				{
					unlink($tsrc);
				}
			}

			$query = 'DELETE FROM #__redshop_product_attribute WHERE attribute_id IN ( ' . $cids . ' )';

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM #__redshop_product_attribute_property WHERE attribute_id IN ( ' . $cids . ' )';

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}
	}

	public function deleteprop($cid = array(), $image_name)
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			foreach ($image_name as $imagename)
			{
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $imagename;

				$tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/thumb/' . $imagename;

				if (file_exists($dest))
				{
					unlink($dest);
				}

				if (file_exists($tsrc))
				{
					unlink($tsrc);
				}
			}

			$query = 'DELETE FROM #__redshop_product_attribute_property WHERE property_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
			else
			{
				$query = 'DELETE FROM #__redshop_product_subattribute_color  WHERE subattribute_id IN (' . $cids . ' )';
				$db->setQuery($query);

				if (!$db->execute())
				{
					$this->setError($db->getErrorMsg());

					return false;
				}
			}
		}
	}

	public function deleteattr_current($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$prop = $this->property_image_list($cids);

			foreach ($prop as $property_image)
			{
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property_image->property_image;

				$tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/thumb/' . $property_image->property_image;

				if (file_exists($dest))
				{
					unlink($dest);
				}

				if (file_exists($tsrc))
				{
					unlink($tsrc);
				}
			}

			$query = 'DELETE FROM #__redshop_product_attribute_property WHERE attribute_id IN ( ' . $cids . ' )';

			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}
	}

	public function property_image_list($cid)
	{
		$producthelper = new producthelper;

		if (count($cid))
		{
			$prop = $producthelper->getAttibuteProperty(0, $cid);
		}

		return $prop;
	}

	public function store_attr($data)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable('product_attribute');

		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function store_pro($data)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable('attribute_property');

		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return $row;
	}

	/**
	 * Store Subattribute Color List
	 */
	public function store_sub($data)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable('subattribute_property');

		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function property_more_img($post, $main_img, $sub_img)
	{
		$db = JFactory::getDbo();

		if ($main_img['name'] != '')
		{
			$filetype = strtolower(JFile::getExt($main_img['name']));

			if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg')
			{
				return false;
			}
			else
			{
				$main_name = RedShopHelperImages::cleanFileName($main_img['name']);
				$main_src = $main_img['tmp_name'];

				// Specific path of the file
				$main_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $main_name;

				JFile::upload($main_src, $main_dest);

				$query = "UPDATE #__redshop_product_attribute_property SET property_main_image = '"
					. $main_name . "' WHERE property_id ='" . $post['section_id'] . "' ";
				$db->setQuery($query);

				if (!$db->execute())
				{
					$this->setError($db->getErrorMsg());

					return false;
				}
			}
		}

		$num = count($sub_img['name']);

		for ($i = 0; $i < $num; $i++)
		{
			if ($sub_img['name'][$i] != "")
			{
				$filetype = strtolower(JFile::getExt($sub_img['name'][$i]));

				if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg')
				{
					return false;
				}
				else
				{
					$sub_name = RedShopHelperImages::cleanFileName($sub_img['name'][$i]);

					$sub_src = $sub_img['tmp_name'][$i];

					$sub_type = $sub_img['type'][$i];

					// Specific path of the file
					$sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $sub_name;

					JFile::upload($sub_src, $sub__dest);

					$query = "INSERT INTO #__redshop_media
								(`media_id`,`media_name`,`media_section`,`section_id`,`media_type`,`media_mimetype`,`published`)
								VALUES ('','" . $sub_name . "','property','" . $post['section_id'] . "','images','" . $sub_type . "','1') ";
					$db->setQuery($query);

					if (!$db->execute())
					{
						$this->setError($db->getErrorMsg());

						return false;
					}
				}
			}
		}
	}

	public function deletesubimage($mediaid)
	{
		$db = JFactory::getDbo();

		$query = 'SELECT * FROM #__redshop_media  WHERE media_id = ' . $mediaid;
		$db->setQuery($query);
		$imgdata = $db->loadObject();

		$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $imgdata->media_name;

		$tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'property/thumb/' . $imgdata->media_name;

		if (file_exists($dest))
		{
			unlink($dest);
		}

		if (file_exists($tsrc))
		{
			unlink($tsrc);
		}

		$query = 'DELETE FROM #__redshop_media WHERE media_id = ' . $mediaid;

		$db->setQuery($query);

		if (!$db->execute())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function subattribute_color($post, $sub_img)
	{
		$db = JFactory::getDbo();
		$num = count($sub_img['name']);

		for ($i = 0; $i < $num; $i++)
		{
			if ($sub_img['name'][$i] != "")
			{
				$filetype = strtolower(JFile::getExt($sub_img['name'][$i]));

				if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg')
				{
					return false;
				}
				else
				{
					$sub_name = RedShopHelperImages::cleanFileName($sub_img['name'][$i]);

					$sub_src = $sub_img['tmp_name'][$i];

					// Specific path of the file
					$sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $sub_name;

					JFile::upload($sub_src, $sub__dest);

					if ($post['property_sub_img_tmp'][$i] != "")
					{
						$sub = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $post['property_sub_img_tmp'][$i];
						$sub_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/thumb/' . $post['property_sub_img_tmp'][$i];

						if (file_exists($sub))
						{
							unlink($sub);
						}

						if (file_exists($sub_thumb))
						{
							unlink($sub_thumb);
						}
					}

					if ($post['subattribute_color_id'][$i] == "")
					{
						$query = "INSERT INTO #__redshop_product_subattribute_color
									(`subattribute_color_id`,`subattribute_color_name`,`subattribute_color_image`,`subattribute_id`)
									VALUES ('','" . $post['subattribute_name'][$i] . "','" . $sub_name . "','" . $post['section_id'] . "') ";
					}
					else
					{
						$query = "UPDATE #__redshop_product_subattribute_color
									SET `subattribute_color_name` = '" . $post['subattribute_name'][$i] . "' ,`subattribute_color_image` = '" .
							$sub_name . "',`subattribute_id` = '" . $post['section_id'] . "' WHERE subattribute_color_id = '" . $post['subattribute_color_id'][$i] . "'";
					}

					$db->setQuery($query);

					if (!$db->execute())
					{
						$this->setError($db->getErrorMsg());

						return false;
					}
				}
			}
			else
			{
				if ($post['property_sub_img_tmp'][$i] != "" && $sub_img['name'][$i] == "")
				{
					$query = "UPDATE #__redshop_product_subattribute_color
								SET `subattribute_color_name` = '" . $post['subattribute_name'][$i] . "' ,`subattribute_color_image` = '" .
						$post['property_sub_img_tmp'][$i] . "',`subattribute_id` = '" . $post['section_id'] . "'
								WHERE subattribute_color_id = '" . $post['subattribute_color_id'][$i] . "'";

					$db->setQuery($query);

					if (!$db->execute())
					{
						$this->setError($db->getErrorMsg());

						return false;
					}
				}
			}
		}
	}

	public function subattr_diff($subattr_id, $section_id)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM #__redshop_product_subattribute_color   WHERE subattribute_id = '
			. $section_id . ' and subattribute_color_id NOT IN (\'' . $subattr_id . '\') ORDER BY subattribute_color_id ASC';
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function get_subattrprop($subattr_id, $section_id)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM #__redshop_product_subattribute_color   WHERE subattribute_color_id IN (\''
			. $subattr_id . '\') ORDER BY subattribute_color_id ASC';
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function delsubattr_diff($subattr_diff)
	{
		$db = JFactory::getDbo();

		foreach ($subattr_diff as $diff)
		{
			$sub_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $diff->subattribute_color_image;

			if (file_exists($sub_dest))
			{
				unlink($sub_dest);
			}

			$query = 'DELETE FROM #__redshop_product_subattribute_color  WHERE subattribute_color_id = "'
				. $diff->subattribute_color_id . '"';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function  attribute_empty()
	{
		$database = JFactory::getDbo();
		$producthelper = new producthelper;

		if ($this->_id)
		{
			$attributes = $producthelper->getProductAttribute(0, $this->_id);

			for ($i = 0, $in = count($attributes); $i < $in; $i++)
			{
				$query = "DELETE FROM `#__redshop_product_attribute` WHERE `attribute_id` = " . $attributes[$i]->attribute_id;
				$database->setQuery($query);

				if ($database->execute())
				{
					$property = $producthelper->getAttibuteProperty(0, $attributes[$i]->attribute_id);

					for ($j = 0, $jn = count($property); $j < $jn; $j++)
					{
						$query = "DELETE FROM `#__redshop_product_attribute_property` WHERE `property_id` = "
							. $property[$j]->property_id;
						$database->setQuery($query);

						if ($database->execute())
						{
							$query = "DELETE FROM `#__redshop_product_subattribute_color` WHERE `subattribute_id` = "
								. $property[$j]->property_id;
							$database->setQuery($query);
							$database->execute();
						}
					}
				}
			}
		}

		return true;
	}

	public function removepropertyImage($pid)
	{
		$db = JFactory::getDbo();

		$producthelper = new producthelper;

		$image = $producthelper->getAttibuteProperty($pid);
		$image = $image[0];
		$imagename = $image->property_image;

		$imagethumbsrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/thumb/" . $imagename;

		if (is_file($imagethumbsrcphy))
		{
			@unlink($imagethumbsrcphy);
		}

		$imagesrc = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $imagename;
		$imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $imagename;

		if (is_file($imagesrcphy))
		{
			@unlink($imagesrcphy);
		}

		$query = "UPDATE `#__redshop_product_attribute_property` SET `property_image` = '' WHERE `property_id` = " . $pid;
		$db->setQuery($query);

		if (!$db->execute())
		{
			return false;
		}

		return true;
	}

	public function removesubpropertyImage($pid)
	{
		$db = JFactory::getDbo();
		$producthelper = new producthelper;
		$image = $producthelper->getAttibuteSubProperty($pid);
		$image = $image[0];
		$imagename = $image->subattribute_color_image;
		$imagethumbsrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/thumb/" . $imagename;

		if (is_file($imagethumbsrcphy))
		{
			@unlink($imagethumbsrcphy);
		}

		$imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $imagename;

		if (is_file($imagesrcphy))
		{
			@unlink($imagesrcphy);
		}

		$query = "UPDATE `#__redshop_product_subattribute_color` SET `subattribute_color_image` = ''
		WHERE `subattribute_color_id` = " . $pid;
		$db->setQuery($query);

		if (!$db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * Store stockroom product xref
	 *
	 * @param $post
	 *
	 * @return bool
	 */
	public function SaveAttributeStockroom($post)
	{
		$database = JFactory::getDbo();
		$query = "DELETE FROM #__redshop_product_attribute_stockroom_xref"
			. "\n  WHERE section_id = " . $post['section_id'] . " AND section = '" . $post['section'] . "'";

		$database->setQuery($query);

		$database->execute();

		for ($i = 0; $i < count($post['quantity']); $i++)
		{
			if ($post['quantity'][$i] || (!USE_BLANK_AS_INFINITE))
			{
				$q = "INSERT IGNORE INTO #__redshop_product_attribute_stockroom_xref VALUES ("
					. $post['section_id'] . ",'" . $post['section'] . "'," . $post['stockroom_id'][$i] . ",'"
					. $post['quantity'][$i] . "') ";

				$database->setQuery($q);

				if (!$database->execute())
				{
					return false;
				}
			}
		}

		return true;
	}

	public function save_product_attribute_price($product_attribute_price, $section)
	{
		$db = JFactory::getDbo();

		// Create array for attribute price for property and subproperty section
		$attribute['section_id'] = $product_attribute_price->section_id;
		$attribute['section'] = $section;
		$attribute['product_price'] = $product_attribute_price->product_price;
		$attribute['product_currency'] = $product_attribute_price->product_currency;
		$attribute['cdate'] = $product_attribute_price->cdate;
		$attribute['shopper_group_id'] = $product_attribute_price->shopper_group_id;
		$attribute['price_quantity_start'] = $product_attribute_price->price_quantity_start;
		$attribute['price_quantity_end'] = $product_attribute_price->price_quantity_end;

		$row = $this->getTable('attributeprices_detail');

		// Bind and save data into 'attributeprices_detail'
		if (!$row->bind($attribute))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}
	}

	public function save_product_attribute_stockquantity($product_attribute_stocks, $section)
	{
		$db = JFactory::getDbo();

		$sql = "INSERT INTO #__redshop_product_attribute_stockroom_xref (`section_id`,`section`,`stockroom_id`,`quantity`)
		VALUES ('" . $product_attribute_stocks->section_id . "','" . $section . "','" . $product_attribute_stocks->stockroom_id . "','"
			. $product_attribute_stocks->quantity . "' )";
		$db->setQuery($sql);
		$db->execute();
	}

	public function copy($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'SELECT * FROM #__redshop_attribute_set WHERE attribute_set_id IN ( ' . $cids . ' )';
			$db->setQuery($query);
			$copydata = $db->loadObjectList();

			for ($i = 0, $in = count($copydata); $i < $in; $i++)
			{
				$post = array();

				// Insert into attribute set table
				$post['attribute_set_id'] = 0;
				$post['attribute_set_name'] = "copy" . $copydata[$i]->attribute_set_name;
				$post['published'] = $copydata[$i]->published;
				$row = $this->store($post);

				// Fetch attributes from the attribute set ID
				$query = 'SELECT * FROM #__redshop_product_attribute  WHERE `attribute_set_id` = '
					. $copydata[$i]->attribute_set_id . ' ';
				$db->setQuery($query);
				$product_attributes = $db->loadObjectList();

				$attribute_set_id = $row->attribute_set_id;

				if (count($product_attributes) > 0)
				{
					foreach ($product_attributes as $product_attribute)
					{
						// Create $attribute array of attributes
						$attribute['attribute_name'] = $product_attribute->attribute_name;
						$attribute['attribute_required'] = $product_attribute->attribute_required;
						$attribute['allow_multiple_selection'] = $product_attribute->allow_multiple_selection;
						$attribute['hide_attribute_price'] = $product_attribute->hide_attribute_price;
						$attribute['product_id'] = $product_attribute->product_id;
						$attribute['ordering'] = $product_attribute->ordering;
						$attribute['attribute_set_id'] = $attribute_set_id;

						$row = $this->getTable('product_attribute');

						// Bind and save data into 'product_attribute'
						if (!$row->bind($attribute))
						{
							$this->setError($db->getErrorMsg());

							return false;
						}

						if (!$row->store())
						{
							$this->setError($db->getErrorMsg());

							return false;
						}

						// Fetch attributes from the attribute set ID

						$query = 'SELECT * FROM #__redshop_product_attribute_property  WHERE `attribute_id` = '
							. $product_attribute->attribute_id . ' ';
						$db->setQuery($query);
						$product_attributes_properties = $db->loadObjectList();

						$query = 'SELECT * FROM `#__redshop_product_attribute_property` WHERE `attribute_id` = "'
							. $product_attribute->attribute_id . '" ';
						$db->setQuery($query);
						$att_property = $db->loadObjectList();

						$attribute_id = $product_attribute->attribute_id;

						if (count($product_attributes_properties))
						{
							foreach ($product_attributes_properties as $product_attributes_property)
							{
								if (isset($row->attribute_id))
								{
									$row->attribute_id = $row->attribute_id;
								}
								else
								{
									$row->attribute_id = $loopattribute_id;
								}

								if ($product_attributes_property->property_image)
								{
									$image_split = $product_attributes_property->property_image;

									// Make the filename unique.
									$filename = RedShopHelperImages::cleanFileName($image_split);
									$product_attributes_property->property_image = $filename;
									$src = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $image_split;
									$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $filename;
									copy($src, $dest);
								}

								if ($product_attributes_property->property_main_image)
								{
									$prop_main_img = $product_attributes_property->property_main_image;
									$image_split = $prop_main_img;
									$image_split = explode('_', $image_split);
									$image_split = $image_split[1];

									// Make the filename unique.
									$filename = RedShopHelperImages::cleanFileName($image_split);
									$product_attributes_property->property_main_image = $filename;
									$src = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $prop_main_img;
									$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $filename;
									copy($src, $dest);
								}

								// Create $attribute_properties array of attributes properties

								$attribute_properties['attribute_id'] = $row->attribute_id;
								$loopattribute_id = $row->attribute_id;
								$attribute_properties['property_name'] = $product_attributes_property->property_name;
								$attribute_properties['property_price'] = $product_attributes_property->property_price;
								$attribute_properties['oprand'] = $product_attributes_property->oprand;
								$attribute_properties['property_image'] = $product_attributes_property->property_image;
								$attribute_properties['property_main_image'] = $product_attributes_property->property_main_image;
								$attribute_properties['ordering'] = $product_attributes_property->ordering;
								$attribute_properties['setdefault_selected'] = $product_attributes_property->setdefault_selected;
								$attribute_properties['property_number'] = $product_attributes_property->property_number;
								$attribute_properties['extra_field'] = $product_attributes_property->extra_field;

								$row = $this->getTable('attribute_property');

								// Bind and save data into 'product_attribute_property'
								if (!$row->bind($attribute_properties))
								{
									$this->setError($db->getErrorMsg());

									return false;
								}

								if (!$row->store())
								{
									$this->setError($db->getErrorMsg());

									return false;
								}

								$listImages = $this->GetimageInfo($product_attributes_property->property_id, 'property');

								for ($li = 0; $li < count($listImages); $li++)
								{
									$mImages = array();
									$mImages['media_name'] = $listImages[$li]->media_name;
									$mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
									$mImages['media_section'] = 'property';
									$mImages['section_id'] = $row->property_id;
									$mImages['media_type'] = 'images';
									$mImages['media_mimetype'] = $listImages[$li]->media_mimetype;
									$mImages['published'] = $listImages[$li]->published;
									$this->copyadditionalImage($mImages);
								}

								// Attribute piggy bank price for property
								$query = 'SELECT * FROM #__redshop_product_attribute_price   WHERE `section_id` = '
									. $product_attributes_property->property_id . ' AND `section`="property" ';
								$db->setQuery($query);
								$product_attribute_prices = $db->loadObjectList();

								if (count($product_attribute_prices))
								{
									foreach ($product_attribute_prices as $product_attribute_price)
									{
										$product_attribute_price->section_id = $row->property_id;
										$this->save_product_attribute_price($product_attribute_price, 'property');
									}
								}

								// Attribute stock quantity for property
								$query = 'SELECT * FROM #__redshop_product_attribute_stockroom_xref   WHERE `section_id` = '
									. $product_attributes_property->property_id . ' AND `section`="property" ';
								$db->setQuery($query);
								$product_attribute_stockquantities = $db->loadObjectList();

								if (count($product_attribute_stockquantities))
								{
									foreach ($product_attribute_stockquantities as $product_attribute_stockquantity)
									{
										$product_attribute_stockquantity->section_id = $row->property_id;
										$this->save_product_attribute_stockquantity($product_attribute_stockquantity, 'property');
									}
								}

								// Fetch attributes from the attribute set ID
								$query = 'SELECT * FROM #__redshop_product_subattribute_color  WHERE `subattribute_id` = '
									. $product_attributes_property->property_id . ' ';
								$db->setQuery($query);
								$product_sub_attributes_properties = $db->loadObjectList();

								$subattribute_id = $product_attributes_property->property_id;

								if (count($product_sub_attributes_properties))
								{
									foreach ($product_sub_attributes_properties as $product_sub_attributes_property)
									{
										if (isset($row->attribute_id))
										{
											$row->property_id = $row->property_id;
										}
										else
										{
											$row->property_id = $loopproperty_id;
										}

										if ($product_sub_attributes_property->subattribute_color_image)
										{
											$image_split = $product_sub_attributes_property->subattribute_color_image;

											// Make the filename unique.
											$filename = RedShopHelperImages::cleanFileName($image_split);
											$product_sub_attributes_property->subattribute_color_image = $filename;
											$src = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $image_split;
											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $filename;
											copy($src, $dest);
										}

										if ($product_sub_attributes_property->subattribute_color_main_image)
										{
											$sub_main_img = $product_sub_attributes_property->subattribute_color_main_image;
											$image_split = $product_sub_attributes_property->subattribute_color_main_image;
											$image_split = explode('_', $image_split);
											$image_split = $image_split[1];

											// Make the filename unique.
											$filename = RedShopHelperImages::cleanFileName($image_split);

											$product_sub_attributes_property->subattribute_color_main_image = $filename;
											$src = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $sub_main_img;
											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $filename;
											copy($src, $dest);
										}

										// Create $sub_attribute_properties array of subattributes properties
										$sub_attribute_properties['subattribute_id'] = $row->property_id;
										$loopproperty_id = $row->property_id;
										$sub_attribute_properties['subattribute_color_name'] = $product_sub_attributes_property->subattribute_color_name;
										$sub_attribute_properties['subattribute_color_price'] = $product_sub_attributes_property->subattribute_color_price;
										$sub_attribute_properties['oprand'] = $product_sub_attributes_property->oprand;
										$sub_attribute_properties['subattribute_color_image'] = $product_sub_attributes_property->subattribute_color_image;
										$sub_attribute_properties['ordering'] = $product_sub_attributes_property->ordering;
										$sub_attribute_properties['setdefault_selected'] = $product_sub_attributes_property->setdefault_selected;
										$sub_attribute_properties['subattribute_color_number'] = $product_sub_attributes_property->subattribute_color_number;
										$sub_attribute_properties['subattribute_color_title'] = $product_sub_attributes_property->subattribute_color_title;
										$sub_attribute_properties['extra_field'] = $product_sub_attributes_property->extra_field;
										$sub_attribute_properties['subattribute_color_main_image'] = $product_sub_attributes_property->subattribute_color_main_image;
										$row = $this->getTable('subattribute_property');

										// Bind and save data into 'subattribute_property'
										if (!$row->bind($sub_attribute_properties))
										{
											$this->setError($db->getErrorMsg());

											return false;
										}

										if (!$row->store())
										{
											$this->setError($db->getErrorMsg());

											return false;
										}

										$listsubpropImages = $this->GetimageInfo($product_sub_attributes_property->subattribute_color_id, 'subproperty');

										for ($lsi = 0; $lsi < count($listsubpropImages); $lsi++)
										{
											$smImages = array();
											$smImages['media_name'] = $listsubpropImages[$lsi]->media_name;
											$smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
											$smImages['media_section'] = 'subproperty';
											$smImages['section_id'] = $row->subattribute_color_id;
											$smImages['media_type'] = 'images';
											$smImages['media_mimetype'] = $listsubpropImages[$lsi]->media_mimetype;
											$smImages['published'] = $listsubpropImages[$lsi]->published;

											$this->copyadditionalImage($smImages);
										}
										// Attribute piggy bank price for Subproperty
										$query = 'SELECT * FROM #__redshop_product_attribute_price   WHERE `section_id` = ' . $product_sub_attributes_property->subattribute_color_id . ' AND `section`="subproperty"  ';
										$db->setQuery($query);
										$product_subattribute_prices = $db->loadObjectList();

										if (count($product_subattribute_prices))
										{
											foreach ($product_subattribute_prices as $product_subattribute_price)
											{
												$product_subattribute_price->section_id = $row->subattribute_color_id;
												$this->save_product_attribute_price($product_subattribute_price, 'subproperty');
											}
										}

										// Attribute stock quantity for property
										$query = 'SELECT * FROM #__redshop_product_attribute_stockroom_xref   WHERE `section_id` = ' . $product_sub_attributes_property->subattribute_color_id . ' AND `section`="subproperty" ';
										$db->setQuery($query);
										$product_attribute_stockquantities = $db->loadObjectList();

										if (count($product_attribute_stockquantities))
										{
											foreach ($product_attribute_stockquantities as $product_attribute_stockquantity)
											{
												$product_attribute_stockquantity->section_id = $row->subattribute_color_id;
												$this->save_product_attribute_stockquantity($product_attribute_stockquantity, 'subproperty');
											}
										}

									}
								}
							}
						}
					}
				}
			}
		}

		return true;
	}

	public function copy_image($imageArray, $section, $section_id)
	{
		$src = $imageArray['tmp_name'];
		$imgname = RedShopHelperImages::cleanFileName($imageArray['name']);
		$property_image = $section_id . '_' . $imgname;
		$dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;
		copy($src, $dest);

		return $property_image;

	}

	public function copy_image_from_path($imagePath, $section, $section_id)
	{
		$src = JPATH_ROOT . '/' . $imagePath;
		$imgname = RedShopHelperImages::cleanFileName($imagePath);
		$property_image = $section_id . '_' . JFile::getName($imgname);
		$dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;
		copy($src, $dest);

		return $property_image;
	}

	public function copyadditionalImage($data)
	{
		$db = JFactory::getDbo();
		$rowmedia = $this->getTable('media_detail');

		$data['media_id '] = 0;

		if (!$rowmedia->bind($data))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}
		$section = $data['media_section'];
		$path = $section . '/' . $data['media_name'];
		$property_image = $this->copy_image_additionalimage_from_path($path, $data['media_section']);
		$data['media_name'] = $property_image;

		if (!$rowmedia->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}
	}

	public function copy_image_additionalimage_from_path($imagePath, $section)
	{
		$src = REDSHOP_FRONT_IMAGES_RELPATH . $imagePath;

		$imgname = basename($imagePath);

		$property_image = RedShopHelperImages::cleanFileName($imgname);

		$dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;

		copy($src, $dest);

		return $property_image;
	}

	public function GetimageInfo($id, $type)
	{
		$db = JFactory::getDbo();
		$image_media = 'SELECT * FROM #__redshop_media WHERE section_id = "' . $id . '" AND media_section = "' . $type . '" ';
		$db->setQuery($image_media);

		return $db->loadObjectlist();
	}
}
