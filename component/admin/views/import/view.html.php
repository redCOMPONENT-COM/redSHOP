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
	 * @param   string $tpl name of template
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
			$model         = $this->getModel();
			$this->imports = $model->getImports();

			$document->setTitle(JText::_('COM_REDSHOP_DATA_IMPORT'));
			JToolBarHelper::title(JText::_('COM_REDSHOP_DATA_IMPORT'));
		}

		$this->allowFileTypes      = explode(
			',',
			Redshop::getConfig()->get('IMPORT_FILE_MIME', 'text/csv,application/vnd.ms-excel')
		);
		$this->allowMaxFileSize    = (int) Redshop::getConfig()->get('IMPORT_MAX_FILE_SIZE', 20000000);
		$this->allowMinFileSize    = (int) Redshop::getConfig()->get('IMPORT_MIN_FILE_SIZE', 1);
		$this->allowFileExtensions = explode(',',
			Redshop::getConfig()->get('IMPORT_FILE_EXTENSION', '.csv')
		);

		$this->encodings = array();

		// Defines encoding used in import
		$characterSets = array(
			'ISO-8859-1'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88591',
			'ISO-8859-5'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88595',
			'ISO-8859-15' => 'COM_REDSHOP_IMPORT_CHARS_ISO885915',
			'UTF-8'       => 'COM_REDSHOP_IMPORT_CHARS_UTF8',
			'cp866'       => 'COM_REDSHOP_IMPORT_CHARS_CP866',
			'cp1251'      => 'COM_REDSHOP_IMPORT_CHARS_CP1251',
			'cp1252'      => 'COM_REDSHOP_IMPORT_CHARS_CP1252',
			'KOI8-R'      => 'COM_REDSHOP_IMPORT_CHARS_KOI8R',
			'BIG5'        => 'COM_REDSHOP_IMPORT_CHARS_BIG5',
			'GB2312'      => 'COM_REDSHOP_IMPORT_CHARS_GB2312',
			'BIG5-HKSCS'  => 'COM_REDSHOP_IMPORT_CHARS_BIG5HKSCS',
			'Shift_JIS'   => 'COM_REDSHOP_IMPORT_CHARS_SHIFTJIS',
			'EUC-JP'      => 'COM_REDSHOP_IMPORT_CHARS_EUCJP',
			'MacRoman'    => 'COM_REDSHOP_IMPORT_CHARS_MACROMAN'
		);

		// Creating JOption for JSelect box.
		foreach ($characterSets as $char => $name)
		{
			$title             = sprintf(JText::_($name), $char);
			$this->encodings[] = JHtml::_('select.option', $char, $title);
		}

		$this->loadLanguages();

		parent::display($tpl);
	}

	/**
	 *
	 * @since  2.0.7
	 */
	protected function loadLanguages()
	{
		JText::script('COM_REDSHOP_IMPORT_SELECT_FILE');
		JText::script('COM_REDSHOP_IMPORT_FAIL');
		JText::script('COM_REDSHOP_IMPORT_SUCCESS');
		JText::script('COM_REDSHOP_IMPORT_ERROR_FILE_TYPE');
		JText::script('COM_REDSHOP_IMPORT_ERROR_FILE_SIZE');
		JText::script('COM_REDSHOP_IMPORT_ERROR_FILE_MIN_SIZE');
		JText::script('COM_REDSHOP_IMPORT_ERROR_FILE_MAX_SIZE');
		JText::script('COM_REDSHOP_IMPORT_ERROR_UPLOAD_FILE');
		JText::script('COM_REDSHOP_IMPORT_MESSAGE_UPLOAD_FILE_SUCCESS');
		JText::script('COM_REDSHOP_IMPORT_IMPORTING');
		JText::script('COM_REDSHOP_IMPORT_FILE_UPLOADING');
		JText::script('COM_REDSHOP_IMPORT_FILE_UPLOAD_COMPLETED');
		JText::script('COM_REDSHOP_IMPORT_INIT_IMPORT');
		JText::script('COM_REDSHOP_IMPORT_IMPORTED_FILE');
		JText::script('COM_REDSHOP_IMPORT_FOLDER');
		JText::script('COM_REDSHOP_IMPORT_TOTAL_ROWS');
		JText::script('COM_REDSHOP_IMPORT_TOTAL_ROWS_PERCENT_FILE');
		JText::script('COM_REDSHOP_IMPORT_TOTAL_FILES');
		JText::script('COM_REDSHOP_IMPORT_FILE_UPLOADING');
		JText::script('COM_REDSHOP_IMPORT_LOADED_CONFIGURATION');
		JText::script('COM_REDSHOP_IMPORT_AJAX_FAILED');
	}
}
