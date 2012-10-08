<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tabletransaction_voucher_detail
class Tableproduct_voucher_transaction extends JTable
{
    public $transaction_voucher_id = null;

    public $voucher_id = null;

    public $voucher_code = null;

    public $amount = null;

    public $user_id = null;

    public $order_id = null;

    public $trancation_date = null;

    public $product_id = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_voucher_transaction', 'transaction_voucher_id', $db);
    }
}
