<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.joom-box');

$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_redshop/assets/js/order.js');
$document->addScript(JURI::base() . 'components/com_redshop/assets/js/common.js');
$document->addScript('components/com_redshop/assets/js/json.js');

$productHelper = productHelper::getInstance();

$quotationHelper = quotationHelper::getInstance();

$order_functions = order_functions::getInstance();

$redconfig = Redconfiguration::getInstance();

$extra_field = extra_field::getInstance();
$quotation   = $this->item;

$quotation_item = $quotationHelper->getQuotationProduct($this->item->id); ?>
<script type="text/javascript">
    var rowCount = 1;

    Joomla.submitbutton = submitbutton = function (pressbutton) {
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

            if (form.quantityproduct1.value == "" || parseInt(form.quantityproduct1.value) <= 0) {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_QUANTITY'); ?>");
                form.quantityproduct1.focus();
                return false;
            }

            submitform('newQuotationItem');
            return;
        }
        if ((pressbutton == 'save') || (pressbutton == 'send') || (pressbutton == 'aplly')) {
            if (form.user_id.value == 0 && form.quotation_email.value == "") {
                alert("<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT_FOR_QUOTATION');?>");
                return false;
            }

            if (validateProductQuantity() == false) {
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

    function validateProductQuantity() {
        var valid = true;
        var quantity = document.querySelectorAll("input[name*='quantityp']");

        for (i = 0; i < quantity.length; i++) {
            if (parseInt(quantity[i].value) <= 0) {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_QUANTITY');?>");
                quantity[i].focus();
                valid = false;
                break;
            }
        }
        return valid;
    }
</script>
<form action="index.php?option=com_redshop&task=quotation.edit&id=<?php echo $this->item->id ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_QUOTATION_INFORMATION') ?></h3>
                </div>
                <div class="box-body">
                    <table border="0" cellspacing="0" cellpadding="0" class="table table-striped table-condensed">
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_QUOTATION_DATE') ?></td>
                            <td><?php echo $redconfig->convertDateFormat($this->item->quotation_cdate) ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_QUOTATION_NUMBER') ?></td>
                            <td><?php echo $this->item->number ?><?php echo $this->form->getInput('number') ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_QUOTATION_IPADDRESS') ?></td>
                            <td><?php echo $this->item->ipaddress ?><?php echo $this->form->getInput('ipaddress') ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->form->getLabel('note') ?></td>
                            <td><?php echo $this->form->getInput('note') ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->form->getLabel('customer_note') ?></td>
                            <td><?php echo $this->item->customer_note ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->form->getLabel('status') ?></td>
                            <td><?php echo $this->form->getInput('status') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ACCOUNT_INFORMATION') ?></h3>
                </div>

                <div class="box-body">
                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed">
                        <tbody>
						<?php if ($this->item->user_id != 0): ?>
							<?php $userInformation = RedshopHelperUser::getUserInformation($this->item->user_id); ?>
                            <tr>
                                <td width="25%"><?php echo JText::_('COM_REDSHOP_FIRSTNAME') ?>:</td>
                                <td><?php echo $userInformation->firstname ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
                                <td><?php echo $userInformation->lastname; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
                                <td><?php echo $userInformation->address; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
                                <td><?php echo $userInformation->zipcode; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
                                <td><?php echo $userInformation->city; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
                                <td><?php echo JText::_(RedshopHelperOrder::getCountryName($userInformation->country_code)) ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
                                <td><?php echo RedshopHelperOrder::getStateName($userInformation->state_code, $userInformation->country_code) ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
                                <td><?php echo $userInformation->phone; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
                                <td><?php echo $userInformation->user_email; ?></td>
                            </tr>
							<?php if ($userInformation->is_company): ?>
                                <tr>
                                    <td><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</td>
                                    <td><?php echo $userInformation->vat_number; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td>
                                    <td><?php echo $userInformation->tax_exempt; ?></td>
                                </tr>
								<?php $fields = RedshopHelperExtrafields::listAllFieldDisplay(8, $this->item->user_info_id); ?>
							<?php else: ?>
								<?php $fields = RedshopHelperExtrafields::listAllFieldDisplay(7, $this->item->user_info_id); ?>
							<?php endif; ?>

							<?php echo $fields; ?>
						<?php else: ?>
							<?php if (!isset($this->item->user_info_id)): ?>
								<?php $this->item->user_info_id = 0; ?>
                                <tr>
                                    <td><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
                                    <td><?php echo $this->item->user_email; ?></td>
                                </tr>
							<?php endif; ?>
						<?php endif ?>

						<?php echo RedshopHelperExtrafields::listAllFieldDisplay(16, $this->item->user_info_id, 0, $this->item->user_email); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_QUOTATION_DETAILS') ?></h3>
                </div>
                <div class="box-body">
                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
                                <th width="35%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
                                <th width="25%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></th>
                                <th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></th>
                                <th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></th>
                                <th width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY'); ?></th>
                                <th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
						<?php
						$unq = 1;

						for ($i = 0, $in = count($quotation_item); $i < $in; $i++)
						{
							$quo = $quotation_item[$i];

							if ($quo->is_giftcard == 1)
							{
								$giftcardData = $productHelper->getGiftcardData($quo->product_id);

								$actual_price   = $giftcardData->giftcard_price;
								$product_number = "";
								$section        = 13;
							}
							else
							{
								$product        = Redshop::product((int) $quo->product_id);
								$actual_price   = $product->product_price;
								$product_number = "<br/>" . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . ": ( " . $product->product_number . " ) ";
								$section        = 12;
								$vat            = 0;

								if ($quo->product_excl_price > 0)
								{
									$vat = $productHelper->getProductTax($quo->product_id, $quo->product_excl_price, $this->item->user_id);
								}

								$quo->product_price = $quo->product_excl_price + $vat;
							}

							$product_userfield = $quotationHelper->displayQuotationUserfield($quo->quotation_item_id, $section);

							$product_attribute = "<br/>" . $productHelper->makeAttributeQuotation($quo->quotation_item_id, 0, $quo->product_id);
							$product_accessory = "<br/>" . $productHelper->makeAccessoryQuotation($quo->quotation_item_id);

							$wrapper_name = "";

							if ($quo->product_wrapperid)
							{
								$wrapper = $productHelper->getWrapper($quo->product_id, $quo->product_wrapperid);

								if (count($wrapper) > 0)
								{
									$wrapper_name = $wrapper[0]->wrapper_name . " (" . $productHelper->getProductFormattedPrice($quo->wrapper_price) . ")";
								}
							}

							$product_title = $quo->product_name . $product_number . $product_attribute . $product_accessory . $product_userfield;

							$product_total = $quo->product_price * $quo->product_quantity;
							$product_tax   = ($quo->product_price - $quo->product_excl_price) * $quo->product_quantity;

							$delete_itemlink = JRoute::_('index.php?option=com_redshop&view=quotation_detail&task=deleteitem&cid[]=' . $this->item->id . '&qitemid=' . $quo->quotation_item_id);
							?>
                            <tr id="trPrd<?php echo $unq; ?>">
                                <td align="center">
                                    <a href="<?php echo $delete_itemlink; ?>"
                                       title="<?php echo JText::_('COM_REDSHOP_DELETE_QUOTATION_ITEM'); ?>">
                                        <img class="delete_item"
                                             src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>cross.png"
                                             title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
                                             alt="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
                                             onclick="submitbutton('deleteitem');">
                                    </a></td>
                                <td><?php echo $product_title; ?><input type="hidden"
                                                                        name="quotation_item_idp<?php echo $unq; ?>"
                                                                        id="quotation_item_id<?php echo $unq; ?>"
                                                                        value="<?php echo $quo->quotation_item_id; ?>"/>
                                </td>

                                <td><?php echo $wrapper_name; ?></td>
                                <td><input type="text" name="product_excl_pricep<?php echo $unq; ?>"
                                           id="product_excl_pricep<?php echo $unq; ?>" size="10" maxlength="50"
                                           value="<?php echo $productHelper->redpriceDecimal($quo->product_excl_price); ?>"
                                           onchange="getQuotationDetail('p<?php echo $unq; ?>');"></td>
                                <td align="right">
                                    <div
                                            id="tdprdpricep<?php echo $unq; ?>"><?php echo $productHelper->getProductFormattedPrice($quo->product_price); ?></div>
                                    <input type="hidden" name="product_pricep<?php echo $unq; ?>"
                                           value="<?php echo $quo->product_price; ?>"
                                           id="product_pricep<?php echo $unq; ?>"/></td>

                                <td align="center"><input type="text" name="quantityp<?php echo $unq; ?>"
                                                          value="<?php echo $quotation_item[$i]->product_quantity; ?>"
                                                          id="quantityp<?php echo $unq; ?>"
                                                          onchange="getQuotationDetail('p<?php echo $unq; ?>');"
                                                          size="<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY'); ?>"
                                                          maxlength="<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY'); ?>"/>
                                    <input type="hidden" name="hiddenqntp<?php echo $unq; ?>"
                                           value="<?php echo $quo->product_quantity; ?>"
                                           id="hiddenqntp<?php echo $unq; ?>"/></td>

                                <td align="right">
                                    <div
                                            id="tdtotalpricep<?php echo $unq; ?>"><?php echo $productHelper->getProductFormattedPrice($product_total); ?></div>
                                    <input type="hidden" name="totalpricep<?php echo $unq; ?>"
                                           value="<?php echo $product_total; ?>" id="totalpricep<?php echo $unq; ?>">
                                    <input type="hidden" name="taxpricep<?php echo $unq; ?>"
                                           value="<?php echo $product_tax; ?>" id="taxpricep<?php echo $unq; ?>"></td>
                            </tr>
							<?php $unq++;
						} ?>
                        </tbody>
                    </table>

                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table-striped table-condensed" width="100%">
                        <tbody>
                        <tr align="left">
                            <td align="right" width="85%"><strong><?php echo JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL'); ?></strong>
                            </td>

                            <td align="right">
                                <div id="divMainSubTotal"><?php echo $productHelper->getProductFormattedPrice($this->item->subtotal) ?></div>
								<?php echo $this->form->getInput('subtotal') ?>
                            </td>
                        </tr>
						<?php
						if ($this->item->total)
						{
							?>
                            <tr align="left">
                                <td align="right" width="85%">
                                    <strong><?php echo JText::_('COM_REDSHOP_DISCOUNT_EXCL_VAT') ?></strong>
                                </td>
                                <td align="right">
									<?php
									$tax                 = $this->item->tax;
									$DiscountWithotVat   = $this->item->discount;
									$DiscountspWithotVat = ($this->item->special_discount * ($this->item->subtotal + $this->item->tax)) / 100;
									$Discountvat         = 0;

									if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
									{
										$Discountvat       = ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $this->item->discount) / (1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'));
										$DiscountWithotVat = $this->item->discount - $Discountvat;
										$tax               = $tax - $Discountvat;
									}

									if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
									{
										$sp_discount         = ($this->item->special_discount * ($this->item->subtotal + $this->item->tax)) / 100;
										$Discountspvat       = ($sp_discount * (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')) / (1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'));
										$DiscountspWithotVat = $sp_discount - $Discountspvat;
										$tax                 = $tax - $Discountspvat;
									}
									?>

									<?php echo $productHelper->getProductFormattedPrice($DiscountWithotVat); ?>
                                </td>
                            </tr>

                            <tr align="left">
                                <td align="right" width="85%">
                                    <strong><?php echo JText::_('COM_REDSHOP_DISCOUNT_INCL_VAT'); ?></strong>
                                </td>
                                <td align="right">
                                    <input type="text" name="quotation_discount" size='10'
                                           value="<?php echo $productHelper->redpriceDecimal($this->item->discount); ?>"
                                           id="quotation_discount" onchange="calculateQuotationTotal();"/>
                                </td>
                            </tr>

                            <tr align="left">
                                <td align="right" width="85%">
                                    <strong><?php echo JText::_('COM_REDSHOP_QUOTATION_SPECIAL_DISCOUNT'); ?></strong></td>
                                <td align="right">


                                    <div style="float: right;">
                                        <input type="text" name="quotation_special_discount" size='10'
                                               value="<?php echo $productHelper->redpriceDecimal($this->item->special_discount); ?>"
                                               id="quotation_special_discount" onchange="calculateQuotationTotal();"/>
                                        %&nbsp;&nbsp;&nbsp;
                                    </div>
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
                                        id="divMainSubTotalwithDiscount"><?php echo $productHelper->getProductFormattedPrice($this->item->subtotal - $DiscountWithotVat - $DiscountspWithotVat); ?></div>
                                <input name="quotation_subtotal_with_discount" id="quotation_subtotal_with_discount" type="hidden"
                                       value="<?php echo($this->item->subtotal - $DiscountWithotVat - $DiscountspWithotVat); ?>"/>

                            </td>
                        </tr>
                        <tr align="left">
                            <td align="right" width="85%"><strong><?php echo JText::_('COM_REDSHOP_QUOTATION_TAX'); ?></strong></td>
                            <td align="right">
                                <div id="divMainTax"><?php echo $productHelper->getProductFormattedPrice($tax); ?></div>
                                <input name="quotation_tax" id="quotation_tax" type="hidden"
                                       value="<?php echo $this->item->tax; ?>"/></td>
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
                                        id="divMainFinalTotal"><?php echo $productHelper->getProductFormattedPrice($this->item->total); ?></div>
                                <input name="quotation_total" id="quotation_total" type="hidden"
                                       value="<?php echo $this->item->total; ?>"/>

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
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT'); ?></h3>
                </div>
                <div class="box-body">
                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table">
                        <tr>
                            <th width="30%">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></td>
                            <th width="20%">
							<?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></td>
                            <th width="10%">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></td>
                            <th width="10%" align="right">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></td>
                            <th width="5%">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></td>
                            <th width="10%" align="right">
							<?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></td>
                            <th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
                        </tr>
                        <tr>
                            <td><?php
								echo JHTML::_('redshopselect.search', '', 'product1',
									array(
										'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
										'select2.options'     => array(
											'events' => array('select2-selecting' => 'function(e) {
                                            document.getElementById(\'product1\').value = e.object.id;
                                            displayProductDetailInfo(\'product1\', 0);
                                            addItembutton(\'product1\');}')
										)
									)
								);
								?>
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
                                       size="<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY'); ?>"
                                       maxlength="<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY'); ?>"></td>
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
                </div>
            </div>
        </div>
    </div>
	<?php echo $this->form->getInput('id') ?>
	<?php echo $this->form->getInput('user_id') ?>
	<?php echo $this->form->getInput('user_info_id') ?>
	<?php echo $this->form->getInput('user_email') ?>
	<?php echo $this->form->getInput('quotation_mdate') ?>
	<?php echo $this->form->getInput('order_id') ?>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="quotation"/>
</form>

<div id="divCalc"></div>
