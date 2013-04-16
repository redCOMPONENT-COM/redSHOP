<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

$model = $this->getmodel('stockroom_listing');
$option = JRequest::getVar('option', '', 'request', 'string');
$showbuttons = JRequest::getVar('showbuttons', '0');
$print_link = JRoute::_('index.php?tmpl=component&option=' . $option . '&view=stockroom_listing&id=0&showbuttons=1');
$stockroom_type = $this->stockroom_type;

if ($showbuttons == 1)
{
	echo '<div align="right"><br><br><input type="button" class="button" value="Print" onClick="window.print()"><br><br></div>';
}        ?>
<script language="javascript" type="text/javascript">


	function clearForm() {
		var form = document.adminForm;
		form.keyword.value = '';
		form.submit();
	}
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {

		if (pressbutton == "print_data") {
			window.open("<?php echo $print_link;?>", "<?php echo JText::_('COM_REDSHOP_STOCKROOM_LISTING' );?>", "scrollbars=1", "location=1");
			return false;
		}
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
			form.view.value = "stockroom_listing";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

	function getTaskChange() {
		var form = document.adminForm;
		form.task.value = "";

	}


</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td>
				<select name="search_field">
					<option
						value="product_name" <?php if ($this->search_field == 'product_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NAME")?></option>
					<option
						value="product_number" <?php if ($this->search_field == 'product_number') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER")?></option>
				</select>
				<input type="text" name="keyword" id="keyword" value="<?php echo $this->keyword; ?>">
				<input type="submit" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				<input type="reset" value="<?php echo JText::_("COM_REDSHOP_RESET") ?>" onclick="clearForm();"></td>
		</tr>
		<tr>
			<td><?php echo JText::_("COM_REDSHOP_CATEGORY") . ": " . $this->lists['category'];?><?php echo $this->lists['stockroom_type']; ?></td>
		</tr>
	</table>

	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_SKU', 'p.product_number', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="20%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'p.product_name', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<?php
				if ($stockroom_type != 'product')
				{
					?>
					<th width="15%"><?php echo JText::_('COM_REDSHOP_PROPERTY_NUMBER');    ?></th>
					<th width="20%"><?php if ($stockroom_type == 'property')
						{
							echo JText::_('COM_REDSHOP_PROPERTY');
						}
						else if ($stockroom_type == 'subproperty')
						{
							echo JText::_('COM_REDSHOP_SUBPROPERTY');
						}?></th>
				<?php
				}
				for($j = 0;$j < count($this->stockroom);$j++)
				{    ?>
				<th width="5%"><?php echo $this->stockroom[$j]->stockroom_name;//JText::_('COM_REDSHOP_QUANTITY'); ?>
					&nbsp;&nbsp;
					<a href="javascript:Joomla.submitbutton('saveStock')" class="saveorder" title="Save Stock"></a></th>
				<th width="5%"><?php echo $this->stockroom[$j]->stockroom_name;?>
					&nbsp;<?php  echo JText::_('COM_REDSHOP_PREORDER_STOCKROOM_QTY'); ?>&nbsp;&nbsp;
					<a href="javascript:Joomla.submitbutton('saveStock')" class="saveorder" title="Save Stock"></a></th>
			<?php }    ?>
			<tr>
			</thead>
			<?php
			$k = 0;
			$qungrandtotal = array(0);
			$preorder_stockalltotal = array(0);

			for ($i = 0, $n = count($this->resultlisting); $i < $n; $i++)
			{
				$quntotal[$i] = array(0);
				$preorder_stocktotal[$i] = array(0);
				$row = & $this->resultlisting [$i];
				$link1 = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);    ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><a href="<?php echo $link1; ?>"><?php echo $row->product_number; ?></a></td>
					<td><a href="<?php echo $link1; ?>"><?php echo $row->product_name; ?></a></td>
					<?php    if ($stockroom_type != 'product')
					{
						?>
						<td><?php if ($stockroom_type == 'property')
							{
								echo $row->property_number;
							}
							else if ($stockroom_type == 'subproperty')
							{
								echo $row->subattribute_color_number;
							} ?></td>
						<td><?php if ($stockroom_type == 'property')
							{
								echo $row->property_name;
							}
							else if ($stockroom_type == 'subproperty')
							{
								echo $row->subattribute_color_name;
							} ?></td>
					<?php }    ?>
					<?php    for ($j = 0; $j < count($this->stockroom); $j++)
					{
						if ($stockroom_type != 'product')
						{
							$section_id = $row->section_id;
						}
						else
						{
							$section_id = $row->product_id;
						}
						$secrow = $model->getQuantity($stockroom_type, $this->stockroom[$j]->stockroom_id, $section_id);


						if (count($secrow) > 0)
						{
							$secrow = $secrow[0];
							$quantity = $secrow->quantity;
							$preorder_stock = $secrow->preorder_stock;
							$ordered_preorder = $secrow->ordered_preorder;
						}
						else
						{
							$quantity = "";
							$preorder_stock = "";
							$ordered_preorder = "";
						}
						$quntotal[$i][$j] = $quantity;
						$preorder_stocktotal[$i][$j] = $preorder_stock;
						?>
						<td align="center">
							<input type="hidden" name="sid[]" value="<?php echo $this->stockroom[$j]->stockroom_id; ?>">
							<input type="hidden" name="pid[]" value="<?php echo $section_id; ?>">
							<input type="text" value="<?php echo $quantity; ?>" name="quantity[]" size="4"></td>
						<td align="center">


							<input type="text" value="<?php echo $preorder_stock; ?>" name="preorder_stock[]" size="4">
							<input type="hidden" value="<?php echo $ordered_preorder; ?>" name="ordered_preorder[]"
							       size="4">
							<?php if ($ordered_preorder > 0)
							{ ?>
								( <?php echo $ordered_preorder ?> )

								<input type="button" name="preorder_reset" value="Reset"
								       onclick="location.href = 'index.php?option=com_redshop&view=stockroom_listing&task=ResetPreorderStock&stockroom_type=<?php echo $stockroom_type ?>&product_id=<?php echo $section_id ?>&stockroom_id=<?php echo $this->stockroom[$j]->stockroom_id ?>' ; ">
							<?php }?>
						</td>

					<?php }    ?>
				</tr>
				<?php    $k = 1 - $k;
			}
			for ($j = 0; $j < count($this->stockroom); $j++)
			{
				$qungrandtotal[$j] = 0;
				for ($i = 0; $i < count($this->resultlisting); $i++)
				{
					$qungrandtotal[$j] = $qungrandtotal[$j] + $quntotal[$i][$j];
				}
			}
			for ($j = 0; $j < count($this->stockroom); $j++)
			{
				$preorder_stockalltotal[$j] = 0;
				for ($i = 0; $i < count($this->resultlisting); $i++)
				{
					$preorder_stockalltotal[$j] = $preorder_stockalltotal[$j] + $preorder_stocktotal[$i][$j];
				}
			}
			$colspan = 5;
			if ($stockroom_type == 'product')
			{
				$colspan = 3;
			}    ?>
			<tr>
				<td colspan="<?php echo $colspan; ?>"><?php echo JText::_('COM_REDSHOP_TOTAL'); ?></td>
				<?php    for ($j = 0; $j < count($this->stockroom); $j++)
				{
					?>
					<td align="center"><?php echo $qungrandtotal[$j]; ?></td>
					<td align="center"><?php echo $preorder_stockalltotal[$j]; ?></td>
				<?php }    ?>
			</tr>
			<?php
			if ($showbuttons != 1)
			{
				?>
				<tfoot>
				<td colspan="<?php echo $colspan + (2 * count($this->stockroom)); ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
				</tfoot>
			<?php
			}    ?>
		</table>
	</div>
	<input type="hidden" name="view" value="stockroom_listing"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
</form>
