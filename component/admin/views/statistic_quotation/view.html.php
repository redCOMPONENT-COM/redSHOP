<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Statistic Quotation view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2
 */
class RedshopViewStatistic_Quotation extends RedshopViewAdmin
{
	/**
	 * Display the Statistic Customer view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_STATISTIC_QUOTATION'));
		/** @scrutinizer ignore-deprecated */JHtml::stylesheet('com_redshop/daterangepicker.min.css', array(), true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/moment.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/daterangepicker.min.js', false, true);

		$this->quotations      = $this->get('Quotations');
		$this->filterStartDate = $app->input->getString('filter_start_date', '');
		$this->filterEndDate   = $app->input->getString('filter_end_date', '');
		$this->filterDateLabel = $app->input->getString('filter_date_label', '');

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$title = JText::_('COM_REDSHOP_STATISTIC_QUOTATION');
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('COM_REDSHOP_STATISTIC_QUOTATION') . " :: " . $title, 'statistic redshop_statistic48');

		RedshopToolbarHelper::custom(
			'exportQuotation',
			'save.png',
			'save_f2.png',
			'COM_REDSHOP_EXPORT_DATA_LBL',
			false
		);
		RedshopToolbarHelper::link(
			'index.php?tmpl=component&option=com_redshop&view=statistic_quotation',
			'print',
			'COM_REDSHOP_PRINT',
			'_blank'
		);
	}
}
