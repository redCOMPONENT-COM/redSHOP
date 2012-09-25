<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

class Tablerating_detail extends JTable
{
    public $rating_id = 0;

    public $product_id = 0;

    public $title = null;

    public $comment = null;

    public $userid = 0;

    public $time = 0;

    public $user_rating = 0;

    public $favoured = 0;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_rating', 'rating_id', $db);
    }

    public function bind($array, $ignore = '')
    {
        if (key_exists('params', $array) && is_array($array['params']))
        {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = $registry->toString();
        }

        return parent::bind($array, $ignore);
    }
}
