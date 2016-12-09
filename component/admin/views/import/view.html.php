<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewImport extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$jinput = JFactory::getApplication()->input;

		$isvm = $jinput->get('vm');

		if ($isvm || $isvm == 1)
		{
			$this->setLayout('vmimport');

			$model = $this->getModel('import');

			$check_vm = $model->check_vm();

			$this->check_vm = $check_vm;

			$document->setTitle(JText::_('COM_REDSHOP_IMPORT_FROM_VM'));
		}
		else
		{
			$layout = $jinput->getCmd('layout', '');

			if ($layout == 'importlog')
			{
				$this->setLayout($layout);
			}

			$task   = $jinput->getCmd('task', '');
			$result = '';

			if ($task == 'importfile')
			{
				// Load the data to export
				$result = $this->get('Data');
			}

			$this->result = $result;

			$document->setTitle(JText::_('COM_REDSHOP_IMPORT'));

			JToolBarHelper::title(JText::_('COM_REDSHOP_IMPORT_MANAGEMENT'), 'redshop_import48');

			if ($layout == 'importlog')
			{
				JToolBarHelper::back();
			}
			else
			{
				JToolBarHelper::custom('importfile', 'redshop_import_import32.png', JText::_('COM_REDSHOP_IMPORT'), JText::_('COM_REDSHOP_IMPORT'), false, false);
			}
		}

		parent::display($tpl);
	}
}
