<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Import view.
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewImport extends RedshopViewAdmin
{
	protected $checkVirtuemart;

	protected $result;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();

		// Import from Virtuemart data.
		if ($app->input->getInt('vm', 0) == 1)
		{
			$this->setLayout('vmimport');

			/** @var RedshopModelImport $model */
			$model = $this->getModel('import');

			$this->checkVirtuemart = $model->check_vm();

			$document->setTitle(JText::_('COM_REDSHOP_IMPORT_FROM_VM'));
			JToolBarHelper::title(JText::_('COM_REDSHOP_IMPORT_FROM_VM'), 'redshop_import48');
		}
		else
		{
			$layout = $app->input->getCmd('layout');

			if ($layout == 'importlog')
			{
				$this->setLayout($layout);
			}

			$task   = $app->input->getCmd('task');
			$result = '';

			if ($task == 'importfile')
			{
				// Load the data to export
				$result = $this->get('Data');
			}

			$this->result = $result;

			$document->setTitle(JText::_('COM_REDSHOP_DATA_IMPORT'));

			JToolBarHelper::title(JText::_('COM_REDSHOP_DATA_IMPORT'), 'redshop_import48');

			if ($layout == 'importlog')
			{
				JToolBarHelper::back();
			}
			else
			{
				JToolBarHelper::custom(
					'importfile',
					'redshop_import_import32.png',
					JText::_('COM_REDSHOP_IMPORT'),
					JText::_('COM_REDSHOP_IMPORT'),
					false
				);
			}
		}

		parent::display($tpl);
	}
}
