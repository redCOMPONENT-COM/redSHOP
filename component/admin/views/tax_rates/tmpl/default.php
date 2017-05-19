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

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="index.php?option=com_redshop&view=tax_rates" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
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
					<th nowrap="nowrap" width="55" class="hidden-phone">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_RATE_EU_SHORT', 't.is_eu_country', $listDirn, $listOrder) ?>
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_RATE_NAME', 't.name', $listDirn, $listOrder) ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_RATE_AMOUNT', 't.tax_rate', $listDirn, $listOrder) ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_RATE_COUNTRY', 't.tax_country', $listDirn, $listOrder) ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_RATE_STATE', 't.tax_state', $listDirn, $listOrder) ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_RATE_GROUP', 't.tax_group_id', $listDirn, $listOrder) ?>
					</th>
					<th width="1" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 't.id', $listDirn, $listOrder) ?>
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
					<td>
						<?php if ($row->is_eu_country): ?>
						<span class="label badge-success"><i class="fa fa-check"></i></span>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($row->checked_out): ?>
							<?php
							$author = JFactory::getUser($row->checked_out);
							$canCheckin = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0;
							echo JHtml::_('jgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, 'tax_rates.', $canCheckin);
							?>
						<?php endif; ?>
						<?php
						$taxName = (empty($row->name)) ? JText::_('COM_REDSHOP_TAX_RATE_NAME_DEFAULT') : $row->name;
						?>
						<?php if ($row->checked_out && $user->id != $row->checked_out): ?>
							<?php echo JHtml::_('string.truncate', $taxName, 50, true, false) ?>
						<?php else: ?>
							<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=tax_rate.edit&id=' . $row->id) ?>">
								<?php echo JHtml::_('string.truncate', $taxName, 50, true, false) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php
						echo number_format($row->tax_rate * 100, 2, Redshop::getConfig()->get('PRICE_SEPERATOR'), Redshop::getConfig()->get('THOUSAND_SEPERATOR')) . " %"
						?>
					</td>
					<td>
						<?php echo $row->country_name ?>
					</td>
					<td>
						<?php echo $row->state_name ?>
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=tax_group.edit&id=' . $row->tax_group_id) ?>">
							<?php echo $row->tax_group_name ?>
						</a>
					</td>
					<td align="center">
						<?php echo $row->id ?>
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
	<input type="hidden" name="view" value="tax_rates">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
