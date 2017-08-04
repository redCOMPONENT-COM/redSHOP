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
 * Statistic Order view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2
 */
class RedshopViewStatistic_Order extends RedshopViewAdmin
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
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_STATISTIC_ORDER'));

		$model = $this->getModel();

		$this->orders     = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->state      = $model->getState();
		$this->filterForm = $model->getForm();

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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('COM_REDSHOP_STATISTIC_ORDER'), 'statistic redshop_statistic48');
		RedshopToolbarHelper::link(
			'index.php?tmpl=component&option=com_redshop&task=statistic_order.exportOrder&date_range=' . $this->state->get('filter.date_range'),
			'save.png',
			'COM_REDSHOP_EXPORT_DATA_LBL',
			'_blank'
		);
		RedshopToolbarHelper::link(
			'index.php?tmpl=component&option=com_redshop&view=statistic_order',
			'print',
			'COM_REDSHOP_PRINT',
			'_blank'
		);
	}
}
