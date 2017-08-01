<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$productHelper = productHelper::getInstance();

global $context;

$app    = JFactory::getApplication();
$config = Redconfiguration::getInstance();

$lists           = $this->lists;
$model           = $this->getModel('order');
$stockroomHelper = rsstockroomhelper::getInstance();
$dispatcher      = RedshopHelperUtility::getDispatcher();
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
            document.adminForm.task.value = '';
        });

        jQuery('#filter_by, #filter_payment_status, #filter_status').change(function (e) {
            document.adminForm.task.value = '';
        });

        jQuery('.order_status_change').click(function (event) {
            event.preventDefault();
            var target = jQuery(this).attr('data-target');
            jQuery('#' + target).slideToggle();
        });
    });

    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton == 'add') {
			<?php $link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=addorder_detail'); ?>
            window.location = '<?php echo $link;?>';
            return;
        }

        switch (pressbutton) {
            case 'edit':
                form.view.value = "order_detail";
                break;
            case 'multiprint_order':
                form.view.value = "order";
                break;
            case 'remove':
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
        }
        catch (e) {
        }

        form.submit();
    }

    resetFilter = function () {
        document.adminForm.task.value = '';
        document.getElementById('filter').value = '';
        document.getElementById('filter_by').value = '';
        document.getElementById('filter_payment_status').value = '';
        document.getElementById('filter_status').value = '0';
        document.getElementById('filter_from_date').value = '';
        document.getElementById('filter_to_date').value = '';
        document.adminForm.submit();
    }
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
            }
            catch (e) {
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
            }
            catch (e) {
            }

            form.submit();
        })(jQuery);
    }
