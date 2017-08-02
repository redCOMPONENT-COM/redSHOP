<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * ======================================
 *
 * @var  object $data        View object
 * @var  array  $displayData Layout data.
 */
extract($displayData);

$listOrder  = $data->escape($data->state->get('list.ordering'));
$listDirn   = $data->escape($data->state->get('list.direction'));
$viewName   = $data->getInstancesName();
$singleName = $data->getInstanceName();
$search     = $data->state->get('filter.search');
$user       = JFactory::getUser();

if ($data->hasOrdering)
{
	$saveOrderUrl   = 'index.php?option=com_redshop&task=' . $viewName . '.saveOrderAjax&tmpl=component';
	$orderingColumn = true === $data->isNested ? 'lft' : 'ordering';
	$allowOrder     = ($listOrder == $orderingColumn && strtolower($listDirn) == 'asc' && $data->canEdit && empty($search));

	if ($allowOrder)
	{
		JHtml::_('sortablelist.sortable', 'table-' . $viewName, 'adminForm', strtolower($listDirn), $saveOrderUrl, false, true);
	}
}
?>
<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton === "<?php echo $viewName ?>.delete") {
            var result = confirm("<?php echo JText::_('COM_REDSHOP_DELETE_CONFIRM') ?>");

            if (result == true) {
                form.submit();
            }
            else {
                return false;
            }
        }
        ;

        form.submit();
    };
</script>

<form action="index.php?option=com_redshop&view=<?php echo $viewName ?>" class="adminForm" id="adminForm" method="post"
      name="adminForm">
    <div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view'    => $data,
				'options' => array(
					'searchField'         => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector'  => '#list_' . $viewName . '_limit',
					'activeOrder'         => $listOrder,
					'activeDirection'     => $listDirn,
					'filterButton'        => (count($data->filterForm->getGroup('filter')) > 1),
					'filtersHidden'       => (count($data->filterForm->getGroup('filter')) > 1) ? false : true
				)
			)
		);
		?>
    </div>
	<?php if (empty($data->items)) : ?>
        <div class="alert alert-no-items alert-info">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
	<?php else : ?>
		<?php $columns = $data->getColumns(); ?>
        <table class="adminlist table table-striped" id="table-<?php echo $viewName ?>">
            <thead>
            <tr>
                <th width="1">#</th>
                <th width="1">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
                </th>
				<?php if ($data->hasOrdering): ?>
                    <th width="80" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class=\'fa fa-sort-alpha-asc\'></i>', $orderingColumn, $listDirn, $listOrder) ?>
                    </th>
				<?php endif; ?>
                <th width="1">
                    &nbsp;
                </th>
				<?php foreach ($columns as $column): ?>
                    <th width="<?php echo $column['width'] ?>">
						<?php if ($column['sortable']): ?>
							<?php echo JHtml::_('grid.sort', $column['text'], $column['dataCol'], $listDirn, $listOrder) ?>
						<?php else: ?>
							<?php echo $column['text'] ?>
						<?php endif; ?>
                    </th>
				<?php endforeach; ?>
                <th width="1">
					<?php echo JHtml::_('grid.sort', JText::_('COM_REDSHOP_ID'), 'id', $listDirn, $listOrder) ?>
                </th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($data->items as $i => $row): ?>
				<?php $rowId = $row->{$data->getPrimaryKey()}; ?>
				<?php $canCheckIn = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0; ?>
				<?php if ($data->hasOrdering && $data->isNested && !empty($data->nestedOrdering)) : ?>
					<?php $orderKey = array_search($row->{$data->getPrimaryKey()}, $data->nestedOrdering[$row->parent_id]); ?>
					<?php if ($row->level > 1) : ?>
						<?php
						$parentsStr      = '';
						$currentParentId = $row->parent_id;
						$parentsStr      = ' ' . $currentParentId;
						?>
						<?php for ($i2 = 0; $i2 < $row->level; $i2++) : ?>
							<?php foreach ($data->nestedOrdering as $k => $v) : ?>
								<?php
								$v = implode('-', $v);
								$v = '-' . $v . '-';
								?>
								<?php if (strpos($v, '-' . $currentParentId . '-') !== false) : ?>
									<?php
									$parentsStr      .= ' ' . $k;
									$currentParentId = $k;
									break;
									?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endfor; ?>
					<?php else : ?>
						<?php $parentsStr = 0; ?>
					<?php endif; ?>
                    <tr sortable-group-id="<?php echo $row->parent_id; ?>" item-id="<?php echo $rowId ?>"
                    parents="<?php echo $parentsStr; ?>" level="<?php echo $row->level; ?>">
				<?php else: ?>
                    <tr>
				<?php endif; ?>
                <td><?php echo $data->pagination->getRowOffset($i) ?></td>
                <td align="center">
					<?php echo JHtml::_('grid.id', $i, $row->{$data->getPrimaryKey()}) ?>
                </td>
				<?php if ($data->hasOrdering): ?>
                    <td class="order nowrap center">
                    <span class="sortable-handler hasTooltip <?php echo ($allowOrder) ? '' : 'inactive'; ?>">
                        <i class="icon-move"></i>
                    </span>
						<?php if ($allowOrder): ?>
                            <input type="text" style="display:none" name="order[]"
                                   value="<?php echo ($data->isNested) ? $orderKey + 1 : $row->{$orderingColumn} ?>"
                                   class="text-area-order"/>
						<?php endif; ?>
                    </td>
				<?php endif; ?>
                <td nowrap="nowrap">
					<?php if (!empty($row->checked_out)): ?>
						<?php echo JHtml::_('redshopgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, $viewName . '.', $canCheckIn) ?>
					<?php elseif ($data->canEdit == false): ?>
                        <a href="javascript:void(0)" class="btn btn-small btn-sm btn-primary disabled">
                            <i class="fa fa-edit"></i>
                        </a>
					<?php else: ?>
                        <a href="index.php?option=com_redshop&task=<?php echo $singleName ?>.edit&<?php echo $data->getPrimaryKey() ?>=<?php echo $rowId ?>"
                           class="btn btn-small btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </a>
					<?php endif; ?>
                </td>
				<?php foreach ($columns as $column): ?>
                    <td>
						<?php echo $data->onRenderColumn($column, $i, $row) ?>
                    </td>
				<?php endforeach; ?>
                <td>
					<?php echo $rowId ?>
                </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
            <tfoot>
            <td colspan="<?php echo count($columns) + 4 ?>"><?php echo $data->pagination->getListFooter() ?></td>
            </tfoot>
        </table>
	<?php endif; ?>
    <input type="hidden" name="view" value=" <?php echo $viewName ?>">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
