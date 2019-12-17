<?php
/**
 * @package     Redshop
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Redshop\View\AbstractView;

/**
 * Base view.
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewForm extends AbstractView
{
	/**
	 * Layout used to render the component
	 *
	 * @var  string
	 */
	protected $componentLayout = 'component.admin';

	/**
	 * Form layout. (box, tab)
	 *
	 * @var    string
	 *
	 * @since  2.0.6
	 */
	protected $formLayout = 'box';

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Do we have to disable a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $disableSidebar = false;

	/**
	 * @var    object
	 *
	 * @since  2.0.6
	 */
	public $item;

	/**
	 * @var    JForm
	 *
	 * @since  2.0.6
	 */
	public $form;

	/**
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	public $fields;

	/**
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	public $hiddenFields;

	/**
	 * Split fieldset in form into column
	 *
	 * @var   integer
	 * @since 2.0.7
	 */
	public $formFieldsetsColumn = 2;

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 *
	 * @throws  Exception
	 */
	public function beforeDisplay(&$tpl)
	{
		// Get data from the model
		$this->item = $this->model->getItem();
		$this->form = $this->model->getForm();

		$this->checkPermission();
		$this->loadFields();
	}

	/**
	 * Method for check permission of current user on view
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 *
	 * @throws  Exception
	 */
	protected function checkPermission()
	{
		if (!$this->useUserPermission)
		{
			return;
		}

		$app = JFactory::getApplication();

		// Check permission on create new
		if ((empty($this->item->{$this->getPrimaryKey()}) && !$this->canCreate)
			|| (!empty($this->item->{$this->getPrimaryKey()}) && !$this->canEdit))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ACCESS_ERROR_NOT_HAVE_PERMISSION'), 'error');

			$app->redirect('index.php?option=com_redshop');
		}
	}

	/**
	 * Method for add toolbar.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	protected function addToolbar()
	{
		$isNew = ($this->item->{$this->getPrimaryKey()} < 1);

		if ($this->canEdit)
		{
			JToolbarHelper::apply($this->getInstanceName() . '.apply');
		}

		if ($this->canEdit || $this->canCreate)
		{
			JToolbarHelper::save($this->getInstanceName() . '.save');
			JToolbarHelper::save2new($this->getInstanceName() . '.save2new');
		}

		if ($isNew)
		{
			JToolbarHelper::cancel($this->getInstanceName() . '.cancel');
		}
		else
		{
			JToolbarHelper::cancel($this->getInstanceName() . '.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}

	/**
	 * Method for load all available fields and populate in groups
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 * @throws  Exception
	 */
	protected function loadFields()
	{
		$this->fields       = array();
		$this->hiddenFields = array();

		foreach ($this->form->getFieldsets() as $fieldset)
		{
			$this->fields[$fieldset->name] = $fieldset;
			$this->prepareFields($this->fields[$fieldset->name]);

			if (empty($this->fields[$fieldset->name]->fields))
			{
				unset($this->fields[$fieldset->name]);
			}
		}
	}

	/**
	 * Method for prepare fields in group and also HTML content
	 *
	 * @param   object  $group  Group object
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since  2.0.6
	 */
	protected function prepareFields($group)
	{
		$group->fields = array();

		$fields = $this->form->getFieldset($group->name);

		if (empty($fields))
		{
			return;
		}

		foreach ($fields as $field)
		{
			$fieldHtml = $this->prepareField($field);

			if (false === $fieldHtml)
			{
				continue;
			}

			$group->fields[] = $fieldHtml;
		}

		$group->html = implode('', $group->fields);
	}

	/**
	 * Method for prepare field HTML
	 *
	 * @param   object  $field  Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 *
	 * @since   2.1.0
	 */
	protected function prepareField($field)
	{
		if ($field->getAttribute('type') === "spacer")
		{
			return false;
		}

		if ($field->getAttribute('type') === "hidden")
		{
			$this->hiddenFields[] = $this->form->getInput($field->getAttribute('name'));

			return false;
		}

		return $this->form->renderField($field->getAttribute('name'));
	}

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 * @throws  Exception
	 */
	public function getTitle()
	{
		$primaryKey = $this->getPrimaryKey();
		$title      = parent::getTitle();

		return !empty($this->item->{$primaryKey}) ? $title . ' <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>' :
			$title . ' <small>[ ' . JText::_('COM_REDSHOP_NEW') . ' ]</small>';
	}
}
