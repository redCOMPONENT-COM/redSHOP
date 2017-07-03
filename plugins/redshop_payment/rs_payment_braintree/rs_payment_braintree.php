<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * PlgRedshop_PaymentRs_Payment_Braintree class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Braintree extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
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
	 * [onPrePayment description]
	 *
	 * @param   [type]  $element  [description]
	 * @param   [type]  $data     [description]
	 *
	 * @return  [type]            [description]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/creditcardform.php';
	}

	/**
	 * [getCredicardForm]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function getCredicardForm($element, $data)
	{
		$user             = JFactory::getUser();
		$userId           = $user->id;
		$jInput = JFactory::getApplication()->input;
		$itemId           = $jInput->getInt('Itemid');
		$newUser          = true;
		$storeInVault   = $this->params->get('store_in_vault', '0');
		$paymentMethodId = $jInput->getCmd('payment_method_id', '');

		if ($storeInVault)
		{
			$userVaultRef = $this->getUser_BraintreeVault_ref($userId);

			if ($userVaultRef != "")
			{
				$newUser = false;
			}
		}

		if ($newUser)
		{
			$session   = JFactory::getSession();
			$ccdata    = $session->get('ccdata');

			$url                      		= JURI::base(true);
			$creditCards                	= array();
			$creditCards['VISA']			= new stdClass;
			$creditCards['VISA']->img   	= 'visa.jpg';
			$creditCards['MC'] 				= new stdClass;
			$creditCards['MC']->img 		= 'master.jpg';
			$creditCards['amex'] 			= new stdClass;
			$creditCards['amex']->img 		= 'blue.jpg';
			$creditCards['maestro'] 		= new stdClass;
			$creditCards['maestro']->img 	= 'mastero.jpg';
			$creditCards['jcb'] 			= new stdClass;
			$creditCards['jcb']->img      	= 'jcb.jpg';
			$creditCards['diners'] 			= new stdClass;
			$creditCards['diners']->img   	= 'dinnersclub.jpg';
			$creditCards['discover'] 		= new stdClass;
			$creditCards['discover']->img 	= 'discover.jpg';

			$months   = [];

			$months[] = JHTML::_('select.option', '0', JText::_('PLG_RS_PAYMENT_BRAINTREE_MONTH'));
			$months[] = JHTML::_('select.option', '01', 1);
			$months[] = JHTML::_('select.option', '02', 2);
			$months[] = JHTML::_('select.option', '03', 3);
			$months[] = JHTML::_('select.option', '04', 4);
			$months[] = JHTML::_('select.option', '05', 5);
			$months[] = JHTML::_('select.option', '06', 6);
			$months[] = JHTML::_('select.option', '07', 7);
			$months[] = JHTML::_('select.option', '08', 8);
			$months[] = JHTML::_('select.option', '09', 9);
			$months[] = JHTML::_('select.option', '10', 10);
			$months[] = JHTML::_('select.option', '11', 11);
			$months[] = JHTML::_('select.option', '12', 12);

			$orderPaymentName   = (!empty($ccdata['order_payment_name'])) ? $ccdata['order_payment_name'] : "";
			$orderPaymentNumber = (!empty($ccdata['order_payment_number'])) ? $ccdata['order_payment_number'] : "";
			$value 				= isset($ccdata['order_payment_expire_month']) ? $ccdata['order_payment_expire_month'] : date('m');
			$thisYear           = date('Y');
			$creditCardCode     = (!empty($ccdata['credit_card_code'])) ? $ccdata['credit_card_code'] : "";

			$acceptedCredictCard = $this->params->get("accepted_credict_card")? $this->params->get("accepted_credict_card"): [];

			require JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_braintree', 'creditcard_newuser');
		}
		else
		{
			require JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_braintree', 'creditcard_existuser');
		}
	}

	/**
	 * [getOrderAndCcdata]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function getOrderAndCcdata($element, $data)
	{
		$session        = JFactory::getSession();
		$orderFunctions = order_functions::getInstance();
		$redConfig      = Redconfiguration::getInstance();

		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$itemId          = JRequest::getVar('Itemid');
		$ccInfo          = JRequest::getVar('ccinfo');
		$orderId         = JRequest::getVar('order_id');

		$order     = $orderFunctions->getOrderDetails($orderId);
		$orderItem = $orderFunctions->getOrderItemDetail($orderId);

		if ($ccInfo == 1)
		{
			$ccData['order_payment_name']         = JRequest::getVar('order_payment_name');
			$ccData['creditcard_code']            = JRequest::getVar('creditcard_code');
			$ccData['order_payment_number']       = JRequest::getVar('order_payment_number');
			$ccData['order_payment_expire_month'] = JRequest::getVar('order_payment_expire_month');
			$ccData['order_payment_expire_year']  = JRequest::getVar('order_payment_expire_year');
			$ccData['credit_card_code']           = JRequest::getVar('credit_card_code');
			$session->set('ccdata', $ccData);
		}

		// Send the order_id and orderpayment_id to the payment plugin so it knows which DB record to update upon successful payment
		$billingAddress = $orderFunctions->getBillingAddress($order->user_id);

		if (isset($billingAddress))
		{
			if (isset($billingAddress->country_code))
			{
				$billingAddress->country_2_code = $redConfig->getCountryCode2($billingAddress->country_code);
			}

			if (isset($billingAddress->state_code))
			{
				$billingAddress->state_2_code = $billingAddress->state_code;
			}
		}

		$shippingAddress = RedshopHelperOrder::getOrderShippingUserInfo($orderId);

		if (isset($shippingAddress))
		{
			if (isset($shippingAddress->country_code))
			{
				$shippingAddress->country_2_code = $redConfig->getCountryCode2($shippingAddress->country_code);
			}

			if (isset($shippingAddress->state_code))
			{
				$shippingAddress->state_2_code = $shippingAddress->state_code;
			}
		}

		$cartQuantity = 0;

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$cartQuantity += $orderItem[$i]->product_quantity;
		}

		$values['shippinginfo']     = $shippingAddress;
		$values['billinginfo']      = $billingAddress;
		$values['carttotal']        = $order->order_total;
		$values['order_subtotal']   = $order->order_subtotal;
		$values["order_id"]         = $orderId;
		$values["order_quantity"]   = $cartQuantity;
		$values['payment_plugin']   = $element;
		$values['Itemid']           = $itemId;
		$values['odiscount']        = $order->order_discount;
		$values['special_discount'] = $order->special_discount_amount;
		$values['order']            = $order;

		$results = $this->onAfterCreditcardInfo($values['payment_plugin'], $values);
	}

	/**
	 * [onAfterCreditcardInfo]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onAfterCreditcardInfo($element, $data)
	{
		$orderFunctions = order_functions::getInstance();

		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$transactionType = $this->params->get("transaction_type");

		// BIlling details
		$billingFname             = $data['billinginfo']->firstname;
		$billingLname             = $data['billinginfo']->lastname;
		$billingLocality          = $data['billinginfo']->city;
		$billingRegion            = $data['billinginfo']->state_code;
		$billingCountryCodeAlpha2 = $data['billinginfo']->country_2_code;
		$bilingPostalCode         = $data['billinginfo']->zipcode;

		// Shipping details
		$shippingFname             = $data['shippinginfo']->firstname;
		$shippingLname             = $data['shippinginfo']->lastname;
		$shippingLocality          = $data['shippinginfo']->city;
		$shippingRegion            = $data['shippinginfo']->state_code;
		$shippingCountryCodeAlpha2 = $data['shippinginfo']->country_2_code;
		$shippingPostalCode        = $data['shippinginfo']->zipcode;

		$itemId         = $data['Itemid'];
		$newUser        = false;
		$user           = JFActory::getUser();
		$userId         = $user->id;
		$userVaultRef   = "";

		if ($this->params->get("store_in_vault"))
		{
			$userVaultRef = $this->getUser_BraintreeVault_ref($userId);

			if ($userVaultRef == "")
			{
				$newUser 	  = true;
				$userVaultRef = $this->generate_BraintreeVault_ref($userId);
			}
		}
		else
		{
			$newUser 		= true;
			$userOrderRef   = $data['order_id'];
		}

		// For total amount
		$calNo = (Redshop::getConfig()->get('PRICE_DECIMAL') != '')? Redshop::getConfig()->get('PRICE_DECIMAL'): 2;

		$orderTotal = number_format($data['order']->order_total, $calNo, '.', '');

		require_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/_environment.php';

		$braintreeData = [];

		if ($this->params->get("store_in_vault"))
		{
			if ($newUser)
			{
				$url = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_braintree&orderid=" . $data['order_id'] . "&Itemid=" . $itemId;

				$braintreeData = Braintree_TransparentRedirect::transactionData(
					[
						'redirectUrl' => $url,
						'transaction' =>
						[
							'options' =>
								[
									'storeInVault' => true
								],
							'billing' =>
								[
									'firstName' => $billingFname,
									'lastName' => $billingLname,
									'locality' => $billingLocality,
									'region' => $billingRegion,
									'countryCodeAlpha2' => $billingCountryCodeAlpha2,
									'postalCode' => $bilingPostalCode
								],
							'shipping' =>
								[
									'firstName' => $shippingFname,
									'lastName' => $shippingLname,
									'locality' => $shippingLocality,
									'region' => $shippingRegion,
									'countryCodeAlpha2' => $shippingCountryCodeAlpha2,
									'postalCode' => $shippingPostalCode
								],
							'customer' =>
								[
									'id' => $userId
								],
							'creditCard' =>
								[
									'token' => $userVaultRef
								],
							'amount' => $orderTotal,
							'type' => $transactionType
						]
					]
				);
			}
			else
			{
				$url = JURI::base()
					. 'index.php?tmpl=component'
					. '&option=com_redshop&view=order_detail&controller=order_detail'
					. '&task=notify_payment&payment_plugin=rs_payment_braintree'
					. '&orderid=' . $data['order_id']
					. "&Itemid=" . $itemId;

				$braintreeData = Braintree_TransparentRedirect::transactionData(
					[
						'redirectUrl' => $url,
						'transaction' =>
							[
								'options' =>
									[
										'storeInVault' => true
									],
								'shipping' =>
									[
										'firstName' => $shippingFname,
										'lastName' => $shippingLname,
										'locality' => $shippingLocality,
										'region' => $shippingRegion,
										'countryCodeAlpha2' => $shippingCountryCodeAlpha2,
										'postalCode' => $shippingPostalCode
									],
								'amount' => $orderTotal,
								'type' => $transactionType,
								'customerId' => $userId,
								'paymentMethodToken' => $userVaultRef
							]
					]
				);
			}
		}
		else
		{
			JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_braintree&orderid=" . $data['order_id'] . "&Itemid=" . $itemId;

			$braintreeData = Braintree_TransparentRedirect::transactionData(
				[
					'redirectUrl' => $url,
					'transaction' =>
						[
							'amount' => $order_total,
							'type' => $transactionType,
							'billing' =>
								[
									'firstName' => $billingFname,
									'lastName' => $billingLname,
									'locality' => $billingLocality,
									'region' => $billingRegion,
									'countryCodeAlpha2' => $billingCountryCodeAlpha2,
									'postalCode' => $bilingPostalCode
								],
							'shipping' =>
								[
									'firstName' => $shippingFname,
									'lastName' => $shippingLname,
									'locality' => $shippingLocality,
									'region' => $shippingRegion,
									'countryCodeAlpha2' => $shippingCountryCodeAlpha2,
									'postalCode' => $shippingPostalCode
								],
							'creditCard' =>
								[
									'token' => $userOrderRef
								]
						]
				]
			);
		}

		$data['braintree_token'] = $braintreeData;
		$data['new_user']        = $newUser;
		$app                     = JFactory::getApplication();

		include_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [onNotifyPaymentrs_payment_braintree]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [mix]     $request  [Actually dont know what this param used for]
	 *
	 * @return  [array]
	 */
	public function onNotifyPaymentrs_payment_braintree($element, $request)
	{
		if ($element != 'rs_payment_braintree')
		{
			return;
		}

		$db      = JFactory::getDbo();
		$input   = JFactory::getApplication()->input;
		$itemId  = $input->get("Itemid");
		$user    = JFActory::getUser();
		$userId  = $user->id;

		// Include API
		require_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/_environment.php';

		// Confirm Return String
		$query_string = "http_status=" . $input->get('http_status')
						. "&id=" . $input->get('id')
						. "&kind=" . $input->get('kind')
						. "&tmpl=" . $input->get('tmpl')
						. "&option=" . $input->get('option')
						. "&view=" . $input->get('view')
						. "&controller=" . $input->get('controller')
						. "&task=" . $input->get('task')
						. "&payment_plugin=" . $input->get('payment_plugin')
						. "&orderid=" . $input->get('orderid')
						. "&Itemid=" . $itemId
						. "&hash=" . $input->get('hash');

		$result       = Braintree_TransparentRedirect::confirm($query_string);
		$transaction  = $result->transaction;

		// Result Response
		$tranId            = htmlentities($transaction->id);
		$orderId           = $request["orderid"];
		$userVaultRef      = htmlentities($transaction->creditCardDetails->token);

		// Update token to USer
		$this->updateUsertovault_token($userId, $userVaultRef);

		JPlugin::loadLanguage('com_redshop');

		$verifyStatus        = $this->params->get('verify_status', '');
		$invalidStatus       = $this->params->get('invalid_status', '');

		$values = new stdClass;

		if (isset($result) && $result->success)
		{
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_NOT_PLACED');
			$values->msg = $result->message;
		}

		$values->transaction_id = $tranId;
		$values->order_id = $orderId;

		return $values;
	}

	/**
	 * [onCapture_Paymentrs_payment_braintree]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [array]
	 */
	public function onCapture_Paymentrs_payment_braintree($element, $data)
	{
		// Get the class
		require_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/_environment.php';

		$tranId = $data['order_transactionid'];

		$calNo = (Redshop::getConfig()->get('PRICE_DECIMAL') != '')? Redshop::getConfig()->get('PRICE_DECIMAL'): 2;

		$order_amount = number_format($data['order_amount'], $calNo);

		$result = Braintree_Transaction::submitForSettlement($tranId, $order_amount);
		$values = new stdClass;

		if ($result->success)
		{
			$values->responsestatus = 'Success';
			$message = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_CAPTURED');
		}
		else
		{
			$message = JText::_('PLG_RS_PAYMENT_BRAINTREE_ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}

	/**
	 * [getUser_BraintreeVault_ref]
	 *
	 * @param   [int]  $userId  [ID of user]
	 *
	 * @return  [int]
	 */
	public function getUser_BraintreeVault_ref($userId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->qn('braintree_vault_number'))
			->from($db->qn('#__redshop_users_info'))
			->where($db->qn('user_id') . ' = ' . $db->q($userId))
			->where($db->qn('address_type') . ' = ' . $db->q('BT'));

		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->braintree_vault_number;
	}

	/**
	 * [generate_BraintreeVault_ref]
	 *
	 * @param   [int]  $userId  [ID of user, actually dont know what it used for]
	 *
	 * @return  [int]
	 */
	public function generate_BraintreeVault_ref($userId)
	{
		return rand(11111, 9999999999);
	}

	/**
	 * [updateUsertovault_token description]
	 *
	 * @param   [int]  $userId        [ID of user]
	 * @param   [int]  $userVaultRef  [Vault Reference Number]
	 *
	 * @return  [void]
	 */
	public function updateUsertovault_token($userId, $userVaultRef)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_users_info'))
			->set($db->qn('braintree_vault_number') . ' = ' . $db->q($userVaultRef))
			->where($db->qn('user_id') . ' = ' . $db->q($userId))
			->where($db->qn('address_type') . ' = ' . $db->q('BT'));

		$db->setQuery($query)->execute();
	}
}
