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
 * View Shopper Group
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewShopper_Group extends RedshopViewAdmin
{
	/**
	 * @var   Object
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $item;

	/**
	 * @var   JForm
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $form;

	/**
	 * @var   array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $state;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->shopper_group_id < 1);

		$title = JText::_('COM_REDSHOP_SHOPPER_GROUP');

		if ($isNew)
		{
			$title .= ': <small>[ ' . JText::_('COM_REDSHOP_NEW') . ' ]</small>';
		}
		else
		{
			$title .= ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
		}

		JToolBarHelper::title($title);
		JToolBarHelper::apply('shopper_group.apply');
		JToolBarHelper::save('shopper_group.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('shopper_group.cancel');
		}
		else
		{
			JToolBarHelper::cancel('shopper_group.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
