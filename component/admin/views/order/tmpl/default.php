<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$producthelper = RedshopSiteProduct::getInstance();


global $context;

$app = JFactory::getApplication();

$order_function = order_functions::getInstance();
$config = Redconfiguration::getInstance();

$lists = $this->lists;
$model = $this->getModel('order');
$redhelper = RedshopSiteHelper::getInstance();
$shippinghelper = shipping::getInstance();
$stockroomhelper = rsstockroomhelper::getInstance();
$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('redshop_product');
?>
<style type="text/css">
	div#toolbar-box div.m{
		height: 70px;
	}
</style>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery( "#search" ).click(function(event) {
			document.adminForm.task.value = '';
		});

		jQuery('#filter_by, #filter_payment_status, #filter_status').change(function(e){
			document.adminForm.task.value = '';
		});

		jQuery('.order_status_change').click(function(event){
			event.preventDefault();
			var target = jQuery(this).attr('data-target');
			jQuery('#' + target).slideToggle();
		})
	});

	Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;

		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if (pressbutton == 'add')
		{
		<?php
			$link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=addorder_detail');
		?>
			window.location = '<?php echo $link;?>';
			return;
			// form.view.value="addorder_detail";
		}
		if ((pressbutton == 'allstatus'))
		{
			if (document.getElementById('order_status_all').value == '0') {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_NEW_STATUS') ?>");
				return false;
			}

		}
		else if ((pressbutton == 'edit') || (pressbutton == 'remove')) {
			form.view.value = "order_detail";
		}
		else if (pressbutton == 'multiprint_order') {
			form.view.value = "order";
		}

		try
		{
			form.onsubmit();
		}
		catch (e)
		{
		}

		form.submit();
	}

	resetfilter = function()
	{
		document.adminForm.task.value = '';
		document.getElementById('filter').value='';
		document.getElementById('filter_by').value='';
		document.getElementById('filter_payment_status').value='';
		document.getElementById('filter_status').value='0';
		document.getElementById('filter_from_date').value='';
		document.getElementById('filter_to_date').value='';
		document.adminForm.submit();
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=order'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
						   placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
					<button class="btn" onclick="document.adminForm.submit();"><?php echo JText::_('COM_REDSHOP_SEARCH'); ?></button>
					<input type="button" class="btn reset" onclick="resetfilter();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
				</div>
			</div>
			<div class="filterItem calendar-div">
				<?php
					$state     = $this->get('State');
					$startDate = $state->get('filter_from_date');
					$endDate   = $state->get('filter_to_date');

					echo JHtml::_(
						'calendar',
						$startDate,
						'filter_from_date',
						'filter_from_date',
						'%d-%m-%Y',
						array(
							'size' => '15',
							'maxlength' => '19',
							'placeholder' => JText::_('COM_REDSHOP_FROM') . ' ' . JText::_('JDATE')
						)
					);

					echo JHtml::_(
						'calendar',
						$endDate,
						'filter_to_date',
						'filter_to_date',
						'%d-%m-%Y',
						array(
							'size' => '15',
							'maxlength' => '19',
							'placeholder' => JText::_('COM_REDSHOP_TO') . ' ' . JText::_('JDATE')
						)
					);
				?>
				<input name="search" class="btn" type="submit" id="search" value="<?php echo JText::_('COM_REDSHOP_GO');?>"/>

			</div>

			<div class="filterItem">
				<?php echo $lists['filter_payment_status'];?>
			</div>
			<div class="filterItem">
				<?php echo $lists['filter_status'] ?>
			</div>
			<div class="filterItem">
				<?php echo $order_function->getstatuslist('order_status_all', '', "class=\"inputbox\" size=\"1\" ", 'COM_REDSHOP_NEW_STATUS') ?>
			</div>
		</div>
	</div>

	<table class="adminlist table table-striped table-hover">
		<thead>
			<tr>
				<th width="1">#</th>
				<th width="1"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
				<th class="title" width="5%">
					<?php echo JHTML::_('grid.sort', 'ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<?php if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT == 2): ?>
				<th width="10%">
					<?php echo  JHTML::_('grid.sort', 'COM_REDSHOP_BOOKINVOICE_NUMBER', 'bookinvoice_number', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<?php endif; ?>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CUSTOMER', 'uf.firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_EMAIL', 'uf.user_email', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COMPANY', 'is_company', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_STATUS', 'order_status', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="7%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_TOTAL', 'order_total', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="1">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PAYMENT', 'order_payment_status', $this->lists['order_Dir'], $this->lists['order']) ?>
				</th>
				<?php if (USE_STOCKROOM == 1): ?>
					<th width="10%">
						<?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?>
					</th>
					<th width="10%">
						<?php echo JText::_('COM_REDSHOP_STOCKROOM_DELIVERY_TIME'); ?>
					</th>
				<?php endif; ?>
				<th width="7%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_DATE', 'cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="1">&nbsp;</th>
				<th></th>
				<?php if (POSTDK_INTEGRATION): ?>
					<th></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$send_mail_to_customer = SEND_MAIL_TO_CUSTOMER ? 'checked' : 0;
			$k = 0;
			?>

			<?php for ($i = 0, $n = count($this->orders); $i < $n; $i++): ?>
				<?php
				$row     = $this->orders[$i];
				$row->id = $row->order_id;
				$link = 'index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $row->order_id;
				$link = RedshopHelperUtility::getSSLLink($link);

				/**
				 * This is an event that is using into back-end order listing page. In to grid column, below check-box.
				 * This event is called to add highlighter from which order can be identified that plug-in enhancement is included into this order.
				 */
				$data = new stdClass;
				$data->highlight = new stdClass;
				$results = $dispatcher->trigger('toHighlightGrid', array(&$row));
				$data->highlight->toHighlightGrid = trim(implode("\n", $results));
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="order">
						<?php echo $this->pagination->getRowOffset($i); ?>
						<?php echo $data->highlight->toHighlightGrid;?>
					</td>
					<td class="order">
						<?php echo JHtml::_('grid.id', $i, $row->id); ?>
					</td>
					<td align="center">
						<a href="<?php echo $link ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER') ?>"><?php echo $row->order_id ?></a>
					</td>
					<?php if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT == 2
						&& $row->invoice_no && $row->is_booked == 1 && $row->bookinvoice_number): ?>
						<td align="center"><?php echo $row->bookinvoice_number; ?></td>
					<?php endif; ?>
					<td>
						<?php echo $row->firstname . ' ' . $row->lastname ?>
					</td>
					<td>
						<a href="mailto:<?php echo $row->user_email ?>" target="_blank"><i class="fa fa-envelope"></i></a>&nbsp;<?php echo $row->user_email ?>
					</td>
					<td>
						<?php if ($row->is_company): ?>
							<p class="text-info"><?php echo $row->company_name ?></p>
						<?php else: ?>
							<p class="text-muted"><?php echo JText::_('COM_REDSHOP_PRIVATE') ?></p>
						<?php endif; ?>
					</td>
					<td>
						<?php
						$orderStatusClass = 'label order_status_' . strtolower($row->order_status);
						$linkupdate = JRoute::_('index.php?option=com_redshop&view=order&task=update_status&return=order&order_id[]=' . $row->order_id);
						?>
						<a href="javascript:void(0);" class="order_status_change" data-target="order_status_form<?php echo $row->id ?>">
							<i class="icon icon-edit"></i>
						</a>
						<span class="label <?php echo $orderStatusClass ?>"><?php echo $row->order_status_name ?></span>
						<div id="order_status_form<?php echo $row->id ?>" class="panel panel-default" style="display: none; margin-top: 15px;">
							<div class="panel-body">
								<div class="form-group">
									<label><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?></label>
									<?php echo $order_function->getstatuslist('order_status' . $row->order_id, $row->order_status, "class=\"form-control inputbox\" size=\"1\" ") ?>
								</div>
								<div class="form-group">
									<label><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS') ?></label>
									<?php echo $order_function->getpaymentstatuslist('order_paymentstatus' . $row->order_id, $row->order_payment_status, "class=\"form-control inputbox\" size=\"1\" ") ?>
								</div>
								<div class="form-group">
									<label><?php echo JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL') ?></label>
									<textarea class="form-control" name="customer_note<?php echo $row->order_id ?>"><?php echo $row->customer_note;?></textarea>
								</div>
								<div class="form-group">
									<label>
										<input type="checkbox" <?php echo $send_mail_to_customer ?> value=""
										       name="sendordermail<?php echo $row->order_id ?>"

										       id="sendordermail<?php echo $row->order_id ?>" /> <?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL') ?>
									</label>
								</div>
								<div class="form-group">
									<input class="button btn btn-success btn-block btn-small"
									       onclick="location.href = '<?php echo $linkupdate; ?>&status='+document.adminForm.order_status<?php echo $row->order_id; ?>.value+'&customer_note='+encodeURIComponent(document.adminForm.customer_note<?php echo $row->order_id; ?>.value)+'&order_sendordermail='+document.adminForm.sendordermail<?php echo $row->order_id; ?>.checked+'&order_paymentstatus='+document.adminForm.order_paymentstatus<?php echo $row->order_id; ?>.value  ; "
									       name="order_status" value="<?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON'); ?>"
									       type="button" />
								</div>
							</div>
						</div>
					</td>
					<td>
						<?php echo $producthelper->getProductFormattedPrice($row->order_total) ?>
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
					<?php if (USE_STOCKROOM == 1) : ?>
						<td align="center">
							<?php $order_items = $order_function->getOrderItemDetail($row->order_id);

							$stockroom_id = "";

							for ($st = 0; $st < count($order_items); $st++)
							{
								if ($order_items[$st]->stockroom_id != "")
								{
									$stockroom_id .= $order_items[$st]->stockroom_id . ",";
								}
							}

							if ($stockroom_id != "")
							{
								$stockroom_list = $stockroomhelper->getStockroom(substr_replace($stockroom_id, "", -1));

								for ($s = 0, $sn = count($stockroom_list); $s < $sn; $s++)
								{
									echo $stockroom_list[$s]->stockroom_name;
									echo "<br>";
									echo $delivery_time = $stockroom_list[$s]->min_del_time . "-" . $stockroom_list[$s]->max_del_time . " " . $stockroom_list[$s]->delivery_time;
									echo "<br>";
								}
							}

							?>

						</td>
						<td align="center">
						<?php
							$carthelper    = RedshopSiteCart::getInstance();
							echo $shipping_name = $carthelper->replaceShippingMethod($row, "{shipping_method}");
							echo "<br />";

							if ($stockroom_id != "")
							{
								$max_delivery = $stockroomhelper->getStockroom_maxdelivery(substr_replace($stockroom_id, "", -1));
								$orderdate = $config->convertDateFormat($row->cdate);

								$stamp = mktime(0, 0, 0, date('m', $row->cdate), date('d', $row->cdate) + $max_delivery[0]->max_del_time, date('Y', $row->cdate));
								$delivery_date = date('d/m/Y', $stamp);
								$current_date = date('d/m/Y');
								$datediff = $stockroomhelper->getdateDiff($stamp, time());

								if ($datediff < 0)
								{
									$datediff = 0;
								}

								echo $datediff . " " . $max_delivery[0]->delivery_time;
							}
							?>
						</td>
					<?php endif; ?>
					<td align="center">
						<?php echo $config->convertDateFormat($row->cdate); ?>
					</td>
					<td>
						<a href="index.php?option=com_redshop&view=order&task=multiprint_order&cid[]=<?php echo $row->order_id ?>"><i class="fa fa-file-pdf-o"></i></a>
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

									echo JHTML::_('calendar', date('Y-m-d'), 'bookDate' . $i, 'bookDate' . $i, $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19'));    ?>
									<br />
									<input type="button" class="button" value="<?php echo JText::_("COM_REDSHOP_BOOK_INVOICE"); ?>"
									       onclick="javascript:<?php echo $confirm; ?>"><br/>
								<?php endif; ?>
							<?php elseif ($row->bookinvoice_date > 0): ?>
								<?php echo JText::_('COM_REDSHOP_INVOICE_BOOKED_ON') ?><br />
								<?php echo $config->convertDateFormat($row->bookinvoice_date) ?>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<?php
						if (POSTDK_INTEGRATION)
						{
							$details = RedshopShippingRate::decrypt($row->ship_method_id);

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
									echo JHTML::_('calendar', date('Y-m-d'), 'specifiedDate' . $i, 'specifiedDate' . $i, $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19'));    ?>
									<input type="button" class="button"
										value="<?php echo JTEXT::_('COM_REDSHOP_CREATE_LABEL'); ?>"
										onclick="
											javascript:document.parcelFrm.order_id.value='<?php echo $row->order_id; ?>';
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
				<?php  echo $this->pagination->getListFooter(); ?>
			</td>
		</tfoot>
	</table>
</div>

<input type="hidden" name="return" value="order"/>
<input type="hidden" name="view" value="order"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="order_id"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
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
