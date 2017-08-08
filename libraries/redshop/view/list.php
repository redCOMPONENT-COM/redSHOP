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
	 * @var  boolean
	 *
	 * @since  2.0.6
	 */
	public $hasOrdering = false;
	/**
	 * @var  boolean
	 *
	 * @since  2.0.6
	 */
	public $isNested = false;
	/**
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	public $nestedOrdering;
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
	 * @since  2.0.6
	 */
	protected $stateColumns = array('published', 'state');

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string $tpl Template name
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function beforeDisplay(&$tpl)
	{
		$this->checkPermission();

		// Get data from the model
		$this->items         = $this->model->getItems();
		$this->pagination    = $this->model->getPagination();
		$this->state         = $this->model->getState();
		$this->activeFilters = $this->model->getActiveFilters();
		$this->filterForm    = $this->model->getForm();

		$this->prepareTable();

		if ($this->hasOrdering && $this->isNested && !empty($this->items))
		{
			foreach ($this->items as &$item)
			{
				$this->nestedOrdering[$item->parent_id][] = $item->id;
			}
		}
	}

	/**
	 * Method for check permission of current user on view
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function checkPermission()
	{
		if (!$this->useUserPermission)
		{
			return;
		}

		// Check permission on create new
		if (!$this->canView)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_REDSHOP_ACCESS_ERROR_NOT_HAVE_PERMISSION'), 'error');
			$app->redirect('index.php?option=com_redshop');
		}
	}

	/**
	 * Method for prepare table.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function prepareTable()
	{
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/models/forms/' . $this->getInstanceName() . '.xml';

		if (!JFile::exists($formPath))
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

			if ($field['name'] == 'ordering')
			{
				$this->hasOrdering = true;
			}

			$this->columns[] = array(
				// This column is sortable?
				'sortable'  => isset($field['table-sortable']) ? boolval((int) $field['table-sortable']) : false,
				// Text for column
				'text'      => JText::_((string) $field['label']),
				// Name of property for get data.
				'dataCol'   => (string) $field['name'],
				// Width of column
				'width'     => isset($field['table-width']) ? (string) $field['table-width'] : 'auto',
				// Enable edit inline?
				'inline'    => isset($field['table-inline']) ? boolval((int) $field['table-inline']) : false,
				// Display with edit link or not?
				'edit_link' => isset($field['table-edit-link']) ? boolval((int) $field['table-edit-link']) : false,
				// Type of column
				'type'      => (string) $field['type'],
			);
		}
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
			$title .= '<span style="font - size: 0.5em; vertical - align: middle;"> (' . $this->pagination->total . ')</span>';
		}

		JToolbarHelper::title($title);
	}

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_' . strtoupper($this->getInstanceName()) . '_MANAGEMENT');
	}

	/**
	 * Method for get columns
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array  $config Row config.
	 * @param   int    $index  Row index.
	 * @param   object $row    Row data.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$user             = JFactory::getUser();
		$isCheckedOut     = $row->checked_out && $user->id != $row->checked_out;
		$inlineEditEnable = Redshop::getConfig()->getBool('INLINE_EDITING');
		$value            = $row->{$config['dataCol']};
		$primaryKey       = $this->getPrimaryKey();
		$itemId           = $row->{$primaryKey};

		if (in_array($config['dataCol'], $this->stateColumns))
		{
			if ($this->canEdit)
			{
				return JHtml::_('jgrid.published', $row->published, $index);
			}
			else
			{
				return '<span class="label ' . ($row->published ? 'label-success' : 'label-danger') . '">' .
					($row->published ? JText::_('JYES') : JText::_('JNO')) . '</span>';
			}
		}
		elseif ($config['inline'] === true && !$isCheckedOut && $inlineEditEnable && $this->canEdit)
		{
			$display = $value;

			if ($config['edit_link'])
			{
				$display = '<a href="index.php?option=com_redshop&task=' . $this->getInstanceName() . '.edit&' . $primaryKey . '=' . $itemId . '">'
					. $value . '</a>';
			}

			return JHtml::_('redshopgrid.inline', $config['dataCol'], $value, $display, $itemId, $config['type']);
		}
		elseif ($config['edit_link'] === true)
		{
			return '<a href="index.php?option=com_redshop&task=' . $this->getInstanceName() . '.edit&' . $primaryKey . '=' . $itemId . '">'
				. $value . '</a>';
		}

		return '<div class="normal-data">' . $value . '</div>';
	}

	/**
	 * Method for add toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function addToolbar()
	{
		// Add common button
		if ($this->canCreate)
		{
			JToolbarHelper::addNew($this->getInstanceName() . '.add');
		}

		if ($this->canDelete)
		{
			JToolbarHelper::deleteList('', $this->getInstancesName() . '.delete');
		}

		if ($this->canEdit)
		{
			JToolbarHelper::publish($this->getInstancesName() . '.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish($this->getInstancesName() . '.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::checkin($this->getInstancesName() . '.checkin', 'JTOOLBAR_CHECKIN', true);
		}
	}
}
