<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$producthelper = productHelper::getInstance();
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'copy')) {
			form.view.value = "shipping_box_detail";
		}

		if (pressbutton == 'shipping_box.remove') {
			var r = confirm('<?php echo JText::_("COM_REDSHOP_DELETE_SHIPPING_BOXES")?>');

			if (r == true)
			{
				form.submit();
			}
			else
			{
				return false;
			}
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
				<th width="5">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="20">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_BOX_NAME', 'shipping_box_name', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRIORITY', 'shipping_box_priority', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_LENGTH', 'shipping_box_length', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_WIDTH', 'shipping_box_width', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_HEIGHT', 'shipping_box_height', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'shipping_box_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->shipping_box); $i < $n; $i++)
			{
				$row = $this->shipping_box[$i];
				$row->id = $row->shipping_box_id;
				$link = JRoute::_('index.php?option=com_redshop&view=shipping_box_detail&task=edit&cid[]=' . $row->shipping_box_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="1%">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td width="2%">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td width="42%">
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_BOX'); ?>"><?php echo $row->shipping_box_name; ?></a>
					</td>
					<td width="10%" align="center">
						<?php echo $row->shipping_box_priority; ?>
					</td>
					<td width="10%" align="center">
						<?php echo $producthelper->redpriceDecimal($row->shipping_box_length); ?>
					</td>
					<td width="10%" align="center">
						<?php echo $producthelper->redpriceDecimal($row->shipping_box_width); ?>
					</td>
					<td width="10%" align="center">
						<?php echo $producthelper->redpriceDecimal($row->shipping_box_height); ?>
					</td>
					<td align="center" width="5%">
						<?php echo $published;?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->shipping_box_id; ?>
					</td>
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
				<?php  echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="shipping_box"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
