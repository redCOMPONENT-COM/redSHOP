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
 * View Mass Discount
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewMass_Discount extends RedshopViewAdmin
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

	/**
	 * @var  object
	 */
	protected $item;

	/**
	 * @var  object
	 */
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

		if ($this->item->id)
		{
			$this->item->discount_product = explode(',', $this->item->discount_product);
			$this->item->category_id = explode(',', $this->item->category_id);
			$this->item->manufacturer_id = explode(',', $this->item->manufacturer_id);
			$this->form->setValue('discount_product', null, $this->item->discount_product);
			$this->form->setValue('category_id', null, $this->item->category_id);
			$this->form->setValue('manufacturer_id', null, $this->item->manufacturer_id);

			$this->form->setValue('end_date', null, JFactory::getDate(date('d-m-Y H:i:s', $this->item->end_date))->format('d-m-Y'));
		}

		$this->state = $this->get('State');

		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id < 1);

		if ($isNew)
		{
			$suffix = JText::_('COM_REDSHOP_NEW');
		}
		else
		{
			$suffix = JText::_('COM_REDSHOP_EDIT');
		}

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_MASS_DISCOUNT_PRODUCT') . ': <small>[ ' . $suffix  . ' ]</small>';

		JToolBarHelper::title($title);
		JToolBarHelper::apply('mass_discount.apply');
		JToolBarHelper::save('mass_discount.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('mass_discount.cancel');
		}
		else
		{
			JToolBarHelper::cancel('mass_discount.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
