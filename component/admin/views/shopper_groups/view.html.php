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
 * Shopper Group list view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewShopper_Groups extends RedshopViewAdmin
{
	/**
	 * List of questions
	 *
	 * @var   array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected $items = array();

	/**
	 * Pagination
	 *
	 * @var   JPagination
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected $pagination = null;

	/**
	 * Model state
	 *
	 * @var   array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected $state = null;

	/**
	 * Active filters
	 *
	 * @var   array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $activeFilters = array();

	/**
	 * Filter form
	 *
	 * @var   array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $filterForm = array();

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		// Get data from the model
		$model = $this->getModel();

		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
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
		$title = JText::_('COM_REDSHOP_SHOPPER_GROUP_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'redshop_giftcard_48');
		JToolBarHelper::addNew('shopper_group.add');
		JToolBarHelper::editList('shopper_group.edit');
		JToolBarHelper::deleteList('', 'shopper_groups.delete');
		JToolbarHelper::publishList('shopper_groups.publish', 'JTOOLBAR_PUBLISH');
		JToolbarHelper::unpublishList('shopper_groups.unpublish', 'JTOOLBAR_UNPUBLISH');
	}
}
