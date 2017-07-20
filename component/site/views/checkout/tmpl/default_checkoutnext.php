<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

?>
<script type="text/javascript">
	function checkout_disable(val) {
		document.adminForm.submit();
		document.getElementById(val).disabled = true;
		var op = document.getElementById(val);
		op.setAttribute("style", "opacity:0.3;");

		if (op.style.setAttribute) //For IE
		{
			op.style.setAttribute("filter", "alpha(opacity=30);");
		}
	}
</script>
<?php
$url = JURI::base();
$user = JFactory::getUser();
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();



$carthelper = rsCarthelper::getInstance();
$producthelper = productHelper::getInstance();
$order_functions = order_functions::getInstance();
$redhelper = redhelper::getInstance();
$userhelper = rsUserHelper::getInstance();
$redTemplate = Redtemplate::getInstance();
$dispatcher = RedshopHelperUtility::getDispatcher();

$user = JFactory::getUser();
$session = JFactory::getSession();
$cart = $session->get('cart');
$user_id = $user->id;

// Get redshop helper

$Itemid = RedshopHelperUtility::getCheckoutItemId();

if ($Itemid == 0)
{
	$Itemid = JRequest::getInt('Itemid');
}

$ccinfo = JRequest::getInt('ccinfo');
$print = JRequest::getInt('print');
$gls_mobile = JRequest::getString('gls_mobile');

$shop_id = JRequest::getString('shop_id') . '###' . $gls_mobile;
$model = $this->getModel('checkout');

$is_creditcard = $this->is_creditcard;

$cart_data = "";

if (Redshop::getConfig()->get('USE_AS_CATALOG'))
{
	$carttempdata = $redTemplate->getTemplate("catalogue_cart");

	if ($carttempdata[0]->template_desc != "")
	{
		$cart_data = $carttempdata[0]->template_desc;
	}
}
else
{
	$carttempdata = $redTemplate->getTemplate("checkout");

	if ($carttempdata[0]->template_desc != "")
	{
		$cart_data = $carttempdata[0]->template_desc;
	}
	else
	{
		$cart_data = '<h1>{cart_lbl}</h1><table style="width: 100%;" class="tdborder" cellpadding="0" cellspacing="0"><thead><tr><th>{product_name_lbl}</th> <th></th><th>{price_lbl} </th><th>{quantity_lbl}</th><th>{total_price_lbl}</th></tr></thead><tbody>{product_loop_start}<tr class="tdborder"><td><div class="cartproducttitle">{product_name}</div><div class="cartattribut">{product_attribute}</div><div class="cartaccessory">{product_accessory}</div><div class="cartuserfields">{product_userfields}</div></td><td>{product_thumb_image}</td><td>{product_price}</td><td><table><tbody><tr><td>{update_cart}</td><td>{remove_product}</td></tr></tbody></table></td><td>{product_total_price}</td></tr>{product_loop_end}</tbody></table><table style="width: 100%;" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td cellspacing="5" cellpadding="5" valign="top" width="50%"><table><tbody><tr><td class="cart_discount_form" colspan="2">{coupon_code_lbl}</td></tr><tr><td class="cart_thirdparty_email" colspan="2">{thirdparty_email_lbl}<br />{thirdparty_email}</td></tr><tr><td class="cart_customer_note" colspan="2">{customer_note_lbl}<br />{customer_note}</td></tr></tbody></table><br /></td><td valign="top" width="50%" align="right"><br /><br /><table class="cart_calculations"><tbody><tr><td>{totalpurchase_lbl}:</td><td width="100">{subtotal}</td></tr>{if vat}<tr><td><em>{vat_lbl}</em></td><td width="100">{tax}</td></tr>{vat end if}{if discount}<tr><td>{discount_lbl}</td><td width="100">{discount}</td></tr>{discount end if}{if payment_discount}<tr><td>{payment_discount_lbl}</td><td>{payment_order_discount}</td></tr>{payment_discount end if}<tr><td>{shipping_lbl}</td><td width="100">{shipping}</td></tr></tbody></table><table class="regnestykke_sidste"><tbody><tr><td><div class="singleline"><strong>{total_lbl}:</strong></div></td><td width="100"><div class="singleline">{total}</div></td></tr></tbody></table><div id="tabelstreg_bund"></div>{checkout}<br /><br /> {shop_more}</td></tr></tbody></table><div id="cart_left"></div>';
	}
}

