<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCheckout extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$Itemid  = $app->input->getInt('Itemid');
		$issplit = $app->input->getBool('issplit');
		$ccinfo  = $app->input->getInt('ccinfo');
		$task    = $app->input->getCmd('task');

		/** @var RedshopModelCheckout $model */
		$model   = $this->getModel('checkout');
		$session = JFactory::getSession();

		if ($issplit != '')
		{
			$session->set('issplit', $issplit);
		}

		$payment_method_id = $app->input->getCmd('payment_method_id');
		$usersInfoId     = $app->input->getInt('users_info_id');
		$auth              = $session->get('auth');

		if (empty($usersInfoId))
		{
			$usersInfoId = $auth['users_info_id'];
		}

		$shipping_rate_id = $app->input->getString('shipping_rate_id');
		$shippingdetail   = Redshop\Shipping\Rate::decrypt($shipping_rate_id);

		if (count($shippingdetail) < 4)
		{
			$shipping_rate_id = "";
		}

		$cart = $session->get('cart');

		if ($cart['idx'] < 1)
		{
			$msg = JText::_('COM_REDSHOP_EMPTY_CART');
			$app->Redirect(JRoute::_('index.php?option=com_redshop&Itemid=' . $Itemid), $msg);
		}

		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if ($usersInfoId < 1)
			{
				$msg  = JText::_('COM_REDSHOP_SELECT_SHIP_ADDRESS');
				$link = 'index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&users_info_id='
					. $usersInfoId . '&shipping_rate_id=' . $shipping_rate_id . '&payment_method_id='
					. $payment_method_id;
				$app->redirect(JRoute::_($link), $msg);
			}

			if ($shipping_rate_id == '' && $cart['free_shipping'] != 1)
			{
				$msg  = JText::_('LIB_REDSHOP_SELECT_SHIP_METHOD');
				$link = 'index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&users_info_id='
					. $usersInfoId . '&shipping_rate_id=' . $shipping_rate_id . '&payment_method_id='
					. $payment_method_id;
				$app->redirect(JRoute::_($link), $msg);
			}
		}

		if ($payment_method_id == '')
		{
			$msg  = JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD');
			$link = 'index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&users_info_id='
				. $usersInfoId . '&shipping_rate_id=' . $shipping_rate_id . '&payment_method_id='
				. $payment_method_id;
			$app->redirect(JRoute::_($link), $msg, 'error');
		}

		$paymentinfo     = RedshopHelperOrder::getPaymentMethodInfo($payment_method_id);
		$paymentinfo     = $paymentinfo[0];
		$paymentpath     = JPATH_SITE . '/plugins/redshop_payment/' . $paymentinfo->element . '/' . $paymentinfo->element . '.xml';
		$paymentparams   = new JRegistry($paymentinfo->params);
		$is_creditcard   = $paymentparams->get('is_creditcard', '');
		$is_subscription = $paymentparams->get('is_subscription', 0);

		if (@$is_creditcard == 1)
		{
			/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.creditcard.min.js', false, true);
		}

		if ($is_subscription)
		{
			$subscription_id = $session->set('subscription_id', $subscription_id);
		}

		$this->cart = $cart;
		$this->users_info_id = $usersInfoId;
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
