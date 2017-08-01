<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCurrency_detail extends RedshopViewAdmin
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
		JToolBarHelper::title(JText::_('COM_REDSHOP_currency_MANAGEMENT'), 'redshop_currencies_48');

		$uri = JFactory::getURI();
		JToolBarHelper::save();
		JToolBarHelper::apply();

		$lists = array();
		$detail = $this->get('data');
		$isNew = ($detail->currency_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_currency') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_currencies_48');

		$this->detail = $detail;
		$this->lists = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
