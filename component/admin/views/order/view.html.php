<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewOrder extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$order_function = order_functions::getInstance();

		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$layout = JRequest::getVar('layout');

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

			RedshopToolbarHelper::custom(
				'allStatusExceptPacsoft',
				'save.png',
				'print_f2.png',
				'COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL',
				true
			);

			if (POSTDK_INTEGRATION)
			{
				RedshopToolbarHelper::custom(
					'allstatus',
					'save.png',
					'save_f2.png',
					'COM_REDSHOP_CHANGE_STATUS_TO_ALL_WITH_PACSOFT_LBL',
					true
				);
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
		}

		$state = $this->get('State');
		$this->filter          = $state->get('filter');
		$filter_by             = $state->get('filter_by');
		$filter_status         = $state->get('filter_status');
		$filter_payment_status = $state->get('filter_payment_status');

		$lists['order']     = $state->get('list.ordering', 'o.order_id');
		$lists['order_Dir'] = $state->get('list.direction', 'desc');

		$orders     = $this->get('Data');
		$pagination = $this->get('Pagination');

		$lists['filter_by'] = $order_function->getFilterbyList('filter_by', $filter_by,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"');

		$lists['filter_status'] = $order_function->getstatuslist('filter_status', $filter_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);
		$lists['filter_payment_status'] = $order_function->getpaymentstatuslist('filter_payment_status', $filter_payment_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();" '
		);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->orders = $orders;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
