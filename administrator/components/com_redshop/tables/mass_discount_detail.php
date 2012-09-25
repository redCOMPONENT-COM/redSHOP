<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablemass_discount_detail extends JTable
{
    public $mass_discount_id = 0;

    public $discount_name = null;

    public $discount_product = null;

    public $category_id = null;

    public $discount_type = null;

    public $discount_amount = null;

    public $discount_startdate = null;

    public $discount_enddate = null;

    public $manufacturer_id = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'mass_discount', 'mass_discount_id', $db);
    }
}
