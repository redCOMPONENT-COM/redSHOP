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
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		global $context;

		$model = $this->getModel('opsearch');

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$order_function = new order_functions;

		$document->addStyleSheet('components/com_redshop/assets/css/search.css');
		$document->addScript('components/com_redshop/assets/js/search.js');

		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'));
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'), 'redshop_order48');

		$lists['order']     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_item_name');
		$lists['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$filter_user   = $app->getUserStateFromRequest($context . 'filter_user', 'filter_user', 0);
		$filter_status = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', 0);

		$products   = $this->get('Data');
		$total      = $this->get('Total');
		$pagination = $this->get('Pagination');

		$lists['filter_user'] = $model->getuserlist('filter_user', $filter_user, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');
		$lists['filter_status'] = $order_function->getstatuslist('filter_status', $filter_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);

		$this->lists = $lists;
		$this->products = $products;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
