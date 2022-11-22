<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

use Redshop\Billy\RedshopBilly;

global $context;

$app              = JFactory::getApplication();
$config           = Redconfiguration::getInstance();
$calendarFormat   = Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d');
$lists            = $this->lists;
$model            = $this->getModel('order');
$stockroomHelper  = rsstockroomhelper::getInstance();
$dispatcher       = RedshopHelperUtility::getDispatcher();
JPluginHelper::importPlugin('redshop_product');
?>
<style type="text/css">
    div#toolbar-box div.m {
        height: 70px;
    }
</style>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function ($) {

        jQuery("#search").click(function (event) {
            document.adminForm.task.value = "";
        });

        jQuery("#filter_by, #filter_payment_status, #filter_status").change(function (e) {
            document.adminForm.task.value = "";
        });

        jQuery(".order_status_change").click(function (event) {
            event.preventDefault();
            var target = jQuery(this).attr("data-target");
            jQuery("#" + target).slideToggle();
        });
    });

    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton == "add") {
            <?php $link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=addorder_detail'); ?>
            window.location = '<?php echo $link;?>';
            return;
        }

        switch (pressbutton) {
            case "edit":
                form.view.value = "order_detail";
                break;
            case "multiprint_order":
                form.view.value = "order";
                break;
            case "remove":
                form.view.value = "order_detail";
                var r = confirm('<?php echo JText::_("COM_REDSHOP_ORDER_DELETE_ORDERS_CONFIRM")?>');

                if (r == false) {
                    return false;
                }
                break;
            default:
                break;
        }

        try {
            form.onsubmit();
        } catch (e) {
        }

        form.submit();

        form.task.value = "";
        jQuery("#adminForm input[type='checkbox'][name='cid[]']").prop("checked", false);
    };

    resetFilter = function () {
        document.adminForm.task.value = "";
        document.getElementById("filter").value = "";
        document.getElementById("filter_by").value = "";
        document.getElementById("filter_payment_status").value = "";
        document.getElementById("filter_status").value = "0";
        document.getElementById("filter_from_date").value = "";
        document.getElementById("filter_to_date").value = "";
        document.adminForm.submit();
    };
</script>
<script type="text/javascript">
    function massStatusChange(option) {
        (function ($) {
            var form = document.adminForm;
            var massStatus = $("#massOrderStatusChange select[name=mass_change_order_status]").val();
            var massPayment = $("#massOrderStatusChange select[name=mass_change_payment_status]").val();
            var massSend = $("#massOrderStatusChange input[name=mass_mail_sending]");

            form.task.value = option;
            form.mass_change_order_status.value = massStatus;
            form.mass_change_payment_status.value = massPayment;
            if ($(massSend).is(":checked")) {
                form.mass_mail_sending.value = 1;
            } else {
                form.mass_mail_sending.value = 0;
            }

            try {
                form.onsubmit();
            } catch (e) {
            }

            form.submit();
        })(jQuery);
    }

    function massPacsoftStatusChange(option) {
        (function ($) {
            var form = document.adminForm;
            var massStatus = $("#massOrderStatusPacsoft select[name=mass_change_order_status]").val();
            var massPayment = $("#massOrderStatusPacsoft select[name=mass_change_payment_status]").val();
            var massSend = $("#massOrderStatusPacsoft input[name=mass_mail_sending]");

            form.task.value = option;
            form.mass_change_order_status.value = massStatus;
            form.mass_change_payment_status.value = massPayment;
            if ($(massSend).is(":checked")) {
                form.mass_mail_sending.value = 1;
            } else {
                form.mass_mail_sending.value = 0;
            }

            try {
                form.onsubmit();
            } catch (e) {
            }

            form.submit();
        })(jQuery);
    }
</script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("#massOrderStatusChange").on("show.bs.modal", function (event) {
                var checks = [];
                var checked = $("input[type='checkbox'][id^='cb'][name^='cid']:checked");

                if (!checked.length) {
                    alert("<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST') ?>");

                    return false;
                } else {
                    $(checked).each(function (index, item) {
                        checks.push($(item).val());
                    });

                    $("#checked_orders").html("<strong>" + checks.join("</strong> , <strong>") + "</strong>");
                }
            });
        });
    })(jQuery);
</script>

