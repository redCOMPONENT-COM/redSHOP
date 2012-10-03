<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class RedshopModelCategory extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'category_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit                = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart           = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $category_main_filter = $app->getUserStateFromRequest($this->_context . 'category_main_filter', 'category_main_filter', 0);
        $category_id          = $app->getUserStateFromRequest($this->_context . 'category_id', 'category_id', 0);

        $this->setState('category_main_filter', $category_main_filter);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('category_id', $category_id);
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $this->_data = $this->_buildQuery();
        }
        return $this->_data;
    }

    public function getPagination()
    {
        if ($this->_pagination == null)
        {
            $this->_buildQuery();
        }
        return $this->_pagination;
    }

    public function store($data)
    {

        $row = $this->getTable('category');

        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // storing image name in the database
        $option = JRequest::getVar('option');

        $filename = "";
        $file     = JRequest::getVar('category_full_image', array(), 'files', 'array'); //Get File name, tmp_name

        if (count($file) > 0)
        {
            $filename = JPath::clean(time() . '_' . $file['name']); //Make the filename unique
            $filename = str_replace(" ", "_", $filename);
        }

        if (isset($data['image_delete']))
        {

            unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb' . DS . $data['old_image']);
            unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $data['old_image']);

            $query = "UPDATE " . $this->_table_prefix . "category set category_thumb_image = '',category_full_image = ''  where category_id =" . $row->category_id;
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        if (count($_FILES) > 0 && $_FILES['category_full_image']['name'] != "")
        {
            $newwidth  = THUMB_WIDTH;
            $newheight = THUMB_HEIGHT;

            $row->category_full_image  = $filename;
            $row->category_thumb_image = $filename;

            $filetype = JFile::getExt($file['name']); //Get extension of the file

            $src  = $file['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $filename; //specific path of the file

            JFile::upload($src, $dest);
        }
        else
        {
            if (isset($data['category_image']) && $data['category_image'] != null)
            {

                $image_split = explode('/', $data['category_image']);

                $filename                  = JPath::clean(time() . '_' . $image_split[count($image_split) - 1]); //Make the filename unique
                $row->category_full_image  = $filename;
                $row->category_thumb_image = $filename;

                // Image Upload

                $newwidth  = THUMB_WIDTH;
                $newheight = THUMB_HEIGHT;

                $src  = JPATH_ROOT . DS . $data['category_image'];
                $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $filename;

                copy($src, $dest);
            }
        }
        // upload back image
        $backfile = JRequest::getVar('category_back_full_image', '', 'files', 'array'); //Get File name, tmp_name

        if (isset($data['image_back_delete']))
        {

            unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb' . DS . $data['old_back_image']);
            unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $data['old_back_image']);

            $query = "UPDATE " . $this->_table_prefix . "category set category_back_full_image = ''  where category_id =" . $row->category_id;
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        if (count($backfile) > 0 && $backfile['name'] != "")
        {
            $filename                      = JPath::clean(time() . '_' . $backfile['name']); //Make the filename unique
            $row->category_back_full_image = $filename;

            $filetype = JFile::getExt($backfile['name']); //Get extension of the file

            $src  = $backfile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $filename; //specific path of the file

            JFile::upload($src, $dest);
        }
        // upload back image end
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
            $query = 'INSERT INTO ' . $this->_table_prefix . 'category_xref(category_parent_id,category_child_id) VALUES ("' . $parentcat . '","' . $newcatid . '");';
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

            $query = 'UPDATE ' . $this->_table_prefix . 'category_xref SET category_parent_id= "' . $parentcat . '"  WHERE category_child_id = "' . $newcatid . '" ';
            $this->_db->setQuery($query);
            $this->_db->query();

            //cheking for the image at the updation time
            if ($_FILES['category_full_image']['name'] != "")
            {
                @unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb' . DS . $_POST['old_image']);
                @unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $_POST['old_image']);
            }
        }
        /// Extra Field Data Saved ////////////////////////
        $field = new extra_field();
        $field->extra_field_save($data, 2, $newcatid); /// field_section 2 :- Category
        /// Extra Field Data Saved ////////////////////////

        //------------ Start Accessory Product --------------------
        if (count($data['product_accessory']) > 0 && is_array($data['product_accessory']))
        {
            $data['product_accessory'] = array_merge(array(), $data['product_accessory']);

            $product_category = new product_category();
            $product_list     = $product_category->getCategoryProductList($newcatid);
            for ($p = 0; $p < count($product_list); $p++)
            {
                $product_id = $product_list[$p]->id;
                for ($a = 0; $a < count($data['product_accessory']); $a++)
                {
                    $acc = $data['product_accessory'][$a];

                    $accessory_id = $product_category->CheckAccessoryExists($product_id, $acc['child_product_id']);
                    if ($product_id != $acc['child_product_id'])
                    {
                        $accdetail = $this->getTable('product_accessory');

                        $accdetail->accessory_id        = $accessory_id;
                        $accdetail->category_id         = $newcatid;
                        $accdetail->product_id          = $product_id;
                        $accdetail->child_product_id    = $acc['child_product_id'];
                        $accdetail->accessory_price     = $acc['accessory_price'];
                        $accdetail->oprand              = $acc['oprand'];
                        $accdetail->ordering            = $acc['ordering'];
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
        //------------ End Accessory Product insert --------------------
        return $row;
    }

    public function _buildQuery()
    {
        $category_id          = $this->getState('category_id');
        $category_main_filter = $this->getState('category_main_filter');
        $limit                = $this->getState('limit');
        $limitstart           = $this->getState('limitstart');

        $orderby = $this->_buildContentOrderBy();
        $and     = "";

        if ($category_main_filter)
        {
            $and .= " AND category_name like '%" . $category_main_filter . "%' ";
        }

        $q = "SELECT c.category_id, cx.category_child_id, cx.category_child_id AS id, cx.category_parent_id, cx.category_parent_id AS parent_id,c.category_name, c.category_name AS title,c.category_description,c.published,ordering " . "FROM " . $this->_table_prefix . "category AS c, " . $this->_table_prefix . "category_xref AS cx " . "WHERE c.category_id=cx.category_child_id " . $and . $orderby;
        $this->_db->setQuery($q);
        $rows = $this->_db->loadObjectList();

        if (!$category_main_filter)
        {
            // establish the hierarchy of the menu
            $children = array();
            // first pass - collect children
            foreach ($rows as $v)
            {
                $pt   = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
            // second pass - get an indent list of the items
            $treelist = JHTML::_('menu.treerecurse', $category_id, '', array(), $children, 9999);

            $total = count($treelist);
        }
        else
        {
            $total    = count($rows);
            $treelist = $rows;
        }

        jimport('joomla.html.pagination');
        $this->_pagination = new JPagination($total, $limitstart, $limit);

        // slice out elements based on limits
        $items = array_slice($treelist, $this->_pagination->limitstart, $this->_pagination->limit);
        return $items;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'c.ordering');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }

    public function getProducts($cid)
    {
        $query = 'SELECT count(category_id) FROM ' . $this->_table_prefix . 'product_category_xref WHERE category_id="' . $cid . '" ';
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /*
      * assign template to multiple categories
      * @prams: $data, post variable	array
      * @return: boolean
      */
    public function assignTemplate($data)
    {

        $cid = $data['cid'];

        $category_template = $data['category_template'];

        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'category' . ' SET `category_template` = "' . intval($category_template) . '" ' . ' WHERE category_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    public function saveorder($cid = array(), $order)
    {
        $row       = $this->getTable('category');
        $groupings = array();

        // update ordering values
        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);

            // track categories
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

    public function delete($cid = array())
    {
        $noError = true;
        for ($i = 0; $i < count($cid); $i++)
        {

            $query = 'SELECT count( * ) as ctotal,c.category_name
						FROM `' . $this->_table_prefix . 'category_xref` as cx LEFT JOIN `' . $this->_table_prefix . 'category` as c ON c.category_id = "' . $cid[$i] . '"
						WHERE `category_parent_id` = "' . $cid[$i] . '" ';
            $this->_db->setQuery($query);
            $childs = $this->_db->loadObject();

            if ($childs->ctotal > 0)
            {
                $noError  = false;
                $errorMSG = sprintf(JText::_('COM_REDSHOP_CATEGORY_PARENT_ERROR_MSG'), $childs->category_name, $cid[$i]);
                $this->setError($errorMSG);
                break;
            }

            $q_image = 'SELECT category_thumb_image,category_full_image FROM ' . $this->_table_prefix . 'category WHERE category_id = "' . $cid[$i] . '" ';
            $this->_db->setQuery($q_image);
            $catimages = $this->_db->loadObject();

            $cat_thumb_image = $catimages->category_thumb_image;
            $cat_full_image  = $catimages->category_full_image;

            $thumb_path      = REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb' . DS . $cat_thumb_image;
            $full_image_path = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $cat_full_image;

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

            $query = 'UPDATE ' . $this->_table_prefix . 'category' . ' SET published = "' . intval($publish) . '" ' . ' WHERE category_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    public function move($direction)
    {
        $row = $this->getTable('category');
        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->move($direction, ' category_id = ' . (int)$row->category_id . ' AND published >= 0 '))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    public function orderup()
    {

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $cid = $cid[0];
        $q   = "SELECT ordering,category_parent_id FROM " . $this->_table_prefix . "category," . $this->_table_prefix . "category_xref ";
        $q .= "WHERE category_id='" . $cid . "' ";
        $q .= "AND category_child_id='" . $cid . "' ";
        $this->_db->setQuery($q);
        $cat                = $this->_db->loadObject();
        $currentpos         = $cat->ordering;
        $category_parent_id = $cat->category_parent_id;

        $q = "SELECT ordering," . $this->_table_prefix . "category.category_id FROM " . $this->_table_prefix . "category, " . $this->_table_prefix . "category_xref ";
        $q .= "WHERE " . $this->_table_prefix . "category_xref.category_parent_id='" . $category_parent_id . "' ";
        $q .= "AND " . $this->_table_prefix . "category_xref.category_child_id=" . $this->_table_prefix . "category.category_id ";
        $q .= "AND ordering='" . intval($currentpos - 1) . "'";
        $this->_db->setQuery($q);
        $cat  = $this->_db->loadObject();
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
        $cat                = $this->_db->loadObject();
        $currentpos         = $cat->ordering;
        $category_parent_id = $cat->category_parent_id;

        $q = "SELECT ordering," . $this->_table_prefix . "category.category_id FROM " . $this->_table_prefix . "category, " . $this->_table_prefix . "category_xref ";
        $q .= "WHERE " . $this->_table_prefix . "category_xref.category_parent_id='" . $category_parent_id . "' ";
        $q .= "AND " . $this->_table_prefix . "category_xref.category_child_id=" . $this->_table_prefix . "category.category_id ";
        $q .= "AND ordering='" . intval($currentpos + 1) . "'";
        $this->_db->setQuery($q);
        $cat  = $this->_db->loadObject();
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

    public function copy($cid = array())
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'category WHERE category_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $copydata = $this->_db->loadObjectList();
            for ($i = 0; $i < count($copydata); $i++)
            {
                $query = 'SELECT category_parent_id FROM ' . $this->_table_prefix . 'category_xref ' . 'WHERE category_child_id="' . $copydata[$i]->category_id . '" ';
                $this->_db->setQuery($query);
                $category_parent_id = $this->_db->loadResult();

                $post      = array();
                $newwidth  = THUMB_WIDTH;
                $newheight = THUMB_HEIGHT;

                $post['category_id']                = 0;
                $post['category_name']              = "copy" . $copydata[$i]->category_name;
                $post['category_short_description'] = $copydata[$i]->category_short_description;
                $post['category_description']       = $copydata[$i]->category_description;
                $post['category_template']          = $copydata[$i]->category_template;
                $post['category_more_template']     = $copydata[$i]->category_more_template;
                $post['products_per_page']          = $copydata[$i]->products_per_page;
                $post['category_full_image']        = "copy" . $copydata[$i]->category_full_image;
                $post['category_thumb_image']       = "copy" . $copydata[$i]->category_thumb_image;
                $post['metakey']                    = $copydata[$i]->metakey;
                $post['metadesc']                   = $copydata[$i]->metadesc;
                $post['metalanguage_setting']       = $copydata[$i]->metalanguage_setting;
                $post['metarobot_info']             = $copydata[$i]->metarobot_info;
                $post['pagetitle']                  = $copydata[$i]->pagetitle;
                $post['pageheading']                = $copydata[$i]->pageheading;
                $post['sef_url']                    = $copydata[$i]->sef_url;
                $post['published']                  = $copydata[$i]->published;
                $post['category_pdate']             = date("Y-m-d h:i:s");
                $post['ordering']                   = count($copydata) + $i + 1;

                $post['category_parent_id'] = $category_parent_id;

                $src  = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $copydata[$i]->category_full_image;
                $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $post['category_full_image'];

                if (is_file($src))
                {
                    JFile::upload($src, $dest);
                }
                $row = $this->store($post);
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
}
