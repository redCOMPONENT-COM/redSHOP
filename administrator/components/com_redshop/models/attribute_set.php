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

class RedshopModelAttribute_set extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'attribute_set_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit      = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total))
        {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();
        $query   = 'SELECT distinct(a.attribute_set_id),a.* FROM ' . $this->_table_prefix . 'attribute_set AS a ' . 'WHERE 1=1 ' . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'attribute_set_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
        $orderby          = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }


    public function delete($cid = array())
    {
        $producthelper = new producthelper();
        $option        = JRequest::getVar('option', '', 'request', 'string');
        if (count($cid))
        {
            $cids           = implode(',', $cid);
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

                $attr_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute WHERE attribute_id =' . $imagename->attribute_id;
                $this->_db->setQuery($attr_delete);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    //return false;
                }
                $prop_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id =' . $imagename->attribute_id;
                $this->_db->setQuery($prop_delete);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    //return false;
                }
            }
            $query = 'DELETE FROM ' . $this->_table_prefix . 'attribute_set WHERE attribute_set_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }
        }

        return true;
    }

    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'attribute_set' . ' SET published = ' . intval($publish) . ' WHERE attribute_set_id IN ( ' . $cids . ' )';
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
            $cids  = implode(',', $cid);
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'attribute_set WHERE attribute_set_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $copydata = $this->_db->loadObjectList();
            for ($i = 0; $i < count($copydata); $i++)
            {
                $post = array();

                //  insert into attribute set table
                $post['attribute_set_id']   = 0;
                $post['attribute_set_name'] = "copy" . $copydata[$i]->attribute_set_name;
                $post['published']          = $copydata[$i]->published;
                $row                        = $this->store($post);

                // Fetch attributes from the attribute set ID
                $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute  WHERE `attribute_set_id` = ' . $copydata[$i]->attribute_set_id . ' ';
                $this->_db->setQuery($query);
                $product_attributes = $this->_db->loadObjectList();

                $attribute_set_id = $row->attribute_set_id;

                if (count($product_attributes) > 0)
                {
                    foreach ($product_attributes as $product_attribute)
                    {

                        // Create $attribute array of attributes
                        $attribute['attribute_name']           = $product_attribute->attribute_name;
                        $attribute['attribute_required']       = $product_attribute->attribute_required;
                        $attribute['allow_multiple_selection'] = $product_attribute->allow_multiple_selection;
                        $attribute['hide_attribute_price']     = $product_attribute->hide_attribute_price;
                        $attribute['product_id']               = $product_attribute->product_id;
                        $attribute['ordering']                 = $product_attribute->ordering;
                        $attribute['attribute_set_id']         = $attribute_set_id;

                        $row = $this->getTable('product_attribute');

                        // Bind and save data into 'product_attribute'
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

                        // Fetch attributes from the attribute set ID

                        $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_property  WHERE `attribute_id` = ' . $product_attribute->attribute_id . ' ';
                        $this->_db->setQuery($query);
                        $product_attributes_properties = $this->_db->loadObjectList();

                        $query = 'SELECT * FROM `' . $this->_table_prefix . 'product_attribute_property` WHERE `attribute_id` = "' . $product_attribute->attribute_id . '" ';
                        $this->_db->setQuery($query);
                        $att_property = $this->_db->loadObjectList();

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

                                // Create $attribute_properties array of attributes properties

                                $attribute_properties['attribute_id']        = $row->attribute_id;
                                $loopattribute_id                            = $row->attribute_id;
                                $attribute_properties['property_name']       = $product_attributes_property->property_name;
                                $attribute_properties['property_price']      = $product_attributes_property->property_price;
                                $attribute_properties['oprand']              = $product_attributes_property->oprand;
                                $attribute_properties['property_image']      = $product_attributes_property->property_image;
                                $attribute_properties['property_main_image'] = $product_attributes_property->property_main_image;
                                $attribute_properties['ordering']            = $product_attributes_property->ordering;
                                $attribute_properties['setdefault_selected'] = $product_attributes_property->setdefault_selected;
                                $attribute_properties['property_number']     = $product_attributes_property->property_number;

                                $row = $this->getTable('product_attribute_property');

                                // Bind and save data into 'product_attribute_property'
                                if (!$row->bind($attribute_properties))
                                {
                                    $this->setError($this->_db->getErrorMsg());
                                    return false;
                                }

                                if (!$row->store())
                                {
                                    $this->setError($this->_db->getErrorMsg());
                                    return false;
                                }

                                $listImages = $this->GetimageInfo($product_attributes_property->property_id, 'property');

                                for ($li = 0; $li < count($listImages); $li++)
                                {
                                    $mImages                         = array();
                                    $mImages['media_name']           = $listImages[$li]->media_name;
                                    $mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
                                    $mImages['media_section']        = 'property';
                                    $mImages['section_id']           = $row->property_id;
                                    $mImages['media_type']           = 'images';
                                    $mImages['media_mimetype']       = $listImages[$li]->media_mimetype;
                                    $mImages['published']            = $listImages[$li]->published;
                                    $this->copyadditionalImage($mImages);
                                }

                                // Attribute piggy bank price for property
                                $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_price   WHERE `section_id` = ' . $product_attributes_property->property_id . ' AND `section`="property" ';
                                $this->_db->setQuery($query);
                                $product_attribute_prices = $this->_db->loadObjectList();

                                if (count($product_attribute_prices))
                                {
                                    foreach ($product_attribute_prices as $product_attribute_price)
                                    {
                                        $product_attribute_price->section_id = $row->property_id;
                                        $this->save_product_attribute_price($product_attribute_price, 'property');
                                    }
                                }

                                // Attribute stock quantity for property
                                $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_stockroom_xref   WHERE `section_id` = ' . $product_attributes_property->property_id . ' AND `section`="property" ';
                                $this->_db->setQuery($query);
                                $product_attribute_stockquantities = $this->_db->loadObjectList();

                                if (count($product_attribute_stockquantities))
                                {
                                    foreach ($product_attribute_stockquantities as $product_attribute_stockquantity)
                                    {
                                        $product_attribute_stockquantity->section_id = $row->property_id;
                                        $this->save_product_attribute_stockquantity($product_attribute_stockquantity, 'property');
                                    }
                                }

                                // Fetch attributes from the attribute set ID
                                $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_subattribute_color  WHERE `subattribute_id` = ' . $product_attributes_property->property_id . ' ';
                                $this->_db->setQuery($query);
                                $product_sub_attributes_properties = $this->_db->loadObjectList();

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

                                        // Create $sub_attribute_properties array of subattributes properties
                                        $sub_attribute_properties['subattribute_id']               = $row->property_id;
                                        $loopproperty_id                                           = $row->property_id;
                                        $sub_attribute_properties['subattribute_color_name']       = $product_sub_attributes_property->subattribute_color_name;
                                        $sub_attribute_properties['subattribute_color_price']      = $product_sub_attributes_property->subattribute_color_price;
                                        $sub_attribute_properties['oprand']                        = $product_sub_attributes_property->oprand;
                                        $sub_attribute_properties['subattribute_color_image']      = $product_sub_attributes_property->subattribute_color_image;
                                        $sub_attribute_properties['ordering']                      = $product_sub_attributes_property->ordering;
                                        $sub_attribute_properties['setdefault_selected']           = $product_sub_attributes_property->setdefault_selected;
                                        $sub_attribute_properties['subattribute_color_number']     = $product_sub_attributes_property->subattribute_color_number;
                                        $sub_attribute_properties['subattribute_color_title']      = $product_sub_attributes_property->subattribute_color_title;
                                        $sub_attribute_properties['subattribute_color_main_image'] = $product_sub_attributes_property->subattribute_color_main_image;
                                        $row                                                       = $this->getTable('product_subattribute_color');

                                        // Bind and save data into 'subattribute_property'
                                        if (!$row->bind($sub_attribute_properties))
                                        {
                                            $this->setError($this->_db->getErrorMsg());
                                            return false;
                                        }

                                        if (!$row->store())
                                        {
                                            $this->setError($this->_db->getErrorMsg());
                                            return false;
                                        }

                                        $listsubpropImages = $this->GetimageInfo($product_sub_attributes_property->subattribute_color_id, 'subproperty');

                                        for ($lsi = 0; $lsi < count($listsubpropImages); $lsi++)
                                        {
                                            $smImages                         = array();
                                            $smImages['media_name']           = $listsubpropImages[$lsi]->media_name;
                                            $smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
                                            $smImages['media_section']        = 'subproperty';
                                            $smImages['section_id']           = $row->subattribute_color_id;
                                            $smImages['media_type']           = 'images';
                                            $smImages['media_mimetype']       = $listsubpropImages[$lsi]->media_mimetype;
                                            $smImages['published']            = $listsubpropImages[$lsi]->published;

                                            $this->copyadditionalImage($smImages);
                                        }
                                        // Attribute piggy bank price for Subproperty
                                        $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_price   WHERE `section_id` = ' . $product_sub_attributes_property->subattribute_color_id . ' AND `section`="subproperty"  ';
                                        $this->_db->setQuery($query);
                                        $product_subattribute_prices = $this->_db->loadObjectList();

                                        if (count($product_subattribute_prices))
                                        {
                                            foreach ($product_subattribute_prices as $product_subattribute_price)
                                            {
                                                $product_subattribute_price->section_id = $row->subattribute_color_id;
                                                $this->save_product_attribute_price($product_subattribute_price, 'subproperty');
                                            }
                                        }

                                        // Attribute stock quantity for property
                                        $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_attribute_stockroom_xref   WHERE `section_id` = ' . $product_sub_attributes_property->subattribute_color_id . ' AND `section`="subproperty" ';
                                        $this->_db->setQuery($query);
                                        $product_attribute_stockquantities = $this->_db->loadObjectList();

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
}

