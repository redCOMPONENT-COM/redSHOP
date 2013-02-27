<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablesample_request extends JTable
{
    public $request_id = null;

    public $colour_id = null;

    public $name = null;

    public $email = null;

    public $registerdate = null;

    public $block = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'sample_request', 'request_id', $db);
    }
}
