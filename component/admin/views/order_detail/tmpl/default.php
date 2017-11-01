<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.joom-box');

JPluginHelper::importPlugin('redshop_shipping');
$dispatcher   = JDispatcher::getInstance();

$productHelper    = productHelper::getInstance();
$cartHelper       = rsCarthelper::getInstance();
$orderFunctions   = order_functions::getInstance();
$redHelper        = redhelper::getInstance();
$extraFieldHelper = extra_field::getInstance();
$shippingHelper   = shipping::getInstance();
$config           = Redconfiguration::getInstance();

$uri = JUri::getInstance();
$url = $uri::root();


$tmpl            = JFactory::getApplication()->input->getCmd('tmpl');
$model           = $this->getModel('order_detail');
$session         = JFactory::getSession();
$billing         = $this->billing;
$shipping        = $this->shipping;
$isCompany       = $billing->is_company;
$orderId         = $this->detail->order_id;
$products        = RedshopHelperOrder::getOrderItemDetail($orderId);
$orderStatusLogs = RedshopEntityOrder::getInstance($orderId)->getStatusLog();

if (!$shipping)
{
    $shipping = $billing;
}
$session->set('shipp_users_info_id', $shipping->users_info_id);

# get Downloadable Products
$downloadProducts     = RedshopHelperOrder::getDownloadProduct($orderId);
$totalDownloadProduct = count($downloadProducts);
$dproducts            = array();

for ($t = 0; $t < $totalDownloadProduct; $t++)
{
    $downloadProduct = $downloadProducts[$t];

    $dproducts[$downloadProduct->product_id][$downloadProduct->download_id] = $downloadProduct;
}
?>
<script type="text/javascript">
    var rowCount = 1;

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

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text"><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?></span>
                <span class="info-box-number"><?php echo RedshopHelperDatetime::convertDateFormat($this->detail->cdate); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-money" aria-hidden="true"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text"><?php echo JText::_('COM_REDSHOP_ORDER_TOTAL'); ?></span>
                <span class="info-box-number"><?php echo $productHelper->getProductFormattedPrice($this->detail->order_total); ?></span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text"><?php echo JText::_('COM_REDSHOP_PRODUCTS'); ?></span>
                <span class="info-box-number"><?php echo count($products) ?></span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-area-chart" aria-hidden="true"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?></span>
                <span class="info-box-number"><?php echo $this->detail->order_payment_status ?></span>
            </div>
        </div>
    </div>
</div>

