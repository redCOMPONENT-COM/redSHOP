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
 * Statistic customer view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2
 */
class RedshopViewStatistic_Customer extends RedshopViewAdmin
{
	protected $state = array();

	/**
	 * Display the Statistic Customer view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_STATISTIC_CUSTOMER'));
		$document->addStyleSheet('components/com_redshop/assets/css/daterangepicker.css');
		$document->addScript('components/com_redshop/assets/js/moment.min.js');
		$document->addScript('components/com_redshop/assets/js/daterangepicker.js');

		/** @var RedshopModelStatistic_Customer $model */
		$model = $this->getModel();

		$this->state     = $this->get('State');
		$this->customers = $model->getItems();

		$this->filterStartDate = $this->state->get('filter.start_date');
		$this->filterEndDate   = $this->state->get('filter.end_date');

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
		$title           = JText::_('COM_REDSHOP_STATISTIC_CUSTOMER');
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('COM_REDSHOP_STATISTIC_CUSTOMER') . " :: " . $title, 'statistic redshop_statistic48');

		RedshopToolbarHelper::custom(
			'exportCustomer',
			'save.png',
			'save_f2.png',
			'COM_REDSHOP_EXPORT_DATA_LBL',
			false
		);
		RedshopToolbarHelper::link(
			'index.php?tmpl=component&option=com_redshop&view=statistic_customer',
			'print',
			'COM_REDSHOP_PRINT',
			'_blank'
		);
	}
}
