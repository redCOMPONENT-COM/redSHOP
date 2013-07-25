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
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/thumbnail.php';
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/extra_field.php';

class product_detailModelproduct_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $attribute_data = null;

	public $_copydata = null;

	public $_copycategorydata = null;

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
			if (!empty($post))
			{
				$this->_initData();
			}
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$post = JRequest::get('post');

		if (empty($this->_data) && empty($post))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product WHERE product_id = "' . $this->_id . '" ';
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

			$data = JRequest::get('post');
			$data['product_desc'] = JRequest::getVar('product_desc', '', 'post', 'string', JREQUEST_ALLOWHTML);
			$data['product_s_desc'] = JRequest::getVar('product_s_desc', '', 'post', 'string', JREQUEST_ALLOWHTML);
			$detail->product_id = (isset($data['product_id'])) ? $data['product_id'] : 0;
			$detail->product_parent_id = (isset($data['product_parent_id'])) ? $data['product_parent_id'] : 0;
			$detail->product_number = (isset($data['product_number'])) ? $data['product_number'] : null;
			$detail->product_price = (isset($data['product_price'])) ? $data['product_price'] : 0;
			$detail->discount_price = (isset($data['discount_price'])) ? $data['discount_price'] : null;
			$detail->discount_stratdate = (isset($data['discount_stratdate'])) ? $data['discount_stratdate'] : time();
			$detail->discount_enddate = (isset($data['discount_enddate'])) ? $data['discount_enddate'] : time();
			$detail->product_volume = (isset($data['product_volume'])) ? $data['product_volume'] : 0;
			$detail->product_type = (isset($data['product_type'])) ? $data['product_type'] : null;
			$detail->product_name = (isset($data['product_name'])) ? $data['product_name'] : null;
			$detail->product_s_desc = (isset($data['product_s_desc'])) ? $data['product_s_desc'] : null;
			$detail->product_desc = (isset($data['product_desc'])) ? $data['product_desc'] : null;
			$detail->product_template = (isset($data['product_template'])) ? $data['product_template'] : 0;
			$detail->product_full_image = (isset($data['old_image'])) ? $this->cleanFileName($data['old_image']) : null;
			$detail->product_thumb_image = (isset($data['old_thumb_image'])) ? $this->cleanFileName($data['old_thumb_image']) : null;
			$detail->product_back_full_image = (isset($data['product_back_full_image'])) ? $this->cleanFileName($data['product_back_full_image']) : null;
			$detail->product_back_thumb_image = (isset($data['product_back_thumb_image'])) ? $this->cleanFileName($data['product_back_thumb_image']) : null;
			$detail->product_preview_image = (isset($data['product_preview_image'])) ? $this->cleanFileName($data['product_preview_image']) : null;
			$detail->product_preview_back_image = (isset($data['product_preview_back_image'])) ?
				$this->cleanFileName($data['product_preview_back_image']) : null;
			$detail->visited = (isset($data['visited'])) ? $data['visited'] : 0;
			$detail->metakey = (isset($data['metakey'])) ? $data['metakey'] : null;
			$detail->metadesc = (isset($data['metadesc'])) ? $data['metadesc'] : null;
			$detail->metalanguage_setting = (isset($data['metalanguage_setting'])) ? $data['metalanguage_setting'] : null;
			$detail->metarobot_info = (isset($data['metarobot_info'])) ? $data['metarobot_info'] : null;
			$detail->pagetitle = (isset($data['pagetitle'])) ? $data['pagetitle'] : null;
			$detail->pageheading = (isset($data['pageheading'])) ? $data['pageheading'] : null;
			$detail->sef_url = (isset($data['sef_url'])) ? $data['sef_url'] : null;
			$detail->cat_in_sefurl = (isset($data['cat_in_sefurl'])) ? $data['cat_in_sefurl'] : null;
			$detail->manufacturer_id = (isset($data['manufacturer_id'])) ? $data['manufacturer_id'] : null;
			$detail->container_id = (isset($data['container_id'])) ? $data['container_id'] : null;
			$detail->supplier_id = (isset($data['supplier_id'])) ? $data['supplier_id'] : null;
			$detail->product_on_sale = (isset($data['product_on_sale'])) ? $data['product_on_sale'] : null;
			$detail->product_special = (isset($data['product_special'])) ? $data['product_special'] : 0;
			$detail->product_download = (isset($data['product_download'])) ? $data['product_download'] : 0;
			$detail->not_for_sale = (isset($data['not_for_sale'])) ? $data['not_for_sale'] : 0;
			$detail->published = (isset($data['published'])) ? $data['published'] : 1;
			$detail->product_tax_id = (isset($data['product_tax_id'])) ? $data['product_tax_id'] : null;
			$detail->product_tax_group_id = (isset($data['product_tax_group_id'])) ? $data['product_tax_group_id'] : null;
			$detail->weight = (isset($data['weight'])) ? $data['weight'] : 0;
			$detail->expired = (isset($data['expired'])) ? $data['expired'] : 0;
			$detail->use_discount_calc = (isset($data['use_discount_calc'])) ? $data['use_discount_calc'] : 0;
			$detail->discount_calc_method = (isset($data['discount_calc_method'])) ? $data['discount_calc_method'] : null;
			$detail->min_order_product_quantity = (isset($data['min_order_product_quantity'])) ? $data['min_order_product_quantity'] : 0;
			$detail->product_length = (isset($data['product_length'])) ? $data['product_length'] : 0;
			$detail->product_width = (isset($data['product_width'])) ? $data['product_width'] : 0;
			$detail->product_height = (isset($data['product_height'])) ? $data['product_height'] : 0;
			$detail->product_diameter = (isset($data['product_diameter'])) ? $data['product_diameter'] : 0;
			$detail->use_range = (isset($data['use_range'])) ? $data['use_range'] : 0;
			$detail->product_availability_date = (isset($data['product_availability_date'])) ? $data['product_availability_date'] : 0;
			$detail->product_download_days = (isset($data['product_download_days'])) ? $data['product_download_days'] : 0;
			$detail->product_download_limit = (isset($data['product_download_limit'])) ? $data['product_download_limit'] : 0;
			$detail->product_download_clock = (isset($data['product_download_clock'])) ? $data['product_download_clock'] : 0;
			$detail->product_download_clock_min = (isset($data['product_download_clock_min'])) ? $data['product_download_clock_min'] : 0;
			$detail->product_download_infinite = (isset($data['product_download_infinite'])) ? $data['product_download_infinite'] : 0;

			$detail->checked_out = (isset($data['checked_out'])) ? $data['checked_out'] : 0;
			$detail->checked_out_time = (isset($data['checked_out_time'])) ? $data['checked_out_time'] : 0;
			$detail->accountgroup_id = (isset($data['accountgroup_id'])) ? $data['accountgroup_id'] : 0;
			$detail->quantity_selectbox_value = (isset($data['quantity_selectbox_value'])) ? $data['quantity_selectbox_value'] : null;
			$detail->preorder = (isset($data['preorder'])) ? $data['preorder'] : 'global';
			$detail->minimum_per_product_total = (isset($data['minimum_per_product_total'])) ? $data['minimum_per_product_total'] : 0;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function cleanFileName($name, $id = null)
	{
		$filetype = JFile::getExt($name);
		$segment = explode("/", $name);

		if (count($segment) > 1)
		{
			$values = preg_replace("/[&'#]/", "", end($segment));
			$segment[count($segment) - 1] = $values;

			return implode("/", $segment);
		}
		else
		{
			$values = preg_replace("/[&'#]/", "", end($segment));
			$valuess = str_replace('_', 'and', $values);
			$valuess = str_replace(' ', '', $valuess);
		}

		if (strlen($valuess) == 0)
		{
			$valuess = $id;
			$filename = JPath::clean(time() . '_' . $valuess) . "." . $filetype;
		}

		else
		{
			$filename = JPath::clean(time() . '_' . $valuess);
		}

		return $filename;
	}

	public function store($data)
	{
		$dispatcher = JDispatcher::getInstance();

		$catorder = array();
		$oldcategory = array();

		$producthelper = new producthelper;

		$row =& $this->getTable('product_detail');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (isset($data['copy_attribute']))
		{
			if ($data['copy_attribute'] > 0)
			{
				if ($data['attribute_set_id'] <= 0)
					return false;
				$row->attribute_set_id = 0;
			}
		}

		if (!$row->check())
		{
			$this->setError(JText::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));

			return false;
		}

		if (isset($data['thumb_image_delete']))
		{
			$row->product_thumb_image = "";
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['old_thumb_image'];

			if (is_file($unlink_path))
				unlink($unlink_path);
		}

		$thumbfile = JRequest::getVar('product_thumb_image', '', 'files', 'array');

		if ($thumbfile['name'] != "")
		{
			$filename = $this->cleanFileName($thumbfile['name'], $row->product_id);
			$row->product_thumb_image = $filename;

			// Image Upload
			$src = $thumbfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
			JFile::upload($src, $dest);
		}

		// Get File name, tmp_name
		$file = JRequest::getVar('product_full_image', '', 'files', 'array');
		if (isset($data['image_delete']) || $file['name'] != "" || $data['product_image'] != null)
		{
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/thumb/' . $data['old_image'];

			if (is_file($unlink_path))
				unlink($unlink_path);

			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['old_image'];

			if (is_file($unlink_path))
				unlink($unlink_path);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE media_name = "' . $data['old_image'] . '" AND media_section = "product" AND section_id = "' . $row->product_id . '" ';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		if ($file['name'] != "")
		{
			$filename = $this->cleanFileName($file['name'], $row->product_id);
			$row->product_full_image = $filename;

			// Image Upload
			$src = $file['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;

			JFile::upload($src, $dest);
		}
		else
		{
			if ($data['product_image'] != null)
			{
				$image_split = explode('/', $data['product_image']);
				$image_name = $image_split[count($image_split) - 1];
				$image_name = explode("_", $image_name, 2);

				if (strlen($image_name[0]) == 10 && preg_match("/^(\d+)/", $image_name[0]))
				{
					$new_image_name = $image_name[1];
				}
				else
				{
					$new_image_name = $image_split[count($image_split) - 1];
				}
				$filename = $new_image_name;
				$row->product_full_image = $filename;

				$src = JPATH_ROOT . DS . $data['product_image'];
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;

				copy($src, $dest);
			}
		}

		if (isset($data['back_thumb_image_delete']))
		{
			$row->product_back_thumb_image = "";
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_back_thumb_image'];

			if (is_file($unlink_path))
			{
				unlink($unlink_path);
			}
		}

		$backthumbfile = JRequest::getVar('product_back_thumb_image', '', 'files', 'array');

		if ($backthumbfile['name'] != "")
		{
			$filename = $this->cleanFileName($backthumbfile['name'], $row->product_id);
			$row->product_back_thumb_image = $filename;

			// Image Upload
			$src = $backthumbfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
			JFile::upload($src, $dest);
		}

		if (isset($data['back_image_delete']))
		{
			$row->product_back_full_image = "";
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_back_full_image'];

			if (is_file($unlink_path))
			{
				unlink($unlink_path);
			}
		}
		$backthumbfile = JRequest::getVar('product_back_full_image', '', 'files', 'array');

		if ($backthumbfile['name'] != "")
		{
			$filename = $this->cleanFileName($backthumbfile['name'], $row->product_id);
			$row->product_back_full_image = $filename;

			// Image Upload
			$src = $backthumbfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
			JFile::upload($src, $dest);
		}

		// upload product preview image

		if (isset($data['preview_image_delete']))
		{
			$row->product_preview_image = "";
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_preview_image'];

			if (is_file($unlink_path))
				unlink($unlink_path);
		}

		$previewfile = JRequest::getVar('product_preview_image', '', 'files', 'array');

		if ($previewfile['name'] != "")
		{
			$filename = $this->cleanFileName($previewfile['name'], $row->product_id);
			$row->product_preview_image = $filename;

			// Image Upload
			$src = $previewfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
			JFile::upload($src, $dest);
		}

		// Upload product preview back image
		if (isset($data['preview_back_image_delete']))
		{
			$row->product_preview_image = "";
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_preview_back_image'];

			if (is_file($unlink_path))
			{
				unlink($unlink_path);
			}
		}
		$previewbackfile = JRequest::getVar('product_preview_back_image', '', 'files', 'array');

		if ($previewbackfile['name'] != "")
		{
			$filename = $this->cleanFileName($previewfile['name'], $row->product_id);
			$row->product_preview_back_image = $filename;

			// Image Upload
			$src = $previewbackfile['tmp_name'];
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
			JFile::upload($src, $dest);
		}

		$isNew = ($row->product_id > 0) ? false : true;
		JPluginHelper::importPlugin('redshop_product');

		/**
		 * @var array Trigger redSHOP Product Plugin
		 */
		$result = $dispatcher->trigger('onBeforeProductSave', array(&$row, $isNew));

		if (in_array(false, $result, true))
		{
			$this->setError($row->getError());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$dispatcher->trigger('onAfterProductSave', array(&$row, $isNew));

		if (isset($data['copy_attribute']))
		{
			if ($data['copy_attribute'] > 0)
			{
				$row->attribute_set_id = 0;
				$this->copyAttributeSetAttribute($data['attribute_set_id'], $row->product_id);
			}
		}

		if ($data['copy_product'] != 1)
		{
			if ($row->product_full_image != "")
			{
				$media_id = 0;
				$query = "SELECT * FROM " . $this->_table_prefix . "media AS m "
					. "WHERE media_name='" . $data['old_image'] . "' "
					. "AND media_section='product' ";
				$this->_db->setQuery($query);
				$result = $this->_db->loadResult();

				if ($result > 0)
				{
					$media_id = $result->media_id;
				}
				$mediarow = $this->getTable('media_detail');
				$mediapost = array();
				$mediapost['media_id'] = $media_id;
				$mediapost['media_name'] = $row->product_full_image;
				$mediapost['media_section'] = "product";
				$mediapost['section_id'] = $row->product_id;
				$mediapost['media_type'] = "images";
				$mediapost['media_mimetype'] = $file['type'];
				$mediapost['published'] = 1;

				if (!$mediarow->bind($mediapost))
				{
					return false;
				}
				if (!$mediarow->store())
				{
					return false;
				}
			}
		}
		$product_id = $row->product_id;
		$container_id = $data['container_id'];

		if ($container_id != "")
		{
			$sql = "INSERT INTO " . $this->_table_prefix . "container_product_xref (container_id,product_id) value ('" . $container_id . "','" . $product_id . "')";
			$this->_db->setQuery($sql);
			$this->_db->query();
		}

		if (!$data['product_id'])
		{
			$prodid = $row->product_id;
		}
		else
		{
			$prodid = $data['product_id'];
			$cids = implode(",", $data['product_category']);
			$query = "SELECT category_id,ordering FROM " . $this->_table_prefix . "product_category_xref WHERE product_id='" . $prodid . "' AND category_id IN(" . $cids . ")";
			$categories = $this->_getList($query);


			for ($g = 0; $g < count($categories); $g++)
			{
				$oldcategory[$g] = $categories[$g]->category_id;
				$catorder[$categories[$g]->category_id] = $categories[$g]->ordering;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id="' . $prodid . '" ';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		$where_cat_discount = '';

		// Building product categories relationship
		for ($j = 0; $j < count($data ['product_category']); $j++)
		{
			$cat = $data ['product_category'] [$j];
			$ordering = 1;

			if (array_key_exists($cat, $catorder))
			{
				$ordering = $catorder [$cat];
			}
			else
			{
				$queryorder = "SELECT max(ordering)  FROM " . $this->_table_prefix . "product_category_xref WHERE  category_id ='" . $cat . "' ";
				$this->_db->setQuery($queryorder);
				$result = $this->_db->loadResult();
				$ordering = $result + 1;
			}
			$query = 'INSERT INTO ' . $this->_table_prefix . 'product_category_xref(category_id,product_id,ordering) VALUES ("' . $cat . '","' . $prodid . '","' . $ordering . '")';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$where_cat_discount .= " FIND_IN_SET('" . $cat . "',category_id) ";

			if ((count($data ['product_category']) - 1) != $j)
			{
				$where_cat_discount .= ' OR ';
			}
		}

		$category_array = array();
		$category_array = array_diff($data['product_category'], $oldcategory);

		if (count($category_array) > 0)
		{
			$category_array = array_diff($oldcategory, $data['product_category']);
		}

		$sel = "SELECT * FROM " . $this->_table_prefix . "mass_discount WHERE " . $where_cat_discount . " ORDER BY mass_discount_id desc limit 0,1";
		$this->_db->setQuery($sel);
		$mass_discount = $this->_db->loadObject();

		if (count($category_array) > 0)
		{
			$this->updateproductdiscount($mass_discount, $row);
		}

		$sel = "SELECT * FROM " . $this->_table_prefix . "mass_discount WHERE FIND_IN_SET('" . $row->manufacturer_id .
			"',manufacturer_id) ORDER BY mass_discount_id desc limit 0,1";
		$this->_db->setQuery($sel);
		$mass_discount = $this->_db->loadObject();

		if ($data['old_manufacturer_id'] != $row->manufacturer_id)
		{
			$this->updateproductdiscount($mass_discount, $row);
		}

		// Save Stcok and Preorder stock for Product
		if ($data['quantity'] || $data['preorder_stock'])
		{
			$product_id = $row->product_id;

			for ($i = 0; $i < count($data['quantity']); $i++)
			{
				if ($data['ordered_preorder'][$i] > $data['preorder_stock'][$i])
				{
					$msg = JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED');
					JError::raiseWarning('', $msg);

					return false;
				}
				$query = "DELETE FROM " . $this->_table_prefix . "product_stockroom_xref "
					. "WHERE product_id = '" . $product_id . "' and  stockroom_id ='" . $data['stockroom_id'][$i] . "'";
				$this->_db->setQuery($query);

				if (!$this->_db->query())
				{
					return false;
				}
				else
				{
					if ($data['quantity'][$i] != "" || !USE_BLANK_AS_INFINITE)
					{
						$this->insertProductStock($product_id, $data['stockroom_id'][$i], $data['quantity'][$i],
							$data['preorder_stock'][$i], $data['ordered_preorder'][$i]);
					}
				}
			}
		}

		// Building product categories relationship end
		if (count($data['product_accessory']) > 0 && is_array($data['product_accessory']))
		{
			$data['product_accessory'] = array_merge(array(), $data['product_accessory']);

			for ($a = 0; $a < count($data['product_accessory']); $a++)
			{
				$acc = $data['product_accessory'][$a];
				$accdetail =& $this->getTable('accessory_detail');

				if ($data['copy_product'] != 1)
				{
					$accdetail->accessory_id = $acc['accessory_id'];
				}
				$accdetail->product_id = $row->product_id;
				$accdetail->child_product_id = $acc['child_product_id'];
				$accdetail->accessory_price = $acc['accessory_price'];
				$accdetail->oprand = $acc['oprand'];
				$accdetail->ordering = $acc['ordering'];
				$accdetail->setdefault_selected = (isset($acc['setdefault_selected']) && $acc['setdefault_selected'] == 1) ? 1 : 0;

				if (!$accdetail->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		if (count($data['product_navigator']) > 0 && is_array($data['product_navigator']))
		{
			$data['product_navigator'] = array_merge(array(), $data['product_navigator']);

			for ($a = 0; $a < count($data['product_navigator']); $a++)
			{
				$acc = $data['product_navigator'][$a];
				$accdetail =& $this->getTable('navigator_detail');

				if ($data['copy_product'] != 1)
				{
					$accdetail->navigator_id = $acc['navigator_id'];
				}
				$accdetail->product_id = $row->product_id;
				$accdetail->child_product_id = $acc['child_product_id'];
				$accdetail->navigator_name = $acc['navigator_name'];
				$accdetail->ordering = $acc['ordering'];

				if (!$accdetail->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		$query_rel_del = 'DELETE FROM ' . $this->_table_prefix . 'product_related '
			. 'WHERE product_id IN ( ' . $row->product_id . ' )';
		$this->_db->setQuery($query_rel_del);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$ordering_related = 0;

		if (count($data['related_product']) > 0)
		{
			foreach ($data['related_product'] as $related_data)
			{
				$ordering_related = $ordering_related + 1;
				$related_id = $related_data;
				$product_id = $row->product_id;
				$query_related = 'INSERT INTO ' . $this->_table_prefix . 'product_related(related_id,product_id,ordering) VALUES ("' . $related_id . '","' . $product_id . '","' . $ordering_related . '")';
				$this->_db->setQuery($query_related);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		// Discount calculator start
		$query = "DELETE FROM `" . $this->_table_prefix . "product_discount_calc` WHERE product_id='" . $row->product_id . "' ";
		$this->_db->setQuery($query);
		$this->_db->Query();

		if (isset($data['area_start']) && count($data['area_start']) > 0)
		{
			$discount_calc_unit = $data['discount_calc_unit'];
			$area_start = $data['area_start'];
			$area_end = $data['area_end'];
			$area_price = $data['area_price'];
			$discount_calc_id = $data['discount_calc_id'];
			$calc_error = 0;

			for ($c = 0; $c < count($area_start); $c++)
			{
				// Convert whatever unit into meter
				$unit = 1;
				$unit = $producthelper->getUnitConversation("m", $discount_calc_unit[$c]);

				// Replace comma with dot
				$new_area_start = str_replace(",", ".", $area_start[$c]);
				$new_area_end = str_replace(",", ".", $area_end[$c]);

				if ($data['discount_calc_method'] == 'volume')
				{
					$calcunit = pow($unit, 3);
				}

				elseif ($data['discount_calc_method'] == 'area')
				{
					$calcunit = pow($unit, 2);
				}
				else
				{
					$calcunit = $unit;
				}

				// Updating value
				$converted_area_start = $new_area_start * $calcunit;
				$converted_area_end = $new_area_end * $calcunit;

				$calcrow =& $this->getTable('product_discount_calc');
				$calcrow->load();
				$calcrow->discount_calc_unit = $discount_calc_unit[$c];
				$calcrow->area_start = $new_area_start;
				$calcrow->area_end = $new_area_end;
				$calcrow->area_price = $area_price[$c];
				$calcrow->area_start_converted = $converted_area_start;
				$calcrow->area_end_converted = $converted_area_end;
				$calcrow->product_id = $row->product_id;

				if ($calcrow->check())
				{
					if (!$calcrow->store())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}
				else
				{
					$calc_error = 1;
					$err_msg = $calcrow->_error;
				}
			}
		}

		// Discount calculator add extra data
		$query = "DELETE FROM `" . $this->_table_prefix . "product_discount_calc_extra` WHERE product_id='" . $row->product_id . "' ";
		$this->_db->setQuery($query);
		$this->_db->Query();

		if (isset($data['pdc_option_name']) && count($data['pdc_option_name']) > 0)
		{
			$pdc_oprand = $data['pdc_oprand'];
			$pdc_option_name = $data['pdc_option_name'];
			$pdc_price = $data['pdc_price'];
			$pdcextra_id = $data['pdcextra_id'];
			$calc_extra = 0;

			for ($c = 0; $c < count($pdc_option_name); $c++)
			{
				if (trim($pdc_option_name[$c]) != "")
				{
					$pdcextrarow =& $this->getTable('product_discount_calc_extra');
					$pdcextrarow->load();
					$pdcextrarow->pdcextra_id = 0;
					$pdcextrarow->option_name = $pdc_option_name[$c];
					$pdcextrarow->oprand = $pdc_oprand[$c];
					$pdcextrarow->price = $pdc_price[$c];
					$pdcextrarow->product_id = $row->product_id;

					if (!$pdcextrarow->store())
					{
						$calc_extra = 1;
						$extra_err_msg = $this->_db->getErrorMsg();
					}
				}
			}
		}

		if ($calc_error == 1)
		{
			$this->setError($err_msg);

			return false;
		}
		if ($calc_extra == 1)
		{
			$this->setError($extra_err_msg);

			return false;
		}

		// Product subscription start
		if (is_array($data['subscription_id']))
		{
			$sub_cond = " AND subscription_id NOT IN(" . implode(",", $data['subscription_id']) . ")";
		}

		else
		{
			$sub_cond = "";
		}
		$subscription_query = "DELETE FROM `" . $this->_table_prefix . "product_subscription`"
			. "WHERE product_id=" . $row->product_id . $sub_cond;
		$this->_db->setQuery($subscription_query);
		$this->_db->Query();

		if (isset($data['subscription_period']) && count($data['subscription_period']) > 0)
		{
			for ($sub = 0; $sub < count($data['subscription_period']); $sub++)
			{
				$sub_row =& $this->getTable('product_subscription');
				$sub_row->subscription_id = $data['subscription_id'][$sub];
				$sub_row->subscription_period = $data['subscription_period'][$sub];
				$sub_row->period_type = $data['period_type'][$sub];
				$sub_row->subscription_price = $data['subscription_price'][$sub];
				$sub_row->product_id = $row->product_id;

				if (!$sub_row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		// Subscription renewal
		$sub_renewal =& $this->getTable('product_subscription_renewal');
		$sub_renewal->renewal_id = $data['renewal_id'];
		$sub_renewal->before_no_days = $data['before_no_days'];
		$sub_renewal->product_id = $row->product_id;

		if (!$sub_renewal->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// If product_type = file and csv file uploaded than do this
		$productCSVfile = JRequest::getVar('serialcsvFile', '', 'files');

		$ext = strtolower(JFile::getExt($productCSVfile['name']));

		if ($productCSVfile['tmp_name'] != "")
		{
			if ($ext == 'csv')
			{
				if (($handle = fopen($productCSVfile['tmp_name'], "r")) !== FALSE)
				{
					while (($csv_row = fgetcsv($handle, 1000, ",")) !== FALSE)
					{
						if ($csv_row[0] != "")
						{
							$product_serial =& $this->getTable('product_serial_number');
							$product_serial->serial_number = $csv_row[0];
							$product_serial->product_id = $row->product_id;

							if (!$product_serial->store())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
						}
					}
					fclose($handle);
				}
				else
				{
					$this->setError(JText::_("COM_REDSHOP_CSV_FILE_NOT_UPLOADED_TRY_AGAIN"));
				}
			}
			else
			{
				$this->setError(JText::_("COM_REDSHOP_ONLY_CSV_FILE_ALLOWED"));

				return false;
			}
		}

		return $row;
	}

	public function updateproductdiscount($mass_discount, $row)
	{
		if (count($mass_discount) > 0)
		{
			$p_price = ($mass_discount->discount_type == 1) ? ($row->product_price - ($row->product_price * $mass_discount->discount_amount / 100)) : $mass_discount->discount_amount;

			$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="1" '
				. ', discount_price="' . $p_price . '" , discount_stratdate="' . $mass_discount->discount_startdate . '" '
				. ', discount_enddate="' . $mass_discount->discount_enddate . '" WHERE product_id="' . $row->product_id . '" ';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				return false;
			}
		}
	}

	public function delete($cid = array())
	{
		$option = JRequest::getVar('option', '', 'request', 'string');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			if ($cids == "")
			{
				return;
			}

			$query = 'SELECT count( `product_id` ) AS total, `product_parent_id`
						FROM `' . $this->_table_prefix . 'product`
						WHERE `product_parent_id`
						IN ( ' . $cids . ' )
						GROUP BY `product_parent_id`';
			$this->_db->setQuery($query);
			$parentids = $this->_db->loadObjectlist();

			for ($i = 0; $i < count($parentids); $i++)
			{
				$parentid[] = $parentids[$i]->product_parent_id;
				$parentkeys = array_keys($cid, $parentids[$i]->product_parent_id);
				unset($cid[$parentkeys[0]]);
			}

			if (count($parentids) > 0)
			{
				$parentids = implode(',', $parentid);

				$errorMSG = sprintf(JText::_('COM_REDSHOP_PRODUCT_PARENT_ERROR_MSG'), $parentids);
				$this->setError($errorMSG);

				return false;

				$cids = implode(',', $cid);

				if ($cids == "")
				{
					return;
				}
			}

			$image_query = 'SELECT pa.attribute_id,pap.property_image FROM ' . $this->_table_prefix . 'product_attribute as pa,' . $this->_table_prefix . 'product_attribute_property as pap WHERE pa.product_id IN( ' . $cids . ') and pa.attribute_id = pap.attribute_id';
			$this->_db->setQuery($image_query);
			$property_image = $this->_db->loadObjectlist();

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

				// Subattribute delete
				$subattr_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_subattribute_color  WHERE subattribute_id ="' .
					$imagename->property_id . '" ';
				$this->_db->setQuery($subattr_delete);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());
				}

				$attr_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute WHERE attribute_id ="' . $imagename->attribute_id . '" ';
				$this->_db->setQuery($attr_delete);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());
				}

				$prop_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id ="' . $imagename->attribute_id . '" ';
				$this->_db->setQuery($prop_delete);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());
				}
			}

			$image_query = 'SELECT p.product_thumb_image,p.product_full_image,p.product_back_full_image,p.product_back_thumb_image,p.product_preview_image,p.product_preview_back_image  FROM ' . $this->_table_prefix . 'product as p WHERE p.product_id IN( ' . $cids . ')';
			$this->_db->setQuery($image_query);
			$product_image = $this->_db->loadObjectlist();

			foreach ($product_image as $imagename)
			{
				$dest_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_full_image;
				$tsrc_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_thumb_image;
				$dest_back_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_back_full_image;
				$tsrc_back_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_back_thumb_image;
				$dest_preview = REDSHOP_FRONT_IMAGES_RELPATH . '/product/' . $imagename->product_preview_image;
				$tsrc_preview_back = REDSHOP_FRONT_IMAGES_RELPATH . '/product/' . $imagename->product_preview_back_image;

				if (is_file($dest_full))
				{
					unlink($dest_full);
				}

				if (is_file($tsrc_thumb))
				{
					unlink($tsrc_thumb);
				}

				if (is_file($dest_back_full))
				{
					unlink($dest_back_full);
				}

				if (is_file($tsrc_back_thumb))
				{
					unlink($tsrc_back_thumb);
				}

				if (is_file($dest_preview))
				{
					unlink($dest_preview);
				}

				if (is_file($tsrc_preview_back))
				{
					unlink($tsrc_preview_back);
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product WHERE product_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			$query_related = 'DELETE FROM ' . $this->_table_prefix . 'product_accessory WHERE product_id IN ( ' . $cids . ' )';

			$this->_db->setQuery($query_related);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			$query_related = 'DELETE FROM ' . $this->_table_prefix . 'product_related WHERE product_id IN ( ' . $cids . ' )';

			$this->_db->setQuery($query_related);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			$image_media = 'SELECT media_name,media_id FROM ' . $this->_table_prefix . 'media WHERE section_id IN( ' . $cids . ') AND media_section = "product" ';
			$this->_db->setQuery($image_media);
			$media_image = $this->_db->loadObjectlist();

			$query_media = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE section_id IN ( ' . $cids . ' ) AND media_section = "product"';

			$this->_db->setQuery($query_media);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			// Remove product category xref relation
			$query_relation = 'DELETE FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id IN ( ' . $cids . ' ) ';

			$this->_db->setQuery($query_relation);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			// Delete Association if Exist

			$check_asso = $this->CheckRedProductFinder();

			if ($check_asso > 0)
			{

				$this->RemoveAssociation($cid);
			}

			// Remove product tags relation
			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_tags_xref  WHERE product_id IN ( ' . $cids . ' ) ';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			// Remove product wishlist relation
			$query = 'DELETE FROM ' . $this->_table_prefix . 'wishlist_product  WHERE product_id IN ( ' . $cids . ' ) ';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			// Remove product compare relation
			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_compare  WHERE product_id IN ( ' . $cids . ' ) ';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}

			// Remove fields_data relation
			$query = 'DELETE FROM ' . $this->_table_prefix . 'fields_data  WHERE itemid IN ( ' . $cids . ' ) ';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'product'
				. ' SET published = "' . intval($publish) . '" '
				. ' WHERE product_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product WHERE product_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$this->_copydata = $this->_db->loadObjectList();
		}

		foreach ($this->_copydata as $pdata)
		{
			$query = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id IN ( ' . $pdata->product_id . ' )';
			$this->_db->setQuery($query);
			$categorydata = $this->_db->loadObjectList();
			$copycategory = array();

			for ($i = 0; $i < count($categorydata); $i++)
			{
				$copycategory[$i] = $categorydata[$i]->category_id;
			}

			$query = 'SELECT related_id FROM ' . $this->_table_prefix . 'product_related WHERE product_id IN ( ' . $pdata->product_id . ' )';
			$this->_db->setQuery($query);
			$relatedproductdata = $this->_db->loadObjectList();
			$copyrelatedproduct = array();

			for ($i = 0; $i < count($relatedproductdata); $i++)
			{
				$copyrelatedproduct[$i] = $relatedproductdata[$i]->related_id;
			}

			$query = 'SELECT stockroom_id,quantity FROM ' . $this->_table_prefix . 'product_stockroom_xref WHERE product_id IN ( ' . $pdata->product_id . ' )';
			$this->_db->setQuery($query);
			$stockroomdata = $this->_db->loadObjectList();
			$copystockroom = array();
			$copyquantity = array();

			for ($i = 0; $i < count($stockroomdata); $i++)
			{
				$copystockroom[$i] = $stockroomdata[$i]->stockroom_id;
				$copyquantity[$i] = $stockroomdata[$i]->quantity;
			}

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_accessory WHERE product_id IN ( ' . $pdata->product_id . ' )';
			$this->_db->setQuery($query);
			$accessorydata = $this->_db->loadObjectList();
			$copyaccessory = array();
			//accessory_product
			for ($i = 0; $i < count($accessorydata); $i++)
			{
				$copyaccessory[$i] = (array) $accessorydata[$i];
			}

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_price WHERE product_id IN ( ' . $pdata->product_id . ' )';
			$this->_db->setQuery($query);
			$productpricedata = $this->_db->loadObjectList();

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'media WHERE media_section = "product" AND section_id IN ( ' . $pdata->product_id . ' )';
			$this->_db->setQuery($query);
			$mediadata = $this->_db->loadObjectList();


			$post['copy_product'] = 1;
			$post['product_id'] = 0;
			$post['product_parent_id'] = $pdata->product_parent_id;
			$post['manufacturer_id'] = $pdata->manufacturer_id;
			$post['supplier_id'] = $pdata->supplier_id;
			$post['product_on_sale'] = $pdata->product_on_sale;
			$post['product_special'] = $pdata->product_special;
			$post['product_download'] = $pdata->product_download;
			$post['product_template'] = $pdata->product_template;
			$post['product_name'] = JText::_('COM_REDSHOP_COPY_OF') . ' ' . $pdata->product_name;
			$post['product_price'] = $pdata->product_price;
			$post['discount_price'] = $pdata->discount_price;
			$post['discount_stratdate'] = $pdata->discount_stratdate;
			$post['discount_enddate'] = $pdata->discount_enddate;
			$post['product_length'] = $pdata->product_length;
			$post['product_height'] = $pdata->product_height;
			$post['product_width'] = $pdata->product_width;
			$post['product_diameter'] = $pdata->product_diameter;
			$post['discount_calc_method'] = $pdata->discount_calc_method;
			$post['use_discount_calc'] = $pdata->use_discount_calc;
			$post['use_range'] = $pdata->use_range;
			$post['product_number'] = trim(JText::_('COM_REDSHOP_COPY_OF') . ' ' . $pdata->product_number);
			$post['product_type'] = $pdata->product_type;
			$post['product_s_desc'] = $pdata->product_s_desc;
			$post['product_desc'] = $pdata->product_desc;
			$post['product_volume'] = $pdata->product_volume;
			$post['product_tax_id'] = $pdata->product_tax_id;
			$post['attribute_set_id'] = $pdata->attribute_set_id;
			$post['product_tax_group_id'] = $pdata->product_tax_group_id;
			$post['min_order_product_quantity'] = $pdata->min_order_product_quantity;
			$post['max_order_product_quantity'] = $pdata->max_order_product_quantity;
			$post['accountgroup_id'] = $pdata->accountgroup_id;
			$post['quantity_selectbox_value'] = $pdata->quantity_selectbox_value;
			$post['not_for_sale'] = $pdata->not_for_sale;
			$post['product_availability_date'] = $pdata->product_availability_date;
			$post['published'] = 0;
			$post['product_thumb_image'] = '';
			$post['product_full_image'] = '';

			if (!empty($pdata->product_thumb_image))
			{
				$new_product_thumb_image = strstr($pdata->product_thumb_image, '_') ? strstr($pdata->product_thumb_image, '_') : $pdata->product_thumb_image;
				$post['product_thumb_image'] = JPath::clean(time() . $new_product_thumb_image);
			}

			if (!empty($pdata->product_full_image))
			{
				$new_product_full_image = strstr($pdata->product_full_image, '_') ? strstr($pdata->product_full_image, '_') : $pdata->product_full_image;
				$post['product_full_image'] = JPath::clean(time() . $new_product_full_image);
			}

			if (!empty($pdata->product_back_full_image))
			{
				$new_product_back_full_image = strstr($pdata->product_back_full_image, '_') ? strstr($pdata->product_back_full_image, '_') : $pdata->product_back_full_image;
				$post['product_back_full_image'] = JPath::clean(time() . $new_product_back_full_image);
			}

			if (!empty($pdata->product_back_thumb_image))
			{
				$new_product_back_thumb_image = strstr($pdata->product_back_thumb_image, '_') ? strstr($pdata->product_back_thumb_image, '_') : $pdata->product_back_thumb_image;
				$post['product_back_thumb_image'] = JPath::clean(time() . $new_product_back_thumb_image);
			}

			if (!empty($pdata->product_preview_image))
			{
				$new_product_preview_image = strstr($pdata->product_preview_image, '_') ? strstr($pdata->product_preview_image, '_') : $pdata->product_preview_image;
				$post['product_preview_image'] = JPath::clean(time() . $new_product_preview_image);
			}

			if (!empty($pdata->product_preview_back_image))
			{
				$new_product_preview_back_image = strstr($pdata->product_preview_back_image, '_') ? strstr($pdata->product_preview_back_image, '_') : $pdata->product_preview_back_image;
				$post['product_preview_back_image'] = JPath::clean(time() . $new_product_preview_back_image);
			}

			$post['publish_date'] = date("Y-m-d H:i:s");
			$post['update_date'] = date("Y-m-d H:i:s");
			$post['visited'] = $pdata->visited;
			$post['metakey'] = $pdata->metakey;
			$post['metadesc'] = $pdata->metadesc;
			$post['metalanguage_setting'] = $pdata->metalanguage_setting;
			$post['metarobot_info'] = $pdata->metarobot_info;
			$post['pagetitle'] = $pdata->pagetitle;
			$post['pageheading'] = $pdata->pageheading;
			$post['cat_in_sefurl'] = $pdata->cat_in_sefurl;
			$post['weight'] = $pdata->weight;
			$post['expired'] = $pdata->expired;
			$post['product_category'] = $copycategory;
			$post['related_product'] = $copyrelatedproduct;
			$post['quantity'] = $copyquantity;
			$post['stockroom_id'] = $copystockroom;
			$post['product_accessory'] = $copyaccessory;

			if ($row = $this->store($post))
			{
				// Image Copy Start
				$old = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $pdata->product_full_image;
				$new = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_product_full_image);
				copy($old, $new);

				$old_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $pdata->product_thumb_image;
				$new_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_product_thumb_image);
				copy($old_thumb, $new_thumb);

				$old_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $pdata->product_preview_image;
				$new_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_product_preview_image);
				copy($old_preview, $new_preview);

				$old_back_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $pdata->product_preview_back_image;
				$new_back_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_product_preview_back_image);
				copy($old_back_preview, $new_back_preview);

				$old_prod_back_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $pdata->product_back_full_image;
				$new_prod_back_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_product_back_full_image);
				copy($old_prod_back_full, $new_prod_back_full);

				$old_prod_back_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $pdata->product_back_thumb_image;
				$new_back_back_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_product_back_thumb_image);
				copy($old_prod_back_thumb, $new_back_back_thumb);

				$field = new extra_field;
				$list_field = $field->copy_product_extra_field($pdata->product_id, $row->product_id); /// field_section 1 :Product

				//End
				$this->SaveStockroom($row->product_id, $post);
				$this->copyProductAttribute($pdata->product_id, $row->product_id);
				$this->copyDiscountCalcdata($pdata->product_id, $row->product_id, $pdata->discount_calc_method);

				for ($i = 0; $i < count($productpricedata); $i++)
				{
					$rowprices_detail =& $this->getTable('prices_detail');
					$data['price_id '] = 0;
					$data['product_id'] = $row->product_id;
					$data['product_price'] = $productpricedata[$i]->product_price;
					$data['product_currency'] = $productpricedata[$i]->product_currency;
					$data['shopper_group_id'] = $productpricedata[$i]->shopper_group_id;
					$data['price_quantity_start'] = $productpricedata[$i]->price_quantity_start;
					$data['price_quantity_end'] = $productpricedata[$i]->price_quantity_end;

					if (!$rowprices_detail->bind($data))
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}

					if (!$rowprices_detail->store())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}

				for ($j = 0; $j < count($mediadata); $j++)
				{
					$old_img = $mediadata[$j]->media_name;
					$new_img = strstr($old_img, '_') ? strstr($old_img, '_') : $old_img;
					$old_media = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $mediadata[$j]->media_name;
					$new_media = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . JPath::clean(time() . $new_img);
					copy($old_media, $new_media);

					$rowmedia =& $this->getTable('media_detail');
					$data['media_id '] = 0;
					$data['media_name'] = JPath::clean(time() . $new_img);
					$data['media_alternate_text'] = $mediadata[$j]->media_alternate_text;
					$data['media_section'] = $mediadata[$j]->media_section;
					$data['section_id'] = $row->product_id;
					$data['media_type'] = $mediadata[$j]->media_type;
					$data['media_mimetype'] = $mediadata[$j]->media_mimetype;
					$data['published'] = $mediadata[$j]->published;

					if (!$rowmedia->bind($data))
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}

					if (!$rowmedia->store())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}
			}
		}

		return $row;
	}

	public function copyProductAttribute($cid, $product_id)
	{
		$query = 'SELECT attribute_id,`attribute_id`,`attribute_name`,`attribute_required`, `ordering` FROM ' . $this->_table_prefix . 'product_attribute WHERE product_id IN ( ' . $cid . ' ) order by ordering asc';

		$this->_db->setQuery($query);
		$attribute = $this->_db->loadObjectList();
		$attr = array();

		for ($att = 0; $att < count($attribute); $att++)
		{
			$query = 'insert into ' . $this->_table_prefix . 'product_attribute (attribute_name,attribute_required,allow_multiple_selection,hide_attribute_price,product_id,ordering,attribute_set_id) value ("' . $attribute[$att]->attribute_name . '","' . $attribute[$att]->attribute_required . '","' . $attribute[$att]->allow_multiple_selection . '","' . $attribute[$att]->hide_attribute_price . '","' . $product_id . '","' . $attribute[$att]->ordering . '","' . $attribute[$att]->attribute_set_id . '")';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$attribute_id = $this->_db->insertid();
			$query = 'SELECT * FROM `' . $this->_table_prefix . 'product_attribute_property` WHERE `attribute_id` = "' . $attribute[$att]->attribute_id . '" order by ordering asc';
			$this->_db->setQuery($query);
			$att_property = $this->_db->loadObjectList();

			for ($prop = 0; $prop < count($att_property); $prop++)
			{
				$property_save['attribute_id'] = $attribute_id;
				$property_save['property_name'] = $att_property[$prop]->property_name; // encode url for allow special char
				$property_save['property_price'] = $att_property[$prop]->property_price;
				$property_save['oprand'] = $att_property[$prop]->oprand;
				$property_save['property_number'] = $att_property[$prop]->property_number;
				$property_save['property_image'] = $att_property[$prop]->property_image;
				$property_save['property_main_image'] = $att_property[$prop]->property_main_image;
				$property_save['ordering'] = $att_property[$prop]->ordering;
				$property_save['setrequire_selected'] = $att_property[$prop]->setrequire_selected;
				$property_save['setdefault_selected'] = $att_property[$prop]->setdefault_selected;
				$property_array = $this->store_pro($property_save);
				$property_id = $property_array->property_id;
				$listImages = $this->GetimageInfo($att_property[$prop]->property_id, 'property');

				// Update image names and copy
				if (!empty($att_property[$prop]->property_image))
				{
					$property_image = 'product_attributes/' . $att_property[$prop]->property_image;
					$new_property_image = $this->copy_image_from_path($property_image, 'product_attributes', $property_id);
					$property_image = $new_property_image;
				}

				if (!empty($att_property[$prop]->property_main_image))
				{
					$property_main_image = 'property/' . $att_property[$prop]->property_main_image;
					$new_property_main_image = $this->copy_image_from_path($property_main_image, 'property', $property_id);
					$property_main_image = $new_property_main_image;
				}

				$attr_property_image = $this->update_attr_property_image($property_id, $property_image, $property_main_image);

				for ($li = 0; $li < count($listImages); $li++)
				{
					$mImages = array();
					$mImages['media_name'] = $listImages[$li]->media_name;
					$mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
					$mImages['media_section'] = 'property';
					$mImages['section_id'] = $property_id;
					$mImages['media_type'] = 'images';
					$mImages['media_mimetype'] = $listImages[$li]->media_mimetype;
					$mImages['published'] = $listImages[$li]->published;
					$this->copyadditionalImage($mImages);
				}

				$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color WHERE `subattribute_id` =  "' . $att_property[$prop]->property_id . '" order by ordering asc';
				$this->_db->setQuery($query);
				$subatt_property = $this->_db->loadObjectList();

				for ($subprop = 0; $subprop < count($subatt_property); $subprop++)
				{
					$subproperty_save = array();
					$subproperty_save['subattribute_color_name'] = $subatt_property[$subprop]->subattribute_color_name;
					$subproperty_save['subattribute_color_title'] = $subatt_property[$subprop]->subattribute_color_title;
					$subproperty_save['subattribute_color_price'] = $subatt_property[$subprop]->subattribute_color_price;
					$subproperty_save['oprand'] = $subatt_property[$subprop]->oprand;
					$subproperty_save['subattribute_id'] = $property_id;
					$subproperty_save['ordering'] = $subatt_property[$subprop]->ordering;
					$subproperty_save['subattribute_color_number'] = $subatt_property[$subprop]->subattribute_color_number;
					$subproperty_save['setdefault_selected'] = $subatt_property[$subprop]->setdefault_selected;
					$subproperty_array = $this->store_sub($subproperty_save);
					$subproperty_id = $subproperty_array->subattribute_color_id;

					if (!empty($subatt_property[$subprop]->subattribute_color_image))
					{
						$subattribute_color_image = 'subcolor/' . $subatt_property[$subprop]->subattribute_color_image;
						$new_subattribute_color_image = $this->copy_image_from_path($subattribute_color_image, 'subcolor', $subproperty_id);

						$subproperty_array = $this->update_subattr_image($subproperty_id, $new_subattribute_color_image);
					}

					$listsubpropImages = $this->GetimageInfo($subatt_property[$subprop]->subattribute_color_id, 'subproperty');

					for ($lsi = 0; $lsi < count($listsubpropImages); $lsi++)
					{
						$smImages = array();
						$smImages['media_name'] = $listsubpropImages[$lsi]->media_name;
						$smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
						$smImages['media_section'] = 'subproperty';
						$smImages['section_id'] = $subproperty_id;
						$smImages['media_type'] = 'images';
						$smImages['media_mimetype'] = $listsubpropImages[$lsi]->media_mimetype;
						$smImages['published'] = $listsubpropImages[$lsi]->published;
						$this->copyadditionalImage($smImages);
					}
				}
			}
		}
	}

	public function gettax()
	{
		$query = 'SELECT tax_rate_id as value,tax_rate as text FROM ' . $this->_table_prefix . 'tax_rate ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getmanufacturers()
	{
		$query = 'SELECT manufacturer_id as value,manufacturer_name as text FROM ' . $this->_table_prefix . 'manufacturer
		WHERE published=1 ORDER BY `manufacturer_name`';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getsupplier()
	{
		$query = 'SELECT supplier_id as value,supplier_name as text FROM ' . $this->_table_prefix . 'supplier ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getproductcats()
	{
		$query = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref  WHERE product_id="' . $this->_id . '" ';
		$this->_db->setQuery($query);

		return $this->_db->loadResultArray();
	}

	public function catin_sefurl()
	{
		$query = 'SELECT c.category_id as value, c.category_name as text FROM ' . $this->_table_prefix . 'product_category_xref as pcf, ' . $this->_table_prefix . 'category as c  WHERE pcf.product_id="' . $this->_id . '" AND pcf.category_id=c.category_id';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getPropertyImages($property_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_attribute_property as p, " . $this->_table_prefix . "media AS m "
			. "WHERE m.section_id = p.property_id  and m.media_section='property' and media_type='images'
				and p.property_id 	 = '" . $property_id . "'  and m.published = 1 order by m.ordering,m.media_id asc";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getSubpropertyImages($subproperty_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_subattribute_color as p, " . $this->_table_prefix . "media AS m "
			. "WHERE m.section_id = p.subattribute_color_id  and m.media_section='subproperty' and media_type='images'
				and p.subattribute_color_id = '" . $subproperty_id . "'  and m.published = 1 order by m.ordering,m.media_id asc";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getPropertyMainImage($property_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_attribute_property as p "
			. "\nWHERE  p.property_id 	 = '" . $property_id . "' ORDER BY p.property_id ASC  ";
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getSubAttributeColor($property_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_attribute_property AS p "
			. ", " . $this->_table_prefix . "product_subattribute_color AS m "
			. "WHERE m.subattribute_id = p.property_id and p.property_id = '" . $property_id . "' ";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getParentProduct($product_id)
	{
		$query = "SELECT product_name FROM " . $this->_table_prefix . "product "
			. "WHERE product_id = '" . $product_id . "'   ";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function getattributes()
	{
		if ($this->_id != 0)
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute WHERE product_id="' . $this->_id . '" ORDER BY ordering ASC';

			$this->_db->setQuery($query);
			$attr = $this->_db->loadObjectlist();
			$attribute_data = '';
			$newattribute_data = '';

			for ($i = 0; $i < count($attr); $i++)
			{
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id ="'
					. $attr[$i]->attribute_id . '" ORDER BY ordering ASC';

				$this->_db->setQuery($query);
				$prop = $this->_db->loadObjectlist();
				$attribute_id = $attr[$i]->attribute_id;
				$attribute_name = $attr[$i]->attribute_name;
				$attribute_required = $attr[$i]->attribute_required;
				$allow_multiple_selection = $attr[$i]->allow_multiple_selection;
				$hide_attribute_price = $attr[$i]->hide_attribute_price;
				$ordering = $attr[$i]->ordering;
				$attribute_published = $attr[$i]->attribute_published;
				$display_type = $attr[$i]->display_type;

				for ($j = 0; $j < count($prop); $j++)
				{
					$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color WHERE subattribute_id ="'
						. $prop[$j]->property_id . '" ORDER BY ordering ASC';
					$this->_db->setQuery($query);
					$subprop = $this->_db->loadObjectlist();
					$prop[$j]->subvalue = $subprop;
				}

				$attribute_data[] = array('attribute_id' => $attribute_id, 'attribute_name' => $attribute_name,
					'attribute_required' => $attribute_required, 'ordering' => $ordering, 'property' => $prop,
					'allow_multiple_selection' => $allow_multiple_selection, 'hide_attribute_price' => $hide_attribute_price,
					'attribute_published' => $attribute_published, 'display_type' => $display_type);

			}

			return $attribute_data;
		}
		return;
	}

	public function getattributelist($data)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute WHERE product_id="' . $data . '" ORDER BY attribute_id ASC';
		$this->_db->setQuery($query);
		$attr = $this->_db->loadObjectlist();
		$attribute_data = '';

		for ($i = 0; $i < count($attr); $i++)
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id ="'
				. $attr[$i]->attribute_id . '" ORDER BY property_id ASC';
			$this->_db->setQuery($query);
			$prop = $this->_db->loadObjectlist();
			$attribute_id = $attr[$i]->attribute_id;
			$attribute_name = $attr[$i]->attribute_name;
			$attribute_data[] = array('attribute_id' => $attribute_id, 'attribute_name' => $attribute_name, 'property' => $prop);

		}

		return $attribute_data;
	}

	public function getpropertylist($data)
	{
		if (count($data))
		{
			$cids = implode(',', $data);
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_property WHERE property_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$prop = $this->_db->loadObjectlist();
		}

		return $prop;
	}

	public function deleteattr($cid = array())
	{
		$option = JRequest::getVar('option', '', 'request', 'string');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			if ($cids == "") return;

			$prop = product_detailModelproduct_detail::property_image_list($cids);

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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute WHERE attribute_id IN ( ' . $cids . ' )';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id IN ( ' . $cids . ' )';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
	}

	public function deleteprop($cid = array(), $image_name)
	{
		$option = JRequest::getVar('option', '', 'request', 'string');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			if ($cids == "") return;

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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_property WHERE property_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
			else
			{
				// Changed 5 feb
				$query = 'DELETE FROM ' . $this->_table_prefix . 'product_subattribute_color  WHERE subattribute_id IN (' . $cids . ' )';
				$this->_db->setQuery($query);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}
	}

	public function deleteattr_current($cid = array())
	{
		$option = JRequest::getVar('option', '', 'request', 'string');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			if ($cids == "") return;

			$prop = product_detailModelproduct_detail::property_image_list($cids);

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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id IN ( ' . $cids . ' )';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
	}

	public function property_image_list($cid)
	{
		if (count($cid))
		{
			$image_query = 'SELECT property_image FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id IN ( ' . $cid . ' )';
			$this->_db->setQuery($image_query);
			$prop = $this->_db->loadObjectlist();
		}
		return $prop;
	}

	public function store_attr($data)
	{
		$row =& $this->getTable('product_attribute');

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

		return $row;
	}

	// Store Property List
	public function store_pro($data)
	{
		$row =& $this->getTable('attribute_property');

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

		return $row;
	}

	// Store Subattribute Color List
	public function store_sub($data)
	{
		$row =& $this->getTable('subattribute_property');

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

		return $row;
	}

	public function accessory_product_data($product_id)
	{
		$query = "SELECT cp.child_product_id as product_id,p.product_name,cp.accessory_price as price,cp.oprand,p.product_price as normal_price FROM " . $this->_table_prefix . "product as p , " . $this->_table_prefix . "product_accessory as cp  WHERE cp.product_id='" . $product_id . "' and cp.child_product_id=p.product_id ";
		$this->_db->setQuery($query);
		$this->_productdata = $this->_db->loadObjectList();

		return $this->_productdata;
	}

	public function related_product_data($product_id)
	{
		$query = "SELECT cp.related_id as value,p.product_name as text FROM " . $this->_table_prefix . "product as p , " . $this->_table_prefix . "product_related as cp  WHERE cp.product_id='" . $product_id . "' and cp.related_id=p.product_id order by cp.ordering asc";
		$this->_db->setQuery($query);
		$this->_productdata = $this->_db->loadObjectList();

		return $this->_productdata;
	}

	public function property_more_img($post, $main_img, $sub_img)
	{
		if ($main_img['name'] != '')
		{
			$filetype = strtolower(JFile::getExt($main_img['name']));

			if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg')
			{
				return false;
			}

			else
			{
				$main_name = time() . "_" . $main_img['name'];
				$main_src = $main_img['tmp_name'];

				if ($post['fsec'] == 'subproperty')
				{
					$main_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $main_name; //specific path of the file

					JFile::upload($main_src, $main_dest);

					$query = "UPDATE " . $this->_table_prefix . "product_subattribute_color SET subattribute_color_image = '" . $main_name .
						"' WHERE subattribute_color_id ='" . $post['section_id'] . "' ";
					$this->_db->setQuery($query);

					if (!$this->_db->query())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}

				}
				else
				{
					$main_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $main_name;

					JFile::upload($main_src, $main_dest);

					$query = "UPDATE " . $this->_table_prefix . "product_attribute_property SET property_main_image = '" . $main_name
						. "' WHERE property_id ='" . $post['section_id'] . "' ";
					$this->_db->setQuery($query);

					if (!$this->_db->query())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
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
					$sub_name = time() . "_" . $sub_img['name'][$i];

					$sub_src = $sub_img['tmp_name'][$i];

					$sub_type = $sub_img['type'][$i];

					$sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $sub_name; //specific path of the file

					JFile::upload($sub_src, $sub__dest);

					$mediarow = $this->getTable('media_detail');
					$mediapost = array();
					$mediapost['media_id'] = 0;
					$mediapost['media_name'] = $sub_name;
					$mediapost['media_section'] = $post['fsec'];
					$mediapost['section_id'] = $post['section_id'];
					$mediapost['media_type'] = "images";
					$mediapost['media_mimetype'] = $sub_type;
					$mediapost['published'] = 1;

					if (!$mediarow->bind($mediapost))
					{
						return false;
					}

					if (!$mediarow->store())
					{
						return false;
					}
				}
			}
		}
	}

	public function deletesubimage($mediaid)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE media_id = ' . $mediaid;
		$this->_db->setQuery($query);
		$imgdata = $this->_db->loadObject();

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

		$query = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE media_id = "' . $mediaid . '" ';

		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function subattribute_color($post, $sub_img)
	{
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
					$sub_name = time() . "_" . $sub_img['name'][$i];

					$sub_src = $sub_img['tmp_name'][$i];

					$sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $sub_name; //specific path of the file

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

					$subpost = array();
					$subpost['subattribute_color_id'] = $post['subattribute_color_id'][$i];
					$subpost['subattribute_color_name'] = $post['subattribute_name'][$i];
					$subpost['subattribute_color_image'] = $sub_name;
					$subpost['subattribute_id'] = $post['section_id'];
					$subrow = $this->store_sub($subpost);
				}
			}
			else
			{
				if ($post['property_sub_img_tmp'][$i] != "" && $sub_img['name'][$i] == "")
				{
					$subpost = array();
					$subpost['subattribute_color_id'] = $post['subattribute_color_id'][$i];
					$subpost['subattribute_color_name'] = $post['subattribute_name'][$i];
					$subpost['subattribute_color_image'] = $post['property_sub_img_tmp'][$i];
					$subpost['subattribute_id'] = $post['section_id'];
					$subrow = $this->store_sub($subpost);
				}
			}
		}
	}

	public function subattr_diff($subattr_id, $section_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color   WHERE subattribute_id = "' . $section_id . '" and subattribute_color_id NOT IN (\'' . $subattr_id . '\') ORDER BY subattribute_color_id ASC';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function get_subattrprop($subattr_id, $section_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color   WHERE subattribute_color_id IN (\'' . $subattr_id . '\') ORDER BY subattribute_color_id ASC';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function delsubattr_diff($subattr_diff)
	{
		foreach ($subattr_diff as $diff)
		{
			$sub_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $diff->subattribute_color_image;

			if (file_exists($sub_dest))
			{
				unlink($sub_dest);
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_subattribute_color  WHERE subattribute_color_id = "' .
				$diff->subattribute_color_id . '"';
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
	 * Check That redproductfinder is installed or not
	 */
	public function CheckRedProductFinder()
	{
		$query = "SELECT extension_id FROM `#__extensions` WHERE `element` LIKE '%com_redproductfinder%'";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * get Association related to perticular Product
	 */
	public function getAssociation()
	{
		if (!$this->CheckRedProductFinder()) return array();

		$query = "SELECT * FROM `#__redproductfinder_associations` WHERE `product_id` ='" . $this->_id . "'";
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	/**
	 * Show all tags that have been created
	 */
	public function Associations()
	{

		if (!$this->CheckRedProductFinder()) return array();

		/* Get all the fields based on the limits */
		$query = "SELECT a.*, p.product_name
			FROM #__redproductfinder_associations a, " . $this->_table_prefix . "product p
			WHERE a.product_id = p.product_id
			ORDER BY a.ordering";
		$this->_db->setQuery($query);
		$products = $this->_db->loadObjectList();

		return $products;
	}

	/**
	 * Get a multi-select list with types and tags
	 */
	public function TypeTagList()
	{

		if (!$this->CheckRedProductFinder()) return array();

		/* 1. Get all types */
		$q = "SELECT id, type_name FROM #__redproductfinder_types where type_select!='Productfinder datepicker' ORDER by ordering";
		$this->_db->setQuery($q);
		$types = $this->_db->loadAssocList('id');

		/* 2. Go through each type and get the tags */
		if (count($types) > 0)
		{
			foreach ($types as $id => $type)
			{
				$q = "SELECT t.id, tag_name
					FROM #__redproductfinder_tag_type j, #__redproductfinder_tags t
					WHERE j.tag_id = t.id
					AND j.type_id = '" . $id . "'
					ORDER BY t.ordering";
				$this->_db->setQuery($q);
				$types[$id]['tags'] = $this->_db->loadAssocList('id');
			}
		}
		return $types;
	}

	/**
	 * Get the list of selected type names for this tag
	 */
	public function AssociationTagNames()
	{

		if (!$this->CheckRedProductFinder()) return array();

		$id = JRequest::getVar('cid');

		$q = "SELECT association_id, CONCAT(y.type_name, ':', g.tag_name) AS tag_name
			FROM #__redproductfinder_association_tag a
			LEFT JOIN #__redproductfinder_tags g
			ON a.tag_id = g.id
			LEFT JOIN #__redproductfinder_types y
			ON a.type_id = y.id";
		$this->_db->setQuery($q);
		$list = $this->_db->loadObjectList();
		$sortlist = array();

		if (count($list) > 0)
		{
			foreach ($list as $key => $tag)
			{
				$sortlist[$tag->association_id][] = $tag->tag_name;
			}
		}
		return $sortlist;

	}

	/**
	 * Show all tags that have been created
	 */
	public function Tags()
	{

		if (!$this->CheckRedProductFinder()) return array();

		/* Get all the fields based on the limits */
		$query = "SELECT t.* FROM #__redproductfinder_tags t
				LEFT JOIN #__redproductfinder_tag_type y
				ON t.id = y.tag_id ";

		$query .= " GROUP BY t.id
					ORDER BY t.ordering";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	/**
	 * Get the list of selected types for this tag
	 */
	public function AssociationTags($id)
	{
		if (!$this->CheckRedProductFinder())
		{
			return array();
		}

		if (!$id)
		{
			return array();
		}

		else
		{
			$q = "SELECT tag_id
				FROM #__redproductfinder_association_tag
				WHERE association_id = '" . $id . "' ";
			$this->_db->setQuery($q);

			return $this->_db->loadResultArray();
		}
	}

	/**
	 * Save an association
	 */
	public function SaveAssociations($product_id, $post)
	{

		if (!$this->CheckRedProductFinder()) return array();

		$row = $this->getTable('associations');

		/* Get the posted data */
		$association = array();
		$association['id'] = $post['association_id'];
		$association['published'] = 1;
		$association['checked_out'] = 0;
		$association['checked_out_time'] = '0000-00-00 00:00:00';
		$association['ordering'] = 1;
		$association['product_id'] = $product_id;

		if (!$row->bind($association))
		{
			return false;
		}

		/* save the changes */
		if (!$row->store())
		{
			return false;
		}
		else
		{
			/* Delete all tag type relations */
			$q = "DELETE FROM #__redproductfinder_association_tag
		  		WHERE association_id = '" . $row->id . "' ";
			$this->_db->setQuery($q);
			$this->_db->query();
			/* Store the tag type relations */
			$tags = JRequest::getVar('tag_id');
			$qs = JRequest::getVar('qs_id');

			if (count($tags) > 0)
			{
				foreach ($tags as $key => $tag)
				{
					/* Split tag to type ID and tag ID */
					list($type_id, $tag_id) = explode('.', $tag);

					if (empty($qs[$type_id . '.' . $tag_id])) $qs_id = 0;
					else $qs_id = $qs[$type_id . '.' . $tag_id];

					$q = "INSERT IGNORE INTO #__redproductfinder_association_tag
				  		VALUES (" . $row->id . "," . $tag_id . "," . $type_id . ",'" . $qs_id . "')";
					$this->_db->setQuery($q);
					$this->_db->query();
				}
			}
		}

		$row->reorder();

		return true;
	}

	/**
	 * Get all Quality Score values
	 */
	public function getQualityScores()
	{
		if (!$this->CheckRedProductFinder())
		{
			return array();
		}

		$association = $this->getAssociation();

		if ($association)
		{
			$query = "SELECT CONCAT(type_id,'.',tag_id) AS qs_id, quality_score
					FROM #__redproductfinder_association_tag WHERE association_id = '" . $association->id . "' ";
			$this->_db->setQuery($query);

			return $this->_db->loadAssocList('qs_id');
		}
	}

	/**
	 * Delete a product
	 */
	public function RemoveAssociation($cid)
	{
		if (!$this->CheckRedProductFinder())
		{
			return array();
		}

		$database = JFactory::getDBO();

		if (count($cid))
		{
			$cids = 'product_id=' . implode(' OR product_id=', $cid);

			$q = "SELECT id FROM #__redproductfinder_associations"
				. "\n  WHERE ( $cids )";
			$database->setQuery($q);
			$asso = $database->loadObjectList();

			foreach ($asso as $newasso)
			{
				$asscid[] = $newasso->id;
			}

			$query = "DELETE FROM #__redproductfinder_associations"
				. "\n  WHERE ( $cids )";
			$database->setQuery($query);

			if (!$database->query())
			{
			}
			else
			{
				/* Now remove the type associations */
				$cids = 'association_id=' . implode(' OR association_id=', $asscid);
				$query = "DELETE FROM #__redproductfinder_association_tag"
					. "\n  WHERE ( $cids )";
				$database->setQuery($query);
				$database->query();
			}
		}
	}

	/*
	 * get dependent tags
	 */
	public function getDependenttag($product_id = 0, $type_id = 0, $tag_id = 0)
	{
		$where = " product_id='" . $product_id . "'";
		$where .= " AND type_id='" . $type_id . "'";
		$where .= " AND tag_id='" . $tag_id . "'";
		$query = "SELECT dependent_tags FROM #__redproductfinder_dependent_tag WHERE " . $where;
		$this->_db->setQuery($query);
		$rs = $this->_db->loadResult();

		return explode(",", $rs);
	}

	/**
	 * Getting the list of StockRoom
	 */
	public function StockRoomList()
	{
		$database = JFactory::getDBO();

		$q = "SELECT * FROM " . $this->_table_prefix . "stockroom"
			. "\n  WHERE published = 1";
		$database->setQuery($q);
		$arrStockrooms = $database->loadObjectList();

		return $arrStockrooms;
	}

	/**
	 * Getting the  StockRoom Product Quantity
	 */
	public function StockRoomProductQuantity($pid, $sid)
	{
		$database = JFactory::getDBO();

		$q = "SELECT `quantity` FROM `" . $this->_table_prefix . "product_stockroom_xref` WHERE `product_id` = '" . $pid . "' and `stockroom_id` = '" . $sid . "' ";
		$database->setQuery($q);
		$quantity = $database->loadResult();

		return $quantity;
	}

	/**
	 * Getting the  StockRoom Product Quantity
	 */
	public function StockRoomAttProductQuantity($pid, $sid, $section)
	{
		$database = JFactory::getDBO();

		$q = "SELECT `quantity` FROM `" . $this->_table_prefix . "product_attribute_stockroom_xref` WHERE `section_id` = '"
			. $pid . "' and `stockroom_id` = '" . $sid . "' AND section = '" . $section . "'";
		$database->setQuery($q);
		$quantity = $database->loadResult();

		return $quantity;
	}

	public function StockRoomAttProductPreorderstock($pid, $sid, $section)
	{
		$database = JFactory::getDBO();

		$q = "SELECT `preorder_stock`, `ordered_preorder`  FROM `" . $this->_table_prefix . "product_attribute_stockroom_xref`
		WHERE `section_id` = '" . $pid . "' and `stockroom_id` = '" . $sid . "' AND section = '" . $section . "'";
		$database->setQuery($q);
		$preorder_stock_data = $database->loadObjectList();

		return $preorder_stock_data;
	}

	// Getting Preorder Stock Quantity
	public function StockRoomPreorderProductQuantity($pid, $sid, $section)
	{
		$database = JFactory::getDBO();

		$q = "SELECT `preorder_stock`, `ordered_preorder`  FROM `" . $this->_table_prefix . "product_stockroom_xref`
		WHERE `product_id` = '" . $pid . "' and `stockroom_id` = '" . $sid . "' ";
		$database->setQuery($q);
		$preorder_stock_data = $database->loadObjectList();

		return $preorder_stock_data;
	}

	// Store stockroom product xref
	public function SaveStockroom($pid, $post)
	{
		$database = JFactory::getDBO();
		$query = "DELETE FROM " . $this->_table_prefix . "product_stockroom_xref"
			. "\n  WHERE product_id = '" . $pid . "' ";

		$database->setQuery($query);

		if (!$database->query())
		{
			return false;
		}
		else
		{
			for ($i = 0; $i < count($post['quantity']); $i++)
			{
				$this->insertProductStock($pid, $post['stockroom_id'][$i], $post['quantity'][$i]);
			}
		}

		return true;
	}

	public function  attribute_empty()
	{
		$producthelper = new producthelper;
		$database = JFactory::getDBO();

		if ($this->_id)
		{
			$attributes = $producthelper->getProductAttribute($this->_id);

			for ($i = 0; $i < count($attributes); $i++)
			{

				$query = "DELETE FROM `" . $this->_table_prefix . "product_attribute` WHERE `attribute_id` = '"
					. $attributes[$i]->attribute_id . "' ";
				$database->setQuery($query);

				if ($database->query())
				{
					$property = $producthelper->getAttibuteProperty(0, $attributes[$i]->attribute_id);

					for ($j = 0; $j < count($property); $j++)
					{
						$query = "DELETE FROM `" . $this->_table_prefix . "product_attribute_property` WHERE `property_id` = '"
							. $property[$j]->property_id . "' ";
						$database->setQuery($query);

						if ($database->query())
						{

							$query = "DELETE FROM `" . $this->_table_prefix . "product_subattribute_color` WHERE `subattribute_id` = '"
								. $property[$j]->property_id . "' ";
							$database->setQuery($query);
							$database->query();
						}
					}
				}
			}
		}
		return true;
	}

	// remove property image
	public function removepropertyImage($pid)
	{
		$query = "SELECT property_image  FROM `" . $this->_table_prefix . "product_attribute_property` WHERE  property_id = '" . $pid . "' ";
		$this->_db->setQuery($query);
		$image = $this->_db->LoadObject();
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

		$query = "UPDATE `" . $this->_table_prefix . "product_attribute_property` SET `property_image` = '' WHERE `property_id` = '" . $pid . "' ";
		$this->_db->setQuery($query);

		if (!$this->_db->Query())
		{
			return false;
		}
		return true;
	}

	// remove property image
	public function removesubpropertyImage($pid)
	{
		$query = "SELECT subattribute_color_image  FROM `" . $this->_table_prefix . "product_subattribute_color` WHERE  subattribute_color_id = '" . $pid . "' ";
		$this->_db->setQuery($query);
		$image = $this->_db->LoadObject();
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

		$query = "UPDATE `" . $this->_table_prefix . "product_subattribute_color` SET `subattribute_color_image` = '' WHERE `subattribute_color_id` = '" . $pid . "' ";
		$this->_db->setQuery($query);

		if (!$this->_db->Query())
		{
			return false;
		}
		return true;
	}

	//
	public function getQuantity($stockroom_type, $sid, $pid)
	{
		$product = " AND product_id='" . $pid . "' ";
		$section = "";
		$stock = "";
		$table = "product";

		if ($stockroom_type != 'product')
		{
			$product = " AND section_id='" . $pid . "' ";
			$section = " AND section = '" . $stockroom_type . "' ";
			$table = "product_attribute";
		}

		if ($sid != 0)
		{
			$stock = "AND stockroom_id='" . $sid . "' ";
		}
		$query = "SELECT * FROM " . $this->_table_prefix . $table . "_stockroom_xref "
			. "WHERE 1=1 "
			. $stock //"AND stockroom_id='".$sid."' "
			. $product . $section;

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function SaveAttributeStockroom($post)
	{
		$product = " AND section_id='" . $post['section_id'] . "' ";
		$section = " AND section = '" . $post['section'] . "' ";
		$table = "product_attribute";


		for ($i = 0; $i < count($post['quantity']); $i++)
		{
			$preorder_stock = $post['preorder_stock'][$i];
			$ordered_preorder = $post['ordered_preorder'][$i];
			$sid = $post['stockroom_id'][$i];
			$quantity = $post['quantity'][$i];
			$stock_update = false;
			$list = $this->getQuantity($post['section'], $sid, $post['section_id']);
			$query = "";

			if (count($list) > 0)
			{
				if ($quantity == "" && USE_BLANK_AS_INFINITE)
				{
					$query = "DELETE FROM " . $this->_table_prefix . $table . "_stockroom_xref "
						. " WHERE stockroom_id='" . $post['stockroom_id'][$i] . "' " . $product . $section;
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
				else
				{
					if (($preorder_stock < $ordered_preorder) && $preorder_stock != "" && $ordered_preorder != "")
					{
						$msg = JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED');
						JError::raiseWarning('', $msg);

						return false;

					}
					else
					{
						$query = "UPDATE " . $this->_table_prefix . $table . "_stockroom_xref "
							. "SET quantity='" . $quantity . "' , preorder_stock= '" . $preorder_stock . "'"
							. " WHERE stockroom_id='" . $sid . "'"
							. $product . $section;
						$this->_db->setQuery($query);
						$this->_db->Query();
						$stock_update = true;
					}
				}
			}
			else
			{
				if ($preorder_stock < $ordered_preorder && $preorder_stock != "" && $ordered_preorder != "")
				{
					$msg = JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED');
					JError::raiseWarning('', $msg);

					return false;
				}
				else
				{
					if ($preorder_stock != "" || $quantity != "")
					{
						if ($quantity == "" && USE_BLANK_AS_INFINITE)
						{
							$query = "";
						}
						else
						{
							if ($quantity == "")
							{
								$quantity = 0;
							}
							$this->InsertStockroom($post['section_id'], $post['section'], $post['stockroom_id'][$i], $post['quantity'][$i],
								$preorder_stock, $ordered_preorder);
							$stock_update = true;
						}
					}
				}
			}
			if ($stock_update)
			{
				//For stockroom Notify Email
				$stockroom_data = array();
				$stockroom_data['section'] = $post['section'];
				$stockroom_data['section_id'] = $post['section_id'];
				$stockroom_data['regular_stock'] = $quantity;
				$stockroom_data['preorder_stock'] = $preorder_stock;
				JPluginHelper::importPlugin('redshop_product');
				$dispatcher = JDispatcher::getInstance();
				$data = $dispatcher->trigger('afterUpdateStock', array($stockroom_data));
				//End
			}
		}

		return true;
	}

	public function getVatGroup()
	{
		$query = "SELECT tg.tax_group_name as text, tg.tax_group_id as value FROM `" . $this->_table_prefix . "tax_group` as tg WHERE `published` = 1 ORDER BY tax_group_id ASC";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	/*
	 * save product ordering
	 * @params: $cid - array , $order-array
	 * $cid= product ids
	 * $order = product current ordring
	 * @return: boolean
	 */
	public function saveorder($cid = array(), $order)
	{
		$app = JFactory::getApplication();
		// get global category id
		$category_id_my = $app->getUserStateFromRequest('category_id', 'category_id', 0);
		// init array
		$orderarray = array();

		for ($i = 0; $i < count($cid); $i++)
		{
			// set product id as key AND order as value
			$orderarray[$cid[$i]] = $order[$i];
		}
		// sorting array using value ( order )
		asort($orderarray);
		$i = 1;

		if (count($orderarray) > 0)
		{
			foreach ($orderarray as $productid => $order)
			{
				if ($order >= 0)
				{
					// update ordering
					$query = 'UPDATE ' . $this->_table_prefix . 'product_category_xref' . ' SET ordering = ' . ( int ) $i . ' WHERE product_id=' . $productid . ' AND category_id = ' . $category_id_my;
					$this->_db->setQuery($query);
					$this->_db->query();
				}
				$i++;
			}
		}
		return true;
	}

	public function orderup()
	{
		$app = JFactory::getApplication();
		$category_id_my = $app->getUserStateFromRequest('category_id', 'category_id', 0);
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$cid = $cid[0];

		$q = "SELECT ordering,category_id," . $this->_table_prefix . "product.product_id FROM " . $this->_table_prefix . "product," . $this->_table_prefix . "product_category_xref ";
		$q .= "WHERE " . $this->_table_prefix . "product_category_xref.product_id='" . $cid . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.category_id='" . $category_id_my . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.product_id = " . $this->_table_prefix . "product.product_id";
		echo '<br/>';

		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();

		$currentpos = $cat->ordering;
		$category_id = $cat->category_id;

		$q = "SELECT " . $this->_table_prefix . "product.product_id FROM " . $this->_table_prefix . "product, " . $this->_table_prefix . "product_category_xref ";
		$q .= "WHERE " . $this->_table_prefix . "product_category_xref.category_id='" . $category_id . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.product_id=" . $this->_table_prefix . "product.product_id AND category_id= '" . $category_id_my . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.ordering='" . intval($currentpos - 1) . "'";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();

		$pred = $cat->product_id;

		$q = "UPDATE " . $this->_table_prefix . "product_category_xref ";
		$q .= "SET ordering=ordering-1 ";
		$q .= "WHERE product_id='" . $cid . "' AND ordering >1 AND category_id = '" . $category_id_my . "' ";
		$this->_db->setQuery($q);
		$this->_db->query();

		$q = "UPDATE " . $this->_table_prefix . "product_category_xref ";
		$q .= "SET ordering=ordering+1 ";
		$q .= "WHERE product_id='" . $pred . "' AND category_id = '" . $category_id_my . "' ";
		$this->_db->setQuery($q);
		$this->_db->query();
	}

	public function orderdown()
	{
		$app = JFactory::getApplication();
		$category_id_my = $app->getUserStateFromRequest('category_id', 'category_id', 0);
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$cid = $cid[0];

		$q = "SELECT ordering,category_id," . $this->_table_prefix . "product.product_id FROM " . $this->_table_prefix . "product," . $this->_table_prefix . "product_category_xref ";
		$q .= "WHERE " . $this->_table_prefix . "product_category_xref.product_id='" . $cid . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.category_id='" . $category_id_my . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.product_id = " . $this->_table_prefix . "product.product_id";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();
		$currentpos = $cat->ordering;
		$category_id = $cat->category_id;

		$q = "SELECT ordering," . $this->_table_prefix . "product.product_id FROM " . $this->_table_prefix . "product, " . $this->_table_prefix . "product_category_xref ";
		$q .= "WHERE " . $this->_table_prefix . "product_category_xref.category_id='" . $category_id . "' ";
		$q .= "AND " . $this->_table_prefix . "product_category_xref.product_id=" . $this->_table_prefix . "product.product_id AND category_id= '" . $category_id_my . "'";
		$q .= "AND ordering='" . intval($currentpos + 1) . "'";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();
		$succ = $cat->product_id;

		$q = "UPDATE " . $this->_table_prefix . "product_category_xref ";
		$q .= "SET ordering=ordering+1 ";
		$q .= "WHERE product_id='" . $cid . "' AND category_id = '" . $category_id_my . "'  ";
		$this->_db->setQuery($q);
		$this->_db->query();

		$q = "UPDATE " . $this->_table_prefix . "product_category_xref ";
		$q .= "SET ordering=ordering-1 ";
		$q .= "WHERE product_id='" . $succ . "' AND category_id = '" . $category_id_my . "' ";
		$this->_db->setQuery($q);
		$this->_db->query();
	}

	// Product ordering end

	public function getDiscountCalcData()
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "product_discount_calc` "
			. "WHERE product_id = '" . $this->_id . "' ORDER BY area_start ";

		return $this->_getList($query);
	}

	public function getDiscountCalcDataExtra()
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "product_discount_calc_extra` "
			. "WHERE product_id = '" . $this->_id . "' ORDER BY option_name ";

		return $this->_getList($query);
	}

	// product subscription detail
	public function getSubscription()
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "product_subscription` "
			. "WHERE product_id = '" . $this->_id . "' order by subscription_id";

		return $this->_getList($query);
	}

	public function getSubscriptionrenewal()
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "subscription_renewal` "
			. "WHERE product_id ='" . $this->_id . "' ";

		return $this->_getList($query);
	}

	public function getAttributeSetList()
	{
		$query = "SELECT attribute_set_id as value,	attribute_set_name as text FROM `" . $this->_table_prefix . "attribute_set` "
			. "WHERE published  = 1";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	/*
	 *  Get serial numbers for downloadable products.
	 */
	public function getProdcutSerialNumbers($only_used = '')
	{
		$usedCond = "";

		if ($only_used == true)
			$usedCond = " AND is_used=1";
		else if ($only_used == false)
			$usedCond = " AND is_used=0";

		$query = "SELECT * FROM `" . $this->_table_prefix . "product_serial_number` "
			. "WHERE product_id = '" . $this->_id . "' " . $usedCond;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function deleteProdcutSerialNumbers($serial_id)
	{
		$query = "DELETE FROM " . $this->_table_prefix . "product_serial_number "
			. "WHERE serial_id = '" . $serial_id . "'";
		$this->_db->setQuery($query);

		if (!$this->_db->Query())
		{
			return false;
		}
		return true;
	}

	public function GetimageInfo($id, $type)
	{
		$image_media = 'SELECT * FROM ' . $this->_table_prefix . 'media WHERE section_id = "' . $id . '" AND media_section = "' . $type . '" ';
		$this->_db->setQuery($image_media);

		return $this->_db->loadObjectlist();
	}

	public function copyadditionalImage($data)
	{
		$src_image = $data['media_name'];
		$old_imgname = strstr($data['media_name'], '_') ? strstr($data['media_name'], '_') : $data['media_name'];
		$new_imgname = JPath::clean(time() . $old_imgname);
		$data['media_name'] = $new_imgname;
		$rowmedia =& $this->getTable('media_detail');
		$data['media_id '] = 0;

		if (!$rowmedia->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		$section = $data['media_section'];
		$path = $section . '/' . $src_image;
		$property_image = $this->copy_image_additionalimage_from_path($path, $data['media_section'], $data['section_id']);
		//$data['media_name'] = $property_image;

		if (!$rowmedia->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
	}

	public function copy_image_additionalimage_from_path($imagePath, $section, $section_id)
	{
		$src = REDSHOP_FRONT_IMAGES_RELPATH . $imagePath;

		$imgname = basename($imagePath);
		$imgname = strstr($imgname, '_') ? strstr($imgname, '_') : $imgname;
		$property_image = JPath::clean(time() . $imgname);
		$dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;


		copy($src, $dest);

		return $property_image;
	}

	public function copyAttributeSetAttribute($attribute_set_id, $product_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute WHERE attribute_set_id ="' . $attribute_set_id . '" ';
		$this->_db->setQuery($query);
		$attribute = $this->_db->loadObjectList();
		$attr = array();

		for ($att = 0; $att < count($attribute); $att++)
		{
			$attpost = array();
			$attpost['attribute_id'] = 0;
			$attpost['attribute_name'] = $attribute[$att]->attribute_name;
			$attpost['attribute_required'] = $attribute[$att]->attribute_required;
			$attpost['product_id'] = $product_id;
			$attpost['ordering'] = $attribute[$att]->ordering;
			$attpost['allow_multiple_selection'] = $attribute[$att]->allow_multiple_selection;
			$attpost['hide_attribute_price'] = $attribute[$att]->hide_attribute_price;
			$attpost['display_type'] = $attribute[$att]->display_type;
			$attpost['attribute_published'] = $attribute[$att]->attribute_published;
			$attrow = $this->store_attr($attpost);
			$attribute_id = $attrow->attribute_id;

			$query = 'SELECT * FROM `' . $this->_table_prefix . 'product_attribute_property` WHERE `attribute_id` = "' . $attribute[$att]->attribute_id . '" ';
			$this->_db->setQuery($query);
			$att_property = $this->_db->loadObjectList();

			for ($prop = 0; $prop < count($att_property); $prop++)
			{
				$listImages = $this->GetimageInfo($att_property[$prop]->property_id, 'property');
				$listStockroomData = $this->GetStockroomData($att_property[$prop]->property_id, 'property');
				$listAttributepriceData = $this->GetAttributepriceData($att_property[$prop]->property_id, 'property');

				if ($att_property[$prop]->property_image)
				{
					$image_split = $att_property[$prop]->property_image;
					$filename = JPath::clean(time() . '_' . $image_split); //Make the filename unique
					$att_property[$prop]->property_image = $filename;
					$src = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $image_split;
					$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $filename;
					copy($src, $dest);
				}
				if ($att_property[$prop]->property_main_image)
				{
					$prop_main_img = $att_property[$prop]->property_main_image;
					$image_split = $att_property[$prop]->property_main_image;
					$image_split = explode('_', $image_split);
					$image_split = $image_split[1];
					$filename = JPath::clean(time() . '_' . $image_split); //Make the filename unique
					$att_property[$prop]->property_main_image = $filename;
					$src = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $prop_main_img;
					$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $filename;
					copy($src, $dest);
				}
				$proppost = array();
				$proppost['property_id'] = 0;
				$proppost['attribute_id'] = $attribute_id;
				$proppost['property_name'] = $att_property[$prop]->property_name;
				$proppost['property_price'] = $att_property[$prop]->property_price;
				$proppost['oprand'] = $att_property[$prop]->oprand;
				$proppost['property_image'] = $att_property[$prop]->property_image;
				$proppost['property_main_image'] = $att_property[$prop]->property_main_image;
				$proppost['ordering'] = $att_property[$prop]->ordering;
				$proppost['setdefault_selected'] = $att_property[$prop]->setdefault_selected;
				$proppost['setrequire_selected'] = $att_property[$prop]->setrequire_selected;
				$proppost['setdisplay_type'] = $att_property[$prop]->setdisplay_type;
				$proprow = $this->store_pro($proppost);
				$property_id = $proprow->property_id;

				for ($ls = 0; $ls < count($listStockroomData); $ls++)
				{
					$this->InsertStockroom($property_id, 'property', $listStockroomData[$ls]->stockroom_id, $listStockroomData[$ls]->quantity);
				}
				for ($lp = 0; $lp < count($listAttributepriceData); $lp++)
				{
					$this->InsertAttributeprice($property_id, 'property', $listAttributepriceData[$lp]->product_price, $listAttributepriceData[$lp]->product_currency, $listAttributepriceData[$lp]->shopper_group_id, $listAttributepriceData[$lp]->price_quantity_start, $listAttributepriceData[$lp]->price_quantity_end, $listAttributepriceData[$lp]->discount_price, $listAttributepriceData[$lp]->discount_start_date, $listAttributepriceData[$lp]->discount_end_date);
				}
				for ($li = 0; $li < count($listImages); $li++)
				{
					$mImages = array();
					$mImages['media_name'] = $listImages[$li]->media_name;
					$mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
					$mImages['media_section'] = 'property';
					$mImages['section_id'] = $property_id;
					$mImages['media_type'] = 'images';
					$mImages['media_mimetype'] = $listImages[$li]->media_mimetype;
					$mImages['published'] = $listImages[$li]->published;
					$this->copyadditionalImage($mImages);
				}
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color WHERE `subattribute_id` =  "' . $att_property[$prop]->property_id . '" ';
				$this->_db->setQuery($query);
				$subatt_property = $this->_db->loadObjectList();

				for ($subprop = 0; $subprop < count($subatt_property); $subprop++)
				{
					$listsubpropImages = $this->GetimageInfo($subatt_property[$subprop]->subattribute_color_id, 'subproperty');
					$listSubStockroomData = $this->GetStockroomData($subatt_property[$subprop]->subattribute_color_id, 'subproperty');
					$listSubAttributepriceData = $this->GetAttributepriceData($subatt_property[$subprop]->subattribute_color_id, 'subproperty');

					if ($subatt_property[$subprop]->subattribute_color_image)
					{
						$image_split = $subatt_property[$subprop]->subattribute_color_image;
						$filename = JPath::clean(time() . '_' . $image_split); //Make the filename unique
						$subatt_property[$subprop]->subattribute_color_image = $filename;
						$src = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $image_split;
						$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $filename;
						copy($src, $dest);
					}
					if ($subatt_property[$subprop]->subattribute_color_main_image)
					{
						$sub_main_img = $subatt_property[$subprop]->subattribute_color_main_image;
						$image_split = $subatt_property[$subprop]->subattribute_color_main_image;
						$image_split = explode('_', $image_split);
						$image_split = $image_split[1];
						$filename = JPath::clean(time() . '_' . $image_split); //Make the filename unique

						$subatt_property[$subprop]->subattribute_color_main_image = $filename;
						$src = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $sub_main_img;
						$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $filename;
						copy($src, $dest);
					}

					$subpost = array();
					$subpost['subattribute_color_id'] = 0;
					$subpost['subattribute_color_name'] = $subatt_property[$subprop]->subattribute_color_name;
					$subpost['subattribute_color_title'] = htmlspecialchars($subatt_property[$subprop]->subattribute_color_title);
					$subpost['subattribute_color_price'] = $subatt_property[$subprop]->subattribute_color_price;
					$subpost['oprand'] = $subatt_property[$subprop]->oprand;
					$subpost['ordering'] = $subatt_property[$subprop]->ordering;
					$subpost['subattribute_color_image'] = $subatt_property[$subprop]->subattribute_color_image;
					$subpost['subattribute_id'] = $property_id;
					$subpost['setdefault_selected'] = $subatt_property[$subprop]->setdefault_selected;
					$subpost['subattribute_color_main_image'] = $subatt_property[$subprop]->subattribute_color_main_image;
					$subpost['subattribute_color_number'] = "";
					$subrow = $this->store_sub($subpost);
					$subproperty_id = $subrow->subattribute_color_id;

					for ($lsi = 0; $lsi < count($listsubpropImages); $lsi++)
					{
						$smImages = array();
						$smImages['media_name'] = $listsubpropImages[$lsi]->media_name;
						$smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
						$smImages['media_section'] = 'subproperty';
						$smImages['section_id'] = $subproperty_id;
						$smImages['media_type'] = 'images';
						$smImages['media_mimetype'] = $listsubpropImages[$lsi]->media_mimetype;
						$smImages['published'] = $listsubpropImages[$lsi]->published;
						$this->copyadditionalImage($smImages);
					}
					for ($lss = 0; $lss < count($listSubStockroomData); $lss++)
					{
						$this->InsertStockroom($subproperty_id, 'subproperty', $listSubStockroomData[$lss]->stockroom_id, $listSubStockroomData[$lss]->quantity);
					}
					for ($lsp = 0; $lsp < count($listSubAttributepriceData); $lsp++)
					{
						$this->InsertAttributeprice($subproperty_id, 'subproperty', $listSubAttributepriceData[$lsp]->product_price, $listSubAttributepriceData[$lsp]->product_currency, $listSubAttributepriceData[$lsp]->shopper_group_id, $listSubAttributepriceData[$lsp]->price_quantity_start, $listSubAttributepriceData[$lsp]->price_quantity_end, $listSubAttributepriceData[$lsp]->discount_price, $listSubAttributepriceData[$lsp]->discount_start_date, $listSubAttributepriceData[$lsp]->discount_end_date);
					}
				}
			}
		}
	}

	public function GetStockroomData($section_id, $name)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_stockroom_xref '
			. 'WHERE `section_id` =  "' . $section_id . '" '
			. 'AND section="' . $name . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function insertProductStock($product_id, $stockroom_id, $quantiy = 0, $preorder_stock = 0, $ordered_preorder = 0)
	{
		$query = 'INSERT INTO ' . $this->_table_prefix . 'product_stockroom_xref '
			. '(product_id,stockroom_id,quantity,preorder_stock,	ordered_preorder) '
			. 'VALUE("' . $product_id . '","' . $stockroom_id . '","' . $quantiy . '","' . $preorder_stock . '","' . $ordered_preorder . '")';
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		//For stockroom Notify Email
		$stockroom_data = array();
		$stockroom_data['section'] = "product";
		$stockroom_data['section_id'] = $product_id;
		$stockroom_data['regular_stock'] = $quantiy;
		$stockroom_data['preorder_stock'] = $preorder_stock;
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = JDispatcher::getInstance();
		$data = $dispatcher->trigger('afterUpdateStock', array($stockroom_data));

		return true;
	}

	public function InsertStockroom($section_id, $name, $stockroom_id, $quantiy, $preorder_stock, $ordered_preorder)
	{
		$query = 'INSERT INTO ' . $this->_table_prefix . 'product_attribute_stockroom_xref '
			. '(section_id,section,stockroom_id,quantity,preorder_stock, ordered_preorder) '
			. 'VALUE("' . $section_id . '","' . $name . '","' . $stockroom_id . '","' . $quantiy . '","' . $preorder_stock . '","' . $ordered_preorder . '")';
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		return true;
	}

	public function GetAttributepriceData($section_id, $name)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_price WHERE `section_id` =  "' . $section_id . '" and section="' . $name . '" ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function InsertAttributeprice($section_id, $name, $product_price, $product_currency, $shopper_group_id, $price_quantity_start, $price_quantity_end, $discount_price, $discount_start_date, $discount_end_date)
	{
		$row = $this->getTable('product_attribute_price_detail');
		$post = array();
		$post['price_id'] = 0;
		$post['section_id'] = $section_id;
		$post['section'] = $name;
		$post['product_price'] = $product_price;
		$post['product_currency'] = $product_currency;
		$post['cdate'] = time();
		$post['shopper_group_id'] = $shopper_group_id;
		$post['price_quantity_start'] = $price_quantity_start;
		$post['price_quantity_end'] = $price_quantity_end;
		$post['discount_price'] = $discount_price;
		$post['discount_start_date'] = $discount_start_date;
		$post['discount_end_date'] = $discount_end_date;

		if (!$row->bind($post))
		{
			return false;
		}
		if (!$row->store())
		{
			return false;
		}
	}

	/**
	 * Method to checkout/lock the product_detail
	 *
	 * @access    public
	 *
	 * @param    int $uid    User ID of the user checking the helloworl detail out
	 *
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid))
			{
				$user = JFactory::getUser();
				$uid = (int) $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$product_detail = $this->getTable('product_detail');

			if (!$product_detail->checkout($uid, $this->_id))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Method to checkin/unlock the product_detail
	 *
	 * @access    public
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function checkin()
	{
		if ($this->_id)
		{
			$product_detail = & $this->getTable('product_detail');

			if (!$product_detail->checkin($this->_id))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		return false;
	}

	/**
	 * Tests if product_detail is checked out
	 *
	 * @access    public
	 *
	 * @param    int    A user id
	 *
	 * @return    boolean    True if checked out
	 * @since    1.5
	 */
	public function isCheckedOut($uid = 0)
	{
		if ($this->_loadData())
		{
			if ($uid)
			{
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			}
			else
			{
				return $this->_data->checked_out;
			}
		}
	}

	public function delete_subprop($sp, $subattribute_id)
	{
		$and = "";
		$producthelper = new producthelper;

		if ($sp)
		{
			$subproperty[0]->subattribute_color_id = $sp;
			$subproperty = $producthelper->getAttibuteSubProperty($sp, $subattribute_id);
		}
		else
		{
			$subproperty = $producthelper->getAttibuteSubProperty(0, $subattribute_id);
		}
		for ($j = 0; $j < count($subproperty); $j++)
		{
			$query = "DELETE FROM `" . $this->_table_prefix . "product_subattribute_color` WHERE `subattribute_id` = '" . $subattribute_id . "' and subattribute_color_id= '" . $subproperty[$j]->subattribute_color_id . "'";
			$this->_db->setQuery($query);
			$this->_db->query();
			$this->delete_image($subproperty[$j]->subattribute_color_image, 'subcolor');
		}
		exit;
	}

	public function delete_prop($attribute_id, $property_id)
	{
		$producthelper = new producthelper;

		if ($property_id)
		{
			$property[0]->property_id = $property_id;
			$property = $producthelper->getAttibuteProperty($property_id, $attribute_id);
		}
		else
		{
			$property = $producthelper->getAttibuteProperty(0, $attribute_id);
		}
		for ($j = 0; $j < count($property); $j++)
		{
			$property_id = $property[$j]->property_id;
			$query = "DELETE FROM `" . $this->_table_prefix . "product_attribute_property` WHERE `attribute_id`='" . $attribute_id . "' and `property_id` = '" . $property[$j]->property_id . "' ";
			$this->_db->setQuery($query);

			if ($this->_db->query())
			{
				$this->delete_image($property[$j]->property_image, 'product_attributes');
				$this->delete_subprop(0, $property_id);
			}
		}
		exit;
	}

	public function delete_attibute($product_id, $attribute_id, $attribute_set_id)
	{
		$producthelper = new producthelper;

		if (empty($attribute_set_id) && empty($product_id))
		{
			exit;
		}
		if ($attribute_id)
		{
			$attributes[0]->attribute_id = $attribute_id;
		}
		else
		{
			if ($product_id)
			{
				$attributes = $producthelper->getProductAttribute($product_id);
			}
			else
			{
				$attributes = $producthelper->getProductAttribute(0, $attribute_set_id);
			}
		}
		if ($product_id)
		{
			$and = "`product_id`='" . $product_id . "'";
		}
		else
		{
			$and = "`attribute_set_id`='" . $attribute_set_id . "'";
		}
		for ($i = 0; $i < count($attributes); $i++)
		{
			$query = "DELETE FROM `" . $this->_table_prefix . "product_attribute` WHERE " . $and . " and `attribute_id` = '" . $attributes[$i]->attribute_id . "' ";
			$this->_db->setQuery($query);

			if ($this->_db->query())
			{
				$this->delete_prop($attributes[$i]->attribute_id, 0);
			}
		}
		exit;
	}

	public function delete_image($imagename, $section)
	{
		$imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . $section . "/" . $imagename;

		if (is_file($imagesrcphy))
		{
			@unlink($imagesrcphy);
		}
	}

	public function copy_image($imageArray, $section, $section_id)
	{
		$src = $imageArray['tmp_name'];
		$imgname = basename($imageArray['name']);
		$imgname = $this->cleanFileName($imageArray['name']);
		$property_image = $section_id . '_' . $imgname;
		$dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;
		copy($src, $dest);

		return $property_image;
	}

	public function copy_image_from_path($imagePath, $section, $section_id)
	{
		$src = JPATH_ROOT . DS . $imagePath;

		$imgname = $this->cleanFileName($imagePath);

		$property_image = end(explode("/", $imgname));

		$dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;
		copy($src, $dest);

		return $property_image;
	}

	public function checkVirtualNumber($product_id = 0, $vpnArray = array())
	{
		if (count($vpnArray) > 0)
		{
			$strVPN = "'" . implode("','", $vpnArray) . "'";
			$query = "SELECT COUNT(product_number) FROM `" . $this->_table_prefix . "product` "
				. "WHERE product_number IN (" . $strVPN . ") ";
			$this->_db->setQuery($query);
			$there = $this->_db->loadResult();

			if ($there > 0)
			{
				return true;
			}
			$query = "SELECT ap.property_number AS number "
				. "FROM " . $this->_table_prefix . "product_attribute_property AS ap "
				. "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.attribute_id=ap.attribute_id "
				. "WHERE a.product_id!='" . $product_id . "' "
				. "AND ap.property_number IN (" . $strVPN . ") "
				. "UNION "
				. "SELECT sp.subattribute_color_number AS number FROM " . $this->_table_prefix . "product_subattribute_color AS sp "
				. "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS ap ON ap.property_id=sp.subattribute_id "
				. "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.attribute_id=ap.attribute_id "
				. "WHERE a.product_id!='" . $product_id . "' "
				. "AND sp.subattribute_color_number IN (" . $strVPN . ") ";

			$this->_db->setQuery($query);
			$list = $this->_db->loadObjectList();

			if (count($list) > 0)
			{
				return true;
			}

			return false;
		}

		return true;
	}

	public function getChildProducts()
	{
		$products = $this->getAllChildProductArrayList(0, $this->_id);
		$product_id = $product_name = array();

		for ($i = 0; $i < count($products); $i++)
		{
			$product = $products[$i];
			$product_id[] = $product->product_id;
			$product_name[] = $product->product_name;
		}

		$prod = new stdClass;
		$prod->name = $product_name;
		$prod->id = $product_id;

		return $prod;
	}

	public function getAllChildProductArrayList($childid = 0, $parentid = 0)
	{
		$producthelper = new producthelper ;
		$info = $producthelper->getChildProduct($parentid);

		for ($i = 0; $i < count($info); $i++)
		{
			if ($childid != $info[$i]->product_id)
			{
				$GLOBALS['childproductlist'][] = $info[$i];
				$this->getAllChildProductArrayList($childid, $info[$i]->product_id);
			}
		}
		return $GLOBALS['childproductlist'];
	}

	public function removeaccesory($accessory_id, $category_id = 0, $child_product_id = 0)
	{
		$and = '';

		if ($category_id != 0)
		{
			$and .= 'AND category_id="' . $category_id . '" ';
		}
		if ($child_product_id != 0)
		{
			$and .= 'AND child_product_id="' . $child_product_id . '" ';
		}
		if ($and != "")
		{
			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_accessory '
				. 'WHERE accessory_id="' . $accessory_id . '" '
				. $and;
		}
		else
		{
			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_accessory '
				. 'WHERE accessory_id="' . $accessory_id . '" ';
		}
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		else
		{
			return true;
		}
	}

	public function removenavigator($navigator_id)
	{
		$query = 'DELETE FROM ' . $this->_table_prefix . 'product_navigator '
			. 'WHERE navigator_id="' . $navigator_id . '" ';
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		else
		{
			return true;
		}
	}

	public function ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid)
	{
		$query = "";
		$product = " AND product_id='" . $pid . "' ";
		$section = "";
		$table = "product";

		if ($stockroom_type != 'product')
		{
			$product = " AND section_id='" . $pid . "' ";
			$section = " AND section = '" . $stockroom_type . "' ";
			$table = "product_attribute";
		}


		$query = "UPDATE " . $this->_table_prefix . $table . "_stockroom_xref "
			. "SET preorder_stock='0' , ordered_preorder= '0'"
			. " WHERE stockroom_id='" . $sid . "'"
			. $product . $section;

		if ($query != "")
		{
			$this->_db->setQuery($query);
			$this->_db->Query();
		}

	}

	public function update_attr_property_image($property_id, $property_image, $property_main_image)
	{

		$query = "UPDATE " . $this->_table_prefix . "product_attribute_property"
			. " SET property_image='" . $property_image . "' , property_main_image= '" . $property_main_image . "'"
			. " WHERE property_id='" . $property_id . "'";
		$this->_db->setQuery($query);
		$this->_db->Query();

	}

	public function update_subattr_image($subproperty_id, $subattribute_color_image)
	{
		$query = "UPDATE " . $this->_table_prefix . "product_subattribute_color"
			. " SET subattribute_color_image='" . $subattribute_color_image . "'"
			. " WHERE subattribute_color_id='" . $subproperty_id . "'";
		$this->_db->setQuery($query);
		$this->_db->Query();
	}

	public function copyDiscountCalcdata($old_product_id, $new_product_id, $discount_calc_method)
	{
		$producthelper = new producthelper;
		$query = "Select * FROM `" . $this->_table_prefix . "product_discount_calc` WHERE product_id='" . $old_product_id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		for ($i = 0; $i < count($list); $i++)
		{
			$discount_calc_unit = $list[$i]->discount_calc_unit;
			$area_start = $list[$i]->area_start;
			$area_end = $list[$i]->area_end;
			$area_price = $list[$i]->area_price;

			$calc_error = 0;

			$unit = 1;
			$unit = $producthelper->getUnitConversation("m", $discount_calc_unit);

			// replace comma with dot
			$new_area_start = str_replace(",", ".", $area_start);
			$new_area_end = str_replace(",", ".", $area_end);

			if ($discount_calc_method == 'volume')
			{
				$calcunit = pow($unit, 3);
			}
			else if ($discount_calc_method == 'area')
			{
				$calcunit = pow($unit, 2);
			}
			else
			{
				$calcunit = $unit;
			}

			//updating value
			$converted_area_start = $new_area_start * $calcunit;
			$converted_area_end = $new_area_end * $calcunit;
			//End
			$calcrow =& $this->getTable('product_discount_calc');
			$calcrow->load();
			$calcrow->discount_calc_unit = $discount_calc_unit;
			$calcrow->area_start = $new_area_start;
			$calcrow->area_end = $new_area_end;
			$calcrow->area_price = $area_price;
			$calcrow->area_start_converted = $converted_area_start;
			$calcrow->area_end_converted = $converted_area_end;
			$calcrow->product_id = $new_product_id;

			if ($calcrow->check())
			{

				if (!$calcrow->store())
				{

					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
			else
			{

				$calc_error = 1;
				$err_msg = $calcrow->_error;
				//return false;
			}

		}

		// discount calc extra data
		$query_extra = "Select * FROM `" . $this->_table_prefix . "product_discount_calc_extra` WHERE product_id='" . $old_product_id . "' ";
		$this->_db->setQuery($query_extra);
		$list_extra = $this->_db->loadObjectList();

		for ($i = 0; $i < count($list_extra); $i++)
		{
			$pdc_oprand = $list_extra[$i]->pdc_oprand;
			$pdc_option_name = $list_extra[$i]->option_name;
			$pdc_price = $list_extra[$i]->price;
			$pdc_oprand = $list_extra[$i]->oprand;

			$calc_extra = 0;

			if (trim($pdc_option_name) != "")
			{
				$pdcextrarow =& $this->getTable('product_discount_calc_extra');
				$pdcextrarow->load();
				$pdcextrarow->pdcextra_id = 0;
				$pdcextrarow->option_name = $pdc_option_name;
				$pdcextrarow->oprand = $pdc_oprand;
				$pdcextrarow->price = $pdc_price;
				$pdcextrarow->product_id = $new_product_id;

				if (!$pdcextrarow->store())
				{
					$calc_extra = 1;
					$extra_err_msg = $this->_db->getErrorMsg();
				}
			}
		}

	}
}