<form action="<?php echo Redshop\IO\Route::_('index.php?option=com_redshop&view=order'); ?>" method="post" name="adminForm"
      id="adminForm">
    <div id="editcell">
        <div class="filterTool">
            <div class="filterItem">
                <div class="btn-wrapper input-append">
                    <input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
                           placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
                    <button class="btn" onclick="document.adminForm.submit();"><?php echo JText::_(
                            'COM_REDSHOP_SEARCH'
                        ); ?></button>
                    <input type="button" class="btn reset" onclick="resetFilter();"
                           value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"/>
                </div>
            </div>
            <div class="filterItem">
                <?php echo $lists['filter_by']; ?>
            </div>
            <?php
            $state     = $this->get('State');
            $startDate = $state->get('filter_from_date');
            $endDate   = $state->get('filter_to_date');
            ?>
            <div class="filterItem calendar-div">
                <?php
                echo JHtml::_(
                    'calendar',
                    $startDate,
                    'filter_from_date',
                    'filter_from_date',
                    '%d-%m-%Y',
                    array(
                        'size'        => '15',
                        'maxlength'   => '19',
                        'placeholder' => JText::_('COM_REDSHOP_FROM') . ' ' . JText::_('JDATE')
                    )
                );
                ?>
            </div>
            <div class="filterItem calendar-div">
                <?php
                echo JHtml::_(
                    'calendar',
                    $endDate,
                    'filter_to_date',
                    'filter_to_date',
                    '%d-%m-%Y',
                    array(
                        'size'        => '15',
                        'maxlength'   => '19',
                        'placeholder' => JText::_('COM_REDSHOP_TO') . ' ' . JText::_('JDATE')
                    )
                );
                ?>
            </div>
            <input name="search" class="btn" type="submit" id="search"
                   value="<?php echo JText::_('COM_REDSHOP_GO'); ?>"/>
            <div class="filterItem">
                <?php echo $lists['filter_payment_status']; ?>
            </div>
            <div class="filterItem">
                <?php echo $lists['filter_status']; ?>
            </div>
        </div>
    </div>

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="1">#</th>
            <th width="1"><?php echo JHtml::_('redshopgrid.checkall') ?></th>
            <th class="title" width="125px">
                <?php echo JHTML::_('grid.sort', 'ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']) ?>
            </th>
            <th width="200px">
                <?php echo JHTML::_(
                    'grid.sort',
                    'COM_REDSHOP_CUSTOMER',
                    'uf.firstname',
                    $this->lists['order_Dir'],
                    $this->lists['order']
                ); ?>
            </th>
            <th>
                <?php echo JText::_('COM_REDSHOP_NOTIFY_CUSTOMER_HEADING'); ?>
            </th>
            <th width="290px">
                <?php echo JHTML::_(
                    'grid.sort',
                    'COM_REDSHOP_PAYMENT',
                    'order_payment_status',
                    $this->lists['order_Dir'],
                    $this->lists['order']
                ); ?>
            </th>
            <th width="300px">
                <?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD') ?>
            </th>
            <?php if (Redshop::getConfig()->get('USE_STOCKROOM') == 1): ?>
                <th width="10%">
                    <?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('COM_REDSHOP_STOCKROOM_DELIVERY_TIME'); ?>
                </th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $send_mail_to_customer = Redshop::getConfig()->get('SEND_MAIL_TO_CUSTOMER') ? 'checked' : 0;
        if (Redshop::getConfig()->get('CLICKATELL_ENABLE')) {
            $send_sms_to_customer  = 'checked="checked"';
        }
        $k                     = 0;
        ?>
        <?php for ($i = 0, $n = count($this->orders); $i < $n; $i++): ?>
            <?php
            $row            = $this->orders[$i];
            $row->id        = $row->order_id;
            $link           = 'index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $row->order_id;
            $link           = RedshopHelperUtility::getSSLLink($link);
            $billing        = RedshopEntityOrder::getInstance($row->order_id)->getBilling();
			$paymentDetail	= RedshopEntityOrder::getInstance($row->order_id)->getPayment();
            $shippingDetail = Redshop\Shipping\Rate::decrypt($row->ship_method_id);
			
            /**
             * This is an event that is using into back-end order listing page. In to grid column, below update status.
             * This event is called to add highlighter from which order can be identified that plug-in enhancement is included into this order.
             */
            $data                             = new stdClass;
            $data->highlight                  = new stdClass;
            $results                          = $dispatcher->trigger('toHighlightGrid', array(&$row));
            $data->highlight->toHighlightGrid = trim(implode("\n", $results));
            ?>
            <tr class="row<?php echo $k; ?>">
                <td class="order">
                    <?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td class="order">
                    <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                </td>
                <td align="center">
                    <a style="margin-bottom:7px;background:#7f7f7f;color:#fff" class="btn btn-default" href="<?php echo $link ?>"
                       title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER') ?>">
                       <i class="fa fa-edit"></i>&nbsp;&nbsp;<?php echo $row->order_id ?>
                    </a>
                    <br>
                    <?php echo RedshopHelperDatetime::convertDateFormat($row->cdate); ?>
                    <br>
					<b style="font-size:15px">
                        <?php echo RedshopHelperProductPrice::formattedPrice($row->order_total); ?>
                    </b>
                </td>
                <td>
                    <b>
					    <?php if ($row->is_company): ?>
						    <a href="index.php?option=com_redshop&view=user_detail&task=edit&user_id=<?php echo $row->user_id ?>
                                &cid[]=<?php echo $row->user_info_id ?>" target="_blank"><?php echo $row->company_name; ?>
                            </a>
					    <?php else: ?>
						    <a href="index.php?option=com_redshop&view=user_detail&task=edit&user_id=<?php echo $row->user_id ?>
                                &cid[]=<?php echo $row->user_info_id ?>" target="_blank"><?php echo $row->firstname . ' ' . $row->lastname; ?>
                            </a>
					    <?php endif; ?>
					</b>
					<?php if ($row->is_company): ?>
						<br>
						<?php echo $row->firstname . ' ' . $row->lastname; ?>
					<?php endif; ?>
					<br>
					<i class="fa-regular fa-envelope"></i>&nbsp;&nbsp;<?php echo $row->user_email; ?>
					<br>
					<i class="fa-solid fa-mobile-screen-button"></i>&nbsp;&nbsp;<?php echo $billing->phone; ?>
                </td>
                <td>
                <?php
                    $linkUpdate = 'index.php?option=com_redshop&view=order&task=update_status&return=order&order_id[]=' . $row->order_id;
                    ?>
                    <button type="button" class="btn btn-default order-row-200px" data-toggle="modal"
                            data-target="#order_status_form<?php echo $row->id ?>">
                        <i class="fa fa-pencil-square-o"></i>&nbsp;</i> <?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON') ?>
                    </button>
                    <div class="modal fade" id="order_status_form<?php echo $row->id ?>" role="dialog"
                         aria-labelledby="order_status_form_label_<?php echo $row->id ?>">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title" id="order_status_form_label_<?php echo $row->id ?>">
                                        <?php echo JText::_('COM_REDSHOP_ORDER') . ': ' . $row->id ?>
                                    </h3>
                                </div>
                                <div class="modal-body">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?></label>
                                                    <?php echo RedshopHelperOrder::getStatusList(
                                                        'order_status' . $row->order_id,
                                                        $row->order_status,
                                                        "class=\"form-control inputbox\" size=\"1\" "
                                                    ); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?></label>
                                                    <?php echo RedshopHelperOrder::getPaymentStatusList(
                                                        'order_paymentstatus' . $row->order_id,
                                                        $row->order_payment_status,
                                                        "class=\"form-control inputbox\" size=\"1\" "
                                                    ); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo JText::_(
                                                            'COM_REDSHOP_CUSTOMER_NOTE_LBL'
                                                        ); ?></label>
                                                    <textarea class="form-control"
                                                              name="customer_note<?php echo $row->order_id ?>"
                                                              style="resize: none;"><?php echo $row->customer_note ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <hr/>
                                                <div class="form-group">
                                                    <label>
                                                        <input type="checkbox" <?php echo $send_mail_to_customer ?>
                                                               value="1"
                                                               name="sendordermail<?php echo $row->order_id ?>"/> 
                                                               <?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL') ?>
                                                    </label>
                                            <?php   if (Redshop::getConfig()->get('CLICKATELL_ENABLE')) { ?>
													<label>
														<input type="checkbox" <?php echo $send_sms_to_customer;?>
                                                               value="1"
															   name="sendordersms<?php echo $row->order_id ?>"/> 
                                                               <?php echo JText::_('COM_REDSHOP_SEND_ORDER_SMS') ?>
													</label>
											<?php   } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="text-align: right">
                                                <hr/>
                                                <div class="form-group"> <?php
                                                    echo $shipping_name = 
                                                            Redshop\Shipping\Tag::replaceShippingMethod(
                                                        $row, "{shipping_method}"); ?>
                                                    <br />									
												    <i class="fa-solid fa-mobile-screen-button"></i>
                                                    &nbsp;&nbsp;<?php echo $billing->phone; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        <?php echo JText::_('JTOOLBAR_CANCEL') ?>
                                    </button>
                                    <button type="button" class="button btn btn-primary"
                                            onclick="location.href='<?php echo Redshop\IO\Route::_(
                                                $linkUpdate,
                                                false
                                            ) ?>&status='+document.adminForm.order_status<?php echo $row->order_id ?>.value+'&customer_note='+encodeURIComponent(document.adminForm.customer_note<?php echo $row->order_id ?>.value)+'&order_sendordermail='+document.adminForm.sendordermail<?php echo $row->order_id ?>.checked+'&order_sendordersms='+document.adminForm.sendordersms<?php echo $row->order_id ?>.checked+'&order_paymentstatus='+document.adminForm.order_paymentstatus<?php echo $row->order_id ?>.value;"
                                            value="<?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON'); ?>">
                                        <?php echo JText::_('JTOOLBAR_SAVE') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <span class="label order_status_<?php echo strtolower($row->order_status) ?> order-row-200px">
                        <?php echo $row->order_status_name ?>
                    </span>
                    <?php echo $data->highlight->toHighlightGrid; ?>
                    <br>
                    <?php if (Redshop::getConfig()->get('CLICKATELL_ENABLE')) {
                    $linkCustomSms = 'index.php?option=com_redshop&view=order&task=custom_sms&return=order&order_id[]=' . $row->order_id; ?>
                    <button type="button" class="label order_status_btn" style="width:55px" data-toggle="modal"
                            data-target="#sms_form<?php echo $row->id ?>">
                        <i class="fa fa-edit"></i>&nbsp;</i> <?php echo JText::_('SMS') ?>
                    </button>
                    <div class="modal fade" id="sms_form<?php echo $row->id ?>" role="dialog"
                         aria-labelledby="sms_form_label_<?php echo $row->id ?>">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title" id="sms_form_label_<?php echo $row->id ?>">
										<?php echo JText::_('COM_REDSHOP_ORDER') . ': ' . $row->id ?>
                                    </h3>
                                </div>
								<div class="modal-body">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
										        <div class="form-group">
											        <label>
                                                        <?php echo JText::_('COM_REDSHOP_CLICKATELL_COUNTRY_PREFIX') . ': ' ?>    
                                                    </label>
											        <input type="text" name="prefix<?php echo $row->order_id ?>" 
                                                        value="<?php echo Redshop::getConfig()->get('CLICKATELL_COUNTRY_PREFIX');?>" />
                                               </div>
                                            </div>
                                            <div class="col-md-6">
										        <div class="form-group">     
                                                    <label>
                                                        <?php echo JText::_('COM_REDSHOP_PHONE') . ': ' ?>
                                                    </label>
											        <input type="text" name="to<?php echo $row->order_id ?>" 
                                                        value="<?php echo $billing->phone;?>" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
											<label>
                                                <?php echo JText::_('COM_REDSHOP_ALERT_MESSAGE') . ': ' ?>
                                            </label>
											<textarea class="form-control" name="message<?php echo $row->order_id ?>" /><?php 
                                                echo Redshop::getConfig()->get('CLICKATELL_CUSTOM_MESSAGE'); ?></textarea>
										</div>
									</div>
								</div>
								<div class="modal-footer">

                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        <?php echo JText::_('JTOOLBAR_CANCEL') ?>
                                    </button>
                                    <button type="button" class="button btn btn-primary"
                                            onclick="location.href='<?php echo Redshop\IO\Route::_(
                                                $linkCustomSms,
                                                false
                                            ) ?>&status='+document.adminForm.order_status<?php echo $row->order_id ?>.value+'&customer_note='+encodeURIComponent(document.adminForm.message<?php echo $row->order_id ?>.value)+'&to='+document.adminForm.to<?php echo $row->order_id ?>.value+'&prefix='+document.adminForm.prefix<?php echo $row->order_id ?>.value;"
                                            value="<?php echo JText::_('Send sms'); ?>">
                                        <?php echo JText::_('Send sms') ?>
                                    </button>
								</div>
                            </div>
                        </div>
                    </div>
                    <?php   } ?>
                    <?php if (RedshopHelperPdf::isAvailablePdfPlugins()): ?>
                        <a href="index.php?option=com_redshop&task=order.printPDF&id=<?php echo $row->order_id ?>"
                           target="_blank">
                            <i class="fa fa-file-pdf-o" style="color:#b7b7b7"></i>
                        </a>
                    <?php else: ?>
                        <span class="disabled" style="color:#b7b7b7"><i class="fa fa-file-pdf-o"></i></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php $paymentStatusClass = 'label order_payment_status_' . strtolower(
                        $row->order_payment_status); ?>
                    <span class="<?php echo $paymentStatusClass ?> order-payment-row">
			            <?php if ($row->order_payment_status == 'Paid'): ?>
                            <?php  echo JText::_($paymentDetail->order_payment_name);?> - 
                            <?php echo JText::_('COM_REDSHOP_PAYMENT_STA_PAID') ?>
                        <?php elseif ($row->order_payment_status == 'Unpaid'): ?>
                            <?php  echo JText::_($paymentDetail->order_payment_name);?> - 
                            <?php echo JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID') ?>
                        <?php elseif ($row->order_payment_status == 'Partial Paid' || 
                                $row->order_payment_status == 'PartialPaid'): ?>
                            <?php  echo JText::_($paymentDetail->order_payment_name);?> - 
                            <?php echo JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID') ?>
                        <?php endif; ?>
			        </span>

                    <?php 
                    // Economic section START
                    if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && 
                            Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2
                            && $row->invoice_no && $row->is_booked == 1 && $row->bookinvoice_number): ?>
                        <?php echo $row->bookinvoice_number ?>
                    <?php endif; ?>
                    <?php if ($row->invoice_no != ''): ?>
                        <?php if ($row->is_booked == 0 && $row->bookinvoice_date <= 0): ?>
                            <?php if ($row->is_company == 1 && $row->ean_number != ""): ?>
                                <?php echo JText::_('COM_REDSHOP_MANUALY_BOOK_INVOICE_FROM_ECONOMIC') ?>
                            <?php else: ?>
                                <?php
                                $confirm = 'if(confirm(\'' . JText::_(
                                        'COM_REDSHOP_CONFIRM_BOOK_INVOICE'
                                    ) . '\')) { document.invoice.order_id.value=\'' . $row->order_id . '\';document.invoice.bookInvoiceDate.value=document.getElementById(\'bookDate' . $i . '\').value;document.invoice.submit(); }';

                                if ($row->order_payment_status == 'Paid' || $row->order_status == 'PR' || $row->order_status == 'C') {
                                    $confirm = 'document.invoice.order_id.value=\'' . $row->order_id . '\';document.invoice.bookInvoiceDate.value=document.getElementById(\'bookDate' . $i . '\').value;document.invoice.submit();';
                                }

                                echo JHtml::_(
                                    'redshopcalendar.calendar',
                                    date($calendarFormat),
                                    'bookDate' . $i,
                                    'bookDate' . $i,
                                    $calendarFormat,
                                    array('class' => 'form-control', 'size' => '15', 'maxlength' => '19')
                                );
                                ?>
                                <br/>
                                <input type="button" class="btn btn-default order-payment-row"
                                       value="<?php echo JText::_("COM_REDSHOP_BOOK_INVOICE"); ?>"
                                       onclick="javascript:<?php echo $confirm; ?>"><br/>
                            <?php endif; ?>
                        <?php elseif ($row->bookinvoice_date > 0): ?>
                                <br/>
                                <span class="label order_payment_status_paid order-payment-row"> 
                                    <?php echo JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') . " " . 
                                        RedshopHelperDatetime::convertDateFormat($row->bookinvoice_date); ?>
                                </span><br />
                        <?php endif; ?>
                    <?php endif;
                    // Economic section END
                    // Billy section START
					if (JPluginHelper::isEnabled('billy')) {	
                        $billyPlugin 		    = JPluginHelper::getPlugin('billy', 'billy');
                        $billyPluginParams 	    = new JRegistry($billyPlugin->params);
						$billySendInvoiceMethod = $billyPluginParams->get('billy_send_invoice_method','0');
                        $invoice                = RedshopBilly::getInvoiceData($row->billy_invoice_no);
				
						if (($row->billy_invoice_no != '' || $row->billy_invoice_no !== 0) && $row->order_status !== 'X') {
							if (($row->is_billy_booked == 0 && $row->billy_bookinvoice_date <= 0) 
                                    || ($row->order_payment_status == 'Paid' && $row->is_billy_cashbook == 0) 
                                    || ($row->order_payment_status == 'Unpaid' && $row->is_billy_booked == 0) 
                                    || ($row->order_payment_status == 'Unpaid' && $row->is_billy_booked == 1 
                                    && $row->is_billy_cashbook == 0)) {
								$confirm = 'if(confirm(\'' . JText::_('COM_REDSHOP_CONFIRM_BOOK_INVOICE') . '\')) { document.binvoice.order_id.value=\'' . $row->order_id . '\';document.binvoice.submit(); }';
								if ($row->is_billy_booked == 0) {
                                    $paymentInfo  = RedshopHelperOrder::getPaymentInfo($row->id);
                        			$paymentClass = $paymentInfo->payment_method_class;
									if ($row->is_company == 1 && $paymentClass == 'rs_payment_eantransfer') {?>
                                        <br/>
                                        <span class="label order_payment_status_x order-payment-row"> 
                                            <?php echo JText::_('COM_REDSHOP_MANUALLY_BOOK'); ?>
                                        </span><br /> <?php
									} else {
										$confirm = 'document.binvoice.onlycashbook.value=0;document.binvoice.onlybook.value=1;document.binvoice.bookwithCashbook.value=0;document.binvoice.order_id.value=\'' . $row->order_id . '\';document.binvoice.submit();'; ?>
                                        <br/>
                                        <button type="button" class="btn btn-default order-payment-row" 
                                                onclick="javascript:<?php echo $confirm; ?>">
                                            <i class='fas fa-file-invoice-dollar'></i>&nbsp;
                                            <?php echo JText::_("COM_REDSHOP_BOOK_INVOICE"); ?>
                                        </button>
                                        <br/><?php
									}
								} else if ($row->order_payment_status !== 'Paid' && $invoice->isPaid == '1'
                                        && $row->is_billy_booked == 1 ) { ?>
                                    <br />
                                    <span class="label order_payment_status_paid order-payment-row"> 
                                        <?php echo JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') . " " . 
                                        date("d-m-Y", strtotime($row->billy_bookinvoice_date)); ?>
                                    </span>
                                    <span class="btn btn-small btn-success order-payment-row"> 
                                        <?php echo JText::_('COM_REDSHOP_BILLY_PAID_IN_BILLY') . " " . 
                                        date("d-m-Y", strtotime($row->billy_bookinvoice_date)); ?>
                                    </span><br /> <?php
								} else if ($row->is_billy_booked == 1 && $row->is_billy_cashbook == 0 
                                        && $row->order_payment_status == 'Paid' && !$invoice->isPaid == '1') {
									$confirm = 'document.binvoice.onlycashbook.value=1;document.binvoice.onlybook.value=0;document.binvoice.bookwithCashbook.value=0;document.binvoice.order_id.value=\'' . $row->order_id . '\';document.binvoice.submit();';
									echo "<span style='font-size:12px'>" . JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') . " " . ($row->billy_bookinvoice_date) . "</span><br />"; ?>
									<input type="button" class="btn btn-default" style="margin-right:15px" value="<?php echo JText::_("COM_REDSHOP_BILLY_MAKE_CASHBOOK"); ?>"
										onclick="javascript:<?php echo $confirm; ?>"><br/> <?php
                                } else if ($row->is_billy_booked == 1) { ?>
                                    <br/>
                                    <span class="label order_payment_status_paid order-payment-row"> 
                                        <?php echo JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') . " " . 
                                            date("d-m-Y", strtotime($row->billy_bookinvoice_date)); ?>
                                    </span><br /> <?php
                                }
                            } else if ($row->is_billy_booked == 1) { ?>
                                <br/>
                                <span class="label order_payment_status_paid order-payment-row"> 
                                    <?php echo JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') . " " . 
                                        date("d-m-Y", strtotime($row->billy_bookinvoice_date)); ?>
                                </span><br /> <?php
                            }
                        } else {
                            echo $row->billy_invoice_no;
                        } ?>
						<a style="width:78px" onclick="javascript: callTimeline('<?php 
                                echo $row->billy_invoice_no; ?>','<?php echo $row->id ?>');" 
                                class="label order_status_btn" data-toggle="modal" 
                                data-target="#billy_timeline<?php echo $row->id ?>">
							<i class="far fa-hourglass-half"></i>&nbsp;
                            <?php echo JText::_('COM_REDSHOP_BILLY_TIMELINE') ?>
						</a>
						<div class="modal fade" id="billy_timeline<?php echo $row->id ?>" role="dialog" 
                                aria-labelledby="billy_timeline_label_<?php echo $row->id ?>">
							<div class="modal-dialog" role="document">
								<div class="modal-content" style="">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" 
                                                aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
										<h3 class="modal-title" 
                                                id="sms_form_rykker_label_<?php echo $row->id ?>">
											<?php echo JText::_('COM_REDSHOP_BILLY_TIMELINE').': '.$row->id ?>
										</h3>
									</div>
									<div class="modal-body" style="text-align:center">
										<div class="billy-timeline-entries" 
                                                id="invoiceTimeLine<?php echo $row->id ?>">
                                        </div>
									</div>
								</div>
							</div>
						</div> <?php
						if ((int) $billySendInvoiceMethod == 2 && $row->order_status !== 'X') {
							if(isset($invoice->sentState)) {
								if ($invoice->sentState == 'sent' || $invoice->sentState == 'opened' 
                                        || $invoice->sentState == 'viewed' 
                                        || $invoice->sentState == 'printed') { ?>
									<a href="<?php echo $invoice->downloadUrl; ?>" class="hasPopover" 
                                            style="color:#b7b7b7; margin-top:7px" target="_blank" 
                                            title data-content="Download invoice as pdf" 
                                            data-original-title="Billy invoice">
                                        <i class="fa fa-download"></i>
                                    </a>&nbsp; <?php
						    	} else { ?>
									<a href="<?php echo $invoice->downloadUrl; ?>?layout=packing-list" 
                                            class="hasPopover" style="color:#b7b7b7; margin-top:7px" 
                                            target="_blank" title data-content="Download packing list as pdf" 
                                            data-original-title="Billy package list">
                                        <i class="far fa-circle-down"></i>
                                    </a>&nbsp; <?php
						    	} 
							}					
							if ($row->is_billy_booked == 1 && $row->order_status !== 'X') {
								if (isset($invoice->sentState)) {
									if ($invoice->sentState == 'sent') { ?>
										<span class="hasPopover billy-order-icons" 
                                                title data-content="Invoice sent as email, but not opened by customer" 
                                                data-original-title="Billy invoice status">
                                            <i class="far fa-envelope"></i>
                                        </span>&nbsp; <?php
							    	}
									if (isset($invoice->sentState) && $invoice->sentState == 'opened') { ?>
										<span class="hasPopover billy-order-icons" 
                                                title data-content="Email viewed by customer" 
                                                data-original-title="Billy invoice status">
                                            <i class="far fa-envelope-open"></i>
                                        </span>&nbsp; <?php
                                    }
									if (isset($invoice->sentState) && ($invoice->sentState == 'viewed' 
                                            || $invoice->sentState == 'printed')) { ?>
										<span class="hasPopover billy-order-icons" 
                                                title data-content="Invoice opened by customer" 
                                                data-original-title="Billy invoice status">
                                            <i class="far fa-search-plus"></i>
                                        </span>&nbsp; <?php
                                    }
								}
								if ((Redshop::getConfig()->get('POSTDK_INTEGRATION')) 
                                        && $row->order_label_create) { ?>
									<a href="https://www.unifaunonline.com/ext.uo.dk.track?key=290000004&order=<?php echo $row->order_id; ?>" 
                                            class="hasPopover billy-order-icons" target="_blank" 
                                            title data-content="Open tracking in new windwow" 
                                            data-original-title="Order shipped">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </a>&nbsp; <?php	
                                }	
							} ?>
							<a href="index.php?option=com_redshop&view=order_detail&task=createpdf&cid[]=<?php 
                                    echo $row->order_id; ?>" class="hasPopover billy-order-icons" 
                                    target="_blank" 
                                    title data-content="Download forsendelses pdf i A5 format" 
                                    data-original-title="Download forsendelses pdf">
                                <i class="fa fa-tag"></i>
                            </a>&nbsp; <?php
				    	}

						if ($row->is_billy_booked == 0 && ($row->order_status == 'S' 
                                || $row->order_status == 'RD' || $row->order_status == 'RD1')) { ?>
							<span class="label order_payment_status_paid order-payment-row" 
                                    style="color:#ffffff;background-color:#e83026">
								<b>
                                    <?php echo JText::_('COM_REDSHOP_BILLY_ERROR_IN_BOOK_INVOICE'); ?>
                                </b>
							</span> <?php
				    	} 
						if ($row->is_billy_booked == 1 && $row->order_payment_status == 'Unpaid' 
                                && ($row->order_status == 'S' || $row->order_status == 'RD' 
                                || $row->order_status == 'RD1')) {
                            // $row->overdue_days
                            $overdueDays = RedshopBilly::calulateOverdueDays($row->billy_invoice_no);
							if ($overdueDays > 0 && $row->order_payment_status == 'Unpaid') { ?>
								<br>
								<span class="label order_status_x order-payment-row" style="margin-top:5px">
                                    <?php echo JText::_('COM_REDSHOP_BILLY_INVOICE_OVERDUE_WITH'); ?>
                                    <?php echo $overdueDays . ' ' . JText::_('COM_REDSHOP_DAYS') ?>
								</span> <?php
					    	}
                            // && $row->overdue_limit > 0
							$billy_reminder = $billyPluginParams->get('billy_reminder','0');
							if ($row->order_payment_status == 'Unpaid' && $row->billy_invoice_no != '' 
                                    && ($billy_reminder && $row->is_billy_booked == 1 
                                    && !$invoice->isPaid) && $overdueDays > 0) { ?>
								<br />
                                <?php if (Redshop::getConfig()->get('CLICKATELL_ENABLE')) {
                                $linkCustomSmsReminder = 'index.php?option=com_redshop&view=order&task=custom_sms_reminder&return=order&order_id[]=' . $row->order_id; ?>
                                <button type="button" class="label order_status_btn order-payment-row" data-toggle="modal"
                                        data-target="#sms_reminder_form<?php echo $row->id ?>">
                                    <i class="fa fa-edit"></i>&nbsp;</i> <?php echo JText::_('COM_REDSHOP_SEND_SMS_REMINDER') ?>
                                </button>
                                <div class="modal fade" id="sms_reminder_form<?php echo $row->id ?>" role="dialog"
                                        aria-labelledby="sms_reminder_form_label_<?php echo $row->id ?>">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h3 class="modal-title" id="sms_reminder_form_label_<?php echo $row->id ?>">
										            <?php echo JText::_('COM_REDSHOP_ORDER') . ': ' . $row->id ?>
                                                </h3>
                                            </div>
			        					    <div class="modal-body">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
										                    <div class="form-group">
											                    <label>
                                                                    <?php echo JText::_('COM_REDSHOP_CLICKATELL_COUNTRY_PREFIX') . ': ' ?>
                                                                </label>
											                    <input type="text" 
                                                                    name="prefix<?php echo $row->order_id ?>" 
                                                                    value="<?php echo Redshop::getConfig()->get('CLICKATELL_COUNTRY_PREFIX');?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
										                    <div class="form-group">     
                                                                <label>
                                                                    <?php echo JText::_('COM_REDSHOP_PHONE') . ': ' ?>
                                                                </label>
											                    <input type="text" name="to<?php echo $row->order_id ?>" 
                                                                    value="<?php echo $billing->phone;?>" />
                                                            </div>
                                                        </div>
                                                    </div>
										            <div class="form-group">
											            <label>
                                                            <?php echo JText::_('COM_REDSHOP_ALERT_MESSAGE') . ': ' ?>
                                                        </label>
											            <textarea class="form-control" name="message<?php echo $row->order_id ?>" /><?php 
                                                            echo Redshop::getConfig()->get('CLICKATELL_CUSTOM_MESSAGE') ?>
                                                        </textarea>
										            </div>
									            </div>
								            </div>
								            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    <?php echo JText::_('JTOOLBAR_CANCEL') ?>
                                                </button>
                                                <button type="button" class="button btn btn-primary"
                                                        onclick="location.href='<?php echo Redshop\IO\Route::_(
                                                        $linkCustomSmsReminder,
                                                        false
                                                        ) ?>&status='+document.adminForm.order_status<?php echo $row->order_id ?>.value+'&customer_note='+encodeURIComponent(document.adminForm.message<?php echo $row->order_id ?>.value)+'&to='+document.adminForm.to<?php echo $row->order_id ?>.value+'&prefix='+document.adminForm.prefix<?php echo $row->order_id ?>.value;"
                                                        value="<?php echo JText::_('COM_REDSHOP_SEND_SMS_REMINDER') ?>">
                                                    <?php echo JText::_('COM_REDSHOP_SEND_SMS_REMINDER') ?>
                                                </button>
								            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php   } ?>
                                <div class="panel-body panel panel-default billy-reminder-block">
									<input class="hasPopover order-row-235px" type="text" 
                                        title data-content="<?php echo JText::_("COM_REDSHOP_BILLY_REMINDER_FEE_NOTE"); ?>" 
                                        data-original-title="<?php echo JText::_("COM_REDSHOP_BILLY_REMINDER_FEE_AMOUNT"); ?>" 
                                        name="billy_reminder_fee_amount<?php echo $row->order_id ?>" 
                                        id="billy_reminder_fee_amount<?php echo $row->order_id ?>" 
                                        value="<?php echo $billyPluginParams->get('billy_reminder_fee_amount');?>" 
                                        placeholder="<?php echo JText::_("COM_REDSHOP_BILLY_REMINDER_FEE_AMOUNT"); ?>" />
									<input class="hasPopover order-row-235px" type="text" 
                                        title data-content="<?php echo JText::_("COM_REDSHOP_BILLY_REMINDER_FEE_NOTE"); ?>" 
                                        data-original-title="<?php echo JText::_("COM_REDSHOP_BILLY_REMINDER_FEE_PROCENT"); ?>" 
                                        name="billy_reminder_fee_procent<?php echo $row->order_id ?>" 
                                        id="billy_reminder_fee_procent<?php echo $row->order_id ?>" 
                                        value="<?php echo $billyPluginParams->get('billy_reminder_fee_procent');?>" 
                                        placeholder="<?php echo JText::_("COM_REDSHOP_BILLY_REMINDER_FEE_PROCENT"); ?>" />
									<br/>
									<select class="order-row-235px" 
                                            name="billy_reminder_email_subject<?php echo $row->order_id ?>" 
                                            id="billy_reminder_email_subject<?php echo $row->order_id ?>" 
                                            onChange="updateEmailBody(this.value, '<?php echo $row->order_id; ?>');">
										<option value="1" />
                                            <?php echo $billyPluginParams->get('billy_reminder_email_subject_1');?> #<?php echo $row->order_id ?>
                                        </option>
										<option value="2" />
                                            <?php echo $billyPluginParams->get('billy_reminder_email_subject_2');?> #<?php echo $row->order_id ?>
                                        </option>
										<option value="3" />
                                            <?php echo $billyPluginParams->get('billy_reminder_email_subject_3');?> #<?php echo $row->order_id ?>
                                        </option>
									</select> <?php
							    	$rconfirm = 'document.reminder.order_id.value=\'' . $row->order_id . '\';document.reminder.billy_invoice_no.value=\'' . $row->billy_invoice_no . '\';document.reminder.billy_reminder_fee_amount_hide.value=document.getElementById(\'billy_reminder_fee_amount' . $row->order_id  . '\').value;document.reminder.billy_reminder_fee_procent_hide.value=document.getElementById(\'billy_reminder_fee_procent' . $row->order_id  . '\').value;document.reminder.billy_reminder_email_subject_hide.value=document.getElementById(\'billy_reminder_email_subject' . $row->order_id  . '\').value;document.reminder.billy_reminder_email_body_hide.value=document.getElementById(\'billy_reminder_email_subject' . $row->order_id  . '\').value;document.reminder.submit();'; ?>
									<input type="button" class="btn btn-small btn-warning order-row-235px" 
                                        value="<?php echo JText::_("COM_REDSHOP_BILLY_SEND_REMINDER"); ?>"
									    onclick="javascript:<?php echo $rconfirm; ?>">
								</div> <?php
					    	}
							$reminders = RedshopBilly::getSentReminders($row->billy_invoice_no);
							if ($reminders && $overdueDays > 0) { ?>
								<span style="font-size:11px"> 
                                    <?php echo JText::_('COM_REDSHOP_BILLY_REMINDER_PREVIOUS_SENT'); ?>
                                </span><br> <?php
								foreach($reminders as $reminder) {
									echo '<span class="label order_status_btn order-payment-row">';
									echo '<b>' . $reminder->emailSubject . '</b><br>';
									echo date("d M Y - H:i", strtotime($reminder->createdTime));
									echo ' | ';
									echo JText::_('COM_REDSHOP_BILLY_REMINDER_FEE') . ' ' . 
                                            $reminder->flatFee.' / '.$reminder->percentageFee.'%';
									echo '</span>';
									echo '<br>';
								}
							}
							if ($row->overdue_limit < 0 && $row->order_payment_status !== 'Paid'
                                    && $overdueDays > 0) { ?>
								<span style="font-size:11px"> 
                                    <?php echo JText::_('COM_REDSHOP_BILLY_NEXT_REMINDER_IN_DAYS') . 
                                    ' <b>'.$row->overdue_limit . ' ' . JText::_('COM_REDSHOP_DAYS').'</b>'; ?>
                                </span> <?php
							}
						}
                    }
                    // Billy section END ?>
                </td>
                <td>
                    <?php
					if ($shippingDetail[0] !== 'plgredshop_shippingself_pickup') {
						$shipping = RedshopEntityOrder::getInstance($row->order_id)->getShipping(); 
                        if ($row->shop_id) {
                            $shop_id_trim = explode("|", $row->shop_id); ?>
						    <div style="font-weight:bold">
						        <?php echo $shop_id_trim[1]; ?>
						    </div> <?php
                        }
						if (!empty($shipping->company_name)) { ?>					
							<b><?php echo $shipping->company_name; ?></b>
							<br>
							<?php echo $shipping->firstname; ?>
							<?php echo $shipping->lastname; ?>
							<?php
						} else { ?>
							<b>
							<?php echo $shipping->firstname; ?> 
							<?php echo $shipping->lastname; ?>
							</b>
							<?php
						} ?>
						<br>
						<?php echo $shipping->address; ?>
						<br>
						<?php echo $shipping->zipcode; ?>
						<?php echo $shipping->city; ?> 
						<?php
					} ?>                
                </td>
                <td>
                    <?php
                    echo "<b>" . $shipping_name = Redshop\Shipping\Tag::replaceShippingMethod($row, "{shipping_method}") . "</b>";
                    echo "<br />";
                    if (Redshop::getConfig()->get('POSTDK_INTEGRATION')) {
                        $shippingParams = new JRegistry;

                       if (!empty($shippingDetail[0])) {
                            $shippingPlugin = JPluginHelper::getPlugin(
                                'redshop_shipping',
                                str_replace('plgredshop_shipping', '', strtolower($shippingDetail[0]))
                            );

                            if (!empty($shippingPlugin)) {
                                $shippingParams = new JRegistry($shippingPlugin->params);
                            }
                        }

                        // Checking 'plgredshop_shippingdefault_shipping' to support backward compatibility
                        $allowPacsoftLabel = ($shippingDetail[0] === 'plgredshop_shippingdefault_shipping' || (boolean)$shippingParams->get(
                                'allowPacsoftLabel'
                            ));

                        if ($allowPacsoftLabel) {
                            if ($row->order_label_create) {
                                echo JTEXT::_("COM_REDSHOP_XML_ALREADY_GENERATED");
                            } else { ?>
                                <span style="display:none"> <?php
                                echo JHtml::_(
                                    'redshopcalendar.calendar',
                                    date($calendarFormat),
                                    'specifiedDate' . $i,
                                    'specifiedDate' . $i,
                                    $calendarFormat,
                                    array('class' => 'form-control', 'size' => '15', 'maxlength' => '19')
                                );
                                ?>
                                </span>
                                <input type="button" class="btn btn-default"
                                    value="<?php echo JTEXT::_('COM_REDSHOP_CREATE_LABEL'); ?>"
                                    onclick="javascript:document.parcelFrm.order_id.value='<?php echo $row->order_id; ?>';
                                        document.parcelFrm.specifiedSendDate.value=document.getElementById('specifiedDate<?php echo $i; ?>').value;
                                        document.parcelFrm.submit();">
                                <?php
                            }
                        }
                    }
                    ?>
                </td>
                <?php if (Redshop::getConfig()->get('USE_STOCKROOM') == 1) : ?>
                    <?php
                    $orderItems   = RedshopHelperOrder::getOrderItemDetail($row->order_id);
                    $stockroomIds = array();

                    foreach ($orderItems as $orderItem) {
                        if (!empty($orderItem->stockroom_id)) {
                            $stockroomIds[] = (int)$orderItem->stockroom_id;
                        }
                    }
                    ?>
                    <td align="center">
                        <?php if (!empty($stockroomIds)): ?>
                            <?php
                            $stockrooms = RedshopHelperStockroom::getStockroom(implode(',', $stockroomIds), 1);
                            ?>
                            <?php foreach ($stockrooms as $stockroom): ?>
                                <?php echo $stockroom->name ?>
                                <br/>
                                <?php echo $stockroom->min_del_time . "-" . $stockroom->max_del_time . " " . $stockroom->delivery_time ?>
                                <br/>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td align="center">
                        <?php
                        if (!empty($stockroomIds)) {
                            $max_delivery = RedshopHelperStockroom::getStockroomMaxDelivery(
                                implode(',', $stockroomIds)
                            );
                            $orderDate    = RedshopHelperDatetime::convertDateFormat($row->cdate);

                            $stamp         = mktime(
                                0,
                                0,
                                0,
                                date('m', $row->cdate),
                                date('d', $row->cdate) + $max_delivery[0]->max_del_time,
                                date('Y', $row->cdate)
                            );
                            $delivery_date = date('d/m/Y', $stamp);
                            $current_date  = date('d/m/Y');
                            $dateDiff      = RedshopHelperStockroom::getDateDiff($stamp, time());

                            if ($dateDiff < 0) {
                                $dateDiff = 0;
                            }

                            echo $dateDiff . " " . $max_delivery[0]->delivery_time;
                        }
                        ?>
                    </td>
                <?php endif; ?>
            </tr>

            <?php $k = 1 - $k; ?>
        <?php endfor; ?>
        </tbody>
        <tfoot>
        <td colspan="13">
            <?php if (version_compare(JVERSION, '3.0', '>=')): ?>
                <div class="redShopLimitBox">
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            <?php endif; ?>
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
        </tfoot>
    </table>
    <input type="hidden" name="return" value="order"/>
    <input type="hidden" name="view" value="order"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="order_id"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <input type="hidden" name="mass_change_order_status" value=""/>
    <input type="hidden" name="mass_change_payment_status" value=""/>
    <input type="hidden" name="mass_mail_sending" value=""/>
