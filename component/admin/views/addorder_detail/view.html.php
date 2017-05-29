<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Add Order detail view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewAddorder_Detail extends RedshopViewAdmin
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

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/order.js');
		$document->addScript('components/com_redshop/assets/js/common.js');

		$uri          = JUri::getInstance();
		$lists        = array();
		$billing      = array();
		$shippinginfo = array();
		$model        = $this->getModel();
		$detail       = $this->get('data');

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		$err = $input->get('err', '');
		$shipping_rate_id = $input->getInt('shipping_rate_id', 0);
		$user_id = $input->getInt('user_id', 0);

		if ($user_id != 0)
		{
			$billing      = RedshopHelperOrder::getBillingAddress($user_id);
			$shippinginfo = RedshopHelperOrder::getShippingAddress($user_id);
		}
		else
		{
			$billing = $model->setBilling();
		}

		$shipping_country = 0;
		$shipping_state = 0;
		$key = 0;
		$shippingop = array();
		$shippingop[0] = new stdClass;
		$shippingop[0]->users_info_id = 0;
		$shippingop[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (count($shippinginfo) > 0)
		{
			$shipping_users_info_id = $input->getInt('shipping_users_info_id', 0);

			if ($shipping_users_info_id != 0)
			{
				for ($o = 0, $on = count($shippinginfo); $o < $on; $o++)
				{
					if ($shippinginfo[$o]->users_info_id == $shipping_users_info_id)
					{
						$key = $o;
						break;
					}
				}

				$shipping_country = $shippinginfo[$key]->country_code;
				$shipping_state = $shippinginfo[$key]->state_code;
			}

			$shippingop = array_merge($shippingop, $shippinginfo);
			$billisship = $shippinginfo[$key]->billisship = ($shipping_users_info_id) ? 0 : 1;
		}
		else
		{
			$shippinginfo[0] = $model->setShipping();
			$shipping_users_info_id = $shippinginfo[0]->users_info_id = 0;
			$billisship = $shippinginfo[0]->billisship;
		}

		$shdisable = ($billisship) ? "disabled" : "";

		$detail->user_id = $user_id;
		$lists['shippinginfo_list'] = JHTML::_('select.genericlist', $shippingop, 'shipp_users_info_id',
			'class="inputbox" ' . $shdisable . ' onchange="getShippinginfo(this.value, ' . $billing->is_company . ');" ',
			'users_info_id', 'text', $shipping_users_info_id
		);

		$payment_detail = $this->get('payment');
		JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER') . ': <small><small>[ ' . JText::_('COM_REDSHOP_NEW') . ' ]</small></small>', 'pencil-2 redshop_order48');

		if ($err == "" && array_key_exists("users_info_id", $billing) && $billing->users_info_id)
		{
			JToolBarHelper::custom('savepay', 'save.png', 'save_f2.png', 'Save + Pay', false);
			JToolBarHelper::custom('save_without_sendmail', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_SAVE_WITHOUT_SEND_ORDERMAIL_LBL'), false);
			JToolBarHelper::save();
		}

		JToolBarHelper::custom('validateUserDetail', 'apply.png', 'apply_f2.png', JText::_('COM_REDSHOP_SAVE_USER_INFORMATION'), false);
		JToolBarHelper::cancel();

		$countryarray          = RedshopHelperWorld::getCountryList((array) $billing);
		$billing->country_code = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];

		$statearray          = RedshopHelperWorld::getStateList((array) $billing, "state_code", "country_code", "BT");
		$lists['state_code'] = $statearray['state_dropdown'];

		$shipping['country_code_ST'] = $shippinginfo[$key]->country_code;
		$countryarray = RedshopHelperWorld::getCountryList((array) $shipping, "country_code_ST", "ST", '', 'state_code_ST');
		$shipping['country_code_ST'] = $shippinginfo[$key]->country_code = $countryarray['country_code_ST'];

		$shipping['state_code_ST'] = $shippinginfo[$key]->state_code;
		$lists['country_code_ST']  = $countryarray['country_dropdown'];

		$statearray = RedshopHelperWorld::getStateList((array) $shipping, "state_code_ST", "ST");
		$lists['state_code_ST'] = $statearray['state_dropdown'];

		$lists['is_company'] = JHTML::_('select.booleanlist', 'is_company',
			'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
			$billing->is_company, JText::_('COM_REDSHOP_USER_COMPANY'),
			JText::_('COM_REDSHOP_USER_CUSTOMER')
		);

		$lists['customer_field']          = RedshopHelperExtrafields::listAllField(7, $billing->users_info_id);
		$lists['company_field']           = RedshopHelperExtrafields::listAllField(8, $billing->users_info_id);
		$lists['shipping_customer_field'] = RedshopHelperExtrafields::listAllField(14, $shippinginfo[0]->users_info_id);
		$lists['shipping_company_field']  = RedshopHelperExtrafields::listAllField(15, $shippinginfo[0]->users_info_id);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->billing = $billing;
		$this->shipping = $shippinginfo[$key];
		$this->shipping_users_info_id = $shipping_users_info_id;
		$this->payment_detail = $payment_detail;
		$this->shipping_rate_id = $shipping_rate_id;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
