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

class zipcode_detailVIEWzipcode_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$Redconfiguration = new Redconfiguration;
		$uri = JFactory::getURI();
		$lists = array();
		$detail = $this->get('data');
		$isNew = ($detail->zipcode_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_ZIPCODE_DETAIL') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_region_48');
		JToolBarHelper::save();
		JToolBarHelper::apply();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$countryarray = $Redconfiguration->getCountryList((array) $detail);
		$detail->country_code = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray = $Redconfiguration->getStateList((array) $detail);
		$lists['state_code'] = $statearray['state_dropdown'];

		$this->detail = $detail;
		$this->lists = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
