<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');

class RedshopViewCategory extends RedshopViewAdmin
{
	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$redTemplate = Redtemplate::getInstance();

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATEGORY_MANAGEMENT'), 'folder redshop_categories48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();

		$state = $this->get('State');

		$GLOBALS['catlist'] = array();
		$categories = $this->get('Data');

		$pagination = $this->get('Pagination');
		$category_main_filter = $state->get('category_main_filter');
		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$category_id = $state->get('category_id', '');
		$category = new product_category;
		$categories_parent = $category->getParentCategories();

		$lists['order'] = $state->get('list.ordering', 'c.ordering');
		$lists['order_Dir'] = $state->get('list.direction', '');

		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->category_id = "0";
		$temps[0]->category_name = JText::_('COM_REDSHOP_SELECT_CATEGORY');
		$categories_parent = @array_merge($temps, $categories_parent);

		$lists['category'] = JHTML::_('select.genericlist', $categories_parent, 'category_id',
			'class="inputbox" onchange="document.adminForm.submit();"      ',
			'category_id', 'category_name', $category_id
		);

		/*
	    * assign template
	    */
		$templates = $redTemplate->getTemplate('category');
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->template_id = "0";
		$temps[0]->template_name = JText::_('COM_REDSHOP_ASSIGN_TEMPLATE');
		$templates = @array_merge($temps, $templates);

		$lists['category_template'] = JHTML::_('select.genericlist', $templates, 'category_template',
			'class="inputbox" size="1"  onchange="return AssignTemplate()" ',
			'template_id', 'template_name', 0
		);

		$this->category_main_filter = $category_main_filter;
		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->categories = $categories;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
