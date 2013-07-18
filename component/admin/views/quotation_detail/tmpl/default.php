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
$producthelper   = new producthelper;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quotation.php';
$quotationHelper = new quotationHelper;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
$order_functions = new order_functions;

$redconfig       = new Redconfiguration;

$option          = JRequest::getVar('option');
$model           = $this->getModel('quotation_detail');

$extra_field     = new extra_field;
$quotation       = $this->quotation;

$uri             = JURI::getInstance();
$url             = $uri->root();

$quotation_item = $quotationHelper->getQuotationProduct($quotation->quotation_id);    ?>
<script type="text/javascript">
	var rowCount = 1;
	var qrowCount = <?php echo count($quotation_item);?>;

	Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (pressbutton == 'add') {
			if (form.product1.value == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_PRODUCT');?>");
				return;
			}
			submitform('newQuotationItem');
			return;
		}
		if ((pressbutton == 'save') || (pressbutton == 'send')) {
			if (form.user_id.value == 0 && form.quotation_email.value == "") {
				alert('<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT_FOR_QUOTATION');?>');
				return false;
			}
			if (form.quotation_total.value == 0) {
				if (!confirm("<?php echo JText::_('COM_REDSHOP_CONFIRM_WITH_QUOTATION_TOTAL_ZERO');?>")) {
					return false;
				}
			}
		}
		submitform(pressbutton);
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
<tbody>
<?php if (!$quotation->quotation_id)
{
	$edit_addlink = JRoute::_('index.php?tmpl=component&option=' . $option . '&view=quotation_detail&layout=account');
	?>
	<tr>
		<td align="right">
			<!--<a href="javascript:#('');" >-->
			<a class="modal" href="<?php echo $edit_addlink; ?>"
			   rel="{handler: 'iframe', size: {x: 670, y: 500}}"><?php echo JText::_('COM_REDSHOP_ADD_USER'); ?></a>
		</td>
	</tr>
<?php
}
?>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
			<tr style="background-color: #cccccc">
				<th colspan="2" align="left"><?php echo JText::_('COM_REDSHOP_QUOTATION_INFORMATION'); ?></th>
			</tr>
			<tr>
				<td width="25%"><?php echo JText::_('COM_REDSHOP_QUOTATION_DATE');?></td>
				<td><?php echo $redconfig->convertDateFormat($quotation->quotation_cdate);?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_QUOTATION_NUMBER');?></td>
				<td><?php echo $quotation->quotation_number;?><input name="quotation_number" id="quotation_number"
				                                                     type="hidden"
				                                                     value="<?php echo $quotation->quotation_number; ?>"/>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_QUOTATION_IPADDRESS');?></td>
				<td><?php echo $quotation->quotation_ipaddress;?><input name="quotation_ipaddress"
				                                                        id="quotation_ipaddress" type="hidden"
				                                                        value="<?php echo $quotation->quotation_ipaddress; ?>"/>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_QUOTATION_NOTE');?></td>
				<td><textarea cols="50" rows="5" name="quotation_note"
				              id="quotation_note"><?php echo $quotation->quotation_note;?></textarea></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_QUOTATION_STATUS'); ?></td>
				<td><?php echo $this->lists['quotation_status'];?></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td id="userinforesult">
		<table width="100%" class="adminlist">
			<tbody>

			<tr style="background-color: #cccccc">
				<th colspan="2"><?php echo JText::_('COM_REDSHOP_ACCOUNT_INFORMATION'); ?></th>
			</tr>
			<?php
			if ($quotation->user_id != 0)
			{
				?>
				<tr>
					<td width="25%"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</td>
					<td><?php echo $this->quotationuser->firstname; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
					<td><?php echo $this->quotationuser->lastname; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
					<td><?php echo $this->quotationuser->address; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
					<td><?php echo $this->quotationuser->zipcode; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
					<td><?php echo $this->quotationuser->city; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
					<td><?php echo JText::_($order_functions->getCountryName($this->quotationuser->country_code)); ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
					<td><?php echo $order_functions->getStateName($this->quotationuser->state_code, $this->quotationuser->country_code); ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
					<td><?php echo $this->quotationuser->phone; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
					<td><?php echo $this->quotationuser->user_email; ?></td>
				</tr>
				<?php
				if ($this->quotationuser->is_company)
				{
					?>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</td>
						<td><?php echo $this->quotationuser->vat_number; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td>
						<td><?php echo $this->quotationuser->tax_exempt; ?></td>
					</tr>
					<?php        $fields = $extra_field->list_all_field_display(8, $quotation->user_info_id);
				}
				else
				{
					$fields = $extra_field->list_all_field_display(7, $quotation->user_info_id);
				}

				echo $fields;
			}
			else
			{
				if (!isset($quotation->user_info_id))
				{
					$quotation->user_info_id = 0;
				}?>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
					<td><?php echo $quotation->user_email; ?></td>
				</tr>
			<?php
			}

			echo $fields = $extra_field->list_all_field_display(16, $quotation->user_info_id, 0, $quotation->user_email); ?>

			</tbody>
		</table>
	</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="adminlist">
