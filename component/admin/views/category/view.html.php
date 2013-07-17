<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/category.php';

class categoryViewcategory extends JView
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
		$app = JFactory::getApplication();

		$context = 'category_id';

		$redTemplate = new Redtemplate;
		$product_category = new product_category;
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_CATEGORY'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATEGORY_MANAGEMENT'), 'redshop_categories48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();

		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'c.ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$GLOBALS['catlist'] = array();
		$catid = JRequest::getVar('category_id', 0, '');
		$categories = $this->get('Data');

		$pagination = $this->get('Pagination');
		$category_main_filter = $app->getUserStateFromRequest($context . 'category_main_filter', 'category_main_filter', '');
		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$category_id = $app->getUserStateFromRequest($context . 'category_id', 'category_id', '');
		$category_name = $app->getUserStateFromRequest($context . 'category_name', 'category_name', 0);
		$category = new product_category;
		$categories_parent = $category->getParentCategories();

		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->category_id = "0";
		$temps[0]->category_name = JText::_('COM_REDSHOP_SELECT');
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
