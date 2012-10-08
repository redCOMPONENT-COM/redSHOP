<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tabletextlibrary_detail
class Tabletextlibrary extends JTable
{
    public $textlibrary_id = null;

    public $text_name = null;

    public $text_desc = null;

    public $text_field = null;

    public $section = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'textlibrary', 'textlibrary_id', $db);
    }
}
