<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$url = JURI::base();
$user = JFactory::getUser();
$app = JFactory::getApplication();

$carthelper      = rsCarthelper::getInstance();
$producthelper   = productHelper::getInstance();
$order_functions = order_functions::getInstance();
$redhelper       = redhelper::getInstance();
$redTemplate     = Redtemplate::getInstance();
$shippinghelper  = shipping::getInstance();
$session         = JFactory::getSession();
$document        = JFactory::getDocument();

// Get redshop helper
$Itemid = $redhelper->getCheckoutItemid();
$model = $this->getModel('checkout');
$cart = $session->get('cart');

JHtml::script('com_redshop/credit_card.js', false, true);

$billingaddresses = $model->billingaddresses();

$paymentmethod = $redhelper->getPlugins('redshop_payment');
$selpayment_method_id = 0;

if (count($paymentmethod) > 0)
{
	$selpayment_method_id = $paymentmethod[0]->element;
}

$shippingBoxes = $shippinghelper->getShippingBox();
$selshipping_box_post_id = 0;

if (count($shippingBoxes) > 0)
{
	$selshipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
}

$users_info_id        = JRequest::getInt('users_info_id', $this->users_info_id);
$payment_method_id    = JRequest::getCmd('payment_method_id', $selpayment_method_id);
$shipping_box_post_id = JRequest::getInt('shipping_box_id', $selshipping_box_post_id);
$shipping_rate_id     = JRequest::getInt('shipping_rate_id', 0);

if (!empty($billingaddresses) && $users_info_id == 0)
{
	$users_info_id = $billingaddresses->users_info_id;
}

$loginTemplate = "";

if (!$users_info_id)
{
	$loginTemplate = RedshopLayoutHelper::render(
		'checkout.login',
		array(),
		'',
		array(
			'component' => 'com_redshop'
		)
	);
}

$onestep_template_desc = "";
$onesteptemplate = $redTemplate->getTemplate("onestep_checkout");

if (count($onesteptemplate) > 0 && $onesteptemplate[0]->template_desc)
{
	$onestep_template_desc = "<div id='divOnestepCheckout'>" . $onesteptemplate[0]->template_desc . "</div>";
}
else
{
	$onestep_template_desc = JText::_("COM_REDSHOP_TEMPLATE_NOT_EXISTS");
}

if (!$users_info_id && strpos($onestep_template_desc, '{billing_template}') !== false)
{
	$billingTemplate = RedshopLayoutHelper::render(
		'checkout.billing',
		array(),
		'',
		array(
			'component' => 'com_redshop'
		)
	);
	$onestep_template_desc = str_replace('{billing_template}', $billingTemplate, $onestep_template_desc);
}
else
{
	$onestep_template_desc = str_replace('{billing_template}', "", $onestep_template_desc);
}

$payment_template = "";
$payment_template_desc = "";
$templatelist = $redTemplate->getTemplate("redshop_payment");

for ($i = 0, $in = count($templatelist); $i < $in; $i++)
{
	if (strstr($onestep_template_desc, "{payment_template:" . $templatelist[$i]->template_name . "}"))
	{
		$payment_template      = "{payment_template:" . $templatelist[$i]->template_name . "}";
		$payment_template_desc = $templatelist[$i]->template_desc;
		$onestep_template_desc = str_replace($payment_template, "<div id='divPaymentMethod'>" . $payment_template . "</div>", $onestep_template_desc);
	}
}

$templatelist = $redTemplate->getTemplate("checkout");

for ($i = 0, $in = count($templatelist); $i < $in; $i++)
{
	if (strstr($onestep_template_desc, "{checkout_template:" . $templatelist[$i]->template_name . "}"))
	{
		$cart_template         = "{checkout_template:" . $templatelist[$i]->template_name . "}";
		$onestep_template_desc = str_replace($cart_template, "<div id='divRedshopCart'>" . $cart_template . "</div><div id='divRedshopCartTemplateId' style='display:none'>" . $templatelist[$i]->template_id . "</div>", $onestep_template_desc);
		$onestep_template_desc = str_replace($cart_template, $templatelist[$i]->template_desc, $onestep_template_desc);
	}
}

// For shipping template
$shippingbox_template = "";
$shippingbox_template_desc = "";
$shipping_template = "";
$shipping_template_desc = "";

