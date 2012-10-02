<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'thumbnail.php');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class attribute_set_detailModelattribute_set_detail extends RedshopCoreModelDetail
{
    public $attribute_data = null;

    public $_copydata = null;

    public $_copycategorydata = null;

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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'attribute_set WHERE attribute_set_id = ' . $this->_id;
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
            $detail                     = new stdClass();
            $detail->attribute_set_id   = 0;
            $detail->attribute_set_name = null;
            $detail->published          = 1;
            $this->_data                = $detail;
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

        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $row;
    }

    public function getattributes()
    {
        $producthelper = new producthelper();
        $attr          = array();
        if ($this->_id != 0)
        {
            $attr = $producthelper->getProductAttribute(0, $this->_id);
        }
        $attribute_data = '';

        for ($i = 0; $i < count($attr); $i++)
        {
            $prop = $producthelper->getAttibuteProperty(0, $attr[$i]->attribute_id);

            $attribute_id             = $attr[$i]->attribute_id;
            $attribute_name           = $attr[$i]->attribute_name;
            $attribute_required       = $attr[$i]->attribute_required;
            $allow_multiple_selection = $attr[$i]->allow_multiple_selection;
            $hide_attribute_price     = $attr[$i]->hide_attribute_price;
            $attribute_published      = $attr[$i]->attribute_published;
            $display_type             = $attr[$i]->display_type;
            $ordering                 = $attr[$i]->ordering;

            for ($j = 0; $j < count($prop); $j++)
            {
                $subprop            = $producthelper->getAttibuteSubProperty(0, $prop[$j]->property_id);
                $prop[$j]->subvalue = $subprop;
            }
            $attribute_data[] = array('attribute_id'=> $attribute_id, 'attribute_name'=> $attribute_name, 'attribute_required'=> $attribute_required, 'ordering'=> $ordering, 'property'=> $prop, 'allow_multiple_selection'=> $allow_multiple_selection, 'hide_attribute_price'=> $hide_attribute_price, 'attribute_published'=> $attribute_published, 'display_type'=> $display_type);
        }

        return $attribute_data;
    }

    public function getattributelist($data)
    {
        $attribute_data = '';
        $producthelper  = new producthelper();
        $attr           = $producthelper->getProductAttribute(0, $data);
        for ($i = 0; $i < count($attr); $i++)
        {
            $prop             = $producthelper->getAttibuteProperty(0, $attr[$i]->attribute_id);
            $attribute_id     = $attr[$i]->attribute_id;
            $attribute_name   = $attr[$i]->attribute_name;
            $attribute_data[] = array('attribute_id'=> $attribute_id, 'attribute_name'=> $attribute_name, 'property'=> $prop);
        }
        return $attribute_data;
    }

    public function getpropertylist($data)
    {
        $producthelper = new producthelper();
        if (count($data))
        {
            $cids = implode(',', $data);
            $prop = $producthelper->getAttibuteProperty($cids);
        }
        return $prop;
    }

    //Delete Attribute Diffrence

    public function deleteattr($cid = array())
    {

        $option = JRequest::getVar('option', '', 'request', 'string');

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

                // changed 5 feb
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

    //All Property Deleteing

    public function deleteattr_current($cid = array())
    {

        $option = JRequest::getVar('option', '', 'request', 'string');

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
        $producthelper = new producthelper();

        if (count($cid))
        {
            $prop = $producthelper->getAttibuteProperty(0, $cid);
        }
        return $prop;
    }

    // Store attribute list

    public function store_attr($data)
    {
        $row = $this->getTable('product_attribute');

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
        $row = $this->getTable('attribute_property');

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
        $row = $this->getTable('subattribute_property');

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
                $main_src  = $main_img['tmp_name'];

                $main_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property' . DS . $main_name; //specific path of the file

                JFile::upload($main_src, $main_dest);

                $query = "UPDATE " . $this->_table_prefix . "product_attribute_property SET property_main_image = '" . $main_name . "' WHERE property_id ='" . $post['section_id'] . "' ";
                $this->_db->setQuery($query);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
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

                    $sub_name = time() . "_" . $sub_img['name'][$i];

                    $sub_src = $sub_img['tmp_name'][$i];

                    $sub_type = $sub_img['type'][$i];

                    $sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property' . DS . $sub_name; //specific path of the file

                    JFile::upload($sub_src, $sub__dest);

                    $query = "INSERT INTO " . $this->_table_prefix . "media
								(`media_id`,`media_name`,`media_section`,`section_id`,`media_type`,`media_mimetype`,`published`)
								VALUES ('','" . $sub_name . "','property','" . $post['section_id'] . "','images','" . $sub_type . "','1') ";
                    $this->_db->setQuery($query);
                    if (!$this->_db->query())
                    {
                        $this->setError($this->_db->getErrorMsg());
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

        $query = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE media_id = ' . $mediaid;

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

                    $sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor' . DS . $sub_name; //specific path of the file

                    JFile::upload($sub_src, $sub__dest);
                    if ($post['property_sub_img_tmp'][$i] != "")
                    {

                        $sub       = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor' . DS . $post['property_sub_img_tmp'][$i];
                        $sub_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/thumb' . DS . $post['property_sub_img_tmp'][$i];
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
                        $query = "INSERT INTO " . $this->_table_prefix . "product_subattribute_color
									(`subattribute_color_id`,`subattribute_color_name`,`subattribute_color_image`,`subattribute_id`)
									VALUES ('','" . $post['subattribute_name'][$i] . "','" . $sub_name . "','" . $post['section_id'] . "') ";
                    }
                    else
                    {
                        $query = "UPDATE " . $this->_table_prefix . "product_subattribute_color
									SET `subattribute_color_name` = '" . $post['subattribute_name'][$i] . "' ,`subattribute_color_image` = '" . $sub_name . "',`subattribute_id` = '" . $post['section_id'] . "'
									WHERE subattribute_color_id = '" . $post['subattribute_color_id'][$i] . "'";
                    }
                    $this->_db->setQuery($query);
                    if (!$this->_db->query())
                    {
                        $this->setError($this->_db->getErrorMsg());
                        return false;
                    }
                }
            }
            else
            {
                if ($post['property_sub_img_tmp'][$i] != "" && $sub_img['name'][$i] == "")
                {
                    $query = "UPDATE " . $this->_table_prefix . "product_subattribute_color
								SET `subattribute_color_name` = '" . $post['subattribute_name'][$i] . "' ,`subattribute_color_image` = '" . $post['property_sub_img_tmp'][$i] . "',`subattribute_id` = '" . $post['section_id'] . "'
								WHERE subattribute_color_id = '" . $post['subattribute_color_id'][$i] . "'";

                    $this->_db->setQuery($query);

                    if (!$this->_db->query())
                    {
                        $this->setError($this->_db->getErrorMsg());
                        return false;
                    }
                }
            }
        }
    }

    public function subattr_diff($subattr_id, $section_id)
    {
        $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color   WHERE subattribute_id = ' . $section_id . ' and subattribute_color_id NOT IN (\'' . $subattr_id . '\') ORDER BY subattribute_color_id ASC';
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

            $sub_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor' . DS . $diff->subattribute_color_image; //specific path of the file

            if (file_exists($sub_dest))
            {
                unlink($sub_dest);
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'product_subattribute_color  WHERE subattribute_color_id = "' . $diff->subattribute_color_id . '"';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    public function  attribute_empty()
    {
        $producthelper = new producthelper();

        if ($this->_id)
        {
            $attributes = $producthelper->getProductAttribute(0, $this->_id);
            for ($i = 0; $i < count($attributes); $i++)
            {
                $query = "DELETE FROM `" . $this->_table_prefix . "product_attribute` WHERE `attribute_id` = " . $attributes[$i]->attribute_id;
                $this->_db->setQuery($query);
                if ($this->_db->query())
                {
                    $property = $producthelper->getAttibuteProperty(0, $attributes[$i]->attribute_id);
                    for ($j = 0; $j < count($property); $j++)
                    {

                        $query = "DELETE FROM `" . $this->_table_prefix . "product_attribute_property` WHERE `property_id` = " . $property[$j]->property_id;
                        $this->_db->setQuery($query);
                        if ($this->_db->query())
                        {

                            $query = "DELETE FROM `" . $this->_table_prefix . "product_subattribute_color` WHERE `subattribute_id` = " . $property[$j]->property_id;
                            $this->_db->setQuery($query);
                            $this->_db->query();
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
        $producthelper = new producthelper();

        $image     = $producthelper->getAttibuteProperty($pid);
        $image     = $image[0];
        $imagename = $image->property_image;

        $imagethumbsrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/thumb/" . $imagename;

        if (is_file($imagethumbsrcphy))
        {
            @unlink($imagethumbsrcphy);
        }

        $imagesrc    = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $imagename;
        $imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $imagename;

        if (is_file($imagesrcphy))
        {
            @unlink($imagesrcphy);
        }

        $query = "UPDATE `" . $this->_table_prefix . "product_attribute_property` SET `property_image` = '' WHERE `property_id` = " . $pid;
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
        $producthelper    = new producthelper();
        $image            = $producthelper->getAttibuteSubProperty($pid);
        $image            = $image[0];
        $imagename        = $image->subattribute_color_image;
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
        $query = "UPDATE `" . $this->_table_prefix . "product_subattribute_color` SET `subattribute_color_image` = '' WHERE `subattribute_color_id` = " . $pid;
        $this->_db->setQuery($query);
        if (!$this->_db->Query())
        {
            return false;
        }
        return true;
    }

    // store stockroom product xref
    public function SaveAttributeStockroom($post)
    {
        $query = "DELETE FROM " . $this->_table_prefix . "product_attribute_stockroom_xref" . "\n  WHERE section_id = " . $post['section_id'] . " AND section = '" . $post['section'] . "'";

        $this->_db->setQuery($query);

        $this->_db->query();

        for ($i = 0; $i < count($post['quantity']); $i++)
        {
            if ($post['quantity'][$i] || (!USE_BLANK_AS_INFINITE))
            {
                $q = "INSERT IGNORE INTO " . $this->_table_prefix . "product_attribute_stockroom_xref VALUES (" . $post['section_id'] . ",'" . $post['section'] . "'," . $post['stockroom_id'][$i] . ",'" . $post['quantity'][$i] . "') ";

                $this->_db->setQuery($q);

                if (!$this->_db->query())
                {
                    return false;
                }
            }
        }

        return true;
    }

    public function save_product_attribute_price($product_attribute_price, $section)
    {

        // Create array for attribute price for property and subproperty section
        $attribute['section_id']           = $product_attribute_price->section_id;
        $attribute['section']              = $section;
        $attribute['product_price']        = $product_attribute_price->product_price;
        $attribute['product_currency']     = $product_attribute_price->product_currency;
        $attribute['cdate']                = $product_attribute_price->cdate;
        $attribute['shopper_group_id']     = $product_attribute_price->shopper_group_id;
        $attribute['price_quantity_start'] = $product_attribute_price->price_quantity_start;
        $attribute['price_quantity_end']   = $product_attribute_price->price_quantity_end;

        $row = $this->getTable('attributeprices_detail');

        // Bind and save data into 'attributeprices_detail'
        if (!$row->bind($attribute))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
    }

    public function save_product_attribute_stockquantity($product_attribute_stocks, $section)
    {
        $sql = "INSERT INTO " . $this->_table_prefix . "product_attribute_stockroom_xref (`section_id`,`section`,`stockroom_id`,`quantity`)
		VALUES ('" . $product_attribute_stocks->section_id . "','" . $section . "','" . $product_attribute_stocks->stockroom_id . "','" . $product_attribute_stocks->quantity . "' )";

        $this->_db->setQuery($sql);
        $this->_db->Query();
    }

    public function copy_image($imageArray, $section, $section_id)
    {
        $src = $imageArray['tmp_name'];

        $imgname = basename($imageArray['name']);

        $property_image = $section_id . '_' . $imgname;

        $dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;

        copy($src, $dest);

        return $property_image;
    }

    public function copy_image_from_path($imagePath, $section, $section_id)
    {

        $src = JPATH_ROOT . DS . $imagePath;

        $imgname = basename($imagePath);

        $property_image = $section_id . '_' . $imgname;

        $dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;

        copy($src, $dest);

        return $property_image;
    }

    public function copyadditionalImage($data)
    {

        $rowmedia = $this->getTable('media_detail');

        $data['media_id '] = 0;
        if (!$rowmedia->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $section            = $data['media_section'];
        $path               = $section . '/' . $data['media_name'];
        $property_image     = $this->copy_image_additionalimage_from_path($path, $data['media_section'], $data['section_id']);
        $data['media_name'] = $property_image;
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

        $property_image = time() . '_' . $imgname;

        $dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $property_image;

        copy($src, $dest);

        return $property_image;
    }

    public function GetimageInfo($id, $type)
    {
        $image_media = 'SELECT * FROM ' . $this->_table_prefix . 'media WHERE section_id = "' . $id . '" AND media_section = "' . $type . '" ';
        $this->_db->setQuery($image_media);
        return $this->_db->loadObjectlist();
    }
}
