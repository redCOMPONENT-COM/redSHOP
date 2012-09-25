<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

class Tablewrapper_detail extends JTable
{
    public $wrapper_id = 0;

    public $product_id = null;

    public $category_id = null;

    public $wrapper_price = null;

    public $wrapper_name = null;

    public $wrapper_image = null;

    public $wrapper_use_to_all = 0;

    public $published = 1;

    public $createdate = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';
        parent::__construct($this->_table_prefix . 'wrapper', 'wrapper_id', $db);
    }
}
