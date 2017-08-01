<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model          = $this->getmodel('stockroom_listing');
$showbuttons    = JFactory::getApplication()->input->getInt('showbuttons', 0);
?>
<?php if ($showbuttons) : ?>
	<div class="container" align="right">
		<a href="javascript:window.print();" class="btn btn-large">
			<i class="icon-print"></i>
		</a>
	</div>
<?php endif; ?>

<script language="javascript" type="text/javascript">
	function clearForm()
	{
		var form = document.adminForm;
		form.keyword.value = '';
		form.search_field.value = 'product_name';
		form.category_id.value = '0';
		form.stockroom_type.value = 'product';
		form.submit();
	}

	Joomla.submitbutton = function (pressbutton)
	{
		if (pressbutton == "print_data")
		{
			window.open(
				"<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=stockroom_listing&id=0&showbuttons=1', false);?>", "<?php echo JText::_('COM_REDSHOP_STOCKROOM_LISTING' );?>",
				"scrollbars=1",
				"location=1"
			);
			return false;
		}

		var form = document.adminForm;

		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		form.submit();
	}

	function getTaskChange()
	{
		document.adminForm.task.value = "";
	}
</script>
<form action="index.php?option=com_redshop&view=stockroom_listing" method="post" name="adminForm" id="adminForm">
	<div class="filterTool">
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<input
					type="text"
					name="keyword"
					id="keyword"
					placeholder="<?php echo JText::_('COM_REDSHOP_SEARCH'); ?>"
					value="<?php echo $this->state->get('keyword'); ?>"
				>
				<?php
				$filterOptions[] = JHtml::_('select.option', 'product_name', JText::_('COM_REDSHOP_PRODUCT_NAME'));
				$filterOptions[] = JHtml::_('select.option', 'product_number', JText::_('COM_REDSHOP_PRODUCT_NUMBER'));

				echo JHtml::_(
					'select.genericlist',
					$filterOptions,
					'search_field',
					'class="inputbox" onchange="document.adminForm.submit();" ',
					'value',
					'text',
					$this->state->get('search_field')
				);
				?>
				<input
					type="submit"
					class="btn"
					value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>"
				>
				<input
					type="reset"
					class="btn"
					value="<?php echo JText::_("JCLEAR") ?>"
					onclick="clearForm();"
				>
			</div>
		</div>
		<div class="filterItem">
			<?php echo $this->lists['category'];?>
		</div>
		<div class="filterItem">
			<?php echo $this->lists['stockroom_type']; ?>
		</div>
	</div>
	<div id="editcell1">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="10%">
				<?php
					echo JHtml::_(
						'grid.sort',
						'COM_REDSHOP_PRODUCT_SKU',
						'p.product_number',
						$this->lists ['order_Dir'],
						$this->lists ['order']
					);
				?>
				</th>
				<th width="20%">
				<?php
					echo JHtml::_(
						'grid.sort',
						'COM_REDSHOP_PRODUCT_NAME',
						'p.product_name',
						$this->lists ['order_Dir'],
						$this->lists ['order']
					);
				?>
				</th>
			<?php if ($this->stockroom_type != 'product') : ?>
				<th width="15%">
					<?php echo JText::_('COM_REDSHOP_PROPERTY_NUMBER'); ?>
				</th>
				<th width="20%">
					<?php if ($this->stockroom_type == 'property') : ?>
						<?php echo JText::_('COM_REDSHOP_PROPERTY'); ?>
					<?php elseif ($this->stockroom_type == 'subproperty') : ?>
						<?php echo JText::_('COM_REDSHOP_SUBPROPERTY');?>
					<?php endif; ?>
				</th>
			<?php endif; ?>
			<?php for($j = 0;$j < count($this->stockroom);$j++) : ?>
				<th width="5%">
					<?php echo $this->stockroom[$j]->stockroom_name; ?>
					<a href="javascript:Joomla.submitbutton('saveStock')" class="saveorder pull-right"></a>
				</th>
				<th width="5%">
					<?php  echo JText::_('COM_REDSHOP_PREORDER_STOCKROOM_QTY'); ?><br />
					<?php echo $this->stockroom[$j]->stockroom_name;?>
					<a href="javascript:Joomla.submitbutton('saveStock')" class="saveorder pull-right"></a>
				</th>
			<?php endfor; ?>
			</tr>
			</thead>
		<?php
			$k = 0;
			$qungrandtotal = array(0);
			$preorder_stockalltotal = array(0);
		?>
		<?php for ($i = 0, $n = count($this->resultlisting); $i < $n; $i++)	: ?>
			<?php
				$quntotal[$i] = array(0);
				$preorder_stocktotal[$i] = array(0);
				$row = $this->resultlisting [$i];
				$link1 = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id);
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<a href="<?php echo $link1; ?>">
							<?php echo $row->product_number; ?>
						</a>
					</td>
					<td>
						<a href="<?php echo $link1; ?>">
							<?php echo $row->product_name; ?>
						</a>
					</td>
				<?php if ($this->stockroom_type != 'product') : ?>
					<td>
						<?php if ($this->stockroom_type == 'property') : ?>
							<?php echo $row->property_number; ?>
						<?php elseif ($this->stockroom_type == 'subproperty') : ?>
							<?php echo $row->subattribute_color_number; ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($this->stockroom_type == 'property') : ?>
							<?php echo $row->property_name; ?>
						<?php elseif ($this->stockroom_type == 'subproperty') : ?>
							<?php echo $row->subattribute_color_name; ?>
						<?php endif; ?>
					</td>
				<?php endif; ?>

				<?php for ($j = 0, $countStockRoom = count($this->stockroom); $j < $countStockRoom; $j++) : ?>

				<?php
					$quantity         = 0;
					$preorder_stock   = 0;
					$ordered_preorder = 0;
					$section_id       = ($this->stockroom_type != 'product') ? $row->section_id : $row->product_id;

					if (isset($this->quantities[$section_id . '.' . $this->stockroom[$j]->stockroom_id]))
					{
						$secrow           = $this->quantities[$section_id . '.' . $this->stockroom[$j]->stockroom_id];
						$quantity         = $secrow->quantity;
						$preorder_stock   = $secrow->preorder_stock;
						$ordered_preorder = $secrow->ordered_preorder;
					}

					$quntotal[$i][$j]            = $quantity;
					$preorder_stocktotal[$i][$j] = $preorder_stock;
				?>
					<td align="center">
						<input
							type="hidden"
							name="sid[]"
							value="<?php echo $this->stockroom[$j]->stockroom_id; ?>"
						>
						<input
							type="hidden"
							name="pid[]"
							value="<?php echo $section_id; ?>"
						>
						<input
							type="text"
							value="<?php echo $quantity; ?>"
							name="quantity[]"
							class="input-small"
							size="4"
						>
					</td>
					<td align="center">
						<input
							type="text"
							value="<?php echo $preorder_stock; ?>"
							name="preorder_stock[]"
							class="input-small"
							size="4"
						>
						<input
							type="hidden"
							value="<?php echo $ordered_preorder; ?>"
							name="ordered_preorder[]"
							size="4"
						>
					<?php if ($ordered_preorder > 0) : ?>
						( <?php echo $ordered_preorder ?> )
						<input
							type="button"
							name="preorder_reset"
							value="Reset"
						    onclick="location.href='index.php?option=com_redshop&view=stockroom_listing&task=ResetPreorderStock&stockroom_type=<?php echo $this->stockroom_type ?>&product_id=<?php echo $section_id ?>&stockroom_id=<?php echo $this->stockroom[$j]->stockroom_id ?>';"
					   >
					<?php endif; ?>
					</td>
				<?php endfor; ?>
				</tr>
				<?php    $k = 1 - $k; ?>
			<?php  endfor; ?>
			<?php

				for ($j = 0, $nj = count($this->stockroom); $j < $nj; $j++)
				{
					$qungrandtotal[$j] = 0;
					$preorder_stockalltotal[$j] = 0;

					for ($i = 0, $ni = count($this->resultlisting); $i < $ni; $i++)
					{
						$qungrandtotal[$j] = $qungrandtotal[$j] + $quntotal[$i][$j];
						$preorder_stockalltotal[$j] = $preorder_stockalltotal[$j] + $preorder_stocktotal[$i][$j];
					}
				}

				$colspan = ($this->stockroom_type == 'product') ? 3 : 5;
			?>

			<tr>
				<td colspan="<?php echo $colspan; ?>">
					<?php echo JText::_('COM_REDSHOP_TOTAL'); ?>
				</td>
			<?php for ($j = 0; $j < count($this->stockroom); $j++) : ?>
				<td align="center">
					<?php echo $qungrandtotal[$j]; ?>
				</td>
				<td align="center">
					<?php echo $preorder_stockalltotal[$j]; ?>
				</td>
			<?php endfor; ?>
			</tr>

			<?php if (!$showbuttons) : ?>
				<tfoot>
				<td colspan="<?php echo $colspan + (2 * count($this->stockroom)); ?>">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
				</tfoot>
			<?php endif; ?>
		</table>
	</div>
	<input type="hidden" name="view" value="stockroom_listing"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
</form>
