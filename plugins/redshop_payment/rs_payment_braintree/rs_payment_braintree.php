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
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
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
	 * @param   string  $element  Name of payment plugin
	 * @param   array   $data     Data
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

			for ($ic = 0, $count = count($creditCard); $ic < $count; $ic++)
			{
				$cardInforHtml .= '<td align="center"><img src="'
					. REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $availableCreditCards[$creditCard[$ic]]->img . '" alt="" border="0" /></td>';
			}

			$cardInforHtml .= '</tr><tr>';

			for ($ic = 0, $count = count($creditCard); $ic < $count; $ic++)
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
	 * @param   string  $element  sName of payment plugin
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

		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata', array());
		$input   = JFactory::getApplication()->input;

		$menuItemId = $input->getInt('Itemid');
		$orderId    = $input->getInt('order_id');

		$order      = RedshopHelperOrder::getOrderDetails($orderId);
		$orderItems = RedshopHelperOrder::getOrderItemDetail($orderId);

		if ($input->getInt('ccinfo', 0) == 1)
		{
			$ccdata['order_payment_name']         = $input->getString('order_payment_name');
			$ccdata['creditcard_code']            = $input->get('creditcard_code');
			$ccdata['order_payment_number']       = $input->get('order_payment_number');
			$ccdata['order_payment_expire_month'] = $input->get('order_payment_expire_month');
			$ccdata['order_payment_expire_year']  = $input->get('order_payment_expire_year');
			$ccdata['credit_card_code']           = $input->get('credit_card_code');

			$session->set('ccdata', $ccdata);
		}

		// Send the order_id and orderpayment_id to the payment plugin so it knows which DB record to update upon successful payment
		$billingAddress = RedshopHelperOrder::getBillingAddress($order->user_id);

		if (!empty($billingAddress))
		{
			if (!empty($billingAddress->country_code))
			{
				$billingAddress->country_2_code = RedshopHelperWorld::getCountryCode2($billingAddress->country_code);
			}

			if (!empty($billingAddress->state_code))
			{
				$billingAddress->state_2_code = $billingAddress->state_code;
			}
		}

		$shippingAddress = RedshopHelperOrder::getOrderShippingUserInfo($orderId);

		if (!empty($shippingAddress))
		{
			if (!empty($shippingAddress->country_code))
			{
				$shippingAddress->country_2_code = RedshopHelperWorld::getCountryCode2($shippingAddress->country_code);
			}

			if (!empty($shippingAddress->state_code))
			{
				$shippingAddress->state_2_code = $shippingAddress->state_code;
			}
		}

		$cartQuantity = 0;

		foreach ($orderItems as $orderItem)
		{
			$cartQuantity += $orderItem->product_quantity;
		}

		$values['shippinginfo']     = $shippingAddress;
		$values['billinginfo']      = $billingAddress;
		$values['carttotal']        = $order->order_total;
		$values['order_subtotal']   = $order->order_subtotal;
		$values["order_id"]         = $orderId;
		$values["order_quantity"]   = $cartQuantity;
		$values['payment_plugin']   = "rs_payment_braintree";
		$values['Itemid']           = $menuItemId;
		$values['odiscount']        = $order->order_discount;
		$values['special_discount'] = $order->special_discount_amount;
		$values['order']            = $order;

		$this->onAfterCreditcardInfo($values['payment_plugin'], $values);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element  Name of payment plugin
	 * @param   array   $data     Data
	 *
	 * @return  void
	 */
	public function onAfterCreditcardInfo($element, $data)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$transactionType = $this->params->get("transaction_type");

		// BIlling details
		$billingName              = $data['billinginfo']->firstname;
		$billingLastName          = $data['billinginfo']->lastname;
		$billingLocality          = $data['billinginfo']->city;
		$billingRegion            = $data['billinginfo']->state_code;
		$billingCountryCodeAlpha2 = $data['billinginfo']->country_2_code;
		$billingPostalCode        = $data['billinginfo']->zipcode;

		// Shipping details
		$shippingFirstName         = $data['shippinginfo']->firstname;
		$shippingLastName          = $data['shippinginfo']->lastname;
		$shippingLocality          = $data['shippinginfo']->city;
		$shippingRegion            = $data['shippinginfo']->state_code;
		$shippingCountryCodeAlpha2 = $data['shippinginfo']->country_2_code;
		$shippingPostalCode        = $data['shippinginfo']->zipcode;

		$menuItemId         = $data['Itemid'];
		$isNewUser          = false;
		$user               = JFactory::getUser();
		$userId             = $user->id;
		$userVaultReference = "";
		$userOrderRef       = null;
		$isStoreInVault     = (boolean) $this->params->get("store_in_vault", 0);

		if ($isStoreInVault)
		{
			$userVaultReference = $this->getUser_BraintreeVault_ref($userId);

			if ($userVaultReference == "")
			{
				$isNewUser          = true;
				$userVaultReference = $this->generate_BraintreeVault_ref($userId);
			}
		}
		else
		{
			$isNewUser    = true;
			$userOrderRef = $data['order_id'];
		}

		// For total amount
		$priceDecimal = (Redshop::getConfig()->get('PRICE_DECIMAL') != '') ? Redshop::getConfig()->get('PRICE_DECIMAL') : 2;

		$orderTotal = number_format($data['order']->order_total, $priceDecimal, '.', '');

		include __DIR__ . '/library/environment.php';

		if ($isStoreInVault)
		{
			if ($isNewUser)
			{
				$url = JUri::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail'
					. '&task=notify_payment&payment_plugin=rs_payment_braintree&orderid=" . $data['order_id'] . "&Itemid=" . $menuItemId;

				$braintreeData = Braintree_TransparentRedirect::transactionData(
					array(
						'redirectUrl' => $url,
						'transaction' => array(
							'options'    => array(
								'storeInVault' => true
							),
							'billing'    => array(
								'firstName'         => $billingName,
								'lastName'          => $billingLastName,
								'locality'          => $billingLocality,
								'region'            => $billingRegion,
								'countryCodeAlpha2' => $billingCountryCodeAlpha2,
								'postalCode'        => $billingPostalCode
							),
							'shipping'   => array(
								'firstName'         => $shippingFirstName,
								'lastName'          => $shippingLastName,
								'locality'          => $shippingLocality,
								'region'            => $shippingRegion,
								'countryCodeAlpha2' => $shippingCountryCodeAlpha2,
								'postalCode'        => $shippingPostalCode
							),
							'amount'     => $orderTotal,
							'type'       => $transactionType,
							'customer'   => array('id' => $userId),
							'creditCard' => array('token' => $userVaultReference)
						)
					)
				);
			}
			else
			{
				$url = JUri::base()
					. 'index.php?tmpl=component'
					. '&option=com_redshop&view=order_detail&controller=order_detail'
					. '&task=notify_payment&payment_plugin=rs_payment_braintree'
					. '&orderid=' . $data['order_id']
					. "&Itemid=" . $menuItemId;

				$braintreeData = Braintree_TransparentRedirect::transactionData(
					array(
						'redirectUrl' => $url,
						'transaction' => array(
							'options'            => array('storeInVault' => true),
							'shipping'           => array(
								'firstName'         => $shippingFirstName,
								'lastName'          => $shippingLastName,
								'locality'          => $shippingLocality,
								'region'            => $shippingRegion,
								'countryCodeAlpha2' => $shippingCountryCodeAlpha2,
								'postalCode'        => $shippingPostalCode
							),
							'amount'             => $orderTotal,
							'type'               => $transactionType,
							'customerId'         => $userId,
							'paymentMethodToken' => $userVaultReference
						)
					)
				);
			}
		}
		else
		{
			$url = JUri::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail'
				. '&task=notify_payment&payment_plugin=rs_payment_braintree&orderid=" . $data['order_id'] . "&Itemid=" . $menuItemId;

			$braintreeData = Braintree_TransparentRedirect::transactionData(
				array(
					'redirectUrl' => $url,
					'transaction' => array(
						'amount'     => $orderTotal,
						'type'       => $transactionType,
						'billing'    => array(
							'firstName'         => $billingName,
							'lastName'          => $billingLastName,
							'locality'          => $billingLocality,
							'region'            => $billingRegion,
							'countryCodeAlpha2' => $billingCountryCodeAlpha2,
							'postalCode'        => $billingPostalCode
						),
						'shipping'   => array(
							'firstName'         => $shippingFirstName,
							'lastName'          => $shippingLastName,
							'locality'          => $shippingLocality,
							'region'            => $shippingRegion,
							'countryCodeAlpha2' => $shippingCountryCodeAlpha2,
							'postalCode'        => $shippingPostalCode
						),
						'creditCard' => array('token' => $userOrderRef)
					)
				)
			);
		}

		$data['braintree_token'] = $braintreeData;
		$data['new_user']        = $isNewUser;
		$app                     = JFactory::getApplication();

		include __DIR__ . '/library/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_braintree($element, $request)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$input      = JFactory::getApplication()->input;
		$menuItemId = $input->getInt("Itemid", 0);
		$user       = JFActory::getUser();
		$userId     = $user->id;

		// Include API
		include __DIR__ . '/library/environment.php';

		// Confirm Return String
		$urlQuery = "http_status=" . $input->get('http_status') . "&id=" . $input->get('id') . "&kind=" . $input->get('kind')
			. "&tmpl=" . $input->get('tmpl') . "&option=" . $input->get('option') . "&view=" . $input->get('view')
			. "&controller=" . $input->get('controller') . "&task=" . $input->get('task') . "&payment_plugin=" . $input->get('payment_plugin')
			. "&orderid=" . $input->get('orderid') . "&Itemid=" . $menuItemId . "&hash=" . $input->get('hash');

		$result      = Braintree_TransparentRedirect::confirm($urlQuery);
		$transaction = $result->transaction;

		// Result Response
		$transactionStatus = htmlentities($transaction->status);
		$tid               = htmlentities($transaction->id);
		$orderId           = $input->get("orderid");
		$userVaultRef      = htmlentities($transaction->creditCardDetails->token);

		// Update token to USer
		$this->updateUsertovault_token($userId, $userVaultRef);

		JPlugin::loadLanguage('com_redshop');

		$verifyStatus  = $this->params->get('verify_status', '');
		$invalidStatus = $this->params->get('invalid_status', '');
		$cancelStatus  = $this->params->get('cancel_status', '');
		$values        = new stdClass;

		if (isset($result) && $result->success)
		{
			$values->order_status_code         = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_PLACED');
			$values->msg                       = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code         = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_NOT_PLACED');
			$values->msg                       = $result->message;
		}

		$values->transaction_id = $tid;
		$values->order_id       = $orderId;

		return $values;
	}

	public function onCapture_Paymentrs_payment_braintree($element, $data)
	{
		// Get the class
		include __DIR__ . '/library/environment.php';

		$orderId = $data['order_id'];
		$tid     = $data['order_transactionid'];

		$priceDecimal = (Redshop::getConfig()->get('PRICE_DECIMAL') != '') ? Redshop::getConfig()->get('PRICE_DECIMAL') : 2;

		$orderAmount = number_format($data['order_amount'], $priceDecimal);

		$result = Braintree_Transaction::submitForSettlement($tid, $orderAmount);
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

	public function getUser_BraintreeVault_ref($userId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('braintree_vault_number'))
			->from($db->qn('#__redshop_users_info'))
			->where($db->qn('user_id') . ' = ' . (int) $userId)
			->where($db->qn('address_type') . ' = ' . $db->quote('BT'));

		return $db->setQuery($query)->loadResult();
	}

	public function generate_BraintreeVault_ref($user_id)
	{
		return rand(11111, 9999999999);
	}

	public function updateUsertovault_token($userId, $userVaultRef)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_users_info'))
			->set($db->qn('braintree_vault_number') . ' = ' . $db->quote($userVaultRef))
			->where($db->qn('user_id') . ' = ' . $db->quote($userId))
			->where($db->qn('address_type') . ' = ' . $db->quote('BT'));

		$db->setQuery($query)->execute();
	}
}
