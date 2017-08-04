<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewStockimage extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_STOCKIMAGE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKIMAGE_MANAGEMENT'), 'redshop_stockroom48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();

		$state = $this->get('State');
		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'stock_amount_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists ['order']     = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;
		$this->filter        = $state->get('filter');

		$data       = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->data = $data;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
