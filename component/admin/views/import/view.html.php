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

class importViewimport extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$isvm = JRequest::getVar('vm');

		if ($isvm || $isvm == 1)
		{
			$this->setLayout('vmimport');

			$model = $this->getModel('import');

			$check_vm = $model->check_vm();

			$this->assignRef('check_vm', $check_vm);

			$document->setTitle(JText::_('COM_REDSHOP_IMPORT_FROM_VM'));

		}
		else
		{
			$layout = JRequest::getVar('layout'); //die();
			if ($layout == 'importlog')
			{
				$this->setLayout($layout);
			}
			$task = JRequest::getVar('task');
			if ($task == 'importfile')
			{
				/* Load the data to export */
				$result = $this->get('Data');
			}

			$document->setTitle(JText::_('COM_REDSHOP_IMPORT'));

			JToolBarHelper::title(JText::_('COM_REDSHOP_IMPORT_MANAGEMENT'), 'redshop_import48');
			JToolBarHelper :: custom('importfile', 'redshop_import_import32.png', JText::_('COM_REDSHOP_IMPORT'), JText::_('COM_REDSHOP_IMPORT'), false, false);


			$this->assignRef('result', $result);
		}
		parent::display($tpl);
	}
}