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
 * View Manufacturer
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewManufacturer extends RedshopViewForm
{
	/**
	 * Form layout. (box, tab)
	 *
	 * @var    string
	 *
	 * @since  2.0.6
	 */
	protected $formLayout = 'tab';

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_MANUFACTURER_MANAGEMENT') . ' <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}
