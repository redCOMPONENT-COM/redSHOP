<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tableattribute_set_detail
class Tableattribute_set extends JTable
{
    public $attribute_set_id = null;

    public $attribute_set_name = 0;

    public $published = null;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'attribute_set', 'attribute_set_id', $db);
    }
}
