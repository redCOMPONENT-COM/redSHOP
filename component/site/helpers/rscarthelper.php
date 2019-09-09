<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

class rsCarthelper
{
	public $_table_prefix = null;

	public $_db = null;

	public $_session = null;

	public $_order_functions = null;

	public $_extra_field = null;

	public $_redhelper = null;

	public $_producthelper = null;

	public $_shippinghelper = null;

	public $_globalvoucher = 0;

	protected static $instance = null;

	protected $input;

	/**
	 * Returns the rsCarthelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  rsCarthelper  The rsCarthelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->_table_prefix    = '#__redshop_';
		$this->_db              = JFactory::getDBO();
		$this->_session         = JFactory::getSession();
		$this->_order_functions = order_functions::getInstance();
		$this->_extra_field     = extra_field::getInstance();
		$this->_extraFieldFront = extraField::getInstance();
		$this->_redhelper       = redhelper::getInstance();
		$this->_producthelper   = productHelper::getInstance();
		$this->_shippinghelper  = shipping::getInstance();
		$this->input            = JFactory::getApplication()->input;
	}

	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param string $data
	 * @param int    $amount
	 * @param int    $discount
	 * @param int    $check
	 * @param int    $quotationMode
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7  Use RedshopHelperCartTag::replaceTax() instead.
	 */
	public function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
	{
		return RedshopHelperCartTag::replaceTax($data, $amount, $discount, $check, $quotationMode);
	}

	/**
	 * Calculate tax after Discount is apply
	 *
	 * @param   float  $tax       Tax amount
	 * @param   float  $discount  Discount amount.
	 *
	 * @return  float             Tax after apply discount.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::calculateTaxAfterDiscount() instead.
	 **/
	public function calculateTaxafterDiscount($tax = 0.0, $discount = 0.0)
	{
		return RedshopHelperCart::calculateTaxAfterDiscount($tax, $discount);
	}

	/**
	 * replace Conditional tag from Redshop Discount
	 *
	 * @param   string  $template       Template
	 * @param   int     $discount       Discount
	 * @param   int     $subTotal       Subtotal
	 * @param   int     $quotationMode  Quotation mode
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7  Use RedshopHelperCartTag::replaceDiscount() instead.
	 */
	public function replaceDiscount($template = '', $discount = 0, $subTotal = 0, $quotationMode = 0)
	{
		return RedshopHelperCartTag::replaceDiscount($template, $discount, $subTotal, $quotationMode);
	}

	/**
	 * replace Conditional tag from Redshop payment Discount/charges
	 *
	 * @param string $data
	 * @param int    $amount
	 * @param int    $cart
	 * @param string $payment_oprand
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.7
	 */
	public function replacePayment($data = '', $amount = 0, $cart = 0, $payment_oprand = '-')
	{
		return RedshopHelperPayment::replaceConditionTag($data, $amount, $cart, $payment_oprand);
	}

	/**
	 * Calculate payment Discount/charges
	 *
	 * @param   float   $total        Total
	 * @param   object  $paymentinfo  Payment information
	 * @param   float   $finalAmount  Final amount
	 *
	 * @return  array
	 *
	 * @deprecated  2.1.0
	 *
	 * @see RedshopHelperPayment::calculate()
	 */
	public function calculatePayment($total, $paymentinfo, $finalAmount)
	{
		return RedshopHelperPayment::calculate($total, $paymentinfo, $finalAmount);
	}

	/**
	 * Method for replace Billing Address
	 *
	 * @param   string   $content         Template content
	 * @param   object   $billingAddress  Billing data
	 * @param   boolean  $sendMail        Is in send mail?
	 *
	 * @return  mixed
	 * @deprecated    2.0.7
	 *
	 * @see RedshopHelperBillingTag::replaceBillingAddress()
	 */
	public function replaceBillingAddress($content, $billingAddress, $sendMail = false)
	{
		return RedshopHelperBillingTag::replaceBillingAddress($content, $billingAddress, $sendMail);
	}

	/**
	 * Replace Shipping Address
	 *
	 * @param   string   $data             Template content
	 * @param   object   $shippingAddress  Shipping address
	 * @param   boolean  $sendMail         Is in send mail
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Shipping\Tag::replaceShippingAddress
	 */
	public function replaceShippingAddress($data, $shippingAddress, $sendMail = false)
	{
		return Redshop\Shipping\Tag::replaceShippingAddress($data, $shippingAddress, $sendMail);
	}

	/**
	 * Replace shipping method
	 *
	 * @param   stdClass  $shipping  Shipping data
	 * @param   string    $content   Template content
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7
	 *
	 * @see Redshop\Shipping\Tag::replaceShippingMethod()
	 */
	public function replaceShippingMethod($shipping, $content = '')
	{
		return Redshop\Shipping\Tag::replaceShippingMethod($shipping, $content);
	}

	/**
	 * Method for replace cart item
	 *
	 * @param   string  $data          Template Html
	 * @param   array   $cart          Cart data
	 * @param   boolean $replaceButton Is replace button?
	 * @param   integer $quotationMode Is in Quotation Mode
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @deprecated   2.1.0
	 * @see RedshopHelperCartTag::replaceCartItem
	 */
	public function replaceCartItem($data, $cart = array(), $replaceButton, $quotationMode = 0)
	{
		return RedshopHelperCartTag::replaceCartItem($data, $cart, $replaceButton, $quotationMode);
	}

	/**
	 * replace Order Items
	 *
	 * @param   string   $data      template
	 * @param   array    $rowitem   Order item list
	 * @param   boolean  $sendMail  is send mail
	 *
	 * @return  array
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0 Use Redshop\Order\Item::replaceItems
	 * @see Redshop\Order\Item::replaceItems
	 */
	public function repalceOrderItems($data, $rowitem = array(), $sendMail = false)
	{
		return Redshop\Order\Item::replaceItems($data, $rowitem, $sendMail);
	}

	/**
	 * Method for replace label in template
	 *
	 * @param   string  $data  Template content
	 *
	 * @return  string
	 *
	 * @deprecated 2.1.0 Redshop\Cart\Render\Label::replace
	 * @see Redshop\Cart\Render\Label::replace
	 */
	public function replaceLabel($data)
	{
		return Redshop\Cart\Render\Label::replace($data);
	}

	/**
	* APPLY_VAT_ON_DISCOUNT = When the discount is a "fixed amount" the
	* final price may vary, depending on if the discount affects "the price+VAT"
	* or just "the price". This CONSTANT will define if the discounts needs to
	* be applied BEFORE or AFTER the VAT is applied to the product price.
	*
	* @param   array     $cart      Cart data
	* @param   integer   $shipping  Cart data
	* @param   integer   $userId    Current user ID
	*
	* @return  array
	* @throws  \Exception
	*
	* @deprecated    2.1.0
	 * @see \Redshop\Cart\Helper::calculation()
	 */
	public function calculation($cart, $shipping = 0, $userId = 0)
	{
		return \Redshop\Cart\Helper::calculation($cart, $userId);
	}

	/**
	 * Get cart module calculate
	 *
	 * @param   array  $redArray  Cart data
	 *
	 * @return  float
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see \Redshop\Cart\Module::calculate()
	 */
	public function GetCartModuleCalc($redArray)
	{
		return Redshop\Cart\Module::calculate($redArray);
	}

	public function replaceTemplate($cart, $cart_data, $checkout = 1)
	{
		JPluginHelper::importPlugin('redshop_checkout');
		JPluginHelper::importPlugin('redshop_shipping');
		$dispatcher   = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onBeforeReplaceTemplateCart', array(&$cart, &$cart_data, $checkout));
		
		if (strpos($cart_data, "{product_loop_start}") !== false && strpos($cart_data, "{product_loop_end}") !== false)
		{
			$template_sdata  = explode('{product_loop_start}', $cart_data);
			$template_start  = $template_sdata[0];
			$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
			$template_end    = $template_edata[1];
			$template_middle = $template_edata[0];
			$template_middle = RedshopHelperCartTag::replaceCartItem($template_middle, $cart, 1, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));
			$cart_data       = $template_start . $template_middle . $template_end;
		}

		$cart_data = Redshop\Cart\Render\Label::replace($cart_data);
		$total                     = $cart ['total'];
		$subtotal_excl_vat         = $cart ['subtotal_excl_vat'];
		$product_subtotal          = $cart ['product_subtotal'];
		$product_subtotal_excl_vat = $cart ['product_subtotal_excl_vat'];
		$subtotal                  = $cart ['subtotal'];
		$discount_ex_vat           = $cart['discount_ex_vat'];
		$discount_total            = $cart['voucher_discount'] + $cart['coupon_discount'];
		$discount_amount           = $cart ["cart_discount"];
		$tax                       = $cart ['tax'];
		$sub_total_vat             = $cart ['sub_total_vat'];
		$shipping                  = $cart ['shipping'];
		$shippingVat               = $cart ['shipping_tax'];

		if ($total <= 0)
		{
			$total = 0;
		}

		if (isset($cart ['discount_type']) === false)
		{
			$cart ['discount_type'] = 0;
		}

		$tmp_discount              = $discount_total;
		$discount_total            = RedshopHelperProductPrice::formattedPrice($discount_total + $discount_amount, true);

