<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tableshipping_box_detail
class Tableshipping_boxes extends JTable
{
    public $shipping_box_id = null;

    public $shipping_box_name = null;

    public $shipping_box_length = null;

    public $shipping_box_width = null;

    public $shipping_box_height = null;

    public $shipping_box_priority = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'shipping_boxes', 'shipping_box_id', $db);
    }
}
