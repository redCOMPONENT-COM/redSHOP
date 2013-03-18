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

class customprintViewcustomprint extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_CUSTOM_VIEWS'));
		$layout = JRequest::getVar('layout');

		if ($layout)
		{
			$tpl = $layout;
		}

		$customviews = & $this->get('Data');
		JToolBarHelper::title(JText::_('COM_REDSHOP_CUSTOM_VIEWS'), 'redshop_statistic48');

		$this->assignRef('customviews', $customviews);
		parent::display($tpl);
	}
}
