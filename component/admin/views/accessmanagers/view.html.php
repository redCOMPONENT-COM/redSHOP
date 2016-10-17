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
 * Access managers view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View.Accessmanagers
 * @since       2.0
 */
class RedshopViewAccessmanagers extends RedshopViewAdmin
{
	/**
	 * Display
	 *
	 * @param   string  $tpl  Tpl
	 *
	 * @return  JViewLegacy
	 */
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->views = $this->get('view');

		$this->addToolbar();

		if ($user->authorise('core.manage', 'com_redshop'))
		{
			parent::display($tpl);
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENABLE_ACCESS_MANAGER_FIRST');
			$app->redirect('index.php?option=com_redshop&view=configuration', $msg);
		}
	}

	/**
	 * Method to add toolbar
	 *
	 * @return  void
	 */
	protected function addToolbar ()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGERS'), 'redshop_catalogmanagement48');
	}
}
