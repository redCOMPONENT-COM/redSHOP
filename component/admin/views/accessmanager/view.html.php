<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class accessmanagerViewaccessmanager extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER'), 'redshop_catalogmanagement48');

		if (ENABLE_BACKENDACCESS)
		{
			parent::display($tpl);
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENABLE_ACCESS_MANAGER_FIRST');
			$app->redirect('index.php?option=com_redshop&view=configuration', $msg);
		}
	}
}
