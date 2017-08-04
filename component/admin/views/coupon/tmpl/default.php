<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$producthelper = productHelper::getInstance();

$order_functions = order_functions::getInstance();


$model = $this->getModel('coupon');
$url = JURI::base();
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if (pressbutton == 'add' || pressbutton == 'edit' || pressbutton == 'remove') {
			form.view.value = "coupon_detail";
		}

		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

	function clearreset() {
		var form = document.adminForm;
		form.filter_search.value = "";
		form.submit();
	}
</script>
<form action="index.php?option=com_redshop&view=coupon" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->filter; ?>"
						   placeholder="<?php echo JText::_('COM_REDSHOP_COUPON_FILTER'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="reset" class="btn reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
						   onclick="return clearreset();">
				</div>
			</div>
		</div>

		<table class="adminlist table table-striped test-redshop-table">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_CODE', 'coupon_code', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_PERCENTAGE_OR_TOTAL'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_USERNAME', 'userid', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_TYPE', 'coupon_type', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_VALUE', 'coupon_value', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_LBL_COUPON_LEFT', 'coupon_left', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'coupon_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->coupons); $i < $n; $i++)
			{
				$row = $this->coupons[$i];
				$row->id = $row->coupon_id;
				$link = JRoute::_('index.php?option=com_redshop&view=coupon_detail&task=edit&cid[]=' . $row->coupon_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				if ($row->userid)
					$username = $order_functions->getUserFullname($row->userid);
				else
					$username = "";

				?>
				<tr class="<?php echo "row$k"; ?> test-redshop-table-row">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center" class="test-redshop-coupon-checkall">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td align="center" class="test-redshop-coupon-code">
						<a href="<?php echo $link; ?>"><?php echo  $row->coupon_code; ?></a>
					</td>
					<td class="order" class="test-redshop-coupon-value-in">
						<?php
						if ($row->percent_or_total == 0)
							echo JText::_('COM_REDSHOP_TOTAL');
						else
							echo JText::_('COM_REDSHOP_PERCENTAGE');
						?>
					</td>
					<td>
						<?php if ($username != "") echo $username; ?>
					</td>
					<td class="test-redshop-coupon-type">
						<?php
						if ($row->coupon_type == 0)
							echo JText::_('COM_REDSHOP_GLOBAL');
						else
							echo JText::_('COM_REDSHOP_USER_SPECIFIC');
						?>
					</td>
					<td align="center" class="test-redshop-coupon-value">
						<?php if ($row->percent_or_total != 0)
						{
							echo $row->coupon_value . " %";
						}
						else
						{
							echo $producthelper->getProductFormattedPrice($row->coupon_value);
							//number_format($row->coupon_value,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR);
						}?>
					</td>
					<td align="center" class="test-redshop-coupon-amount-left">
						<?php echo $row->coupon_left; ?>
					</td>
					<td align="center" class="test-redshop-coupon-state">
						<?php echo $published;?>
					</td>
					<td align="center" class="test-redshop-coupon-id">
						<?php echo $row->coupon_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
			<td colspan="10">
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

	<input type="hidden" name="view" value="coupon"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
