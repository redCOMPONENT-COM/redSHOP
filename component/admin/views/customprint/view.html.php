<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

class customprintViewcustomprint extends JView
{
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_CUSTOM_VIEWS'));
		$layout = JRequest::getVar('layout');

		if ($layout)
		{
			$tpl = $layout;
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_CUSTOM_VIEWS'), 'redshop_statistic48');

		parent::display($tpl);
	}
}
