<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableaccessory_detail extends JTable
{
    public $accessory_id = null;

    public $product_id = null;

    public $child_product_id = null;

    public $accessory_price = null;

    public $oprand = null;

    public $ordering = null;

    public $category_id = null;

    public $setdefault_selected = null;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_accessory', 'accessory_id', $db);
    }
}
