<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablecategory_detail extends JTable
{
    public $category_id = null;

    public $category_name = null;

    public $category_short_description = null;

    public $category_description = null;

    public $category_template = 0;

    public $category_more_template = 0;

    public $category_full_image = null;

    public $category_thumb_image = null;

    public $category_back_full_image = null;

    public $metakey = null;

    public $metadesc = null;

    public $metalanguage_setting = null;

    public $metarobot_info = null;

    public $append_to_global_seo = 'append';

    public $pagetitle = null;

    public $pageheading = null;

    public $sef_url = null;

    public $published = null;

    public $products_per_page = null;

    public $ordering = null;

    public $compare_template_id = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'category', 'category_id', $db);
    }
}
