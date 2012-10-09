<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

class Tableusercart_attribute_item extends JTable
{
    public $cart_att_item_id = 0;

    public $cart_item_id = 0;

    public $section_id = 0;

    public $section = null;

    public $parent_section_id = 0;

    public $is_accessory_att = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'usercart_attribute_item', 'cart_id', $db);
    }
}
