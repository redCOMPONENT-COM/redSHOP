<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

// old Tablemail_detail
class Tablemail extends JTable
{
    public $mail_id = null;

    public $mail_name = null;

    public $mail_subject = null;

    public $mail_section = null;

    public $mail_order_status = null;

    public $mail_body = null;

    public $mail_bcc = null;

    public $published = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'mail', 'mail_id', $db);
    }
}
