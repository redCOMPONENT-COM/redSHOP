<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class shipping_rate_detailModelShipping_rate_detail extends RedshopCoreModelDetail
{
    public $_copydata = null;

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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'shipping_rate WHERE shipping_rate_id="' . $this->_id . '" ';
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
            $detail                                 = new stdClass();
            $detail->shipping_rate_id               = 0;
            $detail->shipping_rate_name             = null;
            $detail->shipping_class                 = null;
            $detail->shipping_rate_country          = null;
            $detail->shipping_rate_state            = null;
            $detail->shipping_rate_on_product       = 0;
            $detail->shipping_rate_on_category      = null;
            $detail->shipping_rate_weight_start     = null;
            $detail->shipping_rate_weight_end       = null;
            $detail->shipping_rate_zip_start        = null;
            $detail->shipping_rate_zip_end          = null;
            $detail->shipping_rate_volume_start     = null;
            $detail->shipping_rate_volume_end       = null;
            $detail->shipping_rate_ordertotal_start = null;
            $detail->shipping_rate_ordertotal_end   = null;
            $detail->shipping_rate_priority         = null;
            $detail->shipping_rate_value            = null;
            $detail->shipping_rate_package_fee      = null;
            $detail->company_only                   = null;
            $detail->shipping_location_info         = null;
            $detail->apply_vat                      = 0;
            $detail->shipping_rate_length_start     = null;
            $detail->shipping_rate_length_end       = null;
            $detail->shipping_rate_width_start      = null;
            $detail->shipping_rate_width_end        = null;
            $detail->shipping_rate_height_start     = null;
            $detail->shipping_rate_height_end       = null;
            $detail->shipping_tax_group_id          = null;
            $detail->shipping_rate_on_shopper_group = null;
            $detail->economic_displaynumber         = null;
            $this->_data                            = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    public function store($data)
    {
        $data['shipping_rate_country']          = @ implode(',', $data['shipping_rate_country']);
        $data['shipping_rate_on_product']       = @ implode(',', $data['shipping_rate_on_product']);
        $data['shipping_rate_on_category']      = @ implode(',', $data['shipping_rate_on_category']);
        $data['shipping_rate_state']            = @ implode(',', $data['shipping_rate_state']);
        $data['shipping_rate_on_shopper_group'] = @ implode(',', $data['shipping_rate_on_shopper_group']);

        $row = $this->getTable();
        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->shipping_rate_on_product)
        {
            $row->shipping_rate_on_product = '';
        }
        if (!$row->shipping_rate_on_category)
        {
            $row->shipping_rate_on_category = '';
        }
        if (!$row->shipping_rate_state)
        {
            $row->shipping_rate_state = '';
        }
        if (!$row->shipping_rate_on_shopper_group)
        {
            $row->shipping_rate_on_shopper_group = '';
        }
        if (!$row->company_only)
        {
            $row->company_only = 0;
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    public function GetProductListshippingrate($d)
    {
        $and = '';
        //if($d!='')
        //{
        $and .= 'AND product_id IN (' . $d . ')';
        //}
        $query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix . 'product ' . 'WHERE published=1 ' . $and;
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function GetProductList()
    {
        $query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix . 'product WHERE published = 1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function GetCategoryList()
    {
        $query = 'SELECT category_name as text,category_id as value FROM ' . $this->_table_prefix . 'category WHERE published = 1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function GetStateList($country_codes)
    {
        $query = 'SELECT s.state_name as text,s.state_2_code as value FROM ' . $this->_table_prefix . 'state AS s ' . 'LEFT JOIN ' . $this->_table_prefix . 'country AS c ON c.country_id = s.country_id ' . 'WHERE find_in_set( c.country_3_code, "' . $country_codes . '" ) ' . 'ORDER BY s.state_name ASC';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getVatGroup()
    {
        $query = "SELECT tg.tax_group_name as text, tg.tax_group_id as value FROM `" . $this->_table_prefix . "tax_group` as tg WHERE `published` = 1 ORDER BY tax_group_id ASC";
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function GetStateDropdown($data)
    {
        $coutry_code      = $data['country_codes'];
        $shipping_rate_id = $data['shipping_rate_id'];

        $shipping_rate = $this->getTable('shipping_rate_detail');
        $shipping_rate->load($shipping_rate_id);
        $shipping_rate_state = $this->GetStateList($coutry_code);

        $shipping_rate->shipping_rate_state = explode(',', $shipping_rate->shipping_rate_state);
        $tmp                                = new stdClass;
        $tmp                                = @array_merge($tmp, $shipping_rate->shipping_rate_state);

        echo JHTML::_('select.genericlist', $shipping_rate_state, 'shipping_rate_state[]', 'class="inputbox" multiple="multiple"', 'value', 'text', $shipping_rate->shipping_rate_state);
    }
}
