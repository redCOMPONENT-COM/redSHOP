<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Troubleshoots list view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1
 */
class RedshopViewTroubleshoots extends RedshopViewAdmin
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$this->list = $this->get('Data');

		parent::display($tpl);
	}

	/**
	 * Render toolbar
	 *
	 * @return void
	 *
	 * @since  2.1
	 */
	protected function displayToolbar ()
	{
		JToolBarHelper::title('COM_REDSHOP_TITLE_TROUBLESHOOTS');
	}
}
