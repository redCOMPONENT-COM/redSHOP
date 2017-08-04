<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewProduct_mini extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$redTemplate = Redtemplate::getInstance();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT'));

		$uri = JFactory::getURI();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'product_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$limitstart       = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit            = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$search_field = $app->getUserStateFromRequest($context . 'search_field', 'search_field', '');
		$keyword      = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
		$category_id  = $app->getUserStateFromRequest($context . 'category_id', 'category_id', '');

		$product_category = new product_category;
		$categories       = $product_category->getCategoryListArray();

		$temps          = array();
		$temps[0]->id   = "0";
		$temps[0]->name = JText::_('COM_REDSHOP_SELECT');
		$categories     = @array_merge($temps, $categories);

		$lists['category'] = JHTML::_('select.genericlist', $categories, 'category_id', 'class="inputbox" onchange="document.adminForm2.submit();"      ', 'category_id', 'category_name', $category_id);

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$total              = $this->get('Total');
		$products           = $this->get('Data');

		$pagination = $this->get('Pagination');

		$this->keyword = $keyword;
		$this->search_field = $search_field;
		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->products = $products;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		JFilterOutput::cleanText($this->request_url);
		parent::display($tpl);
	}
}
