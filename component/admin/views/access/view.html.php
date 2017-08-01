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
 * @since       2.0.6
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
	 * @since   2.0.6
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
	 * @since   2.0.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Prepare text for title
		JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER'));
		JToolBarHelper::apply('access.save');
	}
}
