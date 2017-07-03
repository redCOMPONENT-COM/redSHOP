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
 * redSHOP payment BrainTree
 *
 * @since   2.0.0
 */
class PlgRedshop_Paymentrs_Payment_Braintree extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object $subject   The object to observe
	 * @param   array  $config    An optional associative array of configuration settings.
	 *                            Recognized key values include 'name', 'group', 'params', 'language'
	 *                            (this list is not meant to be comprehensive).
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
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  mixed             Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		include __DIR__ . '/src/creditcardform.php';
	}

	public function getCredicardForm($element, $data)
	{
		$objOrder         = order_functions::getInstance();
		$objconfiguration = Redconfiguration::getInstance();
		$carthelper       = rsCarthelper::getInstance();
		$user             = JFactory::getUser();
		$user_id          = $user->id;
		$jInput           = JFactory::getApplication()->input;
		$Itemid           = $jInput->getInt('Itemid');
		$new_user         = true;
		$store_in_vault   = $this->params->get('store_in_vault', '0');
		$paymentMethodId  = $jInput->getCmd('payment_method_id', '');

		if ($store_in_vault)
		{
			$user_vault_ref = $this->getUser_BraintreeVault_ref($user_id);

			if ($user_vault_ref != "")
			{
				$new_user = false;
			}
		}

		if ($new_user)
		{
			$cart_data = '<form action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=' . (int) $data['order_id'] . '&Itemid=' . $Itemid . '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">';
			$session   = JFactory::getSession();
			$ccdata    = $session->get('ccdata');

			$url                      = JURI::base(true);
			$cc_list                  = array();
			$cc_list['VISA']          = new stdClass;
			$cc_list['VISA']->img     = 'visa.jpg';
			$cc_list['MC']            = new stdClass;
			$cc_list['MC']->img       = 'master.jpg';
			$cc_list['amex']          = new stdClass;
			$cc_list['amex']->img     = 'blue.jpg';
			$cc_list['maestro']       = new stdClass;
			$cc_list['maestro']->img  = 'mastero.jpg';
			$cc_list['jcb']           = new stdClass;
			$cc_list['jcb']->img      = 'jcb.jpg';
			$cc_list['diners']        = new stdClass;
			$cc_list['diners']->img   = 'dinnersclub.jpg';
			$cc_list['discover']      = new stdClass;
			$cc_list['discover']->img = 'discover.jpg';

			$montharr   = array();
			$montharr[] = JHTML::_('select.option', '0', JText::_('PLG_RS_PAYMENT_BRAINTREE_MONTH'));
			$montharr[] = JHTML::_('select.option', '01', 1);
			$montharr[] = JHTML::_('select.option', '02', 2);
			$montharr[] = JHTML::_('select.option', '03', 3);
			$montharr[] = JHTML::_('select.option', '04', 4);
			$montharr[] = JHTML::_('select.option', '05', 5);
			$montharr[] = JHTML::_('select.option', '06', 6);
			$montharr[] = JHTML::_('select.option', '07', 7);
			$montharr[] = JHTML::_('select.option', '08', 8);
			$montharr[] = JHTML::_('select.option', '09', 9);
			$montharr[] = JHTML::_('select.option', '10', 10);
			$montharr[] = JHTML::_('select.option', '11', 11);
			$montharr[] = JHTML::_('select.option', '12', 12);

			$credict_card          = array();
			$accepted_credict_card = $this->params->get("accepted_credict_card");

			if ($accepted_credict_card != "")
			{
				$credict_card = $accepted_credict_card;
			}

			$cardinfo = '<fieldset class="adminform"><legend>'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_INFORMATION')
				. '</legend>';
			$cardinfo .= '<table class="admintable">';
			$cardinfo .= '<tr><td colspan="2" align="right" nowrap="nowrap">';
			$cardinfo .= '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
			$cardinfo .= '<tr>';

			for ($ic = 0; $ic < count($credict_card); $ic++)
			{
				$cardinfo .= '<td align="center"><img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $cc_list[$credict_card[$ic]]->img . '" alt="" border="0" /></td>';
			}

			$cardinfo .= '</tr>';
			$cardinfo .= '<tr>';

			for ($ic = 0; $ic < count($credict_card); $ic++)
			{
				$value   = $credict_card[$ic];
				$checked = "";

				if (!isset($ccdata['creditcard_code']) && $ic == 0)
				{
					$checked = "checked";
				}
				elseif (isset($ccdata['creditcard_code']))
				{
					$checked = ($ccdata['creditcard_code'] == $value) ? "checked" : "";
				}

				$cardinfo .= '<td align="center"><input type="radio" name="creditcard_code" value="' . $value . '" ' . $checked . ' /></td>';
			}

			$cardinfo             .= '</tr></table></td></tr>';
			$cardinfo             .= '<tr valign="top">';
			$cardinfo             .= '<td align="right" nowrap="nowrap" width="10%"><label for="order_payment_name">'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_NAME_ON_CARD') . '</label></td>';
			$order_payment_name   = (!empty($ccdata['order_payment_name'])) ? $ccdata['order_payment_name'] : "";
			$cardinfo             .= '<td><input class="inputbox" id="order_payment_name" name="order_payment_name" value="'
				. $order_payment_name . '" autocomplete="off" type="text"></td>';
			$cardinfo             .= '</tr>';
			$cardinfo             .= '<tr valign="top">';
			$cardinfo             .= '<td align="right" nowrap="nowrap" width="10%"><label for="order_payment_number">'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_NUM') . '</label></td>';
			$order_payment_number = (!empty($ccdata['order_payment_number'])) ? $ccdata['order_payment_number'] : "";
			$cardinfo             .= '<td><input class="inputbox" id="order_payment_number" name="order_payment_number" value="'
				. $order_payment_number . '" autocomplete="off" type="text"></td>';
			$cardinfo             .= '</tr>';
			$cardinfo             .= '<tr><td align="right" nowrap="nowrap" width="10%">' . JText::_('PLG_RS_PAYMENT_BRAINTREE_EXPIRY_DATE') . '</td>';
			$cardinfo             .= '<td>';

			$value    = isset($ccdata['order_payment_expire_month']) ? $ccdata['order_payment_expire_month'] : date('m');
			$cardinfo .= JHTML::_('select.genericlist', $montharr, 'order_payment_expire_month', 'size="1" class="inputbox" ', 'value', 'text', $value);

			$thisyear = date('Y');
			$cardinfo .= '/<select class="inputbox" name="order_payment_expire_year" size="1">';

			for ($y = $thisyear; $y < ($thisyear + 10); $y++)
			{
				$selected = (!empty($ccdata['order_payment_expire_year']) && $ccdata['order_payment_expire_year'] == $y) ? "selected" : "";
				$cardinfo .= '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
			}

			$cardinfo .= '</select></td></tr>';
			$cardinfo .= '<tr valign="top"><td align="right" nowrap="nowrap" width="10%"><label for="credit_card_code">'
				. JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_SECURITY_CODE') . '</label></td>';

			$credit_card_code = (!empty($ccdata['credit_card_code'])) ? $ccdata['credit_card_code'] : "";
			$cardinfo         .= '<td><input class="inputbox" id="credit_card_code" name="credit_card_code" value="'
				. $credit_card_code . '" autocomplete="off" type="password"></td></tr>';

			$cardinfo  .= '</table></fieldset>';
			$cart_data .= $cardinfo;
		}
		else
		{
			$cart_data = '<form action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid='
				. $data['order_id'] . '&Itemid=' . $Itemid
				. '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >';
			$cart_data .= '<table height="100">
								<tr><td>' . JText::_('PLG_RS_PAYMENT_BRAINTREE_USER_IS_ALREADY_REGISTERED_IN_BRAINTREE_VAULT')
				. '</td></tr>
							 </table>';
		}

		$cart_data .= '<input type="hidden" name="option" value="com_redshop" />';
		$cart_data .= '<input type="hidden" name="Itemid" value="' . $Itemid . '" />';

		$cart_data .= '<input type="submit" name="submit" class="greenbutton" value="'
			. JText::_('PLG_RS_PAYMENT_BRAINTREE_BTN_CHECKOUTNEXT') . '" />';
		$cart_data .= '<input type="hidden" name="ccinfo" value="1" />';
		$cart_data .= '<input type="hidden" name="payment_method_id" value="' . $paymentMethodId . '" />';
		$cart_data .= '<input type="hidden" name="new_vault_user" value="' . $new_user . '" />';
		$cart_data .= '<input type="hidden" name="order_id" value="' . $data['order_id'] . '" />';

		$cart_data .= '</form>';

		echo eval("?>" . $cart_data . "<?php ");
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function getOrderAndCcdata($element, $data)
	{
		$session         = JFactory::getSession();
		$order_functions = order_functions::getInstance();
		$configobj       = Redconfiguration::getInstance();

		if ($element != 'rs_payment_braintree')
		{
			return;
		}

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

		include __DIR__ . '/src/environment.php';

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

		include __DIR__ . '/src/extra_info.php';
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
		include __DIR__ . '/src/environment.php';

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
		include __DIR__ . '/src/environment.php';

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
