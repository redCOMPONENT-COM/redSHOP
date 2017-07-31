<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\Economic;



/**
 * Class checkoutModelcheckout
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCheckout extends RedshopModel
{

	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $discount_type = null;

	public $_userhelper = null;

	public $_carthelper = null;

	public $_shippinghelper = null;

	public $_order_functions = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		$session             = JFactory::getSession();

		$this->_carthelper      = rsCarthelper::getInstance();
		$this->_userhelper      = rsUserHelper::getInstance();
		$this->_shippinghelper  = shipping::getInstance();
		$this->_producthelper   = productHelper::getInstance();
		$this->_order_functions = order_functions::getInstance();
		$this->_redshopMail     = redshopMail::getInstance();

		$user = JFactory::getUser();
		$cart = $session->get('cart');

		if (!empty($cart))
		{
			if (!$cart)
			{
				$cart        = array();
				$cart['idx'] = 0;
			}
			elseif (isset($cart['idx']) === false)
			{
				$cart['idx'] = 0;
			}
		}

		$noOFGIFTCARD = 0;
		$idx = 0;

		if (isset($cart['idx']))
		{
			$idx = $cart['idx'];
		}

		for ($i = 0; $i < $idx; $i++)
		{
			if (isset($cart[$i]['giftcard_id']) === true)
			{
				if (!is_null($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'] != 0)
				{
					$noOFGIFTCARD++;
				}
			}
		}

		if (isset($cart['free_shipping']) === false)
		{
			$cart['free_shipping'] = 0;
		}

		if ($noOFGIFTCARD == $idx)
		{
			$cart['free_shipping'] = 1;
		}
		elseif ($cart['free_shipping'] != 1)
		{
			$cart['free_shipping'] = 0;
		}

		if ($user->id)
		{
			$cart = $this->_carthelper->modifyCart($cart, $user->id);
		}

		RedshopHelperCartSession::setCart($cart);
		$this->_carthelper->carttodb();
	}

	public function store($data)
	{
		$captcha = $this->_userhelper->checkCaptcha($data);

		if (!$captcha)
		{
			return false;
		}

		if (isset($data['user_id']) && $data['user_id'])
		{
			$joomlauser = $this->_userhelper->updateJoomlaUser($data);
		}
		else
		{
			$joomlauser = $this->_userhelper->createJoomlaUser($data);
		}

		if (!$joomlauser)
		{
			return false;
		}

		$reduser = $this->_userhelper->storeRedshopUser($data, $joomlauser->id);

		return $reduser;
	}

	public function orderplace()
	{
		$app = JFactory::getApplication();

		$redconfig       = Redconfiguration::getInstance();
		$quotationHelper = quotationHelper::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$helper          = redhelper::getInstance();
		$shippinghelper  = shipping::getInstance();
		$order_functions = order_functions::getInstance();

		$input            = $app->input;
		$post             = $input->post->getArray();
		$Itemid           = $input->post->getInt('Itemid', 0);
		$shop_id          = $input->post->getString('shop_id', "");
		$gls_zipcode      = $input->post->getString('gls_zipcode', "");
		$gls_mobile       = $input->post->getString('gls_mobile', "");
		$customer_message = $input->post->getString('rs_customer_message_ta', "");
		$referral_code    = $input->post->getString('txt_referral_code', "");

		if ($gls_mobile)
		{
			$shop_id .= '###' . $gls_mobile;
		}

		if ($gls_zipcode)
		{
			$shop_id .= '###' . $gls_zipcode;
		}

		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$userId  = $user->id;

		if (!$user->id && $auth['users_info_id'])
		{
			$userId = - $auth['users_info_id'];
		}

		$db      = JFactory::getDbo();
		$issplit = $session->get('issplit');

		// If user subscribe for the newsletter
		if (isset($post['newsletter_signup']) && $post['newsletter_signup'] == 1)
		{
			$this->_userhelper->newsletterSubscribe();
		}

		// If user unsubscribe for the newsletter

		if (isset($post['newsletter_signoff']) && $post['newsletter_signoff'] == 1)
		{
			RedshopHelperNewsletter::removeSubscribe();
		}

		$order_paymentstatus = 'Unpaid';
		$users_info_id       = JRequest::getInt('users_info_id');
		$shippingaddresses   = $this->shipaddress($users_info_id);
		$billingaddresses    = $this->billingaddresses();

		if (isset($shippingaddresses))
		{
			$d ["shippingaddress"]                 = $shippingaddresses;
			$d ["shippingaddress"]->country_2_code = $redconfig->getCountryCode2($d ["shippingaddress"]->country_code);
			$d ["shippingaddress"]->state_2_code   = $redconfig->getStateCode2($d ["shippingaddress"]->state_code);

			$shippingaddresses->country_2_code = $d ["shippingaddress"]->country_2_code;
			$shippingaddresses->state_2_code   = $d ["shippingaddress"]->state_2_code;
		}
		else
		{
			$shippingaddresses = $billingaddresses;
			$shippingaddresses->firstname = isset($post['firstname_ST']) ? $post['firstname_ST'] : $billingaddresses->firstname;
			$shippingaddresses->lastname = isset($post['lastname_ST']) ? $post['lastname_ST'] : $billingaddresses->lastname;
			$shippingaddresses->address = isset($post['address_ST']) ? $post['address_ST'] : $billingaddresses->address;
			$shippingaddresses->zipcode = isset($post['zipcode_ST']) ? $post['zipcode_ST'] : $billingaddresses->zipcode;
			$shippingaddresses->city = isset($post['city_ST']) ? $post['city_ST'] : $billingaddresses->city;
			$shippingaddresses->country_code = isset($post['country_code_ST']) ? $post['country_code_ST'] : $billingaddresses->country_code;
		}

		if (isset($billingaddresses))
		{
			$d ["billingaddress"] = $billingaddresses;

			if (isset($billingaddresses->country_code))
			{
				$d ["billingaddress"]->country_2_code = $redconfig->getCountryCode2($billingaddresses->country_code);
				$billingaddresses->country_2_code     = $d ["billingaddress"]->country_2_code;
			}

			if (isset($billingaddresses->state_code))
			{
				$d ["billingaddress"]->state_2_code = $redconfig->getStateCode2($billingaddresses->state_code);
				$billingaddresses->state_2_code     = $d ["billingaddress"]->state_2_code;
			}
		}

		$cart = $session->get('cart');

		if ($cart['idx'] < 1)
		{
			$msg = JText::_('COM_REDSHOP_EMPTY_CART');
			$app->redirect(JRoute::_('index.php?option=com_redshop&Itemid=' . $Itemid), $msg);
		}

		$shipping_rate_id = '';

		if ($cart['free_shipping'] != 1)
		{
			$shipping_rate_id = $input->post->getString('shipping_rate_id', "");
		}

		$payment_method_id = $input->post->getString('payment_method_id', "");

		if ($shipping_rate_id && $cart['free_shipping'] != 1)
		{
			$shipArr              = $this->calculateShipping($shipping_rate_id);
			$cart['shipping']     = $shipArr['order_shipping_rate'];
			$cart['shipping_vat'] = $shipArr['shipping_vat'];
		}

		$cart = $this->_carthelper->modifyDiscount($cart);

		// Get Payment information
		$paymentMethod = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentMethod = $paymentMethod[0];

		// Se payment method plugin params
		$paymentMethod->params = new JRegistry($paymentMethod->params);

		// Prepare payment Information Object for calculations
		$paymentInfo                              = new stdclass;
		$paymentInfo->payment_price               = $paymentMethod->params->get('payment_price', '');
		$paymentInfo->payment_oprand              = $paymentMethod->params->get('payment_oprand', '');
		$paymentInfo->payment_discount_is_percent = $paymentMethod->params->get('payment_discount_is_percent', '');
		$paymentAmount = $cart ['total'];

		if (Redshop::getConfig()->get('PAYMENT_CALCULATION_ON') == 'subtotal')
		{
			$paymentAmount = $cart ['product_subtotal'];
		}

		$paymentArray  = $this->_carthelper->calculatePayment($paymentAmount, $paymentInfo, $cart ['total']);
		$cart['total'] = $paymentArray[0];
		RedshopHelperCartSession::setCart($cart);

		$order_shipping = RedshopShippingRate::decrypt($shipping_rate_id);
		$order_status   = REDSHOP_ORDER_STATUS_PAID;
		$order_subtotal = $cart ['product_subtotal'];
		$cdiscount      = $cart ['coupon_discount'];
		$order_tax      = $cart ['tax'];
		$d['order_tax'] = $order_tax;

		$dispatcher = RedshopHelperUtility::getDispatcher();

		// Add plugin support
		JPluginHelper::importPlugin('redshop_checkout');
		$dispatcher->trigger('onBeforeOrderSave', array(&$cart, &$post, &$order_shipping));

		$tax_after_discount = 0;

		if (isset($cart ['tax_after_discount']))
		{
			$tax_after_discount = $cart ['tax_after_discount'];
		}

		$odiscount     = $cart['coupon_discount'] + $cart['voucher_discount'] + $cart['cart_discount'];
		$odiscount_vat = $cart['discount_vat'];

		$d["order_payment_trans_id"] = '';
		$d['discount']               = $odiscount;
		$order_total                 = $cart['total'];

		if ($issplit)
		{
			$order_total = $order_total / 2;
		}

		$input->set('order_ship', $order_shipping[3]);

		$paymentElementName = $paymentMethod->element;

		// Check for bank transfer payment type plugin - suffixed using `rs_payment_banktransfer`
		$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentMethod->element);

		if ($isBankTransferPaymentType || $paymentMethod->element == "rs_payment_eantransfer")
		{
			$order_status        = $paymentMethod->params->get('verify_status', '');
			$order_paymentstatus = trim("Unpaid");
		}

		$paymentMethod->element = $paymentElementName;

		$payment_amount = 0;

		if (isset($cart['payment_amount']))
		{
			$payment_amount = $cart['payment_amount'];
		}

		$payment_oprand = "";

		if (isset($cart['payment_oprand']))
		{
			$payment_oprand = $cart['payment_oprand'];
		}

		$economic_payment_terms_id = $paymentMethod->params->get('economic_payment_terms_id');
		$economic_design_layout    = $paymentMethod->params->get('economic_design_layout');
		$is_creditcard             = $paymentMethod->params->get('is_creditcard', '');
		$is_redirected             = $paymentMethod->params->get('is_redirected', 0);

		$input->set('payment_status', $order_paymentstatus);

		$d['order_shipping']         = $order_shipping [3];
		$GLOBALS['billingaddresses'] = $billingaddresses;
		$timestamp                   = time();

		$order_status_log = '';

		// For credit card payment gateway page will redirect to order detail page from plugin
		if ($is_creditcard == 1 && $is_redirected == 0 && $cart['total'] > 0)
		{
			$order_number = $order_functions->generateOrderNumber();

			JPluginHelper::importPlugin('redshop_payment');

			$values['order_shipping'] = $d['order_shipping'];
			$values['order_number']   = $order_number;
			$values['order_tax']      = $d['order_tax'];
			$values['shippinginfo']   = $d['shippingaddress'];
			$values['billinginfo']    = $d['billingaddress'];
			$values['order_total']    = $order_total;
			$values['order_subtotal'] = $order_subtotal;
			$values["order_id"]       = $app->input->get('order_id', 0);
			$values['payment_plugin'] = $paymentMethod->element;
			$values['odiscount']      = $odiscount;
			$paymentResponses         = $dispatcher->trigger('onPrePayment_' . $values['payment_plugin'], array($values['payment_plugin'], $values));
			$paymentResponse          = $paymentResponses[0];

			if ($paymentResponse->responsestatus == "Success")
			{
				$d ["order_payment_trans_id"] = $paymentResponse->transaction_id;
				$order_status_log             = $paymentResponse->message;

				if (!isset($paymentResponse->status))
				{
					$paymentResponse->status = 'C';
				}

				$order_status = $paymentResponse->status;

				if (!isset($paymentResponse->paymentStatus))
				{
					$paymentResponse->paymentStatus = 'Paid';
				}

				$order_paymentstatus = $paymentResponse->paymentStatus;
			}
			else
			{
				if ($values['payment_plugin'] != 'rs_payment_localcreditcard')
				{
					$errorMsg = $paymentResponse->message;
					$this->setError($errorMsg);

					return false;
				}
			}
		}

		// Get the IP Address
		$ip = 'unknown';

		if (!empty($_SERVER['REMOTE_ADDR']))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$row = $this->getTable('order_detail');

		if (!$row->bind($post))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$shippingVatRate = 0;

		if (array_key_exists(6, $order_shipping))
		{
			$shippingVatRate = $order_shipping [6];
		}

		// Start code to track duplicate order number checking
		$order_number = $this->_order_functions->generateOrderNumber();

		$random_gen_enc_key      = $this->_order_functions->random_gen_enc_key(35);
		$users_info_id           = $billingaddresses->users_info_id;
		$row->user_id            = $userId;
		$row->order_number       = $order_number;
		$row->user_info_id       = $users_info_id;
		$row->order_total        = $order_total;
		$row->order_subtotal     = $order_subtotal;
		$row->order_tax          = $order_tax;
		$row->tax_after_discount = $tax_after_discount;
		$row->order_tax_details  = '';
		$row->analytics_status   = 0;
		$row->order_shipping     = $order_shipping [3];
		$row->order_shipping_tax = $shippingVatRate;
		$row->coupon_discount    = $cdiscount;
		$row->shop_id            = $shop_id;
		$row->customer_message   = $customer_message;
		$row->referral_code      = $referral_code;
		$db                      = JFactory::getDbo();

		if ($order_total <= 0)
		{
			$order_status        = $paymentMethod->params->get('verify_status', '');
			$order_paymentstatus = 'Paid';
		}

		if (Redshop::getConfig()->get('USE_AS_CATALOG'))
		{
			$order_status        = REDSHOP_ORDER_STATUS_PAID;
			$order_paymentstatus = REDSHOP_ORDER_PAYMENT_STATUS_UNPAID;
		}

		$dispatcher->trigger('onOrderStatusChange', array($post, &$order_status));

		// For barcode generation
		$row->order_discount       = $odiscount;
		$row->order_discount_vat   = $odiscount_vat;
		$row->payment_discount     = $payment_amount;
		$row->payment_oprand       = $payment_oprand;
		$row->order_status         = $order_status;
		$row->order_payment_status = $order_paymentstatus;
		$row->cdate                = $timestamp;
		$row->mdate                = $timestamp;
		$row->ship_method_id       = $shipping_rate_id;
		$row->customer_note        = $post['customer_note'];
		$row->requisition_number   = $post['requisition_number'];
		$row->ip_address           = $ip;
		$row->encr_key             = $random_gen_enc_key;
		$row->discount_type        = $this->discount_type;
		$row->order_id             = $app->input->getInt('order_id', 0);
		$row->barcode              = $order_functions->barcode_randon_number(12, 0);

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			// Start code to track duplicate order number checking
			$this->deleteOrdernumberTrack();

			return false;
		}

		// Start code to track duplicate order number checking
		$this->deleteOrdernumberTrack();

		// Generate Invoice Number for confirmed credit card payment or for free order
		if (((boolean) Redshop::getConfig()->get('INVOICE_NUMBER_FOR_FREE_ORDER') || $is_creditcard)
			&& ('C' == $row->order_status && 'Paid' == $row->order_payment_status))
		{
			RedshopHelperOrder::generateInvoiceNumber($row->order_id);
		}

		$order_id = $row->order_id;

		$this->coupon($cart, $order_id);
		$this->voucher($cart, $order_id);

		$query = "UPDATE `#__redshop_orders` SET discount_type = " . $db->quote($this->discount_type) . " where order_id = " . (int) $order_id;
		$db->setQuery($query);
		$db->execute();

		if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1 && isset($post['termscondition']) && $post['termscondition'] == 1)
		{
			$this->_userhelper->updateUserTermsCondition($users_info_id, 1);
		}

		// Place order id in quotation table if it Quotation
		if (array_key_exists("quotation_id", $cart) && $cart['quotation_id'])
		{
			$quotationHelper->updateQuotationwithOrder($cart['quotation_id'], $row->order_id);
		}

		if ($row->order_status == Redshop::getConfig()->get('CLICKATELL_ORDER_STATUS'))
		{
			RedshopHelperClickatell::clickatellSMS($order_id);
		}

		$session->set('order_id', $order_id);

		// Add order status log
		$rowOrderStatus                = $this->getTable('order_status_log');
		$rowOrderStatus->order_id      = $order_id;
		$rowOrderStatus->order_status  = $order_status;
		$rowOrderStatus->date_changed  = time();
		$rowOrderStatus->customer_note = $order_status_log;
		$rowOrderStatus->store();

		$input->set('order_id', $row->order_id);
		$input->set('order_number', $row->order_number);

		if (!isset($order_shipping [5]))
		{
			$order_shipping [5] = "";
		}

		$product_delivery_time = $this->_producthelper->getProductMinDeliveryTime($cart[0]['product_id']);
		$input->set('order_delivery', $product_delivery_time);

		$idx = $cart ['idx'];

		for ($i = 0; $i < $idx; $i++)
		{
			$is_giftcard = 0;
			$product_id  = $cart [$i] ['product_id'];
			$product     = $this->_producthelper->getProductById($product_id);
			$rowitem     = $this->getTable('order_item_detail');

			if (!$rowitem->bind($post))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$rowitem->delivery_time = '';

			if (isset($cart [$i] ['giftcard_id']) && $cart [$i] ['giftcard_id'])
			{
				$is_giftcard = 1;
			}

			// Product stockroom update
			if (!$is_giftcard)
			{
				$updatestock                 = $stockroomhelper->updateStockroomQuantity($product_id, $cart [$i] ['quantity']);
				$stockroom_id_list           = $updatestock['stockroom_list'];
				$stockroom_quantity_list     = $updatestock['stockroom_quantity_list'];
				$rowitem->stockroom_id       = $stockroom_id_list;
				$rowitem->stockroom_quantity = $stockroom_quantity_list;
			}

			// End product stockroom update

			$vals = explode('product_attributes/', $cart[$i]['hidden_attribute_cartimage']);

			if (!empty($cart[$i]['attributeImage']) && file_exists(JPATH_ROOT . '/components/com_redshop/assets/images/mergeImages/' . $cart[$i]['attributeImage']))
			{
				$rowitem->attribute_image = $order_id . $cart[$i]['attributeImage'];
				$old_media                = JPATH_ROOT . '/components/com_redshop/assets/images/mergeImages/' . $cart[$i]['attributeImage'];
				$new_media                = JPATH_ROOT . '/components/com_redshop/assets/images/orderMergeImages/' . $rowitem->attribute_image;
				copy($old_media, $new_media);
			}
			elseif (!empty($vals[1]))
			{
				$rowitem->attribute_image = $vals[1];
			}

			$wrapper_price = 0;

			if (@$cart[$i]['wrapper_id'])
			{
				$wrapper_price = $cart[$i]['wrapper_price'];
			}

			if ($is_giftcard == 1)
			{
				$giftcardData                    = $this->_producthelper->getGiftcardData($cart [$i] ['giftcard_id']);
				$rowitem->product_id             = $cart [$i] ['giftcard_id'];
				$rowitem->order_item_name        = $giftcardData->giftcard_name;
				$rowitem->product_item_old_price = $cart [$i] ['product_price'];
			}
			else
			{
				$rowitem->product_id             = $product_id;
				$rowitem->product_item_old_price = $cart [$i] ['product_old_price'];
				$rowitem->supplier_id            = $product->manufacturer_id;
				$rowitem->order_item_sku         = $product->product_number;
				$rowitem->order_item_name        = $product->product_name;
			}

			$rowitem->product_item_price          = $cart [$i] ['product_price'];
			$rowitem->product_quantity            = $cart [$i] ['quantity'];
			$rowitem->product_item_price_excl_vat = $cart [$i] ['product_price_excl_vat'];
			$rowitem->product_final_price         = ($cart [$i] ['product_price'] * $cart [$i] ['quantity']);
			$rowitem->is_giftcard                 = $is_giftcard;

			$retAttArr      = $this->_producthelper->makeAttributeCart($cart [$i] ['cart_attribute'], $product_id, 0, 0, $cart [$i] ['quantity']);
			$cart_attribute = $retAttArr[0];

			// For discount calc data
			$cart_calc_data = "";

			if (isset($cart[$i]['discount_calc_output']))
			{
				$cart_calc_data = $cart[$i]['discount_calc_output'];
			}

			$retAccArr                    = $this->_producthelper->makeAccessoryCart($cart[$i]['cart_accessory'], $product_id);
			$cart_accessory               = $retAccArr[0];
			$rowitem->order_id            = $order_id;
			$rowitem->user_info_id        = $users_info_id;
			$rowitem->order_item_currency = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
			$rowitem->order_status        = $order_status;
			$rowitem->cdate               = $timestamp;
			$rowitem->mdate               = $timestamp;
			$rowitem->product_attribute   = $cart_attribute;
			$rowitem->discount_calc_data  = $cart_calc_data;
			$rowitem->product_accessory   = $cart_accessory;
			$rowitem->wrapper_price       = $wrapper_price;

			if (!empty($cart[$i]['wrapper_id']))
			{
				$rowitem->wrapper_id = $cart[$i]['wrapper_id'];
			}

			if (!empty($cart[$i]['reciver_email']))
			{
				$rowitem->giftcard_user_email = $cart[$i]['reciver_email'];
			}

			if (!empty($cart[$i]['reciver_name']))
			{
				$rowitem->giftcard_user_name  = $cart[$i]['reciver_name'];
			}

			if (RedshopHelperProductDownload::checkDownload($rowitem->product_id))
			{
				$medianame = $this->_producthelper->getProductMediaName($rowitem->product_id);

				for ($j = 0, $jn = count($medianame); $j < $jn; $j++)
				{
					$product_serial_number = $this->_producthelper->getProdcutSerialNumber($rowitem->product_id);
					$this->_producthelper->insertProductDownload($rowitem->product_id, $user->id, $rowitem->order_id, $medianame[$j]->media_name, $product_serial_number->serial_number);
				}
			}

			// Import files for plugin
			JPluginHelper::importPlugin('redshop_product');

			if (!$rowitem->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// Add plugin support
			$dispatcher->trigger('afterOrderItemSave', array($cart, $rowitem, $i));

			// End

			if (isset($cart [$i] ['giftcard_id']) && $cart [$i] ['giftcard_id'])
			{
				$section_id = 13;
			}
			else
			{
				$section_id = 12;
			}

			$this->_producthelper->insertProdcutUserfield($i, $cart, $rowitem->order_item_id, $section_id);

			// My accessory save in table start
			if (count($cart [$i] ['cart_accessory']) > 0)
			{
				$setPropEqual    = true;
				$setSubpropEqual = true;
				$attArr          = $cart [$i] ['cart_accessory'];

				for ($a = 0, $an = count($attArr); $a < $an; $a++)
				{
					$accessory_vat_price = 0;
					$accessory_attribute = "";

					$accessory_id        = $attArr[$a]['accessory_id'];
					$accessory_name      = $attArr[$a]['accessory_name'];
					$accessory_price     = $attArr[$a]['accessory_price'];
					$accessory_quantity  = $attArr[$a]['accessory_quantity'];
					$accessory_org_price = $accessory_price;

					if ($accessory_price > 0)
					{
						$accessory_vat_price = $this->_producthelper->getProductTax($rowitem->product_id, $accessory_price);
					}

					$attchildArr = $attArr[$a]['accessory_childs'];

					for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
					{
						$prooprand     = array();
						$proprice      = array();

						$propArr       = $attchildArr[$j]['attribute_childs'];
						$totalProperty = count($propArr);

						if ($totalProperty)
						{

							$attribute_id = $attchildArr[$j]['attribute_id'];
							$accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

							$rowattitem                    = $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id = 0;
							$rowattitem->order_item_id     = $rowitem->order_item_id;
							$rowattitem->section_id        = $attribute_id;
							$rowattitem->section           = "attribute";
							$rowattitem->parent_section_id = $accessory_id;
							$rowattitem->section_name      = $attchildArr[$j]['attribute_name'];
							$rowattitem->is_accessory_att  = 1;

							if ($attribute_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}

						for ($k = 0; $k < $totalProperty; $k++)
						{
							$prooprand[$k] = $propArr[$k]['property_oprand'];
							$proprice[$k]  = $propArr[$k]['property_price'];
							$section_vat   = 0;

							if ($propArr[$k]['property_price'] > 0)
							{
								$section_vat = $this->_producthelper->getProducttax($rowitem->product_id, $propArr[$k]['property_price']);
							}

							$property_id = $propArr[$k]['property_id'];
							$accessory_attribute .= urldecode($propArr[$k]['property_name']) . " (" . $propArr[$k]['property_oprand'] . $this->_producthelper->getProductFormattedPrice($propArr[$k]['property_price'] + $section_vat) . ")<br/>";
							$subpropArr                    = $propArr[$k]['property_childs'];
							$rowattitem                    = $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id = 0;
							$rowattitem->order_item_id     = $rowitem->order_item_id;
							$rowattitem->section_id        = $property_id;
							$rowattitem->section           = "property";
							$rowattitem->parent_section_id = $attribute_id;
							$rowattitem->section_name      = $propArr[$k]['property_name'];
							$rowattitem->section_price     = $propArr[$k]['property_price'];
							$rowattitem->section_vat       = $section_vat;
							$rowattitem->section_oprand    = $propArr[$k]['property_oprand'];
							$rowattitem->is_accessory_att  = 1;

							if ($property_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}

							for ($l = 0, $nl = count($subpropArr); $l < $nl; $l++)
							{
								$section_vat = 0;

								if ($subpropArr[$l]['subproperty_price'] > 0)
								{
									$section_vat = $this->_producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price']);
								}

								$subproperty_id = $subpropArr[$l]['subproperty_id'];
								$accessory_attribute .= urldecode($subpropArr[$l]['subproperty_name']) . " (" . $subpropArr[$l]['subproperty_oprand'] . $this->_producthelper->getProductFormattedPrice($subpropArr[$l]['subproperty_price'] + $section_vat) . ")<br/>";
								$rowattitem                    = $this->getTable('order_attribute_item');
								$rowattitem->order_att_item_id = 0;
								$rowattitem->order_item_id     = $rowitem->order_item_id;
								$rowattitem->section_id        = $subproperty_id;
								$rowattitem->section           = "subproperty";
								$rowattitem->parent_section_id = $property_id;
								$rowattitem->section_name      = $subpropArr[$l]['subproperty_name'];
								$rowattitem->section_price     = $subpropArr[$l]['subproperty_price'];
								$rowattitem->section_vat       = $section_vat;
								$rowattitem->section_oprand    = $subpropArr[$l]['subproperty_oprand'];
								$rowattitem->is_accessory_att  = 1;

								if ($subproperty_id > 0)
								{
									if (!$rowattitem->store())
									{
										$this->setError($this->_db->getErrorMsg());

										return false;
									}
								}
							}
						}

						// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
						if ($setPropEqual && $setSubpropEqual)
						{
							$accessory_priceArr = $this->_producthelper->makeTotalPriceByOprand($accessory_price, $prooprand, $proprice);
							$setPropEqual       = $accessory_priceArr[0];
							$accessory_price    = $accessory_priceArr[1];
						}

						for ($t = 0, $countProperty = count($propArr), $tn = $countProperty; $t < $tn; $t++)
						{
							$subprooprand  = array();
							$subproprice   = array();
							$subElementArr = $propArr[$t]['property_childs'];

							for ($tp = 0, $countElement = count($subElementArr); $tp < $countElement; $tp++)
							{
								$subprooprand[$tp] = $subElementArr[$tp]['subproperty_oprand'];
								$subproprice[$tp]  = $subElementArr[$tp]['subproperty_price'];
							}

							if ($setPropEqual && $setSubpropEqual)
							{
								$accessory_priceArr = $this->_producthelper->makeTotalPriceByOprand($accessory_price, $subprooprand, $subproprice);
								$setSubpropEqual    = $accessory_priceArr[0];
								$accessory_price    = $accessory_priceArr[1];
							}
						}
					}

					$accdata = $this->getTable('accessory_detail');

					if ($accessory_id > 0)
					{
						$accdata->load($accessory_id);
					}

					$accProductinfo                      = $this->_producthelper->getProductById($accdata->child_product_id);
					$rowaccitem                          = $this->getTable('order_acc_item');
					$rowaccitem->order_item_acc_id       = 0;
					$rowaccitem->order_item_id           = $rowitem->order_item_id;
					$rowaccitem->product_id              = $accessory_id;
					$rowaccitem->order_acc_item_sku      = $accProductinfo->product_number;
					$rowaccitem->order_acc_item_name     = $accessory_name;
					$rowaccitem->order_acc_price         = $accessory_org_price;
					$rowaccitem->order_acc_vat           = $accessory_vat_price;
					$rowaccitem->product_quantity        = $accessory_quantity;
					$rowaccitem->product_acc_item_price  = $accessory_price;
					$rowaccitem->product_acc_final_price = ($accessory_price * $accessory_quantity);
					$rowaccitem->product_attribute       = $accessory_attribute;

					if ($accessory_id > 0)
					{
						if (!$rowaccitem->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}
				}
			}

			// Storing attribute in database
			if (count($cart [$i] ['cart_attribute']) > 0)
			{
				$attchildArr = $cart [$i] ['cart_attribute'];

				for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
				{
					$propArr       = $attchildArr[$j]['attribute_childs'];
					$totalProperty = count($propArr);

					if ($totalProperty > 0)
					{
						$attribute_id                  = $attchildArr[$j]['attribute_id'];
						$rowattitem                    = $this->getTable('order_attribute_item');
						$rowattitem->order_att_item_id = 0;
						$rowattitem->order_item_id     = $rowitem->order_item_id;
						$rowattitem->section_id        = $attribute_id;
						$rowattitem->section           = "attribute";
						$rowattitem->parent_section_id = $rowitem->product_id;
						$rowattitem->section_name      = $attchildArr[$j]['attribute_name'];
						$rowattitem->is_accessory_att  = 0;

						if ($attribute_id > 0)
						{
							if (!$rowattitem->store())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
						}

						for ($k = 0; $k < $totalProperty; $k++)
						{
							$section_vat = 0;

							if ($propArr[$k]['property_price'] > 0)
							{
								$section_vat = $this->_producthelper->getProducttax($rowitem->product_id, $propArr[$k]['property_price']);
							}

							$property_id = $propArr[$k]['property_id'];

							//  Product property STOCKROOM update start
							$updatestock_att             = $stockroomhelper->updateStockroomQuantity($property_id, $cart [$i] ['quantity'], "property", $product_id);
							$stockroom_att_id_list       = $updatestock_att['stockroom_list'];
							$stockroom_att_quantity_list = $updatestock_att['stockroom_quantity_list'];

							$rowattitem                     = $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id  = 0;
							$rowattitem->order_item_id      = $rowitem->order_item_id;
							$rowattitem->section_id         = $property_id;
							$rowattitem->section            = "property";
							$rowattitem->parent_section_id  = $attribute_id;
							$rowattitem->section_name       = $propArr[$k]['property_name'];
							$rowattitem->section_price      = $propArr[$k]['property_price'];
							$rowattitem->section_vat        = $section_vat;
							$rowattitem->section_oprand     = $propArr[$k]['property_oprand'];
							$rowattitem->is_accessory_att   = 0;
							$rowattitem->stockroom_id       = $stockroom_att_id_list;
							$rowattitem->stockroom_quantity = $stockroom_att_quantity_list;

							if ($property_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}

							$subpropArr = $propArr[$k]['property_childs'];

							for ($l = 0, $nl = count($subpropArr); $l < $nl; $l++)
							{
								$section_vat = 0;

								if ($subpropArr[$l]['subproperty_price'] > 0)
								{
									$section_vat = $this->_producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price']);
								}

								$subproperty_id = $subpropArr[$l]['subproperty_id'];

								// Product subproperty STOCKROOM update start
								$updatestock_subatt             = $stockroomhelper->updateStockroomQuantity($subproperty_id, $cart [$i] ['quantity'], "subproperty", $product_id);
								$stockroom_subatt_id_list       = $updatestock_subatt['stockroom_list'];
								$stockroom_subatt_quantity_list = $updatestock_subatt['stockroom_quantity_list'];

								$rowattitem                     = $this->getTable('order_attribute_item');
								$rowattitem->order_att_item_id  = 0;
								$rowattitem->order_item_id      = $rowitem->order_item_id;
								$rowattitem->section_id         = $subproperty_id;
								$rowattitem->section            = "subproperty";
								$rowattitem->parent_section_id  = $property_id;
								$rowattitem->section_name       = $subpropArr[$l]['subproperty_name'];
								$rowattitem->section_price      = $subpropArr[$l]['subproperty_price'];
								$rowattitem->section_vat        = $section_vat;
								$rowattitem->section_oprand     = $subpropArr[$l]['subproperty_oprand'];
								$rowattitem->is_accessory_att   = 0;
								$rowattitem->stockroom_id       = $stockroom_subatt_id_list;
								$rowattitem->stockroom_quantity = $stockroom_subatt_quantity_list;

								if ($subproperty_id > 0)
								{
									if (!$rowattitem->store())
									{
										$this->setError($this->_db->getErrorMsg());

										return false;
									}
								}
							}
						}
					}
				}
			}

			// Store user product subscription detail
			if ($product->product_type == 'subscription')
			{
				$subscribe           = $this->getTable('product_subscribe_detail');
				$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($product_id, $cart[$i]['subscription_id']);

				$add_day                    = $subscription_detail->period_type == 'days' ? $subscription_detail->subscription_period : 0;
				$add_month                  = $subscription_detail->period_type == 'month' ? $subscription_detail->subscription_period : 0;
				$add_year                   = $subscription_detail->period_type == 'year' ? $subscription_detail->subscription_period : 0;
				$subscribe->order_id        = $order_id;
				$subscribe->order_item_id   = $rowitem->order_item_id;
				$subscribe->product_id      = $product_id;
				$subscribe->subscription_id = $cart[$i]['subscription_id'];
				$subscribe->user_id         = $user->id;
				$subscribe->start_date      = time();
				$subscribe->end_date        = mktime(0, 0, 0, date('m') + $add_month, date('d') + $add_day, date('Y') + $add_year);

				if (!$subscribe->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		$rowpayment = $this->getTable('order_payment');

		if (!$rowpayment->bind($post))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$rowpayment->order_id          = $order_id;
		$rowpayment->payment_method_id = $payment_method_id;

		$ccdata = $session->get('ccdata');

		if (!isset($ccdata['creditcard_code']))
		{
			$ccdata['creditcard_code'] = 0;
		}

		if (!isset($ccdata['order_payment_number']))
		{
			$ccdata['order_payment_number'] = 0;
		}

		if (!isset($ccdata['order_payment_expire_month']))
		{
			$ccdata['order_payment_expire_month'] = 0;
		}

		if (!isset($ccdata['order_payment_expire_year']))
		{
			$ccdata['order_payment_expire_year'] = 0;
		}

		$rowpayment->order_payment_code     = $ccdata['creditcard_code'];
		$rowpayment->order_payment_cardname = base64_encode($ccdata['order_payment_name']);
		$rowpayment->order_payment_number   = base64_encode($ccdata['order_payment_number']);

		// This is ccv code
		$rowpayment->order_payment_ccv      = base64_encode($ccdata['credit_card_code']);
		$rowpayment->order_payment_amount   = $order_total;
		$rowpayment->order_payment_expire   = $ccdata['order_payment_expire_month'] . $ccdata['order_payment_expire_year'];
		$rowpayment->order_payment_name     = $paymentMethod->name;
		$rowpayment->payment_method_class   = $paymentMethod->element;
		$rowpayment->order_payment_trans_id = $d ["order_payment_trans_id"];
		$rowpayment->authorize_status       = "";

		if (!$rowpayment->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// For authorize status
		JPluginHelper::importPlugin('redshop_payment');
		JDispatcher::getInstance()->trigger('onAuthorizeStatus_' . $paymentMethod->element, array($paymentMethod->element, $order_id));

		$GLOBALS['shippingaddresses'] = $shippingaddresses;

		// Add billing Info
		$userrow = $this->getTable('user_detail');
		$userrow->load($billingaddresses->users_info_id);
		$userrow->thirdparty_email = $post['thirdparty_email'];
		$orderuserrow              = $this->getTable('order_user_detail');

		if (!$orderuserrow->bind($userrow))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$orderuserrow->order_id     = $order_id;
		$orderuserrow->address_type = 'BT';

		if (!$orderuserrow->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Add shipping Info
		$userrow = $this->getTable('user_detail');

		if (isset($shippingaddresses->users_info_id))
		{
			$userrow->load($shippingaddresses->users_info_id);
		}

		$orderuserrow = $this->getTable('order_user_detail');

		if (!$orderuserrow->bind($userrow))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$orderuserrow->order_id     = $order_id;
		$orderuserrow->address_type = 'ST';

		if (!$orderuserrow->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (isset($cart['extrafields_values']))
		{
			if (count($cart['extrafields_values']) > 0)
			{
				$this->_producthelper->insertPaymentShippingField($cart, $order_id, 18);
				$this->_producthelper->insertPaymentShippingField($cart, $order_id, 19);
			}
		}

		$stockroomhelper->deleteCartAfterEmpty();

		// Economic Integration start for invoice generate and book current invoice
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') != 2)
		{
			$economic = economic::getInstance();

			$economicdata['economic_payment_terms_id'] = $economic_payment_terms_id;
			$economicdata['economic_design_layout']    = $economic_design_layout;
			$economicdata['economic_is_creditcard']    = $is_creditcard;
			$payment_name                              = $paymentMethod->element;
			$paymentArr                                = explode("rs_payment_", $paymentMethod->element);

			if (count($paymentArr) > 0)
			{
				$payment_name = $paymentArr[1];
			}

			$economicdata['economic_payment_method'] = $payment_name;
			Economic::createInvoiceInEconomic($row->order_id, $economicdata);

			if (Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 0)
			{
				$checkOrderStatus = ($isBankTransferPaymentType) ? 0 : 1;

				$bookinvoicepdf = Economic::bookInvoiceInEconomic($row->order_id, $checkOrderStatus);

				if (JFile::exists($bookinvoicepdf))
				{
					RedshopHelperMail::sendEconomicBookInvoiceMail($row->order_id, $bookinvoicepdf);
				}
			}
		}

		// Send the Order mail before payment
		if (!Redshop::getConfig()->get('ORDER_MAIL_AFTER') || (Redshop::getConfig()->get('ORDER_MAIL_AFTER') && $row->order_payment_status == "Paid"))
		{
			$this->_redshopMail->sendOrderMail($row->order_id);
		}
		elseif (Redshop::getConfig()->get('ORDER_MAIL_AFTER') == 1)
		{
			// If Order mail set to send after payment then send mail to administrator only.
			$this->_redshopMail->sendOrderMail($row->order_id, true);
		}

		if ($row->order_status == "C" && $row->order_payment_status == "Paid")
		{
			$this->_order_functions->SendDownload($row->order_id);
		}

		return $row;
	}

	/**
	 * Method for send giftcard email to customer.
	 *
	 * @param   int  $order_id  ID of order.
	 *
	 * @return  void
	 */
	public function sendGiftCard($order_id)
	{
		$giftcardmail = RedshopHelperMail::getMailTemplate(0, "giftcard_mail");

		if (count($giftcardmail) > 0)
		{
			$giftcardmail = $giftcardmail[0];
		}

		$giftCards = RedshopHelperOrder::giftCardItems((int) $order_id);

		foreach ($giftCards as $eachorders)
		{
			$giftcardmailsub   = $giftcardmail->mail_subject;
			$giftcardData      = $this->_producthelper->getGiftcardData($eachorders->product_id);
			$giftcard_value    = $this->_producthelper->getProductFormattedPrice($giftcardData->giftcard_value, true);
			$giftcard_price    = $eachorders->product_final_price;
			$giftcardmail_body = $giftcardmail->mail_body;
			$giftcardmail_body = str_replace('{giftcard_name}', $giftcardData->giftcard_name, $giftcardmail_body);
			$user_fields       = $this->_producthelper->GetProdcutUserfield($eachorders->order_item_id, 13);
			$giftcardmail_body = str_replace("{product_userfields}", $user_fields, $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_price_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_PRICE_LBL'), $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_price}", $this->_producthelper->getProductFormattedPrice($giftcard_price), $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_reciver_name_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL'), $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_reciver_email_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL'), $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_reciver_email}", $eachorders->giftcard_user_email, $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_reciver_name}", $eachorders->giftcard_user_name, $giftcardmail_body);
			$giftcardmail_body = $this->_producthelper->getValidityDate($giftcardData->giftcard_validity, $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_value}", $giftcard_value, $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_value_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_VALUE_LBL'), $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_desc}", $giftcardData->giftcard_desc, $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_validity}", $giftcardData->giftcard_validity, $giftcardmail_body);
			$giftcardmailsub   = str_replace('{giftcard_name}', $giftcardData->giftcard_name, $giftcardmailsub);
			$giftcardmailsub   = str_replace('{giftcard_price}', $this->_producthelper->getProductFormattedPrice($giftcard_price), $giftcardmailsub);
			$giftcardmailsub   = str_replace('{giftcard_value}', $giftcard_value, $giftcardmailsub);
			$giftcardmailsub   = str_replace('{giftcard_validity}', $giftcardData->giftcard_validity, $giftcardmailsub);
			$gift_code         = RedshopHelperOrder::randomGenerateEncryptKey(12);
			$couponItems       = $this->getTable('coupon_detail');

			if ($giftcardData->customer_amount)
			{
				$giftcardData->giftcard_value = $eachorders->product_final_price;
			}

			$couponItems->coupon_code      = $gift_code;
			$couponItems->percent_or_total = 0;
			$couponItems->coupon_value     = $giftcardData->giftcard_value;
			$couponItems->start_date       = strtotime(date('d M Y'));
			$couponItems->end_date         = mktime(0, 0, 0, date('m'), date('d') + $giftcardData->giftcard_validity, date('Y'));
			$couponItems->coupon_type      = 0;
			$couponItems->userid           = 0;
			$couponItems->coupon_left      = 1;
			$couponItems->published        = 1;
			$couponItems->free_shipping    = $giftcardData->free_shipping;

			if (!$couponItems->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$giftcardmail_body = str_replace("{giftcard_code_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_CODE_LBL'), $giftcardmail_body);
			$giftcardmail_body = str_replace("{giftcard_code}", $gift_code, $giftcardmail_body);
			ob_flush();
			ob_clean();
			echo "<div id='redshopcomponent' class='redshop'>";
			$is_giftcard = 1;
			$giftcard_attachment = null;
			$pdfImage = "";
			$mailImage = '';

			if ($giftcardData->giftcard_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftcardData->giftcard_image))
			{
				$pdfImage = '<img src="' . REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftcardData->giftcard_image . '" alt="test alt attribute" width="150px" height="150px" border="0" />';
				$mailImage = '<img src="components/com_redshop/assets/images/giftcard/' . $giftcardData->giftcard_image . '" alt="test alt attribute" width="150px" height="150px" border="0" />';
			}

			if (RedshopHelperPdf::isAvailablePdfPlugins())
			{
				$pdfMailBody = $giftcardmail_body;
				$pdfMailBody = str_replace("{giftcard_image}", $pdfImage, $pdfMailBody);

				JPluginHelper::importPlugin('redshop_pdf');

				$pdfFile = RedshopHelperUtility::getDispatcher()->trigger(
					'onRedshopCreateGiftCardPdf',
					array($giftcardData, $pdfMailBody, $backgroundImage)
				);

				if (!empty($pdfFile))
				{
					$giftcard_attachment = JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfFile[0] . ".pdf";
				}
			}

			$config              = JFactory::getConfig();
			$from                = $config->get('mailfrom');
			$fromname            = $config->get('fromname');
			$giftcardmail_body = str_replace("{giftcard_image}", $mailImage, $giftcardmail_body);
			$giftcardmail_body = RedshopHelperMail::imgInMail($giftcardmail_body);

			JFactory::getMailer()->sendMail(
				$from, $fromname, $eachorders->giftcard_user_email, $giftcardmailsub, $giftcardmail_body, 1, null, null, $giftcard_attachment
			);
		}
	}

	public function billingaddresses()
	{
		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$list    = array();

		if ($user->id)
		{
			$list = $this->_order_functions->getBillingAddress($user->id);
		}
		elseif ($auth['users_info_id'])
		{
			$uid  = - $auth['users_info_id'];
			$list = $this->_order_functions->getBillingAddress($uid);
		}

		return $list;
	}

	public function shipaddress($users_info_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info '
			. 'WHERE users_info_id = ' . (int) $users_info_id;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function shippingaddresses()
	{
		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$list    = array();

		if ($user->id)
		{
			$list = $this->_order_functions->getShippingAddress($user->id);
		}
		else
		{
			$uid  = - $auth['users_info_id'];
			$list = $this->_order_functions->getShippingAddress($uid);
		}

		return $list;
	}

	public function getpaymentmethod()
	{
		$user          = JFactory::getUser();
		$shopper_group = $this->_order_functions->getBillingAddress($user->id);
		$query         = "SELECT * FROM " . $this->_table_prefix . "payment_method WHERE published = '1' AND (FIND_IN_SET('" . (int) $shopper_group->shopper_group_id . "', shopper_group) OR shopper_group = '') ORDER BY ordering ASC";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function validatepaymentccinfo()
	{
		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');

		$validpayment [0] = 1;
		$validpayment [1] = '';

		if ($ccdata['selectedCardId'] != '')
		{
			return $validpayment;
		}

		// The Data should be in the session.
		if (!isset($ccdata))
		{
			$validpayment [0] = 0;
			$validpayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCDATA');

			return $validpayment;
		}

		if (isset($ccdata['order_payment_name']))
		{
			if (preg_match("/[0-9]+/", $ccdata['order_payment_name']) == true)
			{
				$validpayment [0] = 0;
				$validpayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCNM_FOUND');

				return $validpayment;
			}
		}

		if (!$ccdata['order_payment_number'])
		{
			$validpayment [0] = 0;
			$validpayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCNR_FOUND');

			return $validpayment;
		}

		if ($ccdata['order_payment_number'])
		{
			if (!is_numeric($ccdata['order_payment_number']))
			{
				$validpayment [0] = 0;
				$validpayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCNR_NUM_FOUND');

				return $validpayment;
			}
		}

		if (!$ccdata['order_payment_expire_month'])
		{
			$validpayment [0] = 0;
			$validpayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_MON_FOUND');

			return $validpayment;
		}

		$ccerror     = '';
		$ccerrortext = '';

		if (!$this->checkCreditCard($ccdata['order_payment_number'], $ccdata['creditcard_code'], $ccerror, $ccerrortext))
		{
			$validpayment [0] = 0;
			$validpayment [1] = $ccerrortext;

			return $validpayment;
		}

		return $validpayment;
	}

	public function checkCreditCard($cardnumber, $cardname, &$errornumber, &$errortext)
	{
		/**
		 * Define the cards we support. You may add additional card types.
		 *
		 * Name:      As in the selection box of the form - must be same as user's
		 * Length:    List of possible valid lengths of the card number for the card
		 * Prefixes:  List of possible prefixes for the card
		 *
		 * Checkdigit Boolean to say whether there is a check digit
		 * Don't forget - all but the last array definition needs a comma separator!
		 */

		$cards = array(

			// American Express
			array(
				'name'   => 'amex',
				'length' => '15',
				'prefixes' => '34,37',
				'checkdigit' => true
			),
			array(
				'name' => 'Diners Club Carte Blanche',
				'length' => '14',
				'prefixes' => '300,301,302,303,304,305',
				'checkdigit' => true
			),

			// Diners Club
			array(
				'name'   => 'diners',
				'length' => '14,16',
				'prefixes' => '36,54,55',
				'checkdigit' => true
			),
			array(
				'name' => 'Discover',
				'length' => '16',
				'prefixes' => '6011,622,64,65',
				'checkdigit' => true
			),
			array(
				'name' => 'Diners Club Enroute',
				'length' => '15',
				'prefixes' => '2014,2149',
				'checkdigit' => true
			),
			array(
				'name' => 'JCB',
				'length' => '16',
				'prefixes' => '35',
				'checkdigit' => true
			),
			array(
				'name' => 'Maestro',
				'length' => '12,13,14,15,16,18,19',
				'prefixes' => '5018,5020,5038,6304,6759,6761',
				'checkdigit' => true
			),

			// MasterCard
			array(
				'name'   => 'MC',
				'length' => '16',
				'prefixes' => '51,52,53,54,55',
				'checkdigit' => true
			),
			array(
				'name' => 'Solo',
				'length' => '16,18,19',
				'prefixes' => '6334,6767',
				'checkdigit' => true
			),
			array(
				'name' => 'Switch',
				'length' => '16,18,19',
				'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
				'checkdigit' => true
			),
			array(
				'name' => 'Visa',
				'length' => '13,16',
				'prefixes' => '4',
				'checkdigit' => true
			),
			array(
				'name' => 'Visa Electron',
				'length' => '16',
				'prefixes' => '417500,4917,4913,4508,4844',
				'checkdigit' => true
			),
			array(
				'name' => 'LaserCard',
				'length' => '16,17,18,19',
				'prefixes' => '6304,6706,6771,6709',
				'checkdigit' => true
			)
		);

		$ccErrorNo = 0;

		$ccErrors [0] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_UNKNOWN_CCTYPE');
		$ccErrors [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_PROVIDED');
		$ccErrors [2] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_INVALIDFORMAT');
		$ccErrors [3] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_INVALIDNUMBER');
		$ccErrors [4] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_WRONGLENGTH');

		// Establish card type
		$cardType = -1;

		for ($i = 0, $in = count($cards); $i < $in; $i++)
		{
			// See if it is this card (ignoring the case of the string)
			if (strtolower($cardname) == strtolower($cards [$i] ['name']))
			{
				$cardType = $i;
				break;
			}
		}

		// If card type not found, report an error
		if ($cardType == -1)
		{
			$errornumber = 0;
			$errortext   = $ccErrors [$errornumber];

			return false;
		}

		// Ensure that the user has provided a credit card number
		if (strlen($cardnumber) == 0)
		{
			$errornumber = 1;
			$errortext   = $ccErrors [$errornumber];

			return false;
		}

		// Remove any spaces from the credit card number
		$cardNo = str_replace(' ', '', $cardnumber);

		// Check that the number is numeric and of the right sort of length.
		if (!preg_match("/^[0-9]{13,19}$/i", $cardNo))
		{
			$errornumber = 2;
			$errortext   = $ccErrors [$errornumber];

			return false;
		}

		// Now check the modulus 10 check digit - if required
		if ($cards [$cardType] ['checkdigit'])
		{
			// Running checksum total
			$checksum = 0;

			// Next char to process
			$mychar = "";

			// Takes value of 1 or 2
			$j = 1;

			// Process each digit one by one starting at the right
			for ($i = strlen($cardNo) - 1; $i >= 0; $i--)
			{
				// Extract the next digit and multiply by 1 or 2 on alternative digits.
				$calc = $cardNo{$i} * $j;

				// If the result is in two digits add 1 to the checksum total
				if ($calc > 9)
				{
					$checksum++;
					$calc     = $calc - 10;
				}

				// Add the units element to the checksum total
				$checksum = $checksum + $calc;

				// Switch the value of j
				if ($j == 1)
				{
					$j = 2;
				}
				else
				{
					$j = 1;
				}
			}

			// All done - if checksum is divisible by 10, it is a valid modulus 10.
			// If not, report an error.
			if ($checksum % 10 != 0)
			{
				$errornumber = 3;
				$errortext   = $ccErrors [$errornumber];

				return false;
			}
		}

		// The following are the card-specific checks we undertake.

		// Load an array with the valid prefixes for this card
		$prefix = explode(',', $cards[$cardType]['prefixes']);

		// Now see if any of them match what we have in the card number

		$PrefixValid = false;

		for ($i = 0, $in = count($prefix); $i < $in; $i++)
		{
			$exp = '/^' . $prefix [$i] . '/';

			if (preg_match($exp, $cardNo))
			{
				$PrefixValid = true;
				break;
			}
		}

		// If it isn't a valid prefix there's no point at looking at the length
		if (!$PrefixValid)
		{
			$errornumber = 3;
			$errortext   = $ccErrors [$errornumber];

			return false;
		}

		// See if the length is valid for this card
		$LengthValid = false;
		$lengths     = explode(',', $cards[$cardType]['length']);

		for ($j = 0, $jn = count($lengths); $j < $jn; $j++)
		{
			if (strlen($cardNo) == $lengths [$j])
			{
				$LengthValid = true;
				break;
			}
		}

		// See if all is OK by seeing if the length was valid.
		if (!$LengthValid)
		{
			$errornumber = 4;
			$errortext   = $ccErrors [$errornumber];

			return false;
		}

		// The credit card is in the required format.
		return true;
	}

	public function validateCC($cc_num, $type)
	{
		if ($type == "American")
		{
			$denum = "American Express";
		}
		elseif ($type == "Dinners")
		{
			$denum = "Diner's Club";
		}
		elseif ($type == "Discover")
		{
			$denum = "Discover";
		}
		elseif ($type == "Master")
		{
			$denum = "Master Card";
		}
		elseif ($type == "Visa")
		{
			$denum = "Visa";
		}

		// American Express
		if ($type == "American")
		{
			$pattern = "/^([34|37]{2})([0-9]{13})$/";

			if (preg_match($pattern, $cc_num))
			{
				$verified = true;
			}
			else
			{
				$verified = false;
			}
		}

		// Diner's Club
		elseif ($type == "Dinners")
		{
			$pattern = "/^([30|36|38]{2})([0-9]{12})$/";

			if (preg_match($pattern, $cc_num))
			{
				$verified = true;
			}
			else
			{
				$verified = false;
			}

		}

		// Discover Card
		elseif ($type == "Discover")
		{
			$pattern = "/^([6011]{4})([0-9]{12})$/";

			if (preg_match($pattern, $cc_num))
			{
				$verified = true;
			}
			else
			{
				$verified = false;
			}

		}

		// Mastercard
		elseif ($type == "Master")
		{
			$pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";

			if (preg_match($pattern, $cc_num))
			{
				$verified = true;
			}
			else
			{
				$verified = false;
			}

		}

		// Visa
		elseif ($type == "Visa")
		{
			$pattern = "/^([4]{1})([0-9]{12,15})$/";

			if (preg_match($pattern, $cc_num))
			{
				$verified = true;
			}
			else
			{
				$verified = false;
			}

		}

		if ($verified == false)
		{
			// Do something here in case the validation fails
			echo "Credit card invalid. Please make sure that you entered a valid <em>" . $denum . "</em> credit card ";
		}
		// If it will pass...do something
		else
		{
			echo "Your <em>" . $denum . "</em> credit card is valid";
		}
	}

	public function resetcart()
	{
		$session = JFactory::getSession();
		setcookie("redSHOPcart", "", time() - 3600, "/");
		RedshopHelperCartSession::setCart(null);
		$session->set('ccdata', null);
		$session->set('issplit', null);
		$session->set('userfield', null);
		$user = JFactory::getUser();
		$this->_carthelper->removecartfromdb($cart_id = 0, $user->id, $delCart = true);
	}

	public function getCouponPrice()
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$db = JFactory::getDbo();
		$query   = "SELECT coupon_value,percent_or_total FROM " . $this->_table_prefix . "coupons "
			. "WHERE coupon_id = " . (int) $cart['coupon_id'] . " "
			. "AND coupon_code = " . $db->quote($cart['coupon_code']) . " LIMIT 0,1";
		$db->setQuery($query);
		$row = $db->loadObject();

		if (count($row) > 0)
		{
			if ($row->percent_or_total == 1)
			{
				$coupon_amount = ($cart['product_subtotal'] * $row->coupon_value) / (100);
			}
			else
			{
				$coupon_amount = $row->coupon_value;
			}
		}

		return $coupon_amount;
	}

	public function getCategoryNameByProductId($pid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('c.name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->where($db->qn('c.name') . ' IS NOT NULL')
			->order($db->qn('c.id') . ' ASC')
			->setLimit(0, 1);

		return $db->setQuery($query)->loadResult();
	}

	public function voucher($cart, $order_id)
	{
		$session = JFactory::getSession();

		$user        = JFactory::getUser();
		$vouchertype = array();

		if (isset($cart['voucher']))
		{
			if ($this->discount_type)
				$this->discount_type .= '@';

			for ($i = 0, $countVoucher = count($cart['voucher']); $i < $countVoucher; $i++)
			{
				$voucher_id             = $cart['voucher'][$i]['voucher_id'];
				$voucher_volume         = $cart['voucher'][$i]['used_voucher'];
				$transaction_voucher_id = 0;
				$vouchertype[]          = 'v:' . $cart['voucher'][$i]['voucher_code'];
				$sql                    = "UPDATE " . $this->_table_prefix . "product_voucher SET voucher_left = voucher_left - " . (int) $voucher_volume . " "
					. "WHERE voucher_id  = " . (int) $voucher_id;
				$this->_db->setQuery($sql);
				$this->_db->execute();

				if ($cart['voucher'][$i]['remaining_voucher_discount'] > 0)
				{
					$rowvoucher = $this->getTable('transaction_voucher_detail');

					if (!$rowvoucher->bind($cart))
					{
						$this->setError($this->_db->getErrorMsg());
					}

					if ($cart['voucher'][$i]['transaction_voucher_id'])
					{
						$transaction_voucher_id = $cart['voucher'][$i]['transaction_voucher_id'];
					}

					$rowvoucher->transaction_voucher_id = $transaction_voucher_id;
					$rowvoucher->amount                 = $cart['voucher'][$i]['remaining_voucher_discount'];
					$rowvoucher->voucher_code           = $cart['voucher'][$i]['voucher_code'];
					$rowvoucher->user_id                = $user->id;
					$rowvoucher->order_id               = $order_id;
					$rowvoucher->voucher_id             = $voucher_id;
					$rowvoucher->trancation_date        = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$rowvoucher->product_id             = $cart['voucher'][$i]['product_id'];
					$rowvoucher->published              = 1;

					if (!$rowvoucher->store())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}

				}
			}

			$this->discount_type .= implode('@', $vouchertype);
		}

		return;
	}

	public function coupon($cart, $order_id = 0)
	{
		$session = JFactory::getSession();

		$user       = JFactory::getUser();
		$coupontype = array();

		if (isset($cart['coupon']))
		{
			if ($this->discount_type)
			{
				$this->discount_type .= '@';
			}

			for ($i = 0, $countCoupon = count($cart['coupon']); $i < $countCoupon; $i++)
			{
				$coupon_id             = $cart['coupon'][$i]['coupon_id'];
				$coupon_volume         = $cart['coupon'][$i]['used_coupon'];
				$transaction_coupon_id = 0;
				$coupontype[]          = 'c:' . $cart['coupon'][$i]['coupon_code'];

				$rowcouponDetail = $this->getTable('coupon_detail');
				$sql             = "UPDATE " . $this->_table_prefix . "coupons SET coupon_left = coupon_left - " . (int) $coupon_volume . " "
					. "WHERE coupon_id  = " . (int) $coupon_id;
				$this->_db->setQuery($sql);
				$this->_db->execute();

				if ($cart['coupon'][$i]['remaining_coupon_discount'] > 0)
				{
					$rowcoupon = $this->getTable('transaction_coupon_detail');

					if (!$rowcoupon->bind($cart))
					{
						$this->setError($this->_db->getErrorMsg());
					}

					if ($cart['coupon'][$i]['transaction_coupon_id'])
					{
						$transaction_coupon_id = $cart['coupon'][$i]['transaction_coupon_id'];
					}

					$rowcoupon->transaction_coupon_id = $transaction_coupon_id;
					$rowcoupon->coupon_value          = $cart['coupon'][$i]['remaining_coupon_discount'];
					$rowcoupon->coupon_code           = $cart['coupon'][$i]['coupon_code'];
					$rowcoupon->userid                = $user->id;
					$rowcoupon->coupon_id             = $coupon_id;
					$rowcoupon->trancation_date       = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$rowcoupon->published             = 1;

					if (!$rowcoupon->store())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}

				}
			}

			$this->discount_type = implode('@', $coupontype);
		}

		return;
	}

	public function calculateShipping($shipping_rate_id)
	{
		$order_shipping_rate = 0;
		$shippingVatRate     = 0;
		$shipArr             = array();
		$order_shipping      = RedshopShippingRate::decrypt($shipping_rate_id);

		if (isset($order_shipping[3]))
		{
			$shipArr['order_shipping_rate'] = $order_shipping[3];

			if (array_key_exists(6, $order_shipping))
				$shipArr['shipping_vat'] = $order_shipping [6];
		}

		return $shipArr;
	}

	public function displayShoppingCart($template_desc = "", $users_info_id, $shipping_rate_id = 0, $payment_method_id, $Itemid, $customer_note = "", $req_number = "", $thirdparty_email = "", $customer_message = "", $referral_code = "", $shop_id = "", $post = array())
	{
		$session  = JFactory::getSession();
		$cart     = $session->get('cart');
		$user     = JFactory::getUser();
		$user_id  = $user->id;
		$usersess = $session->get('rs_user');
		$userArr  = $this->_producthelper->getVatUserinfo($user_id);
		$redHelper = redhelper::getInstance();

		$usersess['rs_user_info_id'] = $users_info_id;
		unset($cart['shipping']);
		$usersess = $session->set('rs_user', $usersess);
		$cart     = $this->_carthelper->modifyCart($cart, $user_id);

		if ($shipping_rate_id && $cart['free_shipping'] != 1)
		{
			$shipArr              = $this->calculateShipping($shipping_rate_id);
			$cart['shipping']     = $shipArr['order_shipping_rate'];
			$cart['shipping_vat'] = (!isset($shipArr['shipping_vat'])) ? 0 : $shipArr['shipping_vat'];
		}

		$cart = $this->_carthelper->modifyDiscount($cart);

		// Plugin support:  Process the shipping cart
		JPluginHelper::importPlugin('redshop_product');
        JPluginHelper::importPlugin('redshop_checkout');
		RedshopHelperUtility::getDispatcher()->trigger(
			'onDisplayShoppingCart', array(&$cart, &$template_desc, $users_info_id, $shipping_rate_id, $payment_method_id)
		);

		$paymentMethod = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentMethod = $paymentMethod[0];

		$paymentMethod->params               = new JRegistry($paymentMethod->params);
		$is_creditcard               = $paymentMethod->params->get('is_creditcard', '');
		$payment_oprand              = $paymentMethod->params->get('payment_oprand', '');
		$payment_discount_is_percent = $paymentMethod->params->get('payment_discount_is_percent', '');
		$payment_price               = $paymentMethod->params->get('payment_price', '');
		$accepted_credict_card       = $paymentMethod->params->get("accepted_credict_card");

		$paymentInfo                              = new stdclass;
		$paymentInfo->payment_price               = $payment_price;
		$paymentInfo->is_creditcard               = $is_creditcard;
		$paymentInfo->payment_oprand              = $payment_oprand;
		$paymentInfo->payment_discount_is_percent = $payment_discount_is_percent;
		$paymentInfo->accepted_credict_card       = $accepted_credict_card;

		if (Redshop::getConfig()->get('PAYMENT_CALCULATION_ON') == 'subtotal')
		{
			$paymentAmount = $cart ['product_subtotal'];
		}
		else
		{
			$paymentAmount = $cart ['total'];
		}

		$paymentArray   = $this->_carthelper->calculatePayment($paymentAmount, $paymentInfo, $cart ['total']);
		$cart['total']  = $paymentArray[0];
		$payment_amount = $paymentArray[1];

		$subtotal_excl_vat      = $cart ['product_subtotal_excl_vat'];
		$subtotal               = $cart ['product_subtotal'];
		$shipping               = $cart ['shipping'];
		$shippingVat            = $cart['shipping_tax'];
		$tax                    = $cart['tax'];

		if (isset($cart['discount']) === false)
		{
			$cart['discount'] = 0;
		}

		$discount_amount        = $cart['discount'];
		$cart['payment_oprand'] = $payment_oprand;
		$cart['payment_amount'] = $payment_amount;

		$template_desc = $this->_carthelper->replaceTemplate($cart, $template_desc, 1);

		$thirdparty_emailvalue = "";

		if ($thirdparty_email != "")
		{
			$thirdparty_emailvalue = $thirdparty_email;
		}
		elseif (isset($cart['thirdparty_email']))
		{
			$thirdparty_emailvalue = $cart['thirdparty_email'];
		}

		if (strstr($template_desc, "{thirdparty_email}"))
		{
			$thirdpartyemail = '<input type="text" name="thirdparty_email" id="thirdparty_email" value="' . $thirdparty_emailvalue . '"/>';
			$template_desc   = str_replace("{thirdparty_email}", $thirdpartyemail, $template_desc);
			$template_desc   = str_replace("{thirdparty_email_lbl}", JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL'), $template_desc);
		}

		$customernotevalue = "";

		if ($customer_note != "")
		{
			$customernotevalue = $customer_note;
		}
		elseif (isset($cart['customer_note']))
		{
			$customernotevalue = $cart['customer_note'];
		}

		$requisition_number = "";

		if ($req_number != "")
		{
			$requisition_number = $req_number;
		}
		elseif (isset($cart['requisition_number']))
		{
			$requisition_number = $cart['requisition_number'];
		}

		if (strstr($template_desc, "{customer_note}"))
		{
			$customernote  = '<textarea name="customer_note" id="customer_note">' . $customernotevalue . '</textarea>';
			$template_desc = str_replace("{customer_note}", $customernote, $template_desc);
			$template_desc = str_replace("{customer_note_lbl}", JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL'), $template_desc);
		}

		$template_desc        = str_replace("{customer_message_chk_lbl}", JText::_('COM_REDSHOP_CUSTOMER_MESSAGE_LBL'), $template_desc);
		$customer_message_chk = "<input type='checkbox' name='rs_customer_message_chk' id ='rs_customer_message_chk' onclick='javascript:displaytextarea(this);'/> ";
		$customer_message     = "<div id='rs_Divcustomer_messageTA' style='display:none;'><textarea name='rs_customer_message_ta' id ='rs_customer_message_ta' >" . $customer_message . "</textarea></div>";
		$template_desc        = str_replace("{customer_message_chk}", $customer_message_chk, $template_desc);
		$template_desc        = str_replace("{customer_message}", $customer_message, $template_desc);
		$template_desc        = str_replace("{referral_code_lbl}", JText::_('COM_REDSHOP_REFERRAL_CODE_LBL'), $template_desc);
		$referral_code        = "<input type='text' name='txt_referral_code' id='txt_referral_code' value='" . $referral_code . "'/>";
		$template_desc        = str_replace("{referral_code}", $referral_code, $template_desc);

		if (strstr($template_desc, "{requisition_number}"))
		{
			$req_number       = '';
			$req_number_lbl   = '';
			$billingaddresses = $this->billingaddresses();

			$req_number_lbl = JText::_('COM_REDSHOP_REQUISITION_NUMBER');
			$req_number     = '<input class="inputbox" name="requisition_number" id="requisition_number" value="' . $requisition_number . '" />';

			$template_desc = str_replace("{requisition_number}", $req_number, $template_desc);
			$template_desc = str_replace("{requisition_number_lbl}", $req_number_lbl, $template_desc);
		}

		if (strstr($template_desc, "{shop_more}"))
		{
			if (Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK') != '')
			{
				$shopmorelink = JRoute::_(Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK'));
			}
			elseif ($catItemId = RedshopHelperUtility::getCategoryItemid())
			{
				$shopmorelink = JRoute::_('index.php?option=com_redshop&view=category&Itemid=' . $catItemId);
			}
			else
			{
				$shopmorelink = JRoute::_('index.php');
			}

			$shop_more     = '<input type=button class="blackbutton btn" value="' . JText::_('COM_REDSHOP_SHOP_MORE') . '" onclick="javascript:document.location=\'' . $shopmorelink . '\'">';
			$template_desc = str_replace("{shop_more}", $shop_more, $template_desc);
		}

		if (strstr($template_desc, "{checkout_back_button}"))
		{
			$checkout_back = '<input type=button class="blackbutton btn" value="' . JText::_('COM_REDSHOP_BACK_BUTTON') . '" onclick="javascript: history.go(-1);">';
			$template_desc = str_replace("{checkout_back_button}", $checkout_back, $template_desc);
		}

		// CalculatePayment
		if (strstr($template_desc, '{if payment_discount}') && strstr($template_desc, '{payment_discount end if}'))
		{
			$template_desc = $this->_carthelper->replacePayment($template_desc, $payment_amount, 0, $payment_oprand);
		}

		$shippinPrice        = '';
		$shippinPriceWithVat = '';

		if (!empty($shipping_rate_id) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			$shippinPriceWithVat = $this->_producthelper->getProductFormattedPrice($cart ['shipping']);
			$shippinPrice        = $this->_producthelper->getProductFormattedPrice($cart ['shipping'] - $cart['shipping_vat']);
		}
		else
		{
			$template_desc = str_replace("{shipping_lbl}", '', $template_desc);
			$template_desc = str_replace("{tax_with_shipping_lbl}", '', $template_desc);
		}

		$template_desc = $this->_carthelper->replaceTermsConditions($template_desc, $Itemid);
		$template_desc = $this->_carthelper->replaceNewsletterSubscription($template_desc);

		$checkout = '<div id="checkoutfinal" style="float: right;">';
		$checkout .= '<input type="submit" id="checkout_final" name="checkout_final" class="greenbutton btn btn-primary" value="' . JText::_("COM_REDSHOP_BTN_CHECKOUTFINAL") . '" onclick="if(chkvalidaion() && validation()){checkout_disable(\'checkout_final\');}"/>';
		$checkout .= '<input type="hidden" name="task" value="checkoutfinal" />';
		$checkout .= '<input type="hidden" name="view" value="checkout" />';
		$checkout .= '<input type="hidden" name="option" value="com_redshop" />';
		$checkout .= '<input type="hidden" name="Itemid" id="onestepItemid" value="' . $Itemid . '" />';
		$checkout .= '<input type="hidden" name="users_info_id" value="' . $users_info_id . '" />';
		$checkout .= '<input type="hidden" name="order_id" value="' . JRequest::getVar('order_id') . '" />';

		if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
		{
			$checkout .= '<input type="hidden" name="shop_id" value="' . $shop_id . '" />';
			$checkout .= '<input type="hidden" name="shipping_rate_id" value="' . $shipping_rate_id . '" />';
			$checkout .= '<input type="hidden" name="payment_method_id" value="' . $payment_method_id . '" />';
		}

		$checkout .= '</div>';

		$template_desc = str_replace("{checkout}", $checkout, $template_desc);
		$template_desc = str_replace("{checkout_button}", $checkout, $template_desc);

		$qlink             = JRoute::_('index.php?option=com_redshop&view=quotation&tmpl=component&return=1&Itemid=' . $Itemid);
		$quotation_request = '<a href="' . $qlink . '" class="modal" rel="{handler: \'iframe\', size: {x: 570, y: 550}}"><input type=button class="greenbutton btn btn-primary" value= "' . JText::_('COM_REDSHOP_REQUEST_QUOTATION') . '" /></a>';
		$template_desc     = str_replace("{quotation_request}", $quotation_request, $template_desc);

		if (strstr($template_desc, "{coupon_code_lbl}"))
		{
			$coupon = '';

			if (isset($cart["coupon_code"]))
			{
				$coupon_price = $this->getCouponPrice();
				$coupon       = '<span>' . JText::_('COM_REDSHOP_CART_COUPON_CODE_TBL') . ' <br>' . $cart['coupon_code'] . ' <span class="discount">  ' . $coupon_price . '</span></span>';
			}

			$template_desc = str_replace("{coupon_code_lbl}", $coupon, $template_desc);
		}

		$template_desc = $this->_carthelper->replaceLabel($template_desc);
		$template_desc = str_replace("{print}", '', $template_desc);

		RedshopHelperCartSession::setCart($cart);

		return $template_desc;
	}

	/**
	 * Delete order number track
	 *
	 */
	public function deleteOrdernumberTrack()
	{
		$query = "TRUNCATE TABLE " . $this->_table_prefix . "ordernumber_track";

		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Count order number track
	 *
	 */
	public function getOrdernumberTrack()
	{
		$query = "SELECT trackdatetime FROM #__redshop_ordernumber_track";
		$this->_db->setQuery($query, 0, 1);

		return $this->_db->loadResult();
	}

	/**
	 * Insert order number track
	 *
	 */
	public function insertOrdernumberTrack()
	{
		$query_in = "INSERT INTO " . $this->_table_prefix . "ordernumber_track SET trackdatetime=now()";
		$this->_db->setQuery($query_in);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Get Unique order number
	 *
	 */
	public function getOrdernumber()
	{
		$order_functions = order_functions::getInstance();
		$trackid_time    = $this->getOrdernumberTrack();

		if ($trackid_time != "")
		{
			$to_time       = strtotime(date('Y-m-d H:i:s'));
			$from_time     = strtotime($trackid_time);
			$total_minutes = round(abs($to_time - $from_time) / 60, 2);

			if ($total_minutes > 1)
			{
				$this->deleteOrdernumberTrack();
				$trackid_time = "";
			}
		}

		if ($trackid_time == "")
		{
			$this->insertOrdernumberTrack();
			$order_number = $order_functions->generateOrderNumber();

			return $order_number;
		}
		else
		{
			return $this->getOrdernumber();
		}
	}
}
