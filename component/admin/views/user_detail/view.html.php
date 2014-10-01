<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperShopper');

jimport('joomla.application.component.view');

class RedshopViewUser_detail extends JView
{
	public function display($tpl = null)
	{
		$Redconfiguration = new Redconfiguration;
		$userhelper       = new rsUserhelper;
		$extra_field      = new extra_field;
		$shoppergroup     = new shoppergroup;

		$document         = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');

		$this->setLayout('default');

		$this->lists      = array();
		$this->detail     = $this->get('data');

		$isNew = ($detail->users_info_id < 1);
		$text  = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		if (JFactory::getApplication()->input->getString('shipping'))
		{
			JToolBarHelper::title(
				JText::_('COM_REDSHOP_USER_SHIPPING_DETAIL') . ': <small><small>[ '
				. $text . ' ]</small></small>', 'redshop_user48');
		}
		else
		{
			JToolBarHelper::title(
				JText::_('COM_REDSHOP_USER_MANAGEMENT_DETAIL') . ': <small><small>[ '
				. $text . ' ]</small></small>', 'redshop_user48'
			);
		}

		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::customX('order', 'redshop_order32', '', JText::_('COM_REDSHOP_PLACE_ORDER'), false);
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$this->pagination                       = $this->get('Pagination');
		$this->detail->user_groups              = $userhelper->getUserGroupList($this->detail->users_info_id);
		$this->lists['shopper_group']           = $shoppergroup->list_all("shopper_group_id", 0, array((int) $this->detail->shopper_group_id));

		$this->lists['tax_exempt']              = JHTML::_('select.booleanlist', 'tax_exempt', 'class="inputbox"', $this->detail->tax_exempt);
		$this->lists['block']                   = JHTML::_('select.booleanlist', 'block', 'class="inputbox"', $this->detail->block);
		$this->lists['tax_exempt_approved']     = JHTML::_('select.booleanlist', 'tax_exempt_approved', 'class="inputbox"', $this->detail->tax_exempt_approved);

		$this->lists['requesting_tax_exempt']   = JHTML::_('select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', $this->detail->requesting_tax_exempt);
		$this->lists['is_company']              = JHTML::_(
													'select.booleanlist',
													'is_company',
													'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
													$this->detail->is_company,
													JText::_('COM_REDSHOP_USER_COMPANY'),
													JText::_('COM_REDSHOP_USER_CUSTOMER')
												);

		$this->lists['sendEmail']               = JHTML::_('select.booleanlist', 'sendEmail', 'class="inputbox"', $this->detail->sendEmail);
		$this->lists['extra_field']             = $extra_field->list_all_field(6, $this->detail->users_info_id);
		$this->lists['customer_field']          = $extra_field->list_all_field(7, $this->detail->users_info_id);
		$this->lists['company_field']           = $extra_field->list_all_field(8, $this->detail->users_info_id);
		$this->lists['shipping_customer_field'] = $extra_field->list_all_field(14, $this->detail->users_info_id);
		$this->lists['shipping_company_field']  = $extra_field->list_all_field(15, $this->detail->users_info_id);

		$countryarray                           = $Redconfiguration->getCountryList((array) $this->detail);
		$this->detail->country_code             = $countryarray['country_code'];
		$this->lists['country_code']            = $countryarray['country_dropdown'];

		$statearray                             = $Redconfiguration->getStateList((array) $this->detail);
		$this->lists['state_code']              = $statearray['state_dropdown'];

		$this->request_url                      = JFactory::getURI()->toString();

		parent::display($tpl);
	}
}
