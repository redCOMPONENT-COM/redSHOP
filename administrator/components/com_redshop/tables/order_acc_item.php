<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableorder_acc_item extends JTable
{
    public $order_item_acc_id = null;

    public $order_item_id = null;

    public $product_id = null;

    public $order_acc_item_sku = null;

    public $order_acc_item_name = null;

    public $order_acc_price = null;

    public $order_acc_vat = null;

    public $product_quantity = null;

    public $product_acc_item_price = null;

    public $product_acc_final_price = null;

    public $product_attribute = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'order_acc_item', 'order_item_acc_id', $db);
    }

    public function bind($array, $ignore = '')
    {
        if (key_exists('params', $array) && is_array($array['params']))
        {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = $registry->toString();
        }
        return parent::bind($array, $ignore);
    }
}
