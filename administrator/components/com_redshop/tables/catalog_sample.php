<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tablecatalog_detail
// old Tablesample_detail
class Tablecatalog_sample extends JTable
{
    public $sample_id = null;

    public $sample_name = null;

    public $published = null;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'catalog_sample', 'sample_id', $db);
    }
}
