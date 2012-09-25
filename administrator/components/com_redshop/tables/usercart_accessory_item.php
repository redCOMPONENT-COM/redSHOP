<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableusercart_accessory_item extends JTable
{
    public $cart_acc_item_id = 0;

    public $cart_item_id = 0;

    public $accessory_id = 0;

    public $accessory_quantity = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'usercart_accessory_item', 'cart_acc_item_id', $db);
    }
}
