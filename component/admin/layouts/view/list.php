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
$user       = JFactory::getUser();
?>
<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton == '<?php echo $viewName ?>.delete') {
            var result = confirm('<?php echo JText::_('COM_REDSHOP_DELETE_CONFIRM') ?>');

            if (result == true) {
                form.submit();
            }
            else {
                return false;
            }
        }

        form.submit();
    }
</script>

<form action="index.php?option=com_redshop&view=<?php echo $viewName ?>" class="admin" id="adminForm" method="post" name="adminForm">
    <div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view'    => $data,
				'options' => array(
					'searchField'         => 'search',
					'filtersHidden'       => false,
					'filterButton'        => false,
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector'  => '#list_' . $viewName . '_limit',
					'activeOrder'         => $listOrder,
					'activeDirection'     => $listDirn,
					'showFilter'          => false,
					'showListNumber'      => false
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
				<?php if ($data->showNumber): ?>
                    <th width="1">#</th>
				<?php endif; ?>
                <th width="1">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
                </th>
                <th width="1">
                    &nbsp;
                </th>
                <th width="1">
                    &nbsp;
                </th>
				<?php foreach ($columns as $column): ?>
                    <th width="<?php echo $column['width'] ?>">
						<?php if ($column['sortable']): ?>
							<?php echo JHTML::_('grid.sort', $column['text'], $column['dataCol'], $listDirn, $listOrder) ?>
						<?php else: ?>
							<?php echo $column['text'] ?>
						<?php endif; ?>
                    </th>
				<?php endforeach; ?>
				<?php if ($data->showId): ?>
                    <th width="1" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder); ?>
                    </th>
				<?php endif; ?>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($data->items as $i => $row): ?>
				<?php
				$isCheckedOut = $row->checked_out && $user->id != $row->checked_out;
				$canCheckIn   = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0;
				?>
                <tr>
					<?php if ($data->showNumber): ?>
                        <td><?php echo $data->pagination->getRowOffset($i) ?></td>
					<?php endif; ?>
                    <td align="center">
						<?php echo JHtml::_('grid.id', $i, $row->id) ?>
                    </td>
                    <td>
						<?php if ($isCheckedOut && $canCheckIn || !$isCheckedOut): ?>
                            <a href="index.php?option=com_redshop&task=<?php echo $singleName ?>.edit&id=<?php echo $row->id ?>"
                               class="btn btn-primary">
                                <i class="fa fa-edit"></i>&nbsp;<?php echo JText::_('JTOOLBAR_EDIT') ?>
                            </a>
						<?php else: ?>
                            <a href="javasript:void(0);" class="btn btn-default disabled">
                                <i class="fa fa-edit"></i>&nbsp;<?php echo JText::_('JTOOLBAR_EDIT') ?>
                            </a>
						<?php endif; ?>
                    </td>
                    <td class="center">
						<?php if ($isCheckedOut): ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, $viewName . '.', $canCheckIn) ?>
						<?php endif; ?>
                    </td>
					<?php foreach ($columns as $column): ?>
                        <td>
							<?php echo $data->onRenderColumn($column, $i, $row) ?>
                        </td>
					<?php endforeach; ?>
					<?php if ($data->showId): ?>
                        <td><?php echo $row->id ?></td>
					<?php endif; ?>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
	<?php endif; ?>
    <input type="hidden" name="view" value=" <?php echo $viewName ?>">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