<tbody>
<tr style="background-color: #cccccc">
	<th align="left"><?php echo JText::_('COM_REDSHOP_QUOTATION_DETAILS'); ?></th>
</tr>
<?php if (!$quotation->quotation_id)
{
	?>
	<tr>
		<td align="right"><a href="javascript:#('tblproductRow');">&nbsp;</a></td>
	</tr>
<?php
}
?>
<tr>
	<td>
		<table class="adminlist" id="tblproductRow" width="100%">
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
				<th width="35%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
				<th width="25%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></th>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY'); ?></th>
				<th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></th>
			</tr>
			<?php
			$unq = 1;

			for ($i = 0; $i < count($quotation_item); $i++)
			{
				$quo = & $quotation_item[$i];

				if ($quo->is_giftcard == 1)
				{
					$giftcardData = $producthelper->getGiftcardData($quo->product_id);

					$actual_price = $giftcardData->giftcard_price;
					$product_number = "";
					$section = 13;
				}
				else
				{
					$product = $producthelper->getProductById($quo->product_id);
					$actual_price = $product->product_price;
					$product_number = "<br/>" . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . ": ( " . $product->product_number . " ) ";
					$section = 12;
					$vat = 0;

					if ($quo->product_excl_price > 0)
					{
						$vat = $producthelper->getProductTax($quo->product_id, $quo->product_excl_price, $quotation->user_id);
					}

					$quo->product_price = $quo->product_excl_price + $vat;
				}

				$product_userfield = $quotationHelper->displayQuotationUserfield($quo->quotation_item_id, $section);

				$product_attribute = "<br/>" . $producthelper->makeAttributeQuotation($quo->quotation_item_id, 0, $quo->product_id);
				$product_accessory = "<br/>" . $producthelper->makeAccessoryQuotation($quo->quotation_item_id);

				$wrapper_name = "";

				if ($quo->product_wrapperid)
				{
					$wrapper = $producthelper->getWrapper($quo->product_id, $quo->product_wrapperid);

					if (count($wrapper) > 0)
					{
						$wrapper_name = $wrapper[0]->wrapper_name . " (" . $producthelper->getProductFormattedPrice($quo->wrapper_price) . ")";
					}
				}

				$product_title = $quo->product_name . $product_number . $product_attribute . $product_accessory . $product_userfield;

				$product_total = $quo->product_price * $quo->product_quantity;
				$product_tax = ($quo->product_price - $quo->product_excl_price) * $quo->product_quantity;

				$delete_itemlink = JRoute::_('index.php?option=' . $option . '&view=quotation_detail&task=deleteitem&cid[]=' . $quotation->quotation_id . '&qitemid=' . $quo->quotation_item_id);
				?>
				<tr id="trPrd<?php echo $unq; ?>">
					<td align="center">
						<a href="<?php echo $delete_itemlink; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_DELETE_QUOTATION_ITEM'); ?>">
							<img class="delete_item"
							     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>cross.jpg"
							     title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
							     onclick="submitbutton('deleteitem');">
						</a></td>
					<td><?php echo $product_title;?><input type="hidden"
					                                       name="quotation_item_idp<?php echo $unq; ?>"
					                                       id="quotation_item_id<?php echo $unq; ?>"
					                                       value="<?php echo $quo->quotation_item_id; ?>"/>
					</td>

					<td><?php echo $wrapper_name;?></td>
					<td><input type="text" name="product_excl_pricep<?php echo $unq; ?>"
					           id="product_excl_pricep<?php echo $unq; ?>" size="10" maxlength="50"
					           value="<?php echo $producthelper->redpriceDecimal($quo->product_excl_price); ?>"
					           onchange="getQuotationDetail('p<?php echo $unq; ?>');"></td>
					<td align="right">
						<div
							id="tdprdpricep<?php echo $unq; ?>"><?php echo $producthelper->getProductFormattedPrice($quo->product_price);?></div>
						<input type="hidden" name="product_pricep<?php echo $unq; ?>"
						       value="<?php echo $quo->product_price; ?>"
						       id="product_pricep<?php echo $unq; ?>"/></td>

					<td align="center"><input type="text" name="quantityp<?php echo $unq; ?>"
					                          value="<?php echo $quotation_item[$i]->product_quantity; ?>"
					                          id="quantityp<?php echo $unq; ?>"
					                          onchange="getQuotationDetail('p<?php echo $unq; ?>');"
					                          size="<?php echo DEFAULT_QUANTITY; ?>"
					                          maxlength="<?php echo DEFAULT_QUANTITY; ?>"/>
						<input type="hidden" name="hiddenqntp<?php echo $unq; ?>"
						       value="<?php echo $quo->product_quantity; ?>"
						       id="hiddenqntp<?php echo $unq; ?>"/></td>

					<td align="right">
						<div
							id="tdtotalpricep<?php echo $unq; ?>"><?php echo $producthelper->getProductFormattedPrice($product_total);?></div>
						<input type="hidden" name="totalpricep<?php echo $unq; ?>"
						       value="<?php echo $product_total; ?>" id="totalpricep<?php echo $unq; ?>">
						<input type="hidden" name="taxpricep<?php echo $unq; ?>"
						       value="<?php echo $product_tax; ?>" id="taxpricep<?php echo $unq; ?>"></td>
				</tr>
				<?php    $unq++;
			}    ?>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
			<tbody>
			<tr align="left">
				<td align="right" width="85%"><strong><?php echo JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL'); ?></strong>
				</td>

				<td align="right">
					<div
						id="divMainSubTotal"><?php echo $producthelper->getProductFormattedPrice($quotation->quotation_subtotal);?></div>
					<input name="quotation_subtotal" id="quotation_subtotal" type="hidden"
					       value="<?php echo $quotation->quotation_subtotal; ?>"/>

				</td>
			</tr>
			<?php
			if ($quotation->quotation_total)
			{
				?>
				<tr align="left">
					<td align="right" width="85%">
						<strong><?php echo JText::_('COM_REDSHOP_QUOTATION_DISCOUNT'); ?></strong></td>
					<td align="right">

						<div id="divMainDiscount" style="float: right;">

							<?php
							$tax = $quotation->quotation_tax;
							$DiscountWithotVat = $quotation->quotation_discount;
							$DiscountspWithotVat = ($quotation->quotation_special_discount * ($quotation->quotation_subtotal + $quotation->quotation_tax)) / 100;

							if (VAT_RATE_AFTER_DISCOUNT)
							{
								$Discountvat       = (VAT_RATE_AFTER_DISCOUNT * $quotation->quotation_discount) / (1 + VAT_RATE_AFTER_DISCOUNT);
								$DiscountWithotVat = $quotation->quotation_discount - $Discountvat;
								$tax               = $tax - $Discountvat;
							}

							if (VAT_RATE_AFTER_DISCOUNT)
							{
								$sp_discount         = ($quotation->quotation_special_discount * ($quotation->quotation_subtotal + $quotation->quotation_tax)) / 100;
								$Discountspvat       = ($sp_discount * VAT_RATE_AFTER_DISCOUNT) / (1 + VAT_RATE_AFTER_DISCOUNT);
								$DiscountspWithotVat = $sp_discount - $Discountspvat;
								$tax                 = $tax - $Discountspvat;
							}
							?>
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_EXCL_VAT');?>
							<br/><?php echo $producthelper->getProductFormattedPrice($DiscountWithotVat);?></div>
						<div style="float: left;">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_INCL_VAT');?>
							<br/><input type="text" name="quotation_discount" size='10'
							            value="<?php echo $producthelper->redpriceDecimal($quotation->quotation_discount); ?>"
							            id="quotation_discount" onchange="calculateQuotationTotal();"/>
						</div>
					</td>
				</tr>
				<tr align="left">
					<td align="right" width="85%">
						<strong><?php echo JText::_('COM_REDSHOP_QUOTATION_SPECIAL_DISCOUNT'); ?></strong></td>
					<td align="right">

						<div id="divMainSpecialDiscount" style="float: right;">
							<?php
							$special_discount_amount = ($quotation->quotation_subtotal * $quotation->quotation_special_discount) / 100;
							echo $producthelper->getProductFormattedPrice($DiscountspWithotVat);
							?>
						</div>
						<div style="float: right;">
							<input type="text" name="quotation_special_discount" size='10'
							       value="<?php echo $producthelper->redpriceDecimal($quotation->quotation_special_discount); ?>"
							       id="quotation_special_discount" onchange="calculateQuotationTotal();"/>
							%&nbsp;&nbsp;&nbsp;</div>
					</td>
				</tr>

			<?php
			}
		?>

			<tr align="left">
				<td align="right" width="85%">
					<strong><?php echo JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL_WITH_DISCOUNT'); ?></strong></td>

				<td align="right">
					<div
						id="divMainSubTotalwithDiscount"><?php echo $producthelper->getProductFormattedPrice($quotation->quotation_subtotal - $DiscountWithotVat - $DiscountspWithotVat);?></div>
					<input name="quotation_subtotal_with_discount" id="quotation_subtotal_with_discount" type="hidden"
					       value="<?php echo ($quotation->quotation_subtotal - $DiscountWithotVat - $DiscountspWithotVat); ?>"/>

				</td>
			</tr>
			<tr align="left">
				<td align="right" width="85%"><strong><?php echo JText::_('COM_REDSHOP_QUOTATION_TAX'); ?></strong></td>
				<td align="right">
					<div id="divMainTax"><?php echo $producthelper->getProductFormattedPrice($tax);?></div>
					<input name="quotation_tax" id="quotation_tax" type="hidden"
					       value="<?php echo $quotation->quotation_tax; ?>"/></td>
			</tr>
			<tr>
				<td colspan="2">
					<hr/>
				</td>
			</tr>
			<tr align="left">
				<td align="right"><strong><?php echo JText::_('COM_REDSHOP_QUOTATION_TOTAL'); ?></strong></td>
				<td align="right">
					<div
						id="divMainFinalTotal"><?php echo $producthelper->getProductFormattedPrice($quotation->quotation_total);?></div>
					<input name="quotation_total" id="quotation_total" type="hidden"
					       value="<?php echo $quotation->quotation_total; ?>"/>

					<input type="hidden" name="Discountvat" id="Discountvat" value="<?php echo $Discountvat ?>">
					<input type="hidden" name="DiscountWithoutVat" id="DiscountWithoutVat"
					       value="<?php echo $DiscountWithotVat ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr/>
				</td>
			</tr>
		</table>
	</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
	<td><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT');?></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
			<tr>
				<th width="30%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></td>
				<th width="20%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></td>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></td>
				<th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></td>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></td>
				<th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></td>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
			</tr>
			<tr>
				<td><input type="text" name="searchproduct1" id="searchproduct1" size="30"/>
					<input type="hidden" name="product1" id="product1" value="0"/>

					<div id="divAttproduct1"></div>
					<div id="divAccproduct1"></div>
					<div id="divUserFieldproduct1"></div>
				</td>
				<td id="tdnoteproduct1"></td>
				<td><input type="text" name="prdexclpriceproduct1" id="prdexclpriceproduct1" style="display: none;"
				           onchange="changeOfflinePriceBox('product1');" value="0" size="10"></td>
				<!-- <td align="right"><div id="prdtaxproduct1"></div><input name="taxpriceproduct1" id="taxpriceproduct1" type="hidden" value="0" /></td> -->
				<td align="right">
					<div id="prdpriceproduct1"></div>
					<input name="productpriceproduct1" id="productpriceproduct1" type="hidden" value="0"/><input
						name="taxpriceproduct1" id="taxpriceproduct1" type="hidden" value="0"/></td>
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
					<input type="hidden" name="acc_attribute_dataproduct1" id="acc_attribute_dataproduct1" value="0">
					<input type="hidden" name="acc_property_dataproduct1" id="acc_property_dataproduct1" value="0">
					<input type="hidden" name="acc_subproperty_dataproduct1" id="acc_subproperty_dataproduct1"
					       value="0">
					<input type="hidden" name="accessory_priceproduct1" id="accessory_priceproduct1" value="0">
					<input type="hidden" name="accessory_vatpriceproduct1" id="accessory_vatpriceproduct1" value="0">

					<input type="hidden" name="attribute_dataproduct1" id="attribute_dataproduct1" value="0">
					<input type="hidden" name="property_dataproduct1" id="property_dataproduct1" value="0">
					<input type="hidden" name="subproperty_dataproduct1" id="subproperty_dataproduct1" value="0">
					<input type="hidden" name="requiedAttributeproduct1" id="requiedAttributeproduct1" value="0">
				</td>
				<td><input type="submit" name="add" id="add" style="display: none;"
				           value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>" onclick="return submitbutton('add');"/>
				</td>
			</tr>
		</table>
	</td>
</tr>
</tbody>
</table>
<input type="hidden" name="cid[]" value="<?php echo $quotation->quotation_id; ?>"/>
<input type="hidden" name="user_id" id="user_id" value="<?php echo $quotation->user_id; ?>"/>
<input type="hidden" name="user_info_id" value="<?php echo $quotation->user_info_id; ?>"/>
<input type="hidden" name="quotation_email" value="<?php echo $quotation->user_email; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="option" value="<?php echo $option; ?>"/>
<input type="hidden" name="view" value="quotation_detail"/>
<input type="hidden" name="quotation_mdate" value="<?php echo time(); ?>"/>

</form>

<div id="divCalc"></div>
<script type="text/javascript">
	var productoptions = {
		script: "index.php?option=com_redshop&view=search&isproduct=1&tmpl=component&json=true&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			document.getElementById('product1').value = obj.id;
			displayProductDetailInfo('product1', 0);
			addItembutton('product1');
		}
	};
	var as_json = new bsn.AutoSuggest('searchproduct1', productoptions);
</script>
