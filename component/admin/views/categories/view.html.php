<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Categories
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */
class RedshopViewCategories extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $items;

	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  array
	 */
	public $state;

	/**
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * @var  JForm
	 */
	public $filterForm;

	/**
	 * Display template function
	 *
	 * @param   object  $tpl  template variable
	 *
	 * @return void
	 *
	 * @since 2.0.0.3
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		$this->items         = $this->get('Items');
		$this->state         = $this->get('State');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('Form');
		$this->activeFilters = $this->get('ActiveFilters');

		// Set the tool-bar and number of found items
		$this->addToolBar();

		$this->ordering = array();

		foreach ($this->items as &$item)
		{
			$this->ordering[$item->parent_id][] = $item->id;
		}

		// Edit State permission
		$this->canEditState = false;

		if ($user->authorise('core.edit.state', 'com_reditem'))
		{
			$this->canEditState = true;
		}

		// Edit permission
		$this->canEdit = false;

		if ($user->authorise('core.edit', 'com_reditem'))
		{
			$this->canEdit = true;
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		$user  = JFactory::getUser();
		$title = JText::_('COM_REDSHOP_CATEGORY_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'redshop_categories48');

		if ($user->authorise('com_redshop', 'core.create'))
		{
			JToolBarHelper::addNew('category.add');
			JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		}

		if ($user->authorise('com_redshop', 'core.edit'))
		{
			JToolBarHelper::editList('category.edit');
			JToolBarHelper::checkin('categories.checkin');
		}

		if ($user->authorise('com_redshop', 'core.edit.state'))
		{
			JToolBarHelper::deleteList('', 'categories.delete');
			JToolbarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		}
	}
}