</script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("#massOrderStatusChange").on('show.bs.modal', function (event) {
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

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=order'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <div class="filterTool">
            <div class="filterItem">
                <div class="btn-wrapper input-append">
                    <input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
                           placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
                    <button class="btn" onclick="document.adminForm.submit();"><?php echo JText::_('COM_REDSHOP_SEARCH'); ?></button>
                    <input type="button" class="btn reset" onclick="resetFilter();" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"/>
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
            <input name="search" class="btn" type="submit" id="search" value="<?php echo JText::_('COM_REDSHOP_GO'); ?>"/>
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
            <th class="title" width="5%">
				<?php echo JHTML::_('grid.sort', 'ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']) ?>
            </th>
			<?php if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2): ?>
                <th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_BOOKINVOICE_NUMBER', 'bookinvoice_number', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
			<?php endif; ?>
            <th>
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CUSTOMER', 'uf.firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_EMAIL', 'uf.user_email', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CUSTOMER_TYPE', 'is_company', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="20%">
				<?php echo JText::_('COM_REDSHOP_ORDERS_CUSTOMER_NOTE') ?>
            </th>
            <th width="5%">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_STATUS', 'order_status', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="5%">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PAYMENT', 'order_payment_status', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="10%">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_TOTAL', 'order_total', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="1"></th>
			<?php if (Redshop::getConfig()->get('USE_STOCKROOM') == 1): ?>
                <th width="10%">
					<?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?>
                </th>
                <th width="10%">
					<?php echo JText::_('COM_REDSHOP_STOCKROOM_DELIVERY_TIME'); ?>
                </th>
			<?php endif; ?>
            <th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_DATE', 'cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="1">&nbsp;</th>
            <th></th>
			<?php if (Redshop::getConfig()->get('POSTDK_INTEGRATION')): ?>
                <th></th>
			<?php endif; ?>
        </tr>
        </thead>
        <tbody>
		<?php
		$send_mail_to_customer = Redshop::getConfig()->get('SEND_MAIL_TO_CUSTOMER') ? 'checked' : 0;
		$k                     = 0;
		?>
		<?php for ($i = 0, $n = count($this->orders); $i < $n; $i++): ?>
			<?php
			$row     = $this->orders[$i];
			$row->id = $row->order_id;
			$link    = 'index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $row->order_id;
			$link    = RedshopHelperUtility::getSSLLink($link);

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
                    <a href="<?php echo $link ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER') ?>"><?php echo $row->order_id ?></a>
                </td>
				<?php if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2
					&& $row->invoice_no && $row->is_booked == 1 && $row->bookinvoice_number
				): ?>
                    <td align="center"><?php echo $row->bookinvoice_number ?></td>
				<?php endif; ?>
                <td>
                    <a href="<?php echo $link ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER') ?>">
						<?php echo $row->firstname . ' ' . $row->lastname ?>
                    </a>
                </td>
                <td>
                    <a href="mailto:<?php echo $row->user_email ?>" target="_blank"><?php echo $row->user_email ?></a>
                </td>
                <td>
					<?php if ($row->is_company): ?>
                        <span class="text-info"><?php echo $row->company_name; ?></span>
					<?php else: ?>
                        <span class="text-muted"><?php echo JText::_('COM_REDSHOP_PRIVATE'); ?></span>
					<?php endif; ?>
                </td>
                <td>
					<?php echo JHtml::_('redshopgrid.slidetext', $row->customer_note) ?>
                </td>
                <td>
                    <span class="label order_status_<?php echo strtolower($row->order_status) ?>"><?php echo $row->order_status_name ?></span>
                </td>
                <td>
					<?php $paymentStatusClass = 'label order_payment_status_' . strtolower($row->order_payment_status); ?>
                    <span class="<?php echo $paymentStatusClass ?>">
			<?php if ($row->order_payment_status == 'Paid'): ?>
				<?php echo JText::_('COM_REDSHOP_PAYMENT_STA_PAID') ?>
			<?php elseif ($row->order_payment_status == 'Unpaid'): ?>
				<?php echo JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID') ?>
			<?php elseif ($row->order_payment_status == 'Partial Paid' || $row->order_payment_status == 'PartialPaid'): ?>
				<?php echo JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID') ?>
			<?php endif; ?>
			</span>
                </td>
                <td>
					<?php echo $productHelper->getProductFormattedPrice($row->order_total); ?>
                </td>
                <td>
					<?php
					$linkUpdate = 'index.php?option=com_redshop&view=order&task=update_status&return=order&order_id[]=' . $row->order_id;
					?>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#order_status_form<?php echo $row->id ?>">
                        <i class="icon icon-edit"></i> <?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON') ?>
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
                                                    <label><?php echo JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL'); ?></label>
                                                    <textarea class="form-control" name="customer_note<?php echo $row->order_id ?>"
                                                              style="resize: none;"><?php echo $row->customer_note ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        <input type="checkbox" <?php echo $send_mail_to_customer ?> value="1"
                                                               name="sendordermail<?php echo $row->order_id ?>"/> <?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL') ?>
                                                    </label>
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
                                            onclick="location.href='<?php echo JRoute::_($linkUpdate, false) ?>&status='+document.adminForm.order_status<?php echo $row->order_id ?>.value+'&customer_note='+encodeURIComponent(document.adminForm.customer_note<?php echo $row->order_id ?>.value)+'&order_sendordermail='+document.adminForm.sendordermail<?php echo $row->order_id ?>.checked+'&order_paymentstatus='+document.adminForm.order_paymentstatus<?php echo $row->order_id ?>.value;"
                                            value="<?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON'); ?>">
										<?php echo JText::_('JTOOLBAR_SAVE') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php echo $data->highlight->toHighlightGrid; ?>
                </td>
				<?php if (Redshop::getConfig()->get('USE_STOCKROOM') == 1) : ?>
					<?php
					$orderItems   = RedshopHelperOrder::getOrderItemDetail($row->order_id);
					$stockroomIds = array();

					foreach ($orderItems as $orderItem)
					{
						if (!empty($orderItem->stockroom_id))
						{
							$stockroomIds[] = (int) $orderItem->stockroom_id;
						}
					}
					?>
                    <td align="center">
						<?php if (!empty($stockroomIds)): ?>
							<?php
							$stockrooms = RedshopHelperStockroom::getStockroom(implode(',', $stockroomIds), 1);
							?>
							<?php foreach ($stockrooms as $stockroom): ?>
								<?php echo $stockroom->stockroom_name ?>
                                <br/>
								<?php echo $stockroom->min_del_time . "-" . $stockroom->max_del_time . " " . $stockroom->delivery_time ?>
                                <br/>
							<?php endforeach; ?>
						<?php endif; ?>
                    </td>
                    <td align="center">
						<?php
						$carthelper = rsCarthelper::getInstance();
						echo $shipping_name = RedshopHelperShippingTag::replaceShippingMethod($row, "{shipping_method}");
						echo "<br />";

						if (!empty($stockroomIds))
						{
							$max_delivery = RedshopHelperStockroom::getStockroomMaxDelivery(implode(',', $stockroomIds));
							$orderDate    = $config->convertDateFormat($row->cdate);

							$stamp         = mktime(0, 0, 0, date('m', $row->cdate), date('d', $row->cdate) + $max_delivery[0]->max_del_time, date('Y', $row->cdate));
							$delivery_date = date('d/m/Y', $stamp);
							$current_date  = date('d/m/Y');
							$dateDiff      = $stockroomHelper->getdateDiff($stamp, time());

							if ($dateDiff < 0)
							{
								$dateDiff = 0;
							}

							echo $dateDiff . " " . $max_delivery[0]->delivery_time;
						}
						?>
                    </td>
				<?php endif; ?>
                <td align="center">
					<?php echo RedshopHelperDatetime::convertDateFormat($row->cdate); ?>
                </td>
                <td>
					<?php if (RedshopHelperPdf::isAvailablePdfPlugins()): ?>
                        <a href="index.php?option=com_redshop&task=order.printPDF&id=<?php echo $row->order_id ?>" target="_blank">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
					<?php else: ?>
                        <span class="disabled"><i class="fa fa-file-pdf-o"></i></span>
					<?php endif; ?>
                </td>
                <td>
					<?php if ($row->invoice_no != ''): ?>
						<?php if ($row->is_booked == 0 && $row->bookinvoice_date <= 0): ?>
							<?php if ($row->is_company == 1 && $row->ean_number != ""): ?>
								<?php echo JText::_('COM_REDSHOP_MANUALY_BOOK_INVOICE_FROM_ECONOMIC') ?>
							<?php else: ?>
								<?php
								$confirm = 'if(confirm(\'' . JText::_('COM_REDSHOP_CONFIRM_BOOK_INVOICE') . '\')) { document.invoice.order_id.value=\'' . $row->order_id . '\';document.invoice.bookInvoiceDate.value=document.getElementById(\'bookDate' . $i . '\').value;document.invoice.submit(); }';

								if ($row->order_payment_status == 'Paid' || $row->order_status == 'PR' || $row->order_status == 'C')
								{
									$confirm = 'document.invoice.order_id.value=\'' . $row->order_id . '\';document.invoice.bookInvoiceDate.value=document.getElementById(\'bookDate' . $i . '\').value;document.invoice.submit();';
								}

								echo JHTML::_('calendar', date('Y-m-d'), 'bookDate' . $i, 'bookDate' . $i, $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19')); ?>
                                <br/>
                                <input type="button" class="button" value="<?php echo JText::_("COM_REDSHOP_BOOK_INVOICE"); ?>"
                                       onclick="javascript:<?php echo $confirm; ?>"><br/>
							<?php endif; ?>
						<?php elseif ($row->bookinvoice_date > 0): ?>
							<?php echo JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') ?><br/>
							<?php echo $config->convertDateFormat($row->bookinvoice_date) ?>
						<?php endif; ?>
					<?php endif; ?>
                </td>
				<?php
				if (Redshop::getConfig()->get('POSTDK_INTEGRATION'))
				{
					$details        = RedshopShippingRate::decrypt($row->ship_method_id);
					$shippingParams = new JRegistry;

					if (!empty($details[0]))
					{
						$shippingPlugin = JPluginHelper::getPlugin(
							'redshop_shipping',
							str_replace('plgredshop_shipping', '', strtolower($details[0]))
						);

						if (!empty($shippingPlugin))
						{
							$shippingParams = new JRegistry($shippingPlugin->params);
						}
					}

					// Checking 'plgredshop_shippingdefault_shipping' to support backward compatibility
					$allowPacsoftLabel = ($details[0] === 'plgredshop_shippingdefault_shipping' || (boolean) $shippingParams->get('allowPacsoftLabel'));

					if ($allowPacsoftLabel)
					{
						echo "<td>";

						if ($row->order_label_create)
						{
							echo JTEXT::_("COM_REDSHOP_XML_ALREADY_GENERATED");
						}
						else
						{
							echo JHTML::_('calendar', date('Y-m-d'), 'specifiedDate' . $i, 'specifiedDate' . $i, $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19')); ?>
                            <input type="button" class="button"
                                   value="<?php echo JTEXT::_('COM_REDSHOP_CREATE_LABEL'); ?>"
                                   onclick="javascript:document.parcelFrm.order_id.value='<?php echo $row->order_id; ?>';
                                           document.parcelFrm.specifiedSendDate.value=document.getElementById('specifiedDate<?php echo $i; ?>').value;
                                           document.parcelFrm.submit();">
							<?php
						}

						echo "</td>";
					}
					else
					{
						echo '<td>' . JText::_('COM_REDSHOP_NO_PACSOFT_LABEL') . '</td>';
					}
				}
				?>
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

<form name='parcelFrm' method="post">
    <input name="specifiedSendDate" value="" type="hidden">
    <input name="view" value="order" type="hidden">
    <input name="order_id" value="" type="hidden">
    <input name="option" value="com_redshop" type="hidden">
    <input name="task" value="generateParcel" type="hidden">
</form>
<!-- Mass Order Status modal -->
<div class="modal fade" id="massOrderStatusChange" role="dialog" aria-labelledby="massOrderStatusChangelabel" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="massOrderStatusChangelabel"><?php echo JText::_('COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL') ?></h3>
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
									RedshopHelperOrder::getOrderStatusList(), 'mass_change_order_status',
									' class="form-control" size="1" ',
									'value',
									'text',
									'C'
								);
								?>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label"><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?></label>
								<?php
								$massChangePaymentStatus[] = JHtml::_('select.option', 'Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PAID'));
								$massChangePaymentStatus[] = JHtml::_('select.option', 'Unpaid', JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID'));
								$massChangePaymentStatus[] = JHtml::_('select.option', 'Partial Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID'));
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
                                <input type="checkbox" value="1" name="mass_mail_sending"/> <?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL') ?>
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
    <div class="modal fade" id="massOrderStatusPacsoft" role="dialog" aria-labelledby="massOrderStatusPacsoftLabel" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                                    <label class="control-label"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?></label>
									<?php
									echo JHtml::_(
										'select.genericlist',
										RedshopHelperOrder::getOrderStatusList(), 'mass_change_order_status',
										' class="form-control" size="1" ',
										'value',
										'text',
										'C'
									);
									?>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?></label>
									<?php
									$massChangePaymentStatus[] = JHtml::_('select.option', 'Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PAID'));
									$massChangePaymentStatus[] = JHtml::_('select.option', 'Unpaid', JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID'));
									$massChangePaymentStatus[] = JHtml::_('select.option', 'Partial Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID'));
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
                                    <input type="checkbox" value="1" name="mass_mail_sending"/> <?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL') ?>
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
        jQuery(document).on('keydown', '#filter', function (e) {
            if (e.keyCode == 13) {
                jQuery('input[name=task]').val('');
                jQuery('#adminForm').submit();
            }
        })
    });
</script>
