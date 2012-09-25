<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablequotation_attribute_item extends JTable
{
    public $quotation_att_item_id = null;

    public $quotation_item_id = null;

    public $section_id = null;

    public $section = null;

    public $parent_section_id = null;

    public $section_name = null;

    public $section_vat = null;

    public $section_price = null;

    public $section_oprand = null;

    public $is_accessory_att = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'quotation_attribute_item', 'quotation_att_item_id', $db);
    }
}
