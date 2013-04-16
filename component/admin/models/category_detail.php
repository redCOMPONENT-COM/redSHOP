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

require_once JPATH_COMPONENT . '/helpers/extra_field.php';
require_once JPATH_COMPONENT . '/helpers/thumbnail.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class category_detailModelcategory_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
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
			$query = 'SELECT c.*,p.category_parent_id FROM ' . $this->_table_prefix . 'category as c left join '
				. $this->_table_prefix . 'category_xref as p ON p.category_child_id=c.category_id  WHERE category_id = "'
				. $this->_id . '" ';
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
			$detail->category_id = 0;
			$detail->category_name = null;
			$detail->category_short_description = null;
			$detail->category_more_template = null;
			$detail->category_description = null;
			$detail->category_template = 0;
			$detail->products_per_page = 5;
			$detail->category_full_image = null;
			$detail->category_thumb_image = null;
			$detail->category_back_full_image = null;
			$detail->metakey = null;
			$detail->metadesc = null;
			$detail->metalanguage_setting = null;
			$detail->metarobot_info = null;
			$detail->pagetitle = null;
			$detail->pageheading = null;
			$detail->sef_url = null;
			$detail->published = 1;
			$detail->compare_template_id = 0;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Storing image name in the database
		$option = JRequest::getVar('option');

		$filename = "";

		// Get File name, tmp_name
		$file = JRequest::getVar('category_full_image', array(), 'files', 'array');

		if (count($file) > 0)
		{
			// Make the filename unique
			$filename = JPath::clean(time() . '_' . $file['name']);
			$filename = str_replace(" ", "_", $filename);
		}

		if (isset($data['image_delete']))
		{
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_image']);
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_image']);

			$query = "UPDATE " . $this->_table_prefix . "category set category_thumb_image = '',category_full_image = ''  where category_id ="
				. $row->category_id;
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		if (count($_FILES) > 0 && $_FILES['category_full_image']['name'] != "")
		{
			$newwidth = THUMB_WIDTH;
			$newheight = THUMB_HEIGHT;

			$row->category_full_image = $filename;
			$row->category_thumb_image = $filename;

			// Get extension of the file
			$filetype = JFile::getExt($file['name']);

			$src = $file['tmp_name'];

			// Specific path of the file
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $filename;

			JFile::upload($src, $dest);
		}
		else
		{
			if (isset($data['category_image']) && $data['category_image'] != null)
			{

				$image_split = explode('/', $data['category_image']);

				// Make the filename unique
				$filename = JPath::clean(time() . '_' . $image_split[count($image_split) - 1]);
				$row->category_full_image = $filename;
				$row->category_thumb_image = $filename;

				// Image Upload
				$newwidth = THUMB_WIDTH;
				$newheight = THUMB_HEIGHT;

				$src = JPATH_ROOT . DS . $data['category_image'];
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $filename;

				copy($src, $dest);
			}
		}

		// Get File name, tmp_name
		$backfile = JRequest::getVar('category_back_full_image', '', 'files', 'array');

		if (isset($data['image_back_delete']))
		{
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_back_image']);
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_back_image']);

			$query = "UPDATE " . $this->_table_prefix . "category set category_back_full_image = ''  where category_id =" . $row->category_id;
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		if (count($backfile) > 0 && $backfile['name'] != "")
		{
			// Make the filename unique
			$filename = JPath::clean(time() . '_' . $backfile['name']);
			$row->category_back_full_image = $filename;

			// Get extension of the file
			$filetype = JFile::getExt($backfile['name']);

			$src = $backfile['tmp_name'];

			// Specific path of the file
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $filename;

			JFile::upload($src, $dest);
		}

		// Upload back image end
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$data['category_id'])
		{
			$newcatid = $this->_db->insertid();

			if (isset($_POST['category_parent_id']))
			{
				$parentcat = $_POST['category_parent_id'];
			}
			else
			{
				$parentcat = $data['category_parent_id'];
			}

			$query = 'INSERT INTO ' . $this->_table_prefix . 'category_xref(category_parent_id,category_child_id) VALUES ("'
				. $parentcat . '","' . $newcatid . '");';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		else
		{
			$newcatid = $data['category_id'];

			if (isset($_POST['category_parent_id']))
			{
				$parentcat = $_POST['category_parent_id'];
			}
			else
			{
				$parentcat = $data['category_parent_id'];
			}

			$query = 'UPDATE ' . $this->_table_prefix . 'category_xref SET category_parent_id= "' . $parentcat
				. '"  WHERE category_child_id = "' . $newcatid . '" ';
			$this->_db->setQuery($query);
			$this->_db->query();

			// Sheking for the image at the updation time
			if ($_FILES['category_full_image']['name'] != "")
			{
				@unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $_POST['old_image']);
				@unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $_POST['old_image']);
			}
		}

		// Extra Field Data Saved
		$field = new extra_field;
		$field->extra_field_save($data, 2, $newcatid);

		// Start Accessory Product
		if (count($data['product_accessory']) > 0 && is_array($data['product_accessory']))
		{
			$data['product_accessory'] = array_merge(array(), $data['product_accessory']);

			$product_category = new product_category;
			$product_list = $product_category->getCategoryProductList($newcatid);

			for ($p = 0; $p < count($product_list); $p++)
			{
				$product_id = $product_list[$p]->id;

				for ($a = 0; $a < count($data['product_accessory']); $a++)
				{
					$acc = $data['product_accessory'][$a];

					$accessory_id = $product_category->CheckAccessoryExists($product_id, $acc['child_product_id']);

					if ($product_id != $acc['child_product_id'])
					{
						$accdetail =& $this->getTable('accessory_detail');

						$accdetail->accessory_id = $accessory_id;
						$accdetail->category_id = $newcatid;
						$accdetail->product_id = $product_id;
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
			}
		}

		// End Accessory Product insert
		return $row;
	}

	public function delete($cid = array())
	{
		$noError = true;

		for ($i = 0; $i < count($cid); $i++)
		{
			$query = 'SELECT count( * ) as ctotal,c.category_name
						FROM `' . $this->_table_prefix . 'category_xref` as cx LEFT JOIN `' . $this->_table_prefix
				. 'category` as c ON c.category_id = "' . $cid[$i] . '"
						WHERE `category_parent_id` = "' . $cid[$i] . '" ';
			$this->_db->setQuery($query);
			$childs = $this->_db->loadObject();

			if ($childs->ctotal > 0)
			{
				$noError = false;
				$errorMSG = sprintf(JText::_('COM_REDSHOP_CATEGORY_PARENT_ERROR_MSG'), $childs->category_name, $cid[$i]);
				$this->setError($errorMSG);
				break;
			}

			$q_image = 'SELECT category_thumb_image,category_full_image FROM ' . $this->_table_prefix . 'category WHERE category_id = "' . $cid[$i] . '" ';
			$this->_db->setQuery($q_image);
			$catimages = $this->_db->loadObject();

			$cat_thumb_image = $catimages->category_thumb_image;
			$cat_full_image = $catimages->category_full_image;

			$thumb_path = REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $cat_thumb_image;
			$full_image_path = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $cat_full_image;

			if (file_exists($thumb_path))
			{
				@unlink($thumb_path);
			}

			if (file_exists($full_image_path))
			{
				@unlink($full_image_path);
			}

			$q_product = 'DELETE FROM ' . $this->_table_prefix . 'product_category_xref WHERE category_id = "' . $cid[$i] . '" ';
			$this->_db->setQuery($q_product);
			$this->_db->query();

			$q_child = 'DELETE FROM ' . $this->_table_prefix . 'category_xref WHERE category_child_id = "' . $cid[$i] . '" ';
			$this->_db->setQuery($q_child);
			$this->_db->query();

			$query = 'DELETE FROM ' . $this->_table_prefix . 'category WHERE category_id = "' . $cid[$i] . '" ';
			$this->_db->setQuery($query);
			$this->_db->query();

		}

		return $noError;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'category'
				. ' SET published = "' . intval($publish) . '" '
				. ' WHERE category_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getcategories()
	{
		$query = 'SELECT category_id as value,category_name as text FROM ' . $this->_table_prefix . 'category  WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function move($direction)
	{
		$row =& $this->getTable();

		if (!$row->load($this->_id))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->move($direction, ' category_id = ' . (int) $row->category_id . ' AND published >= 0 '))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		// Update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->category_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}

	public function updateorder($oprand, $cat_id = 0)
	{
		$q = "UPDATE " . $this->_table_prefix . "category ";
		$q .= "SET ordering=ordering" . $oprand . "1 ";

		if ($cat_id)
		{
			$q .= " WHERE ordering != 0 ";
		}

		$this->_db->setQuery($q);
		$this->_db->query();
	}

	public function orderup()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$cid = $cid[0];
		$q = "SELECT ordering,category_parent_id FROM " . $this->_table_prefix . "category," . $this->_table_prefix . "category_xref ";
		$q .= "WHERE category_id='" . $cid . "' ";
		$q .= "AND category_child_id='" . $cid . "' ";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();
		$currentpos = $cat->ordering;
		$category_parent_id = $cat->category_parent_id;

		$q = "SELECT ordering," . $this->_table_prefix . "category.category_id FROM " . $this->_table_prefix . "category, "
			. $this->_table_prefix . "category_xref ";
		$q .= "WHERE " . $this->_table_prefix . "category_xref.category_parent_id='" . $category_parent_id . "' ";
		$q .= "AND " . $this->_table_prefix . "category_xref.category_child_id=" . $this->_table_prefix . "category.category_id ";
		$q .= "AND ordering='" . intval($currentpos - 1) . "'";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();
		$pred = $cat->category_id;

		$morder = $this->getmaxminOrder('min');

		if ($currentpos > $morder)
		{
			$q = "UPDATE " . $this->_table_prefix . "category ";
			$q .= "SET ordering=ordering-1 ";
			$q .= "WHERE category_id='" . $cid . "'";
			$this->_db->setQuery($q);
			$this->_db->query();

			$q = "UPDATE " . $this->_table_prefix . "category ";
			$q .= "SET ordering=ordering+1 ";
			$q .= "WHERE category_id='" . $pred . "' ";
			$this->_db->setQuery($q);
			$this->_db->query();
		}
	}

	public function orderdown()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$cid = $cid[0];

		$q = "SELECT ordering,category_parent_id FROM " . $this->_table_prefix . "category," . $this->_table_prefix . "category_xref ";
		$q .= "WHERE category_id='" . $cid . "' ";
		$q .= "AND category_child_id='" . $cid . "' ";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();
		$currentpos = $cat->ordering;
		$category_parent_id = $cat->category_parent_id;

		$q = "SELECT ordering," . $this->_table_prefix . "category.category_id FROM " . $this->_table_prefix . "category, "
			. $this->_table_prefix . "category_xref ";
		$q .= "WHERE " . $this->_table_prefix . "category_xref.category_parent_id='" . $category_parent_id . "' ";
		$q .= "AND " . $this->_table_prefix . "category_xref.category_child_id=" . $this->_table_prefix . "category.category_id ";
		$q .= "AND ordering='" . intval($currentpos + 1) . "'";
		$this->_db->setQuery($q);
		$cat = $this->_db->loadObject();
		$succ = $cat->category_id;

		$morder = $this->getmaxminOrder('max');

		if ($currentpos < $morder)
		{
			$q = "UPDATE " . $this->_table_prefix . "category ";
			$q .= "SET ordering=ordering+1 ";
			$q .= "WHERE category_id='" . $cid . "' ";
			$this->_db->setQuery($q);
			$this->_db->query();

			$q = "UPDATE " . $this->_table_prefix . "category ";
			$q .= "SET ordering=ordering-1 ";
			$q .= "WHERE category_id='" . $succ . "'";
			$this->_db->setQuery($q);
			$this->_db->query();
		}
	}

	public function getmaxminOrder($type)
	{
		$q = "SELECT " . $type . "(ordering) as morder FROM " . $this->_table_prefix . "category";

		$this->_db->setQuery($q);
		$cat = $this->_db->loadResult();

		return $cat;
	}

	public function getProductCompareTemplate()
	{
		$query = "SELECT ts.template_section as text, ts.template_id as value FROM `" . $this->_table_prefix
			. "template` as ts WHERE `published` = 1 AND `template_section`='compare_product'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function copy($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'category WHERE category_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$copydata = $this->_db->loadObjectList();

			for ($i = 0; $i < count($copydata); $i++)
			{
				$query = 'SELECT category_parent_id FROM ' . $this->_table_prefix . 'category_xref '
					. 'WHERE category_child_id="' . $copydata[$i]->category_id . '" ';
				$this->_db->setQuery($query);
				$category_parent_id = $this->_db->loadResult();

				$post = array();
				$newwidth = THUMB_WIDTH;
				$newheight = THUMB_HEIGHT;

				$post['category_id'] = 0;
				$post['category_name'] = "copy" . $copydata[$i]->category_name;
				$post['category_short_description'] = $copydata[$i]->category_short_description;
				$post['category_description'] = $copydata[$i]->category_description;
				$post['category_template'] = $copydata[$i]->category_template;
				$post['category_more_template'] = $copydata[$i]->category_more_template;
				$post['products_per_page'] = $copydata[$i]->products_per_page;
				$post['category_full_image'] = "copy" . $copydata[$i]->category_full_image;
				$post['category_thumb_image'] = "copy" . $copydata[$i]->category_thumb_image;
				$post['metakey'] = $copydata[$i]->metakey;
				$post['metadesc'] = $copydata[$i]->metadesc;
				$post['metalanguage_setting'] = $copydata[$i]->metalanguage_setting;
				$post['metarobot_info'] = $copydata[$i]->metarobot_info;
				$post['pagetitle'] = $copydata[$i]->pagetitle;
				$post['pageheading'] = $copydata[$i]->pageheading;
				$post['sef_url'] = $copydata[$i]->sef_url;
				$post['published'] = $copydata[$i]->published;
				$post['category_pdate'] = date("Y-m-d h:i:s");
				$post['ordering'] = count($copydata) + $i + 1;

				$post['category_parent_id'] = $category_parent_id;

				$src = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $copydata[$i]->category_full_image;
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $post['category_full_image'];

				if (is_file($src))
				{
					JFile::upload($src, $dest);
				}

				$row = $this->store($post);
			}
		}

		return true;
	}
}
