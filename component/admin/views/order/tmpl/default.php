<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

$producthelper = new producthelper;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/shipping.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/stockroom.php';

global $context;

$app = JFactory::getApplication();

$order_function = new order_functions;
$config = new Redconfiguration;
$option = JRequest::getVar('option');
$filter = $app->getUserStateFromRequest($context . 'filter', 'filter', 0);
$lists = $this->lists;
$model = $this->getModel('order');
$redhelper = new redhelper;
$shippinghelper = new shipping;
$stockroomhelper = new rsstockroomhelper;
$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('redshop_product');
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if (pressbutton == 'add')
		{
			<?php      $link = 'index.php?option=' . $option . '&view=addorder_detail';
				$link = $redhelper->sslLink($link);
		?>
			window.location = '<?php echo $link;?>';
			return;
			// form.view.value="addorder_detail";
		}
		if ((pressbutton == 'allstatus'))
		{
			if (document.getElementById('order_status_all').value == '0') {
				alert('<?php echo JText::_('COM_REDSHOP_SELECT_NEW_STATUS' ); ?>');
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
		document.getElementById('filter').value='';
		document.getElementById('filter_by').value='';
		document.adminForm.submit();
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=order'); ?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
<table class="adminlist" width="100%">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_NEW_STATUS'); ?>
			: <?php echo $order_function->getstatuslist('order_status_all', '', "class=\"inputbox\" size=\"1\" "); ?>
		</td>

		<td>
			<?php echo JText::_('COM_REDSHOP_FILTER'); ?>:
			<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"/>
			<?php echo $lists['filter_by'];?>
			<button name="search" id="search" onclick="document.adminForm.submit();" style="font-size:0.909em">
				<?php echo JText::_('COM_REDSHOP_GO');?>
			</button>
			<input type="button" onclick="resetfilter();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
		</td>

		<td valign="bottom" align="right" class="key">
			<?php echo $lists['filter_payment_status'] . " " . $lists['filter_status']; ?>
		</td>
	</tr>
</table>
<table class="adminlist">
<thead>
<tr>
	<th width="5%">
		<?php echo JText::_('COM_REDSHOP_NUM'); ?>
	</th>
	<th width="5%" class="title">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->orders); ?>);"/>
	</th>
	<th class="title" width="5%">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th class="title" width="10%">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_NUMBER', 'order_number', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<?php if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT == 2)
	{ ?>
		<th width="10%">
			<?php echo  JHTML::_('grid.sort', 'COM_REDSHOP_BOOKINVOICE_NUMBER', 'bookinvoice_number', $this->lists['order_Dir'], $this->lists['order']); ?>
		</th>
	<?php } ?>
	<th width="10%">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FULLNAME', 'uf.firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th width="10%">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_EMAIL', 'uf.user_email', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th width="28%">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_STATUS', 'order_status', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<?php if (USE_STOCKROOM == 1)
	{ ?>
		<th width="15%">
			<?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?>
		</th>
		<th width="10%">
			<?php echo JText::_('COM_REDSHOP_STOCKROOM_DELIVERY_TIME'); ?>
		</th>
	<?php } ?>

	<th width="7%" nowrap="nowrap">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_DATE', 'cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th width="7%">
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_TOTAL', 'order_total', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th>
	</th>
	<?php if (POSTDK_INTEGRATION)
		echo "<th></th>";?>
</tr>
</thead>
<?php
$send_mail_to_customer = 0;

if (SEND_MAIL_TO_CUSTOMER)
{
	$send_mail_to_customer = "checked";
}

$k = 0;

