<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$productHelper = productHelper::getInstance();
?>
<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        form.submit();
    }
</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist table table-striped" width="100%">
            <thead>
            <tr>
                <th width="30%"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_NAME'); ?></th>
                <th width="30%"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
                <th width="30%"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
                <th width="15%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?>
                    <a class="btn btn-primary btn-small" onclick="Joomla.submitbutton('saveprice')" href="#">
                        <i class="fa fa-save"></i>
                    </a>
                </th>
            </tr>
            </thead>
			<?php foreach ($this->prices as $row): ?>
                <tr>
                    <td align="center"><?php echo $row->shopper_group_name ?></td>
                    <td align="center">
                        <input type="number" name="price_quantity_start[]" id="price_quantity_start"
                               value="<?php echo $row->price_quantity_start ?>" class="form-control"/>
                    </td>
                    <td align="center">
                        <input type="number" name="price_quantity_end[]" id="price_quantity_end"
                               value="<?php echo $row->price_quantity_end ?>" class="form-control"/>
                    </td>
                    <td align="center" width="5%">
                        <input type="hidden" name="price_id[]" value="<?php echo $row->price_id ?>"/>
                        <input type="hidden" name="shopper_group_id[]" value="<?php echo $row->shopper_group_id; ?>"/>
                        <input type="number" name="price[]" class="form-control"
                               value="<?php echo $productHelper->redpriceDecimal($row->product_price); ?>"/>
                    </td>
                </tr>
			<?php endforeach; ?>
        </table>
    </div>
    <input type="hidden" name="view" value="product_price"/>
    <input type="hidden" name="task" value="saveprice"/>
    <input type="hidden" name="pid" value="<?php echo $this->pid ?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_redshop"/>
</form>
