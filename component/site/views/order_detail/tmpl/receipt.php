<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


$carthelper      = rsCarthelper::getInstance();
$redconfig       = Redconfiguration::getInstance();
$configobj       = Redconfiguration::getInstance();
$redTemplate     = Redtemplate::getInstance();
$producthelper   = productHelper::getInstance();
$order_functions = order_functions::getInstance();

$db       = JFactory::getDbo();
$url      = JURI::base();
$Itemid = RedshopHelperUtility::getCheckoutItemId();
$order_id = $app->input->getInt('oid');

// For barcode
$model = $this->getModel('order_detail');

$order = $this->OrdersDetail;
?>
<?php
if ($this->params->get('show_page_title', 1))
{
?>
	<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape(JText::_('COM_REDSHOP_ORDER_RECEIPT')); ?>
	</h1>
<?php
}

if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
{
	echo JLayoutHelper::render('cart.wizard', array('step' => '3'));
}

if (Redshop::getConfig()->get('USE_AS_CATALOG'))
{
	$ReceiptTemplate = $redTemplate->getTemplate("catalogue_order_receipt");
	$ReceiptTemplate = $ReceiptTemplate[0]->template_desc;
}
else
{
	$ReceiptTemplate = $redTemplate->getTemplate("order_receipt");

	if (count($ReceiptTemplate) > 0 && $ReceiptTemplate[0]->template_desc)
	{
		$ReceiptTemplate = $ReceiptTemplate[0]->template_desc;
	}
	else
	{
		$ReceiptTemplate = '<div class="product_print">{print}</div><table class="tdborder" style="width: 100%;" border="0" cellspacing="0" cellpadding="5"><tbody><tr><th>{product_name_lbl}</th> <th> </th> <th>{price_lbl}</th> <th>{quantity_lbl}</th> <th>{total_price_lbl}</th></tr>{product_loop_start}<tr><td>{product_name}<br />{product_attribute}{product_accessory}{product_userfields}{product_wrapper}</td><td>{product_thumb_image}</td><td>{product_price}</td><td>{product_quantity}</td><td>{product_total_price}</td></tr>{product_loop_end}</tbody></table><p><br /><br /></p><table class="cart_calculations" border="1"><tbody><tr class="tdborder"><td><b>Product Subtotal:</b></td><td width="100">{product_subtotal}</td><td><b>Product Subtotal excl vat:</b></td><td width="100">{product_subtotal_excl_vat}</td></tr><tr><td><b>Shipping with vat:</b></td><td width="100">{shipping}</td><td><b>Shipping excl vat:</b></td><td width="100">{shipping_excl_vat}</td></tr>{if discount}<tr class="tdborder"><td>{discount_lbl}</td><td width="100">{discount}</td><td>{discount_lbl}</td><td width="100">{discount_excl_vat}</td></tr>{discount end if}<tr><td><b>{totalpurchase_lbl}:</b></td><td width="100">{order_subtotal}</td><td><b>{subtotal_excl_vat_lbl} :</b></td><td width="100">{order_subtotal_excl_vat}</td></tr>{if vat}<tr class="tdborder"><td>{vat_lbl}</td><td width="100">{tax}</td><td>{vat_lbl}</td><td width="100">{sub_total_vat}</td></tr>{vat end if}   {if payment_discount}<tr><td>{payment_discount_lbl}</td><td width="100">{payment_order_discount}</td></tr>{payment_discount end if}<tr class="tdborder"><td><b>{shipping_lbl}</b></td><td width="100">{shipping}</td><td><b>{shipping_lbl}</b></td><td width="100">{shipping_excl_vat}</td></tr><tr><td><div class="singleline"><strong>{total_lbl}:</strong></div></td><td width="100"><div class="singleline">{order_total}</div></td><td><div class="singleline"><b>{total_lbl}:</b></div></td><td width="100"><div class="singleline">{total_excl_vat}</div></td></tr><tr><td colspan="4"><p>{shipping_method_lbl} <strong>{shipping_method}</strong></p><p>{payment_status}</p></td></tr><tr><td colspan="4">{billing_address}</td></tr><tr><td colspan="4">{shipping_address}</td></tr></tbody></table>';
	}
}

$orderitem = $order_functions->getOrderItemDetail($order_id);

// Replace Reorder Button
$this->replaceReorderButton($ReceiptTemplate);

$print = $app->input->getInt('print');

if ($print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=order_detail&layout=receipt&oid=" . $order_id . "&print=1&tmpl=component&Itemid=" . $Itemid;
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";
$ReceiptTemplate = str_replace("{print}", $print_tag, $ReceiptTemplate);

$ReceiptTemplate = str_replace("{product_name_lbl}", JText::_('COM_REDSHOP_PRODUCT_NAME_LBL'), $ReceiptTemplate);
$ReceiptTemplate = str_replace("{price_lbl}", JText::_('COM_REDSHOP_PRICE_LBL'), $ReceiptTemplate);
$ReceiptTemplate = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $ReceiptTemplate);
$ReceiptTemplate = str_replace("{total_price_lbl}", JText::_('COM_REDSHOP_TOTAL_PRICE_LBL'), $ReceiptTemplate);
$ReceiptTemplate = $carthelper->replaceOrderTemplate($order, $ReceiptTemplate);

// Added new tag
/**
 * The Tag {txtextra_info} to display some extra information about payment method ( Only For display purpose ).
 *
 * Output is fatched from Payment Gateway Plugin Parameter 'txtextra_info'
 */
$order_payment = $order_functions->getOrderPaymentDetail($order_id);

$payment_method_class = $order_payment[0]->payment_method_class;

JLoader::import('joomla.plugin.helper');

$plugin = JPluginHelper::getPlugin('redshop_payment', $payment_method_class);
$params = new JRegistry($plugin->params);

$txtextra_info = $params->get('txtextra_info');

$ReceiptTemplate = str_replace("{txtextra_info}", $txtextra_info, $ReceiptTemplate);

// End

$ReceiptTemplate = $redTemplate->parseredSHOPplugin($ReceiptTemplate);

/**
 *
 * trigger content plugin
 */
$dispatcher = RedshopHelperUtility::getDispatcher();
$o          = new stdClass;
$o->text    = $ReceiptTemplate;
JPluginHelper::importPlugin('content');
$x               = array();
$results         = $dispatcher->trigger('onPrepareContent', array(&$o, &$x, 0));
$ReceiptTemplate = $o->text;

$dispatcher->trigger('onRenderReceipt', array(&$ReceiptTemplate, $order_id));

// End

echo eval("?>" . $ReceiptTemplate . "<?php ");

// Handle order total for split payment
$session = JFactory::getSession();
$issplit = $session->get('issplit');

if ($issplit)
{
	$split_amount       = ($order->order_total) / 2;
	$order->order_total = $split_amount;
}

// End

$billingaddresses = $model->billingaddresses();

if ($order->analytics_status == 0)
{
	$model->UpdateAnalytics_status($order->order_id);
}
