<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       2.0.0.2.1
 */

defined('_JEXEC') or die;

/**
 * View Access
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */

class RedshopViewAccess extends RedshopViewAdmin
{
	/**
	 * @var  JForm
	 */
	protected $form;

	/**
	 * @var  Object
	 */
	protected $item;

	/**
	 * @var  array
	 */
	protected $state;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER'), 'redshop_country_48');

		/** @var RedshopModelAccess $model */
		$model = $this->getModel();

		$this->item  = $model->getItem();
		$this->form  = $model->getForm();
		$this->state = $model->getState();

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

		// Prepare text for title
		JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER'));
		JToolBarHelper::apply('access.save');
	}
}
