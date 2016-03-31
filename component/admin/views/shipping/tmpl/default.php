<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


$ordering = ($this->lists['order'] == 'ordering');
?>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">

		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_SHIPPING_NAME', 'name ', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>

				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PLUGIN', 'element ', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th class="title">
					<?php echo JText::_("COM_REDSHOP_VERSION") ?>

				</th>
				<th class="order" width="20%">
					<?php  echo JHtml::_('grid.sort', 'COM_REDSHOP_ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php  if ($ordering) echo JHtml::_('grid.order', $this->shippings); ?>
				</th>

				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'state', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ID', 'extension_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->shippings); $i < $n; $i++)
			{
				$row = $this->shippings[$i];
				$link = JRoute::_('index.php?option=com_redshop&view=shipping_detail&task=edit&cid[]=' . $row->extension_id);

				$published = JHtml::_('jgrid.published', $row->enabled, $i, '', 1);
				$cache = new JRegistry($row->manifest_cache);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHtml::_('grid.id', $i, $row->extension_id); ?>
					</td>
					<td width="50%">
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_SHIPPING'); ?>">
						   <?php echo JText::_($row->name); ?>
						</a>
					</td>

					<td align="center">
						<?php echo $row->element; ?>
					</td>
					<td align="center">
						<?php echo $cache->get('version'); ?>
					</td>
					<td class="order">
						<?php if ($ordering) :
							$orderDir = strtoupper($this->lists['order_Dir']);
							?>
							<div class="input-prepend">
								<?php if ($orderDir == 'ASC' || $orderDir == '') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderup'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown'); ?></span>
								<?php elseif ($orderDir == 'DESC') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderdown'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderup'); ?></span>
								<?php endif; ?>
								<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order" />
							</div>
						<?php else : ?>
							<?php echo $row->ordering; ?>
						<?php endif; ?>
					</td>
					<td align="center" width="5%">
						<?php echo $published;?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->extension_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php  echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="shipping"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
