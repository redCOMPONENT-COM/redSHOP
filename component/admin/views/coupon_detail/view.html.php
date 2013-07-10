<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class coupon_detailVIEWcoupon_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$userslist = JRequest::getVar('userslist', array());

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUPON_MANAGEMENT_DETAIL'), 'redshop_coupon48');

		$document = JFactory::getDocument();
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');

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

		$model = $this->getModel('coupon_detail');
		$lists['free_shipping'] = JHTML::_('select.booleanlist', 'free_shipping', 'class="inputbox" ', $detail->free_shipping);
		$percent_or_total = array(JHTML::_('select.option', 'no', JText::_('COM_REDSHOP_SELECT')),
			JHTML::_('select.option', 0, JText::_('COM_REDSHOP_TOTAL')),
			JHTML::_('select.option', 1, JText::_('COM_REDSHOP_PERCENTAGE'))
		);
		$lists['percent_or_total'] = JHTML::_('select.genericlist', $percent_or_total, 'percent_or_total',
			'class="inputbox" size="1"', 'value', 'text', $detail->percent_or_total
		);

		$coupon_type = array(JHTML::_('select.option', 'no', JText::_('COM_REDSHOP_SELECT')),
			JHTML::_('select.option', 0, JText::_('COM_REDSHOP_GLOBAL')),
			JHTML::_('select.option', 1, JText::_('COM_REDSHOP_USER_SPECIFIC'))
		);
		$lists['coupon_type'] = JHTML::_('select.genericlist', $coupon_type, 'coupon_type',
			'class="inputbox" size="1"', 'value', 'text', $detail->coupon_type
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$lists['userslist'] = JHTML::_('select.genericlist', $userslist, 'userid', 'class="inputbox" size="1" ', 'value', 'text', $detail->userid);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
