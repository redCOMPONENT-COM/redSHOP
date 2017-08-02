<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_ORDERSTATUS_MANAGEMENT');
	}
}
