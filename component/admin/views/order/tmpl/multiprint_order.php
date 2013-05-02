<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper();
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';

$order_functions = new order_functions();
$shippinghelper = new shipping();
$carthelper = new rsCarthelper();
$mypost = JRequest::getVar('cid');

$mysplit = preg_split(",", $mypost);
$mycnt = '';
for ($k = 0; $k < count($mysplit); $k++)
{
	$replace = '';

	if ($mysplit[$k] != '')
	{
		$mycnt = count($mysplit);


//exit;
//echo $mypost;exit;


		$option = JRequest::getVar('option');
		$config = new Redconfiguration();
		$model = $this->getModel('order_detail');

		$extra_field = new extra_field();


		$uri = JURI::getInstance();
		$url = $uri->root();
		$redTemplate = new Redtemplate();

//$model = $this->getModel ( 'order_detail' );

		$OrderProducts = $order_functions->getOrderItemDetail($mysplit[$k]);
		$OrdersDetail = $order_functions->getmultiOrderDetails($mysplit[$k]);

		$billing = $order_functions->getOrderBillingUserInfo($OrdersDetail[0]->order_id);
		$shipping = $order_functions->getOrderShippingUserInfo($OrdersDetail[0]->order_id);

		$is_company = $billing->is_company;
		if (!$shipping)
		{

			$shipping = $billing;
		}


//$partialpayment = $order_functions->getOrderPartialPayment ( $OrdersDetail->order_id );
//// get order Payment method information
		$paymentmethod = $order_functions->getOrderPaymentDetail($mysplit[$k]);
		$paymentmethod = $paymentmethod[0];

		$order_print_template = $redTemplate->getTemplate("order_print");
		if (count($order_print_template) > 0 && $order_print_template[0]->template_desc != "")
		{
			$ordersprint_template = $order_print_template[0]->template_desc;
		}
		else
		{
			$ordersprint_template = '<table style="width: 100%;" border="0" cellpadding="5" cellspacing="0"><tbody><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;"><th align="left">{order_information_lbl}{print}</th></tr><tr></tr><tr><td>{order_id_lbl} : {order_id}</td></tr><tr><td>{order_number_lbl} : {order_number}</td></tr><tr><td>{order_date_lbl} : {order_date}</td></tr><tr><td>{order_status_lbl} : {order_status}</td></tr><tr><td>{shipping_method_lbl} : {shipping_method} : {shipping_rate_name}</td></tr><tr><td>{payment_lbl} : {payment_method}</td></tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;"><th align="left">{billing_address_information_lbl}</th></tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;"><th align="left">{shipping_address_info_lbl}</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;"><th align="left">{order_detail_lbl}</th></tr><tr></tr><tr><td><table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody><tr><td>{product_name_lbl}</td><td>{note_lbl}</td><td>{price_lbl}</td><td>{quantity_lbl}</td><td align="right">Total Price</td></tr>{product_loop_start}<tr><td><p>{product_name}<br />{product_attribute}{product_accessory}{product_userfields}</p></td><td>{product_note}{product_thumb_image}</td><td>{product_price}</td><td>{product_quantity}</td><td align="right">{product_total_price}</td></tr>{product_loop_end}</tbody></table></td></tr><tr><td></td></tr><tr><td><table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody><tr align="left"><td align="left"><strong>{order_subtotal_lbl} : </strong></td><td align="right">{order_subtotal}</td></tr>{if vat}<tr align="left"><td align="left"><strong>{vat_lbl} : </strong></td><td align="right">{order_tax}</td></tr>{vat end if}{if discount}<tr align="left"><td align="left"><strong>{discount_lbl} : </strong></td><td align="right">{order_discount}</td></tr>{discount end if}<tr align="left"><td align="left"><strong>{shipping_lbl} : </strong></td><td align="right">{order_shipping}</td></tr><tr align="left"><td colspan="2" align="left"><hr /></td></tr><tr align="left"><td align="left"><strong>{total_lbl} :</strong></td><td align="right">{order_total}</td></tr><tr align="left"><td colspan="2" align="left"><hr /><br /> <hr /></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>';
		}

		$print_tag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
			. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='" . JText::_('COM_REDSHOP_PRINT') . "' /></a>";

		$search[] = "{print}";
		$replace[] = $print_tag;

		$search[] = "{order_id}";
		$replace[] = $OrdersDetail[0]->order_id;

		$search[] = "{order_number}";
		$replace[] = $OrdersDetail[0]->order_number;

		$search[] = "{order_date}";
		$replace[] = $config->convertDateFormat($OrdersDetail[0]->cdate);

		$search[] = "{customer_note}";
		$replace[] = $OrdersDetail[0]->customer_note;


// set order paymethod name
		$search[] = "{payment_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_PAYMENT_METHOD');

		$search[] = "{payment_method}";
		$replace[] = $paymentmethod->order_payment_name;

		$statustext = $order_functions->getOrderStatusTitle($OrdersDetail[0]->order_status);

		$issplit = $OrdersDetail[0]->split_payment;

		$search[] = "{order_status}";
		if (trim($OrdersDetail[0]->order_payment_status) == 'Paid')
		{
			$orderPaymentStatus = JText::_('PAYMENT_STA_PAID');
		}
		else if (trim($OrdersDetail[0]->order_payment_status) == 'Unpaid')
		{
			$orderPaymentStatus = JText::_('PAYMENT_STA_UNPAID');
		}
		else if (trim($OrdersDetail[0]->order_payment_status) == 'Partial Paid')
		{
			$orderPaymentStatus = JText::_('PAYMENT_STA_PARTIAL_PAID');
		}
		else
		{
			$orderPaymentStatus = $OrdersDetail[0]->order_payment_status;
		}

		$replace[] = $statustext . " - " . $orderPaymentStatus;

		$search [] = "{order_status_order_only}";
		$replace [] = $statustext;


		$search [] = "{order_status_payment_only}";
		$replace [] = $orderPaymentStatus;

		$search[] = "{customer_note_lbl}";
		$replace[] = JText::_('COM_REDSHOP_COMMENT');
		$search[] = "{customer_note}";
		$replace[] = $OrdersDetail->customer_note;
		$search[] = "{shipping_method_lbl}";
		$replace[] = JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL');


		$shipping_method = '';
		$shipping_rate_name = '';
		if ($OrdersDetail[0]->ship_method_id != '')
		{
			$ship_method = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $OrdersDetail[0]->ship_method_id)));
			if (count($ship_method) <= 1)
			{
				$ship_method = explode("|", $OrdersDetail[0]->ship_method_id);
			}
			$shipping_method = "";
			$shipping_rate_name = "";
			if (count($ship_method) > 0)
			{
				if (array_key_exists(1, $ship_method))
				{
					$shipping_method = $ship_method[1];
				}
				if (array_key_exists(2, $ship_method))
				{
					$shipping_rate_name = $ship_method[2];
				}
			}

		}

		$search[] = "{shipping_method}";
		$replace[] = $shipping_method;
		$search[] = "{shipping}";
		$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_shipping);
		$search[] = "{shipping_rate_name}";
		$replace[] = $shipping_rate_name;

		$ordersprint_template = $carthelper->replaceBillingAddress($ordersprint_template, $billing);
		$ordersprint_template = $carthelper->replaceShippingAddress($ordersprint_template, $shipping);

		$product_name = "";
		$product_note = "";
		$product_price = "";
		$product_quantity = "";
		$product_total_price = "";

		$template_start = "";
		$template_middle = "";
		$template_end = "";
		if (strstr($ordersprint_template, "{product_loop_start}"))
		{
			$template_sdata = explode('{product_loop_start}', $ordersprint_template);
			$template_start = $template_sdata [0];
			$template_edata = explode('{product_loop_end}', $template_sdata [1]);
			$template_end = $template_edata [1];
			$template_middle = $template_edata [0];
		}
		$cart_tr = '';


		for ($i = 0; $i < count($OrderProducts); $i++)
		{

			$wrapper_name = "";
			if ($OrderProducts[$i]->wrapper_id)
			{
				$wrapper = $producthelper->getWrapper($OrderProducts[$i]->product_id, $OrderProducts[$i]->wrapper_id);
				if (count($wrapper) > 0)
				{
					$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ":<br/>" . $wrapper[0]->wrapper_name . "(" . $producthelper->getProductFormattedPrice($OrderProducts[$i]->wrapper_price) . ")";
				}
			}
			if ($OrderProducts [$i]->is_giftcard == 1)
			{

				$product_userfields = $producthelper->getuserfield($OrderProducts [$i]->order_item_id, 13);

			}
			else
			{

				$product_userfields = $producthelper->getuserfield($OrderProducts [$i]->order_item_id);

			}

			$product_name = "<div  class='product_name'>" . $OrderProducts [$i]->order_item_name . "</div>";

			$product = $producthelper->getProductById($OrderProducts [$i]->product_id);

			$product_number = $OrderProducts [$i]->order_item_sku;

			$product_note = "<div  class='product_note'>" . $wrapper_name . "</div>";

			$product_total_price = "<div class='product_total_price'>" . $producthelper->getProductFormattedPrice($OrderProducts [$i]->product_final_price) . "</div>";

			$product_price = "<div class='product_price'>" . $producthelper->getProductFormattedPrice($OrderProducts [$i]->product_item_price) . "</div>";

			$product_quantity = '<div class="product_quantity">' . $OrderProducts [$i]->product_quantity . '</div>';

			$cart_mdata = '';

			if ($product->product_full_image)
			{
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
					$product_image_path = $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_full_image;
				else
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
						$product_image_path = $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . PRODUCT_DEFAULT_IMAGE;
					else
						$product_image_path = "";
				}

			}
			else
			{
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
					$product_image_path = $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . PRODUCT_DEFAULT_IMAGE;
				else
					$product_image_path = "";
			}

			if ($product_image_path)
				$product_image = "<div  class='product_image'><img src='" . $product_image_path . "&newxsize=" . CART_THUMB_WIDTH . "&newysize=" . CART_THUMB_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "'></div>";
			else
				$product_image = "<div  class='product_image'></div>";

			$cart_mdata = str_replace("{product_name}", $product_name, $template_middle);
			$cart_mdata = str_replace("{product_thumb_image}", $product_image, $cart_mdata);
			$cart_mdata = str_replace("{product_attribute}", $OrderProducts [$i]->product_attribute, $cart_mdata);
			$cart_mdata = str_replace("{product_accessory}", $OrderProducts [$i]->product_accessory, $cart_mdata);
			$cart_mdata = str_replace("{product_wrapper}", '', $cart_mdata);

			$cart_mdata = str_replace("{product_number}", $product_number, $cart_mdata);
			$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cart_mdata);

			$user_subscribe_detail = $producthelper->getUserProductSubscriptionDetail($OrderProducts[$i]->order_item_id);
			if (count($user_subscribe_detail) > 0 && $user_subscribe_detail->subscription_id)
			{
				$subscription_detail = $producthelper->getProductSubscriptionDetail($OrderProducts [$i]->product_id, $user_subscribe_detail->subscription_id);
				$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;

				$cart_mdata = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cart_mdata);
				$cart_mdata = str_replace("{product_subscription}", $selected_subscription, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{product_subscription_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{product_subscription}", "", $cart_mdata);
			}


			$cart_mdata = str_replace("{product_userfields}", $product_userfields, $cart_mdata);

			$cart_mdata = str_replace("{product_note}", $product_note, $cart_mdata);

			$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);

			$cart_mdata = str_replace("{product_quantity}", $product_quantity, $cart_mdata);

			$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);

			$cart_tr .= $cart_mdata;

		}

		$ordersprint_template = $template_start . $cart_tr . $template_end;

		$search[] = "{order_subtotal}";
		$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_subtotal);


		if ($OrdersDetail[0]->order_tax <= 0)
		{
			$template_vat_sdata = explode('{if vat}', $ordersprint_template);
			$template_vat_start = $template_vat_sdata[0];
			$template_vat_edata = explode('{vat end if}', $template_vat_sdata[1]);
			$template_vat_end = $template_vat_edata[1];
			$template_vat_middle = $template_vat_edata[0];
			$ordersprint_template = $template_vat_start . $template_vat_end;
		}
		else
		{
			$search[] = "{if vat}";
			$replace[] = '';
			$search[] = "{order_tax}";
			$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_tax);
			$search[] = "{tax}";
			$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_tax);
			$search[] = "{vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_TAX');
			$search[] = "{vat end if}";
			$replace[] = '';

		}

		if ($OrdersDetail[0]->payment_discount <= 0)
		{
			if (strstr($ordersprint_template, "{if payment_discount}"))
			{
				$template_pd_sdata = explode('{if payment_discount}', $ordersprint_template);
				$template_pd_start = $template_pd_sdata[0];
				$template_pd_edata = explode('{payment_discount end if}', $template_pd_sdata[1]);
				$template_pd_end = $template_pd_edata[1];
				$template_pd_middle = $template_pd_edata[0];
				$ordersprint_template = $template_pd_start . $template_pd_end;
			}
		}
		else
		{
			$OrdersDetail->order_discount = $OrdersDetail[0]->order_discount - $OrdersDetail[0]->payment_discount;
			$search[] = "{if payment_discount}";
			$replace[] = '';
			$search[] = "{payment_order_discount}";
			$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->payment_discount);
			$search[] = "{payment_discount_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
			$search[] = "{payment_discount end if}";
			$replace[] = '';

		}

		if ($OrdersDetail->order_discount <= 0)
		{
			if (strstr($ordersprint_template, "{if discount}"))
			{
				$template_discount_sdata = explode('{if discount}', $ordersprint_template);
				$template_discount_start = $template_discount_sdata[0];
				$template_discount_edata = explode('{discount end if}', $template_discount_sdata[1]);
				$template_discount_end = $template_discount_edata[1];
				$template_discount_middle = $template_discount_edata[0];
				$ordersprint_template = $template_discount_start . $template_discount_end;
			}
		}
		else
		{
			$search[] = "{if discount}";
			$replace[] = '';
			$search[] = "{order_discount}";
			$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_discount);
			$search[] = "{discount_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DISCOUNT');
			$search[] = "{discount end if}";
			$replace[] = '';
		}


		$search[] = "{order_id_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_ID');

		$search[] = "{order_number_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_NUMBER');

		$search[] = "{order_date_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_DATE');

		$search[] = "{order_status_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_STATUS');

		$search[] = "{shipping_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_SHIPPING');

		$search[] = "{order_information_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_INFORMATION');

		$search[] = "{billing_address_information_lbl}";
		$replace[] = JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION');

		$search[] = "{shipping_address_info_lbl}";
		$replace[] = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION');

		$search[] = "{order_detail_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_DETAILS');

		$search[] = "{product_name_lbl}";
		$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME');

		$search[] = "{note_lbl}";
		$replace[] = JText::_('COM_REDSHOP_NOTE');

		$search[] = "{price_lbl}";
		$replace[] = JText::_('COM_REDSHOP_PRICE');

		$search[] = "{quantity_lbl}";
		$replace[] = JText::_('COM_REDSHOP_QUANTITY');

		$search[] = "{total_price_lbl}";
		$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE');


		$search[] = "{order_subtotal_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_SUBTOTAL');

		$search[] = "{product_number_lbl}";
		$replace[] = JText::_('COM_REDSHOP_PRODUCT_NUMBER');

		$search[] = "{total_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_TOTAL');

		$search[] = "{order_shipping}";
		$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_shipping);

		$search[] = "{shipping}";
		$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_shipping);

		$search[] = "{order_total}";
		$replace[] = $producthelper->getProductFormattedPrice($OrdersDetail[0]->order_total);


		$message = str_replace($search, $replace, $ordersprint_template);

		echo eval("?>" . $message . "<?php ");?>
	<?
	}
}
?>

