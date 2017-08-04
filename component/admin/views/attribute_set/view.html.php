<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAttribute_set extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ATTRIBUTE_SET'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET'), 'redshop_attribute_bank48');

		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'attribute_set_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists = array();
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$products = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->products = $products;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
