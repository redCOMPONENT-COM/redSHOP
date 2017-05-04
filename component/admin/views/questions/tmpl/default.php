<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$productHelper = productHelper::getInstance();
$config        = Redconfiguration::getInstance();

$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn     = $this->escape($this->state->get('list.direction'));
$filterSearch = $this->state->get('filter.search', '');
$ordering     = ($this->ordering == 'q.ordering');

// Allow ordering on specific case.
$allowOrder = ($listOrder == 'q.ordering' && strtolower($listDirn) == 'asc');

if ($allowOrder)
{
	$saveOrderingUrl = 'index.php?option=com_redshop&task=questions.saveOrderAjax';
	JHtml::_('redshopsortable.sortable', 'adminForm', 'adminForm', 'asc', $saveOrderingUrl);
}
?>

<form action="index.php?option=com_redshop&view=questions" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="filterTool">
		<?php echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_users_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
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
					<th width="1" class="title">
						<?php echo JHtml::_('redshopgrid.checkall'); ?>
					</th>
					<?php if (empty($filterSearch)): ?>
						<th width="1" class="nowrap center hidden-phone">
							<a href="#" onclick="Joomla.tableOrdering('q.ordering','asc','');return false;"
							   data-order="m.ordering" data-direction="asc">
								<span class="fa fa-sort-alpha-asc"></span>
							</a>
						</th>
					<?php endif; ?>
					<th class="title" width="1">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'q.published', $listDirn, $listOrder) ?>
					</th>
					<th class="title" width="25%">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUESTION', 'q.question', $listDirn, $listOrder) ?>
					</th>
					<th class="title" width="auto">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUESTION_PRODUCT_NAME', 'p.product_name', $listDirn, $listOrder) ?>
					</th>
					<th class="title" width="5%">
						<?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
					<th class="title" width="10%">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_NAME', 'q.user_name', $listDirn, $listOrder) ?>
					</th>
					<th class="title" width="10%">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_EMAIL', 'q.user_email', $listDirn, $listOrder) ?>
					</th>
					<th width="1">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder) ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php $i = 0; ?>
			<?php foreach ($this->items as $row): ?>
				<tr>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<?php if (empty($filterSearch)): ?>
					<td class="order nowrap center hidden-phone">
						<span class="sortable-handler <?php echo ($allowOrder) ? '' : 'inactive' ?>">
							<span class="icon-move"></span>
						</span>
							<input type="text" style="display:none" name="order[]" value="<?php echo $row->ordering; ?>" />
					</td>
					<?php endif; ?>
					<td align="center">
						<?php echo JHtml::_('jgrid.published', $row->published, $i, '', 1) ?>
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=question.edit&id=' . $row->id) ?>"
						   title="<?php echo JText::_('COM_REDSHOP_VIEW_QUESTION') ?>">
							<?php if (strlen($row->question) > 50): ?>
								<?php echo substr($row->question, 0, 50) . "..." ?>
							<?php else: ?>
								<?php echo $row->question ?>
							<?php endif; ?>
						</a>
					</td>
					<td align="center">
						<?php echo $row->product_name ?>
					</td>
					<td align="center">
						<?php
						$answer = $productHelper->getQuestionAnswer($row->id, 0, 1);
						$answer = count($answer);
						?>
						<a class="badge label-info"
						   href="<?php echo JRoute::_('index.php?option=com_redshop&task=question.edit&id=' . $row->id . '#answerlists') ?>">
							<strong><?php echo $answer ?></strong>
						</a>
					</td>
					<td><?php echo $row->user_name ?></td>
					<td><?php echo $row->user_email ?></td>
					<td align="right"><?php echo $row->id ?></td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	<?php endif; ?>

	<?php echo JHtml::_('form.token') ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
</form>
