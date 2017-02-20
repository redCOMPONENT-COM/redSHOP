<?php
/**
 * @package     Redshop
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Doctrine\Common\Inflector\Inflector;

jimport('joomla.application.component.viewlegacy');

/**
 * Base view.
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       1.5
 */
class RedshopViewList extends JViewLegacy
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
	 * Is table data show column number or not?
	 *
	 * @var     bool
	 * @since   __DEPLOY_VERSION__
	 */
	public $showNumber = true;

	/**
	 * Is table data show column id or not?
	 *
	 * @var     bool
	 * @since   __DEPLOY_VERSION__
	 */
	public $showId = true;

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
		$this->beforeDisplay($tpl);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode('<br />', $errors));
		}

		// Add page title
		$this->addTitle();

		// Add toolbar
		$this->addToolbar();

		// Prepare table data.
		$this->prepareTable();

		$render = RedshopLayoutHelper::render(
			$this->componentLayout,
			array(
				'view'            => $this,
				'tpl'             => $tpl,
				'sidebar_display' => $this->displaySidebar
			)
		);

		JPluginHelper::importPlugin('system');
		RedshopHelperUtility::getDispatcher()->trigger('onRedshopAdminRender', array(&$render));

		if ($render instanceof Exception)
		{
			return $render;
		}

		echo $render;

		return true;
	}

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function beforeDisplay($tpl)
	{
		$this->model = $this->getModel();

		// Get data from the model
		$this->items         = $this->model->getItems();
		$this->pagination    = $this->model->getPagination();
		$this->state         = $this->model->getState();
		$this->activeFilters = $this->model->getActiveFilters();
		$this->filterForm    = $this->model->getForm();
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
		JToolBarHelper::addNew($this->getInstanceName() . '.add');
		JToolBarHelper::deleteList('', $this->getInstancesName() . '.delete');
		JToolbarHelper::publish($this->getInstancesName() . '.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish($this->getInstancesName() . '.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolbarHelper::checkin($this->getInstancesName() . '.publish', 'JTOOLBAR_CHECKIN', true);
	}

	/**
	 * Method for get instance name with multi (Ex: products, categories,...) of current view
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getInstancesName()
	{
		if (is_null($this->instancesName))
		{
			$this->instancesName = strtolower(str_replace('RedshopView', '', get_class($this)));
		}

		return $this->instancesName;
	}

	/**
	 * Method for get instance name with single (Ex: product, category,...) of current view
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getInstanceName()
	{
		if (is_null($this->instanceName))
		{
			$this->instanceName = Inflector::singularize($this->getInstancesName());
		}

		return $this->instanceName;
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
				'dataCol'  => isset($field['table-data']) ? (string) $field['table-data'] : (string) $field['name'],
				'width'    => isset($field['table-width']) ? (string) $field['table-width'] : 'auto',
				'inline'   => isset($field['table-inline']) ? (boolean) $field['table-inline'] : false
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
		$isCheckedOut = $row->checked_out && JFactory::getUser()->id != $row->checked_out;

		if ($config['dataCol'] == 'published')
		{
			return JHtml::_('grid.published', $row, $index);
		}
		elseif ($config['inline'] === true && !$isCheckedOut)
		{
			return JHtml::_('redshopgrid.inline', $config['dataCol'], $row->{$config['dataCol']}, $row->id);
		}

		return $row->{$config['dataCol']};
	}
}
