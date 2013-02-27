<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablewishlist extends JTable
{
    public $wishlist_id = 0;

    public $wishlist_name = null;

    public $user_id = null;

    public $comment = null;

    public $cdate = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'wishlist', 'wishlist_id', $db);
    }
}
