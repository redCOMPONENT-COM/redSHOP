<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$option = JRequest::getVar('option', '', 'request', 'string');
$producthelper = new producthelper();
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
			form.view.value = "attributeprices_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}
		form.submit();
	}
</script>
<fieldset>
	<div style="float: right">
		<button type="button" onclick="Joomla.submitbutton('add');">
			<?php echo JText::_('COM_REDSHOP_ADD'); ?>
		</button>
		<button type="button" onclick="Joomla.submitbutton('edit');">
			<?php echo JText::_('COM_REDSHOP_EDIT'); ?>
		</button>
		<button type="button" onclick="Joomla.submitbutton('remove');">
			<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
		</button>
		<button type="button" onclick="window.parent.location.reload();">
			<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
		</button>
	</div>
	<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_ATTRIBUTE_PRICE'); ?></div>
</fieldset>
<form action="<?php echo 'index.php?tmpl=component&option=' . $option; ?>" method="post" name="adminForm"
      id="adminForm">
	<div id="editcell">
		<table class="adminlist" width="100%">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%"><input type="checkbox" name="toggle"
				                      onclick="checkAll(<?php echo count($this->data); ?>);"/></th>
				<th class="title" align="left" width="15%"><?php echo JText::_('COM_REDSHOP_PROPERTY'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
				<th width="15%"><?php echo JText::_('COM_REDSHOP_PRICE'); ?></th>
				<th width="15%"><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?></th>
			</tr>
			</thead>
			<?php    $k = 0;
			for ($i = 0; $i < count($this->data); $i++)
			{
				$row = & $this->data[$i];
				$row->id = $row->price_id;
				$link = JRoute::_('index.php?tmpl=component&option=' . $option . '&view=attributeprices_detail&task=edit&section=' . $this->section . '&section_id=' . $row->section_id . '&cid[]=' . $row->price_id);?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_ATTRIBUTE_PRICE'); ?>"><?php echo $row->property_name;?></a>
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
	<input type="hidden" name="view" value="attributeprices"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="section_id" value="<?php echo $this->section_id; ?>"/>
	<input type="hidden" name="section" value="<?php echo $this->section; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>