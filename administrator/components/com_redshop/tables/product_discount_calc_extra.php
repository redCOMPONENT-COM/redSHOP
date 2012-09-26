<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableproduct_discount_calc_extra extends JTable
{
    public $pdcextra_id = 0;

    public $product_id = 0;

    public $option_name = 0;

    public $oprand = 0;

    public $price = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_discount_calc_extra', 'pdcextra_id', $db);
    }
}

