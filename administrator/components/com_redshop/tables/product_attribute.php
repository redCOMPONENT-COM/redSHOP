<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableproduct_attribute extends JTable
{
    public $attribute_id = null;

    public $attribute_set_id = 0;

    public $attribute_name = null;

    public $attribute_required = null;

    public $allow_multiple_selection = 0;

    public $hide_attribute_price = 0;

    public $product_id = null;

    public $ordering = null;

    public $attribute_published = 1;

    public $display_type = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_attribute', 'attribute_id', $db);
    }
}
