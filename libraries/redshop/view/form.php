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

/**
 * Base view.
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
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
	 * @since  __DEPLOY_VERSION__
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
	 * @since  __DEPLOY_VERSION__
	 */
	public $item;

	/**
	 * @var    JForm
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $form;

	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $fields;

	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $hiddenFields;

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string &$tpl Template name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
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
	 * @since   __DEPLOY_VERSION__
	 */
	protected function checkPermission()
	{
		if (!$this->useUserPermission)
		{
			return;
		}

		$app = JFactory::getApplication();

		// Check permission on create new
		if ((empty($this->item->id) && !$this->canCreate) || (!empty($this->item->id) && !$this->canEdit))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_NO_PERMISSION'), 'error');

			$app->redirect('index.php?option=com_redshop');
		}
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
		$isNew = ($this->item->id < 1);

		JToolBarHelper::apply($this->getInstanceName() . '.apply');
		JToolBarHelper::save($this->getInstanceName() . '.save');

		if ($isNew)
		{
			JToolBarHelper::cancel($this->getInstanceName() . '.cancel');
		}
		else
		{
			JToolBarHelper::cancel($this->getInstanceName() . '.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}

	/**
	 * Method for load all available fields and populate in groups
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
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
	 * @param   object $group Group object
	 *
	 * @return  void
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function prepareFields($group)
	{
		$group->fields = array();
		$fields        = $this->form->getFieldset($group->name);

		if (empty($fields))
		{
			return;
		}

		foreach ($fields as $field)
		{
			if ($field->getAttribute('type') == "spacer")
			{
				continue;
			}

			if ($field->getAttribute('type') == "hidden")
			{
				$this->hiddenFields[] = $this->form->getInput($field->getAttribute('name'));

				continue;
			}

			$group->fields[] = $this->form->renderField($field->getAttribute('name'));
		}

		$group->html = implode('', $group->fields);
	}
}
