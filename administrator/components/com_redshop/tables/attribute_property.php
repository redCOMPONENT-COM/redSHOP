<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableattribute_property extends JTable
{
    public $property_id = null;

    public $attribute_id = null;

    public $property_name = null;

    public $property_price = null;

    public $oprand = null;

    public $property_image = null;

    public $property_main_image = null;

    public $ordering = null;

    public $property_number = null;

    public $setdefault_selected = 0;

    public $setrequire_selected = 0;

    public $setmulti_selected = 0;

    public $setdisplay_type = null;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_attribute_property', 'property_id', $db);
    }
}
