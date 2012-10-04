<?php

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=accountgroup'); ?>" class="admin" id="adminForm" method="post" name="adminForm">
    <table class="adminlist">
        <thead>
        <tr>
            <th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
            <th width="5%"><input type="checkbox" name="toggle" value=""
                                  onclick="checkAll(<?php echo count($this->detail);?>)"? />
            </th>
            <th width="20%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ACCOUNTGROUP_NAME', 'accountgroup_name', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_VAT_ACCOUNT_NUMBER', 'economic_vat_account', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_NON_VAT_ACCOUNT_NUMBER', 'economic_nonvat_account', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_DISCOUNT_PRODUCT_NUMBER_LBL', 'economic_discount_product_number', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_DISCOUNT_VAT_ACCOUNT', 'economic_discount_vat_account', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_DISCOUNT_NONVAT_ACCOUNT', 'economic_discount_nonvat_account', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_SHIPPING_VAT_ACCOUNT', 'economic_shipping_vat_account', $listDirn, $listOrder);?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ECONOMIC_SHIPPING_NONVAT_ACCOUNT', 'economic_shipping_nonvat_account', $listDirn, $listOrder);?></th>
            <th width="5%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $listDirn, $listOrder);?></th>
            <th width="5%"><?php echo JHTML::_('grid.sort', 'ID', 'accountgroup_id', $listDirn, $listOrder); ?>    </th>
        </tr>
        </thead>
        <?php
        $k = 0;
        for ($i = 0, $n = count($this->detail); $i < $n; $i++)
        {
            $row       = $this->detail[$i];
            $row->id   = $row->accountgroup_id;
            $link      = JRoute::_('index.php?option=com_redshop&view=accountgroup_detail&layout=edit&accountgroup_id=' . $row->id);
            $published = JHtml::_('jgrid.published', $row->published, $i, '', 1);    ?>
            <tr class="<?php echo "row$k"; ?>">
                <td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
                <td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
                <td><a href="<?php echo $link; ?>"
                       title="<?php echo JText::_('COM_REDSHOP_EDIT_ACCOUNTGROUP'); ?>"><?php echo $row->accountgroup_name; ?></a>
                </td>
                <td align="center"><?php echo $row->economic_vat_account;?></td>
                <td align="center"><?php echo $row->economic_nonvat_account;?></td>
                <td align="center"><?php echo $row->economic_discount_product_number;?></td>
                <td align="center"><?php echo $row->economic_discount_vat_account;?></td>
                <td align="center"><?php echo $row->economic_discount_nonvat_account;?></td>
                <td align="center"><?php echo $row->economic_shipping_vat_account;?></td>
                <td align="center"><?php echo $row->economic_shipping_nonvat_account;?></td>
                <td align="center"><?php echo $published;?></td>
                <td align="center"><?php echo $row->id;?></td>
            </tr>
            <?php
            $k = 1 - $k;
        }?>
        <tfoot>
        <td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
        </tfoot>
    </table>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>