</form>
<form name='invoice' method="post">
    <input name="view" value="order" type="hidden">
    <input name="order_id" value="" type="hidden">
    <input name="option" value="com_redshop" type="hidden">
    <input name="task" value="bookInvoice" type="hidden">
    <input name="bookInvoiceDate" value="" type="hidden">
</form>
<form name='binvoice' method="post">
	<input name="view" value="order" type="hidden">
	<input name="order_id" value="" type="hidden">
	<input name="option" value="com_redshop" type="hidden">
	<input name="task" value="billybookInvoice" type="hidden">
	<input name="onlycashbook" value="" type="hidden">
	<input name="bookwithCashbook" value="" type="hidden">
	<input name="onlybook" value="" type="hidden">
</form>
<form name='reminder' method="post">
	<input name="view" value="order" type="hidden">
	<input name="order_id" value="" type="hidden">
	<input name="billy_invoice_no" value="" type="hidden">
	<input name="option" value="com_redshop" type="hidden">
	<input name="task" value="sendReminder" type="hidden">
	<input name="billy_reminder_fee_amount_hide" value="" type="hidden">
	<input name="billy_reminder_fee_procent_hide" value="" type="hidden">
	<input name="billy_reminder_email_subject_hide" value="" type="hidden">
	<input name="billy_reminder_email_body_hide" value="" type="hidden">
	<input name="sendReminder" value="" type="hidden">
