<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tablediscount_detail
class Tablediscount extends JTable
{
    public $discount_id = 0;

    public $amount = null;

    public $condition = null;

    public $discount_amount = null;

    public $discount_type = null;

    public $start_date = null;

    public $end_date = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'discount', 'discount_id', $db);
    }
}
