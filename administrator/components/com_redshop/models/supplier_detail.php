<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class supplier_detailModelsupplier_detail extends JModel
{
    var $_id = null;
    var $_data = null;
    var $_table_prefix = null;
    var $_copydata = null;
    var $_templatedata = null;

    function __construct ()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';

        $array = JRequest::getVar('cid', 0, '', 'array');

        $this->setId((int)$array[0]);
    }

    function setId ($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    function &getData ()
    {
        if ($this->_loadData()) {
        } else  {
            $this->_initData();
        }

        return $this->_data;
    }

    function _loadData ()
    {
        if (empty($this->_data)) {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'supplier WHERE supplier_id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }


    function _initData ()
    {
        if (empty($this->_data)) {
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

    function store ($data)
    {
        $row = $this->getTable('supplier_detail');

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $row;
    }

    function delete ($cid = array())
    {
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'supplier WHERE supplier_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function publish ($cid = array(), $publish = 1)
    {
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'supplier'
                . ' SET published = ' . intval($publish)
                . ' WHERE supplier_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function copy ($cid = array())
    {

        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'supplier WHERE supplier_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->_copydata = $this->_db->loadObjectList();
        }
        foreach ($this->_copydata as $cdata) {

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

?>
