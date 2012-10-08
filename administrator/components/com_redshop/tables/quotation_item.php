<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tablequotation_item_detail
class Tablequotation_item extends JTable
{
    public $quotation_item_id = null;

    public $quotation_id = null;

    public $product_id = null;

    public $product_name = null;

    public $product_price = null;

    public $actualitem_price = null;

    public $product_excl_price = null;

    public $product_final_price = null;

    public $product_quantity = null;

    public $product_attribute = null;

    public $product_accessory = null;

    public $mycart_accessory = null;

    public $product_wrapperid = null;

    public $wrapper_price = null;

    public $is_giftcard = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';
        parent::__construct($this->_table_prefix . 'quotation_item', 'quotation_item_id', $db);
    }
}
