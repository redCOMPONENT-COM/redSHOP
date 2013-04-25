<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');

$url = JURI::base();
$user = JFactory::getUser();
$request = JRequest::get();
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';

include_once (JPATH_COMPONENT_SITE . '/helpers/product.php');
include_once (JPATH_COMPONENT_SITE . '/helpers/cart.php');
include_once (JPATH_COMPONENT_SITE . '/helpers/user.php');
include_once (JPATH_COMPONENT_SITE . '/helpers/helper.php');

$carthelper = new rsCarthelper();
$producthelper = new producthelper();
$order_functions = new order_functions();
$redhelper = new redhelper();
$userhelper = new rsUserhelper();
$user = JFactory::getUser();
$session = JFactory::getSession();
$user_id = $user->id;
// get redshop helper

$Itemid = $redhelper->getCheckoutItemid();

if ($Itemid == 0)
	$Itemid = JRequest::getVar('Itemid');

$option = JRequest::getVar('option');
$model = $this->getModel('checkout');


$ccinfo = JRequest::getVar('ccinfo');


/*$paymentinfo = $order_functions->getPaymentMethodInfo($this->payment_method_id);
$paymentinfo = $paymentinfo[0];
*/


//$cart 		 = $this->cart;
$getparameters = $order_functions->getparameters($request['plugin']);
$order = $order_functions->getOrderDetails($request['order_id']);


$paymentinfo = $getparameters[0];

$paymentparams = new JRegistry($paymentinfo->params);

$is_creditcard = $paymentparams->get('is_creditcard', '');
$payment_oprand = $paymentparams->get('payment_oprand', '');
$payment_discount_is_percent = $paymentparams->get('payment_discount_is_percent', '');
$payment_price = $paymentparams->get('payment_price', '');
$accepted_credict_card = $paymentparams->get("accepted_credict_card");

$paymentinfo = new stdclass;
$paymentinfo->payment_price = $payment_price;
$paymentinfo->is_creditcard = $is_creditcard;
$paymentinfo->payment_oprand = $payment_oprand;
$paymentinfo->payment_discount_is_percent = $payment_discount_is_percent;
$paymentinfo->accepted_credict_card = $accepted_credict_card;

$order_shipping_rate = $cart['shipping'];

$shopperGroupId = $userhelper->getShopperGroup($user_id);

if (PAYMENT_CALCULATION_ON == 'subtotal')
{
	$paymentAmount = $cart ['product_subtotal'];
}
else
{
	$paymentAmount = $cart ['total'];
}
$paymentArray = $carthelper->calculatePayment($paymentAmount, $paymentinfo, $paymentAmount);
$total = $paymentArray[0];
$payment_amount = $paymentArray[1];

?>
	<!-- <hr/>
<table width="100%" border="0" cellspacing="2" cellpadding="2" >
<tr><td width="33%" class="checkout-bar-1"><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION' ); ?></td>
	<td width="33%" class="checkout-bar-2-active"><?php echo JText::_('COM_REDSHOP_PAYMENT' ); ?></td>
	<td width="33%" class="checkout-bar-3"><?php echo JText::_('COM_REDSHOP_RECEIPT' ); ?></td></tr>
</table>
<hr/>
--><?php


