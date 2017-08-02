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
 * View Tax group
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */
class RedshopViewTax_Group extends RedshopViewForm
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
		return JText::_('COM_REDSHOP_TAX_GROUP_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}
