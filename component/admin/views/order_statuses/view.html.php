<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * The order statuses view
 *
 * @package     RedSHOP.Backend
 * @subpackage  States.View
 * @since       2.0.0.6
 */
class RedshopViewOrder_Statuses extends RedshopViewList
{
    /**
     * @var  boolean
     *
     * @since  3.0.2
     */
    public $hasOrdering = true;

    /**
     * Method for get page title.
     *
     * @return  string
     *
     * @since   2.0.7
     */
    public function getTitle()
    {
        return Text::_('COM_REDSHOP_ORDERSTATUS_MANAGEMENT');
    }
}
