<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<form
        action="<?php echo 'index.php?option=com_redshop&view=states'; ?>"
        class="admin"
        method="post"
        name="adminForm"
        id="adminForm"
>
    <div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view'    => $this,
				'options' => array(
					'searchField'         => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector'  => '#list_users_limit',
					'activeOrder'         => $listOrder,
					'activeDirection'     => $listDirn,
				)
			)
		);
		?>
    </div>

	<?php if (empty($this->items)) : ?>
        <div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
	<?php else : ?>
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
                <th width="10">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
                </th>
                <th width="auto"><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_NAME'), 'state_name', $listDirn, $listOrder); ?></th>
                <th nowrap="nowrap" width="20%">
					<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_NAME'), 'country_name', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
					<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_3_CODE'), 'state_3_code', $listDirn, $listOrder) ?>
                </th>
                <th width="10%">
					<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_2_CODE'), 'state_2_code', $listDirn, $listOrder) ?>
                </th>
                <th align="right" width="1"><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 'id', $listDirn, $listOrder); ?></th>
            </tr>
            </thead>
			<?php

			$k = 0;
			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{
				$row  = $this->items[$i];
				$link = JRoute::_('index.php?option=com_redshop&task=state.edit&id=' . $row->id);

				?>
                <tr class="<?php echo "row$k"; ?>">
                    <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
                    <td>
                        <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_state') ?>"><?php echo $row->state_name ?></a>
                    </td>
                    <td><?php echo $row->country_name ?></td>
                    <td><?php echo $row->state_3_code ?></td>
                    <td><?php echo $row->state_2_code ?></td>
                    <td><?php echo $row->id; ?></td>
                </tr>
				<?php
				$k = 1 - $k;
			}
			?>
            <tfoot>
            <td colspan="14">
				<?php echo $this->pagination->getListFooter(); ?>
            </td>
            </tfoot>
        </table>
	<?php endif; ?>

    <input type="hidden" name="view" value="states">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
