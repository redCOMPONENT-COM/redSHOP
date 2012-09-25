<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableorder_status_log extends JTable
{
    public $order_status_log_id = null;

    public $order_id = null;

    public $order_status = null;

    public $order_payment_status = null;

    public $date_changed = null;

    public $customer_note = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'order_status_log', 'order_status_log_id', $db);
    }
}
