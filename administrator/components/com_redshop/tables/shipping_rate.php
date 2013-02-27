<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tableshipping_rate_detail
class Tableshipping_rate extends JTable
{
    public $shipping_rate_id = null;

    public $shipping_rate_name = null;

    public $shipping_class = null;

    public $shipping_rate_country = null;

    public $shipping_rate_volume_start = null;

    public $shipping_rate_volume_end = null;

    public $shipping_rate_weight_start = null;

    public $shipping_rate_weight_end = null;

    public $shipping_rate_ordertotal_start = null;

    public $shipping_rate_ordertotal_end = null;

    public $shipping_rate_zip_start = null;

    public $shipping_rate_zip_end = null;

    public $shipping_rate_value = null;

    public $shipping_rate_priority = null;

    public $shipping_rate_package_fee = null;

    public $shipping_rate_on_product = null;

    public $shipping_rate_on_category = null;

    public $company_only = null;

    public $apply_vat = 0;

    public $shipping_location_info = null;

    public $shipping_rate_length_start = 0;

    public $shipping_rate_length_end = 0;

    public $shipping_rate_width_start = 0;

    public $shipping_rate_width_end = 0;

    public $shipping_rate_height_start = 0;

    public $shipping_rate_height_end = 0;

    public $shipping_tax_group_id = null;

    public $shipping_rate_state = null;

    public $shipping_rate_on_shopper_group = null;

    public $consignor_carrier_code = null;

    public $economic_displaynumber = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'shipping_rate', 'shipping_rate_id', $db);
    }
}
