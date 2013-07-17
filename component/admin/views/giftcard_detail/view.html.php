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

class giftcard_detailVIEWgiftcard_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_GIFTCARD_MANAGEMENT'), 'redshop_giftcard_48');

		$uri = JFactory::getURI();

		jimport('joomla.html.pane');
		$pane = JPane::getInstance('sliders');
		$this->pane = $pane;

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->giftcard_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_GIFTCARDS') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_giftcard_48');
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$lists['customer_amount'] = JHTML::_('select.booleanlist', 'customer_amount', 'class="inputbox" ', $detail->customer_amount);
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		if (ECONOMIC_INTEGRATION == 1)
		{
			$redhelper = new redhelper;
			$accountgroup = $redhelper->getEconomicAccountGroup();
			$op = array();
			$op[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
			$accountgroup = array_merge($op, $accountgroup);
			$lists["accountgroup_id"] = JHTML::_('select.genericlist', $accountgroup, 'accountgroup_id',
				'class="inputbox" size="1" ', 'value', 'text',
				$detail->accountgroup_id
			);
		}

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
