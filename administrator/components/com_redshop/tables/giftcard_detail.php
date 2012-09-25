<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablegiftcard_detail extends JTable
{
    public $giftcard_id = null;

    public $giftcard_name = null;

    public $giftcard_validity = null;

    public $giftcard_date = 0;

    public $giftcard_price = 0;

    public $giftcard_value = 0;

    public $giftcard_bgimage = null;

    public $giftcard_image = null;

    public $published = null;

    public $giftcard_desc = null;

    public $customer_amount = 0;

    public $accountgroup_id = 0;

    function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'giftcard', 'giftcard_id', $db);
    }
}
