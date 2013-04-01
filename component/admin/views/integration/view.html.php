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

class integrationViewintegration extends JView
{
	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_INTEGRATION'), 'redshop_products48');

		$task = JRequest::getVar('task');

		switch ($task)
		{
			case 'googlebase' :
				$tpl = 'gbase';
				break;
		}

		parent::display($tpl);
	}
}
