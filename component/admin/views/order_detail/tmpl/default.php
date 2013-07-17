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
require_once JPATH_COMPONENT_SITE . '/helpers/helper.php';
require_once JPATH_COMPONENT_SITE . '/helpers/cart.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/shipping.php';

$producthelper = new producthelper();
$carthelper = new rsCarthelper();
$order_functions = new order_functions();
$redhelper = new redhelper();
$extra_field = new extra_field();
$shippinghelper = new shipping();
$config = new Redconfiguration();

$uri = JURI::getInstance();
$url = $uri->root();

$option = JRequest::getVar('option');
$tmpl = JRequest::getVar('tmpl');
$model = $this->getModel('order_detail');
$session = JFactory::getSession();
$billing = $this->billing;
$shipping = $this->shipping;
$is_company = $billing->is_company;
$order_id = $this->detail->order_id;
$products = $order_functions->getOrderItemDetail($order_id);
$log_rec = $model->getOrderLog($order_id);

if (!$shipping)
{
	$shipping = $billing;
}
$session->set('shipp_users_info_id', $shipping->users_info_id);

# get Downloadable Products
$downloadProducts = $order_functions->getDownloadProduct($order_id);
$totalDownloadProduct = count($downloadProducts);
$dproducts = array();
for ($t = 0; $t < $totalDownloadProduct; $t++)
{
	$downloadProduct = $downloadProducts[$t];
	$dproducts[$downloadProduct->product_id][$downloadProduct->download_id] = $downloadProduct;
}
?>
<script type="text/javascript">
	var rowCount = 1;
	function openPrintOrder() {
		window.open('index.php?tmpl=component&option=com_redshop&view=order_detail&layout=print_order&cid[]=' + <?php echo $order_id;?>, 'mywindow', 'scrollbars=1', 'location=1');
	}
	function submitbutton(pressbutton, form) {
		if (pressbutton == 'add') {
			if (form.product1.value == 0) {
				alert("<?php echo JText::_('SELECT_PRODUCT');?>");
				return false;
			}
			else if (validateExtrafield(form) == false) {
				return false;
			}
			else {
				form.task.value = 'neworderitem';
				form.submit();
				return true;
			}
		}
	}
