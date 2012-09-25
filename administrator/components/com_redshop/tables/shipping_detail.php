<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableshipping_detail extends JTable
{
    public $shipping_id = null;

    public $shipping_name = null;

    public $shipping_class = null;

    public $shipping_method_code = null;

    public $published = null;

    public $shipping_details = null;

    public $params = null;

    public $plugin = null;

    public $ordering = null;

    public function __construct(&$db)
    {
        $this->_table_prefix = '#__extensions';

        parent::__construct($this->_table_prefix, 'extension_id', $db);
    }
}
