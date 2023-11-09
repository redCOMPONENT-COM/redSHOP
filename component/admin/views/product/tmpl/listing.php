<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Html\HtmlHelper;

// HtmlHelper::_('behavior.formvalidator');
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('form.validate');

$model = $this->getModel('product');

?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {

        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (!document.formvalidator.isValid(form)) {
            return;
        }

        form.submit();
    }
</script>

<script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                // Validate discount price
                document.formvalidator.setHandler("discountPrice", function (value, element) {
                    if (isNaN(value) || value < 0) {
                        return false;
                    }

                    var $priceField = $("#" + $(element).attr("id").replace("discount_", "product_"));

                    var price = parseFloat($priceField.val());
                    var discount = parseFloat(value);

                    return (discount == 0 || (discount > 0 && price > discount));
                });

                // Validate Product price
                document.formvalidator.setHandler("positiveNumber", function (value) {
                    return !isNaN(value) && value >= 0;
                });
            });
        })(jQuery);
</script>

<form action="<?php echo 'index.php?option=com_redshop&view=product&layout=listing'; ?>" method="post" name="adminForm"
    id="adminForm" class="form-validate">
    <div class="filterTool">
        <div class="filterItem">
            <div class="btn-wrapper input-append">
                <input type="text" name="keyword" value="<?php echo $this->keyword; ?>">
                <input type="submit" class="btn" value="<?php echo Text::_("COM_REDSHOP_SEARCH") ?>">
            </div>
        </div>
        <div class="filterItem">
            <select name="search_field" onchange="javascript:document.adminForm.submit();">
                <option value="p.product_name" <?php echo $this->search_field == 'p.product_name' ? "selected='selected'" : '' ?>>
                    <?php echo Text::_("COM_REDSHOP_PRODUCT_NAME") ?>
                </option>
                <option value="c.category_name" <?php echo $this->search_field == 'c.category_name' ? "selected='selected'" : '' ?>>
                    <?php echo Text::_("COM_REDSHOP_CATEGORY") ?>
                </option>
                <option value="p.product_number" <?php echo $this->search_field == 'p.product_number' ? "selected='selected'" : '' ?>>
                    <?php echo Text::_("COM_REDSHOP_PRODUCT_NUMBER") ?>
                </option>
            </select>
        </div>
    </div>
    <div id="editcell">
        <table class="adminlist table table-striped" id="table-product-price">
            <thead>
                <tr>
                    <th width="5">
                        #
                    </th>
                    <th width="20">
                        <?php echo JHtml::_('redshopgrid.checkall'); ?>
                    </th>
                    <th class="title" width="500">
                        <?php echo JHTML::_(
                            'grid.sort',
                            'COM_REDSHOP_PRODUCT_NAME',
                            'product_name',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th class="title" width="300">
                        <?php echo JHTML::_(
                            'grid.sort',
                            'COM_REDSHOP_PRODUCT_NUMBER',
                            'product_number',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th class="title" width="20">
                        <?php echo Text::_('COM_REDSHOP_PRICE'); ?>
                        <a href="javascript:Joomla.submitbutton('saveprice')" class="btn btn-success btn-sm"
                            title="<?php echo Text::_('COM_REDSHOP_PRICE') ?>">
                            <i class="fa fa-save"></i>
                        </a>
                    </th>
                    <th class="title" width="20">
                        <?php echo Text::_('COM_REDSHOP_DISCOUNT_PRICE'); ?>
                        <a href="javascript:Joomla.submitbutton('savediscountprice')" class="btn btn-success btn-sm"
                            title="<?php echo Text::_('COM_REDSHOP_DISCOUNT_PRICE') ?>">
                            <i class="fa fa-save"></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <?php foreach ($this->products as $i => $row): ?>
                <tr>
                    <td>
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo JHTML::_('grid.id', $i, $row->product_id) ?>
                    </td>
                    <td>
                        <a href="<?php echo Redshop\IO\Route::_(
                            'index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id
                        ) ?>" title="<?php echo Text::_('COM_REDSHOP_EDIT_PRODUCT') ?>">
                            <?php echo $row->product_name ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $row->product_number ?>
                    </td>
                    <td width="20%">
                        <input type="hidden" name='pid[]' value="<?php echo $row->product_id ?>" />
                        <label id="product_price_<?php echo $row->product_id ?>-lbl"
                            for="product_price_<?php echo $row->product_id ?>" class="hidden">
                            <?php echo Text::_('COM_REDSHOP_PRODUCT_PRICE_ERROR_PRICE_MUST_NOT_NEGATIVE_NUMBER') ?>
                        </label>
                        <input type="number" name="price[]" size="4" id="product_price_<?php echo $row->product_id ?>"
                            class="validate-positiveNumber" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                $row->product_price,
                                false
                            ); ?>" />
                    </td>
                    <td width="20%">
                        <label id="discount_price_<?php echo $row->product_id ?>-lbl"
                            for="discount_price_<?php echo $row->product_id ?>" class="hidden">
                            <?php echo Text::_('COM_REDSHOP_DISCOUNT_PRICE_MUST_BE_LESS_THAN_PRICE') ?>
                        </label>
                        <input type="number" id="discount_price_<?php echo $row->product_id ?>" name="discount_price[]"
                            size="4" class="form-control validate-discountPrice"
                            value="<?php echo RedshopHelperProduct::redpriceDecimal($row->discount_price) ?>" />
                    </td>
                </tr>
            <?php endforeach; ?>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="redShopLimitBox">
                            <?php echo $this->pagination->getLimitBox() ?>
                        </div>
                        <?php echo $this->pagination->getListFooter() ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php echo JHtml::_('form.token') ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>