for ($i = 0, $n = count($this->orders); $i < $n; $i++)
{
	$row = & $this->orders[$i];
	$row->id = $row->order_id;
	$link = 'index.php?option=' . $option . '&view=order_detail&task=edit&cid[]=' . $row->order_id;
	$link = $redhelper->sslLink($link);
	/**
	 * @var $data
	 * Trigger event onAfterDisplayProduct
	 * Show content return by plugin directly into product page after display product title
	 */
	$data->highlight = new stdClass;
	$results = $dispatcher->trigger('toHighlightGrid', array(& $row));
	$data->highlight->toHighlightGrid = trim(implode("\n", $results));
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td class="order">
			<?php echo $this->pagination->getRowOffset($i); ?>
			<?php echo $data->highlight->toHighlightGrid;?>
		</td>
		<td class="order">
			<?php echo JHTML::_('grid.id', $i, $row->id); ?>
		</td>
		<td align="center">
			<a href="<?php echo $link; ?>"
			   title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER'); ?>"><?php echo $row->order_id; ?></a>
		</td>
		<td align="center"><?php echo $row->order_number; ?></td>
		<?php
		if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT == 2 && $row->invoice_no && $row->is_booked == 1 && $row->bookinvoice_number)
		{
			?>
			<td align="center"><?php echo $row->bookinvoice_number; ?></td>
		<?php } ?>
		<td><?php
			echo $row->firstname . ' ' . $row->lastname;
			echo ($row->is_company && $row->company_name != "") ? "<br />" . $row->company_name : ""; ?>
		</td>
		<td>
			<?php echo $row->user_email; ?>
		</td>
		<td>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<?php
						$linkupdate = JRoute::_('index.php?option=' . $option . '&view=order&task=update_status&return=order&order_id[]=' . $row->order_id);
						echo $order_function->getstatuslist('order_status' . $row->order_id, $row->order_status, "class=\"inputbox\" size=\"1\" ");
						echo "&nbsp";
						echo $order_function->getpaymentstatuslist('order_paymentstatus' . $row->order_id, $row->order_payment_status, "class=\"inputbox\" size=\"1\" ");
						?>
					</td>
				</tr>
				<tr>
					<td><textarea
							name="customer_note<?php echo $row->order_id; ?>"><?php echo $row->customer_note;?></textarea>
					</td>
					<!-- <td>
							<input class="inputbox" name="ic<?php echo $row->order_id ;?>" id="include_comment<?php echo $row->order_id ;?>" value="N" type="checkbox" onclick="if(this.checked==true) {this.value = 'Y';} else {this.value = 'N';}"><?php echo JText::_('COM_REDSHOP_INCLUDE_COMMENT_MSG' ); ?><br />
							<input class="inputbox" name="nc<?php echo $row->order_id ;?>" id="notify_customer<?php echo $row->order_id ;?>" value="N" type="checkbox" onclick="if(this.checked==true) {this.value = 'Y';} else {this.value = 'N';}"><?php echo JText::_('COM_REDSHOP_NOTIFY_CUSTOMER_MSG' ); ?>
						</td> -->
				</tr>
				<tr>
					<td>
						<input type="checkbox" <?php echo $send_mail_to_customer;?>  value=""
						       name="sendordermail<?php echo $row->order_id; ?>"
						       id="sendordermail<?php echo $row->order_id; ?>"/><?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL'); ?>
					</td>
				</tr>
				<tr>
					<!-- <td></td> -->
					<td>
						<input class="button"
						       onclick="location.href = '<?php echo $linkupdate; ?>&status='+document.adminForm.order_status<?php echo $row->order_id; ?>.value+'&customer_note='+encodeURIComponent(document.adminForm.customer_note<?php echo $row->order_id; ?>.value)+'&order_sendordermail='+document.adminForm.sendordermail<?php echo $row->order_id; ?>.checked+'&order_paymentstatus='+document.adminForm.order_paymentstatus<?php echo $row->order_id; ?>.value  ; "
						       name="order_status" value="<?php echo JText::_('COM_REDSHOP_UPDATE_STATUS_BUTTON'); ?>"
						       type="button">
					</td>
				</tr>
			</table>
		</td>
		<?php if (USE_STOCKROOM == 1)
		{ ?>
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

					for ($s = 0; $s < count($stockroom_list); $s++)
					{
						echo $stockroom_list[$s]->stockroom_name;
						echo "<br>";
						echo $delivery_time = $stockroom_list[$s]->min_del_time . "-" . $stockroom_list[$s]->max_del_time . " " . $stockroom_list[$s]->delivery_time;
						echo "<br>";
					}
				}

				?>

			</td>
			<td align="center"> <?php
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
				} ?> </td>
		<?php } ?>
		<td align="center">
			<?php echo $config->convertDateFormat($row->cdate); ?>
		</td>
		<td>
			<?php echo $producthelper->getProductFormattedPrice($row->order_total);//,false,$row->order_item_currency);//CURRENCY_SYMBOL.number_format($row->order_total,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR); ?>
		</td>
		<td><?php if ($row->invoice_no != '' && $row->is_booked == 0)
			{
				if ($row->is_company == 1 && $row->ean_number != "")
				{
					echo JText::_('COM_REDSHOP_MANUALY_BOOK_INVOICE_FROM_ECONOMIC');
				}
				else
				{
					$confirm = 'if(confirm(\'' . JText::_('COM_REDSHOP_CONFIRM_BOOK_INVOICE') . '\')) { document.invoice.order_id.value=\'' . $row->order_id . '\';document.invoice.bookInvoiceDate.value=document.getElementById(\'bookDate' . $i . '\').value;document.invoice.submit(); }';
					if ($row->order_payment_status == 'Paid' || $row->order_status == 'PR' || $row->order_status == 'C')
					{
						$confirm = 'document.invoice.order_id.value=\'' . $row->order_id . '\';document.invoice.bookInvoiceDate.value=document.getElementById(\'bookDate' . $i . '\').value;document.invoice.submit();';
					}
					echo JHTML::_('calendar', date('Y-m-d'), 'bookDate' . $i, 'bookDate' . $i, $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19'));    ?>
					<input type="button" class="button" value="<?php echo JText::_("COM_REDSHOP_BOOK_INVOICE"); ?>"
					       onclick="javascript:<?php echo $confirm; ?>"><br/>
				<?php
				}
			}
			echo "</td>";
			$details = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $row->ship_method_id)));
			if ($details[0] === 'plgredshop_shippingdefault_shipping' && POSTDK_INTEGRATION)
			{
				echo "<td>";
				if ($row->order_label_create)
				{
					echo JTEXT::_("COM_REDSHOP_XML_ALREADY_GENERATED");
				}
				else
				{
					echo JHTML::_('calendar', date('Y-m-d'), 'specifiedDate' . $i, 'specifiedDate' . $i, $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19'));    ?>
					<input type="button" class="button" value="<?php echo JTEXT::_('COM_REDSHOP_CREATE_LABEL'); ?>" onclick="javascript:document.parcelFrm.order_id.value='<?php echo $row->order_id; ?>';document.parcelFrm.specifiedSendDate.value=document.getElementById('specifiedDate<?php echo $i; ?>').value;document.parcelFrm.submit();">
				<?php
				}

				echo "</td>";
			}
			else
			{
				echo '<td>' . JText::_('COM_REDSHOP_NO_PACSOFT_LABEL') . '</td>';
			}
		?>
	</tr>
	<?php
	$k = 1 - $k;
}
?>
<tfoot>
<td colspan="13">
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
