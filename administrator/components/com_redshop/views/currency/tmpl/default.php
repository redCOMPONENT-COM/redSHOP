<?php
defined('_JEXEC') or die('Restricted access');

$option = JRequest::getVar('option', '', 'request', 'string');
$filter = JRequest::getVar('filter');
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" class="admin" id="adminForm" method="post" name="adminForm">
    <table class="adminlist">
        <thead>
        <tr>
            <th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
            <th width="10"><input type="checkbox" name="toggle" value=""
                                  onclick="checkAll(<?php echo count($this->fields);?>)"? />
            </th>
            <th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_CURRENCY_NAME'), 'currency_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
            <th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_CURRENCY_CODE_LBL'), 'currency_code', $this->lists['order_Dir'], $this->lists['order']);?></th>

            <th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 'currency_id', $this->lists['order_Dir'], $this->lists['order']); ?>    </th>
        </tr>
        </thead>
        <?php
        $k = 0;
        for ($i = 0, $n = count($this->fields); $i < $n; $i++)
        {
            $row     = $this->fields[$i];
            $row->id = $row->currency_id;
            $link    = JRoute::_('index.php?option=' . $option . '&view=currency_detail&task=edit&cid[]=' . $row->currency_id);

            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                <td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
                <td><a href="<?php echo $link; ?>"
                       title="<?php echo JText::_('COM_REDSHOP_EDIT_CURRENCY'); ?>"><?php echo $row->currency_name ?></a>
                </td>
                <td align="center" width="10%"><?php echo $row->currency_code; ?></td>
                <td align="center" width="10%"><?php echo $row->currency_id;?></td>

            </tr>
            <?php
            $k = 1 - $k;
        }
        ?>


        <tfoot>
        <td colspan="9">
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
        </tfoot>
    </table>
    <input type="hidden" name="view" value="currency"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order'];?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>


