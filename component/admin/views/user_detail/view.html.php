<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * User Detail view
 *
 * @package     Redshop.Site
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewUser_Detail extends RedshopViewAdmin
{
	/**
	 * @var  JEventDispatchers
	 */
	public $dispatcher;

	/**
	 * @var  object
	 */
	public $detail;

	/**
	 * @var  array
	 */
	public $lists;

	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		RedshopHelperJs::init();

		JPluginHelper::importPlugin('redshop_product');
		$this->dispatcher = RedshopHelperUtility::getDispatcher();

		/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/json.min.js', false, true);
		/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.validation.min.js', false, true);

		$this->setLayout('default');

		$this->lists  = array();
		$this->detail = $this->get('data');

		$isNew = ($this->detail->users_info_id < 1);
		$text  = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		if (JFactory::getApplication()->input->getString('shipping'))
		{
			JToolbarHelper::title(
				JText::_('COM_REDSHOP_USER_SHIPPING_DETAIL') . ': <small><small>[ '
				. $text . ' ]</small></small>', 'user redshop_user48'
			);
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

		$this->lists['tax_exempt'] = JHtml::_('select.booleanlist', 'tax_exempt', 'class="inputbox"', $this->detail->tax_exempt);
		$this->lists['block']      = JHtml::_('select.booleanlist', 'block', 'class="inputbox"', $this->detail->block);

		$this->lists['tax_exempt_approved'] = JHtml::_(
			'select.booleanlist', 'tax_exempt_approved', 'class="inputbox"', $this->detail->tax_exempt_approved
		);

		$this->lists['requesting_tax_exempt'] = JHtml::_(
			'select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', $this->detail->requesting_tax_exempt
		);

		$this->lists['is_company'] = JHtml::_(
			'select.booleanlist',
			'is_company',
			'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
			$this->detail->is_company,
			JText::_('COM_REDSHOP_USER_COMPANY'),
			JText::_('COM_REDSHOP_USER_CUSTOMER')
		);

		$this->lists['sendEmail'] = JHtml::_('select.booleanlist', 'sendEmail', 'class="inputbox"', $this->detail->sendEmail);

		$this->lists['extra_field'] = RedshopHelperExtrafields::listAllField(
			RedshopHelperExtrafields::SECTION_USER_INFORMATIONS, $this->detail->users_info_id
		);

		$this->lists['customer_field'] = RedshopHelperExtrafields::listAllField(
			RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS, $this->detail->users_info_id
		);

		$this->lists['company_field'] = RedshopHelperExtrafields::listAllField(
			RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS, $this->detail->users_info_id
		);

		$this->lists['shipping_customer_field'] = RedshopHelperExtrafields::listAllField(
			RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS, $this->detail->users_info_id
		);

		$this->lists['shipping_company_field'] = RedshopHelperExtrafields::listAllField(
			RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS, $this->detail->users_info_id
		);

		$countries                   = RedshopHelperWorld::getCountryList((array) $this->detail);
		$this->detail->country_code  = $countries['country_code'];
		$this->lists['country_code'] = $countries['country_dropdown'];

		$states                    = RedshopHelperWorld::getStateList((array) $this->detail);
		$this->lists['state_code'] = $states['state_dropdown'];

		$this->request_url = JUri::getInstance()->toString();

		parent::display($tpl);
	}
}
