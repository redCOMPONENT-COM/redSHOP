<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewProduct_mini extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT'));

		$uri = JFactory::getURI();

		$filterOrder     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'p.product_id');
		$filterOrderDir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$searchField = $app->getUserStateFromRequest($context . 'search_field', 'search_field', '');
		$keyword      = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
		$category_id  = $app->getUserStateFromRequest($context . 'category_id', 'category_id', '');

		$categories = RedshopHelperCategory::getCategoryListArray();

		$temps          = [new \stdClass];
		$temps[0]->id   = "0";
		$temps[0]->name = JText::_('COM_REDSHOP_SELECT');
		$categories     = @array_merge($temps, $categories);

		$lists['category'] = JHTML::_('select.genericlist', $categories, 'category_id', 'class="inputbox" onchange="document.adminForm2.submit();"      ', 'category_id', 'category_name', $category_id);

		$lists['order']     = $filterOrder;
		$lists['order_Dir'] = $filterOrderDir;
		$total              = $this->get('Total');
		$products           = $this->get('Data');

		$pagination = $this->get('Pagination');

		$this->keyword = $keyword;
		$this->search_field = $searchField;
		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->products = $products;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		JFilterOutput::cleanText($this->request_url);

		parent::display($tpl);
	}
}
