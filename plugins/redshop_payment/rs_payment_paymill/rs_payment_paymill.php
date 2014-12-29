<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperAdminConfiguration');

class plgRedshop_paymentrs_payment_paymill extends JPlugin
{
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/creditcardform.php';

		include $paymentpath;
	}

	function getCredicardForm($element, $data)
	{
		$user = JFactory::getUser();
		$user_id = $user->id;
		$Itemid = JRequest::getInt('Itemid');
		$paymill_public_key = $this->params->get('paymill_public_key', '0');
		$paymill_private_key = $this->params->get('paymill_private_key', '0');
		$cart_data = '';

		if (version_compare(JVERSION, '3.0', '<'))
		{
			JHtml::_('redshopjquery.framework');
			$cart_data .= '<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
							<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap.no-responsive.no-icons.min.css">';
		}
		else
		{
			JHtml::_('bootstrap.framework');
			JHtml::_('bootstrap.loadCss');
		}

		$cart_data .= '<script type="text/javascript">
					var PAYMILL_PUBLIC_KEY = "<?php echo $paymill_public_key;?>";
			     </script>
			     <script src="https://bridge.paymill.com/"></script>';

		$cart_data .= '<div class="container span8"><div class="controls">
					      <div class="span4">
						<div class="payment-errors text-error"></div>
					      </div>
					    </div>
					    <div class="clearfix"></div>
					    <div class="well span5">
					      <div class="controls controls-row">
						<div class="btn-group span4">
						  <button id="btn-paymenttype-cc" class="paymenttype btn btn-primary disabled" value="cc">Credit card</button>

						</div><br /><br />
					      </div>

					      <form id="payment-form" method="POST" action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=' . (int) $data['order_id'] . '&Itemid=' . $Itemid . '">

						<div class="clearfix"></div>

						<div id="payment-form-cc">
						  <input class="card-amount" type="hidden" value="' . $data['order']->order_total . '"/>
						  <input class="card-currency" type="hidden" value="' . CURRENCY_CODE . '"/>

						  <div class="controls controls-row">
						    <div class="span3"><label>Card number</label>
							<input class="card-number span3" type="text" size="20" value=""/>
						    </div>
						    <div class="span1"><label>CVC</label>
							<input class="card-cvc span1" type="text" size="4" value=""/>
						    </div>
						  </div>

						  <div class="controls">
						    <div class="span4">
						      <label>Card holder</label>
						      <input class="card-holdername span4" type="text" size="20" value=""/>
						    </div>
						  </div>

						  <div class="controls">
						    <div class="span3">
						      <label>Valid until (MM/YYYY)</label>
						      <input class="card-expiry-month span1" type="text" size="2" value=""/>
						      <span> / </span>
						      <input class="card-expiry-year span1" type="text" size="4" value=""/>
						    </div>
						  </div>
						</div>

						<div id="payment-form-elv" style="display: none;">
						  <div class="controls">
						    <div class="span3">
						      <label>Account holder</label>
						      <input class="elv-holdername span3" type="text" size="20" value=""/>
						    </div>
						  </div>

						  <div class="controls controls-row">
						    <div class="span3"><label>Account number</label>
							<input class="elv-account span3" type="text" size="20" value="1234567890"/>
						    </div>
						  </div>

						  <div class="controls">
						    <div class="span3">
						      <label>Bank code</label>
						      <input class="elv-bankcode span3" type="text" size="20" value="40050150"/>
						    </div>
						  </div>
						</div>

						<div class="controls">
						  <div class="span3">
						    <button class="submit-button btn btn-primary" type="submit">Buy now</button>
						  </div>
						</div>

					    </div>
					    <!-- END: Payment form -->
					  </div>';

		$cart_data .= '<input type="hidden" name="option" value="com_redshop" />';
		$cart_data .= '<input type="hidden" name="Itemid" value="' . $Itemid . '" />';
		$cart_data .= '<input type="hidden" name="ccinfo" value="1" />';
		$cart_data .= '<input type="hidden" name="payment_method_id" value="' . $this->payment_method_id . '" />';
		$cart_data .= '<input type="hidden" name="order_id" value="' . $data['order_id'] . '" />';
		$cart_data .= '</form>';

		echo eval("?>" . $cart_data . "<?php ");
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function getOrderAndCcdata($element, $data)
	{
		$app = JFactory::getApplication();

		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$paymill_public_key = $this->params->get('paymill_public_key', '0');
		$paymill_private_key = $this->params->get('paymill_private_key', '0');

		$order_functions = new order_functions;
		$orderDetails = $order_functions->getOrderDetails($data['order_id']);
		$order_amount = number_format($orderDetails->order_total, 2, '.', '') * 100;

		$session = JFactory::getSession();
		$Itemid = JRequest::getVar('Itemid');
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/lib/Services/Paymill/Transactions.php';
		include $paymentpath;

		if ($token = $data['paymillToken'])
		{
			// Require "Services/Paymill/Transactions.php";
			$transactionsObject = new Services_Paymill_Transactions($paymill_private_key, "https://api.paymill.com/v2/");
			$params = array(
				'amount'      => $order_amount, // E.g. "15" for 0.15 EUR!
				'currency'    => CURRENCY_CODE, // ISO 4217
				'token'       => $token,
				'description' => 'Order: ' . $data['order_id']
			);

			$transaction = $transactionsObject->create($params);

			$session->set('paymillresult', $transaction);
		}

		$redirect_url = JRoute::_("index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paymill&Itemid=" . $Itemid . "&orderid=" . $data['order_id']);
		$app->redirect($redirect_url);
	}

	function onNotifyPaymentrs_payment_paymill($element, $request)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$request = JRequest::get('request');
		$Itemid = $request["Itemid"];
		$order_id = $request['orderid'];

		$session = JFactory::getSession();
		$paymillresult = $session->get('paymillresult');
		$tid = $paymillresult['id'];

		$paymentparams = new JRegistry($paymentinfo->params);
		$verify_status = $this->params->get('verify_status');
		$invalid_status = $this->params->get('invalid_status');
		$cancel_status = $this->params->get('cancel_status');

		if ($paymillresult['error'] != '')
		{
			$error_message = $paymillresult['error'];
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $error_message;
			$values->msg = $error_message;
		}
		else
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function onCapture_Paymentrs_payment_paymill($element, $data)
	{
		return;
	}
}
