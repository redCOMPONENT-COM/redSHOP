<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$producthelper = productHelper::getInstance();
$config = Redconfiguration::getInstance();

?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if (pressbutton == 'add' || pressbutton == 'edit' || pressbutton == 'remove') {
			form.view.value = "voucher_detail";
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
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
 					<input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
 						placeholder="<?php echo JText::_('COM_REDSHOP_VOUCHER_FILTER'); ?>">
 					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="button" class="btn reset" onclick="document.getElementById('filter').value='';this.form.submit();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
 				</div>
			</div>
		</div>

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
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_VOUCHER_CODE', 'amount', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_VOUCHER_AMOUNT', 'amount', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_VOUCHER_TYPE', 'voucher_type', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_VOUCHER_STARTDATE', 'start_date', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_VOUCHER_ENDDATE', 'end_date', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_LBL_VOUCHER_LEFT', 'voucher_left', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'voucher_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php

			$k = 0;

			for ($i = 0, $n = count($this->vouchers); $i < $n; $i++)
			{
				$row = $this->vouchers[$i];
				$row->id = $row->voucher_id;
				$link = JRoute::_('index.php?option=com_redshop&view=voucher_detail&task=edit&cid[]=' . $row->voucher_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td align="center">
						<a href="<?php echo $link; ?>" title="
						<?php echo JText::_('COM_REDSHOP_EDIT_VOUCHER'); ?>
						"><?php echo $row->voucher_code; ?></a>
					</td>
					<td align="center">
						<?php echo $producthelper->getProductFormattedPrice($row->amount); ?>
					</td>
					<td align="center"><?php echo $row->voucher_type; ?></td>
					<td align="center"><?php echo $config->convertDateFormat($row->start_date); ?></td>
					<td align="center"><?php echo $config->convertDateFormat($row->end_date); ?></td>
					<td align="center"><?php echo $row->voucher_left; ?></td>
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->voucher_id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="10">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="voucher"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
