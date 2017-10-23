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
 * View Template
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.7
 */
class RedshopViewTemplate extends RedshopViewForm
{
	/**
	 * Split fieldset in form into column
	 *
	 * @var   integer
	 * @since 2.0.7
	 */
	public $formFieldsetsColumn = 1;

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_TEMPLATE_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}
