<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$producthelper = productHelper::getInstance();
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		{
			var form = document.adminForm;
			if (pressbutton) {
				form.task.value = pressbutton;
			}

			form.submit();
		}
</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped" width="100%">
			<thead>
			<tr>
				<th width="30%"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_NAME'); ?></th>
				<th width="30%"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
				<th width="30%"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
				<th width="15%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?>
					<a class="saveorder" onclick="Joomla.submitbutton('saveprice')" href="#"></a>
				</th>
			</tr>
			</thead>
			<?php    $k = 0;
			for ($i = 0; $i < count($this->prices); $i++)
			{
				$row = $this->prices[$i];
				$row->id = $row->price_id;
				//$product_id = $row->product_id;
				?>
				<tr class="<?php echo "row$k"; ?>">


					<td align="center"><?php echo $row->shopper_group_name;?></td>
					<td align="center"><input type="text" name="price_quantity_start[]" id="price_quantity_start"
					                          value=" <?php echo $row->price_quantity_start; ?>" class="input-small"/></td>
					<td align="center"><input type="text" name="price_quantity_end[]" id="price_quantity_end"
					                          value="<?php echo $row->price_quantity_end; ?>" class="input-small"/></td>
					<td align="center" width="5%"><input type="hidden" name="price_id[]"
					                                     value="<?php echo $row->id; ?>"><input type="hidden"
					                                                                            name="shopper_group_id[]"
					                                                                            value="<?php echo $row->shopper_group_id; ?>"><input
							type="text" name="price[]" class="input-small"
							value="<?php echo $producthelper->redpriceDecimal($row->product_price); ?>"></td>
				</tr>
				<?php        $k = 1 - $k;
			}    ?>

		</table>
	</div>
	<input type="hidden" name="view" value="product_price"/>
	<input type="hidden" name="task" value="saveprice"/>
	<input type="hidden" name="pid" value="<?php echo $this->pid ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="option" value="com_redshop"/>
</form>
