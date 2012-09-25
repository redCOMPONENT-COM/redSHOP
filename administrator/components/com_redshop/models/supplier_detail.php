<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class supplier_detailModelsupplier_detail extends JModelLegacy
{
    var $_id = null;

    var $_data = null;

    var $_table_prefix = null;

    var $_copydata = null;

    var $_templatedata = null;

    function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';

        $array = JRequest::getVar('cid', 0, '', 'array');

        $this->setId((int)$array[0]);
    }

    function setId($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    function &getData()
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

    function _loadData()
    {
        if (empty($this->_data))
        {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'supplier WHERE supplier_id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    function _initData()
    {
        if (empty($this->_data))
        {
            $detail                 = new stdClass();
            $detail->supplier_id    = 0;
            $detail->supplier_name  = null;
            $detail->supplier_desc  = null;
            $detail->supplier_email = null;
            $detail->published      = 1;
            $this->_data            = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    function store($data)
    {
        $row = $this->getTable('supplier_detail');

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

    function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'supplier WHERE supplier_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'supplier' . ' SET published = ' . intval($publish) . ' WHERE supplier_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function copy($cid = array())
    {

        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'supplier WHERE supplier_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->_copydata = $this->_db->loadObjectList();
        }
        foreach ($this->_copydata as $cdata)
        {

            $post['supplier_id']    = 0;
            $post['supplier_name']  = 'Copy Of ' . $cdata->supplier_name;
            $post['supplier_desc']  = $cdata->supplier_desc;
            $post['supplier_email'] = $cdata->supplier_email;
            $post['published']      = $cdata->published;

            supplier_detailModelsupplier_detail::store($post);
        }

        return true;
    }
}
