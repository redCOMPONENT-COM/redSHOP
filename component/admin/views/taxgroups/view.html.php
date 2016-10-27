<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Tax Groups
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.9
 */
class RedshopViewTaxgroups extends RedshopViewAdmin
{
	/**
	 * Display template function
	 *
	 * @param   object  $tpl  template variable
	 *
	 * @return void
	 *
	 * @since 2.0.0.9
	 */

	public function display($tpl = null)
	{
		$model = $this->getModel();

		// Get data from the model
		$this->items         = $model->getItems();
		$this->user          = JFactory::getUser();
		$this->pagination    = $model->getPagination();
		$this->state         = $model->getState();
		$this->activeFilters = $model->getActiveFilters();
		$this->filterForm    = $model->getForm();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode('<br />', $errors));

			return false;
		}

		// Set the tool-bar and number of found items
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.0.9
	 */
	protected function addToolBar()
	{
		$title = JText::_('COM_REDSHOP_TAX_GROUP_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'tags redshop_vatgroup48');
		JToolBarHelper::addNew('taxgroup.add');
		JToolBarHelper::editList('taxgroup.edit');
		JToolBarHelper::deleteList('', 'taxgroups.delete');
		JToolbarHelper::publish('taxgroups.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('taxgroups.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}
