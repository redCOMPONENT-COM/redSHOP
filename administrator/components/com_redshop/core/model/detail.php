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
 * Base Model for Detail Models.
 *
 * @package     redSHOP
 * @subpackage  Core
 */
class RedshopCoreModelDetail extends JModelLegacy
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = '#__redshop_';

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $input = JFactory::getApplication()->input;

        $array     = $input->get('cid', array(0), 'array');
        $this->_id = (int)$array[0];
    }
}

