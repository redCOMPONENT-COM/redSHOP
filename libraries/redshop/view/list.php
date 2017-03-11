<?php
/**
 * @package     Redshop
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Redshop\View\AbstractView;

jimport('joomla.application.component.viewlegacy');

/**
 * Base view.
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       1.5
 */
class RedshopViewList extends AbstractView
{
	/**
	 * Layout used to render the component
	 *
	 * @var  string
	 */
	protected $componentLayout = 'component.admin';

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = true;

	/**
	 * Do we have to disable a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $disableSidebar = false;

	/**
	 * @var  string
	 */
	protected $instancesName;

	/**
	 * @var  string
	 */
	protected $instanceName;

	/**
	 * @var array
	 */
	protected $columns = array();

	/**
	 * Column for render published state.
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	protected $stateColumns = array('published', 'state');

	/**
	 * @var  RedshopModel
	 */
	public $model;

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
	 * Method for run before display to initial variables.
	 *
	 * @param   string  &$tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function beforeDisplay(&$tpl)
	{
		// Get data from the model
		$this->items         = $this->model->getItems();
		$this->pagination    = $this->model->getPagination();
		$this->state         = $this->model->getState();
		$this->activeFilters = $this->model->getActiveFilters();
		$this->filterForm    = $this->model->getForm();

		$this->prepareTable();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function addTitle()
	{
		$title = $this->getTitle();

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'> (" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title);
	}

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_' . strtoupper($this->getInstanceName()) . '_MANAGEMENT');
	}

	/**
	 * Method for add toolbar.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		// Add common button
		JToolbarHelper::addNew($this->getInstanceName() . '.add');
		JToolbarHelper::deleteList('', $this->getInstancesName() . '.delete');
		JToolbarHelper::publish($this->getInstancesName() . '.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish($this->getInstancesName() . '.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolbarHelper::checkin($this->getInstancesName() . '.publish', 'JTOOLBAR_CHECKIN', true);
	}

	/**
	 * Method for get columns
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Method for prepare table.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function prepareTable()
	{
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/models/forms/' . $this->getInstanceName() . '.xml';

		if (!is_file($formPath))
		{
			return;
		}

		// Load single form xml file
		$form = simplexml_load_file($formPath);

		// Get field set data
		$fields = $form->xpath('(//fieldset[@name="details"]//field | //field[@fieldset="details"])[not(ancestor::field)]');

		if (empty($fields))
		{
			return;
		}

		foreach ($fields as $field)
		{
			// Skip for spacer
			if ($field['type'] == 'spacer' || $field['type'] == 'hidden' || !empty($field['table-hide']))
			{
				continue;
			}

			$this->columns[] = array(
				'sortable' => isset($field['table-sortable']) ? (boolean) $field['table-sortable'] : false,
				'text'     => JText::_((string) $field['label']),
				'dataCol'  => (string) $field['name'],
				'width'    => isset($field['table-width']) ? (string) $field['table-width'] : 'auto',
				'inline'   => isset($field['table-inline']) ? (boolean) $field['table-inline'] : false,
				'type'     => (string) $field['type'],
			);
		}
	}

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if (empty($config) || is_null($index) || is_null($row))
		{
			return '';
		}

		$isCheckedOut = $row->checked_out && JFactory::getUser()->id != $row->checked_out;

		if (in_array($config['dataCol'], $this->stateColumns))
		{
			return JHtml::_('jgrid.published', $row->published, $index);
		}
		elseif ($config['inline'] === true && !$isCheckedOut)
		{
			return JHtml::_('redshopgrid.inline', $config['dataCol'], $row->{$config['dataCol']}, $row->id, $config['type']);
		}

		return '<div class="normal-data">' . $row->{$config['dataCol']} . '</div>';
	}
}
