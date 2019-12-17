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
 * The Shipping Box view
 *
 * @package     RedSHOP.Backend
 * @subpackage  Shipping_Box.View
 * @since       2.0.0.4
 */
class RedshopViewShipping_Box extends RedshopViewForm
{
	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_SHIPPING_BOX') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}
