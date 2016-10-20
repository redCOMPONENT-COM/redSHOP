<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHtml::_('behavior.modal');

$productHelper = productHelper::getInstance();
$orderFunctions = order_functions::getInstance();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="index.php?option=com_redshop&view=coupons" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<?php
				echo JLayoutHelper::render(
					'joomla.searchtools.default',
					array(
						'view' => $this,
						'options' => array(
							'searchField' => 'search',
							'filtersHidden' => false,
							'searchFieldSelector' => '#filter_search',
							'limitFieldSelector' => '#list_users_limit',
							'activeOrder' => $listOrder,
							'activeDirection' => $listDirn,
						)
					)
				);
			?>
		</div>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
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
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_CODE', 'coupon_code', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_PERCENTAGE_OR_TOTAL'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_USERNAME', 'userid', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_TYPE', 'coupon_type', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_COUPON_VALUE', 'coupon_value', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_LBL_COUPON_LEFT', 'coupon_left', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'coupon_id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{
				$row = $this->items[$i];
				$link = JRoute::_('index.php?option=com_redshop&task=coupon.edit&id=' . $row->id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				if ($row->userid)
				{
					$username = $orderFunctions->getUserFullname($row->userid);
				}
				else
				{
					$username = "";
				}

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
							echo $productHelper->getProductFormattedPrice($row->coupon_value);
						}?>
					</td>
					<td align="center" class="test-redshop-coupon-amount-left">
						<?php echo $row->coupon_left; ?>
					</td>
					<td align="center" class="test-redshop-coupon-state">
						<?php echo $published;?>
					</td>
					<td align="center" class="test-redshop-coupon-id">
						<?php echo $row->id; ?>
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
		<?php endif; ?>
	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="coupons"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
