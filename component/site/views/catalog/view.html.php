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


class catalogViewcatalog extends JView
{

	public function display($tpl = null)
	{
		global $mainframe;

		$params = & $mainframe->getParams('com_redshop');
		$layout = JRequest::getVar('layout');
		if ($layout == "sample")
		{
			$this->setLayout('sample');
		}
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}
