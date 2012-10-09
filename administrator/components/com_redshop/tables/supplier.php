<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tablesupplier_detail
class Tablesupplier extends JTable
{
    public $supplier_id = null;

    public $supplier_name = null;

    public $supplier_desc = null;

    public $supplier_email = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'supplier', 'supplier_id', $db);
    }
}
