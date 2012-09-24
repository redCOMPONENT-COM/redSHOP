<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die();

/**
 * Barcode reder/generator Model
 *
 * @package    redSHOP
 * @version    1.2
 */
class barcodeModelbarcode extends JModelLegacy
{
    var $_id = null;

    var $_data = null;

    var $_table_prefix = null;

    var $_loglist = null;

    function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';
    }

    ///var $_hellos=null;
    function save($data)
    {
        $row = $this->getTable('barcode');

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
    }

    function checkorder($barcode)
    {
        $query = "SELECT order_id  FROM " . $this->_table_prefix . "orders where barcode='" . $barcode . "'";
        $this->_db->setQuery($query);
        $order = $this->_db->loadObject();
        if (!$order)
        {
            return false;
        }

        return $order;
    }

    function getLog($order_id)
    {
        $query = "SELECT count(*) as log FROM " . $this->_table_prefix . "orderbarcode_log where order_id=" . $order_id;
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    function getLogdetail($order_id)
    {
        $logquery = "SELECT *  FROM " . $this->_table_prefix . "orderbarcode_log where order_id=" . $order_id;
        $this->_db->setQuery($logquery);
        return $this->_db->loadObjectlist();
    }

    function getUser($user_id)
    {

        $this->_table_prefix = '#__';
        $userquery           = "SELECT name  FROM " . $this->_table_prefix . "users where id=" . $user_id;
        $this->_db->setQuery($userquery);
        return $this->_db->loadObject();
    }

    // for update order status
    function updateorderstatus($barcode, $order_id)
    {
        $update_query = "UPDATE " . $this->_table_prefix . "orders SET order_status = 'S' where barcode='" . $barcode . "' and order_id ='" . $order_id . "'";
        $this->_db->setQuery($update_query);
        $this->_db->query();
    }
}
