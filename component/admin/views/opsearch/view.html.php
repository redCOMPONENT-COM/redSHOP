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

class opsearchViewopsearch extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$model = $this->getModel('opsearch');
		$document = JFactory::getDocument();
		$order_function = new order_functions;

		$option = JRequest::getVar('option');
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/com_redshop/assets/css/search.css');
		$document->addScript('components/com_redshop/assets/js/search.js');

		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'));
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'), 'redshop_order48');

		$uri = JFactory::getURI();

		$lists['order'] = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_item_name');
		$lists['order_Dir'] = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$filter_user = $mainframe->getUserStateFromRequest($context . 'filter_user', 'filter_user', 0);
		$filter_status = $mainframe->getUserStateFromRequest($context . 'filter_status', 'filter_status', 0);

		$products = & $this->get('Data');
		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');

		$lists['filter_user'] = $model->getuserlist('filter_user', $filter_user, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');
		$lists['filter_status'] = $order_function->getstatuslist('filter_status', $filter_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);

		$this->assignRef('lists', $lists);
		$this->assignRef('products', $products);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
