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
 * View Tax group
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */
class RedshopViewTax_Group extends RedshopViewAdmin
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

	protected $form;

	protected $item;

	protected $state;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  Name of template
	 *
	 * @return  void
	 *
	 * @since   2.0.0.2.1
	 */

	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		$this->addToolbar();

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

		$isNew = ($this->item->id < 1);

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_TAX_GROUP_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolbarHelper::title($title, 'redshop_supplier_48');
		JToolbarHelper::apply('tax_group.apply');
		JToolbarHelper::save('tax_group.save');

		if ($isNew)
		{
			JToolbarHelper::cancel('tax_group.cancel');
		}
		else
		{
			JToolbarHelper::cancel('tax_group.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
