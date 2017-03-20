<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$producthelper = productHelper::getInstance();

$bool = true;
$shippname = JText::_('COM_REDSHOP_SHIPPING_RATE_NAME');
if ($this->shipper_location)
{
	// FOR SELF PICKUP
	$bool = false;
	$shippname = JText::_('COM_REDSHOP_SHIPPING_LOCATION');
}
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
			form.view.value = "shipping_rate_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', $shippname, 'shipping_rate_name ', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<?php if ($bool)
				{ ?>
					<th class="title">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SHIPPING_RATE_VALUE', 'shipping_rate_value ', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
				<?php }    ?>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'shipping_rate_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->shipping_rates); $i < $n; $i++)
			{
				$row = $this->shipping_rates[$i];
//	$row->id = $row->shipping_rate_id;
				$link = JRoute::_('index.php?option=com_redshop&view=shipping_rate_detail&task=edit&cid[]=' . $row->shipping_rate_id . '&id=' . $this->shipping->id);    ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->shipping_rate_id); ?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_SHIPPING'); ?>"><?php echo $row->shipping_rate_name; ?></a>
					</td>
					<?php if ($bool)
					{ ?>
						<td align="center"><?php echo $producthelper->getProductFormattedPrice($row->shipping_rate_value); ?></td>
					<?php }    ?>
					<td align="center"><?php echo $row->shipping_rate_id; ?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="5">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php  echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="shipping_rate"/>
	<input type="hidden" name="id" value="<?php echo $this->shipping->id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
