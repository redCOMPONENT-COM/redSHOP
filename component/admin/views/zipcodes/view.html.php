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
 * View Zipcodes
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.8
 */
class RedshopViewZipcodes extends RedshopViewAdmin
{
	/**
	 * Display template function
	 *
	 * @param   object  $tpl  template variable
	 *
	 * @return void
	 *
	 * @since 2.0.0.8
	 */

	public function display($tpl = null)
	{
		$model = $this->getModel();

		// Get data from the model
		$this->items         = $model->getItems();
		$this->user = JFactory::getUser();
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
	 * @since   2.0.0.8
	 */
	protected function addToolBar()
	{
		$title = JText::_('COM_REDSHOP_ZIPCODE_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'redshop_zipcode_48');
		JToolBarHelper::addNew('zipcode.add');
		JToolBarHelper::editList('zipcode.edit');
		JToolBarHelper::deleteList('', 'zipcodes.delete');
	}
}
