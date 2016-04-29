<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCountry_detail extends RedshopView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');

		$uri = JFactory::getURI();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		$lists = array();
		$detail = $this->get('data');
		$isNew = ($detail->country_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_country_48');

		$this->detail = $detail;
		$this->lists = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
