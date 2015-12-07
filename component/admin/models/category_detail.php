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
JLoader::load('RedshopHelperAdminThumbnail');
JLoader::load('RedshopHelperAdminCategory');
JLoader::load('RedshopHelperAdminImages');
JLoader::load('RedshopHelperAdminTemplate');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class RedshopModelCategory_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;


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
		if (!$this->_loadData())
		{
			$this->_initData();
		}

		if (!empty($_POST))
		{
			$this->_data = $this->setPostData($this->_data, array('category_full_image', 'category_thumb_image', 'category_back_full_image'));
		}

		return $this->_data;
	}

	/**
	 * Method to get extra fields to category.
	 *
	 * @param   integer  $item  The object category values.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getExtraFields($item)
	{
		$redshopTemplate = new Redtemplate;
		$template_desc = $redshopTemplate->getTemplate('category', $item->category_template, '', true);
		$template = $template_desc[0]->template_desc;
		$regex = '/{rs_[\w]{1,}\}/';
		preg_match_all($regex, $template, $matches);
		$listField = array();

		if (count($matches[0]) > 0)
		{
			$dbname = implode(',', $matches[0]);
			$dbname = str_replace(array('{', '}'), '', $dbname);
			$field = new extra_field;
			$listField[] = $field->list_all_field(2, $item->category_id, $dbname);
		}

		return implode('', $listField);
	}

	public function _loadData()
	{
		$db = JFactory::getDbo();

		if (empty($this->_data))
		{
			$query = 'SELECT c.*,p.category_parent_id FROM #__redshop_category as c left join '
				. '#__redshop_category_xref as p ON p.category_child_id=c.category_id  WHERE category_id = "'
				. $this->_id . '" ';
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
			$detail->append_to_global_seo = 'append';
			$detail->canonical_url = '';
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	 * Set POST data
	 *
	 * @param   object  $details   Init data object
	 * @param   array   $excludes  Exclude array fields
	 *
	 * @return  object
	 */
	public function setPostData($details, $excludes = array())
	{
		$postData  = JFactory::getApplication()->input->getArray($_POST);
		$details = (array) $details;

		foreach ($details as $key => $detail)
		{
			if (isset($postData[$key]) && array_search($postData[$key], $excludes) === false)
			{
				$details[$key] = $postData[$key];
			}
		}

		return (object) $details;
	}

	public function store($data)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());

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
			$filename = RedShopHelperImages::cleanFileName($file['name']);
		}

		if (isset($data['image_delete']))
		{
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_image']);
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_image']);

			$query = "UPDATE #__redshop_category set category_thumb_image = '',category_full_image = ''  where category_id ="
				. $row->category_id;
			$db->setQuery($query);
			$db->execute();
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
				$filename = RedShopHelperImages::cleanFileName($image_split[count($image_split) - 1]);
				$row->category_full_image = $filename;
				$row->category_thumb_image = $filename;

				// Image Upload
				$newwidth = THUMB_WIDTH;
				$newheight = THUMB_HEIGHT;

				$src = JPATH_ROOT . '/' . $data['category_image'];
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

			$query = "UPDATE #__redshop_category set category_back_full_image = ''  where category_id =" . $row->category_id;
			$db->setQuery($query);
			$db->execute();
		}

		if (count($backfile) > 0 && $backfile['name'] != "")
		{
			// Make the filename unique
			$filename = RedShopHelperImages::cleanFileName($backfile['name']);
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
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$data['category_id'])
		{
			$newcatid = $db->insertid();

			if (isset($_POST['category_parent_id']))
			{
				$parentcat = $_POST['category_parent_id'];
			}
			else
			{
				$parentcat = $data['category_parent_id'];
			}

			$query = 'INSERT INTO #__redshop_category_xref(category_parent_id,category_child_id) VALUES ("'
				. $parentcat . '","' . $newcatid . '");';
			$db->setQuery($query);
			$db->execute();
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

			$query = 'UPDATE #__redshop_category_xref SET category_parent_id= "' . $parentcat
				. '"  WHERE category_child_id = "' . $newcatid . '" ';
			$db->setQuery($query);
			$db->execute();

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
						$accdetail = $this->getTable('accessory_detail');

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
							$this->setError($db->getErrorMsg());

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
		$db = JFactory::getDbo();
		$noError = true;

		for ($i = 0; $i < count($cid); $i++)
		{
			$query = 'SELECT count( * ) as ctotal,c.category_name
						FROM `#__redshop_category_xref` as cx LEFT JOIN `#__redshop_category` as c ON c.category_id = "' . $cid[$i] . '"
						WHERE `category_parent_id` = "' . $cid[$i] . '" ';
			$db->setQuery($query);
			$childs = $db->loadObject();

			if ($childs->ctotal > 0)
			{
				$noError = false;
				$errorMSG = sprintf(JText::_('COM_REDSHOP_CATEGORY_PARENT_ERROR_MSG'), $childs->category_name, $cid[$i]);
				$this->setError($errorMSG);
				break;
			}

			$q_image = 'SELECT category_thumb_image,category_full_image FROM #__redshop_category WHERE category_id = "' . $cid[$i] . '" ';
			$db->setQuery($q_image);
			$catimages = $db->loadObject();

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

			$q_product = 'DELETE FROM #__redshop_product_category_xref WHERE category_id = "' . $cid[$i] . '" ';
			$db->setQuery($q_product);
			$db->execute();

			$q_child = 'DELETE FROM #__redshop_category_xref WHERE category_child_id = "' . $cid[$i] . '" ';
			$db->setQuery($q_child);
			$db->execute();

			$query = 'DELETE FROM #__redshop_category WHERE category_id = "' . $cid[$i] . '" ';
			$db->setQuery($query);
			$db->execute();

		}

		return $noError;
	}

	public function publish($cid = array(), $publish = 1)
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE #__redshop_category'
				. ' SET published = "' . intval($publish) . '" '
				. ' WHERE category_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getcategories()
	{
		$db = JFactory::getDbo();
		$query = 'SELECT category_id as value,category_name as text FROM #__redshop_category  WHERE published=1';
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function move($direction)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable();

		if (!$row->load($this->_id))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->move($direction, ' category_id = ' . (int) $row->category_id . ' AND published >= 0 '))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function saveorder($cid = array(), $order)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable();
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
					$this->setError($db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}

	public function updateorder($oprand, $cat_id = 0)
	{
		$db = JFactory::getDbo();
		$q = "UPDATE #__redshop_category ";
		$q .= "SET ordering=ordering" . $oprand . "1 ";

		if ($cat_id)
		{
			$q .= " WHERE ordering != 0 ";
		}

		$db->setQuery($q);
		$db->execute();
	}

	public function orderup()
	{
		$db = JFactory::getDbo();
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$cid = $cid[0];
		$q = "SELECT ordering,category_parent_id FROM #__redshop_category,#__redshop_category_xref ";
		$q .= "WHERE category_id='" . $cid . "' ";
		$q .= "AND category_child_id='" . $cid . "' ";
		$db->setQuery($q);
		$cat = $db->loadObject();
		$currentpos = $cat->ordering;
		$category_parent_id = $cat->category_parent_id;

		$q = "SELECT ordering,#__redshop_category.category_id FROM #__redshop_category, "
			. "#__redshop_category_xref ";
		$q .= "WHERE #__redshop_category_xref.category_parent_id='" . $category_parent_id . "' ";
		$q .= "AND #__redshop_category_xref.category_child_id=#__redshop_category.category_id ";
		$q .= "AND ordering='" . intval($currentpos - 1) . "'";
		$db->setQuery($q);
		$cat = $db->loadObject();
		$pred = $cat->category_id;

		$morder = $this->getmaxminOrder('min');

		if ($currentpos > $morder)
		{
			$q = "UPDATE #__redshop_category ";
			$q .= "SET ordering=ordering-1 ";
			$q .= "WHERE category_id='" . $cid . "'";
			$db->setQuery($q);
			$db->execute();

			$q = "UPDATE #__redshop_category ";
			$q .= "SET ordering=ordering+1 ";
			$q .= "WHERE category_id='" . $pred . "' ";
			$db->setQuery($q);
			$db->execute();
		}
	}

	public function orderdown()
	{
		$db = JFactory::getDbo();
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$cid = $cid[0];

		$q = "SELECT ordering,category_parent_id FROM #__redshop_category,#__redshop_category_xref ";
		$q .= "WHERE category_id='" . $cid . "' ";
		$q .= "AND category_child_id='" . $cid . "' ";
		$db->setQuery($q);
		$cat = $db->loadObject();
		$currentpos = $cat->ordering;
		$category_parent_id = $cat->category_parent_id;

		$q = "SELECT ordering,#__redshop_category.category_id FROM #__redshop_category, "
			. "#__redshop_category_xref ";
		$q .= "WHERE #__redshop_category_xref.category_parent_id='" . $category_parent_id . "' ";
		$q .= "AND #__redshop_category_xref.category_child_id=#__redshop_category.category_id ";
		$q .= "AND ordering='" . intval($currentpos + 1) . "'";
		$db->setQuery($q);
		$cat = $db->loadObject();
		$succ = $cat->category_id;

		$morder = $this->getmaxminOrder('max');

		if ($currentpos < $morder)
		{
			$q = "UPDATE #__redshop_category ";
			$q .= "SET ordering=ordering+1 ";
			$q .= "WHERE category_id='" . $cid . "' ";
			$db->setQuery($q);
			$db->execute();

			$q = "UPDATE #__redshop_category ";
			$q .= "SET ordering=ordering-1 ";
			$q .= "WHERE category_id='" . $succ . "'";
			$db->setQuery($q);
			$db->execute();
		}
	}

	public function getmaxminOrder($type)
	{
		$db = JFactory::getDbo();
		$q = "SELECT " . $type . "(ordering) as morder FROM #__redshop_category";

		$db->setQuery($q);
		$cat = $db->loadResult();

		return $cat;
	}

	public function getProductCompareTemplate()
	{
		$db = JFactory::getDbo();
		$query = "SELECT ts.template_section as text, ts.template_id as value FROM `#__redshop_template` as ts WHERE `published` = 1 AND `template_section`='compare_product'";
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function copy($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'SELECT * FROM #__redshop_category WHERE category_id IN ( ' . $cids . ' )';
			$db->setQuery($query);
			$copydata = $db->loadObjectList();

			for ($i = 0; $i < count($copydata); $i++)
			{
				$query = 'SELECT category_parent_id FROM #__redshop_category_xref '
					. 'WHERE category_child_id="' . $copydata[$i]->category_id . '" ';
				$db->setQuery($query);
				$category_parent_id = $db->loadResult();

				$post = array();
				$newwidth = THUMB_WIDTH;
				$newheight = THUMB_HEIGHT;

				$post['category_id'] = 0;
				$post['category_name'] = $this->renameToUniqueValue('category_name', $copydata[$i]->category_name);
				$post['category_short_description'] = $copydata[$i]->category_short_description;
				$post['category_description'] = $copydata[$i]->category_description;
				$post['category_template'] = $copydata[$i]->category_template;
				$post['category_more_template'] = $copydata[$i]->category_more_template;
				$post['products_per_page'] = $copydata[$i]->products_per_page;
				$post['category_full_image'] = $this->renameToUniqueValue('category_full_image', $copydata[$i]->category_full_image, 'dash');
				$post['category_thumb_image'] = $this->renameToUniqueValue('category_thumb_image', $copydata[$i]->category_thumb_image, 'dash');
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
