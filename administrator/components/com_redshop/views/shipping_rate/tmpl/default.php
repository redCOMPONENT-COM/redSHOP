<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

$producthelper = new producthelper();
$option        = JRequest::getVar('option', '', 'request', 'string');
$bool          = true;
$shippname     = JText::_('COM_REDSHOP_SHIPPING_RATE_NAME');
if ($this->shipper_location)
{
    // FOR SELF PICKUP
    $bool      = false;
    $shippname = JText::_('COM_REDSHOP_SHIPPING_LOCATION');
}
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist">
            <thead>
            <tr>
                <th width="5%">
                    <?php echo JText::_('COM_REDSHOP_NUM'); ?>
                </th>
                <th width="5%">
                    <input type="checkbox" name="toggle" value=""
                           onclick="checkAll(<?php echo count($this->shipping_rates); ?>);"/>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', $shippname, 'shipping_rate_name ', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php if ($bool)
            { ?>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SHIPPING_RATE_VALUE', 'shipping_rate_value ', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php }    ?>
                <th width="5%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'shipping_rate_id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
            </tr>
            </thead>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->shipping_rates); $i < $n; $i++)
            {
                $row = &$this->shipping_rates[$i];
                //	$row->id = $row->shipping_rate_id;
                $link = JRoute::_('index.php?option=' . $option . '&view=shipping_rate_detail&task=edit&cid[]=' . $row->shipping_rate_id . '&id=' . $this->shipping->id);    ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td align="center"><?php echo JHTML::_('grid.id', $i, $row->shipping_rate_id); ?></td>
                    <td><a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_SHIPPING'); ?>"><?php echo $row->shipping_rate_name; ?></a>
                    </td>
                    <?php if ($bool)
                { ?>
                    <td align="center"><?php echo $producthelper->getProductFormattedPrice($row->shipping_rate_value); ?></td>
                    <?php }    ?>
                    <td align="center"><?php echo $row->shipping_rate_id; ?></td>
                </tr>
                <?php    $k = 1 - $k;
            }    ?>
            <tfoot>
            <td colspan="5"><?php  echo $this->pagination->getListFooter(); ?></td>
            </tfoot>
        </table>
    </div>

    <input type="hidden" name="view" value="shipping_rate"/>
    <input type="hidden" name="id" value="<?php echo $this->shipping->id;?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
