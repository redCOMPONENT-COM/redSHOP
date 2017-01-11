<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT'); ?></h3>
            </div>
            <div class="box-body">
                <table class="admintable table">
                    <tr>
                        <td>
							<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT'); ?>
                        </td>
                        <td>

							<?php echo $this->lists['related_product']; ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="fetch_child_for_related_product">
								<?php echo JText::_('COM_REDSHOP_CHILD_PRODUCT_AS_RELATED_PRODUCT_TEXT'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="checkbox"
                                   value="1"
                                   id="fetch_child_for_related_product"
                                   name="fetch_child_for_related_product"
                                   onclick="updateRelatedProduct(this);"
                            />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    var preproductids = [];

    function updateRelatedProduct(me) {
        (function ($) {
            if (preproductids.length > 0) {
                updateRelatedBox(preproductids, me.checked);
                return;
            }

            var url = "index.php?option=com_redshop&cid[]=" + $("#product_id").val() + "&task=product_detail.getChildProducts";
            url += "&tmpl=component&json=1&<?php echo JSession::getFormToken() ?>=1";

            $.ajax({
                url: url,
                type: 'GET'
            })
                .done(function (response) {
                    var products = response.split(":");

                    updateRelatedBox(products, true);

                    preproductids = products;
                })
                .fail(function (response) {
                    alert(response.responseText);
                });
        })(jQuery);
    }

    function updateRelatedBox(products, ischecked) {
        (function ($) {
            var productids = products[0].split(",");
            var productnames = products[1].split(",");

            var selTo = $("#related_product");
            var checkedData = selTo.val().split(',');
            var currentData = selTo.select2("data");

            if (ischecked) {
                for (i = 0; i < productids.length; i++) {
                    if ($.inArray(productids[i], checkedData) == -1) {
                        currentData.push({id: productids[i], text: productnames[i]});
                    }
                }

                selTo.select2('data', currentData);
            } else {
                var newData = {};

                for (i = 0; i < currentData.length; i++) {
                    if ($.inArray(currentData[i].id, productids) == -1) {
                        newData.push(currentData[i]);
                    }
                }

                if (!newData.length)
                    newData = null;

                selTo.select2('data', newData);
            }
        })(jQuery);
    }
</script>
