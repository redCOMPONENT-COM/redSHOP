<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * BrainTree payment class
 *
 * @package  Redshop.Plugin
 *
 * @since    2.0.0
 */
class PlgRedshop_Paymentrs_Payment_Braintree extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object $subject    The object to observe
	 * @param   array  $config     An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_payment_rs_payment_braintree', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * @param   string $element Name of the payment plugin
	 * @param   array  $data    Cart Information
	 *
	 * @return  object            Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		include __DIR__ . '/library/creditcardform.php';
	}

	/**
	 * Method for get credit card form
	 *
	 * @param   string $element Name of payment plugin
	 * @param   array  $data    Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function getCredicardForm($element, $data)
	{
		$user            = JFactory::getUser();
		$userId          = $user->id;
		$input           = JFactory::getApplication()->input;
		$menuItemId      = $input->getInt('Itemid');
		$newUser         = true;
		$storeInVault    = $this->params->get('store_in_vault', '0');
		$paymentMethodId = $input->getCmd('payment_method_id', '');

		if ($storeInVault)
		{
			$userVaultReference = $this->getUser_BraintreeVault_ref($userId);

			if ($userVaultReference != "")
			{
				$newUser = false;
			}
		}

		if ($newUser)
		{
			$cartData = '<form action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid='
				. (int) $data['order_id'] . '&Itemid=' . $menuItemId
				. '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">';

			$creditCardSession = JFactory::getSession()->get('ccdata');

			$availableCreditCards                  = array();
			$availableCreditCards['VISA']          = new stdClass;
			$availableCreditCards['VISA']->img     = 'visa.jpg';
			$availableCreditCards['MC']            = new stdClass;
			$availableCreditCards['MC']->img       = 'master.jpg';
			$availableCreditCards['amex']          = new stdClass;
			$availableCreditCards['amex']->img     = 'blue.jpg';
			$availableCreditCards['maestro']       = new stdClass;
			$availableCreditCards['maestro']->img  = 'mastero.jpg';
			$availableCreditCards['jcb']           = new stdClass;
			$availableCreditCards['jcb']->img      = 'jcb.jpg';
			$availableCreditCards['diners']        = new stdClass;
			$availableCreditCards['diners']->img   = 'dinnersclub.jpg';
			$availableCreditCards['discover']      = new stdClass;
			$availableCreditCards['discover']->img = 'discover.jpg';

			$months   = array();
			$months[] = JHtml::_('select.option', '0', JText::_('PLG_RS_PAYMENT_BRAINTREE_MONTH'));
			$months[] = JHtml::_('select.option', '01', 1);
			$months[] = JHtml::_('select.option', '02', 2);
			$months[] = JHtml::_('select.option', '03', 3);
			$months[] = JHtml::_('select.option', '04', 4);
			$months[] = JHtml::_('select.option', '05', 5);
			$months[] = JHtml::_('select.option', '06', 6);
			$months[] = JHtml::_('select.option', '07', 7);
			$months[] = JHtml::_('select.option', '08', 8);
			$months[] = JHtml::_('select.option', '09', 9);
			$months[] = JHtml::_('select.option', '10', 10);
			$months[] = JHtml::_('select.option', '11', 11);
			$months[] = JHtml::_('select.option', '12', 12);

			$creditCard         = array();
			$acceptedCreditCard = $this->params->get("accepted_credict_card");

			if ($acceptedCreditCard != "")
			{
				$creditCard = $acceptedCreditCard;
			}

			$cardInforHtml = '<fieldset class="adminform"><legend>'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_INFORMATION')
				. '</legend>';
			$cardInforHtml .= '<table class="admintable">';
			$cardInforHtml .= '<tr><td colspan="2" align="right" nowrap="nowrap">';
			$cardInforHtml .= '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
			$cardInforHtml .= '<tr>';

			for ($ic = 0; $ic < count($creditCard); $ic++)
			{
				$cardInforHtml .= '<td align="center"><img src="'
					. REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $availableCreditCards[$creditCard[$ic]]->img . '" alt="" border="0" /></td>';
			}

			$cardInforHtml .= '</tr><tr>';

			for ($ic = 0; $ic < count($creditCard); $ic++)
			{
				$value   = $creditCard[$ic];
				$checked = "";

				if (!isset($creditCardSession['creditcard_code']) && $ic == 0)
				{
					$checked = "checked";
				}
				elseif (isset($creditCardSession['creditcard_code']))
				{
					$checked = ($creditCardSession['creditcard_code'] == $value) ? "checked" : "";
				}

				$cardInforHtml .= '<td align="center"><input type="radio" name="creditcard_code" value="' . $value . '" ' . $checked . ' /></td>';
			}

			$cardInforHtml        .= '</tr></table></td></tr>';
			$cardInforHtml        .= '<tr valign="top">';
			$cardInforHtml        .= '<td align="right" nowrap="nowrap" width="10%"><label for="order_payment_name">'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_NAME_ON_CARD') . '</label></td>';
			$orderPaymentName     = (!empty($creditCardSession['order_payment_name'])) ? $creditCardSession['order_payment_name'] : "";
			$cardInforHtml        .= '<td><input class="inputbox" id="order_payment_name" name="order_payment_name" value="'
				. $orderPaymentName . '" autocomplete="off" type="text"></td>';
			$cardInforHtml        .= '</tr>';
			$cardInforHtml        .= '<tr valign="top">';
			$cardInforHtml        .= '<td align="right" nowrap="nowrap" width="10%"><label for="order_payment_number">'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_NUM') . '</label></td>';
			$order_payment_number = (!empty($creditCardSession['order_payment_number'])) ? $creditCardSession['order_payment_number'] : "";
			$cardInforHtml        .= '<td><input class="inputbox" id="order_payment_number" name="order_payment_number" value="'
				. $order_payment_number . '" autocomplete="off" type="text"></td>';
			$cardInforHtml        .= '</tr>';
			$cardInforHtml        .= '<tr><td align="right" nowrap="nowrap" width="10%">' . JText::_('PLG_RS_PAYMENT_BRAINTREE_EXPIRY_DATE') . '</td>';
			$cardInforHtml        .= '<td>';

			$value         = isset($creditCardSession['order_payment_expire_month']) ? $creditCardSession['order_payment_expire_month'] : date('m');
			$cardInforHtml .= JHtml::_('select.genericlist', $months, 'order_payment_expire_month', 'size="1" class="inputbox" ', 'value', 'text', $value);

			$thisyear      = date('Y');
			$cardInforHtml .= '/<select class="inputbox" name="order_payment_expire_year" size="1">';

			for ($y = $thisyear; $y < ($thisyear + 10); $y++)
			{
				$selected      = (!empty($creditCardSession['order_payment_expire_year']) && $creditCardSession['order_payment_expire_year'] == $y) ? "selected" : "";
				$cardInforHtml .= '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
			}

			$cardInforHtml .= '</select></td></tr>';
			$cardInforHtml .= '<tr valign="top"><td align="right" nowrap="nowrap" width="10%"><label for="credit_card_code">'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_SECURITY_CODE') . '</label></td>';

			$creditCardCode = (!empty($creditCardSession['credit_card_code'])) ? $creditCardSession['credit_card_code'] : "";
			$cardInforHtml  .= '<td><input class="inputbox" id="credit_card_code" name="credit_card_code" value="'
				. $creditCardCode . '" autocomplete="off" type="password"></td></tr>';

			$cardInforHtml .= '</table></fieldset>';
			$cartData      .= $cardInforHtml;
		}
		else
		{
			$cartData = '<form action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid='
				. $data['order_id'] . '&Itemid=' . $menuItemId
				. '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >';
			$cartData .= '<table height="100">
								<tr><td>' . JText::_('PLG_RS_PAYMENT_BRAINTREE_USER_IS_ALREADY_REGISTERED_IN_BRAINTREE_VAULT')
				. '</td></tr>
							 </table>';
		}

		$cartData .= '<input type="hidden" name="option" value="com_redshop" />';
		$cartData .= '<input type="hidden" name="Itemid" value="' . $menuItemId . '" />';

		$cartData .= '<input type="submit" name="submit" class="greenbutton" value="'
			. JText::_('PLG_RS_PAYMENT_BRAINTREE_BTN_CHECKOUTNEXT') . '" />';
		$cartData .= '<input type="hidden" name="ccinfo" value="1" />';
		$cartData .= '<input type="hidden" name="payment_method_id" value="' . $paymentMethodId . '" />';
		$cartData .= '<input type="hidden" name="new_vault_user" value="' . $newUser . '" />';
		$cartData .= '<input type="hidden" name="order_id" value="' . $data['order_id'] . '" />';

		$cartData .= '</form>';

		echo eval("?>" . $cartData . "<?php ");
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element  Name of payment plugin
	 * @param   array   $data     Data
	 *
	 * @return  void
	 */
	public function getOrderAndCcdata($element, $data)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$session         = JFactory::getSession();
		$order_functions = order_functions::getInstance();
		$configobj       = Redconfiguration::getInstance();

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$Itemid            = JRequest::getVar('Itemid');
		$ccinfo            = JRequest::getVar('ccinfo');
		$order_id          = JRequest::getVar('order_id');
		$payment_method_id = JRequest::getVar('payment_method_id');

		$order     = $order_functions->getOrderDetails($order_id);
		$orderitem = $order_functions->getOrderItemDetail($order_id);

		$errormsg = "";

		if ($ccinfo == 1)
		{
			$ccdata['order_payment_name']         = JRequest::getVar('order_payment_name');
			$ccdata['creditcard_code']            = JRequest::getVar('creditcard_code');
			$ccdata['order_payment_number']       = JRequest::getVar('order_payment_number');
			$ccdata['order_payment_expire_month'] = JRequest::getVar('order_payment_expire_month');
			$ccdata['order_payment_expire_year']  = JRequest::getVar('order_payment_expire_year');
			$ccdata['credit_card_code']           = JRequest::getVar('credit_card_code');
			$session->set('ccdata', $ccdata);
		}

		// Send the order_id and orderpayment_id to the payment plugin so it knows which DB record to update upon successful payment
		$billingaddresses = $order_functions->getBillingAddress($order->user_id);

		if (isset($billingaddresses))
		{
			if (isset($billingaddresses->country_code))
			{
				$billingaddresses->country_2_code = $configobj->getCountryCode2($billingaddresses->country_code);
			}

			if (isset($billingaddresses->state_code))
			{
				$billingaddresses->state_2_code = $billingaddresses->state_code;
			}
		}

		$shippingaddresses = RedshopHelperOrder::getOrderShippingUserInfo($order_id);

		if (isset($shippingaddresses))
		{
			if (isset($shippingaddresses->country_code))
			{
				$shippingaddresses->country_2_code = $configobj->getCountryCode2($shippingaddresses->country_code);
			}

			if (isset($shippingaddresses->state_code))
			{
				$shippingaddresses->state_2_code = $shippingaddresses->state_code;
			}
		}

		$cart_quantity = 0;

		for ($i = 0, $in = count($orderitem); $i < $in; $i++)
		{
			$cart_quantity += $orderitem[$i]->product_quantity;
		}

		$values['shippinginfo']     = $shippingaddresses;
		$values['billinginfo']      = $billingaddresses;
		$values['carttotal']        = $order->order_total;
		$values['order_subtotal']   = $order->order_subtotal;
		$values["order_id"]         = $order_id;
		$values["order_quantity"]   = $cart_quantity;
		$values['payment_plugin']   = "rs_payment_braintree";
		$values['Itemid']           = $Itemid;
		$values['odiscount']        = $order->order_discount;
		$values['special_discount'] = $order->special_discount_amount;
		$values['order']            = $order;

		$results = $this->onAfterCreditcardInfo($values['payment_plugin'], $values);
	}

	public function onAfterCreditcardInfo($element, $data)
	{
		$order_functions = order_functions::getInstance();

		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$transaction_type = $this->params->get("transaction_type");

		// BIlling details
		$billing_fname               = $data['billinginfo']->firstname;
		$billing_lname               = $data['billinginfo']->lastname;
		$billing_locality            = $data['billinginfo']->city;
		$billing_region              = $data['billinginfo']->state_code;
		$billing_country_code_alpha2 = $data['billinginfo']->country_2_code;
		$biling_postal_code          = $data['billinginfo']->zipcode;

		// Shipping details
		$shipping_fname               = $data['shippinginfo']->firstname;
		$shipping_lname               = $data['shippinginfo']->lastname;
		$shipping_locality            = $data['shippinginfo']->city;
		$shipping_region              = $data['shippinginfo']->state_code;
		$shipping_country_code_alpha2 = $data['shippinginfo']->country_2_code;
		$shipping_postal_code         = $data['shippinginfo']->zipcode;

		$Itemid         = $data['Itemid'];
		$new_user       = false;
		$user           = JFActory::getUser();
		$user_id        = $user->id;
		$user_vault_ref = "";

		if ($this->params->get("store_in_vault"))
		{
			$user_vault_ref = $this->getUser_BraintreeVault_ref($user_id);

			if ($user_vault_ref == "")
			{
				$new_user       = true;
				$user_vault_ref = $this->generate_BraintreeVault_ref($user_id);
			}
		}
		else
		{
			$new_user       = true;
			$user_order_ref = $data['order_id'];
		}

		// For total amount
		$cal_no = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$cal_no = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$order_total = number_format($data['order']->order_total, $cal_no, '.', '');

		include __DIR__ . '/library/environment.php';

		if ($this->params->get("store_in_vault"))
		{
			if ($new_user)
			{
				$braintree_data = Braintree_TransparentRedirect::transactionData(
					array(
						'redirectUrl' => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_braintree&orderid=" . $data['order_id'] . "&Itemid=" . $Itemid,
						'transaction' => array(
							'options'    => array(
								'storeInVault' => true
							),
							'billing'    => array(
								'firstName'         => $billing_fname,
								'lastName'          => $billing_lname,
								'locality'          => $billing_locality,
								'region'            => $billing_region,
								'countryCodeAlpha2' => $billing_country_code_alpha2,
								'postalCode'        => $biling_postal_code
							),
							'shipping'   => array(
								'firstName'         => $shipping_fname,
								'lastName'          => $shipping_lname,
								'locality'          => $shipping_locality,
								'region'            => $shipping_region,
								'countryCodeAlpha2' => $shipping_country_code_alpha2,
								'postalCode'        => $shipping_postal_code
							),
							'amount'     => $order_total,
							'type'       => $transaction_type,
							'customer'   => array('id' => $user_id),
							'creditCard' => array('token' => $user_vault_ref)
						)
					)
				);
			}
			else
			{
				$url = JURI::base()
					. 'index.php?tmpl=component'
					. '&option=com_redshop&view=order_detail&controller=order_detail'
					. '&task=notify_payment&payment_plugin=rs_payment_braintree'
					. '&orderid=' . $data['order_id']
					. "&Itemid=" . $Itemid;

				$braintree_data = Braintree_TransparentRedirect::transactionData(
					array(
						'redirectUrl' => $url,
						'transaction' => array(
							'options'            => array('storeInVault' => true),
							'shipping'           => array(
								'firstName'         => $shipping_fname,
								'lastName'          => $shipping_lname,
								'locality'          => $shipping_locality,
								'region'            => $shipping_region,
								'countryCodeAlpha2' => $shipping_country_code_alpha2,
								'postalCode'        => $shipping_postal_code
							),
							'amount'             => $order_total,
							'type'               => $transaction_type,
							'customerId'         => $user_id,
							'paymentMethodToken' => $user_vault_ref
						)
					)
				);
			}
		}
		else
		{
			$braintree_data = Braintree_TransparentRedirect::transactionData(
				array(
					'redirectUrl' => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_braintree&orderid=" . $data['order_id'] . "&Itemid=" . $Itemid,
					'transaction' => array(
						'amount'     => $order_total,
						'type'       => $transaction_type,
						'billing'    => array(
							'firstName'         => $billing_fname,
							'lastName'          => $billing_lname,
							'locality'          => $billing_locality,
							'region'            => $billing_region,
							'countryCodeAlpha2' => $billing_country_code_alpha2,
							'postalCode'        => $biling_postal_code
						),
						'shipping'   => array(
							'firstName'         => $shipping_fname,
							'lastName'          => $shipping_lname,
							'locality'          => $shipping_locality,
							'region'            => $shipping_region,
							'countryCodeAlpha2' => $shipping_country_code_alpha2,
							'postalCode'        => $shipping_postal_code
						),
						'creditCard' => array('token' => $user_order_ref)
					)
				)
			);
		}

		$data['braintree_token'] = $braintree_data;
		$data['new_user']        = $new_user;
		$app                     = JFactory::getApplication();

		include __DIR__ . '/library/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_braintree($element, $request)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$db      = JFactory::getDbo();
		$request = JRequest::get('request');
		$Itemid  = $request["Itemid"];
		$user    = JFActory::getUser();
		$user_id = $user->id;

		// Include API
		include __DIR__ . '/library/environment.php';

		// Confirm Return String
		$query_string = "http_status=" . $request['http_status'] . "&id=" . $request['id'] . "&kind=" . $request['kind'] . "&tmpl=" . $request['tmpl'] . "&option=" . $request['option'] . "&view=" . $request['view'] . "&controller=" . $request['controller'] . "&task=" . $request['task'] . "&payment_plugin=" . $request['payment_plugin'] . "&orderid=" . $request['orderid'] . "&Itemid=" . $Itemid . "&hash=" . $request['hash'];
		$result       = Braintree_TransparentRedirect::confirm($query_string);
		$transaction  = $result->transaction;

		// Result Response
		$transaction_status = htmlentities($transaction->status);
		$tid                = htmlentities($transaction->id);
		$order_id           = $request["orderid"];
		$user_vault_ref     = htmlentities($transaction->creditCardDetails->token);

		// Update token to USer
		$this->updateUsertovault_token($user_id, $user_vault_ref);

		JPlugin::loadLanguage('com_redshop');

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$cancel_status  = $this->params->get('cancel_status', '');
		$values         = new stdClass;

		if (isset($result) && $result->success)
		{
			$values->order_status_code         = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_PLACED');
			$values->msg                       = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_NOT_PLACED');
			$values->msg                       = $result->message;
		}

		$values->transaction_id = $tid;
		$values->order_id       = $order_id;

		return $values;
	}

	public function onCapture_Paymentrs_payment_braintree($element, $data)
	{
		// Get the class
		include __DIR__ . '/library/environment.php';

		$order_id = $data['order_id'];
		$tid      = $data['order_transactionid'];

		$cal_no = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$cal_no = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$order_amount = number_format($data['order_amount'], $cal_no);

		$result = Braintree_Transaction::submitForSettlement($tid, $order_amount);
		$values = new stdClass;

		if ($result->success)
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_CAPTURED');
		}
		else
		{
			$message                = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}

	public function getUser_BraintreeVault_ref($user_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT braintree_vault_number
							FROM  `#__redshop_users_info`
							WHERE  `user_id` = '" . $user_id . "'
							AND address_type = 'BT'";
		$db->setQuery($query);
		$BraintreeVault_ref = $db->loadObject();

		return $BraintreeVault_ref->braintree_vault_number;
	}

	public function generate_BraintreeVault_ref($user_id)
	{
		$BraintreeVault_ref = rand(11111, 9999999999);

		return $BraintreeVault_ref;
	}

	public function updateUsertovault_token($user_id, $user_vault_ref)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_users_info'))
			->set('braintree_vault_number = ' . $db->q($user_vault_ref))
			->where('user_id = ' . $db->q($user_id))
			->where('address_type = ' . $db->q('BT'));
		$db->setQuery($query)->execute();
	}
}
