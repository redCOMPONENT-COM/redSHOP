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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';

class addorder_detailVIEWaddorder_detail extends JView
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
		$extra_field = new extra_field;
		$order_functions = new order_functions;
		$Redconfiguration = new Redconfiguration;

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));

		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');

		$document->addScript('components/' . $option . '/assets/js/json.js');
		$document->addScript('components/' . $option . '/assets/js/validation.js');
		$document->addScript('components/' . $option . '/assets/js/order.js');
		$document->addScript('components/' . $option . '/assets/js/common.js');

		$uri = JFactory::getURI();
		$lists = array();
		$billing = array();
		$shippinginfo = array();
		$model = $this->getModel();
		$detail = $this->get('data');
		$redhelper = new redhelper;

		$payment_lang_list = $redhelper->getPlugins("redshop_payment");

		$language = JFactory::getLanguage();
		$base_dir = JPATH_ADMINISTRATOR;
		$language_tag = $language->getTag();

		for ($l = 0; $l < count($payment_lang_list); $l++)
		{
			$extension = 'plg_redshop_payment_' . $payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
		}

		$err = JRequest::getVar('err', '');
		$shipping_rate_id = JRequest::getVar('shipping_rate_id');
		$user_id = JRequest::getVar('user_id', 0);

		if ($user_id != 0)
		{
			$billing = $order_functions->getBillingAddress($user_id);
			$shippinginfo = $order_functions->getShippingAddress($user_id);
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
			$shipping_users_info_id = JRequest::getVar('shipping_users_info_id', 0);

			if ($shipping_users_info_id != 0)
			{
				for ($o = 0; $o < count($shippinginfo); $o++)
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
		JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER') . ': <small><small>[ ' . JText::_('COM_REDSHOP_NEW') . ' ]</small></small>', 'redshop_order48');

		if ($err == "" && array_key_exists("users_info_id", $billing) && $billing->users_info_id)
		{
			JToolBarHelper::custom('savepay', 'save.png', 'save_f2.png', 'Save + Pay', false);
			JToolBarHelper::custom('save_without_sendmail', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_SAVE_WITHOUT_SEND_ORDERMAIL_LBL'), false);
			JToolBarHelper::save();
		}

		JToolBarHelper::cancel();

		$countryarray = $Redconfiguration->getCountryList((array) $billing, "country_code", "BT");
		$billing->country_code = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray = $Redconfiguration->getStateList((array) $billing, "state_code", "country_code", "BT", 1);
		$lists['state_code'] = $statearray['state_dropdown'];

		$shipping['country_code_ST'] = $shippinginfo[$key]->country_code;
		$countryarray = $Redconfiguration->getCountryList((array) $shipping, "country_code_ST", "ST");
		$shipping['country_code_ST'] = $shippinginfo[$key]->country_code = $countryarray['country_code_ST'];
		$lists['country_code_ST'] = $countryarray['country_dropdown'];
		$statearray = $Redconfiguration->getStateList((array) $shipping, "state_code_ST", "country_code_ST", "ST", 1);
		$lists['state_code_ST'] = $statearray['state_dropdown'];

		$lists['is_company'] = JHTML::_('select.booleanlist', 'is_company',
			'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
			$billing->is_company, JText::_('COM_REDSHOP_USER_COMPANY'),
			JText::_('COM_REDSHOP_USER_CUSTOMER')
		);

		$lists['customer_field'] = $extra_field->list_all_field(7, $billing->users_info_id);
		$lists['company_field'] = $extra_field->list_all_field(8, $billing->users_info_id);
		$lists['shipping_customer_field'] = $extra_field->list_all_field(14, $shippinginfo[0]->users_info_id);
		$lists['shipping_company_field'] = $extra_field->list_all_field(15, $shippinginfo[0]->users_info_id);

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
