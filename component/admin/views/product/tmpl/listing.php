<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$producthelper = productHelper::getInstance();
$model = $this->getModel('product');

?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;

		if (pressbutton) {

			form.task.value = pressbutton;

		}

		form.submit();
	}

</script>

<form action="<?php echo 'index.php?option=com_redshop&view=product&layout=listing'; ?>" method="post" name="adminForm" id="adminForm">
	<div class="filterTool">
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<input type="text" name="keyword" value="<?php echo $this->keyword; ?>">
				<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
			</div>
		</div>
		<div class="filterItem">
			<select name="search_field" onchange="javascript:document.adminForm.submit();">
				<option
					value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NAME")?></option>
				<option
					value="c.category_name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_CATEGORY")?></option>
				<option
					value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER")?></option>
			</select>
		</div>
	</div>
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
				<th class="title" width="500">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title" width="300">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title" width="20">
					<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
					<a href="javascript:Joomla.submitbutton('saveprice')" class="saveorder" title="Save Price"></a>
				</th>
				<th class="title" width="20">
					<?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?>
					<a href="javascript:Joomla.submitbutton('savediscountprice')" class="saveorder"
					   title="Save Discount Price"></a>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->products); $i < $n; $i++)
			{
				$row = $this->products[$i];
				$row->id = $row->product_id;
				$link = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->product_name; ?></a>
					</td>
					<td>
						<?php echo $row->product_number;?>
					</td>
					<td width="20%"><input type="hidden" name='pid[]' value="<?php echo $row->product_id ?>"> <input
							type="text" value="<?php echo $producthelper->redpriceDecimal($row->product_price, false); ?>"
							name="price[]" class="input-small" size=4>
						<a class='joom-box' rel="{handler: 'iframe', size: {x: 750, y: 400}}"
						   href="index.php?tmpl=component&option=com_redshop&view=product_price&pid=<?php echo $row->product_id ?>">+</a>
					</td>
					<td width="20%"><input type="text" class="input-small"
					                       value="<?php echo $producthelper->redpriceDecimal($row->discount_price); ?>"
					                       name="discount_price[]" size=4>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="6">
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

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
