<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

?>
<script language="javascript" type="text/javascript">
    var viewForm = 'prices_detail';
    Joomla.submitbutton = function (pressbutton) {
        checkSubmit(pressbutton, viewForm);
    };
</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist table table-striped" width="100%">
            <thead>
            <tr>
                <th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
                <th width="5%"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
                <th class="title" align="left" width="15%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP'); ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
                <th width="15%"><?php echo JText::_('COM_REDSHOP_PRICE'); ?></th>
                <th width="15%"><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?></th>
            </tr>
            </thead>
            <?php $k = 0;
            for ($i = 0; $i < count($this->media); $i++) {
                $row = $this->media[$i];
                $row->id = $row->price_id;
                //$productId = $row->product_id;
                $link = Redshop\IO\Route::_(
                    'index.php?option=com_redshop&view=prices_detail&task=edit&product_id=' . $row->product_id . '&cid[]=' . $row->price_id
                ); ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
                    <td><a href="<?php echo $link; ?>"
                           title="<?php echo JText::_(
                               'COM_REDSHOP_EDIT_PRODUCT_PRICE'
                           ); ?>"><?php echo $row->product_name; ?></a>
                    </td>
                    <td align="center"><?php echo $row->name; ?></td>
                    <td align="center"><?php echo $row->price_quantity_start; ?></td>
                    <td align="center"><?php echo $row->price_quantity_end; ?></td>
                    <td align="center"
                        width="5%"><?php echo RedshopHelperProductPrice::formattedPrice($row->product_price); ?></td>
                    <td align="center"
                        width="5%"><?php echo RedshopHelperProductPrice::formattedPrice($row->discount_price); ?></td>
                </tr>
                <?php $k = 1 - $k;
            } ?>
            <tfoot>
            <td colspan="8">
                <?php if (version_compare(JVERSION, '3.0', '>=')): ?>
                    <div class="redShopLimitBox">
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>
                <?php endif; ?>
                <?php echo $this->pagination->getListFooter(); ?></td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="prices"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="product_id" value="<?php echo $this->product_id ?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
</form>
