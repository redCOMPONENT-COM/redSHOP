<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tablestockimage_detail
class Tablestockroom_amount_image extends JTable
{
    public $stock_amount_id = null;

    public $stockroom_id = null;

    public $stock_option = null;

    public $stock_quantity = null;

    public $stock_amount_image = null;

    public $stock_amount_image_tooltip = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';
        parent::__construct($this->_table_prefix . 'stockroom_amount_image', 'stock_amount_id', $db);
    }
}
