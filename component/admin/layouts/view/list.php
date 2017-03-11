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
 * @var  object  $data         View object
 * @var  array   $displayData  Layout data.
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

<form action="index.php?option=com_redshop&view=<?php echo $viewName ?>" class="adminForm" id="adminForm" method="post" name="adminForm">
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
                <th width="1">#</th>
                <th width="1">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
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
                <th width="1">
	                <?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 'id', $listDirn, $listOrder) ?>
                </th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($data->items as $i => $row): ?>
				<?php $canCheckIn = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0; ?>
                <tr>
                    <td><?php echo $data->pagination->getRowOffset($i) ?></td>
                    <td align="center">
						<?php echo JHtml::_('grid.id', $i, $row->id) ?>
                    </td>
                    <td nowrap="nowrap">
						<?php if ($row->checked_out): ?>
							<?php echo JHtml::_('redshopgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, $viewName . '.', $canCheckIn) ?>
						<?php else: ?>
                            <a href="index.php?option=com_redshop&task=<?php echo $singleName ?>.edit&id=<?php echo $row->id ?>"
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
		                <?php echo $row->id ?>
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
