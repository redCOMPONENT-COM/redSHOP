<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$uri         = JURI::getInstance();
$url         = JURI::base();
$redconfig   = Redconfiguration::getInstance();
$extra_field = extra_field::getInstance();

$app             = JFactory::getApplication();
$producthelper   = productHelper::getInstance();
$redhelper       = redhelper::getInstance();
$order_functions = order_functions::getInstance();
$redTemplate     = Redtemplate::getInstance();
$shippinghelper  = shipping::getInstance();
$carthelper      = rsCarthelper::getInstance();

$Itemid = $app->input->getInt('Itemid');
$oid    = $app->input->getInt('oid');
$print  = $app->input->getInt('print');

$getshm    = $uri->getScheme();
$config    = JFactory::getConfig();
$force_ssl = $config->get('force_ssl');

if ($getshm == 'https' && $force_ssl > 2)
{
	$uri->setScheme('http');
}

if ($this->params->get('show_page_heading', 1)) : ?>
	<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape(JText::_('COM_REDSHOP_ORDER_DETAILS')); ?>
	</h1>
<?php endif; ?>
<?php
$model          = $this->getModel('order_detail');
$OrdersDetail   = $this->OrdersDetail;
$OrderProducts  = $order_functions->getOrderItemDetail($oid);
$partialpayment = $order_functions->getOrderPartialPayment($oid);

// Get order Payment method information

if (Redshop::getConfig()->get('USE_AS_CATALOG'))
{
	$orderslist_template = $redTemplate->getTemplate("catalogue_order_detail");
	$orderslist_template = $orderslist_template[0]->template_desc;
}
else
{
	$orderslist_template = $redTemplate->getTemplate("order_detail");

	if (count($orderslist_template) > 0 && $orderslist_template[0]->template_desc)
	{
		$orderslist_template = $orderslist_template[0]->template_desc;
	}
	else
	{
		$orderslist_template = '<div class="product_print">{print}</div><table style="width: 100%;" border="0" cellspacing="0" cellpadding="5"><tbody><tr><td colspan="2"><table style="width: 100%;" border="0" cellspacing="0" cellpadding="2"><tbody><tr style="background-color: #cccccc"><th align="left">{discount_type_lbl}</th></tr><tr><td>{discount_type}</td></tr><tr style="background-color: #cccccc;"><th align="left">{order_information_lbl}</th></tr><tr></tr><tr><td>{order_id_lbl} : {order_id}</td></tr><tr><td>{order_number_lbl} : {order_number}</td></tr><tr><td>{order_date_lbl} : {order_date}</td></tr><tr><td>{order_status_lbl} : {order_status}</td></tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellspacing="0" cellpadding="2"><tbody><tr style="background-color: #cccccc;"><th align="left">{billing_address_information_lbl}</th></tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellspacing="0" cellpadding="2"><tbody><tr style="background-color: #cccccc;"><th align="left">{shipping_address_information_lbl}</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellspacing="0" cellpadding="2"><tbody><tr style="background-color: #cccccc;"><th align="left">{order_detail_lbl}</th></tr><tr></tr><tr><td><table style="width: 100%;" border="0" cellspacing="2" cellpadding="2"><tbody><tr><td>{copy_orderitem_lbl}</td><td>{product_name_lbl}</td><td>{note_lbl}</td><td>{price_lbl}</td><td>{quantity_lbl}</td><td align="right">{total_price_lbl}</td></tr>{product_loop_start}<tr><td>{copy_orderitem}</td><td>{product_name}<br />{product_attribute}{product_accessory}{product_userfields}</td><td>{product_wrapper}</td><td>{product_price}</td><td>{product_quantity}</td><td align="right">{product_total_price}</td></tr>{product_loop_end}</tbody></table></td></tr><tr><td>{customer_note_lbl}: {customer_note}</td></tr><tr><td>{requisition_number_lbl}: {requisition_number}</td></tr><tr><td><table class="cart_calculations" border="1"><tbody><tr class="tdborder"><td><b>Product Subtotal:</b></td><td width="100">{product_subtotal}</td><td><b>Product Subtotal excl vat:</b></td><td width="100">{product_subtotal_excl_vat}</td></tr><tr><td><b>Shipping with vat:</b></td><td width="100">{shipping}</td><td><b>Shipping excl vat:</b></td><td width="100">{shipping_excl_vat}</td></tr>{if discount}<tr class="tdborder"><td>{discount_lbl}</td><td width="100">{discount}</td><td>{discount_lbl}</td><td width="100">{discount_excl_vat}</td></tr>{discount end if}<tr><td><b>{totalpurchase_lbl}:</b></td><td width="100">{order_subtotal}</td><td><b>{subtotal_excl_vat_lbl} :</b></td><td width="100">{order_subtotal_excl_vat}</td></tr>{if vat}<tr class="tdborder"><td>{vat_lbl}</td><td width="100">{tax}</td><td>{vat_lbl}</td><td width="100">{sub_total_vat}</td></tr>{vat end if}   {if payment_discount}<tr><td>{payment_discount_lbl}</td><td width="100">{payment_order_discount}</td></tr>{payment_discount end if}<tr class="tdborder"><td><b>{tax_with_shipping_lbl}</b></td><td width="100">{shipping}</td><td><b>{shipping_lbl}</b></td><td width="100">{shipping_excl_vat}</td></tr><tr><td><div class="singleline"><strong>{total_lbl}:</strong></div></td><td width="100"><div class="singleline">{order_total}</div></td><td><div class="singleline"><b>{total_lbl}:</b></div></td><td width="100"><div class="singleline">{total_excl_vat}</div></td></tr></tbody></table></td></tr><tr><td align="left">{reorder_button}</td></tr></tbody></table></td></tr></tbody></table>';
	}
}

