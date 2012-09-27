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

class zipcode_detailModelzipcode_detail extends RedshopCoreModelDetail
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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'zipcode WHERE zipcode_id = ' . $this->_id;
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

            $detail->zipcode_id   = 0;
            $detail->city_name    = null;
            $detail->state_code   = null;
            $detail->country_code = null;
            $detail->zipcode      = null;

            $this->_data = $detail;

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

        if (!$row->check())
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

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'zipcode WHERE zipcode_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function getcountry()
    {
        require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'helper.php');
        $redhelper = new redhelper();
        $q         = "SELECT  country_3_code as value,country_name as text,country_jtext from #__" . TABLE_PREFIX . "_country ORDER BY country_name ASC";
        $this->_db->setQuery($q);
        $countries = $this->_db->loadObjectList();
        $countries = $redhelper->convertLanguageString($countries);
        return $countries;
    }
}
