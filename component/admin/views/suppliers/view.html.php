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
 * View Suppliers
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.7
 */
class RedshopViewSuppliers extends RedshopViewAdmin
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
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   2.0.0.7
	 */
	public function display($tpl = null)
	{
		/** @var RedshopModelSuppliers $model */
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
		$title = JText::_('COM_REDSHOP_SUPPLIER_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'redshop_supplier_48');
		JToolBarHelper::addNew('supplier.add');
		JToolBarHelper::editList('supplier.edit');
		JToolBarHelper::deleteList('', 'suppliers.delete');
		JToolbarHelper::publish('suppliers.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('suppliers.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolbarHelper::custom('suppliers.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
	}
}
