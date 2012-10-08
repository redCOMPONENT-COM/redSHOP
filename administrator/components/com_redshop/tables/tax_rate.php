<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tabletax_detail
class Tabletax_rate extends JTable
{
    public $tax_rate_id = null;

    public $tax_state = null;

    public $tax_country = null;

    public $mdate = null;

    public $tax_rate = null;

    public $tax_group_id = null;

    public $is_eu_country = 0;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'tax_rate', 'tax_rate_id', $db);
    }
}
