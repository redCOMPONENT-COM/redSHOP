<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCoupon_detail extends RedshopViewAdmin
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

	public function display($tpl = null)
	{
		$userslist = JFactory::getApplication()->input->get('userslist', array(), 'raw');

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
}