$templatelist = $redTemplate->getTemplate("shippingbox");

for ($i = 0, $in = count($templatelist); $i < $in; $i++)
{
	if (strstr($onestep_template_desc, "{shippingbox_template:" . $templatelist[$i]->template_name . "}"))
	{
		$shippingbox_template      = "{shippingbox_template:" . $templatelist[$i]->template_name . "}";
		$shippingbox_template_desc = $templatelist[$i]->template_desc;
	}
}

$templatelist = $redTemplate->getTemplate("redshop_shipping");

for ($i = 0, $in = count($templatelist); $i < $in; $i++)
{
	if (strstr($onestep_template_desc, "{shipping_template:" . $templatelist[$i]->template_name . "}"))
	{
		$shipping_template      = "{shipping_template:" . $templatelist[$i]->template_name . "}";
		$shipping_template_desc = $templatelist[$i]->template_desc;

		$onestep_template_desc  = str_replace($shipping_template, "<div id='divShippingRate'>" . $shipping_template . "</div><div id='divShippingRateTemplateId' style='display:none'>" . $templatelist[$i]->template_id . "</div>", $onestep_template_desc);
	}
}

if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
{
	$ordertotal     = $cart['total'];
	$total_discount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
	$order_subtotal = (Redshop::getConfig()->get('SHIPPING_AFTER') == 'total') ? $cart['product_subtotal'] - $total_discount : $cart['product_subtotal'];

	$shippingbox_template_desc = $carthelper->replaceShippingBoxTemplate($shippingbox_template_desc, $shipping_box_post_id);
	$onestep_template_desc     = str_replace($shippingbox_template, $shippingbox_template_desc, $onestep_template_desc);

	$returnarr              = $carthelper->replaceShippingTemplate($shipping_template_desc, $shipping_rate_id, $shipping_box_post_id, $user->id, $users_info_id, $ordertotal, $order_subtotal);
	$shipping_template_desc = $returnarr['template_desc'];
	$shipping_rate_id       = $returnarr['shipping_rate_id'];

	if ($shipping_rate_id)
	{
		$shipArr              = $model->calculateShipping($shipping_rate_id);
		$cart['shipping']     = $shipArr['order_shipping_rate'];
		$cart['shipping_vat'] = $shipArr['shipping_vat'];
		$cart                 = $carthelper->modifyDiscount($cart);
	}

	$onestep_template_desc = str_replace($shipping_template, $shipping_template_desc, $onestep_template_desc);
}
else
{
	$onestep_template_desc = str_replace($shippingbox_template, "", $onestep_template_desc);
	$onestep_template_desc = str_replace($shipping_template, "", $onestep_template_desc);
}

$ean_number = 0;

if (!empty($billingaddresses) && $billingaddresses->ean_number != "")
{
	$ean_number = 1;
}

if (strstr($onestep_template_desc, "{edit_billing_address}") && $users_info_id)
{
	$editbill              = JRoute::_('index.php?option=com_redshop&view=account_billto&tmpl=component&return=checkout&Itemid=' . $Itemid);
	$edit_billing          = '<a class="modal btn btn-primary" href="' . $editbill . '" rel="{handler: \'iframe\', size: {x: 800, y: 550}}"> ' . JText::_('COM_REDSHOP_EDIT') . '</a>';
	$onestep_template_desc = str_replace("{edit_billing_address}", $edit_billing, $onestep_template_desc);
}
else
{
	$onestep_template_desc = str_replace("{edit_billing_address}", "", $onestep_template_desc);
}

if ($users_info_id)
{
	$onestep_template_desc = $carthelper->replaceBillingAddress($onestep_template_desc, $billingaddresses);
}
else
{
	$onestep_template_desc = str_replace("{billing_address}", "", $onestep_template_desc);
}

