<?php
/**
 * @package     redSHOP
 * @subpackage  Core
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Base Model for Redshop Models.
 *
 * @package     redSHOP
 * @subpackage  Core
 */
class RedshopCoreModel extends JModelLegacy
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = '#__redshop_';
}

