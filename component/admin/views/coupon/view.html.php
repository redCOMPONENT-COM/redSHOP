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
 * View Coupon
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.5
 */
class RedshopViewCoupon extends RedshopViewAdmin
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

	protected $state;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 * 
	 * @return  void
	 * 
	 * @since   2.0.0.4
	 */
	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_COUPON_MANAGEMENT'), 'redshop_coupon_48');

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
		$title = JText::_('COM_REDSHOP_COUPON_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title, 'redshop_country_48');
		JToolBarHelper::apply('coupon.apply');
		JToolBarHelper::save('coupon.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('coupon.cancel');
		}
		else
		{
			JToolBarHelper::cancel('coupon.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}

	/*
	public function display($tpl = null)
	{
		$userslist = JRequest::getVar('userslist', array());

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUPON_MANAGEMENT_DETAIL'), 'redshop_coupon48');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->coupon_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUPON') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_coupon48');

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$lists['free_shipping'] = JHtml::_('redshopselect.booleanlist', 'free_shipping', 'class="inputbox" ', $detail->free_shipping);
		$percent_or_total = array(
			JHtml::_('select.option', 0, JText::_('COM_REDSHOP_TOTAL')),
			JHtml::_('select.option', 1, JText::_('COM_REDSHOP_PERCENTAGE'))
		);
		$lists['percent_or_total'] = JHtml::_('select.genericlist', $percent_or_total, 'percent_or_total',
			'class="inputbox" size="1"', 'value', 'text', $detail->percent_or_total
		);

		$coupon_type = array(
			JHtml::_('select.option', 0, JText::_('COM_REDSHOP_GLOBAL')),
			JHtml::_('select.option', 1, JText::_('COM_REDSHOP_USER_SPECIFIC'))
		);
		$lists['coupon_type'] = JHtml::_('select.genericlist', $coupon_type, 'coupon_type',
			'class="inputbox" size="1"', 'value', 'text', $detail->coupon_type
		);

		$lists['published'] = JHtml::_('redshopselect.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$lists['userslist'] = JHtml::_('select.genericlist', $userslist, 'userid', 'class="inputbox" size="1" ', 'value', 'text', $detail->userid);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
	*/
}
