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
 * The tax rate view
 *
 * @package     RedSHOP.Backend
 * @subpackage  Tax_Rate.View
 * @since       2.0.0.6
 */
class RedshopViewTax_Rate extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @throws  Exception
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));

			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.0.4
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id < 1);

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_TAX_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title);
		JToolBarHelper::apply('tax_rate.apply');
		JToolBarHelper::save('tax_rate.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('tax_rate.cancel');
		}
		else
		{
			JToolBarHelper::cancel('tax_rate.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
