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
	/**
	 * @param   string  $tpl  Template layout
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function display($tpl = null)
	{
		$app       = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$user      = JFactory::getUser();
		$session   = JFactory::getSession();
		$language = JFactory::getLanguage();

		$model     = $this->getModel('checkout');

		$itemId    = $input->getInt('Itemid');

		$redhelper = redhelper::getInstance();
		$field     = extraField::getInstance();

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Load Shipping language file
		// @TODO Move ot helper like payment
		$shippingPlugins = RedshopHelperUtility::getPlugins("redshop_shipping", 1);
		$baseDir        = JPATH_ADMINISTRATOR;

		for ($l = 0, $ln = count($shippingPlugins); $l < $ln; $l++)
		{
			$extension = 'plg_redshop_shipping_' . $shippingPlugins[$l]->element;
			$language->load($extension, $baseDir);
		}

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
			$link = 'index.php?option=com_redshop&Itemid=' . $itemId;
			$app->redirect(JRoute::_($link), JText::_('COM_REDSHOP_EMPTY_CART'));
		}

		$lists = array();
		$isCompany = $input->getInt('is_company', 0);

		// Toggler settings
		$openToStretcher = 0;

		if ($isCompany == 1 || Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$openToStretcher = 1;
		}

		// Allow registration type settings
		$lists['allowCustomer'] = "";
		$lists['allowCompany']  = "";

		if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 1)
		{
			$lists['allowCompany'] = "style='display:none;'";
			$openToStretcher       = 0;
		}
		elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$lists['allowCustomer'] = "style='display:none;'";
			$openToStretcher        = 1;
		}

		$lists['is_company'] = ($openToStretcher == 1 || ($isCompany == 1)) ? 1 : 0;

		if ($user->id || $auth['users_info_id'] > 0)
		{
			$cart = $session->get('cart');

			if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') == 1 && !array_key_exists("quotation_id", $cart))
			{
				$app->redirect(JRoute::_('index.php?option=com_redshop&view=quotation&Itemid=' . $itemId));
			}

			$users_info_id     = $input->getInt('users_info_id');
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
					$app->redirect(JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $itemId));
				}
			}

			$shipping_rate_id = $input->getInt('shipping_rate_id');
			$element          = $input->getCmd('payment_method_id');
			$ccinfo           = $input->getInt('ccinfo');

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

			$this->users_info_id    = $users_info_id;
			$this->shipping_rate_id = $shipping_rate_id;
			$this->element          = $element;
			$this->ccinfo           = $ccinfo;
			$this->order_subtotal   = $subtotal;
			$this->ordertotal       = $cart['total'];
		}
		else
		{
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

		if (($user->id || $auth['users_info_id'] > 0) && Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
		{
			$this->setLayout('onestepcheckout');
		}

		$this->lists = $lists;
		parent::display($tpl);
	}
}
