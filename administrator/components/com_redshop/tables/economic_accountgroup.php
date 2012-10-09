<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tableaccountgroup_detail
class Tableeconomic_accountgroup extends JTable
{
    public $accountgroup_id = null;

    public $accountgroup_name = null;

    public $economic_vat_account = null;

    public $economic_nonvat_account = null;

    public $economic_discount_vat_account = null;

    public $economic_discount_nonvat_account = null;

    public $economic_shipping_vat_account = null;

    public $economic_shipping_nonvat_account = null;

    public $economic_discount_product_number = null;

    public $published = 1;

    public function __construct(&$db)
    {
        parent::__construct('#__redshop_economic_accountgroup', 'accountgroup_id', $db);
    }
}