		if (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			if (strpos($cart_data, '{product_subtotal_lbl}') !== false)
			{
				$cart_data = str_replace("{product_subtotal_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{product_subtotal_excl_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{product_subtotal_excl_vat_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{shipping_with_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{shipping_with_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{shipping_excl_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{shipping_excl_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{product_price_excl_lbl}') !== false)
			{
				$cart_data = str_replace("{product_price_excl_lbl}", JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL'), $cart_data);
			}

			$cart_data = str_replace("{total}", "<span id='spnTotal'>" . RedshopHelperProductPrice::formattedPrice($total, true) . "</span>", $cart_data);
			$cart_data = str_replace("{total_excl_vat}", "<span id='spnTotal'>" . RedshopHelperProductPrice::formattedPrice($subtotal_excl_vat) . "</span>", $cart_data);

			$chktag = \Redshop\Template\Helper::isApplyVat($cart_data);

			if (!empty($chktag))
			{
				$cart_data = str_replace("{subtotal}", RedshopHelperProductPrice::formattedPrice($subtotal), $cart_data);
				$cart_data = str_replace("{product_subtotal}", RedshopHelperProductPrice::formattedPrice($product_subtotal), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{subtotal}", RedshopHelperProductPrice::formattedPrice($subtotal_excl_vat), $cart_data);
				$cart_data = str_replace("{product_subtotal}", RedshopHelperProductPrice::formattedPrice($product_subtotal_excl_vat), $cart_data);
			}

			if ((strpos($cart_data, "{discount_denotation}") !== false || strpos($cart_data, "{shipping_denotation}") !== false) && ($discount_total != 0 || $shipping != 0))
			{
				$cart_data = str_replace("{denotation_label}", JText::_('COM_REDSHOP_DENOTATION_TXT'), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{denotation_label}", "", $cart_data);
			}

			if (strpos($cart_data, "{discount_excl_vat}") !== false)
			{
				$cart_data = str_replace("{discount_denotation}", "*", $cart_data);
			}
			else
			{
				$cart_data = str_replace("{discount_denotation}", "", $cart_data);
			}

			$cart_data = str_replace("{subtotal_excl_vat}", RedshopHelperProductPrice::formattedPrice($subtotal_excl_vat), $cart_data);
			$cart_data = str_replace("{product_subtotal_excl_vat}", RedshopHelperProductPrice::formattedPrice($product_subtotal_excl_vat), $cart_data);
			$cart_data = str_replace("{sub_total_vat}", RedshopHelperProductPrice::formattedPrice($sub_total_vat), $cart_data);
			$cart_data = str_replace("{discount_excl_vat}", RedshopHelperProductPrice::formattedPrice($discount_ex_vat), $cart_data);

			$rep = true;

			if (!$checkout)
			{
				if (!Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART') || !Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}
			else
			{
				if (!Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}

			if (!empty($rep))
			{
				if (strpos($cart_data, "{shipping_excl_vat}") !== false)
				{
					$cart_data = str_replace("{shipping_denotation}", "*", $cart_data);
				}
				else
				{
					$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
				}

				$cart_data = str_replace("{order_shipping}", RedshopHelperProductPrice::formattedPrice($shipping, true), $cart_data);
				$cart_data = str_replace("{shipping_excl_vat}", "<span id='spnShippingrate'>" . RedshopHelperProductPrice::formattedPrice($shipping - $cart['shipping_tax'], true) . "</span>", $cart_data);
				$cart_data = str_replace("{shipping_lbl}", JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'), $cart_data);
				$cart_data = str_replace("{shipping}", "<span id='spnShippingrate'>" . RedshopHelperProductPrice::formattedPrice($shipping, true) . "</span>", $cart_data);
				$cart_data = str_replace("{tax_with_shipping_lbl}", JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'), $cart_data);
				$cart_data = str_replace("{vat_shipping}", RedshopHelperProductPrice::formattedPrice($shippingVat), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{order_shipping}", '', $cart_data);
				$cart_data = str_replace("{shipping_excl_vat}", '', $cart_data);
				$cart_data = str_replace("{shipping_lbl}", '', $cart_data);
				$cart_data = str_replace("{shipping}", '', $cart_data);
				$cart_data = str_replace("{tax_with_shipping_lbl}", '', $cart_data);
				$cart_data = str_replace("{vat_shipping}", '', $cart_data);
				$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
			}
		}
		else
		{
			$cart_data = str_replace("{total}", "<span id='spnTotal'></span>", $cart_data);
			$cart_data = str_replace("{shipping_excl_vat}", "<span id='spnShippingrate'></span>", $cart_data);
			$cart_data = str_replace("{order_shipping}", "", $cart_data);
			$cart_data = str_replace("{shipping_lbl}", '', $cart_data);
			$cart_data = str_replace("{shipping}", "<span id='spnShippingrate'></span>", $cart_data);
			$cart_data = str_replace("{subtotal}", "", $cart_data);
			$cart_data = str_replace("{tax_with_shipping_lbl}", '', $cart_data);
			$cart_data = str_replace("{vat_shipping}", '', $cart_data);
			$cart_data = str_replace("{subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{shipping_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{product_subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{product_subtotal}", "", $cart_data);
			$cart_data = str_replace("{sub_total_vat}", "", $cart_data);
			$cart_data = str_replace("{discount_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{discount_denotation}", "", $cart_data);
			$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
			$cart_data = str_replace("{denotation_label}", "", $cart_data);
			$cart_data = str_replace("{total_excl_vat}", "", $cart_data);
		}

		if (!Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$total_for_discount = $subtotal_excl_vat;
		}
		else
		{
			$total_for_discount = $subtotal;
		}

		$cart_data = RedshopHelperCartTag::replaceDiscount($cart_data, $discount_amount + $tmp_discount, $total_for_discount, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));

		if ($checkout)
		{
			$cart_data = RedshopHelperPayment::replaceConditionTag($cart_data, $cart['payment_amount'], 0, $cart['payment_oprand']);
		}
		else
		{
			$paymentOprand = (isset($cart['payment_oprand'])) ? $cart['payment_oprand'] : '-';
			$cart_data     = RedshopHelperPayment::replaceConditionTag($cart_data, 0, 1, $paymentOprand);
		}

		$cart_data = RedshopHelperCartTag::replaceTax(
			$cart_data,
			$tax + $shippingVat,
			$discount_amount + $tmp_discount,
			0,
			Redshop::getConfig()->getBool('DEFAULT_QUOTATION_MODE')
		);
		
		$dispatcher->trigger('onAfterReplaceTemplateCart', array(&$cart_data, $checkout));

		return $cart_data;
	}

	/**
	 * Method for replace template order
	 *
	 * @param   object   $row              Order data.
	 * @param   string   $ReceiptTemplate  Template content.
	 * @param   boolean  $sendMail         In send mail
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see  Redshop\Order\Template::replaceTemplate
	 */
	public function replaceOrderTemplate($row, $ReceiptTemplate, $sendMail = false)
	{
		return Redshop\Order\Template::replaceTemplate($row, $ReceiptTemplate, $sendMail);
	}

	/**
	 * Method for render cart.
	 *
	 * @param   array  $cart  Cart data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::generateCartOutput() instead.
	 */
	public function makeCart_output($cart)
	{
		return RedshopHelperCart::generateCartOutput($cart);
	}

	/**
	 * Method for get parameters of module cart
	 *
	 * @return  Registry
	 *
	 * @since   2.1.0
	 * @see \Redshop\Cart\Module::getParams()
	 */
	public function GetCartParameters()
	{
		return \Redshop\Cart\Module::getParams();
	}

	/**
	 * Method for modify cart data.
	 *
	 * @param   array   $cartArr   Cart data.
	 * @param   integer $user_id   User ID
	 *
	 * @return  array
	 *
	 * @deprecated 2.1.0
	 * @see \Redshop\Cart\Cart::modify
	 */
	public function modifyCart($cartArr, $user_id)
	{
		return \Redshop\Cart\Cart::modify($cartArr, $user_id);
	}

	public function replaceShippingBoxTemplate($box_template_desc = "", $shipping_box_post_id = 0)
	{
		// Get shipping boxes HTML
		$shippingBoxes = RedshopHelperShipping::getShippingBox();

		$box_template_desc = str_replace("{shipping_box_heading}", JText::_('COM_REDSHOP_SHIPPING_BOXES'), $box_template_desc);

		if (count($shippingBoxes) == 1 || (count($shippingBoxes) > 0 && $shipping_box_post_id == 0))
		{
			$shipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
		}

		$shipping_box_list = JText::_('COM_REDSHOP_NO_SHIPPING_BOX');

		if (count($shippingBoxes) > 0)
		{
			$shipping_box_list = "";

			for ($i = 0, $in = count($shippingBoxes); $i < $in; $i++)
			{
				$shipping_box_id = $shippingBoxes[$i]->shipping_box_id;

				// Previous priority
				if ($i > 0)
				{
					$shipping_box_priority_pre = $shippingBoxes[$i - 1]->shipping_box_priority;
				}

				// Current priority
				$shipping_box_priority = $shippingBoxes[$i]->shipping_box_priority;
				$checked               = ($shipping_box_post_id == $shipping_box_id) ? "checked='checked'" : "";

				if ($i == 0 || ($shipping_box_priority == $shipping_box_priority_pre))
				{
					$shipping_box_list .= "<div class='radio'><label class=\"radio\" for='shipping_box_id" . $shipping_box_id . "'><input " . $checked . " type='radio' id='shipping_box_id" . $shipping_box_id . "' name='shipping_box_id'  onclick='javascript:onestepCheckoutProcess(this.name,\'\');' value='" . $shipping_box_id . "' />";
					$shipping_box_list .= "" . $shippingBoxes[$i]->shipping_box_name . "</label></div>";
				}
			}
		}

		$box_template_desc = str_replace("{shipping_box_list}", $shipping_box_list, $box_template_desc);
		$style             = 'none';

		$shippingmethod = RedshopHelperOrder::getShippingMethodInfo();

		for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
		{
			if ($shippingmethod[$s]->element == 'bring' || $shippingmethod[$s]->element == 'ups' || $shippingmethod[$s]->element == 'uspsv4')
			{
				$style = 'block';
			}
		}

		if (count($shippingBoxes) <= 1 || count($shippingmethod) <= 1)
		{
			$style = 'none';
		}

		$box_template_desc = "<div style='display:$style;'>" . $box_template_desc . "</div>";

		return $box_template_desc;
	}

	public function replaceShippingTemplate($template_desc = "", $shipping_rate_id = 0, $shipping_box_post_id = 0, $user_id = 0, $users_info_id = 0, $ordertotal = 0, $order_subtotal = 0, $post = array())
	{
		$shippingmethod       = RedshopHelperOrder::getShippingMethodInfo();
		$rateExist            = 0;
		$d                    = array();
		$d['user_id']         = $user_id;
		$d['users_info_id']   = $users_info_id;
		$d['shipping_box_id'] = $shipping_box_post_id;
		$d['ordertotal']      = $ordertotal;
		$d['order_subtotal']  = $order_subtotal;
		$d['post']            = $post;
		$template_desc        = str_replace("{shipping_heading}", JText::_('COM_REDSHOP_SHIPPING_METHOD'), $template_desc);
		$extrafield_total     = "";

		if (strpos($template_desc, "{shipping_method_loop_start}") !== false && strpos($template_desc, "{shipping_method_loop_end}") !== false)
		{
			$template1       = explode("{shipping_method_loop_start}", $template_desc);
			$template1       = explode("{shipping_method_loop_end}", $template1[1]);
			$template_middle = $template1[0];

			$template_rate_middle = "";

			if (strpos($template_middle, "{shipping_rate_loop_start}") !== false && strpos($template_middle, "{shipping_rate_loop_end}") !== false)
			{
				$template1            = explode("{shipping_rate_loop_start}", $template_middle);
				$template1            = explode("{shipping_rate_loop_end}", $template1[1]);
				$template_rate_middle = $template1[0];
			}

			$rate_data = "";

			if ($template_middle != "" && count($shippingmethod) > 0)
			{
				JPluginHelper::importPlugin('redshop_shipping');
				$dispatcher   = RedshopHelperUtility::getDispatcher();
				$shippingrate = $dispatcher->trigger('onListRates', array(&$d));

				for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
				{
					if (isset($shippingrate[$s]) === false)
					{
						continue;
					}

					$rate = $shippingrate[$s];

					if (!empty($rate))
					{
						if (empty($shipping_rate_id))
						{
							$shipping_rate_id = $rate[0]->value;
						}

						$rs        = $shippingmethod[$s];
						$classname = $rs->element;
						$rate_data .= $template_middle;
						$rate_data = str_replace("{shipping_method_title}", JText::_($rs->name), $rate_data);

						if ($template_rate_middle != "")
						{
							$data         = "";
							$mainlocation = "";

							for ($i = 0, $in = count($rate); $i < $in; $i++)
							{
								$checked      = '';
								$data        .= $template_rate_middle;

								$displayrate = (trim($rate[$i]->rate) > 0) ? " (" . RedshopHelperProductPrice::formattedPrice((double) trim($rate[$i]->rate)) . " )" : "";

								if ((isset($rate[$i]->checked) && $rate[$i]->checked) || $rateExist == 0)
								{
									$checked = "checked";
								}

								if ($checked == "checked")
								{
									$shipping_rate_id = $rate[$i]->value;
								}

								$shipping_rate_name = '<label class="radio inline" for="shipping_rate_id_' . $shippingmethod[$s]->extension_id . '_' . $i . '"><input type="radio" id="shipping_rate_id_'
									. $shippingmethod[$s]->extension_id . '_' . $i . '" name="shipping_rate_id" value="'
									. $rate[$i]->value . '" '
									. $checked
									. ' onclick="javascript:onestepCheckoutProcess(this.name,\'' . $classname . '\');"><span>'
									. '' . html_entity_decode($rate[$i]->text) . '</span></label>';

								$shipping_rate_short_desc = '';

								if (isset($rate[$i]->shortdesc) === true)
								{
									$shipping_rate_short_desc = html_entity_decode($rate[$i]->shortdesc);
								}

								$shipping_rate_desc = '';

								if (isset($rate[$i]->longdesc) === true)
								{
									$shipping_rate_desc = html_entity_decode($rate[$i]->longdesc);
								}

								$rateExist++;
								$data = str_replace("{shipping_rate_name}", $shipping_rate_name, $data);
								$data = str_replace("{shipping_rate_short_desc}", $shipping_rate_short_desc, $data);
								$data = str_replace("{shipping_rate_desc}", $shipping_rate_desc, $data);
								$data = str_replace("{shipping_rate}", $displayrate, $data);

								if (strpos($data, "{shipping_location}") !== false)
								{
									$shippinglocation = RedshopHelperOrder::getShippingLocationInfo($rate[$i]->text);

									for ($k = 0, $kn = count($shippinglocation); $k < $kn; $k++)
									{
										if ($shippinglocation[$k] != '')
										{
											$mainlocation = $shippinglocation[$k]->shipping_location_info;
										}
									}

									$data = str_replace("{shipping_location}", $mainlocation, $data);
								}

								$dispatcher->trigger('onReplaceShippingTemplate', array($d, &$data, $classname, $checked));

								$data = str_replace("{gls_shipping_location}", "", $data);
							}

							$rate_data = str_replace("{shipping_rate_loop_start}", "", $rate_data);
							$rate_data = str_replace("{shipping_rate_loop_end}", "", $rate_data);
							$rate_data = str_replace($template_rate_middle, $data, $rate_data);
						}
					}

					if (strpos($rate_data, "{shipping_extrafields}") !== false)
					{
						$paymentparams_new  = new JRegistry($shippingmethod[$s]->params);
						$extrafield_payment = $paymentparams_new->get('extrafield_shipping');

						$extrafield_hidden  = "";

						if (!empty($extrafield_payment))
						{
							$countExtrafield = count($extrafield_payment);

							for ($ui = 0; $ui < $countExtrafield; $ui++)
							{
								$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($extrafield_payment[$ui], 19, '', 0, 0, 0);
								$extrafield_total .= $productUserFields[0] . " " . $productUserFields[1] . "<br>";
								$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_payment[$ui] . "'>";
							}

							$rate_data = str_replace("{shipping_extrafields}", "<div id='extrafield_shipping'>" . $extrafield_total . "</div>", $rate_data);
						}
						else
						{
							$rate_data = str_replace("{shipping_extrafields}", "", $rate_data);
						}
					}
				}
			}

			$template_desc = str_replace("{shipping_method_loop_start}", "", $template_desc);
			$template_desc = str_replace("{shipping_method_loop_end}", "", $template_desc);
			$template_desc = str_replace($template_middle, $rate_data, $template_desc);
		}

		if ($rateExist == 0)
		{
			$template_desc = "<div></div>";
		}

		JPluginHelper::importPlugin('redshop_checkout');
		JDispatcher::getInstance()->trigger('onRenderShippingMethod', array(&$template_desc));

		$returnarr = array("template_desc" => $template_desc, "shipping_rate_id" => $shipping_rate_id);

		return $returnarr;
	}

	/**
	 * Display credit card form based on payment method
	 *
	 * @param   integer  $payment_method_id  Payment Method ID for which form needs to be prepare
	 *
	 * @return  string     Credit Card form display data in HTML
	 */
	public function replaceCreditCardInformation($payment_method_id = 0)
	{
		if (empty($payment_method_id))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_REDSHOP_PAYMENT_NO_CREDIT_CARDS_PLUGIN_LIST_FOUND'),
				'error'
			);

			return '';
		}

		$paymentmethod = RedshopHelperOrder::getPaymentMethodInfo($payment_method_id);
		$paymentmethod = $paymentmethod[0];

		$cardinfo = "";

		if (file_exists(JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '/' . $paymentmethod->element . '.php'))
		{
			$paymentparams = new Registry($paymentmethod->params);
			$acceptedCredictCard = $paymentparams->get("accepted_credict_card", array());

			if ($paymentparams->get('is_creditcard', 0)
				&& !empty($acceptedCredictCard))
			{
				$cardinfo = RedshopLayoutHelper::render(
						'order.payment.creditcard',
						array(
							'pluginParams' => $paymentparams,
						)
					);
			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_PAYMENT_CREDIT_CARDS_NOT_FOUND'), 'error');
			}
		}

		return $cardinfo;
	}

	/**
	 * Replace Payment Methods
	 *
	 * @param   string  $templateDesc    Template Content
	 * @param   integer $paymentMethodId Payment Method Id
	 * @param   integer $isCompany       Is Company?
	 * @param   integer $eanNumber       Ean Number
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function replacePaymentTemplate($templateDesc = "", $paymentMethodId = 0, $isCompany = 0, $eanNumber = 0)
	{
		$userId = JFactory::getUser()->id;

		$paymentMethods = RedshopHelperPayment::info();

		// Get common payment methods of product in this cart
		$commonPaymentMethods = RedshopHelperPayment::getPaymentMethodInCheckOut($paymentMethods);

		if (!empty($commonPaymentMethods))
		{
			$templateDesc = str_replace("{payment_heading}", JText::_('COM_REDSHOP_PAYMENT_METHOD'), $templateDesc);

			if (strpos($templateDesc, "{split_payment}") !== false)
			{
				$templateDesc = str_replace("{split_payment}", "", $templateDesc);
			}

			if (strpos($templateDesc, "{payment_loop_start}") !== false && strpos($templateDesc, "{payment_loop_end}") !== false)
			{
				$template1      = explode("{payment_loop_start}", $templateDesc);
				$template1      = explode("{payment_loop_end}", $template1[1]);
				$templateMiddle = $template1[0];
				$shopperGroupId = RedshopHelperUser::getShopperGroup($userId);
				$paymentDisplay = "";
				$hasCreditCard  = false;

				// Filter payment gateways array for shopperGroups
				$paymentMethods = array_filter(
					$paymentMethods,
					function ($paymentMethod) use ($shopperGroupId)
					{
						$paymentFilePath = JPATH_SITE
							. '/plugins/redshop_payment/'
							. $paymentMethod->name . '/' . $paymentMethod->name . '.php';

						if (!JFile::exists($paymentFilePath))
						{
							return false;
						}

						$shopperGroups  = $paymentMethod->params->get('shopper_group_id', array());

						if (!is_array($shopperGroups))
						{
							$shopperGroups = array($shopperGroups);
						}

						$shopperGroups = ArrayHelper::toInteger($shopperGroups);

						if (in_array((int) $shopperGroupId, $shopperGroups) || (!isset($shopperGroups[0]) || 0 == $shopperGroups[0]))
						{
							return true;
						}

						return false;
					}
				);

				$totalPaymentMethod = count($paymentMethods);

				if ($totalPaymentMethod > 0)
				{
					foreach ($paymentMethods as $index => $oneMethod)
					{
						if (in_array($oneMethod->name, $commonPaymentMethods))
						{
							$cardInformation        = "";
							$displayPayment = "";
							include_once JPATH_SITE . '/plugins/redshop_payment/' . $oneMethod->name . '/' . $oneMethod->name . '.php';

							$lang = JFactory::getLanguage();
							$lang->load('plg_redshop_payment_' . $oneMethod->name, JPATH_ADMINISTRATOR, $lang->getTag(), true);

							$privatePerson = $oneMethod->params->get('private_person', '');
							$business      = $oneMethod->params->get('business', '');
							$isCreditCard  = (boolean) $oneMethod->params->get('is_creditcard', 0);
							$checked       = $paymentMethodId === $oneMethod->name || $totalPaymentMethod <= 1;

							$paymentRadioOutput = RedshopLayoutHelper::render(
								'checkout.payment_radio',
								array(
										'oneMethod'          => $oneMethod,
										'paymentMethodId'    => $paymentMethodId,
										'index'              => $index,
										'totalPaymentMethod' => $totalPaymentMethod,
										'checked'            => $checked,
										'isCompany'          => $isCompany,
										'eanNumber'          => $eanNumber
									),
								'',
								array(
									'component' => 'com_redshop'
								)
							);

							$isSubscription = false;

							// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
							$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($oneMethod->name);

							if ($oneMethod->name == 'rs_payment_eantransfer' || $isBankTransferPaymentType)
							{
								if ($isCompany == 0 && $privatePerson == 1)
								{
									$displayPayment = $paymentRadioOutput;
								}
								else
								{
									if ($isCompany == 1 && $business == 1 &&
										($oneMethod->name != 'rs_payment_eantransfer'
											|| ($oneMethod->name == 'rs_payment_eantransfer' && $eanNumber != 0)))
									{
										$displayPayment = $paymentRadioOutput;
									}
								}
							}
							elseif ($isSubscription)
							{
								$displayPayment = '<label class="radio" for="' . $oneMethod->name . $index . '">'
									. '<input id="' . $oneMethod->name . $index . '" type="radio" name="payment_method_id" value="'
									. $oneMethod->name . '" '
									. ($checked ? 'checked="checked"' :  '')
									. ' onclick="javascript:onestepCheckoutProcess(this.name);" />'
									. '' . JText::_($oneMethod->name) . '</label><br>';

								$displayPayment .= '<table><tr><td>'
									. JText::_('COM_REDSHOP_SUBSCRIPTION_PLAN')
									. '</td><td>' . $this->getSubscriptionPlans()
									. '<td></tr><table>';
							}
							else
							{
								$displayPayment = $paymentRadioOutput;
							}

							if ($isCreditCard)
							{
								$cardInformation = '<div id="divcardinfo_' . $oneMethod->name . '">';

								$cart = JFactory::getSession()->get('cart');

								if ($checked && Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')  && $cart['total'] > 0)
								{
									$cardInformation .= $this->replaceCreditCardInformation($oneMethod->name);
								}

								$cardInformation .= '</div>';

								$hasCreditCard = true;
							}

							$templateMiddle1 = str_replace(
								'<div class="extrafield_payment">',
								'<div class="extrafield_payment" id="' . $oneMethod->name . '">',
								$templateMiddle
							);

							$paymentDisplay .= $templateMiddle1;
							$paymentDisplay = str_replace("{payment_method_name}", $displayPayment, $paymentDisplay);
							$paymentDisplay = str_replace("{creditcard_information}", $cardInformation, $paymentDisplay);

							if (strpos($paymentDisplay, "{payment_extrafields}") !== false)
							{
								$paymentExtraFieldsHtml = '';

								if ($checked)
								{
									$layoutFile = new JLayoutFile('order.payment.extrafields');

									// Append plugin JLayout path to improve view based on plugin if needed.
									$layoutFile->addIncludePath(JPATH_SITE . '/plugins/' . $oneMethod->type . '/' . $oneMethod->name . '/layouts');
									$paymentExtraFieldsHtml =  $layoutFile->render(array('plugin' => $oneMethod));
								}

								$paymentDisplay = str_replace(
									'{payment_extrafields}',
									'<div class="extrafield_payment">' . $paymentExtraFieldsHtml . '</div>',
									$paymentDisplay
								);
							}
						}
					}
				}

				$templateDesc = str_replace("{payment_loop_start}", "", $templateDesc);
				$templateDesc = str_replace("{payment_loop_end}", "", $templateDesc);
				$templateDesc = str_replace($templateMiddle, $paymentDisplay, $templateDesc);

				if (count($paymentMethods) == 1 && !$hasCreditCard)
				{
					$templateDesc = "<div style='display:none;'>" . $templateDesc . "</div>";
				}
			}
		}
		else
		{
			//clear
			$templateDesc = str_replace("{creditcard_information}", "", $templateDesc);
			$templateDesc = str_replace("{payment_loop_start}", "", $templateDesc);
			$templateDesc = str_replace("{payment_loop_end}", "", $templateDesc);
			//new template
			$templateDesc = str_replace("{payment_heading}", JText::_('COM_REDSHOP_PAYMENT_METHOD_CONFLICT'), $templateDesc);
			$templateDesc = str_replace(
				"{payment_method_name}",
				RedshopHelperPayment::displayPaymentMethodInCheckOut($paymentMethods),
				$templateDesc
			);
		}

		return $templateDesc;
	}

	public function replaceTermsConditions($template_desc = "", $Itemid = 1)
	{
		if (strpos($template_desc, "{terms_and_conditions") !== false)
		{
			$user    = JFactory::getUser();
			$session = JFactory::getSession();
			$auth    = $session->get('auth');
			$list    = array();

			if ($user->id)
			{
				$query = "SELECT u.* FROM " . $this->_table_prefix . "users_info AS u "
					. "WHERE u.user_id='" . $user->id . "' "
					. "AND address_type='BT' ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}
			elseif (isset($auth['users_info_id']) && $auth['users_info_id'] > 0)
			{
				$query = "SELECT u.* FROM " . $this->_table_prefix . "users_info AS u "
					. "WHERE u.users_info_id='" . $auth['users_info_id'] . "' "
					. "AND address_type='BT' ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}

			$terms_left_final = "";

			if (strpos($template_desc, "{terms_and_conditions:") !== false && strpos($template_desc, "}") !== false)
			{
				$terms_left_one   = explode("{terms_and_conditions:", $template_desc);
				$terms_left_two   = explode("}", $terms_left_one[1]);
				$terms_left_three = explode(":", $terms_left_two[0]);
				$terms_left_final = $terms_left_three[0];
			}

			$finaltag       = ($terms_left_final != "") ? "{terms_and_conditions:$terms_left_final}" : "{terms_and_conditions}";
			$termscondition = '';

			if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 0 || (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1 && ((count($list) > 0 && $list->accept_terms_conditions == 0) || count($list) == 0)))
			{
				$finalwidth  = "500";
				$finalheight = "450";

				if ($terms_left_final != "")
				{
					$dimension = explode(" ", $terms_left_final);

					if (count($dimension) > 0)
					{
						if (strpos($dimension[0], "width") !== false)
						{
							$width      = explode("width=", $dimension[0]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
						else
						{
							$height      = explode("height=", $dimension[0]);
							$finalheight = (isset($height[1])) ? $height[0] : "450";
						}

						if (strpos($dimension[1], "height") !== false)
						{
							$height      = explode("height=", $dimension[1]);
							$finalheight = (isset($height[1])) ? $height[1] : "450";
						}
						else
						{
							$width      = explode("width=", $dimension[1]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
					}
				}

				$url            = JURI::base();
				$article_link   = $url . "index.php?option=com_content&amp;view=article&amp;id=" . Redshop::getConfig()->get('TERMS_ARTICLE_ID') . "&Itemid=" . $Itemid . "&tmpl=component";
				$termscondition = '<label class="checkbox"><input type="checkbox" id="termscondition" name="termscondition" value="1" /> ';
				$termscondition .= JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL');
				$termscondition .= ' <a class="modal" href="' . $article_link . '" rel="{handler: \'iframe\', size: {x: ' . $finalwidth . ', y: ' . $finalheight . '}}">' . JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_FOR_LBL') . '</a></label>';
			}

			$template_desc = str_replace($finaltag, $termscondition, $template_desc);
		}

		return $template_desc;
	}

	public function replaceNewsletterSubscription($template_desc = "", $onchange = 0)
	{
		$db = JFactory::getDbo();

		if (strpos($template_desc, "{newsletter_signup_chk}") !== false)
		{
			$Itemid               = $this->input->get('Itemid');
			$newslettersignup     = "";
			$newslettersignup_lbl = "";
			$link                 = "";

			if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != 0)
			{
				$user  = JFactory::getUser();
				$query = "SELECT subscription_id FROM " . $this->_table_prefix . "newsletter_subscription"
					. " WHERE user_id=" . (int) $user->id . " AND email=" . $db->quote($user->email);
				$this->_db->setQuery($query);
				$subscribe = $this->_db->loadResult();

				if ($subscribe == 0)
				{
					if ($onchange)
					{
						$link = " onchange='window.location.href=\"" . JURI::root() . "index.php?option=com_redshop&view=account&task=newsletterSubscribe&tmpl=component&Itemid=" . $Itemid . "\"";

					}

					$newslettersignup     = "<input type='checkbox' name='newsletter_signup' value='1' '$link'>";
					$newslettersignup_lbl = JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
				}
			}

			$template_desc = str_replace("{newsletter_signup_chk}", $newslettersignup, $template_desc);
			$template_desc = str_replace("{newsletter_signup_lbl}", $newslettersignup_lbl, $template_desc);
			$template_desc = str_replace("{newsletter_unsubscribe}", "", $template_desc);
		}

		return $template_desc;
	}

	public function getCartProductPrice($product_id, $cart)
	{
		$productArr             = array();
		$affected_product_idArr = array();
		$idx                    = $cart['idx'];
		$product_price          = 0;
		$product_price_excl_vat = 0;
		$quantity               = 0;
		$product_idArr          = explode(',', $product_id);
		$product_idArr          = Joomla\Utilities\ArrayHelper::toInteger($product_idArr);

		for ($v = 0; $v < $idx; $v++)
		{
			if (in_array($cart[$v]['product_id'], $product_idArr) || $this->_globalvoucher)
			{
				// Set Quantity based on discount type - i.e Multiple or Single.
				$p_quantity = (Redshop::getConfig()->get('DISCOUNT_TYPE') == 4) ? $cart[$v]['quantity'] : 1;

				$product_price            += ($cart[$v]['product_price'] * $p_quantity);
				$product_price_excl_vat   += $cart[$v]['product_price_excl_vat'] * $p_quantity;
				$affected_product_idArr[] = $cart[$v]['product_id'];

				$quantity += $p_quantity;
			}
		}

		$productArr['product_ids']            = implode(',', $affected_product_idArr);
		$productArr['product_price']          = $product_price;
		$productArr['product_price_excl_vat'] = $product_price_excl_vat;
		$productArr['product_quantity']       = $p_quantity;

		return $productArr;
	}

	/**
	 * Method for apply coupon to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool
	 *
	 * @deprecated   2.0.7
	 *
	 * @see  RedshopHelperCartDiscount::applyCoupon()
	 *
	 * @throws  Exception
	 */
	public function coupon($cartData = array())
	{
		return RedshopHelperCartDiscount::applyCoupon($cartData);
	}

	/**
	 * Method for apply voucher to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool
	 *
	 * @deprecated   2.0.7
	 *
	 * @throws  Exception
	 *
	 * @see  RedshopHelperCartDiscount::applyVoucher()
	 */
	public function voucher($cartData = array())
	{
		return RedshopHelperCartDiscount::applyVoucher($cartData);
	}

	/**
	 * Re-calculate the Voucher/Coupon value when the product is already discount
	 *
	 * @param   float  $value  Voucher/Coupon value
	 * @param   array  $cart   Cart array
	 *
	 * @return  float          Voucher/Coupon value
	 *
	 * @deprecated  2.1.0
	 *
	 * @see  RedshopHelperDiscount::calculateAlreadyDiscount()
	 */
	public function calcAlreadyDiscount($value, $cart)
	{
		return RedshopHelperDiscount::calculateAlreadyDiscount($value, $cart);
	}

	public function rs_multi_array_key_exists($needle, $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			if ($needle === $key)
			{
				return true;
			}

			if (is_array($value))
			{
				if ($this->rs_multi_array_key_exists($needle, $value))
				{
					return true;
				}
			}
		}

		return false;
	}

	public function rs_recursiveArraySearch($haystack, $needle, $index = null)
	{
		$aIt = new RecursiveArrayIterator($haystack);
		$it  = new RecursiveIteratorIterator($aIt);

		while ($it->valid())
		{
			if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle))
			{
				return true;
			}

			$it->next();
		}

		return false;
	}

	/**
	 * Method for calculate discount.
	 *
	 * @param   string  $type   Type of discount
	 * @param   array   $types  List of type
	 *
	 * @return  float
	 *
	 * @deprecated  2.1.0
	 *
	 * @see RedshopHelperDiscount::calculate()
	 */
	public function calculateDiscount($type, $types)
	{
		return RedshopHelperDiscount::calculate($type, $types);
	}

	public function getVoucherData($voucher_code)
	{
		$db = JFactory::getDbo();

		$user         = JFactory::getUser();
		$voucher      = array();
		$current_time = JFactory::getDate()->toSql();
		$globalVouchers = $this->globalvoucher($voucher_code);

		if ($this->_globalvoucher != 1)
		{
			if ($user->id)
			{
				$subQuery = $db->getQuery(true)
					->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
					->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
					->where('v.id = pv.voucher_id');

				$query = $db->getQuery(true)
					->select(
						array('vt.transaction_voucher_id', 'vt.amount AS total', 'vt.product_id', 'v.*', '(' . $subQuery . ') AS nproduct')
					)
					->from($db->qn('#__redshop_voucher', 'v'))
					->leftJoin($db->qn('#__redshop_product_voucher_transaction', 'vt') . ' ON vt.voucher_id = v.id')
					->where('vt.voucher_code = ' . $db->quote($voucher_code))
					->where('vt.amount > 0')
					->where('v.type = ' . $db->quote('Total'))
					->where('v.published = 1')
					->where(
						'('
						. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($current_time) . ')'
						. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($current_time) . ')'
						. ')'
					)
					->where('vt.user_id = ' . (int) $user->id)
					->order('vt.transaction_voucher_id DESC');

				$voucher = $db->setQuery($query)->loadObject();

				if (count($voucher) > 0)
				{
					return false;
				}
			}

			if (count($voucher) <= 0)
			{
				$subQuery = $db->getQuery(true)
					->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
					->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
					->where($db->qn('v.id') . ' = ' . $db->qn('pv.voucher_id'));

				$query = $db->getQuery(true)
					->select(
						array(
							'(' . $subQuery . ') AS nproduct', 'v.amount AS total', 'v.type',
							'v.free_ship', 'v.id', 'v.code', 'v.voucher_left')
					)
					->from($db->qn('#__redshop_voucher', 'v'))
					->where($db->qn('v.published') . ' = 1')
					->where($db->qn('v.code') . ' = ' . $db->quote($voucher_code))
					->where('('
						. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($current_time) . ')'
						. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($current_time) . ')'
						. ')')
					->where($db->qn('v.voucher_left') . ' > 0');

				return $db->setQuery($query)->loadObject();
			}
		}

		return $globalVouchers;
	}

	public function globalvoucher($voucherCode)
	{
		$db = JFactory::getDbo();

		$currentTime = JFactory::getDate()->toSql();

		$query = $db->getQuery(true)
			->select($db->qn('pv.product_id'))
			->select('v.*')
			->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
			->leftJoin($db->qn('#__redshop_voucher', 'v') . ' ON ' . $db->qn('v.id') . ' = ' . $db->qn('pv.voucher_id'))
			->where($db->qn('v.published') . ' = 1')
			->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
			->where('('
				. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
				. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
				. ')')
			->where($db->qn('v.voucher_left') . ' > 0');

		$voucher = $this->_db->setQuery($query)->loadObject();

		if ($voucher)
		{
			return $voucher;
		}

		$this->_globalvoucher = 1;

		$query->clear()
			->select('v.*')
			->select($db->qn('v.amount', 'total'))
			->from($db->qn('#__redshop_voucher', 'v'))
			->where($db->qn('v.published') . ' = 1')
			->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
			->where('('
				. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
				. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
				. ')')
			->where($db->qn('v.voucher_left') . ' > 0');

		return $this->_db->setQuery($query)->loadObject();
	}

	/**
	 * @param   string   $couponCode  Coupon code
	 * @param   integer  $subtotal    Subtotal
	 *
	 * @return   array|mixed
	 */
	public function getCouponData($couponCode, $subtotal = 0)
	{
		$db = JFactory::getDbo();

		$today  = JFactory::getDate()->toSql();
		$user   = JFactory::getUser();
		$coupon = array();

		// Create the base select statement.
		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__redshop_coupons', 'c'))
			->where($db->qn('c.published') . ' = 1')
			->where(
			'('
				. '(' . $db->qn('c.start_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('c.start_date') . ' <= ' . $db->quote($today) . ')'
				. ' AND (' . $db->qn('c.end_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('c.end_date') . ' >= ' . $db->quote($today) . ')'
				. ')'
			);

		if ($user->id)
		{
			$userQuery = clone($query);
			$userQuery->select(
					array(
						$db->qn('ct.coupon_value', 'coupon_value'),
						$db->qn('ct.userid'),
						$db->qn('ct.transaction_coupon_id')
					)
				)
				->leftjoin(
					$db->qn('#__redshop_coupons_transaction', 'ct')
					. ' ON ' . $db->qn('ct.coupon_id') . ' = ' . $db->qn('c.id')
				)
				->where($db->qn('ct.coupon_value') . ' > 0')
				->where($db->qn('ct.coupon_code') . ' = ' . $db->quote($couponCode))
				->where($db->qn('ct.userid') . ' = ' . (int) $user->id)
				->order($db->qn('ct.transaction_coupon_id') . ' DESC');

			$db->setQuery($userQuery, 0, 1);
			$coupon = $db->loadObject();

			if (count($coupon) > 0)
			{
				$this->_c_remain = 1;
			}
		}

		if (count($coupon) <= 0)
		{
			$query->where($db->qn('c.code') . ' = ' . $db->quote($couponCode))

				->where($db->qn('c.amount_left') . ' > 0')
				->where(
					'('
						. $db->quote($subtotal) . ' >= ' . $db->qn('c.subtotal')
						. ' OR ' . $db->qn('c.subtotal') . ' = 0'
					. ')'
				);

			$db->setQuery($query, 0, 1);
			$coupon = $db->loadObject();
		}

		return $coupon;
	}

	/**
	 * Method for modify discount
	 *
	 * @param   array $cart Cart data.
	 *
	 * @return  mixed
	 *
	 * @throws  Exception
	 */
	public function modifyDiscount($cart)
	{
		$calArr                            = \Redshop\Cart\Helper::calculation($cart);
		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];

		$couponIndex  = !empty($cart['coupon']) && is_array($cart['coupon']) ? count($cart['coupon']) : 0;
		$voucherIndex = !empty($cart['voucher']) && is_array($cart['voucher']) ? count($cart['voucher']) : 0;

		$discountAmount = 0;

		if (Redshop::getConfig()->getBool('DISCOUNT_ENABLE'))
		{
			$discountAmount = Redshop\Cart\Helper::getDiscountAmount($cart);

			if ($discountAmount > 0)
			{
				$cart = RedshopHelperCartSession::getCart();
			}
		}

		if (!isset($cart['quotation_id']) || (isset($cart['quotation_id']) && !$cart['quotation_id']))
		{
			$cart['cart_discount'] = $discountAmount;
		}

		// Calculate voucher discount
		$voucherDiscount = 0;

		if (array_key_exists('voucher', $cart))
		{
			if (count($cart['voucher']) > 1)
			{
				foreach ($cart['voucher'] as $cartVoucher)
				{
					$voucherDiscount += $cartVoucher['voucher_value'];
				}
			}
			else
			{
				if (!empty($cart['voucher'][0]['voucher_value']))
				{
					$voucherDiscount = $cart['voucher'][0]['voucher_value'];
				}
				else
				{
					for ($v = 0; $v < $voucherIndex; $v++)
					{
						$voucherCode = $cart['voucher'][$v]['voucher_code'];

						unset($cart['voucher'][$v]);

						$cart = RedshopHelperCartDiscount::applyVoucher($cart, $voucherCode);
					}

					$voucherDiscount = RedshopHelperDiscount::calculate('voucher', $cart['voucher']);

					empty($voucherDiscount) ? $voucherDiscount = $cart['voucher_discount'] : $voucherDiscount;
				}
			}
		}

		$cart['voucher_discount'] = $voucherDiscount;

		// Calculate coupon discount
		$couponDiscount = 0;

		if (array_key_exists('coupon', $cart))
		{
			if (count($cart['coupon']) > 1)
			{
				foreach ($cart['coupon'] as $cartCoupon)
				{
					$couponDiscount += $cartCoupon['coupon_value'];
				}
			}
			else
			{
				if (!empty($cart['coupon'][0]['coupon_value']) && !Redshop::getConfig()->get('DISCOUNT_TYPE') == 2)
				{
					$couponDiscount = $cart['coupon'][0]['coupon_value'];
				}
				else
				{
					for ($c = 0; $c < $couponIndex; $c++)
					{
						$couponCode = $cart['coupon'][$c]['coupon_code'];

						unset($cart['coupon'][$c]);

						$cart = RedshopHelperCartDiscount::applyCoupon($cart, $couponCode);
					}

					$couponDiscount = RedshopHelperDiscount::calculate('coupon', $cart['coupon']);

					empty($couponDiscount) ? $couponDiscount = $cart['coupon_discount'] : $couponDiscount;
				}
			}
		}

		$cart['coupon_discount'] = $couponDiscount;

		$codeDiscount  = $voucherDiscount + $couponDiscount;
		$totalDiscount = $cart['cart_discount'] + $codeDiscount;

		$calArr      = \Redshop\Cart\Helper::calculation((array) $cart);
		$tax         = $calArr[5];
		$discountVAT = 0;
		$chktag      = RedshopHelperCart::taxExemptAddToCart();

		if (Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT') && !empty($chktag)
			&& !Redshop::getConfig()->getBool('APPLY_VAT_ON_DISCOUNT'))
		{
			$vatData = RedshopHelperUser::getVatUserInformation();

			if (!empty($vatData->tax_rate))
			{
				$productPriceExclVAT = (float) $cart['product_subtotal_excl_vat'];
				$productVAT          = (float) $cart['product_subtotal'] - $cart['product_subtotal_excl_vat'];

				if ($productPriceExclVAT > 0)
				{
					$avgVAT      = (($productPriceExclVAT + $productVAT) / $productPriceExclVAT) - 1;
					$discountVAT = ($avgVAT * $totalDiscount) / (1 + $avgVAT);
				}
			}
		}

		$cart['total'] = $calArr[0] - $totalDiscount;
		$cart['total'] = $cart['total'] < 0 ? 0 : $cart['total'];

		$cart['subtotal'] = $calArr[1] + $calArr[3] - $totalDiscount;
		$cart['subtotal'] = $cart['subtotal'] < 0 ? 0 : $cart['subtotal'];

		$cart['subtotal_excl_vat'] = $calArr[2] + ($calArr[3] - $calArr[6]) - ($totalDiscount - $discountVAT);
		$cart['subtotal_excl_vat'] = $cart['total'] <= 0 ? 0 : $cart['subtotal_excl_vat'];

		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];
		$cart['shipping']                  = $calArr[3];
		$cart['tax']                       = $tax;
		$cart['sub_total_vat']             = $tax + $calArr[6];
		$cart['discount_vat']              = $discountVAT;
		$cart['shipping_tax']              = $calArr[6];
		$cart['discount_ex_vat']           = $totalDiscount - $discountVAT;
		$cart['mod_cart_total']            = Redshop\Cart\Module::calculate((array) $cart);

		$this->_session->set('cart', $cart);

		return $cart;
	}

	public function getWrapperPriceArr($cartArr = array())
	{
		$wrapper     = $this->_producthelper->getWrapper($cartArr['product_id'], $cartArr['wrapper_id']);
		$wrapper_vat = 0;
		$wrapperArr  = array();

		if (count($wrapper) > 0)
		{
			if ($wrapper[0]->wrapper_price > 0)
			{
				$wrapper_vat = RedshopHelperProduct::getProductTax($cartArr['product_id'], $wrapper[0]->wrapper_price);
			}

			$wrapper_price = $wrapper[0]->wrapper_price;
		}

		$wrapperArr['wrapper_vat']   = $wrapper_vat;
		$wrapperArr['wrapper_price'] = $wrapper_price;

		return $wrapperArr;
	}

	public function checkQuantityInStock($data = array(), $newquantity = 1, $minQuantity = 0)
	{
		JPluginHelper::importPlugin('redshop_product');
		$result = RedshopHelperUtility::getDispatcher()->trigger('onCheckQuantityInStock', array(&$data, &$newquantity, &$minQuantity));

		if (in_array(true, $result, true))
		{
			return $newquantity;
		}

		$productData     = RedshopHelperProduct::getProductById($data['product_id']);
		$productPreOrder = $productData->preorder;

		if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $newquantity)
		{
			$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MINIMUM_QUANTITY');
			$msg = sprintf($msg, $productData->min_order_product_quantity);
			/** @scrutinizer ignore-deprecated */
			JError::raiseWarning('', $msg);
			$newquantity = $productData->min_order_product_quantity;
		}

		if (!Redshop::getConfig()->getBool('USE_STOCKROOM'))
		{
			return $newquantity;
		}

		$productStock  = 0;
		$allowPreOrder = Redshop::getConfig()->getBool('ALLOW_PRE_ORDER');

		if (($productPreOrder == 'global' && !$allowPreOrder)
			|| $productPreOrder == 'no'
			|| ($productPreOrder == "" && !$allowPreOrder))
		{
			$productStock = RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
		}

		if (($productPreOrder == "global" && $allowPreOrder)
			|| $productPreOrder == "yes"
			|| ($productPreOrder == "" && $allowPreOrder))
		{
			$productStock  = RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
			$productStock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($data['product_id']);
		}

		$ownProductReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock($data['product_id']);
		$attArr                 = $data['cart_attribute'];

		if (count($attArr) <= 0)
		{
			if ($productStock >= 0)
			{
				if ($newquantity > $ownProductReserveStock && $productStock < ($newquantity - $ownProductReserveStock))
				{
					$newquantity = $productStock + $ownProductReserveStock;
				}
			}
			else
			{
				$newquantity = $productStock + $ownProductReserveStock;
			}

			if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
			{
				$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MAXIMUM_QUANTITY');
				$msg = sprintf($msg, $productData->max_order_product_quantity);
				/** @scrutinizer ignore-deprecated */
				JError::raiseWarning('', $msg);
				$newquantity = $productData->max_order_product_quantity;
			}

			if (array_key_exists('quantity', $data))
			{
				$productReservedQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
			}
			else
			{
				$productReservedQuantity = $newquantity;
			}

			RedshopHelperStockroom::addReservedStock($data['product_id'], $productReservedQuantity, 'product');
		}
		else
		{
			for ($i = 0, $in = count($attArr); $i < $in; $i++)
			{
				$propArr = $attArr[$i]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					// Get subproperties from add to cart tray.
					$subpropArr = $propArr[$k]['property_childs'];
					$totalSubProperty = count($subpropArr);
					$ownReservePropertyStock = RedshopHelperStockroom::getCurrentUserReservedStock($propArr[$k]['property_id'], 'property');
					$property_stock = 0;

					if (($productPreOrder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "no") || ($productPreOrder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						$property_stock = RedshopHelperStockroom::getStockroomTotalAmount($propArr[$k]['property_id'], "property");
					}

					if (($productPreOrder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes") || ($productPreOrder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						$property_stock = RedshopHelperStockroom::getStockroomTotalAmount($propArr[$k]['property_id'], "property");
						$property_stock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($propArr[$k]['property_id'], "property");
					}

					// Get Property stock only when SubProperty is not in cart
					if ($totalSubProperty <= 0)
					{
						if ($property_stock >= 0)
						{
							if ($newquantity > $ownReservePropertyStock && $property_stock < ($newquantity - $ownReservePropertyStock))
							{
								$newquantity = $property_stock + $ownReservePropertyStock;
							}
						}
						else
						{
							$newquantity = $property_stock + $ownReservePropertyStock;
						}

						if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
						{
							$newquantity = $productData->max_order_product_quantity;
						}

						if (array_key_exists('quantity', $data))
						{
							$propertyReservedQuantity = $ownReservePropertyStock + $newquantity - $data['quantity'];
							$newProductQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
						}
						else
						{
							$propertyReservedQuantity = $newquantity;
							$newProductQuantity = $ownProductReserveStock + $newquantity;
						}

						RedshopHelperStockroom::addReservedStock($propArr[$k]['property_id'], $propertyReservedQuantity, "property");
						RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
					}
					else
					{
						// Get SubProperty Stock here.
						for ($l = 0; $l < $totalSubProperty; $l++)
						{
							$subproperty_stock = 0;

							if (($productPreOrder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "no") || ($productPreOrder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
							{
								$subproperty_stock = RedshopHelperStockroom::getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
							}

							if (($productPreOrder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes") || ($productPreOrder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
							{
								$subproperty_stock = RedshopHelperStockroom::getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
								$subproperty_stock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
							}

							$ownSubPropReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock($subpropArr[$l]['subproperty_id'], "subproperty");

							if ($subproperty_stock >= 0)
							{
								if ($newquantity > $ownSubPropReserveStock && $subproperty_stock < ($newquantity - $ownSubPropReserveStock))
								{
									$newquantity = $subproperty_stock + $ownSubPropReserveStock;
								}
							}
							else
							{
								$newquantity = $subproperty_stock + $ownSubPropReserveStock;
							}

							if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
							{
								$newquantity = $productData->max_order_product_quantity;
							}

							if (array_key_exists('quantity', $data))
							{
								$subPropertyReservedQuantity = $ownSubPropReserveStock + $newquantity - $data['quantity'];
								$newPropertyQuantity = $ownReservePropertyStock + $newquantity - $data['quantity'];
								$newProductQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
							}
							else
							{
								$subPropertyReservedQuantity = $newquantity;
								$newPropertyQuantity = $ownReservePropertyStock + $newquantity;
								$newProductQuantity = $ownProductReserveStock + $newquantity;
							}

							RedshopHelperStockroom::addReservedStock($subpropArr[$l]['subproperty_id'], $subPropertyReservedQuantity, 'subproperty');
							RedshopHelperStockroom::addReservedStock($propArr[$k]['property_id'], $newPropertyQuantity, 'property');
							RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
						}
					}
				}
			}
		}

		return $newquantity;
	}

	/**
	 * Method for calculate final price of cart.
	 *
	 * @param   bool  $callmodify  Is modify cart?
	 *
	 * @return  array
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::cartFinalCalculation() instead.
	 *
	 * @throws  Exception
	 */
	public function cartFinalCalculation($callmodify = true)
	{
		return RedshopHelperCart::cartFinalCalculation($callmodify);
	}

	/**
	 * Store Cart to Database
	 *
	 * @param   array  $cart   Cart
	 *
	 * @return  null
	 * @throws  Exception
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function carttodb($cart = array())
	{
		return RedshopHelperCart::addCartToDatabase($cart);
	}

	/**
	 * Store Cart Attribute to Database
	 *
	 * @param   array    $attribute      Cart attribute data.
	 * @param   int      $cart_item_id   Cart item ID
	 * @param   int      $product_id     Cart product ID.
	 * @param   boolean  $isAccessary    Is this accessory?
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function attributetodb($attribute = array(), $cart_item_id = 0, $product_id = 0, $isAccessary = false)
	{
		return RedshopHelperCart::addCartAttributeToDatabase($attribute, $cart_item_id, $product_id, $isAccessary);
	}

	/**
	 * Remove cart entry from table
	 *
	 * @param   int  $cart_id   #__redshop_usercart table key id
	 * @param   int  $userid    user information id - joomla #__users table key id
	 * @param   bool $delCart   remove cart from #__redshop_usercart table
	 *
	 * @return bool
	 *
	 * @deprecated  2.0.3  Use edshopHelperCart::removeCartFromDatabase() instead.
	 */
	public function removecartfromdb($cart_id = 0, $userid = 0, $delCart = false)
	{
		return RedshopHelperCart::removeCartFromDatabase($cart_id, $userid, $delCart);
	}

	/**
	 * Method for convert data from database to cart.
	 *
	 * @param   int  $userId  ID of user.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::databaseToCart() instead.
	 */
	public function dbtocart($userId = 0)
	{
		RedshopHelperCart::databaseToCart($userId);
	}

	/**
	 * Method for generate attribute from cart.
	 *
	 * @param   int  $cart_item_id       ID of cart item.
	 * @param   int  $is_accessory       Is accessory?
	 * @param   int  $parent_section_id  ID of parent section
	 * @param   int  $quantity           Quantity of product.
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3
	 */
	public function generateAttributeFromCart($cart_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		return RedshopHelperCart::generateAttributeFromCart($cart_item_id, $is_accessory, $parent_section_id, $quantity);
	}

	public function generateAccessoryFromCart($cart_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$cartItemdata = $this->getCartItemAccessoryDetail($cart_item_id);

		for ($i = 0, $in = count($cartItemdata); $i < $in; $i++)
		{
			$accessory          = RedshopHelperAccessory::getProductAccessories($cartItemdata[$i]->product_id);
			$accessorypricelist = \Redshop\Product\Accessory::getPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$i]['accessory_id']     = $cartItemdata[$i]->product_id;
			$generateAccessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
			$generateAccessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
			$generateAccessoryCart[$i]['accessory_price']  = $accessory_price;
			$generateAccessoryCart[$i]['accessory_childs'] = RedshopHelperCart::generateAttributeFromCart($cart_item_id, 1, $cartItemdata[$i]->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function getCartItemAccessoryDetail($cart_item_id = 0)
	{
		$list = null;

		if ($cart_item_id != 0)
		{
			$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_accessory_item "
				. "WHERE cart_item_id=" . (int) $cart_item_id;
			$this->_db->setQuery($query);
			$list = $this->_db->loadObjectlist();
		}

		return $list;
	}

	public function getCartItemAttributeDetail($cart_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$db = JFactory::getDbo();

		$and = "";

		if ($cart_item_id != 0)
		{
			$and .= " AND cart_item_id=" . (int) $cart_item_id . " ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id=" . (int) $parent_section_id . " ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_attribute_item "
			. "WHERE is_accessory_att=" . (int) $is_accessory . " "
			. "AND section=" . $db->quote($section) . " "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * Add GiftCard To Cart
	 *
	 * @param   array  $cartItem  Cart item
	 * @param   array  $data      User cart data
	 *
	 * @return  void
	 *
	 * @deprecated  2.1.0
	 *
	 * @see  RedshopHelperDiscount::addGiftCardToCart()
	 */
	public function addGiftCardToCart(&$cartItem, $data)
	{
		RedshopHelperDiscount::addGiftCardToCart($cartItem, $data);
	}

	/**
	 * Method for add product to cart
	 *
	 * @param   array  $data  Product data
	 *
	 * @return  mixed
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Cart\Cart::addProduct
	 */
	public function addProductToCart($data = array())
	{
		return Redshop\Cart\Cart::addProduct($data);
	}

	public function userfieldValidation($data, $data_add, $section = 12)
	{
		$returnArr    = $this->_producthelper->getProductUserfieldFromTemplate($data_add);
		$userfieldArr = $returnArr[1];

		$msg = "";

		if (count($userfieldArr) > 0)
		{
			$req_fields = RedshopHelperExtrafields::getSectionFieldList($section, 1, 1, 1);

			for ($i = 0, $in = count($req_fields); $i < $in; $i++)
			{
				if (in_array($req_fields[$i]->name, $userfieldArr))
				{
					if (!isset($data[$req_fields[$i]->name]) || (isset($data[$req_fields[$i]->name]) && $data[$req_fields[$i]->name] == ""))
					{
						$msg .= $req_fields[$i]->title . " " . JText::_('COM_REDSHOP_IS_REQUIRED') . "<br/>";
					}
				}
			}
		}

		return $msg;
	}

	/**
	 * @param   array  $data
	 * @param   int    $user_id
	 *
	 * @return  array|bool
	 *
	 * @throws  Exception
	 */
	public function generateAccessoryArray($data, $user_id = 0)
	{
		$generateAccessoryCart = array();

		if (!empty($data['accessory_data']))
		{
			$accessoryData    = explode("@@", $data['accessory_data']);
			$accQuantityData = array();

			if (isset($data['acc_quantity_data']))
			{
				$accQuantityData = explode("@@", $data['acc_quantity_data']);
			}

			for ($i = 0, $in = count($accessoryData); $i < $in; $i++)
			{
				$accessory          = RedshopHelperAccessory::getProductAccessories($accessoryData[$i]);
				$accessoryPriceList = \Redshop\Product\Accessory::getPrice(
					$data['product_id'], $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1, $user_id
				);
				$accessory_price    = $accessoryPriceList[0];
				$acc_quantity       = (isset($accQuantityData[$i]) && $accQuantityData[$i]) ? $accQuantityData[$i] : $data['quantity'];

				$generateAccessoryCart[$i]['accessory_id']       = $accessoryData[$i];
				$generateAccessoryCart[$i]['accessory_name']     = $accessory[0]->product_name;
				$generateAccessoryCart[$i]['accessory_oprand']   = $accessory[0]->oprand;
				$generateAccessoryCart[$i]['accessory_price']    = $accessory_price * $acc_quantity;
				$generateAccessoryCart[$i]['accessory_quantity'] = $acc_quantity;

				$accAttributeCart = array();

				if (!empty($data['acc_attribute_data']))
				{
					$acc_attribute_data = explode('@@', $data['acc_attribute_data']);

					if ($acc_attribute_data[$i] != "")
					{
						$acc_attribute_data = explode('##', $acc_attribute_data[$i]);
						$countAccessoryAttribute = count($acc_attribute_data);

						for ($ia = 0; $ia < $countAccessoryAttribute; $ia++)
						{
							$accPropertyCart                         = array();
							$attribute                               = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $acc_attribute_data[$ia]);
							$accAttributeCart[$ia]['attribute_id']   = $acc_attribute_data[$ia];
							$accAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

							if ($attribute[0]->text != "" && !empty($data['acc_property_data']))
							{
								$acc_property_data = explode('@@', $data['acc_property_data']);
								$acc_property_data = explode('##', $acc_property_data[$i]);

								if (empty($acc_property_data[$ia]) && $attribute[0]->attribute_required == 1)
								{
									return array();
								}

								if (!empty($acc_property_data[$ia]))
								{
									$acc_property_data = explode(',,', $acc_property_data[$ia]);
									$countAccessoryProperty = count($acc_property_data);

									for ($ip = 0; $ip < $countAccessoryProperty; $ip++)
									{
										$accSubpropertyCart = array();
										$property_price     = 0;
										$property           = RedshopHelperProduct_Attribute::getAttributeProperties($acc_property_data[$ip]);
										$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($acc_property_data[$ip], $data['quantity'], 'property', $user_id);

										if (count($pricelist) > 0)
										{
											$property_price = $pricelist->product_price;
										}
										else
										{
											$property_price = $property[0]->property_price;
										}

										$accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
										$accPropertyCart[$ip]['property_name']   = $property[0]->text;
										$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
										$accPropertyCart[$ip]['property_price']  = $property_price;

										if (!empty($data['acc_subproperty_data']))
										{
											$acc_subproperty_data = explode('@@', $data['acc_subproperty_data']);
											$acc_subproperty_data = @explode('##', $acc_subproperty_data[$i]);
											$acc_subproperty_data = @explode(',,', $acc_subproperty_data[$ia]);


											if (!empty($acc_subproperty_data[$ip]))
											{
												$acc_subproperty_data = explode('::', $acc_subproperty_data[$ip]);
												$countAccessorySubproperty = count($acc_subproperty_data);

												for ($isp = 0; $isp < $countAccessorySubproperty; $isp++)
												{
													$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($acc_subproperty_data[$isp]);
													$pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice($acc_subproperty_data[$isp], $data['quantity'], 'subproperty', $user_id);

													if (count($pricelist) > 0)
													{
														$subproperty_price = $pricelist->product_price;
													}
													else
													{
														$subproperty_price = $subproperty[0]->subattribute_color_price;
													}

													$accSubpropertyCart[$isp]['subproperty_id']     = $acc_subproperty_data[$isp];
													$accSubpropertyCart[$isp]['subproperty_name']   = $subproperty[0]->text;
													$accSubpropertyCart[$isp]['subproperty_oprand'] = $subproperty[0]->oprand;
													$accSubpropertyCart[$isp]['subproperty_price']  = $subproperty_price;
												}
											}
										}

										$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
									}
								}
							}

							$accAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
						}
					}
				}
				else
				{
					$attribute_set_id   = $this->getAttributeSetId($accessory[0]->child_product_id);
					$attributes_acc_set = array();

					if ($attribute_set_id > 0)
					{
						$attributes_acc_set = $this->getProductAccAttribute($accessory[0]->child_product_id, $attribute_set_id, 0, 0, 1);
					}

					$requireAttribute = RedshopHelperProduct_Attribute::getProductAttribute($accessory[0]->child_product_id, 0, 0, 0, 1);
					$requireAttribute = array_merge($requireAttribute, $attributes_acc_set);

					if (count($requireAttribute) > 0)
					{
						$requied_attributeArr = array();

						for ($re = 0, $countAttribute = count($requireAttribute); $re < $countAttribute; $re++)
						{
							$requied_attributeArr[$re] = urldecode($requireAttribute[$re]->attribute_name);
						}

						$requied_attribute_name = implode(", ", $requied_attributeArr);

						// Throw an error as first attribute is required
						$msg      = urldecode($requied_attribute_name) . " " . JText::_('IS_REQUIRED');
						JFactory::getApplication()->enqueueMessage($msg);

						return false;
					}
				}

				$generateAccessoryCart[$i]['accessory_childs'] = $accAttributeCart;
			}
		}

		return $generateAccessoryCart;
	}

	public function getProductAccAttribute($product_id = 0, $attribute_set_id = 0, $attribute_id = 0, $published = 0, $attribute_required = 0, $notAttributeId = 0)
	{
		$and          = "";
		$astpublished = "";

		if ($product_id != 0)
		{
			// Secure productsIds
			if ($productsIds = explode(',', $product_id))
			{
				$productsIds = Joomla\Utilities\ArrayHelper::toInteger($productsIds);

				$and .= "AND p.product_id IN (" . implode(',', $productsIds) . ") ";
			}
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id=" . (int) $attribute_set_id . " ";
		}

		if ($published != 0)
		{
			$astpublished = " AND ast.published=" . (int) $published . " ";
		}

		if ($attribute_required != 0)
		{
			$and .= "AND a.attribute_required=" . (int) $attribute_required . " ";
		}

		if ($notAttributeId != 0)
		{
			// Secure notAttributeId
			if ($notAttributeIds = explode(',', $notAttributeId))
			{
				$notAttributeIds = Joomla\Utilities\ArrayHelper::toInteger($notAttributeIds);

				$and .= "AND a.attribute_id NOT IN (" . implode(',', $notAttributeIds) . ") ";
			}
		}

		$query = "SELECT a.attribute_id AS value,a.attribute_name AS text,a.*,ast.attribute_set_name "
			. "FROM " . $this->_table_prefix . "product_attribute AS a "
			. "LEFT JOIN " . $this->_table_prefix . "attribute_set AS ast ON ast.attribute_set_id=a.attribute_set_id "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.attribute_set_id=a.attribute_set_id " . $astpublished
			. "WHERE a.attribute_name!='' "
			. $and
			. " and attribute_published=1 ORDER BY a.ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAttributeSetId($pid)
	{
		return RedshopEntityProduct::getInstance($pid)->get('attribute_set_id');
	}

	/**
	 * Method for generate attribute array
	 *
	 * @param   array    $data    Data of attributes
	 * @param   integer  $userId  ID of user
	 *
	 * @return  array
	 *
	 * @deprecated    2.1.0
	 * @see Redshop\Cart\Helper::generateAttribute
	 */
	public function generateAttributeArray($data, $userId = 0)
	{
		return Redshop\Cart\Helper::generateAttribute($data, $userId);
	}

	public function getSelectedCartAttributeArray($attArr = array())
	{
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
			{
				$selectedproperty[] = $propArr[$k]['property_id'];
				$subpropArr         = $propArr[$k]['property_childs'];

				for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
				{
					$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
				}
			}
		}

		$ret = array($selectedproperty, $selectedsubproperty);

		return $ret;
	}

	public function getSelectedCartAccessoryArray($attArr = array())
	{
		$selectedAccessory   = array();
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$selectedAccessory[] = $attArr[$i]['accessory_id'];
			$attchildArr         = $attArr[$i]['accessory_childs'];

			for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					$selectedproperty[] = $propArr[$k]['property_id'];
					$subpropArr         = $propArr[$k]['property_childs'];

					for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
					{
						$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
					}
				}
			}
		}

		$ret = array($selectedAccessory, $selectedproperty, $selectedsubproperty);

		return $ret;
	}

	/**
	 * @param   int  $order_item_id
	 * @param   int  $is_accessory
	 * @param   int  $parent_section_id
	 * @param   int  $quantity
	 *
	 * @return  array
	 */
	public function generateAttributeFromOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		$generateAttributeCart = array();

		$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
		{
			$accPropertyCart                             = array();
			$generateAttributeCart[$i]['attribute_id']   = $orderItemAttdata[$i]->section_id;
			$generateAttributeCart[$i]['attribute_name'] = $orderItemAttdata[$i]->section_name;

			$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id);

			for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
			{
				$accSubpropertyCart = array();
				$property           = RedshopHelperProduct_Attribute::getAttributeProperties($orderPropdata[$p]->section_id);
				$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($orderPropdata[$p]->section_id, $quantity, 'property');

				if (count($pricelist) > 0)
				{
					$property_price = $pricelist->product_price;
				}
				else
				{
					$property_price = $property[0]->property_price;
				}

				$accPropertyCart[$p]['property_id']     = $orderPropdata[$p]->section_id;
				$accPropertyCart[$p]['property_name']   = $property[0]->text;
				$accPropertyCart[$p]['property_oprand'] = $property[0]->oprand;
				$accPropertyCart[$p]['property_price']  = $property_price;

				$orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

				for ($sp = 0, $countSubproperty = count($orderSubpropdata); $sp < $countSubproperty; $sp++)
				{
					$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubpropdata[$sp]->section_id);
					$pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice($orderSubpropdata[$sp]->section_id, $quantity, 'subproperty');

					if (count($pricelist) > 0)
					{
						$subproperty_price = $pricelist->product_price;
					}
					else
					{
						$subproperty_price = $subproperty[0]->subattribute_color_price;
					}

					$accSubpropertyCart[$sp]['subproperty_id']     = $orderSubpropdata[$sp]->section_id;
					$accSubpropertyCart[$sp]['subproperty_name']   = $subproperty[0]->text;
					$accSubpropertyCart[$sp]['subproperty_oprand'] = $subproperty[0]->oprand;
					$accSubpropertyCart[$sp]['subproperty_price']  = $subproperty_price;
				}

				$accPropertyCart[$p]['property_childs'] = $accSubpropertyCart;
			}

			$generateAttributeCart[$i]['attribute_childs'] = $accPropertyCart;
		}

		return $generateAttributeCart;
	}

	public function generateAccessoryFromOrder($order_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$orderItemdata = RedshopHelperOrder::getOrderItemAccessoryDetail($order_item_id);

		foreach ($orderItemdata as $index => $orderItem)
		{
			$accessory          = RedshopHelperAccessory::getProductAccessories($orderItem->product_id);
			$accessorypricelist = \Redshop\Product\Accessory::getPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$index]['accessory_id']       = $orderItem->product_id;
			$generateAccessoryCart[$index]['accessory_name']     = $accessory[0]->product_name;
			$generateAccessoryCart[$index]['accessory_oprand']   = $accessory[0]->oprand;
			$generateAccessoryCart[$index]['accessory_price']    = $accessory_price;
			$generateAccessoryCart[$index]['accessory_quantity'] = $orderItem->product_quantity;
			$generateAccessoryCart[$index]['accessory_childs']   = $this->generateAttributeFromOrder($order_item_id, 1, $orderItem->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function discountCalculatorData($product_data, $data)
	{
		$use_discount_calculator = $product_data->use_discount_calc;
		$discount_calc_method    = $product_data->discount_calc_method;
		$use_range               = $product_data->use_range;
		$calc_output_array       = array();

		if ($use_discount_calculator)
		{
			$discount_cal = $this->discountCalculator($data);

			$calculator_price  = $discount_cal['product_price'];
			$product_price_tax = $discount_cal['product_price_tax'];

			$discountArr = array();
			if ($calculator_price)
			{
				$calc_output               = "Type : " . $discount_calc_method . "<br />";
				$calc_output_array['type'] = $discount_calc_method;

				if ($use_range)
				{
					$calcHeight        = @$data['calcHeight'];
					$calcWidth         = @$data['calcWidth'];
					$calcDepth         = @$data['calcDepth'];
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = "";
					$totalPiece        = "";
				}
				else
				{
					$calcHeight        = @$product_data->product_height;
					$calcWidth         = @$product_data->product_width;
					$calcDepth         = @$product_data->product_length;
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = @$discount_cal['price_per_piece'];
					$totalPiece        = @$discount_cal['total_piece'];
				}

				switch ($discount_calc_method)
				{
					case "volume":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_HEIGHT') . " " . $calcHeight . "<br />";
						$calc_output_array['calcHeight'] = $calcHeight;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_LENGTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "area":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_DEPTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "circumference":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_RADIUS') . " " . $calcRadius . "<br />";
						$calc_output_array['calcRadius'] = $calcRadius;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}
						break;
				}

				$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
				$calc_output_array['calcUnit'] = $data['calcUnit'];

				// Extra selected value data
				$calc_output .= "<br />" . $discount_cal['pdcextra_data'];

				// Extra selected value ids
				$calc_output_array['calcextra_ids'] = $discount_cal['pdcextra_ids'];

				$discountArr[] = $calc_output;
				$discountArr[] = $calc_output_array;
				$discountArr[] = $calculator_price;
				$discountArr[] = $product_price_tax;

				return $discountArr;
			}
			else
			{
				return array();
			}
		}
	}

	/**
	 * Discount calculator Ajax Function
	 *
	 * @param   array  $get
	 *
	 * @return  array
	 */
	public function discountCalculator($get)
	{
		$productId = (int) $get['product_id'];

		$discount_cal = array();

		$productPrice = RedshopHelperProductPrice::getNetPrice($productId);

		$product_price = $productPrice['product_price_novat'];

		$data = RedshopHelperProduct::getProductById($productId);

		// Default calculation method
		$calcMethod = $data->discount_calc_method;

		// Default calculation unit
		$globalUnit = "m";

		// Use range or not
		$use_range = $data->use_range;

		$calcHeight = $get['calcHeight'];
		$calcWidth  = $get['calcWidth'];
		$calcLength = $get['calcDepth'];
		$calcRadius = $get['calcRadius'];
		$calcUnit   = trim($get['calcUnit']);

		$calcHeight = str_replace(",", ".", $calcHeight);
		$calcWidth  = str_replace(",", ".", $calcWidth);
		$calcLength = str_replace(",", ".", $calcLength);
		$calcRadius = $cart_mdata = str_replace(",", ".", $calcRadius);
		$calcUnit   = $cart_mdata = str_replace(",", ".", $calcUnit);

		// Convert unit using helper function
		$unit = \Redshop\Helper\Utility::getUnitConversation($globalUnit, $calcUnit);

		$calcHeight *= $unit;
		$calcWidth *= $unit;
		$calcLength *= $unit;
		$calcRadius *= $unit;

		$product_unit = 1;

		if (!$use_range)
		{
			$product_unit = \Redshop\Helper\Utility::getUnitConversation($globalUnit, Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

			$product_height   = $data->product_height * $product_unit;
			$product_width    = $data->product_width * $product_unit;
			$product_length   = $data->product_length * $product_unit;
			$product_diameter = $data->product_diameter * $product_unit;
		}

		$Area      = 0;

		switch ($calcMethod)
		{
			case "volume":

				$Area = $calcHeight * $calcWidth * $calcLength;

				if (!$use_range)
					$product_area = $product_height * $product_width * $product_length;
				break;

			case "area":
				$Area = $calcLength * $calcWidth;

				if (!$use_range)
					$product_area = $product_length * $product_width;
				break;

			case "circumference":

				$Area = 2 * PI * $calcRadius;

				if (!$use_range)
					$product_area = PI * $product_diameter;
				break;
		}

		$finalArea = $Area;

		if ($use_range)
		{
			$finalArea = number_format($finalArea, 8, '.', '');

			// Calculation prices as per various area
			$discount_calc_data = $this->getDiscountCalcData($finalArea, $productId);

		}
		else
		{
			// Shandard size of product
			$final_product_Area = $product_area;

			// Total sheet calculation
			if ($final_product_Area <= 0)
				$final_product_Area = 1;
			$total_sheet = $finalArea / $final_product_Area;

			// Returns the next highest integer value by rounding up value if necessary.
			if (isset($data->allow_decimal_piece) && $data->allow_decimal_piece)
			{
				$total_sheet = ceil($total_sheet);
			}

			// If sheet is less than 0 or equal to 0 than
			if ($total_sheet <= 0)
				$total_sheet = 1;

			// Product price of all sheets
			$product_price_total = $total_sheet * $product_price;

			$discount_calc_data = array();
			$discount_calc_data[0] = new stdClass;

			// Generating array
			$discount_calc_data[0]->area_price         = $product_price;
			$discount_calc_data[0]->discount_calc_unit = $product_unit;
			$discount_calc_data[0]->price_per_piece    = $product_price_total;
		}

		$area_price       = 0;
		$pricePerPieceTax = 0;

		if (count($discount_calc_data))
		{
			$area_price = $discount_calc_data[0]->area_price;

			// Discount calculator extra price enhancement
			$pdcextraid = $get['pdcextraid'];
			$pdcstring  = $pdcids = array();

			if (trim($pdcextraid) != "")
			{
				$pdcextradatas = $this->getDiscountCalcDataExtra($pdcextraid);

				for ($pdc = 0, $countExtrafield = count($pdcextradatas); $pdc < $countExtrafield; $pdc++)
				{
					$pdcextradata = $pdcextradatas[$pdc];
					$option_name  = $pdcextradata->option_name;
					$pdcprice     = $pdcextradata->price;
					$pdcoprand    = $pdcextradata->oprand;
					$pdcextra_id  = $pdcextradata->pdcextra_id;

					$pdcstring[] = $option_name . ' (' . $pdcoprand . ' ' . $pdcprice . ' )';
					$pdcids[]    = $pdcextra_id;

					switch ($pdcoprand)
					{
						case "+":
							$area_price += $pdcprice;
							break;
						case "-":
							$area_price -= $pdcprice;
							break;
						case "%":
							$area_price *= 1 + ($pdcprice / 100);
							break;
					}
				}
			}

			// Applying TAX
			$chktag              = \Redshop\Template\Helper::isApplyAttributeVat();

			if ($use_range)
			{
				$display_final_area = $finalArea / ($unit * $unit);
				$price_per_piece = $area_price;

				$pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $price_per_piece, 0, 1);

				echo $display_final_area . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $pricePerPieceTax . "\n";
				echo $chktag . "\n";
			}
			else
			{
				$price_per_piece = $discount_calc_data[0]->price_per_piece;

				$pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $price_per_piece, 0, 1);

				echo $Area . "<br />" . JText::_('COM_REDSHOP_TOTAL_PIECE') . $total_sheet . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_OF_ALL_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $pricePerPieceTax . "\n";
				echo $chktag . "\n";
			}
		}
		else
		{
			$price_per_piece = false;
			echo "fail";
		}

		$discount_cal['product_price']     = $price_per_piece;
		$discount_cal['product_price_tax'] = $pricePerPieceTax;
		$discount_cal['pdcextra_data']     = "";

		if (isset($pdcstring) && count($pdcstring) > 0)
		{
			$discount_cal['pdcextra_data'] = implode("<br />", $pdcstring);
		}

		$discount_cal['pdcextra_ids']      = '';

		if (isset($pdcids) && (count($pdcids) > 0))
		{
			$discount_cal['pdcextra_ids'] = implode(",", $pdcids);
		}

		if (isset($total_sheet))
		{
			$discount_cal['total_piece']       = $total_sheet;
		}

		$discount_cal['price_per_piece']   = $area_price;

		return $discount_cal;
	}

	/**
	 * Funtion get Discount calculation data
	 *
	 * @param   number  $area         default value is 0
	 * @param   number  $pid          default value can be null
	 * @param   number  $areabetween  default value is 0
	 *
	 * @return object|mixed
	 */
	public function getDiscountCalcData($area = 0, $pid = 0, $areabetween = 0)
	{
		$query = $this->_db->getQuery(true)
			->select("*")
			->from($this->_db->quoteName("#__redshop_product_discount_calc"))
			->where($this->_db->quoteName("product_id") . "=" . (int) $pid)
			->order("id ASC");

		if ($areabetween)
		{
			$query->where((floatval($area)) . " BETWEEN `area_start` AND `area_end` ");
		}

		if ($area)
		{
			$query->where($this->_db->quoteName("area_start_converted") . "<=" . floatval($area))
				->where($this->_db->quoteName("area_end_converted") . ">=" . floatval($area));
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * @param   string  $pdcextraids
	 * @param   int     $productId
	 *
	 * @return  mixed
	 */
	public function getDiscountCalcDataExtra($pdcextraids = "", $productId = 0)
	{
		return RedshopHelperCartDiscount::getDiscountCalcDataExtra($pdcextraids, $productId);
	}

	/**
	 * Handle required attribute before add in to cart messages
	 *
	 * @param   array   $data                  cart data
	 * @param   string  $attributeTemplate     Attribute added data
	 * @param   array   $selectedAttrId        Selected attribute id for add to cart
	 * @param   array   $selectedPropId        Selected Property Id for Add to cart
	 * @param   array   $notselectedSubpropId  Not selected subproperty ids during add to cart
	 *
	 * @return  string  Error Message if found otherwise return null.
	 * @throws  Exception
	 */
	public function handleRequiredSelectedAttributeCartMessage($data, $attributeTemplate, $selectedAttrId, $selectedPropId, $notselectedSubpropId)
	{
		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			return;
		}

		// Check if required attribute is filled or not ...
		$attributeTemplateArray = \Redshop\Template\Helper::getAttribute($attributeTemplate);

		if (!empty($attributeTemplateArray))
		{
			$selectedAttributId = 0;

			if (count($selectedAttrId) > 0)
			{
				$selectedAttributId = implode(",", $selectedAttrId);
			}

			$requiredAttribute = RedshopHelperProduct_Attribute::getProductAttribute(
								$data['product_id'],
								0,
								0,
								0,
								1,
								$selectedAttributId
							);

			if (!empty($requiredAttribute))
			{
				$requiredAttributeArray = array();

				for ($re = 0, $countAttribute = count($requiredAttribute); $re < $countAttribute; $re++)
				{
					$requiredAttributeArray[$re] = urldecode($requiredAttribute[$re]->attribute_name);
				}

				$requiredAttributeName = implode(", ", $requiredAttributeArray);

				// Error message if first attribute is required
				return $requiredAttributeName . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
			}

			$selectedPropertyId = 0;

			if (!empty($selectedPropId))
			{
				$selectedPropertyId = implode(",", $selectedPropId);
			}

			$notselectedSubpropertyId = 0;

			if (count($notselectedSubpropId) > 0)
			{
				$notselectedSubpropertyId = implode(",", $notselectedSubpropId);
			}

			$requiredProperty = RedshopHelperProduct_Attribute::getAttributeProperties(
								/** @scrutinizer ignore-type */ $selectedPropertyId,
								/** @scrutinizer ignore-type */ $selectedAttributId,
								$data['product_id'],
								0,
								1,
								/** @scrutinizer ignore-type */ $notselectedSubpropertyId
							);

			if (!empty($requiredProperty))
			{
				$requiredSubAttributeArray = array();

				for ($re1 = 0, $countProperty = count($requiredProperty); $re1 < $countProperty; $re1++)
				{
					$requiredSubAttributeArray[$re1] = urldecode($requiredProperty[$re1]->property_name);
				}

				$requiredSubAttributeName = implode(",", $requiredSubAttributeArray);

				// Give error as second attribute is required
				if ($data['reorder'] != 1)
				{
					return $requiredSubAttributeName . " " . JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED');
				}
			}
		}

		return;
	}
}
