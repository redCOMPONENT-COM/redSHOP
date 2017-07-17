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
 * Troubleshoots list view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewTroubleshoots extends RedshopViewAdmin
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->list = $this->get('Data');

		$this->displayToolbar();

		parent::display($tpl);
	}

	/**
	 * Render toolbar
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function displayToolbar()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_TITLE_TROUBLESHOOTS'));
	}
}