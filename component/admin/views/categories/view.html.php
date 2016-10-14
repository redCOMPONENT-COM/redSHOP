<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewCategories extends RedshopViewAdmin
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
		$model = $this->getModel();

		// Get data from the model
		$this->items         = $model->getData();
		$this->pagination    = $model->getPagination();
		$this->state         = $model->getState();
		$this->activeFilters = $model->getActiveFilters();
		$this->filterForm    = $model->getForm();

		// Set the tool-bar and number of found items
		$this->addToolBar();

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

		if ((count($user->authorise('com_redshop', 'core.create'))) > 0)
		{
			JToolBarHelper::addNew('category.add');
			JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		}

		if ((count($user->authorise('com_redshop', 'core.edit'))) > 0)
		{
			JToolBarHelper::editList('category.edit');
		}

		if ((count($user->authorise('com_redshop', 'core.edit.state'))) > 0)
		{
			JToolBarHelper::deleteList('', 'categories.delete');
			JToolbarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		}
	}
}