if ($is_creditcard == 1 && $ccinfo != '1')
{

	$accepted_cc_list = array();
	$accepted_cc_list = $accepted_credict_card;
	if ($accepted_credict_card != "")
		$cc_list = array();

	$cc_list['VISA']->img = 'visa.jpg';
	$cc_list['MC']->img = 'master.jpg';
	$cc_list['amex']->img = 'blue.jpg';
	$cc_list['maestro']->img = 'mastero.jpg';
	$cc_list['jcb']->img = 'jcb.jpg';
	$cc_list['diners']->img = 'dinnersclub.jpg';
	$cc_list['discover']->img = 'discover.jpg';
	?>

	<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=checkout') ?>" method="post"
	      name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">

		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_CARD_INFORMATION'); ?></legend>
			<table class="admintable">
				<tr>
					<td colspan="2" align="right" nowrap="nowrap">


						<table width="100%" border="0" cellspacing="2" cellpadding="2">
							<tr>
								<?php for ($i = 0; $i < count($accepted_cc_list); $i++)
								{
									$cc_img = $cc_list[$accepted_cc_list[$i]]->img;
									?>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/<?php echo $cc_img; ?>"
											alt="" border="0"/></td>
								<?php } ?>
							</tr>
							<tr>
								<?php for ($i = 0; $i < count($accepted_cc_list); $i++)
								{
									$value = $accepted_cc_list[$i];
									$checked = "";
									if (!isset($_SESSION['ccdata']['creditcard_code']) && $i == 0)
										$checked = "checked";
									elseif (isset($_SESSION['ccdata']['creditcard_code']))
										$checked = ($_SESSION['ccdata']['creditcard_code'] == $value) ? "checked" : "";
									?>
									<td align="center"><input type="radio" name="creditcard_code"
									                          value="<?php echo $value; ?>"  <?php echo $checked ?>  />
									</td>
								<?php } ?>
							</tr>
						</table>

					</td>

				</tr>
				<tr valign="top">
					<td align="right" nowrap="nowrap" width="10%">
						<label for="order_payment_name"><?php echo JText::_('COM_REDSHOP_NAME_ON_CARD'); ?></label>
					</td>
					<td>
						<input class="inputbox" id="order_payment_name" name="order_payment_name"
						       value="<?php if (!empty($_SESSION['ccdata']['order_payment_name'])) echo $_SESSION['ccdata']['order_payment_name'] ?>"
						       autocomplete="off" type="text">
					</td>

				</tr>
				<tr valign="top">
					<td align="right" nowrap="nowrap" width="10%">
						<label for="order_payment_number"><?php echo JText::_('COM_REDSHOP_CARD_NUM'); ?></label>
					</td>
					<td>
						<input class="inputbox" id="order_payment_number" name="order_payment_number"
						       value="<?php if (!empty($_SESSION['ccdata']['order_payment_number'])) echo $_SESSION['ccdata']['order_payment_number'] ?>"
						       autocomplete="off" type="text">
					</td>

				</tr>

				<tr>
					<td align="right" nowrap="nowrap"
					    width="10%"><?php echo JText::_('COM_REDSHOP_EXPIRY_DATE'); ?></td>
					<td>
						<?php
						$value = @$_SESSION['ccdata']['order_payment_expire_month'];
						if ($value == '')
						{
							$value = date('m');
						}
						$arr = array("Month",
							"01" => JText::_('COM_REDSHOP_JAN'),
							"02" => JText::_('COM_REDSHOP_FEB'),
							"03" => JText::_('COM_REDSHOP_MAR'),
							"04" => JText::_('COM_REDSHOP_APR'),
							"05" => JText::_('COM_REDSHOP_MAY'),
							"06" => JText::_('COM_REDSHOP_JUN'),
							"07" => JText::_('COM_REDSHOP_JUL'),
							"08" => JText::_('COM_REDSHOP_AUG'),
							"09" => JText::_('COM_REDSHOP_SEP'),
							"10" => JText::_('COM_REDSHOP_OCT'),
							"11" => JText::_('COM_REDSHOP_NOV'),
							"12" => JText::_('COM_REDSHOP_DEC'));

						$html = "<select class=\"inputbox\" name=\"order_payment_expire_month\" size=\"1\" >\n";

						while (list($key, $val) = each($arr))
						{
							$selected = "";
							if (is_array($value))
							{
								if (in_array($key, $value))
								{
									$selected = "selected=\"selected\"";
								}
							}
							else
							{
								if (strtolower($value) == strtolower($key))
								{
									$selected = "selected=\"selected\"";
								}
							}
							$html .= "<option value=\"$key\" $selected>$val";
							$html .= "</option>\n";
						}

						echo $html .= "</select>\n";

						?>
						<?php $thisyear = date('Y'); ?>
						/<select class="inputbox" name="order_payment_expire_year" size="1">
							<?php

							for ($y = $thisyear; $y < ($thisyear + 10); $y++)
							{

								?>
								<option
									value="<?php echo $y; //echo substr($y,2); ?>" <?php if (!empty($_SESSION['ccdata']['order_payment_expire_year']) && $_SESSION['ccdata']['order_payment_expire_year'] == $y)
								{ ?> selected="selected" <?php } ?> ><?php echo $y; ?></option>
							<?php
							}
							?> </select>
					</td>
				</tr>

				<tr valign="top">
					<td align="right" nowrap="nowrap" width="10%">
						<label for="credit_card_code">
							<?php echo JText::_('COM_REDSHOP_CARD_SECURITY_CODE'); ?>
						</label>
					</td>
					<td>
						<input class="inputbox" id="credit_card_code" name="credit_card_code"
						       value="<?php if (!empty($_SESSION['ccdata']['credit_card_code'])) echo $_SESSION['ccdata']['credit_card_code'] ?>"
						       autocomplete="off" type="text">
					</td>
				</tr>


				<tr valign="top">
					<td align="right" nowrap="nowrap" width="10%">
						<label for="credit_card_code">
							<?php echo JText::_('COM_REDSHOP_ORDERTOTAL'); ?>
						</label>
					</td>
					<td>
						<?php

						$total = $order->order_total;
						$cart_shipping = 0;
						if (!isset($order->order_shipping) || $order->order_shipping == '')
						{
							$cart_shipping = 0;
						}
						else
						{
							$cart_shipping = $order->order_shipping;
						}
						//	$total = ($total - $cart_shipping)+$order_shipping_rate;
						$total = $total + $cart_shipping;

						$tax = $order->order_tax;
						$check_type = $order->order_discount;

						$cdiscount = $order->order_discount;
						if ($check_type == 0) //////// 0 : Discount code in total , 1: Discount code in total
						{
							$discount_total = $order->order_discount;
						}
						else if ($check_type == 1) //////// 0 : Discount code in percentage , 1: Discount code in perstage
						{
							$discount_total = $order->order_discount;
							$discount_total = ($total * $discount_total) / 100;
						}


						$odiscount = 0;
						if ($order->order_discount > 0)
						{
							$total = ($total) - ($odiscount);
							$odiscount = $discount_total + $order->order_discount;
						}
						else
						{
							$total = 0;
							$odiscount = $cdiscount + $order->order_discount;
						}

						$issplit = $session->get('issplit');
						if ($issplit)
							$amt = $total / 2;
						else
							$amt = $total;
						?>
						<?php
						echo $order->order_total;
						//echo $producthelper->getProductFormattedPrice($amt); ?>
					</td>
				</tr>
			</table>
		</fieldset>
		<div style="float: right;">
			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="task" value="checkoutnext"/>
			<input type="hidden" name="payment_plugin" value="<?php echo $request['plugin'] ?>"/>
			<input type="hidden" name="order_id" value="<?php echo $request['order_id'] ?>"/>
			<input type="hidden" name="view" value="order_detail"/>
			<input type="submit" name="submit" class="greenbutton"
			       value="<?php echo JText::_('COM_REDSHOP_BTN_CHECKOUTNEXT'); ?>"/>
			<input type="hidden" name="ccinfo" value="1"/>
			<input type="hidden" name="users_info_id" value="<?php echo $order->user_info_id; ?>"/>
		</div>
	</form>
<?php

}
else
{

	$values = array();
	JPluginHelper::importPlugin('redshop_payment');
	$dispatcher = JDispatcher::getInstance();
	$results = $dispatcher->trigger('onPrePayment', array($request['plugin'], $values));
	$paymentResponse = $results[0];




	?>
	<form>
		<div style="float: right;">
			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="task" value="checkoutnext"/>
			<input type="hidden" name="payment_plugin" value="<?php echo $request['plugin'] ?>"/>
			<input type="hidden" name="order_id" value="<?php echo $request['order_id'] ?>"/>
			<input type="hidden" name="view" value="order_detail"/>
			<input type="submit" name="submit" class="greenbutton"
			       value="<?php echo JText::_('COM_REDSHOP_BTN_CHECKOUTNEXT'); ?>"/>
			<input type="hidden" name="ccinfo" value="0"/>
			<input type="hidden" name="users_info_id" value="<?php echo $order->user_info_id; ?>"/>
		</div>
	</form>
<?php } ?>
