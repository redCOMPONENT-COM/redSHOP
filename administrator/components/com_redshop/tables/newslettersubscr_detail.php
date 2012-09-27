<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablenewslettersubscr_detail extends JTable
{
    public $subscription_id = null;

    public $user_id = null;

    public $date = null;

    public $newsletter_id = null;

    public $name = null;

    public $email = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'newsletter_subscription', 'subscription_id', $db);
    }
}
