<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablestockroom_detail extends JTable
{
    public $stockroom_id = null;

    public $stockroom_name = null;

    public $min_stock_amount = 0;

    public $stockroom_desc = null;

    public $creation_date = null;

    public $min_del_time = null;

    public $max_del_time = null;

    public $show_in_front = 0;

    public $delivery_time = 'Days';

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'stockroom', 'stockroom_id', $db);
    }
}
