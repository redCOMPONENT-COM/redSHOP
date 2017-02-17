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
 * View Supplier
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.7
 */
class RedshopViewSupplier extends RedshopViewAdmin
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
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   2.0.0.2.1
	 */

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_SUPPLIER_MANAGEMENT'), 'redshop_supplier_48');

		$uri = JFactory::getURI();

		$this->form       = $this->get('Form');
		$this->item       = $this->get('Item');
		$this->state      = $this->get('State');
		$this->requestUrl = $uri->toString();

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

		$isNew = ($this->item->id < 1);

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_SUPPLIER_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title, 'redshop_supplier_48');
		JToolBarHelper::apply('supplier.apply');
		JToolBarHelper::save('supplier.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('supplier.cancel');
		}
		else
		{
			JToolBarHelper::save2copy('supplier.save2copy');
			JToolBarHelper::cancel('supplier.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
