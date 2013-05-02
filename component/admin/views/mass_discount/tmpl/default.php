<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper();
$option = JRequest::getVar('option', '', 'request', 'string');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'remove')) {
			form.view.value = "mass_discount_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->discounts); ?>);"/>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_DISCOUNT_NAME', 'discount_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_DISCOUNT_AMOUNT', 'discount_amount', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_DISCOUNT_TYPE', 'discount_type', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'mass_discount_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php

			$k = 0;
			for ($i = 0, $n = count($this->discounts); $i < $n; $i++)
			{
				$row = & $this->discounts[$i];
				$row->id = $row->mass_discount_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=mass_discount_detail&task=edit&cid[]=' . $row->mass_discount_id);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_DISCOUNT'); ?>">
							<?php
							echo $row->discount_name;
							?></a>
					</td>
					<td>
						<?php
						if ($row->discount_type == 0) echo $producthelper->getProductFormattedPrice($row->discount_amount);
						else echo $row->discount_amount . '%';
						?>
					</td>
					<td>
						<?php if ($row->discount_type == 0) echo JText::_('COM_REDSHOP_TOTAL');
						else echo JText::_('COM_REDSHOP_PERCENTAGE');
						?>
					</td>

					<td align="center"><?php echo $row->mass_discount_id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="mass_discount"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
