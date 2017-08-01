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
 * View Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2.1
 */

class RedshopViewCountries extends RedshopViewAdmin
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
		/** @var RedshopModelCountries $model */
		$model = $this->getModel();

		// Get data from the model
		$this->items         = $model->getItems();
		$this->pagination    = $model->getPagination();
		$this->state         = $model->getState();
		$this->activeFilters = $model->getActiveFilters();
		$this->filterForm    = $model->getForm();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode('<br />', $errors));
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
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');
		JToolBarHelper::addNew('country.add');
		JToolBarHelper::editList('country.edit');
		JToolBarHelper::deleteList('', 'countries.delete');
	}
}