if (strstr($onestep_template_desc, "{shipping_address}"))
{
	if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE') && $users_info_id)
	{
		$shippingaddresses = $model->shippingaddresses();
		$shipp             = '';

		if ($billingaddresses && Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
		{
			$ship_check = ($users_info_id == $billingaddresses->users_info_id) ? 'checked="checked"' : '';
			$shipp .= '<div class="radio"><label class="radio"><input type="radio" onclick="javascript:onestepCheckoutProcess(this.name,\'\');" name="users_info_id" value="' . $billingaddresses->users_info_id . '" ' . $ship_check . ' />' . JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS') . '</label></div>';
		}

		for ($i = 0, $in = count($shippingaddresses); $i < $in; $i++)
		{
			$shipinfo = $shippingaddresses[$i];

			$edit_addlink = JRoute::_('index.php?option=com_redshop&view=account_shipto&tmpl=component&task=addshipping&return=checkout&Itemid=' . $Itemid . '&infoid=' . $shipinfo->users_info_id);

			$delete_addlink = $url . "index.php?option=com_redshop&view=account_shipto&return=checkout&tmpl=component&task=remove&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;
			$ship_check     = ($users_info_id == $shipinfo->users_info_id) ? 'checked="checked"' : '';

			$shipp .= '<div class="radio"><label class="radio inline"><input type="radio" onclick="javascript:onestepCheckoutProcess(this.name,\'\');" name="users_info_id" value="' . $shipinfo->users_info_id . '" ' . $ship_check . ' />' . $shipinfo->firstname . " " . $shipinfo->lastname . "</label> ";
			$shipp .= '<a class="modal" href="' . $edit_addlink . '" rel="{handler: \'iframe\', size: {x: 570, y: 470}}">(' . JText::_('COM_REDSHOP_EDIT_LBL') . ')</a> ';
			$shipp .= '<a href="' . $delete_addlink . '" title="">(' . JText::_('COM_REDSHOP_DELETE_LBL') . ')</a></div>';
		}

		$add_addlink = JRoute::_('index.php?option=com_redshop&view=account_shipto&tmpl=component&task=addshipping&return=checkout&Itemid=' . $Itemid . '&infoid=0&is_company=' . $billingaddresses->is_company);
		$shipp .= '<a class="modal btn btn-primary" href="' . $add_addlink . '" rel="{handler: \'iframe\', size: {x: 570, y: 470}}"> ' . JText::_('COM_REDSHOP_ADD_ADDRESS') . '</a>';
		$onestep_template_desc = str_replace('{shipping_address}', $shipp, $onestep_template_desc);
		$onestep_template_desc = str_replace('{shipping_address_information_lbl}', JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL'), $onestep_template_desc);
	}
	else
	{
		$onestep_template_desc = str_replace('{shipping_address}', '', $onestep_template_desc);
		$onestep_template_desc = str_replace('{shipping_address_information_lbl}', '', $onestep_template_desc);
	}
}

$isCompany = isset($billingaddresses->is_company) ? $billingaddresses->is_company : 0;
$payment_template_desc = $carthelper->replacePaymentTemplate($payment_template_desc, $payment_method_id, $isCompany, $ean_number);
$onestep_template_desc = str_replace($payment_template, $payment_template_desc, $onestep_template_desc);

$onestep_template_desc = $model->displayShoppingCart($onestep_template_desc, $users_info_id, $shipping_rate_id, $payment_method_id, $Itemid);

$onestep_template_desc = $loginTemplate . '<form action="' . JRoute::_('index.php?option=com_redshop&view=checkout') . '" method="post" name="adminForm" id="adminForm"	enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">' . $onestep_template_desc . '<div style="display:none" id="responceonestep"></div></form>';

$onestep_template_desc = $redTemplate->parseredSHOPplugin($onestep_template_desc);
echo eval("?>" . $onestep_template_desc . "<?php ");?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		jQuery('input[name="togglerchecker"]').each(function(idx, el){
			if (jQuery(el).is(':checked'))
			{
				getBillingTemplate(jQuery(el));
			}
		});
	});
	function validation()
	{
		return true;
	}
	function chkvalidaion() {
		<?php
			if (Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') > 0 && $cart['total'] < Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL'))
			{
			?>
		alert("<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_HAS_TO_BE_MORE_THAN');?>");
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
	function checkout_disable(val) {
		document.adminForm.submit();
		document.getElementById(val).disabled = true;
		var op = document.getElementById(val);
		op.setAttribute("style", "opacity:0.3;");

		if (op.style.setAttribute) //For IE
			op.style.setAttribute("filter", "alpha(opacity=30);");

	}
</script>
