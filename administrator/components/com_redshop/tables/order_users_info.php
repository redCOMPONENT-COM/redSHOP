<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

// old Tableorder_user_detail
class Tableorder_users_info extends JTable
{
    public $order_info_id = null;

    public $users_info_id = null;

    public $order_id = null;

    public $user_id = null;

    public $firstname = null;

    public $address_type = null;

    public $lastname = null;

    public $vat_number = null;

    public $tax_exempt = 0;

    public $requesting_tax_exempt = 0;

    public $shopper_group_id = null;

    public $published = null;

    public $is_company = null;

    public $country_code = null;

    public $state_code = null;

    public $zipcode = 0;

    public $phone = 0;

    public $city = 0;

    public $address = 0;

    public $tax_exempt_approved = 0;

    public $approved = 0;

    public $user_email = null;

    public $company_name = null;

    public $thirdparty_email = null;

    public $ean_number = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'order_users_info', 'order_info_id', $db);
    }
}
