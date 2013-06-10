<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.controller');

require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/extra_field.php';

/**
 * Checkout Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class CheckoutController extends JController
{
	public $_order_functions = null;

	public $_shippinghelper = null;

	/**
	 * Constructor.
	 *
	 * @param   array  $default  config array
	 */
	public function __construct($default = array())
	{
		$this->_order_functions = new order_functions;
		$this->_shippinghelper  = new shipping;
		JRequest::setVar('layout', 'default');
		parent::__construct($default);
	}

	/**
	 *  Method to store user detail when user do checkout.
	 *
	 * @return void
	 */
	public function checkoutprocess()
	{
		$post   = JRequest::get('post');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$model  = $this->getModel('checkout');

		if ($model->store($post))
		{
			$link = JRoute::_('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, false);
			$this->setRedirect($link, $msg);
		}
		else
		{
			$link = JRoute::_('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, false);
			$this->setRedirect($link);
		}
	}

	/**
	 *  Method for checkout second step.
	 *
	 * @return void
	 */
	public function checkoutnext()
	{
		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$post    = JRequest::get('post');
		$user    = JFactory::getUser();
		$cart    = $session->get('cart');

		if (isset($post['extrafields0']) && isset($post['extrafields']) && count($cart) > 0)
		{
			if (count($post['extrafields0']) > 0 && count($post['extrafields']) > 0)
			{
				for ($r = 0; $r < count($post['extrafields']); $r++)
				{
					$post['extrafields_values'][$post['extrafields'][$r]] = $post['extrafields0'][$r];
				}

				$cart['extrafields_values'] = $post['extrafields_values'];
				$session->set('cart', $cart);
			}
		}

		$Itemid        = JRequest::getInt('Itemid');
		$users_info_id = JRequest::getInt('users_info_id');
		$helper        = new redhelper;
		$chk           = $this->chkvalidation($users_info_id);

		if (!empty($chk))
		{
			if ($chk == 1)
			{
				$link = 'index.php?option=com_redshop&view=account_billto&return=checkout&setexit=0&Itemid=' . $Itemid;
			}
			else
			{
				$link = 'index.php?option=com_redshop&view=account_shipto&task=addshipping&setexit=0&return=checkout&infoid=' . $users_info_id . '&Itemid=' . $Itemid;
			}

			$app->Redirect($link);
		}

		if ($helper->isredCRM())
		{
			if (($session->get('isredcrmuser_debitor') || $session->get('isredcrmuser')) && ($post['payment_method_id'] == "rs_payment_banktransfer" || $post['payment_method_id'] == "rs_payment_banktransfer2" || $post['payment_method_id'] == "rs_payment_banktransfer3" || $post['payment_method_id'] == "rs_payment_banktransfer4" || $post['payment_method_id'] == "rs_payment_banktransfer5" || $post['payment_method_id'] == "rs_payment_cashtransfer" || $post['payment_method_id'] == "rs_payment_cashsale" || $post['payment_method_id'] == "rs_payment_banktransfer_discount"))
			{
				$crmDebitorHelper = new crmDebitorHelper;

				if ($session->get('isredcrmuser_debitor'))
				{
					$debitor_id = $session->get('isredcrmuser_debitor');
				}
				else
				{
					$debitor_id_tot = $crmDebitorHelper->getContactPersons(0, 0, 0, $user->id);
					$debitor_id = $debitor_id_tot[0]->section_id;
				}

				$details = $crmDebitorHelper->getDebitor($debitor_id);

				if (count($details) > 0 && $details[0]->is_company == 1)
				{
					$unpaid     = $details[0]->debitor_unpaid_balance;
					$max_credit = $details[0]->debitor_max_credit;
					$total      = $cart['total'];

					if ($max_credit <= ($unpaid + $total))
					{
						$option = JRequest::getVar('option');
						$Itemid = JRequest::getVar('Itemid');
						$msg    = JText::_('DEBITOR_CREDIT_LIMIT_EXCEED');
						$link   = JRoute::_('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, false);
						$this->setRedirect($link, $msg);
					}
				}
			}
		}

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$ccinfo = JRequest::getVar('ccinfo');

		$errormsg = "";

		if ($ccinfo == 1)
		{
			$errormsg = $this->setcreditcardInfo();

		}

		if ($errormsg != "")
		{
			$app->Redirect('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, $errormsg);
		}
		else
		{
			$view = $this->getView('checkout', 'next');
			parent::display();
		}
	}

	/**
	 * Update GLS Location
	 *
	 * @return void
	 */
	public function updateGLSLocation()
	{
		$get = JRequest::get('get');
		JPluginHelper::importPlugin('rs_labels_GLS');
		$dispatcher = JDispatcher::getInstance();
		$values = new stdClass;
		$values->zipcode = $get['zipcode'];

		$ShopResponses = $dispatcher->trigger('GetNearstParcelShops', array($values));
		$ShopRespons = $ShopResponses[0];

		$shopList = array();

		for ($i = 0; $i < count($ShopRespons); $i++)
		{
			$shopList[] = JHTML::_('select.option', $ShopRespons[$i]->shop_id, $ShopRespons[$i]->CompanyName . ", " . $ShopRespons[$i]->Streetname . ", " . $ShopRespons[$i]->ZipCode . ", " . $ShopRespons[$i]->CityName);
		}

		echo $lists['shopList'] = JHTML::_('select.genericlist', $shopList, 'shop_id', 'class="inputbox" ', 'value', 'text', $ShopRespons[0]->shop_id);
		exit;
	}

	/**
	 * Check validation
	 *
	 * @param   string  $users_info_id  not used
	 *
	 * @return bool
	 */
	public function chkvalidation($users_info_id)
	{
		$model             = $this->getModel('checkout');
		$billingaddresses  = $model->billingaddresses();
		$shippingaddresses = $model->shipaddress($users_info_id);
		$extra_field       = new extra_field;
		$extrafield_name   = '';
		$return            = 0;

		if (!$billingaddresses->is_company)
		{
			if ($billingaddresses->firstname == '')
			{
				$return = 1;
				$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME');
				JError::raiseWarning('', $msg);

				return $return;
			}
			elseif ($billingaddresses->lastname == '')
			{
				$return = 1;
				$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME');
				JError::raiseWarning('', $msg);

				return $return;
			}
		}
		else
		{
			if ($billingaddresses->company_name == '')
			{
				$return = 1;
				$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME');
				JError::raiseWarning('', $msg);

				return $return;
			}

			if ($billingaddresses->firstname == '')
			{
				$return = 1;
				$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME');
				JError::raiseWarning('', $msg);

				return $return;
			}
			elseif ($billingaddresses->lastname == '')
			{
				$return = 1;
				$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME');
				JError::raiseWarning('', $msg);

				return $return;
			}
			elseif (ECONOMIC_INTEGRATION == 1 && trim($billingaddresses->ean_number) != '')
			{
				$economic     = new economic;
				$debtorHandle = $economic->createUserInEconomic($billingaddresses);

				if (JError::isError(JError::getError()))
				{
					$return = 1;
					$error  = JError::getError();
					$msg    = $error->message;
					JError::raiseWarning('', $msg);

					return $return;
				}
			}
		}

		if (!trim($billingaddresses->address))
		{
			$return = 1;
			$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS');
			JError::raiseWarning('', $msg);

			return $return;
		}
		elseif (!$billingaddresses->country_code)
		{
			$return = 1;
			$msg    = JText::_('COM_REDSHOP_PLEASE_SELECT_COUNTRY');
			JError::raiseWarning('', $msg);

			return $return;
		}
		elseif (!$billingaddresses->zipcode)
		{
			$return = 1;
			$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE');
			JError::raiseWarning('', $msg);

			return $return;
		}
		elseif (!$billingaddresses->phone)
		{
			$return = 1;
			$msg    = JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE');
			JError::raiseWarning('', $msg);

			return $return;
		}

		if ($billingaddresses->is_company == 1)
		{
			$extrafield_name = $extra_field->chk_extrafieldValidation(8, $billingaddresses->users_info_id);

			if (!empty($extrafield_name))
			{
				$return = 1;
				$msg    = $extrafield_name . JText::_('COM_REDSHOP_IS_REQUIRED');
				JError::raiseWarning('', $msg);

				return $return;
			}
		}
		else
		{
			$extrafield_name = $extra_field->chk_extrafieldValidation(7, $billingaddresses->users_info_id);

			if (!empty($extrafield_name))
			{
				$return = 1;
				$msg    = $extrafield_name . JText::_('COM_REDSHOP_IS_REQUIRED');
				JError::raiseWarning('', $msg);

				return $return;
			}
		}

		if (SHIPPING_METHOD_ENABLE && $users_info_id != $billingaddresses->users_info_id)
		{
			if ($billingaddresses->is_company == 1)
			{
				$extrafield_name = $extra_field->chk_extrafieldValidation(15, $users_info_id);

				if (!empty($extrafield_name))
				{
					$return = 2;
					$msg    = $extrafield_name . JText::_('COM_REDSHOP_IS_REQUIRED');
					JError::raiseWarning('', $msg);

					return $return;
				}
			}
			else
			{
				$extrafield_name = $extra_field->chk_extrafieldValidation(14, $users_info_id);

				if (!empty($extrafield_name))
				{
					$return = 2;
					$msg    = $extrafield_name . JText::_('COM_REDSHOP_IS_REQUIRED');
					JError::raiseWarning('', $msg);

					return $return;
				}
			}
		}

		return $return;
	}

	/**
	 * Checkout final step function
	 *
	 * @return void
	 */
	public function checkoutfinal()
	{
		$app        = JFactory::getApplication();
		$dispatcher = JDispatcher::getInstance();
		$post       = JRequest::get('post');
		$option     = JRequest::getVar('option');
		$Itemid     = JRequest::getVar('Itemid');
		$model      = $this->getModel('checkout');
		$session    = JFactory::getSession();
		$cart       = $session->get('cart');
		$payment_method_id = JRequest::getCmd('payment_method_id', '');

		if (isset($post['extrafields0']) && isset($post['extrafields']) && count($cart) > 0)
		{
			if (count($post['extrafields0']) > 0 && count($post['extrafields']) > 0)
			{
				for ($r = 0; $r < count($post['extrafields']); $r++)
				{
					$post['extrafields_values'][$post['extrafields'][$r]] = $post['extrafields0'][$r];
				}

				$cart['extrafields_values'] = $post['extrafields_values'];
				$session->set('cart', $cart);
			}
		}

		if (SHIPPING_METHOD_ENABLE)
		{
			$shipping_rate_id = JRequest::getVar('shipping_rate_id');
			$shippingdetail   = explode("|", $this->_shippinghelper->decryptShipping(str_replace(" ", "+", $shipping_rate_id)));

			if (count($shippingdetail) < 4)
			{
				$shipping_rate_id = "";
			}

			if ($shipping_rate_id == '' && $cart['free_shipping'] != 1)
			{
				$msg = JText::_('COM_REDSHOP_SELECT_SHIP_METHOD');
				$app->Redirect('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, $msg);
			}
		}

		if ($payment_method_id != '')
		{
			if (isset($cart['idx']))
			{
				if ($cart['idx'] > 0)
				{
					$session->set('order_id', 0);
				}
				else
				{
					$app->Redirect('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid);
					exit;
				}
			}

			if (ONESTEP_CHECKOUT_ENABLE)
			{
				$users_info_id = JRequest::getInt('users_info_id');
				$chk           = $this->chkvalidation($users_info_id);

				if (!empty($chk))
				{
					if ($chk == 1)
					{
						$link = 'index.php?option=com_redshop&view=account_billto&return=checkout&setexit=0&Itemid=' . $Itemid;
					}
					else
					{
						$link = 'index.php?option=com_redshop&view=account_shipto&task=addshipping&setexit=0&return=checkout&infoid=' . $users_info_id . '&Itemid=' . $Itemid;
					}

					$app->Redirect($link);

					return;
				}

				$errormsg = $this->setcreditcardInfo();

				if ($errormsg != "")
				{
					$app->Redirect('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, $errormsg);

					return;
				}
			}

			$order_id = $session->get('order_id');

			// Import files for plugin
			JPluginHelper::importPlugin('redshop_product');

			if ($order_id == 0)
			{
				// Add plugin support
				$results     = $dispatcher->trigger('beforeOrderPlace', array($cart));

				$orderresult = $model->orderplace();
				$order_id    = $orderresult->order_id;
			}
			else
			{
				JRequest::setVar('order_id', $order_id);
			}

			if ($order_id)
			{
				JPluginHelper::importPlugin('redshop_product');
				$data = $dispatcher->trigger('getStockroomStatus', array($order_id));

				$model->resetcart();

				// Add Plugin support
				$results = $dispatcher->trigger('afterOrderPlace', array($cart, $orderresult));

				// New checkout flow
				/**
				 * change redirection
				 * The page will redirect to stand alon page where, payment extra infor code will execute.
				 * Note: ( Only when redirect payment gateway are in motion, not for credit card gateway)
				 *
				 */
				$paymentmethod = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
				$paymentmethod = $paymentmethod[0];
				$params        = new JRegistry($paymentmethod->params, $xmlpath);
				$is_creditcard = $params->get('is_creditcard', 0);
				$is_redirected = $params->get('is_redirected', 0);

				if ($is_creditcard && !$is_redirected)
				{
					$link = JRoute::_('index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $order_id . '&Itemid=' . $Itemid);
					$msg  = JText::_('COM_REDSHOP_ORDER_PLACED');
					$this->setRedirect($link, $msg);
				}
				else
				{
					$link = JURI::root() . 'index.php?option=com_redshop&view=order_detail&layout=checkout_final&oid=' . $order_id . '&Itemid=' . $Itemid;
					$this->setRedirect($link);
				}
			}
			else
			{
				$errorMsg = $model->getError();
				JError::raiseWarning(21, $errorMsg);
				$app->Redirect('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid);
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD');
			$app->Redirect('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid, $msg);
		}
	}

	/**
	 * Set credit Card info
	 *
	 * @return string
	 */
	public function setcreditcardInfo()
	{
		$model             = $this->getModel('checkout');
		$session           = JFactory::getSession();
		$payment_method_id = JRequest::getCmd('payment_method_id', '');
		$errormsg          = "";
		$paymentmethod     = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentparams     = new JRegistry($paymentmethod[0]->params);
		$is_creditcard     = $paymentparams->get('is_creditcard', 0);

		if ($is_creditcard)
		{
			$ccdata['order_payment_name']         = JRequest::getVar('order_payment_name');
			$ccdata['creditcard_code']            = JRequest::getVar('creditcard_code');
			$ccdata['order_payment_number']       = JRequest::getVar('order_payment_number');
			$ccdata['order_payment_expire_month'] = JRequest::getVar('order_payment_expire_month');
			$ccdata['order_payment_expire_year']  = JRequest::getVar('order_payment_expire_year');
			$ccdata['credit_card_code']           = JRequest::getVar('credit_card_code');
			$session->set('ccdata', $ccdata);

			$validpayment = $model->validatepaymentccinfo();

			if (!$validpayment[0])
			{
				$errormsg = $validpayment[1];
			}
		}

		return $errormsg;
	}

	/**
	 * One Step checkout process
	 *
	 * @return void
	 */
	public function oneStepCheckoutProcess()
	{
		$producthelper   = new producthelper;
		$redTemplate     = new Redtemplate;
		$carthelper      = new rsCarthelper;
		$order_functions = new order_functions;

		$model   = $this->getModel('checkout');
		$post    = JRequest::get('post');
		$user    = JFactory::getUser();
		$session = JFactory::getSession();

		$cart = $session->get('cart');
		$users_info_id    = $post['users_info_id'];
		$shipping_box_id  = $post['shipping_box_id'];
		$shipping_rate_id = $post['shipping_rate_id'];
		$customer_note    = $post['customer_note'];
		$req_number       = $post['requisition_number'];
		$customer_message = $post['rs_customer_message_ta'];
		$referral_code    = $post['txt_referral_code'];

		$payment_method_id = $post['payment_method_id'];
		$order_total       = $cart['total'];
		$total_discount    = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
		$order_subtotal    = (SHIPPING_AFTER == 'total') ? $cart['product_subtotal'] - $total_discount : $cart['product_subtotal_excl_vat'];
		$Itemid            = $post['Itemid'];
		$objectname        = $post['objectname'];
		$rate_template_id  = $post['rate_template_id'];
		$cart_template_id  = $post['cart_template_id'];

		$onestep_template_desc = "";
		$rate_template_desc    = "";

		if ($objectname == "users_info_id" || $objectname == "shipping_box_id")
		{
			if ($users_info_id > 0)
			{
				$shipping_template = $redTemplate->getTemplate("redshop_shipping", $rate_template_id);

				if (count($shipping_template) > 0)
				{
					$rate_template_desc = $shipping_template[0]->template_desc;
				}

				$returnarr          = $carthelper->replaceShippingTemplate($rate_template_desc, $shipping_rate_id, $shipping_box_id, $user->id, $users_info_id, $order_total, $order_subtotal);
				$rate_template_desc = $returnarr['template_desc'];
				$shipping_rate_id   = $returnarr['shipping_rate_id'];
			}
			else
			{
				$rate_template_desc = JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS');
			}
		}

		if ($shipping_rate_id != "")
		{
			$shipArr = $model->calculateShipping($shipping_rate_id);
			$cart['shipping']     = $shipArr['order_shipping_rate'];
			$cart['shipping_vat'] = $shipArr['shipping_vat'];
			$cart = $carthelper->modifyDiscount($cart);
		}

		if ($cart_template_id != 0)
		{
			$templatelist = $redTemplate->getTemplate("checkout", $cart_template_id);
			$onestep_template_desc = $templatelist[0]->template_desc;

			$onestep_template_desc = $model->displayShoppingCart($onestep_template_desc, $users_info_id, $shipping_rate_id, $payment_method_id, $Itemid, $customer_note, $req_number, '', $customer_message, $referral_code);
		}

		$display_shippingrate = '<div id="onestepshiprate">' . $rate_template_desc . '</div>';
		$display_cart = '<div id="onestepdisplaycart">' . $onestep_template_desc . '</div>';

		$description = $display_shippingrate . $display_cart;
		$lang = JFactory::getLanguage();
		$Locale = $lang->getLocale();

		if (in_array('ru', $Locale))
		{
			// Commented because redshop currency symbole has been changed because of ajax response
			$description = html_entity_decode($description, ENT_QUOTES, 'KOI8-R');
		}

		$cart_total = $producthelper->getProductFormattedPrice($cart['mod_cart_total']);
		echo "`_`" . $description . "`_`" . $cart_total;
		die();
	}

	/**
	 * Display Credit Card
	 *
	 * @return void
	 */
	public function displaycreditcard()
	{
		$carthelper = new rsCarthelper;
		$get = JRequest::get('get');
		$creditcard = "";

		$payment_method_id = $get['payment_method_id'];

		if ($payment_method_id != "")
		{
			$creditcard = $carthelper->replaceCreditCardInformation($payment_method_id);
		}

		$creditcard = '<div id="creditcardinfo">' . $creditcard . '</div>';
		echo $creditcard;
		die();
	}

	/**
	 * Captcha
	 *
	 * @return void
	 */
	public function captcha()
	{
		require_once JPATH_COMPONENT_SITE . '/helpers/captcha.php';

		// Isset($_GET['width']) ? $_GET['width'] : '120';
		$width = JRequest::getInt('width', 120);

		// Isset($_GET['height']) ? $_GET['height'] : '40';
		$height = JRequest::getInt('height', 40);

		// Isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';
		$characters = JRequest::getInt('characters', 6);

		// Isset($_GET['captcha']) ? $_GET['captcha'] : 'security_code';
		$captchaname = JRequest::getCmd('captcha', 'security_code');

		$captcha = new CaptchaSecurityImages($width, $height, $characters, $captchaname);
	}

	/**
	 * Display payment extra field
	 *
	 * @return void
	 */
	public function displaypaymentextrafield()
	{
		ob_clean();
		$payment_method_id  = JRequest::getCmd('payment_method_id', '');
		$paymentmethod      = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentparams      = new JRegistry($paymentmethod[0]->params);
		$extrafield_payment = $paymentparams->get('extrafield_payment', '');

		$extraField       = new extraField;
		$extrafield_total = "";

		if (count($extrafield_payment) > 0)
		{
			for ($ui = 0; $ui < count($extrafield_payment); $ui++)
			{
				if ($extrafield_payment[$ui] != "")
				{
					$product_userfileds = $extraField->list_all_user_fields($extrafield_payment[$ui], 18, '', 0, 0, 0);
					$extrafield_total .= $product_userfileds[0] . " " . $product_userfileds[1] . "<br>";
					$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_payment[$ui] . "'>";
				}
			}

			echo $extrafield_total . $extrafield_hidden;
			die();
		}
	}

	/**
	 * Display shipping extra field
	 *
	 * @return void
	 */
	public function displayshippingextrafield()
	{
		ob_clean();
		$shipping_rate_id    = JRequest::getCmd('shipping_rate_id', '');
		$shippingmethod      = $this->_order_functions->getShippingMethodInfo($shipping_rate_id);
		$shippingparams      = new JRegistry($shippingmethod[0]->params);
		$extrafield_shipping = $shippingparams->get('extrafield_shipping', '');

		$extraField = new extraField;
		$extrafield_total = "";

		if (count($extrafield_shipping) > 0)
		{
			for ($ui = 0; $ui < count($extrafield_shipping); $ui++)
			{
				if ($extrafield_shipping[$ui] != "")
				{
					$product_userfileds = $extraField->list_all_user_fields($extrafield_shipping[$ui], 19, '', 0, 0, 0);
					$extrafield_total .= $product_userfileds[0] . " " . $product_userfileds[1] . "<br>";
					$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_shipping[$ui] . "'>";
				}
			}

			echo $extrafield_total . $extrafield_hidden;
			die();
		}
	}
}
