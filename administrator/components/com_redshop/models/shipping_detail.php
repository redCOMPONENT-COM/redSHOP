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

class shipping_detailModelshipping_detail extends RedshopCoreModelDetail
{
    public $_copydata = null;

    public function &getData()
    {
        $this->_loadData();
        return $this->_data;
    }

    public function _loadData()
    {
        $query = 'SELECT * FROM #__extensions WHERE folder="redshop_shipping" and extension_id ="' . $this->_id . '" ';
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return (boolean)$this->_data;
    }

    public function store($data)
    {
        $query = 'UPDATE #__extensions ' . 'SET name="' . $data['name'] . '" ' . ',enabled ="' . intval($data['published']) . '" ' . 'WHERE element="' . $data['element'] . '" ';
        $this->_db->setQuery($query);
        $this->_db->query();
        if (!$this->_db->query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onWriteconfig', array($data));

        return true;
    }

    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE #__extensions' . ' SET enabled = ' . intval($publish) . ' WHERE  extension_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function saveOrder(&$cid)
    {
        $row = $this->getTable();

        $total = count($cid);
        $order = JRequest::getVar('order', array(0), 'post', 'array');
        JArrayHelper::toInteger($order, array(0));

        // update ordering values
        for ($i = 0; $i < $total; $i++)
        {
            $row->load((int)$cid[$i]);
            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    throw new RuntimeException($this->_db->getErrorMsg());
                }
            }
        }
        $row->reorder();
        return true;
    }

    /**
     * Method to get max ordering
     *
     * @access public
     * @return boolean
     */
    public function MaxOrdering()
    {
        $query = "SELECT (max(ordering)+1) FROM #__extensions where folder='redshop_shipping'";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     * Method to move
     *
     * @access  public
     * @return  boolean True on success
     * @since   0.9
     */
    public function move($direction)
    {
        $row = JTable::getInstance('shipping_detail', 'Table');

        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    /**
     * Method to up order
     *
     * @access public
     * @return boolean
     */
    public function orderup()
    {
        return $this->move(-1);
    }

    /**
     * Method to down the order
     *
     * @access public
     * @return boolean
     */
    public function orderdown()
    {
        return $this->move(1);
    }
}
