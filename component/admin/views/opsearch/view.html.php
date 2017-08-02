<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewOpsearch extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public $state;

	public function display($tpl = null)
	{
		$model = $this->getModel('opsearch');

		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$order_function = order_functions::getInstance();

		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'));
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'), 'redshop_order48');

		$this->state = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'order_item_name');
		$lists['order_Dir'] = $this->state->get('list.direction', '');

		$filter_user   = $this->state->get('filter_user', 0);
		$filter_status = $this->state->get('filter_status', 0);

		$products   = $this->get('Data');
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
