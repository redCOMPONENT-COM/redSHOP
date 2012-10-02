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

class accountgroup_detailModelaccountgroup_detail extends RedshopCoreModelDetail
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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'economic_accountgroup ' . 'WHERE accountgroup_id="' . $this->_id . '" ';
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

            $detail->accountgroup_id                  = 0;
            $detail->accountgroup_name                = null;
            $detail->economic_vat_account             = null;
            $detail->economic_nonvat_account          = null;
            $detail->economic_discount_vat_account    = null;
            $detail->economic_discount_nonvat_account = null;
            $detail->economic_shipping_vat_account    = null;
            $detail->economic_shipping_nonvat_account = null;
            $detail->economic_discount_product_number = null;
            $detail->published                        = 1;
            $this->_data                              = $detail;

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
}
