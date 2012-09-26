<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tabletransaction_coupon_detail extends JTable
{
    public $transaction_coupon_id = null;

    public $coupon_id = null;

    public $coupon_code = null;

    public $coupon_value = null;

    public $userid = null;

    public $trancation_date = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'coupons_transaction', 'transaction_coupon_id', $db);
    }
}
