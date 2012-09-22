<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class producttags_detailModelproducttags_detail extends JModel
{
    var $_id = null;

    var $_data = null;

    var $_table_prefix = null;

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
        else  {
            $this->_initData();
        }

        return $this->_data;
    }

    function _loadData()
    {
        if (empty($this->_data))
        {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'product_tags WHERE tags_id = ' . $this->_id;
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
            $detail = new stdClass();

            $detail->tags_id      = 0;
            $detail->tags_name    = null;
            $detail->tags_counter = 0;
            $detail->published    = 1;
            $this->_data          = $detail;

            return (boolean)$this->_data;
        }
        return true;
    }

    function store($data)
    {
        $row = $this->getTable('product_tags');

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

        return true;
    }

    function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'product_tags WHERE tags_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            else
            {
                $query = 'DELETE FROM ' . $this->_table_prefix . 'product_tags_xref WHERE tags_id IN ( ' . $cids . ' )';
                $this->_db->setQuery($query);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }

        return true;
    }

    function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'product_tags' . ' SET published = ' . intval($publish) . ' WHERE tags_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }
}
