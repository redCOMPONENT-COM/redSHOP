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

class orderVieworder extends JView
{
	public function display($tpl = null)
	{
		$context = 'order_id';

		require_once JPATH_COMPONENT . '/helpers/order.php';

		$order_function = new order_functions;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$model = $this->getModel('order');
		$layout = JRequest::getVar('layout');

		if ($layout == 'previewlog')
		{
			$this->setLayout($layout);
		}
		elseif ($layout == 'labellisting')
		{
			JToolBarHelper::title(JText::_('COM_REDSHOP_DOWNLOAD_LABEL'), 'redshop_order48');
			$this->setLayout('labellisting');
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}
		else
		{
			JToolBarHelper::custom('multiprint_order', 'print_f2.png', 'print_f2.png', JText::_('COM_REDSHOP_MULTI_PRINT_ORDER_LBL'), true);
			JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER_MANAGEMENT'), 'redshop_order48');
			JToolBarHelper::addNewX();
			JToolBarHelper::custom('allstatus', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL'), true);
			JToolBarHelper::custom('export_data', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_EXPORT_DATA_LBL'), false);
			JToolBarHelper::custom('export_fullorder_data', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_EXPORT_FULL_DATA_LBL'), false);
			JToolBarHelper::custom('gls_export', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_EXPORT_GLS_LBL'), false);
			JToolBarHelper::custom('business_gls_export', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_EXPORT_GLS_BUSINESS_LBL'), false);
			JToolBarHelper::deleteList();
		}

		$filter_order          = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', ' o.order_id ');
		$filter_order_Dir      = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');
		$filter_by             = $app->getUserStateFromRequest($context . 'filter_by', 'filter_by', '', '');
		$filter_status         = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', '', 'string');
		$filter_payment_status = $app->getUserStateFromRequest($context . 'filter_payment_status', 'filter_payment_status', '', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$orders     = $this->get('Data');
		$total      = $this->get('Total');
		$pagination = $this->get('Pagination');

		$lists['filter_by'] = $order_function->getFilterbyList('filter_by', $filter_by, 'class="inputbox" size="1"');

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
