<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablequotation_accessory_item extends JTable
{
    public $quotation_item_acc_id = null;

    public $quotation_item_id = null;

    public $accessory_id = null;

    public $accessory_item_sku = null;

    public $accessory_item_name = null;

    public $accessory_price = null;

    public $accessory_vat = null;

    public $accessory_quantity = null;

    public $accessory_item_price = null;

    public $accessory_final_price = null;

    public $accessory_attribute = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'quotation_accessory_item', 'quotation_item_acc_id', $db);
    }
}
