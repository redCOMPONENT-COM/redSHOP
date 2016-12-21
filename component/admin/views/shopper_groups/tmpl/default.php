<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$productHelper = productHelper::getInstance();
$config        = Redconfiguration::getInstance();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<form action="index.php?option=com_redshop&view=shopper_groups" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="filterTool">
		<?php echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_shopper_groups_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
					'filtersHidden' => false,
					'filterButton' => false,
					'showFilter' => false,
					'showListNumber' => false
				)
			)
		) ?>
	</div>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="1">#</th>
					<th width="1" class="title">
						<?php echo JHtml::_('redshopgrid.checkall'); ?>
					</th>
					<th class="title" width="1">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'sg.published', $listDirn, $listOrder) ?>
					</th>
					<th class="title" width="auto">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SHOPPER_GROUP_NAME', 'sg.shopper_group_name', $listDirn, $listOrder) ?>
					</th>
					<th nowrap="nowrap">
						<?php echo JText::_('COM_REDSHOP_DISCOUNT') ?>
					</th>
					<th width="5%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder) ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php $i = 0; ?>
			<?php foreach ($this->items as $row): ?>
				<tr>
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i) ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->shopper_group_id); ?>
					</td>
					<td align="center">
						<?php echo JHtml::_('jgrid.published', $row->published, $i, '', 1) ?>
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=shopper_group.edit&shopper_group_id=' . $row->shopper_group_id) ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_SHOPPER_GROUP') ?>">
							<?php echo $row->shopper_group_name ?>
						</a>
					</td>
					<td align="center">
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=discount&spgrpdis_filter=' . $row->shopper_group_id, false) ?>">
							<?php echo JText::_('COM_REDSHOP_ADD_DISCOUNT') ?>
						</a>
					</td>
					<td align="center">
						<?php echo $row->shopper_group_id ?>
					</td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tfoot>
		</table>
	<?php endif; ?>

	<?php echo JHtml::_('form.token') ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
</form>
