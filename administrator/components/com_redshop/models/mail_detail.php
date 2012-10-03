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

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'thumbnail.php');
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class mail_detailModelmail_detail extends RedshopCoreModelDetail
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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'mail WHERE mail_id = ' . $this->_id;
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
            $detail                    = new stdClass();
            $detail->mail_id           = 0;
            $detail->mail_name         = null;
            $detail->mail_subject      = null;
            $detail->mail_section      = 0;
            $detail->mail_order_status = null;
            $detail->mail_body         = null;
            $detail->published         = 1;
            $detail->mail_bcc          = null;
            $this->_data               = $detail;

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

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'mail WHERE mail_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'mail' . ' SET published = ' . intval($publish) . ' WHERE mail_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function mail_section()
    {
        $query = 'SELECT order_status_code as value, concat(order_status_name," (",order_status_code,")") as text FROM ' . $this->_table_prefix . 'order_status  where published=1';

        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    public function order_statusHtml($order_status)
    {
        $select   = array();
        $select[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_Select'));

        $merge = array_merge($select, $order_status);

        return JHTML::_('select.genericlist', $merge, 'mail_order_status', 'class="inputbox" size="1" title="" ', 'value', 'text');
    }
}
