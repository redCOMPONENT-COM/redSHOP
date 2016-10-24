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
 * View Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.6
 */
class RedshopViewCurrency extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	protected $form;

	protected $item;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 * 
	 * @return  void
	 * 
	 * @since   2.0.0.6
	 */

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_CURRENCY_MANAGEMENT'), 'redshop_currency_48');

		$uri = JFactory::getURI();

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
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
		$title = JText::_('COM_REDSHOP_CURRENCY_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title, 'redshop_currency_48');
		JToolBarHelper::apply('currency.apply');
		JToolBarHelper::save('currency.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('currency.cancel');
		}
		else
		{
			JToolBarHelper::cancel('currency.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
