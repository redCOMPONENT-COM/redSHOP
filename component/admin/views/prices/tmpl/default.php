<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper();
$option = JRequest::getVar('option', '', 'request', 'string');?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "prices_detail";
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
		<table class="adminlist" width="100%">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%"><input type="checkbox" name="toggle"
				                      onclick="checkAll(<?php echo count($this->media); ?>);"/></th>
				<th class="title" align="left" width="15%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
				<th width="15%"><?php echo JText::_('COM_REDSHOP_PRICE'); ?></th>
				<th width="15%"><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?></th>
			</tr>
			</thead>
			<?php    $k = 0;
			for ($i = 0; $i < count($this->media); $i++)
			{
				$row = & $this->media[$i];
				$row->id = $row->price_id;
				//$product_id = $row->product_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=prices_detail&task=edit&product_id=' . $row->product_id . '&cid[]=' . $row->price_id);?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT_PRICE'); ?>"><?php echo $row->product_name;?></a>
					</td>
					<td align="center"><?php echo $row->shopper_group_name;?></td>
					<td align="center"><?php echo $row->price_quantity_start;?></td>
					<td align="center"><?php echo $row->price_quantity_end;?></td>
					<td align="center"
					    width="5%"><?php echo $producthelper->getProductFormattedPrice($row->product_price); ?></td>
					<td align="center"
					    width="5%"><?php echo $producthelper->getProductFormattedPrice($row->discount_price); ?></td>
				</tr>
				<?php        $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="view" value="prices"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="product_id" value="<?php echo $this->product_id ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>