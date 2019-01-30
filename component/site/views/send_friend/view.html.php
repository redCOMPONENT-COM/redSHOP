<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Send Friend View
 *
 * @package     RedShop.Component
 * @subpackage  View
 *
 * @since       1.0
 */
class RedshopViewSend_Friend extends RedshopView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise an Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		JHtml::stylesheet('com_redshop/scrollable-navig.min.css', array(), true);

		parent::display($tpl);
	}
}
