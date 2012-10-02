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
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'stockroom.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'shipping.php');
require_once(JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'product.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class productModelproduct extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_categorytreelist = null;

    public $_context = 'product_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit        = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart   = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $search_field = $app->getUserStateFromRequest($this->_context . 'search_field', 'search_field', '');
        $keyword      = $app->getUserStateFromRequest($this->_context . 'keyword', 'keyword', '');
        $category_id  = $app->getUserStateFromRequest($this->_context . 'category_id', 'category_id', 0);
        $product_sort = $app->getUserStateFromRequest($this->_context . 'product_sort', 'product_sort', 0);

        $this->setState('product_sort', $product_sort);
        $this->setState('search_field', $search_field);
        $this->setState('keyword', $keyword);
        $this->setState('category_id', $category_id);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'product' . ' SET published = "' . intval($publish) . '" ' . ' WHERE product_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
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

    public function _buildQuery()
    {
        static $items;

        if (isset($items))
        {
            return $items;
        }
        $orderby      = $this->_buildContentOrderBy();
        $limitstart   = $this->getState('limitstart');
        $limit        = $this->getState('limit');
        $search_field = $this->getState('search_field');
        $keyword      = $this->getState('keyword');
        $category_id  = $this->getState('category_id');
        $product_sort = $this->getState('product_sort');
        $keyword      = addslashes($keyword);
        $arr_keyword  = array();

        $where = '';
        $and   = '';
        if (!empty($product_sort))
        {
            if ($product_sort == 'p.published')
            {
                $and = 'AND p.published=1 ';
            }
            else if ($product_sort == 'p.unpublished')
            {
                $and = 'AND p.published=0 ';
            }
            else if ($product_sort == 'p.product_on_sale')
            {
                $and = 'AND p.product_on_sale=1 ';
            }
            else if ($product_sort == 'p.product_special')
            {
                $and = 'AND p.product_special=1 ';
            }
            else if ($product_sort == 'p.expired')
            {
                $and = 'AND p.expired=1 ';
            }
            else if ($product_sort == 'p.not_for_sale')
            {
                $and = 'AND p.not_for_sale=1 ';
            }
            else if ($product_sort == 'p.product_not_on_sale')
            {
                $and = 'AND p.product_on_sale=0 ';
            }
            else if ($product_sort == 'p.sold_out')
            {
                $query_prd           = "SELECT DISTINCT(p.product_id),p.attribute_set_id FROM " . $this->_table_prefix . "product AS p ";
                $tot_products        = $this->_getList($query_prd);
                $product_id_array    = '';
                $producthelper       = new producthelper();
                $products_stock      = $producthelper->removeOutofstockProduct($tot_products);
                $final_product_stock = $this->getFinalProductStock($products_stock);
                if (count($final_product_stock) > 0)
                {
                    $product_id_array = implode(',', $final_product_stock);
                }
                else
                {
                    $product_id_array = "0";
                }
                $and = "AND p.product_id IN (" . $product_id_array . ")";
            }
        }
        if (trim($keyword) != '')
        {
            $arr_keyword = explode(' ', $keyword);
        }
        if ($search_field != 'pa.property_number')
        {
            for ($k = 0; $k < count($arr_keyword); $k++)
            {
                if ($k == 0)
                {
                    $where .= " AND ( ";
                }
                if ($search_field == 'p.name_number')
                {
                    $where .= " p.product_name LIKE '%$arr_keyword[$k]%' OR p.product_number LIKE '%$arr_keyword[$k]%' ";
                }
                else
                {
                    $where .= $search_field . " LIKE '%$arr_keyword[$k]%'  "; //$arr_keyword[$k];
                }

                if ($k != count($arr_keyword) - 1)
                {
                    if ($search_field == 'p.name_number')
                    {
                        $where .= ' OR ';
                    }
                    else
                    {
                        $where .= ' AND ';
                    }
                }
                if ($k == count($arr_keyword) - 1)
                {
                    $where .= " )  ";
                }
            }
        }
        if ($category_id)
        {
            $where .= " AND c.category_id = '" . $category_id . "'  ";
        }
        if ($where == '' && $search_field != 'pa.property_number')
        {

            $query = "SELECT p.product_id,p.product_id AS id,p.product_name,p.product_name AS treename,p.product_name AS title,p.product_price,p.product_parent_id,p.product_parent_id AS parent_id  " . ",p.published,p.visited,p.manufacturer_id,p.product_number ,p.checked_out,p.checked_out_time,p.discount_price " . ",p.product_template FROM " . $this->_table_prefix . "product AS p " //."LEFT JOIN ".$this->_table_prefix."product_category_xref AS x ON x.product_id = p.product_id "
                . "WHERE 1=1 " . $and . $orderby;
        }
        else
        {

            $query = "SELECT p.product_id AS id,p.product_id,p.product_name,p.product_name AS treename,p.product_name AS name,p.product_parent_id,p.product_parent_id AS parent,p.product_price " . ",p.published,p.visited,p.manufacturer_id,p.product_number,p.product_template,p.checked_out,p.checked_out_time,p.discount_price " . ", x.ordering , x.category_id " . "FROM " . $this->_table_prefix . "product AS p " . "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS x ON x.product_id = p.product_id " . "LEFT JOIN " . $this->_table_prefix . "category AS c ON x.category_id = c.category_id ";
            if ($search_field == 'pa.property_number' && $keyword != '')
            {
                $query .= "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.product_id = p.product_id " . "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS pa ON pa.attribute_id = a.attribute_id " . "LEFT JOIN " . $this->_table_prefix . "product_subattribute_color AS ps ON ps.subattribute_id = pa.property_id ";
            }
            $query .= "WHERE 1=1 ";
            if ($search_field == 'pa.property_number' && $keyword != '')
            {
                $query .= "AND (pa.property_number LIKE '%$keyword%'  OR ps.subattribute_color_number LIKE '%$keyword%') ";
            }
            $query .= $where . $and . " GROUP BY p.product_id ";
            $query .= $orderby;
        }

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectlist();
        if ($where == '' && $search_field != 'pa.property_number')
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
            $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, max(0, 9));
        }
        else
        {
            $list = $rows;
        }

        $total = count($list);

        jimport('joomla.html.pagination');
        $this->_pagination = new JPagination($total, $limitstart, $limit);

        // slice out elements based on limits
        $list  = array_slice($list, $this->_pagination->limitstart, $this->_pagination->limit);
        $items = $list;
        return $items;
    }

    public function getFinalProductStock($product_stock)
    {
        if (count($product_stock) > 0)
        {
            $product = array();
            for ($i = 0; $i < count($product_stock); $i++)
            {
                $product[] = $product_stock[$i]->product_id;
            }
            $product_id = implode(',', $product);
            $query_prd  = "SELECT DISTINCT(p.product_id) FROM " . $this->_table_prefix . "product AS p WHERE p.product_id NOT IN(" . $product_id . ")";
            $this->_db->setQuery($query_prd);
            $final_products = $this->_db->loadResultArray();
            return $final_products;
        }
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $category_id      = $this->getState('category_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        if ($category_id)
        {
            $filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'x.ordering');
        }
        else
        {
            $filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'p.product_id');
        }
        $orderby = " ORDER BY " . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }

    public function MediaDetail($pid)
    {
        $query = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE section_id ="' . $pid . '" AND media_section = "product"';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }

    public function listedincats($pid)
    {
        $query = 'SELECT c.category_name FROM ' . $this->_table_prefix . 'product_category_xref as ref, ' . $this->_table_prefix . 'category as c WHERE product_id ="' . $pid . '" AND ref.category_id=c.category_id ORDER BY c.category_name';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }

    public function product_template($template_id, $product_id, $section)
    {
        $redTemplate = new Redtemplate();
        if ($section == 1 || $section == 12)
        {
            $template_desc = $redTemplate->getTemplate("product", $template_id);
        }
        else
        {
            $template_desc = $redTemplate->getTemplate("category", $template_id);
        }
        if (count($template_desc) == 0)
        {
            return;
        }

        $template = $template_desc[0]->template_desc;
        $str      = array();
        $sec      = explode(',', $section);
        for ($t = 0; $t < count($sec); $t++)
        {
            $inArr[] = "'" . $sec[$t] . "'";
        }
        $in = implode(',', $inArr);
        $q  = "SELECT field_name,field_type,field_section from " . $this->_table_prefix . "fields where field_section in (" . $in . ") ";
        $this->_db->setQuery($q);
        $fields = $this->_db->loadObjectlist();
        for ($i = 0; $i < count($fields); $i++)
        {
            if (strstr($template, "{" . $fields[$i]->field_name . "}"))
            {
                if ($fields[$i]->field_section == 12)
                {
                    if ($fields[$i]->field_type == 15)
                    {
                        $str[] = $fields[$i]->field_name;
                    }
                }
                else
                {
                    $str[] = $fields[$i]->field_name;
                }
            }
        }
        $list_field = array();
        if (count($str) > 0)
        {
            $dbname = "'" . implode("','", $str) . "'";
            $field  = new extra_field();
            for ($t = 0; $t < count($sec); $t++)
            {

                $list_field[] = $field->list_all_field($sec[$t], $product_id, $dbname);
            }
        }
        if (count($list_field) > 0)
        {
            return $list_field;
        }
        else
        {
            return "";
        }
    }

    public function getmanufacturername($mid)
    {
        $query = 'SELECT manufacturer_name FROM ' . $this->_table_prefix . 'manufacturer  WHERE manufacturer_id="' . $mid . '" ';
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    public function assignTemplate($data)
    {

        $cid = $data['cid'];

        $product_template = $data['product_template'];

        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'product' . ' SET `product_template` = "' . intval($product_template) . '" ' . ' WHERE product_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    public function gbasefeed($data)
    {

        $producthelper    = new producthelper();
        $stockroomhelper  = new rsstockroomhelper();
        $shippinghelper   = new shipping();
        $unpublished_data = $data['unpublished_data'];
        $cid              = $data['cid'];

        $url             = JURI::root();
        $currency        = new convertPrice();
        $product_img_url = $url . "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "product" . DS;
        $file_path       = JPATH_COMPONENT_SITE . DS . "assets" . DS . "document" . DS . "gbase";

        $file_name = $file_path . DS . "product.xml";
        if (count($cid))
        {
            $cids = implode(',', $cid);
            if ($unpublished_data == 1)
            {
                $query = "SELECT p.*,m.manufacturer_name FROM " . $this->_table_prefix . "product AS p " . " LEFT JOIN " . $this->_table_prefix . "manufacturer AS m" . " ON p.manufacturer_id = m.manufacturer_id" . " WHERE p.product_id IN (" . $cids . ")";
            }
            else
            {
                $query = "SELECT p.*,m.manufacturer_name FROM " . $this->_table_prefix . "product AS p " . " LEFT JOIN " . $this->_table_prefix . "manufacturer AS m" . " ON p.manufacturer_id = m.manufacturer_id" . " WHERE p.product_id IN (" . $cids . ") and p.published =1";
            }
            $this->_db->setQuery($query);

            $rs = $this->_db->loadObjectlist();

            // For shipping information
            $shippingArr = $shippinghelper->getShopperGroupDefaultShipping();

            $default_shipping         = 0.00;
            $shipping_rate            = $currency->convert(number_format($shippingArr['shipping_rate'], PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR), '', CURRENCY_CODE);
            $default_shipping         = (count($shippingArr) > 0) ? $shipping_rate : number_format($default_shipping, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            $default_shipping_country = DEFAULT_SHIPPING_COUNTRY;

            // End

            $xml_code = '<?xml version="1.0" encoding="UTF-8" ?>';
            $xml_code .= '<rss version ="2.0" xmlns:g="http://base.google.com/ns/1.0" xmlns:c="http://base.google.com/cns/1.0">';
            $xml_code .= "<channel>";
            for ($i = 0; $i < count($rs); $i++)
            {
                // for additional images
                $additional_images = $producthelper->getAdditionMediaImage($rs[$i]->product_id, $section = "product", $mediaType = "images");

                $add_image = "";
                for ($ad = 0; $ad < 10; $ad++)
                {
                    if (trim($additional_images[$ad]->product_full_image) != trim($additional_images[$ad]->media_name) && trim($additional_images[$ad]->media_name) != "")
                    {
                        $add_image .= "<g:additional_image_link>" . $product_img_url . htmlspecialchars($additional_images[$ad]->media_name, ENT_NOQUOTES, "UTF-8") . "</g:additional_image_link>";
                    }
                }

                // for getting product Category
                $category_name = $producthelper->getCategoryNameByProductId($rs[$i]->product_id);
                // End
                if (USE_STOCKROOM == 1)
                {
                    // for cunt attributes
                    $attributes_set = array();
                    if ($rs[$i]->attribute_set_id > 0)
                    {
                        $attributes_set = $producthelper->getProductAttribute(0, $rs[$i]->attribute_set_id, 0, 1);
                    }
                    $attributes = $producthelper->getProductAttribute($rs[$i]->product_id);
                    $attributes = array_merge($attributes, $attributes_set);
                    $totalatt   = count($attributes);

                    // get stock details
                    $isStockExists = $stockroomhelper->isStockExists($rs[$i]->product_id);

                    if ($totalatt > 0 && !$isStockExists)
                    {

                        $isStockExists = $stockroomhelper->isAttributeStockExists($product_id);
                    }

                    $isPreorderStockExists = $stockroomhelper->isPreorderStockExists($product_id);
                    if ($totalatt > 0 && !$isPreorderStockExists)
                    {
                        $isPreorderStockExists = $stockroomhelper->isAttributePreorderStockExists($product_id);
                    }

                    if (!$isStockExists)
                    {
                        $product_preorder = $rs[$i]->preorder;
                        if (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes") || ($product_preorder == "" && ALLOW_PRE_ORDER))
                        {
                            if (!$isPreorderStockExists)
                            {

                                $product_status = JText::_('COM_REDSHOP_OUT_OF_STOCK');
                            }
                            else
                            {

                                $product_status = JText::_('COM_REDSHOP_PREORDER');
                            }
                        }
                        else
                        {

                            $product_status = JText::_('COM_REDSHOP_OUT_OF_STOCK');
                        }
                    }
                    else
                    {
                        $product_status = JText::_('COM_REDSHOP_AVAILABLE_FOR_ORDER');
                    }
                }
                else
                {
                    $product_status = JText::_('COM_REDSHOP_AVAILABLE_FOR_ORDER');
                }
                // End

                $product_on_sale = 0;
                if ($rs[$i]->product_on_sale == 1 && (($rs[$i]->discount_stratdate == 0 && $rs[$i]->discount_enddate == 0) || ($rs[$i]->discount_stratdate <= time() && $rs[$i]->discount_enddate >= time())))
                {
                    $product_on_sale = 1;
                }
                // For price and vat settings

                $product_price  = $rs[$i]->product_price;
                $discount_price = $rs[$i]->discount_price;
                $sale_price     = ($product_on_sale == 1) ? $discount_price : $product_price;
                $price_vat      = $producthelper->getGoogleVatRates($rs[$i]->product_id, $product_price, USE_TAX_EXEMPT);
                $sale_price_vat = $producthelper->getGoogleVatRates($rs[$i]->product_id, $sale_price, USE_TAX_EXEMPT);
                if (DEFAULT_VAT_COUNTRY != "USA")
                {

                    $product_price = $rs[$i]->product_price + $price_vat;
                    $sale_price    = $sale_price + $sale_price_vat;
                }

                $product_price  = $currency->convert(number_format($product_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR), '', CURRENCY_CODE);
                $discount_price = $currency->convert(number_format($discount_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR), '', CURRENCY_CODE);
                $sale_price     = $currency->convert(number_format($sale_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR), '', CURRENCY_CODE);
                $price_vat      = $currency->convert(number_format($price_vat, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR), '', CURRENCY_CODE);
                //End

                $product_url = $url . "index.php?option=com_redshop&amp;view=product&amp;pid=" . $rs[$i]->product_id;
                //$product_url = $url."index.html";
                //if($rs[$i]->publish_date)
                $xml_code .= "\n<item>";
                $xml_code .= "\n<g:id>" . htmlspecialchars($rs[$i]->product_id, ENT_NOQUOTES, "UTF-8") . "</g:id>";
                $xml_code .= "\n<title>" . htmlspecialchars($rs[$i]->product_name, ENT_NOQUOTES, "UTF-8") . "</title>";
                $xml_code .= "\n<description>'" . htmlspecialchars($rs[$i]->product_s_desc, ENT_NOQUOTES, "UTF-8") . "'</description>";
                $xml_code .= "\n<g:product_type>'" . htmlspecialchars($category_name, ENT_NOQUOTES, "UTF-8") . "'</g:product_type>";
                $xml_code .= "\n<link>" . htmlspecialchars($product_url, ENT_NOQUOTES, "UTF-8") . "</link>";
                $xml_code .= "\n<g:image_link>" . $product_img_url . htmlspecialchars($rs[$i]->product_full_image, ENT_NOQUOTES, "UTF-8") . "</g:image_link>";
                $xml_code .= "\n<g:brand>" . htmlspecialchars($rs[$i]->manufacturer_name, ENT_NOQUOTES, "UTF-8") . "</g:brand>";
                $xml_code .= "\n<g:condition>New</g:condition>";
                $xml_code .= "\n<g:availability>" . $product_status . "</g:availability>";
                $xml_code .= "\n<g:price>" . $product_price . " " . CURRENCY_CODE . "</g:price>";
                $xml_code .= "\n<g:sale_price>" . $sale_price . " " . CURRENCY_CODE . "</g:sale_price>";

                if ($product_on_sale == 1)
                {
                    $discount_start_date = date("c", $rs[$i]->discount_stratdate);
                    $discount_end_date   = date("c", $rs[$i]->discount_enddate);
                    $xml_code .= "\n<g:sale_price_effective_date>" . $discount_start_date . "/" . $discount_end_date . "</g:sale_price_effective_date>";
                }
                $xml_code .= "\n<g:mpn>" . htmlspecialchars($rs[$i]->product_number, ENT_NOQUOTES, "UTF-8") . "</g:mpn>";
                if (DEFAULT_VAT_COUNTRY == "USA" || DEFAULT_VAT_COUNTRY == "GBR")
                {
                    $xml_code .= "\n<g:delivery>
						<g:country>" . DEFAULT_SHIPPING_COUNTRY . "</g:country>
					    <g:price>" . $default_shipping . " " . CURRENCY_CODE . "</g:price>
					</g:delivery>";
                    if ($rs[$i]->weight != 0)
                    {
                        $xml_code .= "\n<g:delivery_weight>" . $rs[$i]->weight . " " . DEFAULT_WEIGHT_UNIT . "</g:delivery_weight>";
                    }
                }
                else
                {

                    $xml_code .= "\n<g:shipping>
						<g:country>" . DEFAULT_SHIPPING_COUNTRY . "</g:country>
					    <g:price>" . $default_shipping . " " . CURRENCY_CODE . "</g:price>
					</g:shipping>";
                    if ($rs[$i]->weight != 0)
                    {
                        $xml_code .= "\n<g:shipping_weight>" . $rs[$i]->weight . " " . DEFAULT_WEIGHT_UNIT . "</g:shipping_weight>";
                    }
                }
                if (DEFAULT_VAT_COUNTRY == "USA")
                {

                    $xml_code .= "\n<g:tax>
								   <g:country>US</g:country>
								   <g:rate>" . $price_vat . "</g:rate>
							  </g:tax>";
                }
                $xml_code .= "\n" . $add_image;
                $xml_code .= "\n</item>";
            }
            $xml_code .= '</channel>';
            $xml_code .= '</rss>';

            $fp = fopen($file_name, "w");
            fwrite($fp, $xml_code);
            fclose($fp);

            if (!file_exists($file_name))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        return false;
    }

    public function getCategoryList()
    {
        if ($this->_categorytreelist)
        {
            return $this->_categorytreelist;
        }
        $this->_categorytreelist = array();
        $q                       = "SELECT cx.category_child_id AS id, cx.category_parent_id AS parent_id, c.category_name AS title " . "FROM " . $this->_table_prefix . "category AS c, " . $this->_table_prefix . "category_xref AS cx " . "WHERE c.category_id=cx.category_child_id " . "ORDER BY ordering ";
        $this->_db->setQuery($q);
        $rows = $this->_db->loadObjectList();

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
        $list = $this->treerecurse(0, '', array(), $children);

        //		$treelist = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        if (count($list) > 0)
        {
            $this->_categorytreelist = $list;
        }
        return $this->_categorytreelist;
    }

    public function treerecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0)
    {
        if (@$children[$id] && $level <= $maxlevel)
        {
            foreach ($children[$id] as $v)
            {
                $id     = $v->id;
                $spacer = '  ';
                if ($v->parent_id == 0)
                {
                    $txt = $v->title;
                }
                else
                {
                    $txt = '- ' . $v->title;
                }
                $pt                  = $v->parent_id;
                $list[$id]           = $v;
                $list[$id]->treename = $indent . $txt;
                $list[$id]->children = count(@$children[$id]);
                $list                = $this->treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1);
            }
        }
        return $list;
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
            foreach ($orderarray as $productid=> $order)
            {
                if ($order >= 0)
                {
                    // update ordering
                    $query = 'UPDATE ' . $this->_table_prefix . 'product_category_xref' . ' SET ordering = ' . ( int )$i . ' WHERE product_id=' . $productid . ' AND category_id = ' . $category_id_my;
                    $this->_db->setQuery($query);
                    $this->_db->query();
                }
                $i++;
            }
        }
        return true;
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
                $subattr_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_subattribute_color  WHERE subattribute_id ="' . $imagename->property_id . '" ';
                $this->_db->setQuery($subattr_delete);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    //return false;
                }

                $attr_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute WHERE attribute_id ="' . $imagename->attribute_id . '" ';
                $this->_db->setQuery($attr_delete);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    //return false;
                }
                $prop_delete = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_property WHERE attribute_id ="' . $imagename->attribute_id . '" ';
                $this->_db->setQuery($prop_delete);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    //return false;
                }
            }
            /*********************DELETE WRAPPER*****************/
            //			$wquery = 'SELECT * FROM '.$this->_table_prefix.'wrapper '
            //					.'WHERE product_id IN( '.$cids.') ';
            //			$this->_db->setQuery( $wquery );
            //			$wrapperimage = $this->_db->loadObjectlist();
            //			for($i=0;$i<count($wrapperimage);$i++)
            //			{
            //				$wimg1 = REDSHOP_FRONT_IMAGES_RELPATH.'wrapper/'.$wrapperimage[$i]->wrapper_image;
            //		 		$wimg2 = REDSHOP_FRONT_IMAGES_RELPATH.'wrapper/thumb/'.$wrapperimage[$i]->wrapper_image;
            //		 		if(is_file($wimg1))
            //		 			unlink($wimg1);
            //		 		if(is_file($wimg2))
            //		 			unlink($wimg2);
            //			}
            //			$query = 'DELETE FROM '.$this->_table_prefix.'wrapper WHERE product_id IN ( '.$cids.' )';
            //			$this->_db->setQuery( $query );
            //			if(!$this->_db->query())
            //			{
            //				$this->setError($this->_db->getErrorMsg());
            //			}
            /*********************END DELETE WRAPPER*****************/

            $image_query = 'SELECT p.product_thumb_image,p.product_full_image,p.product_back_full_image,p.product_back_thumb_image,p.product_preview_image,p.product_preview_back_image  FROM ' . $this->_table_prefix . 'product as p WHERE p.product_id IN( ' . $cids . ')';
            $this->_db->setQuery($image_query);
            $product_image = $this->_db->loadObjectlist();

            foreach ($product_image as $imagename)
            {

                $dest_full         = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_full_image;
                $tsrc_thumb        = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_thumb_image;
                $dest_back_full    = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_back_full_image;
                $tsrc_back_thumb   = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_back_thumb_image;
                $dest_preview      = REDSHOP_FRONT_IMAGES_RELPATH . '/product/' . $imagename->product_preview_image;
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
                //return false;
            }

            //---------- Accessory Delete ---------------

            $query_related = 'DELETE FROM ' . $this->_table_prefix . 'product_accessory WHERE product_id IN ( ' . $cids . ' )';

            $this->_db->setQuery($query_related);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            //---------- End Of Accessory Delete ---------
            //---------- Related  Delete ---------------

            $query_related = 'DELETE FROM ' . $this->_table_prefix . 'product_related WHERE product_id IN ( ' . $cids . ' )';

            $this->_db->setQuery($query_related);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            //---------- End Of Related Delete ---------

            //---------- Media Images Delete ---------------

            $image_media = 'SELECT media_name,media_id FROM ' . $this->_table_prefix . 'media WHERE section_id IN( ' . $cids . ') AND media_section = "product" ';
            $this->_db->setQuery($image_media);
            $media_image = $this->_db->loadObjectlist();

            /*foreach($media_image as $mimage){

				$dest = REDSHOP_FRONT_IMAGES_RELPATH.'product/'.$mimage->media_id.'_'.$mimage->media_name;

		 		$tsrc = REDSHOP_FRONT_IMAGES_RELPATH.'product/thumb/'.$mimage->media_id.'_'.$mimage->media_name;

		 		if(file_exists($dest))
		 			unlink($dest);
		 		if(file_exists($tsrc))
		 			unlink($tsrc);

			}*/

            $query_media = 'DELETE FROM ' . $this->_table_prefix . 'media WHERE section_id IN ( ' . $cids . ' ) AND media_section = "product"';

            $this->_db->setQuery($query_media);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            // remove product category xref relation
            $query_relation = 'DELETE FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id IN ( ' . $cids . ' ) ';

            $this->_db->setQuery($query_relation);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            // Delete Association if Exist

            $check_asso = $this->CheckRedProductFinder();

            if ($check_asso > 0)
            {

                $this->RemoveAssociation($cid);
            }

            // 	remove product tags relation
            $query = 'DELETE FROM ' . $this->_table_prefix . 'product_tags_xref  WHERE product_id IN ( ' . $cids . ' ) ';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            // 	remove product wishlist relation
            $query = 'DELETE FROM ' . $this->_table_prefix . 'wishlist_product  WHERE product_id IN ( ' . $cids . ' ) ';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            // 	remove product compare relation
            $query = 'DELETE FROM ' . $this->_table_prefix . 'product_compare  WHERE product_id IN ( ' . $cids . ' ) ';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }

            // 	remove fields_data relation
            $query = 'DELETE FROM ' . $this->_table_prefix . 'fields_data  WHERE itemid IN ( ' . $cids . ' ) ';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }
        }
        return true;
    }

    public function copy($cid = array())
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
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
            $copyquantity  = array();

            for ($i = 0; $i < count($stockroomdata); $i++)
            {
                $copystockroom[$i] = $stockroomdata[$i]->stockroom_id;
                $copyquantity[$i]  = $stockroomdata[$i]->quantity;
            }

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_accessory WHERE product_id IN ( ' . $pdata->product_id . ' )';
            $this->_db->setQuery($query);
            $accessorydata = $this->_db->loadObjectList();
            $copyaccessory = array();
            //accessory_product
            for ($i = 0; $i < count($accessorydata); $i++)
            {
                $copyaccessory[$i] = (array)$accessorydata[$i];
            }

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_price WHERE product_id IN ( ' . $pdata->product_id . ' )';
            $this->_db->setQuery($query);
            $productpricedata = $this->_db->loadObjectList();

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'media WHERE media_section = "product" AND section_id IN ( ' . $pdata->product_id . ' )';
            $this->_db->setQuery($query);
            $mediadata = $this->_db->loadObjectList();

            $post['copy_product']         = 1;
            $post['product_id']           = 0;
            $post['product_parent_id']    = $pdata->product_parent_id;
            $post['manufacturer_id']      = $pdata->manufacturer_id;
            $post['supplier_id']          = $pdata->supplier_id;
            $post['product_on_sale']      = $pdata->product_on_sale;
            $post['product_special']      = $pdata->product_special;
            $post['product_download']     = $pdata->product_download;
            $post['product_template']     = $pdata->product_template;
            $post['product_name']         = JText::_('COM_REDSHOP_COPY_OF') . ' ' . $pdata->product_name;
            $post['product_price']        = $pdata->product_price;
            $post['discount_price']       = $pdata->discount_price;
            $post['discount_stratdate']   = $pdata->discount_stratdate;
            $post['discount_enddate']     = $pdata->discount_enddate;
            $post['product_length']       = $pdata->product_length;
            $post['product_height']       = $pdata->product_height;
            $post['product_width']        = $pdata->product_width;
            $post['product_diameter']     = $pdata->product_diameter;
            $post['discount_calc_method'] = $pdata->discount_calc_method;
            $post['use_discount_calc']    = $pdata->use_discount_calc;
            $post['use_range']            = $pdata->use_range;

            $post['product_number']             = trim(JText::_('COM_REDSHOP_COPY_OF') . ' ' . $pdata->product_number);
            $post['product_type']               = $pdata->product_type;
            $post['product_s_desc']             = $pdata->product_s_desc;
            $post['product_desc']               = $pdata->product_desc;
            $post['product_volume']             = $pdata->product_volume;
            $post['product_tax_id']             = $pdata->product_tax_id;
            $post['attribute_set_id']           = $pdata->attribute_set_id;
            $post['product_tax_group_id']       = $pdata->product_tax_group_id;
            $post['min_order_product_quantity'] = $pdata->min_order_product_quantity;
            $post['max_order_product_quantity'] = $pdata->max_order_product_quantity;
            $post['accountgroup_id']            = $pdata->accountgroup_id;
            $post['quantity_selectbox_value']   = $pdata->quantity_selectbox_value;
            $post['not_for_sale']               = $pdata->not_for_sale;
            $post['product_availability_date']  = $pdata->product_availability_date;

            $post['published']           = 0;
            $post['product_thumb_image'] = '';
            $post['product_full_image']  = '';
            if (!empty($pdata->product_thumb_image))
            {

                $new_product_thumb_image     = strstr($pdata->product_thumb_image, '_') ? strstr($pdata->product_thumb_image, '_') : $pdata->product_thumb_image;
                $post['product_thumb_image'] = JPath::clean(time() . $new_product_thumb_image);
            }
            if (!empty($pdata->product_full_image))
            {
                $new_product_full_image     = strstr($pdata->product_full_image, '_') ? strstr($pdata->product_full_image, '_') : $pdata->product_full_image;
                $post['product_full_image'] = JPath::clean(time() . $new_product_full_image);
            }
            if (!empty($pdata->product_back_full_image))
            {
                $new_product_back_full_image     = strstr($pdata->product_back_full_image, '_') ? strstr($pdata->product_back_full_image, '_') : $pdata->product_back_full_image;
                $post['product_back_full_image'] = JPath::clean(time() . $new_product_back_full_image);
            }
            if (!empty($pdata->product_back_thumb_image))
            {
                $new_product_back_thumb_image     = strstr($pdata->product_back_thumb_image, '_') ? strstr($pdata->product_back_thumb_image, '_') : $pdata->product_back_thumb_image;
                $post['product_back_thumb_image'] = JPath::clean(time() . $new_product_back_thumb_image);
            }
            if (!empty($pdata->product_preview_image))
            {
                $new_product_preview_image     = strstr($pdata->product_preview_image, '_') ? strstr($pdata->product_preview_image, '_') : $pdata->product_preview_image;
                $post['product_preview_image'] = JPath::clean(time() . $new_product_preview_image);
            }
            if (!empty($pdata->product_preview_back_image))
            {
                $new_product_preview_back_image     = strstr($pdata->product_preview_back_image, '_') ? strstr($pdata->product_preview_back_image, '_') : $pdata->product_preview_back_image;
                $post['product_preview_back_image'] = JPath::clean(time() . $new_product_preview_back_image);
            }
            $post['publish_date']         = date("Y-m-d H:i:s");
            $post['update_date']          = date("Y-m-d H:i:s");
            $post['visited']              = $pdata->visited;
            $post['metakey']              = $pdata->metakey;
            $post['metadesc']             = $pdata->metadesc;
            $post['metalanguage_setting'] = $pdata->metalanguage_setting;
            $post['metarobot_info']       = $pdata->metarobot_info;
            $post['pagetitle']            = $pdata->pagetitle;
            $post['pageheading']          = $pdata->pageheading;
            $post['cat_in_sefurl']        = $pdata->cat_in_sefurl;
            $post['weight']               = $pdata->weight;
            $post['expired']              = $pdata->expired;
            $post['product_category']     = $copycategory;
            $post['related_product']      = $copyrelatedproduct;
            $post['quantity']             = $copyquantity;
            $post['stockroom_id']         = $copystockroom;
            $post['product_accessory']    = $copyaccessory;

            if ($row = $this->store($post))
            {
                //Image Copy Start
                $old = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $pdata->product_full_image;
                $new = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_product_full_image);
                copy($old, $new);

                $old_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $pdata->product_thumb_image;
                $new_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_product_thumb_image);
                copy($old_thumb, $new_thumb);

                $old_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $pdata->product_preview_image;
                $new_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_product_preview_image);
                copy($old_preview, $new_preview);

                $old_back_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $pdata->product_preview_back_image;
                $new_back_preview = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_product_preview_back_image);
                copy($old_back_preview, $new_back_preview);

                $old_prod_back_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $pdata->product_back_full_image;
                $new_prod_back_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_product_back_full_image);
                copy($old_prod_back_full, $new_prod_back_full);

                $old_prod_back_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $pdata->product_back_thumb_image;
                $new_back_back_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_product_back_thumb_image);
                copy($old_prod_back_thumb, $new_back_back_thumb);

                $field      = new extra_field();
                $list_field = $field->copy_product_extra_field($pdata->product_id, $row->product_id); /// field_section 1 :Product

                //End
                $this->SaveStockroom($row->product_id, $post);
                $this->copyProductAttribute($pdata->product_id, $row->product_id);
                $this->copyDiscountCalcdata($pdata->product_id, $row->product_id, $pdata->discount_calc_method);

                for ($i = 0; $i < count($productpricedata); $i++)
                {
                    $rowprices_detail             = $this->getTable('prices_detail');
                    $data['price_id ']            = 0;
                    $data['product_id']           = $row->product_id;
                    $data['product_price']        = $productpricedata[$i]->product_price;
                    $data['product_currency']     = $productpricedata[$i]->product_currency;
                    $data['shopper_group_id']     = $productpricedata[$i]->shopper_group_id;
                    $data['price_quantity_start'] = $productpricedata[$i]->price_quantity_start;
                    $data['price_quantity_end']   = $productpricedata[$i]->price_quantity_end;
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
                    $old_img   = $mediadata[$j]->media_name;
                    $new_img   = strstr($old_img, '_') ? strstr($old_img, '_') : $old_img;
                    $old_media = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $mediadata[$j]->media_name;
                    $new_media = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . JPath::clean(time() . $new_img);
                    copy($old_media, $new_media);

                    $rowmedia                     = $this->getTable('media_detail');
                    $data['media_id ']            = 0;
                    $data['media_name']           = JPath::clean(time() . $new_img);
                    $data['media_alternate_text'] = $mediadata[$j]->media_alternate_text;
                    $data['media_section']        = $mediadata[$j]->media_section;
                    $data['section_id']           = $row->product_id;
                    $data['media_type']           = $mediadata[$j]->media_type;
                    $data['media_mimetype']       = $mediadata[$j]->media_mimetype;
                    $data['published']            = $mediadata[$j]->published;
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
}

