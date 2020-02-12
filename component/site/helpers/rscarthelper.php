<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

class rsCarthelper
{
    public $_table_prefix = null;

    public $_db = null;

    public $_session = null;

    public $_order_functions = null;

    public $_extra_field = null;

    public $_producthelper = null;

    public $_shippinghelper = null;

    public $_globalvoucher = 0;

    protected static $instance = null;

    protected $input;

    /**
     * Returns the rsCarthelper object, only creating it
     * if it doesn't already exist.
     *
     * @return  rsCarthelper  The rsCarthelper object
     *
     * @since   1.6
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->_table_prefix    = '#__redshop_';
        $this->_db              = JFactory::getDBO();
        $this->_session         = JFactory::getSession();
        $this->_order_functions = order_functions::getInstance();
        $this->_extra_field     = extra_field::getInstance();
        $this->_shippinghelper  = shipping::getInstance();
        $this->input            = JFactory::getApplication()->input;
    }
}