// Process the product plugin for cart item
JPluginHelper::importPlugin('redshop_product');
$results = $dispatcher->trigger('onStartCartTemplateReplace', array(& $cart_data, $cart));
// End

echo JLayoutHelper::render('cart.wizard', array('step' => '2'));

if ($is_creditcard == 1 && $ccinfo != '1' && $cart['total'] > 0)
{
	$cart_data = '<form action="' . JRoute::_('index.php?option=com_redshop&view=checkout') . '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">';
	$cart_data .= $carthelper->replaceCreditCardInformation($this->payment_method_id);
	$cart_data .= '<input type="hidden" name="option" value="com_redshop" />';
	$cart_data .= '<input type="hidden" name="Itemid" value="' . $Itemid . '" />';
	$cart_data .= '<input type="hidden" name="task" value="checkoutnext" />';
	$cart_data .= '<input type="hidden" name="view" value="checkout" />';
	$cart_data .= '<input type="submit" name="submit" class="greenbutton btn btn-primary" value="' . JText::_('COM_REDSHOP_BTN_CHECKOUTNEXT') . '" />';
	$cart_data .= '<input type="hidden" name="ccinfo" value="1" />';
	$cart_data .= '<input type="hidden" name="users_info_id" value="' . $this->users_info_id . '" />';
	$cart_data .= '<input type="hidden" name="shipping_rate_id" value="' . $this->shipping_rate_id . '" />';
	$cart_data .= '<input type="hidden" name="payment_method_id" value="' . $this->payment_method_id . '" />';
	$cart_data .= '</form>';

	echo eval("?>" . $cart_data . "<?php ");
}
elseif ($cart_data != "")
{
	if ($print)
	{
		$onclick = "onclick='window.print();'";
	}
	else
	{
		$print_url = $url . "index.php?option=com_redshop&view=checkout&task=checkoutnext&print=1&tmpl=component&Itemid=" . $Itemid;
		$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
	}

	$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
	$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
	$print_tag .= "</a>";

	$cart_data = str_replace("{without_vat}", '', $cart_data);
	$cart_data = str_replace("{with_vat}", '', $cart_data);
	$cart_data = $model->displayShoppingCart($cart_data, $this->users_info_id, $this->shipping_rate_id, $this->payment_method_id, $Itemid, '', '', '', '', '', $shop_id);

	$cart_data = '<form	action="' . JRoute::_('index.php?option=com_redshop&view=checkout') . '" method="post" name="adminForm" id="adminForm"	enctype="multipart/form-data" onsubmit="return chkvalidaion();">' . $cart_data . '</form>';
	echo eval("?>" . $cart_data . "<?php ");
}

/*
$mod_cart_total = $carthelper->GetCartModuleCalc($cart);
$cart['mod_cart_total'] = $mod_cart_total;
$session->set('cart',$cart);*/
?>

<script type="text/javascript">
	function chkvalidaion() {
		<?php
			if( Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') > 0 && $cart['total'] < Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL'))
			{
			?>
		alert("<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_HAS_TO_BE_MORE_THAN') . ' ' . Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') . '';?>");
		return false;
		<?php
			}	?>
		if (document.getElementById('termscondition')) {
			var termscondition = document.getElementById('termscondition').checked;

			if (!termscondition) {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_TEMS_CONDITIONS')?>");
				return false;
			}
		}
		return true;
	}
</script>
