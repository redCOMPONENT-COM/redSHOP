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
<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton == 'tax_groups.delete') {
            if (false == confirm("<?php echo JText::_('COM_REDSHOP_TAX_GROUPS_DELETE_CONFIRM'); ?>")) {
                return false;
            }
        }

        form.submit();
    }
</script>
<form action="index.php?option=com_redshop&view=tax_groups" class="admin" id="adminForm" method="post" name="adminForm">
    <div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view'    => $this,
				'options' => array(
					'searchField'         => 'search',
					'filtersHidden'       => false,
					'filterButton'        => false,
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector'  => '#list_tax_groups_limit',
					'activeOrder'         => $listOrder,
					'activeDirection'     => $listDirn,
					'showFilter'          => false,
					'showListNumber'      => false
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
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAX_GROUP_NAME_LBL', 'name', $listDirn, $listOrder); ?>
                </th>
                <th nowrap="nowrap">
					<?php echo JText::_('COM_REDSHOP_TAX_RATE') ?>
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
						<?php if ($row->checked_out): ?>
							<?php
							$author     = JFactory::getUser($row->checked_out);
							$canCheckin = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $user->id || $row->checked_out == 0;
							echo JHtml::_('jgrid.checkedout', $i, $row->checked_out, $row->checked_out_time, 'tax_groups.', $canCheckin);
							?>
						<?php endif; ?>

						<?php if ($row->checked_out && $user->id != $row->checked_out): ?>
							<?php echo JHtml::_('string.truncate', $row->name, 50, true, false) ?>
						<?php else: ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_redshop&task=tax_group.edit&id=' . $row->id) ?>"
                               title="<?php echo JText::_('COM_REDSHOP_EDIT') ?>">
								<?php echo JHtml::_('string.truncate', $row->name, 50, true, false) ?>
                            </a>
						<?php endif; ?>
                    </td>
                    <td align="center">
						<?php $taxRates = RedshopEntityTax_Group::getInstance($row->id)->getTaxRates()->count(); ?>
						<?php if ($taxRates): ?>
                            <a href="index.php?option=com_redshop&view=tax_rates&filter[tax_group]=<?php echo $row->id ?>"
                               class="badge label-success">
								<?php echo $taxRates ?>
                            </a>
						<?php else: ?>
                            <span class="badge">0</span>
						<?php endif; ?>
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
    <input type="hidden" name="view" value="tax_groups">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
