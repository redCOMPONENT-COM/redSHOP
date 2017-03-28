<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$user = JFactory::getUser();

$productHelper = productHelper::getInstance();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if (pressbutton == 'mass_discounts.delete') {
			var r = confirm('<?php echo JText::_("COM_REDSHOP_MASS_DISCOUNT_DELETE_MASS_DISCOUNTS")?>');
			if (r == true)
				form.submit();
			else return false;
		}

		form.submit();
	}
</script>
<form action="index.php?option=com_redshop&view=mass_discounts" class="admin" method="post" name="adminForm" id="adminForm">
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
				<th nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MASS_DISCOUNT_NAME', 'm.name', $listDirn, $listOrder) ?>
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MASS_DISCOUNT_START_DATE', 'm.start_date', $listDirn, $listOrder) ?>
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MASS_DISCOUNT_END_DATE', 'm.end_date', $listDirn, $listOrder) ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MASS_DISCOUNT_TYPE', 'm.type', $listDirn, $listOrder) ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MASS_DISCOUNT_AMOUNT', 'm.amount', $listDirn, $listOrder) ?>
				</th>
				<th align="right" width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'm.id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->items as $i => $row): ?>
				<?php $link = JRoute::_('index.php?option=com_redshop&task=mass_discount.edit&id=' . $row->id); ?>
			<tr>
				<td>
					<?php echo $this->pagination->getRowOffset($i) ?>
				</td>
				<td>
					<?php echo JHTML::_('grid.id', $i, $row->id) ?>
				</td>
				<td>
					<?php if ($row->checked_out && $user->id != $row->checked_out): ?>
						<?php
						$author = JFactory::getUser($row->checked_out);
						$canCheckin = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0;
						echo JHtml::_('jgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, 'mass_discounts.', $canCheckin);
						?>
					<?php endif; ?>

					<?php if ($row->checked_out && $user->id != $row->checked_out): ?>
						<?php echo JHtml::_('string.truncate', $row->name, 50, true, false) ?>
					<?php else: ?>
						<a href="<?php echo $link ?>">
							<?php echo JHtml::_('string.truncate', $row->name, 50, true, false) ?>
						</a>
					<?php endif; ?>
				</td>
				<td>
					<?php if (!empty($row->start_date)): ?>
						<?php echo JFactory::getDate($row->start_date)->format('d-m-Y') ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if (!empty($row->end_date)): ?>
						<?php echo JFactory::getDate($row->end_date)->format('d-m-Y') ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($row->type == 0): ?>
						<span class="badge label-success"><?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT_TYPE_OPTION_TOTAL') ?></span>
					<?php else: ?>
						<span class="badge label-info"><?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT_TYPE_OPTION_PERCENTAGE') ?></span>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($row->type == 0): ?>
						<?php echo $productHelper->getProductFormattedPrice($row->amount) ?>
					<?php else: ?>
						<?php echo $row->amount . '%'; ?>
					<?php endif; ?>
				</td>
				<td>
					<?php echo $row->id ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<td colspan="14">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tfoot>
	</table>
	<?php endif; ?>

	<input type="hidden" name="view" value="mass_discounts">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
