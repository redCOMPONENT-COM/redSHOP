<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablecontainer_detail extends JTable
{
    public $container_id = null;

    public $container_name = null;

    public $creation_date = null;

    public $container_desc = null;

    public $min_del_time = null;

    public $max_del_time = null;

    public $container_volume = null;

    public $stockroom_id = null;

    public $manufacture_id = null;

    public $supplier_id = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'container', 'container_id', $db);
    }
}
