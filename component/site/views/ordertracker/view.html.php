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

class ordertrackerViewordertracker extends JView
{
	public function display($tpl = null)
	{
		global $mainframe;

		$params = & $mainframe->getParams('com_redshop');

		// Request variables
		$option = JRequest::getVar('option');
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}