// Replace Reorder Button
$this->replaceReorderButton($orderslist_template);

if ($print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=order_detail&oid=" . $oid . "&print=1&tmpl=component&Itemid=" . $Itemid . "&encr=" . $app->input->getCmd('encr', '');
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

$orderslist_template = str_replace("{print}", $print_tag, $orderslist_template);

$arr_discount_type = array();
$arr_discount      = explode('@', $OrdersDetail->discount_type);
$discount_type     = '';

for ($d = 0, $dn = count($arr_discount); $d < $dn; $d++)
{
	if ($arr_discount[$d])
	{
		$arr_discount_type = explode(':', $arr_discount[$d]);

		if ($arr_discount_type[0] == 'c')
			$discount_type .= JText::_('COM_REDSHOP_COUPEN_CODE') . ' : ' . $arr_discount_type[1] . '<br>';

		if ($arr_discount_type[0] == 'v')
			$discount_type .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
	}
}

$search[]  = "{discount_type_lbl}";
$replace[] = JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL');

if ($discount_type)
{
	$search[]  = "{discount_type}";
	$replace[] = $discount_type;
}
else
{
	$search[]  = "{discount_type}";
	$replace[] = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
}

$statustext = $order_functions->getOrderStatusTitle($OrdersDetail->order_status);

$search [] = "{order_status}";

if (trim($OrdersDetail->order_payment_status) == 'Paid')
{
	$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PAID');
}
elseif (trim($OrdersDetail->order_payment_status) == 'Unpaid')
{
	$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID');
}
elseif (trim($OrdersDetail->order_payment_status) == 'Partial Paid')
{
	$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID');
}
else
{
	$orderPaymentStatus = $OrdersDetail->order_payment_status;
}

$replace[] = $statustext . " - " . $orderPaymentStatus;

if (strstr($orderslist_template, "{order_status_order_only}"))
{
	$search []  = "{order_status_order_only}";
	$replace [] = $statustext;
}

if (strstr($orderslist_template, "{order_status_payment_only}"))
{
	$search []  = "{order_status_payment_only}";
	$replace [] = $orderPaymentStatus;
}

$message = str_replace($search, $replace, $orderslist_template);

$message = $redTemplate->parseredSHOPplugin($message);
$message = $carthelper->replaceOrderTemplate($OrdersDetail, $message);
echo eval("?>" . $message . "<?php ");
