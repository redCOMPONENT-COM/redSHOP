<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class RedshopModelMedia_detail extends RedshopCoreModelDetail
{
    public $_mediadata = null;

    public $_mediatypedata = null;

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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'media ' . 'WHERE media_id = "' . $this->_id . '" ' . 'order by section_id';
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
            $detail                       = new stdClass();
            $detail->media_id             = 0;
            $detail->media_title          = null;
            $detail->media_type           = null;
            $detail->media_name           = null;
            $detail->media_alternate_text = null;
            $detail->media_section        = null;
            $detail->section_id           = null;
            $detail->published            = 1;
            $this->_data                  = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    public function store($data)
    {
        $row = $this->getTable('media');
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



    public function getSection($id, $type)
    {
        if ($type == 'product')
        {
            $query = 'SELECT product_name as name, product_id as id FROM ' . $this->_table_prefix . 'product  WHERE product_id = "' . $id . '" ';
        }
        else
        {
            $query = 'SELECT category_name as name,category_id as id FROM ' . $this->_table_prefix . 'category  WHERE category_id = "' . $id . '" ';
        }
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    public function defaultmedia($media_id = 0, $section_id = 0, $media_section = "")
    {
        if ($media_id && $media_section)
        {
            $query = "SELECT * FROM " . $this->_table_prefix . "media " . "WHERE `section_id`='" . $section_id . "' " . "AND `media_section` = '" . $media_section . "' " . "AND `media_id` = '" . $media_id . "' " . "AND `media_type` = 'images' ";
            $this->_db->setQuery($query);
            $rs = $this->_db->loadObject();
            if (count($rs) > 0)
            {
                switch ($media_section)
                {
                    case "product":
                        $query = "UPDATE `" . $this->_table_prefix . "product` " . "SET `product_thumb_image` = '', `product_full_image` = '" . $rs->media_name . "' " . "WHERE `product_id`='" . $section_id . "' ";
                        $this->_db->setQuery($query);
                        if (!$this->_db->query())
                        {
                            $this->setError($this->_db->getErrorMsg());
                            return false;
                        }
                        break;
                    case "property":
                        $query = "UPDATE `" . $this->_table_prefix . "product_attribute_property` " . "SET `property_main_image` = '" . $rs->media_name . "' " . "WHERE `property_id`='" . $section_id . "' ";
                        $this->_db->setQuery($query);
                        if (!$this->_db->query())
                        {
                            $this->setError($this->_db->getErrorMsg());
                            return false;
                        }
                        break;
                    case "subproperty":
                        $query = "UPDATE `" . $this->_table_prefix . "product_subattribute_color` " . "SET `subattribute_color_main_image` = '" . $rs->media_name . "' " . "WHERE `subattribute_color_id`='" . $section_id . "' ";
                        $this->_db->setQuery($query);
                        if (!$this->_db->query())
                        {
                            $this->setError($this->_db->getErrorMsg());
                            return false;
                        }
                        break;
                }
            }
        }
        return true;
    }


}