<div class="tab-content">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION'); ?></h3>
                </div>
                <div class="box-body">
                    <form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
                        <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed no-margin">
                            <tbody>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?>:</td>
                                <td><?php echo $orderId; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ORDER_NUMBER'); ?>:</td>
                                <td><?php echo $this->detail->order_number; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_INVOICE_NUMBER'); ?>:</td>
                                <td><?php echo $this->detail->invoice_number; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?>:</td>
                                <td><?php echo $config->convertDateFormat($this->detail->cdate); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_METHOD'); ?>:</td>
                                <td><?php echo JText::_($this->payment_detail->order_payment_name); ?>
                                    <?php if (count($model->getccdetail($orderId)) > 0): ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_redshop&view=order_detail&task=ccdetail&cid[]=' . $orderId); ?>"
                                           class="joom-box btn btn-primary"
                                           rel="{handler: 'iframe', size: {x: 550, y: 200}}"><?php echo JText::_('COM_REDSHOP_CLICK_TO_VIEW_CREDIT_CARD_DETAIL'); ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_EXTRA_FILEDS'); ?>:</td>
                                <td><?php echo $PaymentExtrafields = $productHelper->getPaymentandShippingExtrafields($this->detail, 18); ?>

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
                            <tr>
                                <td align="left"><?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?>:</td>
                                <td>
                                    <?php
                                    $arr_discount_type = array();
                                    $arr_discount      = explode('@', $this->detail->discount_type);
                                    $discount_type     = '';
                                    for ($d = 0, $dn = count($arr_discount); $d < $dn; $d++)
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
                                    <?php echo $discount_type; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><h3><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_CHANGE') ?></h3></td>
                            </tr>
                            <?php //if($is_company){?>
                            <tr>
                                <td><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER'); ?>:</td>
                                <td><input class="inputbox" name="requisition_number" id="requisition_number"
                                           type="text"
                                           value="<?php echo $this->detail->requisition_number; ?>"/></td>
                            </tr>
                            <?php //}?>
                            <?php
                            $partialPaid        = $orderFunctions->getOrderPartialPayment($orderId);
                            $sendMailToCustomer = 0;
                            if (Redshop::getConfig()->get('SEND_MAIL_TO_CUSTOMER'))
                            {
                                $sendMailToCustomer = "checked";
                            }
                            $linkUpdate = JRoute::_('index.php?option=com_redshop&view=order&task=update_status&return=order_detail&order_id[]=' . $orderId);
                            ?>
                            <tr>
                                <td>
                                    <?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?>
                                </td>
                                <td>
                                    <?php echo RedshopHelperOrder::getStatusList('status', $this->detail->order_status, "class=\"form-control\" size=\"1\" ") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?>
                                </td>
                                <td>
                                    <?php echo RedshopHelperOrder::getPaymentStatusList('order_paymentstatus', $this->detail->order_payment_status, "class=\"form-control\" size=\"1\" "); ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <?php echo JText::_('COM_REDSHOP_COMMENT'); ?>
                                </td>
                                <td>
                                        <textarea cols="50" rows="5" class="form-control"
                                                  name="customer_note"><?php echo $this->detail->customer_note; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <label class="checkbox inline">
                                        <input type="checkbox" <?php echo $sendMailToCustomer; ?> value="true"
                                               name="order_sendordermail"
                                               id="order_sendordermail"/><?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input class="button btn btn-primary" onclick="this.form.submit();" name="order_status"
                                           value="<?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON'); ?>" type="button">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php if ($tmpl): ?>
                            <input type="hidden" name="tmpl" value="<?php echo $tmpl ?>">
                        <?php endif; ?>
                        <input type="hidden" name="option" value="com_redshop"/>
                        <input type="hidden" name="view" value="order"/>
                        <input type="hidden" name="task" value="update_status"/>
                        <input type="hidden" name="return" value="order_detail"/>
                        <input type="hidden" name="order_id[]" value="<?php echo $orderId; ?>"/>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php if ($this->detail->ship_method_id) : ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="index.php?option=com_redshop" method="post" name="updateshippingrate"
                              id="updateshippingrate">
                            <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed no-margin">
                                <tr>
                                    <td align="left">
                                        <?php echo JText::_('COM_REDSHOP_SHIPPING_NAME') ?>:
                                    </td>
                                    <td>
                                        <?php echo $shipping_name = RedshopHelperShippingTag::replaceShippingMethod($this->detail, "{shipping_method}"); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        <?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_NAME') ?>:
                                    </td>
                                    <td>
                                        <?php echo $shipping_name = RedshopHelperShippingTag::replaceShippingMethod($this->detail, "{shipping_rate_name}"); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo JText::_('COM_REDSHOP_ORDER_SHIPPING_EXTRA_FILEDS'); ?>:
                                    </td>
                                    <td>
                                        <?php echo $ShippingExtrafields = $productHelper->getPaymentandShippingExtrafields($this->detail, 19); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        <?php echo JText::_('COM_REDSHOP_SHIPPING_MODE') ?>:
                                    </td>
                                    <td>
                                        <?php echo $this->loadTemplate('shipping'); ?>
                                    </td>
                                </tr>
                                <?php
                                $details = RedshopShippingRate::decrypt($this->detail->ship_method_id);

                                if (count($details) <= 1)
                                {
                                    $details = explode("|", $row->ship_method_id);
                                }

                                $disp_style = '';

                                if ($details[0] != 'plgredshop_shippingdefault_shipping_gls')
                                {
                                    $disp_style = "style=display:none";
                                }
                                ?>
                                <tr>
                                    <td align="left">
                                        <div id="rs_glslocationId" <?php echo $disp_style ?>>
                                            <?php $result = $dispatcher->trigger('getGLSLocation', array($shipping->users_info_id, 'default_shipping_gls', $this->detail->shop_id)); ?>
											<?php echo $result[0]; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php if ($this->detail->track_no)
                                { ?>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_TRACKING_NUMBER'); ?>:</td>
                                        <td><?php echo $this->detail->track_no; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                            <input type="submit" name="add" id="add" class="btn btn-primary"
                                   value="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"/>
                            <input type="hidden" name="task" value="update_shippingrates">
                            <input type="hidden" name="user_id" id="user_id"
                                   value="<?php echo $this->detail->user_id; ?>">
                            <input type="hidden" name="view" value="order_detail">
                            <input type="hidden" name="return" value="order_detail">
                            <input type="hidden" name="cid[]" value="<?php echo $orderId; ?>">
                        </form>

                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION'); ?></h3>
                            <?php if (!$tmpl)
                            { ?>
                                <a class="joom-box btn btn-primary"
                                   href="index.php?tmpl=component&option=com_redshop&view=order_detail&layout=billing&cid[]=<?php echo $orderId; ?>"
                                   rel="{handler: 'iframe', size: {x: 500, y: 450}}"><?php echo JText::_('COM_REDSHOP_EDIT'); ?></a>
                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <table class="adminlist table table-striped no-margin">
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</td>
                                    <td><?php echo $billing->firstname; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
                                    <td><?php echo $billing->lastname; ?></td>
                                </tr>
                                <?php if ($isCompany)
                                { ?>
                                    <tr>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_COMPANY'); ?>:</td>
                                        <td><?php echo $billing->company_name; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
                                    <td><?php echo $billing->address; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
                                    <td><?php echo $billing->zipcode; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
                                    <td><?php echo $billing->city; ?></td>
                                </tr>

                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
                                    <td><?php echo ($billing->country_code) ? JText::_(RedshopHelperOrder::getCountryName($billing->country_code)) : ''; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
                                    <td><?php echo ($billing->state_code) ? RedshopHelperOrder::getStateName($billing->state_code, $billing->country_code) : ''; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
                                    <td><?php echo $billing->phone; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
                                    <td>
                                        <a href="mailto:<?php echo $billing->user_email; ?>"><?php echo $billing->user_email; ?></a>
                                    </td>
                                </tr>
                                <?php
                                if ($isCompany)
                                {
                                    ?>
                                    <tr>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</td>
                                        <td><?php echo $billing->vat_number; ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td>
                                        <td><?php echo $billing->tax_exempt; ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</td>
                                        <td><?php echo $billing->ean_number; ?></td>
                                    </tr>
                                    <?php $fields = $extraFieldHelper->list_all_field_display(8, $billing->users_info_id);
                                }
                                else
                                {
                                    $fields = $extraFieldHelper->list_all_field_display(7, $billing->users_info_id);
                                }
                                echo $fields;
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'); ?></h3>
                            <?php if (!$tmpl)
                            { ?>
                                <a class="joom-box btn btn-primary"
                                   href="index.php?tmpl=component&option=com_redshop&view=order_detail&layout=shipping&cid[]=<?php echo $orderId; ?>"
                                   rel="{handler: 'iframe', size: {x: 500, y: 450}}"><?php echo JText::_('COM_REDSHOP_EDIT'); ?></a>
                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <table class="adminlist table table-striped no-margin">
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</td>
                                    <td><?php echo $shipping->firstname; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
                                    <td><?php echo $shipping->lastname; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
                                    <td><?php echo $shipping->address; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
                                    <td><?php echo $shipping->zipcode; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
                                    <td><?php echo $shipping->city; ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
                                    <td><?php echo JText::_($orderFunctions->getCountryName($shipping->country_code)); ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
                                    <td><?php echo $orderFunctions->getStateName($shipping->state_code, $shipping->country_code); ?></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
                                    <td><?php echo $shipping->phone; ?></td>
                                </tr>
                                <?php

                                if ($isCompany)
                                {
                                    $fields = $extraFieldHelper->list_all_field_display(15, $shipping->users_info_id);
                                }
                                else
                                {
                                    $fields = $extraFieldHelper->list_all_field_display(14, $shipping->users_info_id);
                                }
                                echo $fields; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3><?php echo JText::_('COM_REDSHOP_ORDER_DETAILS'); ?></h3>
                </div>
                <div class="box-body">
                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed">
                        <tbody>
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed" width="100%">
                                    <tr>
                                        <th width="20%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
                                        <th width="15%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></th>
                                        <th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></th>
                                        <th width="5%"><?php echo JText::_('COM_REDSHOP_TAX'); ?></th>
                                        <th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></th>
                                        <th width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></th>
                                        <th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></th>
                                        <th width="20%"><?php echo JText::_('COM_REDSHOP_STATUS'); ?></th>
                                        <th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
                                    </tr>
                                </table>
                            </td>
                            <?php if ($totalDownloadProduct > 0) echo '<td>' . JText::_('COM_REDSHOP_DOWNLOAD_SETTING') . '</td>'; ?>
                        </tr>
                        <?php
                        $ordervolume       = 0;
                        $cart              = array();
                        $subtotal_excl_vat = 0;

                        for ($i = 0, $in = count($products); $i < $in; $i++)
                        {
                            $cart[$i]['product_id'] = $products[$i]->product_id;
                            $cart[$i]['quantity']   = $products[$i]->product_quantity;
                            $quantity               = $products[$i]->product_quantity;
                            $product_id             = $products[$i]->product_id;

                            if ($productdetail = $productHelper->getProductById($product_id))
                            {
                                $ordervolume = $ordervolume + $productdetail->product_volume;
                            }

                            $order_item_id   = $products[$i]->order_item_id;
                            $order_item_name = $products[$i]->order_item_name;
                            $order_item_sku  = $products[$i]->order_item_sku;
                            $wrapper_id      = $products[$i]->wrapper_id;

                            $p_userfield      = $productHelper->getuserfield($order_item_id);
                            $subscribe_detail = $model->getUserProductSubscriptionDetail($order_item_id);
                            $catId            = $productHelper->getCategoryProduct($product_id);
                            $res              = $productHelper->getSection("category", $catId);
                            $cname            = '';

                            if (count($res) > 0)
                            {
                                $cname = $res->name;
                                $clink = JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catId);
                                $cname = "<a href='" . $clink . "'>" . $cname . "</a>";
                            }

                            $subtotal_excl_vat += $products[$i]->product_item_price_excl_vat * $quantity;
                            $vat               = ($products[$i]->product_item_price - $products[$i]->product_item_price_excl_vat);

                            // Make sure this variable is object before we can use it
                            if (is_object($productdetail))
                            {
                                // Generate frontend link
                                $itemData  = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $productdetail->product_id);
                                $catIdMain = $productdetail->cat_in_sefurl;

                                if (count($itemData) > 0)
                                {
                                    $pItemid = $itemData->id;
                                }
                                else
                                {
                                    $objhelper = redhelper::getInstance();
                                    $pItemid   = RedshopHelperUtility::getItemId($productdetail->product_id, $catIdMain);
                                }

                                $productFrontendLink = JUri::root();
                                $productFrontendLink .= 'index.php?option=com_redshop';
                                $productFrontendLink .= '&view=product&pid=' . $productdetail->product_id;
                                $productFrontendLink .= '&cid=' . $catIdMain;
                                $productFrontendLink .= '&Itemid=' . $pItemid;
                            }
                            else
                            {
                                $productFrontendLink = '#';
                            }

                            $makeAttributeOrder = $productHelper->makeAttributeOrder($order_item_id);

                            $displayAttribute = $makeAttributeOrder->product_attribute;

                            ?>
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped table-condensed"
                                           width="100%">
                                        <tr>
                                            <td>
                                                <form action="index.php?option=com_redshop" method="post"
                                                      name="itemForm<?php echo $order_item_id; ?>" id="itemForm<?php echo $order_item_id; ?>">
                                                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table table-striped"
                                                           width="100%">
                                                        <tr>
                                                            <td width="20%">
                                                                <div class="order_product_detail" id="order_product_detail_<?php echo $order_item_id ?>">
                                                                    <a href="<?php echo $productFrontendLink; ?>" target="_blank">
                                                                        <?php echo $order_item_name ?>
                                                                    </a>
                                                                    <div>
                                                                        <span class="small">SKU:</span>&nbsp;
                                                                        <span><?php echo $order_item_sku ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="order_product_detail_more">
                                                                    <?php if (!empty($p_userfield)): ?>
                                                                        <div class="order_product_userfield"><?php echo $p_userfield ?></div>
                                                                    <?php endif; ?>
                                                                    <?php echo $displayAttribute ?>
                                                                    <div class="order_product_category">
                                                                        <span class="small">Category:</span>
                                                                        <?php echo $cname ?>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td width="15%">
                                                                <?php
                                                                echo $products[$i]->product_accessory . "<br/>" . $products[$i]->discount_calc_data;

                                                                if ($wrapper_id)
                                                                {
                                                                    $wrapper = $productHelper->getWrapper($product_id, $wrapper_id);
                                                                    echo "<br>" . JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name . "(" . $products[$i]->wrapper_price . ")";
                                                                }

                                                                if ($subscribe_detail)
                                                                {
                                                                    $subscription_detail   = $model->getProductSubscriptionDetail($product_id, $subscribe_detail->subscription_id);
                                                                    $selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;
                                                                    echo JText::_('COM_REDSHOP_SUBSCRIPTION') . ': ' . $selected_subscription;
                                                                }
                                                                ?>
                                                                <br/><br/>
                                                                <?php
                                                                JPluginHelper::importPlugin('redshop_product');
                                                                $dispatcher = RedshopHelperUtility::getDispatcher();
                                                                $dispatcher->trigger('onDisplayOrderItemNote', array($products[$i]));
                                                                ?>
                                                            </td>
                                                            <td width="10%">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><?php echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL'); ?></span>
                                                                    <input type="number" min="0" name="update_price" id="update_price"
                                                                           class="form-control"
                                                                           value="<?php echo $productHelper->redpriceDecimal($products[$i]->product_item_price_excl_vat); ?>"
                                                                           size="10">
                                                                </div>
                                                            </td>
                                                            <td width="5%"><?php echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $vat; ?></td>
                                                            <td width="10%"><?php echo $productHelper->getProductFormattedPrice($products[$i]->product_item_price) . " " . JText::_('COM_REDSHOP_INCL_VAT'); ?></td>
                                                            <td width="5%">
                                                                <input type="number" min="1" name="quantity" id="quantity" class="col-sm-12"
                                                                       value="<?php echo $quantity; ?>" size="3">
                                                            </td>
                                                            <td align="right" width="10%">
                                                                <?php
                                                                echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . "&nbsp;";
                                                                echo $productHelper->redpriceDecimal($products[$i]->product_final_price);
                                                                ?>
                                                            </td>
                                                            <td width="20%">
                                                                <?php
                                                                echo $orderFunctions->getstatuslist('status', $products[$i]->order_status, "class=\"form-control\" size=\"1\" ");
                                                                ?>
                                                                <br/><br/>
                                                                <textarea cols="30" rows="3" class="form-control"
                                                                          name="customer_note"><?php echo $products[$i]->customer_note; ?></textarea>
                                                            </td>
                                                            <td width="5%">
                                                                <button type="button" class="btn btn-danger"
                                                                        title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
                                                                        onclick="if(confirm('<?php echo JText::_('COM_REDSHOP_CONFIRM_DELETE_ORDER_ITEM'); ?>')) { document.itemForm<?php echo $order_item_id; ?>.task.value='delete_item';document.itemForm<?php echo $order_item_id; ?>.submit();}"
                                                                        href="javascript:void(0);">
                                                                    <i class="fa fa-remove"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-success"
                                                                        title="<?php echo JText::_('COM_REDSHOP_UPDATE'); ?>"
                                                                        onclick="document.itemForm<?php echo $order_item_id; ?>.task.value='updateItem';javascript:validateProductQuantity('#itemForm<?php echo $order_item_id; ?>');">
                                                                    <i class="fa fa-save"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <input type="hidden" name="task" id="task" value="">
                                                    <input type="hidden" name="view" value="order_detail">
                                                    <input type="hidden" name="productid" value="<?php echo $product_id; ?>">
                                                    <input type="hidden" name="cid[]" value="<?php echo $orderId; ?>">
                                                    <input type="hidden" name="order_id[]" value="<?php echo $orderId; ?>"/>
                                                    <input type="hidden" name="order_item_id" value="<?php echo $order_item_id; ?>">
                                                    <input type="hidden" name="return" value="order_detail"/>
                                                    <input type="hidden" name="isproduct" value="1"/>
                                                    <input type="hidden" name="option" value="com_redshop"/>
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
                                                        <form action="index.php?option=com_redshop" method="post"
                                                              name="download_token<?php echo $order_item_id; ?>">
                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                <?php
                                                                foreach ($downloadarray as $downloads)
                                                                {
                                                                    $file_name                 = substr(basename($downloads->file_name), 11);
                                                                    $download_id               = $downloads->download_id;
                                                                    $download_max              = $downloads->download_max;
                                                                    $end_date                  = $downloads->end_date;
                                                                    $product_download_infinite = ($end_date == 0) ? 1 : 0;

                                                                    if ($end_date == 0)
                                                                    {
                                                                        $limit_over = false;
                                                                    }
                                                                    else
                                                                    {
                                                                        $days_in_time = $end_date - time();
                                                                        $hour         = date("H", $end_date);
                                                                        $minite       = date("i", $end_date);
                                                                        $days         = round($days_in_time / (24 * 60 * 60));
                                                                        $limit_over   = false;
                                                                        if ($days_in_time <= 0 || $download_max <= 0)
                                                                        {
                                                                            $limit_over = true;
                                                                        }
                                                                    }
                                                                    $td_style = ($end_date == 0) ? 'style="display:none;"' : 'style="display:table-row;"';
                                                                    ?>
                                                                    <tr>
                                                                        <th colspan="2"
                                                                            align="center"><?php echo JText::_('COM_REDSHOP_TOKEN_ID') . ": " . $download_id; ?></th>
                                                                    </tr>
                                                                    <?php
                                                                    if ($limit_over)
                                                                    {
                                                                        ?>
                                                                        <tr>
                                                                            <td colspan="2"
                                                                                align="center"><?php echo JText::_('COM_REDSHOP_DOWNLOAD_LIMIT_OVER'); ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td valign="top" align="right"
                                                                            class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_INFINITE_LIMIT'); ?>
                                                                            :
                                                                        </td>
                                                                        <td><?php echo JHtml::_('select.booleanlist', 'product_download_infinite_' . $download_id, 'class="inputbox" onclick="hideDownloadLimit(this,\'' . $download_id . '\');" ', $product_download_infinite); ?></td>
                                                                    </tr>
                                                                    <tr id="limit_<?php echo $download_id; ?>" <?php echo $td_style; ?>>
                                                                        <td><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL'); ?></td>
                                                                        <td><input type="text" name="limit_<?php echo $download_id; ?>"
                                                                                   value="<?php echo $download_max; ?>"></td>
                                                                    </tr>
                                                                    <tr id="days_<?php echo $download_id; ?>" <?php echo $td_style; ?>>
                                                                        <td><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL'); ?></td>
                                                                        <td>
                                                                            <input type="text" name="days_<?php echo $download_id; ?>" size="2"
                                                                                   maxlength="2" value="<?php echo $days; ?>">
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="clock_<?php echo $download_id; ?>" <?php echo $td_style; ?>>
                                                                        <td><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_CLOCK_LBL'); ?></td>
                                                                        <td>
                                                                            <input type="text" name="clock_<?php echo $download_id; ?>" size="2"
                                                                                   maxlength="2" value="<?php echo $hour; ?>">:
                                                                            <input type="text" name="clock_min_<?php echo $download_id; ?>"
                                                                                   size="2" maxlength="2" value="<?php echo $minite; ?>">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
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
                                                                        <input type="hidden" name="option" value="com_redshop"/>
                                                                        <input type="hidden" name="view" value="order"/>
                                                                        <input type="hidden" name="task" value="download_token"/>
                                                                        <input type="hidden" name="product_id"
                                                                               value="<?php echo $product_id; ?>"/>
                                                                        <input type="hidden" name="return" value="order_detail"/>
                                                                        <input type="hidden" name="cid[]" value="<?php echo $orderId; ?>"/>
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
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php
                        }
                        $cart['idx'] = count($cart);
                        RedshopHelperCartSession::setCart($cart); ?>
                        <tr>
                            <td>
                                <div class="row-fluid">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        <table align="right" border="0" cellspacing="0" cellpadding="0" class="table-striped table table-bordered">
                                            <tbody>
                                            <tr align="left">
                                                <td align="right" width="65%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_SUBTOTAL'); ?>:</strong>
                                                </td>
                                                <td align="right" width="35%">
                                                    <?php echo $productHelper->getProductFormattedPrice($subtotal_excl_vat); ?>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td align="right" width="65%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_TAX'); ?>:</strong></td>
                                                <?php
                                                $order_tax               = $this->detail->order_tax;
                                                $totaldiscount           = $this->detail->order_discount;
                                                $special_discount_amount = $this->detail->special_discount_amount;
                                                $vatOnDiscount           = false;

                                                if ((int) Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') == 0 && (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
                                                    && (int) $this->detail->order_discount != 0 && (int) $order_tax
                                                    && !empty($this->detail->order_discount)
                                                )
                                                {
                                                    $vatOnDiscount = true;
                                                    $Discountvat   = ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $totaldiscount) / (1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'));
                                                    $totaldiscount = $totaldiscount - $Discountvat;
                                                }

                                                if ((int) Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') == 0 && (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
                                                    && (int) $this->detail->special_discount_amount != 0 && (int) $order_tax
                                                    && !empty($this->detail->special_discount_amount)
                                                )
                                                {
                                                    $vatOnDiscount           = true;
                                                    $Discountvat             = ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $special_discount_amount) / (1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'));
                                                    $special_discount_amount = $special_discount_amount - $Discountvat;
                                                }

                                                if ($vatOnDiscount)
                                                {
                                                    $order_tax = (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * ($subtotal_excl_vat - ($totaldiscount + $special_discount_amount));
                                                }
                                                ?>
                                                <td align="right" width="35%">
                                                    <?php echo $productHelper->getProductFormattedPrice($order_tax); ?>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td align="right" width="65%">
                                                    <strong>
                                                        <?php
                                                        if ($this->detail->payment_oprand == '+')
                                                            echo JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL');
                                                        else
                                                            echo JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
                                                        ?>:
                                                    </strong>
                                                </td>
                                                <td align="right" width="35%">
                                                    <?php echo $productHelper->getProductFormattedPrice($this->detail->payment_discount); ?>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td align="right" width="65%">
                                                    <strong>
                                                        <?php echo JText::_('COM_REDSHOP_ORDER_DISCOUNT'); ?>:
                                                    </strong>
                                                </td>
                                                <td align="right" width="35%">
                                                    <form action="index.php?option=com_redshop" method="post"
                                                          name="update_discount<?php echo $orderId; ?>" id="update_discount<?php echo $orderId; ?>">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL'); ?></span>
                                                            <input type="number" min="0" name="update_discount"
                                                                   id="update_discount" class="form-control"
                                                                   value="<?php echo $productHelper->redpriceDecimal($this->detail->order_discount); ?>"
                                                                   size="10">
                                                            <span class="input-group-addon">
                                                                    <a href="#"
                                                                       onclick="javascript:validateDiscount('#update_discount<?php echo $orderId; ?>');">
                                                                    <?php echo JText::_('COM_REDSHOP_UPDATE'); ?>
                                                                    </a>
                                                                    </span>
                                                        </div>
                                                        <br/>
                                                        <?php echo $productHelper->getProductFormattedPrice($totaldiscount); ?>
                                                        <input type="hidden" name="task" value="update_discount">
                                                        <input type="hidden" name="view" value="order_detail">
                                                        <input type="hidden" name="cid[]" value="<?php echo $orderId; ?>">
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td align="right" width="65%"><strong><?php echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT'); ?>
                                                        :</strong>
                                                </td>
                                                <td align="right" width="35%">
                                                    <form action="index.php?option=com_redshop" method="post"
                                                          name="special_discount<?php echo $orderId; ?>"
                                                          id="special_discount<?php echo $orderId; ?>">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">%&nbsp;</span>
                                                            <input type="number" min="0" name="special_discount"
                                                                   id="special_discount" class="form-control"
                                                                   value="<?php echo $this->detail->special_discount; ?>"
                                                                   size="10">
                                                            <span class="input-group-addon">
                                                                    <a href="#"
                                                                       onclick="javascript:validateDiscount('#special_discount<?php echo $orderId; ?>');">
                                                                    <?php echo JText::_('COM_REDSHOP_UPDATE'); ?>
                                                                    </a>
                                                                    </span>
                                                        </div>
                                                        <br/>
                                                        <?php
                                                        echo $productHelper->getProductFormattedPrice($special_discount_amount);
                                                        ?>
                                                        <input type="hidden" name="order_total" value="<?php echo $this->detail->order_total; ?>">
                                                        <input type="hidden" name="task" value="special_discount">
                                                        <input type="hidden" name="view" value="order_detail">
                                                        <input type="hidden" name="cid[]" value="<?php echo $orderId; ?>">
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td align="right" width="65%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_SHIPPING'); ?>:</strong>
                                                </td>
                                                <td align="right" width="35%">
                                                    <?php echo $productHelper->getProductFormattedPrice($this->detail->order_shipping); ?>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td align="right" width="65%"><strong><?php echo JText::_('COM_REDSHOP_ORDER_TOTAL'); ?>:</strong>
                                                </td>
                                                <td align="right" width="35%">
                                                    <?php echo $productHelper->getProductFormattedPrice($this->detail->order_total); ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
                    <h3><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT'); ?></h3>
                </div>
                <div class="box-body">
                    <form action="index.php?option=com_redshop" method="post" name="adminFormAdd" id="adminFormAdd">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="adminlist table table-condensed table-striped">
                            <tr>
                                <th width="30%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
                                <th width="20%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></th>
                                <th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></th>
                                <th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TAX'); ?></th>
                                <th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></th>
                                <th width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></th>
                                <th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></th>
                                <th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
                            </tr>
                            <tr id="trPrd1">
                                <td><?php
                                    echo JHtml::_('redshopselect.search', '', 'product1',
                                        array(
                                            'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
                                            'select2.options'     => array(
                                                'events' => array('select2-selecting' => 'function(e) {
                                                    document.getElementById(\'product1\').value = e.object.id;
                                                    displayProductDetailInfo(\'product1\', 0);
                                                    displayAddbutton(e.object.id, \'product1\');}')
                                            )
                                        )
                                    );
                                    ?>
                                    <div id="divAttproduct1"></div>
                                    <div id="divAccproduct1"></div>
                                    <div id="divUserFieldproduct1"></div>
                                </td>
                                <td id="tdnoteproduct1"></td>
                                <td><input type="hidden" name="change_product_tmp_priceproduct1"
                                           id="change_product_tmp_priceproduct1" value="0" size="10">
                                    <input type="number" min="0" name="prdexclpriceproduct1" style="display: none;" id="prdexclpriceproduct1"
                                           class="col-sm-12"
                                           onchange="changeOfflinePriceBox('product1');" value="0" size="10"></td>
                                <td align="right">
                                    <div id="prdtaxproduct1"></div>
                                    <input name="taxpriceproduct1" id="taxpriceproduct1" type="hidden" value="0"/></td>
                                <td align="right">
                                    <div id="prdpriceproduct1"></div>
                                    <input name="productpriceproduct1" id="productpriceproduct1" type="hidden" value="0"/></td>
                                <td><input type="number" min="0" name="quantityproduct1" id="quantityproduct1" style="display: none;"
                                           onchange="changeOfflineQuantityBox('product1');" value="1" class="col-sm-12"
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
                                <td><input type="button" class="btn btn-primary" name="add" id="add" style="display: none;"
                                           value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
                                           onclick="javascript:submitbutton('add',this.form);"/></td>
                            </tr>

                            <tr>
                                <td colspan="8">
                                    <input type="hidden" name="task" value="">
                                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->detail->user_id; ?>">
                                    <input type="hidden" name="view" value="order_detail">
                                    <input type="hidden" name="return" value="order_detail">
                                    <input type="hidden" name="cid[]" value="<?php echo $orderId; ?>">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_LOG'); ?></h3>
                </div>
                <div class="box-body">
                    <ul class="timeline">
                        <?php $orderStatusLogs = array_reverse($orderStatusLogs); ?>
                        <?php foreach ($orderStatusLogs as $index => $log): ?>
                            <?php $nextLog = (isset($orderStatusLogs[$index + 1])) ? $orderStatusLogs[$index + 1] : false; ?>
                            <li class="time-label">
                                <span class="bg-green"><?php echo RedshopHelperDatetime::convertDateFormat($log->date_changed) ?></span>
                            </li>
                            <?php if (!$nextLog): ?>
                                <li>
                                    <i class="fa fa-check bg-green"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header"><?php echo JText::_('COM_REDSHOP_ORDER_PLACED') ?></h3>
                                        <div class="timeline-body">
                                            <p><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?>: <span
                                                        class="label order_status_<?php echo strtolower($log->order_status) ?>"><?php echo $log->order_status_name ?></span>
                                            </p>
                                            <?php if (empty($log->order_payment_status)): ?>
                                                <p><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?>: <span
                                                            class="label order_payment_status_unpaid"><?php echo JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID') ?></span>
                                                </p>
                                            <?php else: ?>
                                                <?php $paymentName = JText::_('COM_REDSHOP_PAYMENT_STA_' . strtoupper(str_replace(' ', '_', $log->order_payment_status))); ?>
                                                <p><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?>: <span
                                                            class="label order_payment_status_<?php echo strtolower($log->order_payment_status) ?>"><?php echo $paymentName ?></span>
                                                </p>
                                            <?php endif; ?>
                                            <p><?php echo $log->customer_note ?></p>
                                        </div>
                                    </div>
                                </li>
                            <?php else: ?>
                                <?php if ($log->order_status != $nextLog->order_status): ?>
                                    <li>
                                        <i class="fa fa-book bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-body">
                                                <?php echo JText::_('COM_REDSHOP_ORDER_STATUS_CHANGE_TO') ?>&nbsp;<span
                                                        class="label order_status_<?php echo strtolower($log->order_status) ?>"><?php echo $log->order_status_name ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if ($log->order_payment_status != $nextLog->order_payment_status && $log->order_payment_status): ?>
                                    <?php $paymentName = JText::_('COM_REDSHOP_PAYMENT_STA_' . strtoupper(str_replace(' ', '_', $log->order_payment_status))); ?>
                                    <li>
                                        <i class="fa fa-dollar bg-red"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-body">
                                                <?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_STATUS_CHANGE_TO') ?>&nbsp;<span
                                                        class="label order_payment_status_<?php echo strtolower($log->order_payment_status) ?>"><?php echo $paymentName ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($log->customer_note) && $log->customer_note != $nextLog->customer_note): ?>
                                    <li>
                                        <i class="fa fa-comment bg-yellow"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-body">
                                                <i><?php echo $log->customer_note ?></i>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($this->lists['order_extra_fields'])): ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3><?php echo JText::_('COM_REDSHOP_EXTRA_FIELD'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo JRoute::_('index.php?option=com_redshop&view=order_detail&task=storeExtraField'); ?>" method="post"
                              name="adminForm" id="adminForm">
                            <?php echo $this->lists['order_extra_fields'] ?>
                            <input class="button btn btn-primary" name="submit"
                                   value="<?php echo JText::_('COM_REDSHOP_SAVE'); ?>" type="submit"/>
                            <input type="hidden" name="order_id" value="<?php echo $billing->order_id; ?>"/>
                            <input type="hidden" name="user_email" value="<?php echo $billing->user_email; ?>"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php echo $this->loadTemplate('plugin'); ?>
<div id="divCalc"></div>
<script type="text/javascript">
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

    function validateDiscount(form) {
        var subTotal = <?php echo $subtotal_excl_vat ?>;
        var discount = parseFloat(jQuery('#update_discount').val());

        var specialDiscount = parseFloat(jQuery('#special_discount').val());

        if ((discount < 0) || (specialDiscount < 0)) {
            alert('<?php echo JText::_("COM_REDSHOP_ORDER_DISCOUNT_NOT_LESS_THAN_ZERO") ?>');
            return false;
        }

        var totalDiscount = discount + specialDiscount;

        if (subTotal <= totalDiscount) {
            alert('<?php echo JText::_("COM_REDSHOP_ORDER_DISCOUNT_INVALID") ?>');
            return false;
        }

        jQuery(form).submit();
    }

    function validateInputFloat(e) {
        if ((e.keyCode == 189) || (e.keyCode == 109)) {
            e.preventDefault();
        }
    }

    function validateProductQuantity(form) {
        var itemPrice = jQuery("input[name=quantity]").val();

        if (itemPrice < 1) {
            alert('<?php echo JText::_("COM_REDSHOP_ORDER_ITEM_QUANTITY_ATLEAST_ONE") ?>');
            return false;
        }

        jQuery(form).submit();
    }

    jQuery(document).ready(function () {

        jQuery("#update_discount").keydown(function (e) {
            validateInputFloat(e);
        });

        jQuery("#special_discount").keydown(function (e) {
            validateInputFloat(e);
        });

        jQuery("input[name=update_price]").keydown(function (e) {
            validateInputFloat(e);
        });

        jQuery("input[name=quantity]").keydown(function (e) {
            validateInputFloat(e);
        });
    });
</script>
