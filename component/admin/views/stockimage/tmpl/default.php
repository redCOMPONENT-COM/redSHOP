<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


$filter = JFactory::getApplication()->input->get('filter');

$model = $this->getModel('stockimage');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'remove') || (pressbutton == 'publish') || (pressbutton == 'unpublish')) {
			form.view.value = "stockimage_detail";
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
						   onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_STOCKIMAGE_FILTER'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>" />
					<input type="reset" class="btn reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
						   onclick="document.getElementById('filter').value='';this.form.submit();" />
				</div>
			</div>
		</div>

		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_STOCK_AMOUNT_IMAGE_TOOLTIP_LBL', 'stock_amount_image_tooltip', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th><?php echo JText::_('COM_REDSHOP_STOCKIMAGE') ?></th>
				<th><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_STOCK_AMOUNT_QUANTITY_LBL', 'stock_quantity', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_STOCK_AMOUNT_OPTION_LBL', 'stock_option', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_STOCKROOM_NAME', 'stockroom_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th><?php echo JHTML::_('grid.sort', 'ID', 'stock_amount_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->data); $i < $n; $i++)
			{
				$row = $this->data[$i];
				$row->id = $row->stock_amount_id;

				$thumbUrl = RedShopHelperImages::getImagePath(
								$row->stock_amount_image,
								'',
								'thumb',
								'stockroom',
								Redshop::getConfig()->get('DEFAULT_STOCKAMOUNT_THUMB_WIDTH'),
								Redshop::getConfig()->get('DEFAULT_STOCKAMOUNT_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

				$link = JRoute::_('index.php?option=com_redshop&view=stockimage_detail&task=edit&cid[]=' . $row->id);    ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pagination->getRowOffset($i);?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_STOCKIMAGE'); ?>"><?php echo $row->stock_amount_image_tooltip;?></a>
					</td>
					<td><img src="<?php echo $thumbUrl ?>" /></td>
					<td><?php echo $row->stock_quantity;?></td>
					<td><?php echo $model->getStockAmountOption($row->stock_option);?></td>
					<td><?php echo $row->stockroom_name;?></td>
					<td><?php echo $row->stock_amount_id;?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="7">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter();?></td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="stockimage"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
</form>
