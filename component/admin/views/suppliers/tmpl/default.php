<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$user = JFactory::getUser();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="index.php?option=com_redshop&view=suppliers" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'filtersHidden' => false,
					'filterButton' => false,
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_users_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
					'showFilter' => false,
					'showListNumber' => false
				)
			)
		);
		?>
	</div>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items alert-info">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="1">#</th>
					<th width="1">
						<?php echo JHtml::_('redshopgrid.checkall'); ?>
					</th>
					<th width="5%" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $listDirn, $listOrder); ?>
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SUPPLIER_NAME', 's.name', $listDirn, $listOrder); ?>
					</th>
					<th width="20%">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SUPPLIER_EMAIL', 'email', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $i => $row): ?>
				<tr>
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i) ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id) ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.published', $row, $i) ?>
					</td>
					<td>
						<?php if ($row->checked_out && $user->id != $row->checked_out): ?>
							<?php
							$author = JFactory::getUser($row->checked_out);
							$canCheckin = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0;
							echo JHtml::_('jgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, 'suppliers.', $canCheckin);
							?>
						<?php endif; ?>

						<?php if ($row->checked_out && $user->id != $row->checked_out): ?>
							<?php echo JHtml::_('string.truncate', $row->name, 50, true, false) ?>
						<?php else: ?>
							<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=supplier.edit&id=' . $row->id) ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_SUPPLIER') ?>">
								<?php echo JHtml::_('string.truncate', $row->name, 50, true, false) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $row->email; ?>
					</td>
					<td align="center">
						<?php echo $row->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
			<td colspan="9">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	<?php endif; ?>
	<input type="hidden" name="view" value="suppliers">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
