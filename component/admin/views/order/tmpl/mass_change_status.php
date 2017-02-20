<?php
	$massChangePaymentStatus[] = JHtml::_('select.option', 'Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PAID'));
	$massChangePaymentStatus[] = JHtml::_('select.option', 'Unpaid', JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID'));
	$massChangePaymentStatus[] = JHtml::_('select.option', 'Partial Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID'));
	$massChangePaymentStatus   = JHtml::_('select.genericlist', $massChangePaymentStatus, 'mass_change_payment_status', ' class="inputbox" size="1" ', 'value', 'text', '');

	$massChangeStatus['mass_change_order_status'] = JHtml::_('select.genericlist', RedshopHelperOrder::getOrderStatusList(), 'mass_change_order_status', ' class="inputbox" size="1" ', 'value', 'text', 'C');
	$massChangeStatus['mass_change_payment_status'] =  $massChangePaymentStatus;

	$type = JFactory::getApplication()->input->getString('type');
?>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function($) {
		if (window.parent.document.adminForm.boxchecked.value == 0) {
			alert('Please first make a selection from the list.');
			window.parent.SqueezeBox.close();
			return false;
	 	}

	 	var checked_orders = [];
		window.parent.document.querySelectorAll('[id^="cb"][name^="cid"]:checked').forEach(function(element) {
    		checked_orders.push(element.value);
		});
		checked_orders = checked_orders.join(', ');
		document.getElementById('checked_orders').innerHTML = checked_orders;
	});

	massStatusChange = function(option)
	{
		var form = window.parent.document.adminForm;
		var mass_change_status = document.getElementById('mass_change_status');
		form.appendChild(mass_change_status);
		form.task.value = option;

		try
		{
			form.onsubmit();
		}
		catch (e)
		{}

		form.submit();
	}
</script>
<div name="mass_change_status" id="mass_change_status">
	<div align="center" style="margin: 30px 30px;">
		<h3><?php echo JText::_('COM_REDSHOP_MASS_CHANGE_STATUS_ORDER'); ?></h3>
	</div>
	<p><?php echo JText::_('COM_REDSHOP_ORDER') . ': '; ?><span id="checked_orders" style="font-weight:bold"></span></p>
	<table class="adminlist table" width="100%">
		<thead>
			<th width="50%"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?></th>
			<th width="50%"><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS'); ?></th>

		</thead>
		<tbody>

		<tr>
			<td>
				<?php echo $massChangeStatus['mass_change_order_status']; ?>
			</td>
			<td>
				<?php echo $massChangeStatus['mass_change_payment_status']; ?>
			</td>

		</tbody>
		<tfoot>
			<td colspan="2">
				<label>
				<input type="checkbox" value="1" name="mass_mail_sending" /> <?php echo JText::_('COM_REDSHOP_SEND_ORDER_MAIL'); ?>
			</label>
			</td>
		</tfoot>
	</table>
	<div style="float:right">
		<button type="button" class="btn btn-danger" onclick="window.parent.SqueezeBox.close();">
			<?php echo JText::_('JTOOLBAR_CANCEL') ?>
		</button>
		<button type="button" class="btn btn-primary" onclick="massStatusChange('<?php echo $type; ?>');">
			<?php echo JText::_('COM_REDSHOP_APPLY'); ?>
		</button>
	</div>
</div>


