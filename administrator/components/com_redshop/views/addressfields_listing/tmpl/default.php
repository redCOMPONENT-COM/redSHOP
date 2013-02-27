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

$option = JRequest::getVar('option', '', 'request', 'string');
$filter = JRequest::getVar('filter');

//Ordering allowed ?

$pagination         = $this->pagination;
$ordering           = ($this->lists['order'] == 'ordering');
$field_section_drop = JRequest::getVar('field_section_drop');
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table width="100%">
            <tr>
                <td width="100%" valign="top" align="left" class="key" colspan="5">
                    <?php echo JText::_('COM_REDSHOP_FIELD_SECTION') . " : " . $this->lists['addresssections']; ?>
                </td>
            </tr>
        </table>
        <table class="adminlist">
            <thead>
            <tr>
                <th width="5%">
                    <?php echo JText::_('COM_REDSHOP_NUM'); ?>
                </th>
                <th width="5%">
                    <input type="checkbox" name="toggle" value=""
                           onclick="checkAll(<?php echo count($this->fields); ?>);"/>
                </th>
                <th class="title" width="20%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_TITLE', 'field_title', $this->lists['order_Dir'], $this->lists['order']); ?>

                </th>
                <th width="10%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_SECTION', 'field_section', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="order" width="10%">
                    <?php  echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
                    <?php  if ($ordering)
                {
                    echo JHTML::_('grid.order', $this->fields);
                }  ?>
                </th>


            </tr>
            </thead>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->fields); $i < $n; $i++)
            {
                $row     = &$this->fields[$i];
                $row->id = $row->field_id;
                $link    = JRoute::_('index.php?option=' . $option . '&view=fields_detail&task=edit&cid[]=' . $row->field_id);

                $published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td>
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo JHTML::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td width="30%">
                        <?php echo $row->field_title; ?>
                    </td>

                    <td class="order" width="30%">
                        <?php
                        if ($row->field_section == 1)
                        {
                            echo 'Product';
                        }
                        elseif ($row->field_section == 2)
                        {
                            echo 'Category';
                        }
                        elseif ($row->field_section == 3)
                        {
                            echo 'Form';
                        }
                        elseif ($row->field_section == 4)
                        {
                            echo 'Email';
                        }
                        elseif ($row->field_section == 5)
                        {
                            echo 'Confirmation';
                        }
                        elseif ($row->field_section == 6)
                        {
                            echo 'Userinformations';
                        }
                        elseif ($row->field_section == 7)
                        {
                            echo 'Customer Address';
                        }
                        elseif ($row->field_section == 8)
                        {
                            echo 'Company Address';
                        }
                        elseif ($row->field_section == 9)
                        {
                            echo 'Color sample';
                        }
                        elseif ($row->field_section == 10)
                        {
                            echo 'Manufacturer';
                        }
                        elseif ($row->field_section == 11)
                        {
                            echo 'Shipping';
                        }
                        elseif ($row->field_section == 12)
                        {
                            echo 'Product UserField';
                        }
                        elseif ($row->field_section == 13)
                        {
                            echo 'Giftcard UserField';
                        }
                        elseif ($row->field_section == 14)
                        {
                            echo 'Customer shipping Address';
                        }
                        else
                        {
                            echo 'Company Shipping Address';
                        }

                        ?>


                    <td class="order" width="30%">
                        <span><?php echo $this->pagination->orderUpIcon($i, ($row->field_section == @$this->fields[$i - 1]->field_section), 'orderup', 'Move Up', $ordering); ?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i, $n, ($row->field_section == @$this->fields[$i + 1]->field_section), 'orderdown', 'Move Down', $ordering); ?></span>

                        <?php $disabled = $ordering ? '' : 'disabled="disabled"'; ?>
                        <input type="text" name="order[]" size="5"
                               value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area"
                               style="text-align: center"/>
                    </td>
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

    <input type="hidden" name="view" value="addressfields_listing"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