</script>
<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
<tbody>
<tr>
	<td colspan="2">
		<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
			<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
				<tbody>
				<tr>
					<th align="right" colspan="2">
						<?php if(isset($order_id)) {     ?>
						<a href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=createpdfstocknote&cid[]=' . $order_id); ?>"><?php echo JText::_('COM_REDSHOP_CREATE_STOCKNOTE'); ?></a>
						&nbsp;
						<a href="javascript:openPrintOrder();" title="<?php echo JText::_('COM_REDSHOP_PRINT'); ?>">
							<img src="<?php echo JSYSTEM_IMAGES_PATH . 'printButton.png'; ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_PRINT'); ?>"
							     title="<?php echo JText::_('COM_REDSHOP_PRINT'); ?>"/></a>

						<a href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=createpdf&cid[]=' . $order_id); ?>"><?php echo JText::_('COM_REDSHOP_CREATE_SHIPPING_LABEL'); ?></a>
						&nbsp;<?php if($tmpl){ ?><a
							href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=send_downloadmail&cid[]=' . $order_id); ?>&tmpl=<?php echo $tmpl; ?>"><?php } else { ?>
							<a href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=send_downloadmail&cid[]=' . $order_id); ?>"><?php }  echo JText::_('COM_REDSHOP_SEND_DOWNLOEADMAIL'); ?></a>
							&nbsp;<?php if($tmpl){ ?><a
								href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=send_invoicemail&cid[]=' . $order_id); ?>&tmpl=<?php echo $tmpl; ?>"><?php } else { ?>
								<a href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=send_invoicemail&cid[]=' . $order_id); ?>"><?php }  echo JText::_('COM_REDSHOP_SEND_INVOICEMAIL'); ?></a>
								&nbsp;
								<?php }    ?>

								<?php if (!$tmpl)
								{ ?>

									<a href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order'); ?>"><?php echo JText::_('COM_REDSHOP_BACK'); ?></a>
								<?php } ?>

					</th>
				</tr>
				<tr>
					<th align="left" colspan="2"><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION'); ?></th>
				</tr>
				<!--<tr>
						<td width="100"><?php echo "Barcode" ?>:</td>
						<td>
							<?php
							$barcode = sprintf("%012d",$this->detail->order_number);
							$barcode_url = REDSHOP_FRONT_IMAGES_ABSPATH.'barcode/'.$barcode.'.png';
							?>
							<img alt="" src="<?php echo $barcode_url;?>">
						</td>
					</tr>
					-->
				<tr>
					<td width="100"><?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?>:</td>
					<td><?php echo $order_id; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ORDER_NUMBER'); ?>:</td>
					<td><?php echo $this->detail->order_number; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?>:</td>
					<td><?php echo $config->convertDateFormat($this->detail->cdate); ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_METHOD'); ?>:</td>
					<td><?php echo JText::_($this->payment_detail->order_payment_name); ?>
						<?php if (count($model->getccdetail($order_id)) > 0)
						{ ?>
							<a href="<?php echo JRoute::_('index.php?option=' . $option . '&view=order_detail&task=ccdetail&cid[]=' . $order_id); ?>"
							   class="modal"
							   rel="{handler: 'iframe', size: {x: 550, y: 200}}"><?php echo JText::_('COM_REDSHOP_CLICK_TO_VIEW_CREDIT_CARD_DETAIL');?></a>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_EXTRA_FILEDS'); ?>:</td>
					<td><?php echo $PaymentExtrafields = $producthelper->getPaymentandShippingExtrafields($this->detail, 18); ?>

					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_REFERENCE_NUMBER'); ?>:</td>
					<td><?php
						if ($this->payment_detail->order_payment_trans_id != "")
						{
							echo $this->payment_detail->order_payment_trans_id;
						}
						else
						{
							echo "N/A";
						}
						?>
					</td>
				</tr>
				<?php //if($is_company){?>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER'); ?>:</td>
					<td><input class="inputbox" name="requisition_number" id="requisition_number"
					           value="<?php echo $this->detail->requisition_number; ?>"/></td>
				</tr>
				<?php //}?>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?>:</td>
					<td>
						<?php

						$send_mail_to_customer = 0;
						if (SEND_MAIL_TO_CUSTOMER)
						{
							$send_mail_to_customer = "checked";
						}

						$linkupdate = JRoute::_('index.php?option=' . $option . '&view=order&task=update_status&return=order_detail&order_id[]=' . $order_id);

						echo $order_functions->getstatuslist('status', $this->detail->order_status, "class=\"inputbox\" size=\"1\" ");
						echo "&nbsp";
						echo $order_functions->getpaymentstatuslist('order_paymentstatus', $this->detail->order_payment_status, "class=\"inputbox\" size=\"1\" ");
						?>
						<?php if ($tmpl)
						{ ?>
							<input type="hidden" name="tmpl" value="<?php echo $tmpl ?>">
						<?php } ?>
						<input type="checkbox" <?php echo $send_mail_to_customer;?>  value="true"
						       name="order_sendordermail"
						       id="order_sendordermail"/><?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL'); ?>
						<input class="button" onclick="this.form.submit();" name="order_status"
						       value="<?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON'); ?>" type="button">
						<br/><br/>
						<?php
						$partial_paid = $order_functions->getOrderPartialPayment($order_id);

						$remaningtopay = $this->detail->order_total - $partial_paid;
						$remaningtopay = $producthelper->getProductFormattedPrice($remaningtopay);//number_format($remaningtopay,2);
						?>
						<?php if ($this->detail->split_payment)
						{
							echo "<strong>" . JText::_('COM_REDSHOP_ORDER_DETAIL_PARTIALLY_PAID_AMOUNT') . ": " . $producthelper->getProductFormattedPrice($partial_paid) . "</strong>";
						}
						?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_COMMENT'); ?>:</td>
					<td>
						<table width="100%" border="0" cellspacing="2" cellpadding="2">
							<tr>
								<td width="25%"><textarea cols="50" rows="5"
								                          name="customer_note"><?php echo $this->detail->customer_note;?></textarea>
								</td>
								<td><?php /*?><input class="inputbox" name="nc"
										id="notify_customer<?php echo $order_id ;?>"
										value="N" type="checkbox"
										onclick="if(this.checked==true) {this.value = 'Y';} else {this.value = 'N';}"><?php echo JText::_('COM_REDSHOP_INCLUDE_COMMENT_MSG' ); ?> <br />
									<input class="inputbox" name="ic"
										id="include_comment<?php echo $order_id ;?>"
										value="N" type="checkbox"
										onclick="if(this.checked==true) {this.value = 'Y';} else {this.value = 'N';}"><?php echo JText::_('COM_REDSHOP_NOTIFY_CUSTOMER_MSG' ); ?><?php */?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_CUSTOMER_IP_ADDRESS'); ?>:</td>
					<td><?php echo $this->detail->ip_address; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_CUSTOMER_MESSAGE_LBL'); ?>:</td>
					<td><?php echo $this->detail->customer_message; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_REFERRAL_CODE_LBL'); ?>:</td>
					<td><?php echo $this->detail->referral_code; ?></td>
				</tr>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="view" value="order"/>
			<input type="hidden" name="task" value="update_status"/>
			<input type="hidden" name="return" value="order_detail"/>
			<input type="hidden" name="order_id[]" value="<?php echo $order_id; ?>"/>
		</form>
	</td>
</tr>
<tr>
	<td colspan="2">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adminlist">
			<tbody>
			<tr style="background-color: #cccccc">
				<th><?php echo JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION'); ?></th>
				<th><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'); ?></th>
			</tr>
			<tr valign="top">
				<td>
					<table class="adminlist" border="0">

						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</td>
							<td><?php echo $billing->firstname; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
							<td><?php echo $billing->lastname; ?></td>
						</tr>
						<?php if ($billing->company_name)
						{ ?>
							<tr>
								<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_COMPANY'); ?>:</td>
								<td><?php echo $billing->company_name; ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
							<td><?php echo $billing->address; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
							<td><?php echo $billing->zipcode; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
							<td><?php echo $billing->city; ?></td>
						</tr>

						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
							<td><?php echo ($billing->country_code) ? JTEXT::_($order_functions->getCountryName($billing->country_code)) : ''; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
							<td><?php echo ($billing->state_code) ? $order_functions->getStateName($billing->state_code, $billing->country_code) : ''; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
							<td><?php echo $billing->phone; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
							<td>
								<a href="mailto:<?php echo $billing->user_email; ?>"><?php echo $billing->user_email; ?></a>
							</td>
						</tr>
						<?php
						if ($is_company)
						{
							?>
							<tr>
								<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</td>
								<td><?php echo $billing->vat_number; ?></td>
							</tr>
							<tr>
								<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td>
								<td><?php echo $billing->tax_exempt; ?></td>
							</tr>
							<tr>
								<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</td>
								<td><?php echo $billing->ean_number; ?></td>
							</tr>
							<!-- <tr>
									<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER' ); ?>:</td>
									<td><?php echo ($billing->requisition_number!="") ? $billing->requisition_number : "N/A"; ?></td>
								</tr>-->
							<?php    $fields = $extra_field->list_all_field_display(8, $billing->users_info_id);
						}
						else
						{
							$fields = $extra_field->list_all_field_display(7, $billing->users_info_id);
						}
						echo $fields; ?>
						<tr>
							<td colspan="2">
								<?php if (!$tmpl)
								{ ?>
									<a class="modal"
									   href="index.php?tmpl=component&option=com_redshop&view=order_detail&layout=billing&cid[]=<?php echo $order_id; ?>"
									   rel="{handler: 'iframe', size: {x: 500, y: 450}}"><?php echo JText::_('COM_REDSHOP_EDIT');?></a>
								<?php } ?>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table class="adminlist" border="0">
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</td>
							<td><?php echo $shipping->firstname; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
							<td><?php echo $shipping->lastname; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
							<td><?php echo $shipping->address; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
							<td><?php echo $shipping->zipcode; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
							<td><?php echo $shipping->city; ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
							<td><?php echo JTEXT::_($order_functions->getCountryName($shipping->country_code)); ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
							<td><?php echo $order_functions->getStateName($shipping->state_code, $shipping->country_code); ?></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
							<td><?php echo $shipping->phone; ?></td>
						</tr>
						<?php

						if ($is_company)
						{
							$fields = $extra_field->list_all_field_display(15, $shipping->users_info_id);
						}
						else
						{
							$fields = $extra_field->list_all_field_display(14, $shipping->users_info_id);
						}
						echo $fields; ?>
						<tr>
							<td colspan="2">
								<?php if (!$tmpl)
								{ ?>

									<a class="modal"
									   href="index.php?tmpl=component&option=com_redshop&view=order_detail&layout=shipping&cid[]=<?php echo $order_id; ?>"
									   rel="{handler: 'iframe', size: {x: 500, y: 450}}"><?php echo JText::_('COM_REDSHOP_EDIT');?></a>
								<?php } ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>
<?php
$arr_discount_type = array();
$arr_discount = explode('@', $this->detail->discount_type);
$discount_type = '';
for ($d = 0; $d < count($arr_discount); $d++)
{
	if ($arr_discount[$d])
	{
		$arr_discount_type = explode(':', $arr_discount[$d]);

		if ($arr_discount_type[0] == 'c')
			$discount_type .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
		if ($arr_discount_type[0] == 'v')
			$discount_type .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
	}
}

if (!$discount_type)
{
	$discount_type = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
}
?>
<tr>
	<td colspan="2">
		<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
			<tbody>
			<tr style="background-color: #cccccc">
				<th align="left"><?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?></th>
			</tr>
			<tr>
				<td>
					<?php echo $discount_type;?>
				</td>
			</tr>
			</tbody>
		</table>
</tr>
<tr>
<td colspan="2">
<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
<tbody>
<tr style="background-color: #cccccc">
	<th align="left"><?php echo JText::_('COM_REDSHOP_ORDER_DETAILS'); ?></th>
</tr>
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="0" class="adminlist" width="100%">
<tbody>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" class="adminlist" width="100%">
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="0" class="adminlist" width="100%">
						<tr>
							<td width="20%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></td>
							<td width="15%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></td>
							<td width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></td>
							<td width="5%" align="right"><?php echo JText::_('COM_REDSHOP_TAX'); ?></td>
							<td width="10%" align="right"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></td>
							<td width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></td>
							<td width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></td>
							<td width="20%"><?php echo JText::_('COM_REDSHOP_STATUS'); ?></td>
							<td width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
	<?php
	if ($totalDownloadProduct > 0) echo '<td>' . JText::_('COM_REDSHOP_DOWNLOAD_SETTING') . '</td>';
	?>
</tr>
<?php

$ordervolume = 0;
$cart = array();
$subtotal_excl_vat = 0;
for ($i = 0; $i < count($products); $i++)
{
	$cart[$i]['product_id'] = $products[$i]->product_id;
	$cart[$i]['quantity'] = $products[$i]->product_quantity;
	$quantity = $products[$i]->product_quantity;
	$product_id = $products[$i]->product_id;
	$productdetail = $producthelper->getProductById($product_id);
	$ordervolume = $ordervolume + $productdetail->product_volume;
	$order_item_id = $products[$i]->order_item_id;
	$order_item_name = $products[$i]->order_item_name;
	$order_item_sku = $products[$i]->order_item_sku;
	$wrapper_id = $products[$i]->wrapper_id;

	$p_userfield = $producthelper->getuserfield($order_item_id);
	$subscribe_detail = $model->getUserProductSubscriptionDetail($order_item_id);
	$catId = $producthelper->getCategoryProduct($product_id);
	$res = $producthelper->getSection("category", $catId);
	if (count($res) > 0)
	{
		$cname = $res->category_name;
		$clink = JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catId);

	}
	$cname = "<a href='" . $clink . "'>" . $cname . "</a>";
	$Product_name = $order_item_name . '<br>' . $order_item_sku . '<br>' . $p_userfield . '<br>' . $cname;

	$subtotal_excl_vat += $products[$i]->product_item_price_excl_vat * $quantity;
	$vat = ($products[$i]->product_item_price - $products[$i]->product_item_price_excl_vat);
	?>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" class="adminlist" width="100%">
				<tr>
					<td>
						<form action="<?php echo 'index.php?option=' . $option; ?>" method="post"
						      name="itemForm<?php echo $order_item_id; ?>">
							<table border="0" cellspacing="0" cellpadding="0" class="adminlist" width="100%">
								<tr>

									<td width="20%"><?php echo $Product_name; ?>
									</td>
									<td width="15%"><?php

										echo $products[$i]->product_attribute . "<br />" . $products[$i]->product_accessory . "<br/>" . $products[$i]->discount_calc_data;
										if ($wrapper_id)
										{
											$wrapper = $producthelper->getWrapper($product_id, $wrapper_id);
											echo "<br>" . JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name . "(" . $products[$i]->wrapper_price . ")";
										}
										if ($subscribe_detail)
										{
											$subscription_detail = $model->getProductSubscriptionDetail($product_id, $subscribe_detail->subscription_id);
											$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;
											echo JText::_('COM_REDSHOP_SUBSCRIPTION') . ': ' . $selected_subscription;
										}
										?>
									</td>
									<td width="10%">
										<?php echo REDCURRENCY_SYMBOL; ?>
										<input type="text" name="update_price" id="update_price"
										       value="<?php echo $producthelper->redpriceDecimal($products[$i]->product_item_price_excl_vat); ?>"
										       size="10">

									</td>
									<td width="5%"><?php echo REDCURRENCY_SYMBOL . " " . $vat;?></td>
									<td width="10%"><?php echo $producthelper->getProductFormattedPrice($products[$i]->product_item_price) . " " . JText::_('COM_REDSHOP_INCL_VAT'); ?></td>

									<td width="5%">
										<input type="text" name="quantity" id="quantity"
										       value="<?php echo $quantity; ?>" size="3">
									</td>
									<td align="right" width="10%">
										<?php
										echo REDCURRENCY_SYMBOL . "&nbsp;";
										echo $producthelper->redpriceDecimal($products[$i]->product_final_price);
										?>
									</td>
									<td width="20%">
										<?php
										echo $order_functions->getstatuslist('status', $products[$i]->order_status, "class=\"inputbox\" size=\"1\" ");
										?>
										<br><br><textarea cols="30" rows="3"
										                  name="customer_note"><?php echo $products[$i]->customer_note;?></textarea><br/>
									</td>
									<td width="5%">
										<img class="delete_item"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>cross.jpg"
										     title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
										     alt="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
										     onclick="if(confirm('<?php echo JText::_('COM_REDSHOP_CONFIRM_DELETE_ORDER_ITEM'); ?>')) { document.itemForm<?php echo $order_item_id; ?>.task.value='delete_item';document.itemForm<?php echo $order_item_id; ?>.submit();}">
										<img class="update_price"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>update.jpg"
										     title="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
										     alt="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
										     onclick="document.itemForm<?php echo $order_item_id; ?>.task.value='updateItem';document.itemForm<?php echo $order_item_id; ?>.submit();">
									</td>
								</tr>
							</table>
							<input type="hidden" name="task" id="task" value="">
							<input type="hidden" name="view" value="order_detail">
							<input type="hidden" name="productid" value="<?php echo $product_id; ?>">
							<input type="hidden" name="cid[]" value="<?php echo $order_id; ?>">
							<input type="hidden" name="order_id[]" value="<?php echo $order_id; ?>"/>
							<input type="hidden" name="order_item_id" value="<?php echo $order_item_id; ?>">
							<input type="hidden" name="return" value="order_detail"/>
							<input type="hidden" name="isproduct" value="1"/>
							<input type="hidden" name="option" value="<?php echo $option; ?>"/>
							<?php if ($tmpl)
							{ ?>
								<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
							<?php } ?>
						</form>
					</td>
					<?php
					$downloadarray = @$dproducts[$product_id];
					if ($totalDownloadProduct > 0)
					{
						?>
						<td>
							<?php
							if (count($downloadarray) > 0)
							{
								?>
								<form action="<?php echo 'index.php?option=' . $option; ?>" method="post"
								      name="download_token<?php echo $order_item_id; ?>">
									<table cellpadding="0" cellspacing="0" border="0">
										<?php
										foreach ($downloadarray as $downloads)
										{
											$file_name = substr(basename($downloads->file_name), 11);
											$download_id = $downloads->download_id;
											$download_max = $downloads->download_max;
											$end_date = $downloads->end_date;
											$product_download_infinite = ($end_date == 0) ? 1 : 0;

											if ($end_date == 0)
											{
												$limit_over = false;
											}
											else
											{
												$days_in_time = $end_date - time();
												$hour = date("H", $end_date);
												$minite = date("i", $end_date);
												$days = round($days_in_time / (24 * 60 * 60));
												$limit_over = false;
												if ($days_in_time <= 0 || $download_max <= 0)
												{
													$limit_over = true;
												}
											}
											$td_style = ($end_date == 0) ? 'style="display:none;"' : 'style="display:table-row;"';
											?>
											<tr>
												<th colspan="2"
												    align="center"><?php echo JText::_('COM_REDSHOP_TOKEN_ID') . ": " . $download_id;?></th>
											</tr>
											<?php
											if ($limit_over)
											{
												?>
												<tr>
													<td colspan="2"
													    align="center"><?php echo JText::_('COM_REDSHOP_DOWNLOAD_LIMIT_OVER');?></td>
												</tr>
											<?php
											}
											?>
											<tr>
												<td valign="top" align="right"
												    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_INFINITE_LIMIT'); ?>
													:
												</td>
												<td><?php echo JHTML::_('select.booleanlist', 'product_download_infinite_' . $download_id, 'class="inputbox" onclick="hideDownloadLimit(this,\'' . $download_id . '\');" ', $product_download_infinite);?></td>
											</tr>
											<tr id="limit_<?php echo $download_id; ?>" <?php echo $td_style;?>>
												<td><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL');?></td>
												<td><input type="text" name="limit_<?php echo $download_id; ?>"
												           value="<?php echo $download_max; ?>"></td>
											</tr>
											<tr id="days_<?php echo $download_id; ?>" <?php echo $td_style;?>>
												<td><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL');?></td>
												<td>
													<input type="text" name="days_<?php echo $download_id; ?>" size="2"
													       maxlength="2" value="<?php echo $days; ?>">
												</td>
											</tr>
											<tr id="clock_<?php echo $download_id; ?>" <?php echo $td_style;?>>
												<td><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_CLOCK_LBL');?></td>
												<td>
													<input type="text" name="clock_<?php echo $download_id; ?>" size="2"
													       maxlength="2" value="<?php echo $hour; ?>">:
													<input type="text" name="clock_min_<?php echo $download_id; ?>"
													       size="2" maxlength="2" value="<?php echo $minite; ?>">
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<hr/>
													<input type="hidden" name="download_id[]"
													       value="<?php echo $download_id; ?>">
												</td>
											</tr>
										<?php
										}
										?>
										<tr>
											<td colspan="2" align="center">
												<input type="button" name="update"
												       value="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
												       onclick="document.download_token<?php echo $order_item_id; ?>.submit();">
												<input type="hidden" name="option" value="<?php echo $option; ?>"/>
												<input type="hidden" name="view" value="order"/>
												<input type="hidden" name="task" value="download_token"/>
												<input type="hidden" name="product_id"
												       value="<?php echo $product_id; ?>"/>
												<input type="hidden" name="return" value="order_detail"/>
												<input type="hidden" name="cid[]" value="<?php echo $order_id; ?>"/>
												<?php if ($tmpl)
												{ ?>
													<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
												<?php } ?>
											</td>
										</tr>
									</table>
								</form>
							<?php
							}
							?>
						</td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>

<?php
}
$cart['idx'] = count($cart);
$session->set('cart', $cart); ?>
</tbody>
</table>
</td>
</tr>
<tr>
	<td></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0"
		       class="adminlist">
			<tbody>
			<tr align="left">
				<td align="right" width="70%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_SUBTOTAL'); ?>:</strong>
				</td>
				<td align="right"
				    width="30%"><?php echo $producthelper->getProductFormattedPrice($subtotal_excl_vat);//CURRENCY_SYMBOL."&nbsp;&nbsp;".( $this->detail->order_subtotal - $this->detail->order_discount); ?></td>
			</tr>
			<tr align="left">
				<td align="right" width="70%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_TAX'); ?>:</strong></td>
				<?php
				$order_tax     = $this->detail->order_tax;
				$totaldiscount = $this->detail->order_discount;
				if (APPLY_VAT_ON_DISCOUNT == '0' && VAT_RATE_AFTER_DISCOUNT && $this->detail->order_discount != "0.00" && $order_tax && !empty($this->detail->order_discount))
				{

					$totaldiscount = $this->detail->order_discount;
					$Discountvat = (VAT_RATE_AFTER_DISCOUNT * $totaldiscount) / (1 + VAT_RATE_AFTER_DISCOUNT);
					$totaldiscount = $totaldiscount - $Discountvat;
					$order_tax = VAT_RATE_AFTER_DISCOUNT * ($subtotal_excl_vat - $totaldiscount);
				}
				?>
				<td align="right"
				    width="30%"><?php echo $producthelper->getProductFormattedPrice($order_tax);//CURRENCY_SYMBOL."&nbsp;&nbsp;".$this->detail->order_tax; ?></td>
			</tr>
			<tr align="left">
				<td align="right" width="70%">
					<strong>
						<?php
						if ($this->detail->payment_oprand == '+')
							echo JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL');
						else
							echo JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
						?>:
					</strong>
				</td>
				<td align="right" width="30%">
					<?php echo $producthelper->getProductFormattedPrice($this->detail->payment_discount); ?>
				</td>
			</tr>
			<tr align="left">
				<td align="right" width="70%">
					<strong>
						<?php echo JText::_('COM_REDSHOP_ORDER_DISCOUNT'); ?>:
					</strong>
				</td>
				<td align="right" width="30%">
					<form action="<?php echo 'index.php?option=' . $option; ?>" method="post"
					      name="update_discount<?php echo $order_id; ?>">
						<label style="float: left;">
							<?php echo REDCURRENCY_SYMBOL . "&nbsp;&nbsp;"; ?><input type="text" name="update_discount"
							                                                         id="update_discount"
							                                                         value="<?php echo $producthelper->redpriceDecimal($this->detail->order_discount); ?>"
							                                                         size="10">
							&nbsp;<img class="update_price" align="absmiddle"
							           src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>update.jpg"
							           title="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
							           alt="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
							           onclick="document.update_discount<?php echo $order_id; ?>.submit();">
						</label>
						<?php echo $producthelper->getProductFormattedPrice($totaldiscount);//CURRENCY_SYMBOL."&nbsp;&nbsp;".$this->detail->order_discount; ?>
						<input type="hidden" name="task" value="update_discount">
						<input type="hidden" name="view" value="order_detail">
						<input type="hidden" name="cid[]" value="<?php echo $order_id; ?>">
					</form>
				</td>
			</tr>
			<tr align="left">
				<td align="right" width="70%"><strong><?php echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT'); ?>:</strong>
				</td>
				<td align="right" width="30%">
					<form action="<?php echo 'index.php?option=' . $option; ?>" method="post"
					      name="special_discount<?php echo $order_id; ?>">
						<label style="float: left;">
							<?php echo REDCURRENCY_SYMBOL . "&nbsp;&nbsp;"; ?><input type="text" name="special_discount"
							                                                         id="special_discount"
							                                                         value="<?php echo $this->detail->special_discount; ?>"
							                                                         size="10">%
							&nbsp;<img class="update_price" align="absmiddle"
							           src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>update.jpg"
							           title="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
							           alt="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
							           onclick="document.special_discount<?php echo $order_id; ?>.submit();">
						</label>
						<?php

						echo $producthelper->getProductFormattedPrice($this->detail->special_discount_amount);//CURRENCY_SYMBOL."&nbsp;&nbsp;".$this->detail->special_discount_amount; ?>

						<input type="hidden" name="order_total" value="<?php echo $this->detail->order_total; ?>">
						<input type="hidden" name="task" value="special_discount">
						<input type="hidden" name="view" value="order_detail">
						<input type="hidden" name="cid[]" value="<?php echo $order_id; ?>">
					</form>
				</td>
			</tr>
			<tr align="left">
				<td align="right" width="70%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_SHIPPING'); ?>:</strong>
				</td>
				<td align="right"
				    width="30%"><?php echo $producthelper->getProductFormattedPrice($this->detail->order_shipping);//CURRENCY_SYMBOL."&nbsp;&nbsp;".$this->detail->order_shipping; ?></td>
			</tr>
			<tr align="left">
				<td colspan="2" align="left">
					<hr/>
				</td>
			</tr>
			<tr align="left">
				<td align="right" width="70%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_TOTAL'); ?>:</strong></td>
				<td align="right"
				    width="30%"><?php echo $producthelper->getProductFormattedPrice($this->detail->order_total);//CURRENCY_SYMBOL."&nbsp;&nbsp;".$this->detail->order_total; ?></td>
			</tr>
			<tr align="left">
				<td colspan="2" align="left">
					<hr/>
					<br/>
					<hr/>
				</td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>
<tr>
	<td><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT');?>

	</td>
</tr>
<tr>
	<td>
		<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminFormAdd" id="adminFormAdd">
			<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
				<tr>
					<td width="30%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></td>
					<td width="20%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></td>
					<td width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></td>
					<td width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TAX'); ?></td>
					<td width="10%" align="right"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></td>
					<td width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></td>
					<td width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></td>
					<td width="5%"><?php echo JText::_('COM_REDSHOP_ACTION');?></td>
				</tr>
				<tr id="trPrd1">
					<td><input type="text" name="searchproduct1" id="searchproduct1" size="30"/>
						<input type="hidden" name="product1" id="product1" value="0"/>

						<div id="divAttproduct1"></div>
						<div id="divAccproduct1"></div>
						<div id="divUserFieldproduct1"></div>
					</td>
					<td id="tdnoteproduct1"></td>
					<td><input type="hidden" name="change_product_tmp_priceproduct1"
					           id="change_product_tmp_priceproduct1" value="0" size="10">
						<input type="text" name="prdexclpriceproduct1" style="display: none;" id="prdexclpriceproduct1"
						       onchange="changeOfflinePriceBox('product1');" value="0" size="10"></td>
					<td align="right">
						<div id="prdtaxproduct1"></div>
						<input name="taxpriceproduct1" id="taxpriceproduct1" type="hidden" value="0"/></td>
					<td align="right">
						<div id="prdpriceproduct1"></div>
						<input name="productpriceproduct1" id="productpriceproduct1" type="hidden" value="0"/></td>
					<td><input type="text" name="quantityproduct1" id="quantityproduct1" style="display: none;"
					           onchange="changeOfflineQuantityBox('product1');" value="1"
					           size="<?php echo DEFAULT_QUANTITY; ?>" maxlength="<?php echo DEFAULT_QUANTITY; ?>"></td>
					<td align="right">
						<div id="tdtotalprdproduct1"></div>
						<input name="subpriceproduct1" id="subpriceproduct1" type="hidden" value="0"/>

						<input type="hidden" name="main_priceproduct1" id="main_priceproduct1" value="0"/>
						<input type="hidden" name="tmp_product_priceproduct1" id="tmp_product_priceproduct1" value="0">
						<input type="hidden" name="product_vatpriceproduct1" id="product_vatpriceproduct1" value="0">
						<input type="hidden" name="tmp_product_vatpriceproduct1" id="tmp_product_vatpriceproduct1"
						       value="0">
						<input type="hidden" name="wrapper_dataproduct1" id="wrapper_dataproduct1" value="0">
						<input type="hidden" name="wrapper_vatpriceproduct1" id="wrapper_vatpriceproduct1" value="0">

						<input type="hidden" name="accessory_dataproduct1" id="accessory_dataproduct1" value="0">
						<input type="hidden" name="acc_attribute_dataproduct1" id="acc_attribute_dataproduct1"
						       value="0">
						<input type="hidden" name="acc_property_dataproduct1" id="acc_property_dataproduct1" value="0">
						<input type="hidden" name="acc_subproperty_dataproduct1" id="acc_subproperty_dataproduct1"
						       value="0">
						<input type="hidden" name="accessory_priceproduct1" id="accessory_priceproduct1" value="0">
						<input type="hidden" name="accessory_vatpriceproduct1" id="accessory_vatpriceproduct1"
						       value="0">

						<input type="hidden" name="attribute_dataproduct1" id="attribute_dataproduct1" value="0">
						<input type="hidden" name="property_dataproduct1" id="property_dataproduct1" value="0">
						<input type="hidden" name="subproperty_dataproduct1" id="subproperty_dataproduct1" value="0">
						<input type="hidden" name="requiedAttributeproduct1" id="requiedAttributeproduct1" value="0">
						<?php if ($tmpl)
						{ ?>
							<input type="hidden" name="tmpl" id="tmpl" value="<?php echo $tmpl ?>">
						<?php } ?>

					</td>
					<td><input type="button" name="add" id="add" style="display: none;"
					           value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
					           onclick="javascript:submitbutton('add',this.form);"/></td>
				</tr>

				<tr>
					<td colspan="8">
						<input type="hidden" name="task" value="">
						<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->detail->user_id; ?>">
						<input type="hidden" name="view" value="order_detail">
						<input type="hidden" name="return" value="order_detail">
						<input type="hidden" name="cid[]" value="<?php echo $order_id; ?>">
					</td>
				</tr>
			</table>
		</form>
	</td>
</tr>
<?php if ($this->detail->ship_method_id)
{ ?>
	<tr>
		<td>
			<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="updateshippingrate"
			      id="updateshippingrate">
				<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
					<tr>
						<td align="left">
							<?php
							echo JText::_('COM_REDSHOP_SHIPPING_NAME') ?>:
							<?php  echo $shipping_name = $carthelper->replaceShippingMethod($this->detail, "{shipping_method}"); ?>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php

							echo JText::_('COM_REDSHOP_SHIPPING_RATE_NAME') ?>:
							<?php  echo $shipping_name = $carthelper->replaceShippingMethod($this->detail, "{shipping_rate_name}"); ?>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_ORDER_SHIPPING_EXTRA_FILEDS'); ?>:
							<?php echo $ShippingExtrafields = $producthelper->getPaymentandShippingExtrafields($this->detail, 19); ?>

						</td>
					</tr>
					<tr>
						<td align="left">
							<?php echo JText::_('COM_REDSHOP_SHIPPING_MODE') ?>:
							<?php echo $this->loadTemplate('shipping'); ?>
						</td>
					</tr>
					<?php $details = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $this->detail->ship_method_id)));
					if (count($details) <= 1)
					{
						$details = explode("|", $row->ship_method_id);
					}
					$disp_style = '';
					if ($details[0] != 'plgredshop_shippingdefault_shipping_GLS')
					{
						$disp_style = "style=display:none";
					} ?>
					<tr>
						<td align="left">
							<div
								id="rs_glslocationId" <?php echo $disp_style?>><?php //echo JText::_('COM_REDSHOP_SHIPPING_MODE') ?>
								<?php
								echo $carthelper->getGLSLocation($billing->users_info_id, 'default_shipping_GLS', $this->detail->shop_id); ?>
							</div>
						</td>
					</tr>


					<tr>
						<td><input type="submit" name="add" id="add"
						           value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"/>
							<input type="hidden" name="task" value="update_shippingrates">
							<input type="hidden" name="user_id" id="user_id"
							       value="<?php echo $this->detail->user_id; ?>">
							<input type="hidden" name="view" value="order_detail">
							<input type="hidden" name="return" value="order_detail">
							<input type="hidden" name="cid[]" value="<?php echo $order_id; ?>"></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
<?php }?>
</tbody>
</table>
</td>
</tr>
<tr>
	<td colspan="2"><?php echo $this->loadTemplate('plugin');?></td>
</tr>
<?php // order status log ?>
<tr>
	<td colspan="2">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adminlist" id="log">
			<tbody>
			<tr style="background-color: #cccccc">
				<th><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_LOG'); ?></th>
			</tr>
			<tr valign="top">
				<td>
					<table class="adminlist" border="0">
						<tr>
							<td width="5%" align="center"><b><?php echo JText::_('COM_REDSHOP_NUM');?></b></td>
							<td width="15%" align="center"><b><?php echo JText::_('COM_REDSHOP_MODIFIED_DATE');?></b>
							</td>
							<td width="20%" align="center"><b><?php echo JText::_('COM_REDSHOP_STATUS');?></b></td>
							<td width="20%" align="center"><b><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS');?></b>
							</td>
							<td width="40%" align="center"><b><?php echo JText::_('COM_REDSHOP_COMMENT');?></b></td>
						</tr>
						<?php
						for ($log = 0; $log < count($log_rec); $log++):
							$log_row = & $log_rec[$log];
							?>
							<tr>
								<td width="5%" align="center"><?php echo ($log + 1); ?></td>
								<td width="15%"
								    align="center"><?php echo $config->convertDateFormat($log_row->date_changed); ?></td>
								<td width="20%" align="center"><?php echo $log_row->order_status_name; ?></td>
								<td width="20%" align="center"><?php if ($log_row->order_payment_status != "")
									{
										echo  JText::_('COM_REDSHOP_PAYMENT_STA_' . strtoupper($log_row->order_payment_status));
									} ?></td>
								<td width="40%" align="center"><?php echo urldecode($log_row->customer_note); ?></td>
							</tr>
						<?php endfor;?>
					</table>
				</td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>

<?php
// order status log end here
?>
</tbody>
</table>
<div id="divCalc"></div>
<script type="text/javascript">
	var productoptions = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&isproduct=1&json=true&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			document.getElementById('product1').value = obj.id;
			displayProductDetailInfo('product1', 0);
			displayAddbutton(obj.id, 'product1');
		}
	};
	var as_json = new bsn.AutoSuggest('searchproduct1', productoptions);

	function hideDownloadLimit(val, tid) {

		var downloadlimit = document.getElementById('limit_' + tid);
		var downloaddays = document.getElementById('days_' + tid);
		var downloadclock = document.getElementById('clock_' + tid);

		if (val.value == 1) {

			downloadlimit.style.display = 'none';
			downloaddays.style.display = 'none';
			downloadclock.style.display = 'none';
		} else {

			downloadlimit.style.display = 'table-row';
			downloaddays.style.display = 'table-row';
			downloadclock.style.display = 'table-row';
		}
	}
</script>
