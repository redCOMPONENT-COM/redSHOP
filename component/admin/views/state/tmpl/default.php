<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$filter = JRequest::getVar('filter');

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "state_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="<?php echo 'index.php?option=com_redshop'; ?>" class="admin" method="post" name="adminForm" id="adminForm">
	<div class="filterItem">
		<div class="btn-wrapper input-append">
			<input type="text" name="country_main_filter" id="country_main_filter" placeholder="<?php echo JText::_('COM_REDSHOP_FILTER');  ?>"
				   value="<?php echo $this->country_main_filter; ?>">
			<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
		</div>
	</div>
	<div class="filterItem">
		<?php echo JText::_("COM_REDSHOP_COUNTRY_NAME"); ?> :
		<?php echo $this->lists['country_id']; ?>
	</div>
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th width="10">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_NAME'), 's.state_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_NAME'), 'c.country_name', $this->lists['order_Dir'], $this->lists['order']);?></th>

			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_3_CODE'), 's.state_3_code', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_2_CODE'), 's.state_2_code', $this->lists['order_Dir'], $this->lists['order']);?></th>

			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 's.state_id', $this->lists['order_Dir'], $this->lists['order']); ?>    </th>
		</tr>
		</thead>
		<?php

		$k = 0;
		for ($i = 0, $n = count($this->fields); $i < $n; $i++)
		{
			$row = $this->fields[$i];
			$row->id = $row->state_id;

			$link = JRoute::_('index.php?option=com_redshop&view=state_detail&task=edit&cid[]=' . $row->state_id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo @JHTML::_('grid.checkedout', $row, $i); ?></td>
				<td>
					<a href="<?php echo $link; ?>"
					   title="<?php echo JText::_('COM_REDSHOP_EDIT_state'); ?>"><?php echo $row->state_name ?></a></td>

				<td align="center" width="10%"><?php echo $row->country_name; ?></td>
				<td align="center" width="10%"><?php echo $row->state_3_code; ?></td>
				<td align="center" width="10%"><?php echo $row->state_2_code; ?></td>

				<td align="center" width="10%"><?php echo $row->state_id;?></td>

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
	<input type="hidden" name="view" value="state">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
