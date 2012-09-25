<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableorder_attribute_item extends JTable
{
    var $order_att_item_id = null;

    var $order_item_id = null;

    var $section_id = null;

    var $section = null;

    var $parent_section_id = null;

    var $section_name = null;

    var $section_vat = null;

    var $section_price = null;

    var $section_oprand = null;

    var $is_accessory_att = null;

    var $stockroom_id = null;

    var $stockroom_quantity = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'order_attribute_item', 'order_att_item_id', $db);
    }
}
