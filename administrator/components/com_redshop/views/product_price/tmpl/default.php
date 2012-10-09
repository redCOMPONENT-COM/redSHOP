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
$option        = JRequest::getVar('option', '', 'request', 'string');
$producthelper = new producthelper();
?>
<script language="javascript" type="text/javascript">

    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton) {
            form.task.value = pressbutton;
        }

        form.submit();
    }
</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist" width="100%">
            <thead>
            <tr>
                <th width="30%"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_NAME'); ?></th>
                <th width="30%"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
                <th width="30%"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
                <th width="15%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?>&nbsp;&nbsp;
                    <div style="float:right;">
                        <a onclick="Joomla.submitbutton('saveprice')" href="#">
                            <div style="
								width:17px;
								height:17px;
								background:url(templates/bluestork/images/admin/filesave.png) 0 0;
								"
                                >

                            </div>
                        </a>
                    </div>
                </th>
            </tr>
            </thead>
            <?php    $k = 0;
            for ($i = 0; $i < count($this->prices); $i++)
            {
                $row     = &$this->prices[$i];
                $row->id = $row->price_id;
                //$product_id = $row->product_id;
                ?>
                <tr class="<?php echo "row$k"; ?>">


                    <td align="center"><?php echo $row->shopper_group_name;?></td>
                    <td align="center"><input type="text" name="price_quantity_start[]" id="price_quantity_start"
                                              value=" <?php echo $row->price_quantity_start;?>"/></td>
                    <td align="center"><input type="text" name="price_quantity_end[]" id="price_quantity_end"
                                              value="<?php echo $row->price_quantity_end;?>"/></td>
                    <td align="center" width="5%"><input type="hidden" name="price_id[]"
                                                         value="<?php echo $row->id; ?>"><input type="hidden"
                                                                                                name="shopper_group_id[]"
                                                                                                value="<?php echo $row->shopper_group_id; ?>"><input
                        type="text" name="price[]"
                        value="<?php echo $producthelper->redpriceDecimal($row->product_price); ?>"></td>
                </tr>
                <?php        $k = 1 - $k;
            }    ?>

        </table>
    </div>
    <input type="hidden" name="view" value="product_price"/>
    <input type="hidden" name="task" value="saveprice"/>
    <input type="hidden" name="pid" value="<?php echo $this->pid?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_redshop"/>
</form>
