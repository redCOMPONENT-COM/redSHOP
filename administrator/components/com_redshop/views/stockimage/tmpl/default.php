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
//
defined('_JEXEC') or die ('Restricted access');

$option = JRequest::getVar('option', '', 'request', 'string');
$filter = JRequest::getVar('filter');

$model = $this->getModel('stockimage');
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist">
            <thead>
            <tr>
                <th><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
                <th><input type="checkbox" name="toggle" value=""
                           onclick="checkAll(<?php echo count($this->data);?>);"/></th>
                <th><?php echo JHTML::_('grid.sort', 'STOCK_AMOUNT_IMAGE_TOOLTIP_LBL', 'stock_amount_image_tooltip', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
                <th><?php echo JHTML::_('grid.sort', 'STOCK_AMOUNT_QUANTITY_LBL', 'stock_quantity', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
                <th><?php echo JHTML::_('grid.sort', 'STOCK_AMOUNT_OPTION_LBL', 'stock_option', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
                <th><?php echo JHTML::_('grid.sort', 'STOCKROOM_NAME', 'stockroom_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
                <th><?php echo JHTML::_('grid.sort', 'ID', 'stock_amount_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
            </tr>
            </thead>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->data); $i < $n; $i++)
            {
                $row     = &$this->data[$i];
                $row->id = $row->stock_amount_id;
                $link    = JRoute::_('index.php?option=' . $option . '&view=stockimage_detail&task=edit&cid[]=' . $row->id);    ?>
                <tr class="<?php echo "row$k";?>">
                    <td><?php echo $this->pagination->getRowOffset($i);?></td>
                    <td><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
                    <td><a href="<?php echo $link;?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_STOCKIMAGE');    ?>"><?php echo $row->stock_amount_image_tooltip;?></a>
                    </td>
                    <td><?php echo $row->stock_quantity;?></td>
                    <td><?php echo $model->getStockAmountOption($row->stock_option);?></td>
                    <td><?php echo $row->stockroom_name;?></td>
                    <td><?php echo $row->stock_amount_id;?></td>
                </tr>
                <?php    $k = 1 - $k;
            }    ?>
            <tfoot>
            <td colspan="7"><?php echo $this->pagination->getListFooter();?></td>
            </tfoot>
        </table>
    </div>

    <input type="hidden" name="view" value="stockimage"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists ['order'];?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir'];?>"/>
</form>
