<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablemanufacturer_detail extends JTable
{
    public $manufacturer_id = null;

    public $manufacturer_name = null;

    public $manufacturer_desc = null;

    public $manufacturer_email = null;

    public $manufacturer_url = null;

    public $product_per_page = 0;

    public $template_id = null;

    public $metakey = null;

    public $metadesc = null;

    public $metalanguage_setting = null;

    public $metarobot_info = null;

    public $pagetitle = null;

    public $pageheading = null;

    public $sef_url = null;

    public $published = null;

    public $ordering = null;

    public $excluding_category_list = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';
        parent::__construct($this->_table_prefix . 'manufacturer', 'manufacturer_id', $db);
    }
}

