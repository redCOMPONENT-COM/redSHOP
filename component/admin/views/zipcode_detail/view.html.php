<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewZipcode_detail extends RedshopViewAdmin
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

	public $detail;

	public $lists;

	public function display($tpl = null)
	{
		$Redconfiguration = Redconfiguration::getInstance();
		$uri              = JFactory::getURI();
		$lists            = array();
		$detail           = $this->get('data');
		$isNew            = ($detail->zipcode_id < 1);
		$text             = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		$model            = $this->getModel();

		JHtml::script('media/com_redshop/js/redshop.admin.common.js');

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

		$countries = $model->getcountry();

		$state_code = array();

		if ($detail->country_code)
		{
			$state_code = $model->getStateList($detail->country_code);
		}

		$detail->state_code = explode(',', $detail->state_code);

		$lists['state_code'] = JHTML::_('select.genericlist', $state_code, 'state_code[]',
			'class="inputbox" multiple="multiple"', 'value', 'text', $detail->state_code
		);

		$detail->country_code  = explode(',', $detail->country_code);

		$lists['country_code'] = JHTML::_('select.genericlist', $countries, 'country_code[]',
			'class="inputbox" multiple="multiple" onchange="getStateList_Zipcode()" ', 'value', 'text', $detail->country_code
		);

		$this->detail      = $detail;
		$this->lists       = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
