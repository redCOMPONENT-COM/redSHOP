<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class ordertrackerModelordertracker extends JModelLegacy
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = null;

    public $_template = null;

    function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';
    }
}

