<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperAdminShipping');

class RedshopViewCheckout extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$shippinghelper  = new shipping;
		$order_functions = new order_functions;

		$params  = $app->getParams('com_redshop');
		$option  = JRequest::getVar('option');
		$Itemid  = JRequest::getInt('Itemid');
		$issplit = JRequest::getBool('issplit');
		$ccinfo  = JRequest::getInt('ccinfo');
		$task    = JRequest::getCmd('task');

		$model   = $this->getModel('checkout');
		$session = JFactory::getSession();

		if ($issplit != '')
		{
			$session->set('issplit', $issplit);
		}

		$payment_method_id = JRequest::getCmd('payment_method_id');
		$users_info_id     = JRequest::getInt('users_info_id');
		$auth              = $session->get('auth');

		if (empty($users_info_id))
		{
			$users_info_id = $auth['users_info_id'];
		}

		$shipping_rate_id = JRequest::getString('shipping_rate_id');
		$shippingdetail   = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $shipping_rate_id)));

		if (count($shippingdetail) < 4)
		{
			$shipping_rate_id = "";
		}

		$cart = $session->get('cart');

		if ($cart['idx'] < 1)
		{
			$msg = JText::_('COM_REDSHOP_EMPTY_CART');
			$app->Redirect('index.php?option=com_redshop&Itemid=' . $Itemid, $msg);
		}

		if (SHIPPING_METHOD_ENABLE)
		{
			if ($users_info_id < 1)
			{
				$msg  = JText::_('COM_REDSHOP_SELECT_SHIP_ADDRESS');
				$link = 'index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&users_info_id='
					. $users_info_id . '&shipping_rate_id=' . $shipping_rate_id . '&payment_method_id='
					. $payment_method_id;
				$app->redirect($link, $msg);
			}

			if ($shipping_rate_id == '' && $cart['free_shipping'] != 1)
			{
				$msg  = JText::_('LIB_REDSHOP_SELECT_SHIP_METHOD');
				$link = 'index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&users_info_id='
					. $users_info_id . '&shipping_rate_id=' . $shipping_rate_id . '&payment_method_id='
					. $payment_method_id;
				$app->redirect($link, $msg);
			}
		}

		if ($payment_method_id == '')
		{
			$msg  = JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD');
			$link = 'index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&users_info_id='
				. $users_info_id . '&shipping_rate_id=' . $shipping_rate_id . '&payment_method_id='
				. $payment_method_id;
			$app->redirect($link, $msg);
		}

		$paymentinfo     = $order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentinfo     = $paymentinfo[0];
		$paymentpath     = JPATH_SITE . '/plugins/redshop_payment/' . $paymentinfo->element . '/' . $paymentinfo->element . '.xml';
		$paymentparams   = new JRegistry($paymentinfo->params);
		$is_creditcard   = $paymentparams->get('is_creditcard', '');
		$is_subscription = $paymentparams->get('is_subscription', 0);

		if (@$is_creditcard == 1)
		{
			JHtml::script('com_redshop/credit_card.js', false, true);
		}

		if ($is_subscription)
		{
			$subscription_id = $session->set('subscription_id', $subscription_id);
		}

		$this->cart = $cart;
		$this->users_info_id = $users_info_id;
		$this->shipping_rate_id = $shipping_rate_id;
		$this->payment_method_id = $payment_method_id;
		$this->is_creditcard = $is_creditcard;

		if ($task != '')
		{
			$tpl = $task;
		}

		parent::display($tpl);
	}
}
