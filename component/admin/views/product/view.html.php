<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Helper\Categories;

defined('_JEXEC') or die;


class RedshopViewProduct extends RedshopViewAdmin
{
	/**
	 * @param   string $tpl Layout
	 *
	 * @return  mixed|void
	 *
	 * @since   2.1.0
	 */
	public function display($tpl = null)
	{
		$this->addToolbar();

		$this->state = $this->get('State');
		$categoryId  = $this->state->get('category_id');

		$this->list_in_products       = RedshopHelperExtrafields::listAllFieldInProduct();
		$this->keyword                = $this->state->get('keyword');
		$this->search_field           = $this->state->get('search_field');
		$this->order                  = ($categoryId) ? $this->state->get('list.ordering', 'x.ordering') : $this->state->get('list.ordering', 'p.product_id');
		$this->orderDir               = $this->state->get('list.direction');
		$this->product_template       = $this->getProductTemplateHtml();
		$this->products               = $this->get('Items');
		$this->filterCategoriesHtml   = $this->getCategoriesHtml($categoryId);
		$this->filterProductsSortHtml = JHTML::_('select.genericlist', RedshopHelperProduct::getProductsSortByList(), 'product_sort',
			'class="inputbox"  onchange="document.adminForm.submit();" ', 'value', 'text', $this->state->get('product_sort')
		);
		$this->pagination             = $this->get('Pagination');

		parent::display($tpl);
	}

	/**
	 * @param   integer $categoryId Category Id
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	protected function getCategoriesHtml($categoryId)
	{
		$categories  = Categories::getCategories();
		$categories1 = array();

		foreach ($categories as $key => $value)
		{
			$categories1[$key]            = new stdClass;
			$categories1[$key]->id        = $categories[$key]->id;
			$categories1[$key]->parent_id = $categories[$key]->parent_id;
			$categories1[$key]->title     = $categories[$key]->title;
			$treename                     = str_replace("&#160;&#160;&#160;&#160;&#160;&#160;", " ", $categories[$key]->treename);
			$treename                     = str_replace("<sup>", " ", $treename);
			$treename                     = str_replace("</sup>&#160;", " ", $treename);
			$categories1[$key]->treename  = $treename;
			$categories1[$key]->children  = $categories[$key]->children;
		}

		$temps              = array();
		$temps[0]           = new stdClass;
		$temps[0]->id       = "0";
		$temps[0]->treename = JText::_('COM_REDSHOP_SELECT_CATEGORY');
		$categories1        = @array_merge($temps, $categories1);

		return JHTML::_('select.genericlist', $categories1, 'category_id',
			'class="inputbox" onchange="document.adminForm.submit();" ', 'id', 'treename', $categoryId
		);
	}

	/**
	 *
	 * @return string
	 *
	 * @since  2.1.0
	 * @throws Exception
	 */
	protected function getProductTemplateHtml()
	{
		$templates      = RedshopHelperTemplate::getTemplate('product');
		$temps          = array();
		$temps[0]       = new stdClass;
		$temps[0]->id   = "0";
		$temps[0]->name = JText::_('COM_REDSHOP_ASSIGN_TEMPLATE');
		$templates      = @array_merge($temps, $templates);

		return JHtml::_('select.genericlist', $templates, 'product_template',
			'class="inputbox" size="1"  onchange="return AssignTemplate()" ', 'id', 'name', 0
		);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.5
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT'), 'stack redshop_products48');
		$layout = JFactory::getApplication()->input->getCmd('layout', '');

		if ($layout === 'element')
		{
			return;
		}

		if ($layout != 'importproduct' && $layout != 'importattribute' && $layout != 'listing' && $layout != 'ins_product')
		{
			JToolbarHelper::addNew('product_detail.addRedirect');
			JToolbarHelper::editList('product_detail.editRedirect');
			JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
			JToolBarHelper::deleteList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();

			JToolBarHelper::custom('assignCategory', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_ASSIGN_CATEGORY'), true);
			JToolBarHelper::custom('removeCategory', 'delete.png', 'delete_f2.png', JText::_('COM_REDSHOP_REMOVE_CATEGORY'), true);
		}
	}
}
