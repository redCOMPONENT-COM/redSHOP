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

class discount_detailModeldiscount_detail extends RedshopCoreModelDetail
{
    public $_shoppers = null;

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
        $layout = JRequest::getVar('layout');

        if (empty($this->_data))
        {
            if (isset($layout) && $layout == 'product')
            {
                $query = 'SELECT * FROM ' . $this->_table_prefix . 'discount_product WHERE discount_product_id = ' . $this->_id;
            }
            else
            {
                $query = 'SELECT * FROM ' . $this->_table_prefix . 'discount WHERE discount_id = ' . $this->_id;
            }

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
            $detail = new stdClass();

            $detail->discount_id         = 0;
            $detail->discount_product_id = 0;
            $detail->condition           = 0;
            $detail->shopper_group_id    = 0;
            $detail->amount              = 0;
            $detail->discount_amount     = 0;
            $detail->discount_type       = 'no';
            $detail->start_date          = time();
            $detail->end_date            = time();
            $detail->published           = 1;
            $this->_data                 = $detail;

            return (boolean)$this->_data;
        }
        return true;
    }

    public function store($data)
    {
        $row = $this->getTable('discount_detail');

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
        // Remove Relation With Shoppers
        $sdel = "DELETE FROM " . $this->_table_prefix . "discount_shoppers WHERE discount_id = " . $row->discount_id;
        $this->_db->setQuery($sdel);
        if (!$this->_db->query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $row;
    }

    public function &getShoppers()
    {
        $query = 'SELECT shopper_group_id as value,shopper_group_name as text FROM ' . $this->_table_prefix . 'shopper_group WHERE published = 1';
        $this->_db->setQuery($query);
        $this->_shoppers = $this->_db->loadObjectList();

        return $this->_shoppers;
    }

    public function selectedShoppers()
    {
        $layout = JRequest::getVar('layout');
        if (isset($layout) && $layout == 'product')
        {
            $query = "SELECT s.shopper_group_id as value,s.shopper_group_name as text " . " FROM " . $this->_table_prefix . "discount_product_shoppers as ds " . " left join " . $this->_table_prefix . "shopper_group as s on s.shopper_group_id = ds.shopper_group_id " . " WHERE ds.discount_product_id = " . $this->_id;
        }
        else
        {

            $query = "SELECT s.shopper_group_id as value,s.shopper_group_name as text " . " FROM " . $this->_table_prefix . "discount_shoppers as ds " . " left join " . $this->_table_prefix . "shopper_group as s on s.shopper_group_id = ds.shopper_group_id " . " WHERE ds.discount_id = " . $this->_id;
        }

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function saveShoppers($did, $sids)
    {
        $layout = JRequest::getVar('layout');

        foreach ($sids as $sid)
        {
            if (isset($layout) && $layout == 'product')
            {
                $query = "INSERT INTO #__redshop_discount_product_shoppers VALUES('" . $did . "','" . $sid . "')";
            }
            else
            {
                $query = "INSERT INTO #__redshop_discount_shoppers VALUES('" . $did . "','" . $sid . "')";
            }

            $this->_db->setQuery($query);
            if (!$this->_db->Query())
            {
                return false;
            }
        }
        return true;
    }

    public function storeDiscountProduct($data)
    {
        $dprow = $this->getTable('discount_product');

        if (!$dprow->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$dprow->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // 	Remove Relation With Shoppers
        $del = "DELETE FROM " . $this->_table_prefix . "discount_product_shoppers WHERE discount_product_id = " . $dprow->discount_product_id;
        $this->_db->setQuery($del);
        if (!$this->_db->query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $dprow;
    }
}
