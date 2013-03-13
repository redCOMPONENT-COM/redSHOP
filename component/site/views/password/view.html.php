<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class passwordViewpassword extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$layout = JRequest::getVar('layout');
		$uid    = JRequest::getInt('uid', 0);
		$params = & $mainframe->getParams('com_redshop');

		if ($uid != 0)
		{
			$this->setLayout('setpassword');
		}
		else
		{
			if ($layout == 'token')
			{
				$this->setLayout('token');
			}
			else
			{
				$this->setLayout('default');
			}
		}
		parent::display($tpl);
	}
}