<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php';

class plgRedshop_paymentrs_payment_paymill extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_paymill(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_paymill');
		$this->_params = new JRegistry($this->_plugin->params);
	}

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

		$mainframe = JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'creditcardform.php';

		include $paymentpath;
	}

	function getCredicardForm($element, $data)
	{
		$user = JFactory::getUser();
		$user_id = $user->id;
		$Itemid = JRequest::getInt('Itemid');
		$paymill_public_key = $this->_params->get('paymill_public_key', '0');
		$paymill_private_key = $this->_params->get('paymill_private_key', '0');
		$cart_data = '<script type="text/javascript">
					var PAYMILL_PUBLIC_KEY = "<?php echo $paymill_public_key;?>";
			     </script>
			     <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap.no-responsive.no-icons.min.css">
			     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
			     <script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
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

					      <form id="payment-form" method="POST" action="index.php?option=com_redshop&view=checkout&format=final&stap=2&oid=' . (int) $data['order_id'] . '&Itemid=' . $Itemid . '">

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
	function getOrderAndCcdata($element, $data)
	{
		$mainframe = JFactory::getApplication();

		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$paymill_public_key = $this->_params->get('paymill_public_key', '0');
		$paymill_private_key = $this->_params->get('paymill_private_key', '0');

		$order_functions = new order_functions;
		$orderDetails = $order_functions->getOrderDetails($data['order_id']);
		$order_amount = number_format($orderDetails->order_total, 2, '.', '') * 100;

		$session = JFactory::getSession();
		$Itemid = JRequest::getVar('Itemid');
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'lib/Services/Paymill/Transactions.php';
		include $paymentpath;

		if ($token = $data['paymillToken'])
		{
			// Require "Services/Paymill/Transactions.php";
			$transactionsObject = new Services_Paymill_Transactions($paymill_private_key, "https://api.paymill.com/v2/");
			$params = array(
				'amount'      => $order_amount, // E.g. "15" for 0.15 EUR!
				'currency'    => CURRENCY_CODE, // ISO 4217
				'token'       => $token,
				'description' => 'Test Transaction'
			);

			$transaction = $transactionsObject->create($params);

			$session->set('paymillresult', $transaction);
		}

		$redirect_url = JRoute::_("index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paymill&Itemid=" . $Itemid . "&orderid=" . $data['order_id']);
		$mainframe->Redirect($redirect_url);
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
		$verify_status = $this->_params->get('verify_status');
		$invalid_status = $this->_params->get('invalid_status');
		$cancel_status = $this->_params->get('cancel_status');

		if (isset($paymillresult['error']) && $paymillresult['error'])
		{
			foreach ($paymillresult['error'] as $key => $value)
			{
				$error_message = $key . ":" . $value;
			}

			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JTEXT::_('COM_REDSHOP_ORDER_NOT_PLACED');
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
