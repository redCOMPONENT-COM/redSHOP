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

class zip_importViewzip_import extends JView
{
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$layout = JRequest::getVar('layout');

		if ($layout == 'confirmupdate')
		{
			$this->setLayout('confirmupdate');
		}
		else
		{
			$model = $this->getModel('zip_import');
			/* Load the data to export */
			$result = $this->get('Data');
			$this->result = $result;
		}
		parent::display($tpl);
	}
}
