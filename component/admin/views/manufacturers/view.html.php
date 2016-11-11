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
 * Manufacturers list view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View.Manufacturers
 * @since       2.0.0.3
 */
class RedshopViewManufacturers extends RedshopViewAdmin
{

	/**
	 * Display view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  bool
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
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

			return false;
		}

		// Set the tool-bar and number of found items
		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.0.2
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_MANUFACTURER_MANAGEMENT'), 'flag redshop_manufact48');

		JToolbarHelper::addNew('manufacturer.add');
		JToolbarHelper::editList('manufacturer.edit');
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList('', 'manufacturers.delete');
		JToolBarHelper::publishList('manufacturer.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublishList('manufacturer.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}
