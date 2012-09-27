<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

class Tablesubattribute_property extends JTable
{
    public $subattribute_color_id = null;

    public $subattribute_color_name = null;

    public $subattribute_color_title = null;

    public $subattribute_color_price = null;

    public $oprand = null;

    public $subattribute_color_image = null;

    public $subattribute_id = null;

    public $ordering = null;

    public $subattribute_color_number = null;

    public $setdefault_selected = 0;

    public $subattribute_color_main_image = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_subattribute_color', 'subattribute_color_id', $db);
    }
}
