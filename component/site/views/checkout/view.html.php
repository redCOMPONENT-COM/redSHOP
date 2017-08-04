<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCheckout extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model     = $this->getModel('checkout');
		$Itemid    = $app->input->getInt('Itemid');
		$user      = JFactory::getUser();
		$redhelper = redhelper::getInstance();
		$field     = extraField::getInstance();
		$session   = JFactory::getSession();

		$language          = JFactory::getLanguage();

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Load Shipping language file
		$shippingPlugins = RedshopHelperUtility::getPlugins("redshop_shipping", 1);
		$base_dir        = JPATH_ADMINISTRATOR;

		for ($l = 0, $ln = count($shippingPlugins); $l < $ln; $l++)
		{
			$extension = 'plg_redshop_shipping_' . $shippingPlugins[$l]->element;
			$language->load($extension, $base_dir);
		}

		JHtml::script('system/validate.js', true, false);
		JHtml::_('redshopjquery.framework');
		JHtml::script('com_redshop/jquery.validate.js', false, true);
		JHtml::script('com_redshop/common.js', false, true);
		JHtml::script('com_redshop/jquery.metadata.js', false, true);
		JHtml::script('com_redshop/registration.js', false, true);
		JHtml::stylesheet('com_redshop/validation.css', array(), true);
		JHtml::script('com_redshop/redbox.js', false, true);

		JPluginHelper::importPlugin('redshop_vies_registration');

		$cart = $session->get('cart');
		$auth = $session->get('auth');

		if (!is_array($auth))
		{
			$auth['users_info_id'] = 0;
			$session->set('auth', $auth);
			$auth = $session->get('auth');
		}

		if ($cart['idx'] < 1)
		{
			$msg  = JText::_('COM_REDSHOP_EMPTY_CART');
			$link = 'index.php?option=com_redshop&Itemid=' . $Itemid;
			$app->redirect(JRoute::_($link), $msg);
		}

		$lists = array();

		$jInput = $app->input;
		$isCompany = $jInput->getInt('is_company', 0);

		// Toggler settings
		$openToStretcher = 0;

		if ($isCompany == 1 || Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$openToStretcher = 1;
		}

		// Allow registration type settings
		$lists['allowCustomer'] = "";
		$lists['allowCompany'] = "";

		if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 1)
		{
			$lists['allowCompany'] = "style='display:none;'";
			$openToStretcher = 0;
		}
		elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$lists['allowCustomer'] = "style='display:none;'";
			$openToStretcher = 1;
		}

		$lists['is_company'] = ($openToStretcher == 1 || ($isCompany == 1)) ? 1 : 0;

		if ($user->id || $auth['users_info_id'] > 0)
		{
			$cart = $session->get('cart');

			if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') == 1 && !array_key_exists("quotation_id", $cart))
			{
				$app->redirect(JRoute::_('index.php?option=com_redshop&view=quotation&Itemid=' . $Itemid));
			}

			$users_info_id     = $app->input->getInt('users_info_id');
			$billingaddresses  = $model->billingaddresses();
			$shippingaddresses = $model->shippingaddresses();

			if (!$users_info_id)
			{
				if ((!isset($users_info_id) || $users_info_id == 0) && count($shippingaddresses) > 0)
				{
					$users_info_id = $shippingaddresses[0]->users_info_id;
				}
				elseif ((!isset($users_info_id) || $users_info_id == 0) && count($billingaddresses) > 0)
				{
					$users_info_id = $billingaddresses->users_info_id;
				}
				else
				{
					$app->redirect(JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $Itemid));
				}
			}

			$shipping_rate_id = $app->input->getInt('shipping_rate_id');
			$element          = $app->input->getCmd('payment_method_id');
			$ccinfo           = $app->input->getInt('ccinfo');

			if (!isset($cart['voucher_discount']))
			{
				$cart['voucher_discount'] = 0;
			}

			if (!isset($cart['coupon_discount']))
			{
				$cart['coupon_discount'] = 0;
			}

			if (!isset($cart['product_subtotal']))
			{
				$cart['product_subtotal'] = 0;
			}

			if (!isset($cart['total']))
			{
				$cart['total'] = 0;
			}

			$total_discount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
			$subtotal       = (Redshop::getConfig()->get('SHIPPING_AFTER') == 'total') ? $cart['product_subtotal'] - $total_discount : $cart['product_subtotal'];

			$this->shipping_rate_id = $shipping_rate_id;
			$this->element = $element;
			$this->ccinfo = $ccinfo;
			$this->order_subtotal = $subtotal;
			$this->ordertotal = $cart['total'];
		}
		else
		{
			$users_info_id = 0;

			if ($lists['is_company'])
			{
				// Field_section Company
				$lists['extra_field_company'] = $field->list_all_field(8);
			}
			else
			{
				// Field_section Customer
				$lists['extra_field_user'] = $field->list_all_field(7);
			}

			$lists['shipping_company_field']  = $field->list_all_field(15, 0, 'billingRequired valid');
			$lists['shipping_customer_field'] = $field->list_all_field(14, 0, 'billingRequired valid');
		}

		if (Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
		{
			$this->setLayout('onestepcheckout');
		}

		$this->users_info_id = $users_info_id;
		$this->lists = $lists;
		parent::display($tpl);
	}
}
