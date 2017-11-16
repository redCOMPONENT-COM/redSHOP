<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * 2Checkout Payment plugin for redSHOP and other joomla ecommerce component
 *
 * @package     RedSHOP.Payment
 * @subpackage  Plugin
 *
 * @since       1.0
 */
class PlgRedshop_PaymentRs_Payment_2checkout extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $data     Order Information
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_2checkout')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app         = JFactory::getApplication();
		$orderHelper = order_functions::getInstance();
		$orderItems  = $orderHelper->getOrderItemDetail($data['order_id']);
		$Itemid      = $app->input->getInt('Itemid');

		// Authnet vars to send
		$formdata = array(
			'sid'                => $this->params->get('vendor_id'),
			'cart_order_id'      => 'Order Id:' . $data['order_id'],
			'merchant_order_id'  => $data['order_id'],

			// Customer Name and Billing Address
			'card_holder_name'   => $data['billinginfo']->firstname . ' ' . $data['billinginfo']->lastname,
			'street_address'     => $data['billinginfo']->address,
			'city'               => $data['billinginfo']->city,
			'state'              => $data['billinginfo']->state_code,
			'zip'                => $data['billinginfo']->zipcode,
			'country'            => $data['billinginfo']->country_code,
			'email'              => $data['billinginfo']->user_email,
			'phone'              => $data['billinginfo']->phone,

			// Customer Shipping Address
			'ship_name'          => $data['shippinginfo']->firstname . ' ' . $data['shippinginfo']->lastname,
			'ship_steet_address' => $data['shippinginfo']->address,
			'ship_city'          => $data['shippinginfo']->city,
			'ship_state'         => $data['shippinginfo']->state_code,
			'ship_zip'           => $data['shippinginfo']->zipcode,
			'ship_country'       => $data['shippinginfo']->country_code,
			'return_url'         => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_2checkout&Itemid=$Itemid&orderid=" . $data['order_id'],
			'x_receipt_link_url' => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_2checkout&Itemid=$Itemid&orderid=" . $data['order_id'],
			'id_type'            => 1,
			'c_tangible_1'       => 'Y',
			'total'              => number_format($data['carttotal'], 2, '.', '')
		);

		for ($p = 0, $n = count($orderItems); $p < $n; $p++)
		{
			$formdata['c_prod_' . ($p + 1)]        = '1,' . $orderItems[$p]->product_quantity;
			$formdata['c_name_' . ($p + 1)]        = $orderItems[$p]->order_item_name;
			$formdata['c_price_' . ($p + 1)]       = $orderItems[$p]->product_item_price;
			$formdata['c_description_' . ($p + 1)] = '';
		}

		// Live 2checkout api url
		$checkoutUrl = 'https://www.2checkout.com/checkout/purchase';

		if ((bool) $this->params->get('is_test'))
		{
			$formdata['demo'] = 'Y';

			// Test mode 2checkout api url
			$checkoutUrl = 'https://sandbox.2checkout.com/checkout/purchase';
		}

		$app->redirect($checkoutUrl . '/?' . JUri::buildQuery($formdata));
		$app->close();
	}

	/**
	 * This method will trigger when notify payment task will execute
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $request  Request data from payment gateway
	 *
	 * @return  array   Return array of information about payment status and codes.
	 */
	public function onNotifyPaymentrs_payment_2checkout($element, $request)
	{
		if ($element != 'rs_payment_2checkout')
		{
			return;
		}

		$app = JFactory::getApplication();

		// Load plugin language
		JPlugin::loadLanguage('com_redshop');

		// Get value from plugin params file
		$stringForHash = $this->params->get('secret_words');

		// This should be YOUR vendor number
		$stringForHash .= $app->input->getString('sid');

		// Append the order number for live mode
		if ($this->params->get('is_test'))
		{
			$stringForHash .= 1;
		}
		else
		{
			$stringForHash .= $app->input->getString('order_number');
		}

		// Append the sale total
		$stringForHash .= $app->input->getString('total');

		// Get a md5 hash of the string, uppercase it to match the returned key
		$hashCode = strtoupper(md5($stringForHash));

		// Now validate on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		if ($app->input->getString('key') === $hashCode)
		{
			$values->order_status_code         = $this->params->get('verify_status');
			$values->order_payment_status_code = 'Paid';
			$values->transaction_id            = $app->input->getString('invoice_id');
			$values->order_id                  = $app->input->getInt('merchant_order_id', 0);
			$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code         = $this->params->get('invalid_status');
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		return $values;
	}
}
