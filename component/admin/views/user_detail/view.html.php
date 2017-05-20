<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewUser_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		RedshopHelperJs::init();
		$userhelper   = rsUserHelper::getInstance();
		$extra_field  = extra_field::getInstance();
		$shoppergroup = new shoppergroup;

		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');

		$this->setLayout('default');

		$this->lists  = array();
		$this->detail = $this->get('data');

		$isNew = ($this->detail->users_info_id < 1);
		$text  = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		if (JFactory::getApplication()->input->getString('shipping'))
		{
			JToolbarHelper::title(
				JText::_('COM_REDSHOP_USER_SHIPPING_DETAIL') . ': <small><small>[ '
				. $text . ' ]</small></small>', 'user redshop_user48');
		}
		else
		{
			JToolbarHelper::title(
				JText::_('COM_REDSHOP_USER_MANAGEMENT_DETAIL') . ': <small><small>[ '
				. $text . ' ]</small></small>', 'user redshop_user48'
			);
		}

		JToolbarHelper::apply();
		JToolbarHelper::save();

		if ($isNew)
		{
			JToolbarHelper::cancel();
		}
		else
		{
			JToolbarHelper::custom('order', 'redshop_order32', '', JText::_('COM_REDSHOP_PLACE_ORDER'), false);
			JToolbarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$this->pagination             = $this->get('Pagination');
		$this->detail->user_groups    = RedshopHelperUser::getUserGroups($this->detail->users_info_id);
		$this->lists['shopper_group'] = RedshopHelperShopper_Group::listAll("shopper_group_id", 0, array((int) $this->detail->shopper_group_id));

		$this->lists['tax_exempt']            = JHtml::_('select.booleanlist', 'tax_exempt', 'class="inputbox"', $this->detail->tax_exempt);
		$this->lists['block']                 = JHtml::_('select.booleanlist', 'block', 'class="inputbox"', $this->detail->block);
		$this->lists['tax_exempt_approved']   = JHtml::_('select.booleanlist', 'tax_exempt_approved', 'class="inputbox"', $this->detail->tax_exempt_approved);
		$this->lists['requesting_tax_exempt'] = JHtml::_('select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', $this->detail->requesting_tax_exempt);
		$this->lists['is_company']            = JHtml::_(
			'select.booleanlist',
			'is_company',
			'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
			$this->detail->is_company,
			JText::_('COM_REDSHOP_USER_COMPANY'),
			JText::_('COM_REDSHOP_USER_CUSTOMER')
		);

		$this->lists['sendEmail']               = JHtml::_('select.booleanlist', 'sendEmail', 'class="inputbox"', $this->detail->sendEmail);
		$this->lists['extra_field']             = $extra_field->list_all_field(6, $this->detail->users_info_id, "", "notable");
		$this->lists['customer_field']          = $extra_field->list_all_field(7, $this->detail->users_info_id, "", "notable");
		$this->lists['company_field']           = $extra_field->list_all_field(8, $this->detail->users_info_id, "", "notable");
		$this->lists['shipping_customer_field'] = $extra_field->list_all_field(14, $this->detail->users_info_id, "", "notable");
		$this->lists['shipping_company_field']  = $extra_field->list_all_field(15, $this->detail->users_info_id, "", "notable");

		$countryarray                = RedshopHelperWorld::getCountryList((array) $this->detail);
		$this->detail->country_code  = $countryarray['country_code'];
		$this->lists['country_code'] = $countryarray['country_dropdown'];

		$statearray                = RedshopHelperWorld::getStateList((array) $this->detail);
		$this->lists['state_code'] = $statearray['state_dropdown'];

		$this->request_url = JFactory::getURI()->toString();

		parent::display($tpl);
	}
}
