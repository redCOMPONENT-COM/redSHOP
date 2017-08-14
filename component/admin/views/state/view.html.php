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
 * The state view
 *
 * @package     RedSHOP.Backend
 * @subpackage  State.View
 * @since       2.0.0.4
 */
class RedshopViewState extends RedshopViewForm
{
	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_STATE_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}
