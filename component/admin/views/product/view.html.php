<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewProduct extends RedshopView
{
	/**
	 * The pagination object.
	 *
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	public function display($tpl = null)
	{
		global $context;

		$context = 'product_id';

		$GLOBALS['productlist'] = array();
		$redTemplate        = new Redtemplate;
		$extra_field        = new extra_field;
		$adminproducthelper = new adminproducthelper;

		$list_in_products = $extra_field->list_all_field_in_product();

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();

		$layout = JRequest::getVar('layout');
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT'), 'stack redshop_products48');

		if ($layout != 'importproduct' && $layout != 'importattribute' && $layout != 'listing' && $layout != 'ins_product')
		{
			JToolBarHelper::custom('assignCategory', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_ASSIGN_CATEGORY'), true);
			JToolBarHelper::custom('removeCategory', 'delete.png', 'delete_f2.png', JText::_('COM_REDSHOP_REMOVE_CATEGORY'), true);
			JToolbarHelper::addNew();
			JToolbarHelper::EditList();
			JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
			JToolBarHelper::deleteList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}

		if ($layout == 'listing')
		{
			JToolBarHelper::back();
		}

		$state = $this->get('State');
		$category_id = $state->get('category_id');

		if ($category_id)
		{
			$filter_order = $state->get('list.ordering', 'x.ordering');
		}
		else
		{
			$filter_order = $state->get('list.ordering', 'p.product_id');
		}

		$filter_order_Dir = $state->get('list.direction');

		$search_field = $state->get('search_field');
		$keyword      = $state->get('keyword');

		$categories = $this->get('CategoryList');
		$categories1 = array();

		foreach ($categories as $key => $value)
		{
			$categories1[$key] = new stdClass;
			$categories1[$key]->id = $categories[$key]->id;
			$categories1[$key]->parent_id = $categories[$key]->parent_id;
			$categories1[$key]->title = $categories[$key]->title;
			$treename = str_replace("&#160;&#160;&#160;&#160;&#160;&#160;", " ", $categories[$key]->treename);
			$treename = str_replace("<sup>", " ", $treename);
			$treename = str_replace("</sup>&#160;", " ", $treename);
			$categories1[$key]->treename = $treename;
			$categories1[$key]->children = $categories[$key]->children;
		}

		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->id = "0";
		$temps[0]->treename = JText::_('COM_REDSHOP_SELECT');
		$categories1 = @array_merge($temps, $categories1);
		$lists['category'] = JHTML::_('select.genericlist', $categories1, 'category_id',
			'class="inputbox" onchange="document.adminForm.submit();" ', 'id', 'treename', $category_id
		);

		$product_sort = $adminproducthelper->getProductrBySortedList();
		$lists['product_sort'] = JHTML::_('select.genericlist', $product_sort, 'product_sort',
			'class="inputbox"  onchange="document.adminForm.submit();" ', 'value', 'text', $state->get('product_sort')
		);

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$products = $this->get('Data');

		$pagination = $this->get('Pagination');

		/*
	     * assign template
	     */
		$templates = $redTemplate->getTemplate('product');
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->template_id = "0";
		$temps[0]->template_name = JText::_('COM_REDSHOP_ASSIGN_TEMPLATE');
		$templates = @array_merge($temps, $templates);

		$lists['product_template'] = JHTML::_('select.genericlist', $templates, 'product_template',
			'class="inputbox" size="1"  onchange="return AssignTemplate()" ', 'template_id', 'template_name', 0
		);

		$this->list_in_products = $list_in_products;
		$this->keyword = $keyword;
		$this->search_field = $search_field;
		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->products = $products;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
