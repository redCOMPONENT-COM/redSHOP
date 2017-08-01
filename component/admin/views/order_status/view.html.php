<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Order Status
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewOrder_Status extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * @var  JForm
	 */
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->order_status_id < 1);

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_ORDERSTATUS_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title);
		JToolBarHelper::apply('order_status.apply');
		JToolBarHelper::save('order_status.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('order_status.cancel');
		}
		else
		{
			JToolBarHelper::cancel('order_status.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
