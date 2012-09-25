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
    var $_id = null;

    var $_data = null;

    var $_table_prefix = null;

    var $_template = null;

    function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';
    }
}

