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

	resetfilter = function()
	{
		document.getElementById('read_filter').value = 'select';
		document.getElementById('name_filter').value = '';
		document.adminForm.submit();
	}

</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="name_filter" id="name_filter" value="<?php echo $this->state->get('name_filter'); ?>"
						 onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_NAME'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="button" class="btn reset" onclick="resetfilter();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
				</div>
			</div>
			<div class="filterItem">
				<?php echo JText::_('COM_REDSHOP_ALERT_READ_FILTER'); ?>:
				<?php echo $this->lists['read_filter']; ?>
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
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ALERT_MESSAGE', 'message', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ALERT_DATE', 'sent_date', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ALERT_READ', 'read', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php

			$k = 0;

			for ($i = 0, $n = count($this->alerts); $i < $n; $i++)
			{
				$row = $this->alerts[$i];
				$row->id = $row->id;

				$read = JHtml::_('jgrid.published', $row->read, $i, '', 1);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td align="center"><?php echo $row->message; ?></td>
					<td align="center"><?php echo $row->sent_date; ?></td>
					<td align="center"><?php echo $read;?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
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

	<input type="hidden" name="view" value="alert"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