</form>
<form name='parcelFrm' method="post">
    <input name="specifiedSendDate" value="" type="hidden">
    <input name="view" value="order" type="hidden">
    <input name="order_id" value="" type="hidden">
    <input name="option" value="com_redshop" type="hidden">
    <input name="task" value="generateParcel" type="hidden">
</form>
<!-- Mass Order Status modal -->
<div class="modal fade" id="massOrderStatusChange" role="dialog" aria-labelledby="massOrderStatusChangelabel"
     tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="massOrderStatusChangelabel"><?php echo JText::_(
                        'COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL'
                    ) ?></h3>
            </div>
            <div class="modal-body form-vertical">
                <div class="container-fluid">
                    <div class="form-group">
                        <div class="row">
                            <label class="control-label col-md-12">
                                <?php echo JText::_('COM_REDSHOP_ORDER') ?>:
                                <div style="display: inline;" id="checked_orders"></div>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?></label>
                                <?php
                                echo JHtml::_(
                                    'select.genericlist',
                                    RedshopHelperOrder::getOrderStatusList(),
                                    'mass_change_order_status',
                                    ' class="form-control" size="1" ',
                                    'value',
                                    'text',
                                    'C'
                                );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label"><?php echo JText::_(
                                        'COM_REDSHOP_PAYMENT_STATUS'
                                    ) ?></label>
                                <?php
                                $massChangePaymentStatus[] = JHtml::_(
                                    'select.option',
                                    'Paid',
                                    JText::_('COM_REDSHOP_PAYMENT_STA_PAID')
                                );
                                $massChangePaymentStatus[] = JHtml::_(
                                    'select.option',
                                    'Unpaid',
                                    JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID')
                                );
                                $massChangePaymentStatus[] = JHtml::_(
                                    'select.option',
                                    'Partial Paid',
                                    JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID')
                                );
                                echo JHtml::_(
                                    'select.genericlist',
                                    $massChangePaymentStatus,
                                    'mass_change_payment_status',
                                    ' class="form-control" size="1" ',
                                    'value',
                                    'text',
                                    ''
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row-fluid">
                            <label class="radio col-md-12">
                                <input type="checkbox" value="1" name="mass_mail_sending"/> <?php echo JText::_(
                                    'COM_REDSHOP_SEND_ORDER_MAIL'
                                ) ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_BTN_CLOSE') ?>
                </button>
                <button type="button" class="btn btn-primary" onclick="massStatusChange('allStatusExceptPacsoft');">
                    <?php echo JText::_('COM_REDSHOP_APPLY'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (Redshop::getConfig()->get('POSTDK_INTEGRATION')): ?>
    <div class="modal fade" id="massOrderStatusPacsoft" role="dialog" aria-labelledby="massOrderStatusPacsoftLabel"
         tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="massOrderStatusPacsoftLabel">
                        <?php echo JText::_('COM_REDSHOP_CHANGE_STATUS_TO_ALL_WITH_PACSOFT_LBL') ?>
                    </h3>
                </div>
                <div class="modal-body form-vertical">
                    <div class="container-fluid">
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-md-12">
                                    <?php echo JText::_('COM_REDSHOP_ORDER') ?>:
                                    <div style="display: inline;" id="checked_orders"></div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label"><?php echo JText::_(
                                            'COM_REDSHOP_ORDER_STATUS'
                                        ) ?></label>
                                    <?php
                                    echo JHtml::_(
                                        'select.genericlist',
                                        RedshopHelperOrder::getOrderStatusList(),
                                        'mass_change_order_status',
                                        ' class="form-control" size="1" ',
                                        'value',
                                        'text',
                                        'C'
                                    );
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><?php echo JText::_(
                                            'COM_REDSHOP_PAYMENT_STATUS'
                                        ) ?></label>
                                    <?php
                                    $massChangePaymentStatus[] = JHtml::_(
                                        'select.option',
                                        'Paid',
                                        JText::_('COM_REDSHOP_PAYMENT_STA_PAID')
                                    );
                                    $massChangePaymentStatus[] = JHtml::_(
                                        'select.option',
                                        'Unpaid',
                                        JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID')
                                    );
                                    $massChangePaymentStatus[] = JHtml::_(
                                        'select.option',
                                        'Partial Paid',
                                        JText::_(
                                            'COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID'
                                        )
                                    );
                                    echo JHtml::_(
                                        'select.genericlist',
                                        $massChangePaymentStatus,
                                        'mass_change_payment_status',
                                        ' class="form-control" size="1" ',
                                        'value',
                                        'text',
                                        ''
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row-fluid">
                                <label class="radio col-md-12">
                                    <input type="checkbox" value="1" name="mass_mail_sending"/> <?php echo JText::_(
                                        'COM_REDSHOP_SEND_ORDER_MAIL'
                                    ) ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_BTN_CLOSE') ?>
                    </button>
                    <button type="button" class="btn btn-primary" onclick="massPacsoftStatusChange('allstatus');">
                        <?php echo JText::_('COM_REDSHOP_APPLY'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(document).on("keydown", "#filter", function (e) {
            if (e.keyCode == 13) {
                jQuery("input[name=task]").val("");
                jQuery("#adminForm").submit();
            }
        });
    });
</script>
<script type="text/javascript">
	function updateEmailBody(subject_number, order_id) {
		if(subject_number && order_id) {
			document.getElementById('billy_reminder_email_body'+order_id).value=subject_number;
		}
	}
</script>
<script type="text/javascript">
	function callTimeline(billy_invoice_no,row_id) {
		jQuery.ajax({
			data: { task: "getInvoiceTimelines", billy_invoice_no:billy_invoice_no },
			success: function(result, status, xhr) { 
				jQuery("#invoiceTimeLine"+row_id).html(result); },
			error: function() { console.log('ajax call for Billy timeline failed'); },
		});
	}
</script>