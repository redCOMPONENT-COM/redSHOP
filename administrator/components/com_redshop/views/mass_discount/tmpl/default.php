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
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
$producthelper = new producthelper();
$option        = JRequest::getVar('option', '', 'request', 'string');
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
                           onclick="checkAll(<?php echo count($this->discounts); ?>);"/>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_DISCOUNT_NAME', 'discount_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_DISCOUNT_AMOUNT', 'discount_amount', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_DISCOUNT_TYPE', 'discount_type', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="5%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'mass_discount_id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

            </tr>
            </thead>
            <?php

            $k = 0;
            for ($i = 0, $n = count($this->discounts); $i < $n; $i++)
            {
                $row     = &$this->discounts[$i];
                $row->id = $row->mass_discount_id;
                $link    = JRoute::_('index.php?option=' . $option . '&view=mass_discount_detail&task=edit&cid[]=' . $row->mass_discount_id);
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_DISCOUNT'); ?>">
                            <?php
                            echo $row->discount_name;
                            ?></a>
                    </td>
                    <td>
                        <?php
                        if ($row->discount_type == 0)
                        {
                            echo $producthelper->getProductFormattedPrice($row->discount_amount);
                        }
                        else
                        {
                            echo $row->discount_amount . '%';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($row->discount_type == 0)
                    {
                        echo JText::_('COM_REDSHOP_TOTAL');
                    }
                    else
                    {
                        echo JText::_('COM_REDSHOP_PERCENTAGE');
                    }
                        ?>
                    </td>

                    <td align="center"><?php echo $row->mass_discount_id; ?></td>
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
    </div>

    <input type="hidden" name="view" value="mass_discount"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
