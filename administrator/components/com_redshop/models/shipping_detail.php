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
jimport('joomla.filesystem.file');
class shipping_detailModelshipping_detail extends JModelLegacy
{
    var $_id = null;

    var $_data = null;

    var $_table_prefix = null;

    var $_copydata = null;

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
        $this->_loadData();
        return $this->_data;
    }

    function _loadData()
    {
        $query = 'SELECT * FROM #__extensions WHERE folder="redshop_shipping" and extension_id ="' . $this->_id . '" ';
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return (boolean)$this->_data;
    }

    function store($data)
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
        $payment    = $dispatcher->trigger('onWriteconfig', array($data));
        return true;
    }

    function publish($cid = array(), $publish = 1)
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

    function saveOrder(&$cid)
    {
        global $mainframe;
        //$scope 		= JRequest::getCmd( 'scope' );
        $db  = JFactory::getDBO();
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
                    JError::raiseError(500, $db->getErrorMsg());
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
    function MaxOrdering()
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
    function move($direction)
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
}
