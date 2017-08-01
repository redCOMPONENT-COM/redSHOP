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
 * Order List View
 *
 * @package     RedShop.Backend
 * @subpackage  Views
 * @since       1.0
 */
class RedshopViewOrder extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $filter;

	/**
	 * @var  array
	 */
	public $lists;

	/**
	 * @var  JUser
	 */
	public $user;

	/**
	 * @var  array
	 */
	public $orders;

	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  string
	 */
	public $request_url;

	/**
	 * Display method
	 *
	 * @param   string  $tpl  The template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$order_function = order_functions::getInstance();

		JFactory::getDocument()->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$layout = JFactory::getApplication()->input->getCmd('layout');

		if ($layout == 'previewlog')
		{
			$this->setLayout($layout);
		}
		elseif ($layout == 'labellisting')
		{
			RedshopToolbarHelper::title(JText::_('COM_REDSHOP_DOWNLOAD_LABEL'), 'redshop_order48');
			$this->setLayout('labellisting');
			RedshopToolbarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}
		elseif ($layout == 'batch')
		{
			RedshopToolbarHelper::title(JText::_('COM_REDSHOP_ORDER_MANAGEMENT'), 'stack redshop_order48');
		}
		else
		{
			RedshopToolbarHelper::title(JText::_('COM_REDSHOP_ORDER_MANAGEMENT'), 'stack redshop_order48');
			RedshopToolbarHelper::addNew();

			RedshopToolbarHelper::custom(
				'multiprint_order',
				'print_f2.png',
				'print_f2.png',
				'COM_REDSHOP_MULTI_PRINT_ORDER_LBL',
				true
			);

			RedshopToolbarHelper::modal('massOrderStatusChange', 'fa fa-gears', 'COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL');

			if (Redshop::getConfig()->get('POSTDK_INTEGRATION'))
			{
				RedshopToolbarHelper::modal('massOrderStatusPacsoft', 'fa fa-gears', 'COM_REDSHOP_CHANGE_STATUS_TO_ALL_WITH_PACSOFT_LBL');
			}

			$group = RedshopToolbarHelper::createGroup('export', 'COM_REDSHOP_EXPORT_DATA_LBL');

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_DATA_LBL',
				'export_data',
				true
			);

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_FULL_DATA_LBL',
				'export_fullorder_data',
				false
			);

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_GLS_LBL',
				'gls_export',
				true
			);

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_GLS_BUSINESS_LBL',
				'business_gls_export',
				true
			);

			$group->renderGroup();

			RedshopToolbarHelper::deleteList();

			// Check PDF plugin
			if (!RedshopHelperPdf::isAvailablePdfPlugins())
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_WARNING_MISSING_PDF_PLUGIN'), 'warning');
			}
		}

		$state                 = $this->get('State');
		$this->filter          = $state->get('filter');
		$filter_by             = $state->get('filter_by');
		$filter_status         = $state->get('filter_status');
		$filter_payment_status = $state->get('filter_payment_status');

		$lists['order']        = $state->get('list.ordering', 'o.order_id');
		$lists['order_Dir']    = $state->get('list.direction', 'desc');

		$lists['filter_by'] = $order_function->getFilterbyList('filter_by', $filter_by,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);

		$lists['filter_status'] = $order_function->getstatuslist('filter_status', $filter_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);
		$lists['filter_payment_status'] = $order_function->getpaymentstatuslist('filter_payment_status', $filter_payment_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();" '
		);

		$this->user        = JFactory::getUser();
		$this->lists       = $lists;
		$this->orders      = $this->get('Data');
		$this->pagination  = $this->get('Pagination');
		$this->request_url = JUri::getInstance()->toString();

		parent::display($tpl);
	}
}
