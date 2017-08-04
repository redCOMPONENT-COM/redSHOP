<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCurrency extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$context = 'currency_id';

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$uri      = JFactory::getURI();

		jimport('joomla.html.pagination');

		$document->setTitle(JText::_('COM_REDSHOP_CURRENCY'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_CURRENCY_MANAGEMENT'), 'redshop_currencies_48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolbarHelper::deleteList();

		$state		    = $this->get('State');
		$filter_order       = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'currency_id');
		$filter_order_Dir   = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$fields             = $this->get('Data');
		$pagination         = $this->get('Pagination');

		$this->user         = JFactory::getUser();
		$this->pagination   = $pagination;
		$this->fields       = $fields;
		$this->lists        = $lists;
		$this->request_url  = $uri->toString();
		$this->filter       = $state->get('filter');

		parent::display($tpl);
	}
}
