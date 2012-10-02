<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'thumbnail.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'category.php');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class category_detailModelcategory_detail extends RedshopCoreModelDetail
{
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
            $query = 'SELECT c.*,p.category_parent_id FROM ' . $this->_table_prefix . 'category as c left join ' . $this->_table_prefix . 'category_xref as p ON p.category_child_id=c.category_id  WHERE category_id = "' . $this->_id . '" ';
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    public function _initData()
    {
        if (empty($this->_data))
        {
            $detail                             = new stdClass();
            $detail->category_id                = 0;
            $detail->category_name              = null;
            $detail->category_short_description = null;
            $detail->category_more_template     = null;
            $detail->category_description       = null;
            $detail->category_template          = 0;
            $detail->products_per_page          = 5;
            $detail->category_full_image        = null;
            $detail->category_thumb_image       = null;
            $detail->category_back_full_image   = null;
            $detail->metakey                    = null;
            $detail->metadesc                   = null;
            $detail->metalanguage_setting       = null;
            $detail->metarobot_info             = null;
            $detail->pagetitle                  = null;
            $detail->pageheading                = null;
            $detail->sef_url                    = null;
            $detail->published                  = 1;
            $detail->compare_template_id        = 0;
            $this->_data                        = $detail;

            return (boolean)$this->_data;
        }

        return true;
    }

    public function store($data)
    {

        $row = $this->getTable();

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
                        $accdetail = $this->getTable('accessory_detail');

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

    public function getcategories()
    {
        $query = 'SELECT category_id as value,category_name as text FROM ' . $this->_table_prefix . 'category  WHERE published=1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
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
        $query = "SELECT ts.template_section as text, ts.template_id as value FROM `" . $this->_table_prefix . "template` as ts WHERE `published` = 1 AND `template_section`='compare_product'";
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}
