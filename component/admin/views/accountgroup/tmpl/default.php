<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish') || (pressbutton == 'remove')) {
			form.view.value = "accountgroup_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}
		form.submit();
	}
</script>

<form action="index.php?option=com_redshop" class="admin" id="adminForm" method="post" name="adminForm">
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th width="5%">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
			</th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ACCOUNTGROUP_NAME', 'accountgroup_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_VAT_ACCOUNT_NUMBER', 'economic_vat_account', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_NON_VAT_ACCOUNT_NUMBER', 'economic_nonvat_account', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_DISCOUNT_PRODUCT_NUMBER_LBL', 'economic_discount_product_number', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_DISCOUNT_VAT_ACCOUNT', 'economic_discount_vat_account', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_DISCOUNT_NONVAT_ACCOUNT', 'economic_discount_nonvat_account', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_SHIPPING_VAT_ACCOUNT', 'economic_shipping_vat_account', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_SHIPPING_NONVAT_ACCOUNT', 'economic_shipping_nonvat_account', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="5%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th width="5%"><?php echo JHTML::_('grid.sort', 'ID', 'accountgroup_id', $this->lists['order_Dir'], $this->lists['order']); ?>    </th>
		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i = 0, $n = count($this->detail); $i < $n; $i++)
		{
			$row = $this->detail[$i];
			$row->id = $row->accountgroup_id;
			$link = JRoute::_('index.php?option=com_redshop&view=accountgroup_detail&task=edit&cid[]=' . $row->id);
			$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);    ?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
				<td><a href="<?php echo $link; ?>"
				       title="<?php echo JText::_('COM_REDSHOP_EDIT_ACCOUNTGROUP'); ?>"><?php echo $row->accountgroup_name; ?></a>
				</td>
				<td align="center"><?php echo $row->economic_vat_account;?></td>
				<td align="center"><?php echo $row->economic_nonvat_account;?></td>
				<td align="center"><?php echo $row->economic_discount_product_number;?></td>
				<td align="center"><?php echo $row->economic_discount_vat_account;?></td>
				<td align="center"><?php echo $row->economic_discount_nonvat_account;?></td>
				<td align="center"><?php echo $row->economic_shipping_vat_account;?></td>
				<td align="center"><?php echo $row->economic_shipping_nonvat_account;?></td>
				<td align="center"><?php echo $published;?></td>
				<td align="center"><?php echo $row->id;?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}?>
		<tfoot>
		<td colspan="12">
			<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
				<div class="redShopLimitBox">
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>
			<?php echo $this->pagination->getListFooter(); ?></td>
		</tfoot>
	</table>
	<input type="hidden" name="view" value="accountgroup"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
