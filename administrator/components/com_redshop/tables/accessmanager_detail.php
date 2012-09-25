<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableaccessmanager_detail extends JTable
{
    public $id = null;

    public $section_name = null;

    public $gid = 0;

    public $view = null;

    public $add = null;

    public $edit = null;

    public $delete = null;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'accessmanager', 'id', $db);
    }
}
