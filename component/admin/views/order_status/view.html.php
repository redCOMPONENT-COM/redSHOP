<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Order Status
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewOrder_Status extends RedshopViewForm
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
		$primaryKey = $this->getPrimaryKey();
		$title      = JText::_('COM_REDSHOP_ORDERSTATUS_MANAGEMENT');

		return !empty($this->item->{$primaryKey}) ? $title . ' <small>[ ' . JText::_(
				'COM_REDSHOP_EDIT'
			) . ' ]</small>' :
			$title . ' <small>[ ' . JText::_('COM_REDSHOP_NEW') . ' ]</small>';
	}
}
