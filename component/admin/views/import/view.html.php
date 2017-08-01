<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Import view.
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewImport extends RedshopViewAdmin
{
	protected $checkVirtuemart;

	/**
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected $imports;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   2.0.3
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

			/** @var RedshopModelImport $model */
			$model = $this->getModel();
			$this->imports = $model->getImports();

			$document->setTitle(JText::_('COM_REDSHOP_DATA_IMPORT'));
			JToolBarHelper::title(JText::_('COM_REDSHOP_DATA_IMPORT'));
		}

		parent::display($tpl);
	}
}
