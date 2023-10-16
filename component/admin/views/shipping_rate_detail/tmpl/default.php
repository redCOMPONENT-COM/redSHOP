<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

$editor = \Joomla\CMS\Editor\Editor::getInstance();
?>

<script language="javascript" type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('select[name="shipping_rate_country[]"]').trigger("change");
    });
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
            Joomla.submitform(pressbutton);
            return;
        }

        <?php if ($this->shipper_location): ?>
            if (form.shipping_rate_name.value === "") {
                alert("<?php echo Text::_('COM_REDSHOP_SHIPPING_LOCATION_NAME_MUST_HAVE_A_NAME', true); ?>");
            } else {
                Joomla.submitform(pressbutton);
            }
        <?php else: ?>
            if (form.shipping_rate_name.value === "") {
                alert("<?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_NAME_MUST_HAVE_A_NAME', true); ?>");
            } else if (parseInt(form.shipping_rate_ordertotal_end.value) < parseInt(form.shipping_rate_ordertotal_start.value)) {
                alert("<?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_ORDERTOTAL_END_MUST_MORE', true); ?>");
            } else if (parseInt(form.shipping_rate_zip_end.value) < parseInt(form.shipping_rate_zip_start.value)) {
                alert("<?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_ZIP_END_MUST_MORE', true); ?>");
            } else {
                Joomla.submitform(pressbutton);
            }
        <?php endif ?>
    }
</script>
<form action="<?php echo Redshop\IO\Route::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm"
    enctype="multipart/form-data">
    <input type="hidden" name="extension_id" value="<?php echo $this->shipping->extension_id; ?>" />
    <input type="hidden" name="shipping_class" value="<?php echo $this->shipping->element; ?>" />
    <input type="hidden" name="shipping_rate_id" id="shipping_rate_id"
        value="<?php echo $this->detail->shipping_rate_id; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="shipping_rate_detail" />
    <?php
    if ($this->shipper_location) {
        ?>
        <div class="col50">
            <fieldset class="adminform">
                <legend>
                    <?php echo Text::_('COM_REDSHOP_DETAILS'); ?>
                </legend>
                <table class="admintable" width="100%">
                    <tr>
                        <td width="100" align="right" class="key">
                            <?php echo Text::_('COM_REDSHOP_SHIPPING_LOCATION'); ?>:
                        </td>
                        <td>
                            <input class="text_area" type="text" name="shipping_rate_name" id="shipping_rate_name" size="32"
                                maxlength="250" value="<?php echo $this->detail->shipping_rate_name; ?>" />
                        </td>
                    </tr>
                    <td class="key">
                        <?php echo Text::_('COM_REDSHOP_SHIPPING_LOCATION_INFORMATION'); ?>:
                    </td>
                    <td>
                        <?php echo $editor->display(
                            "shipping_location_info",
                            $this->detail->shipping_location_info,
                            '100%',
                            '400px !important',
                            '100',
                            '20',
                            '1'
                        ); ?>
                    </td>
                    </tr>
                    <tr>
                        <td width="100" align="right" class="key">
                            <label for="name">
                                <?php echo Text::_('COM_REDSHOP_COUNTRY'); ?>:
                            </label>
                        </td>
                        <td>
                            <?php echo $this->lists['shipping_rate_country']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="100" align="right" class="key">
                            <label for="name">
                                <?php echo Text::_('COM_REDSHOP_STATE'); ?>:
                            </label>
                        </td>
                        <td>
                            <?php echo $this->lists['shipping_rate_state']; ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <?php
    } else {
        echo HTMLHelper::_('bootstrap.startTabSet', 'shippingRateTab', array('active' => 'tab1'));
        echo HTMLHelper::_('bootstrap.addTab', 'shippingRateTab', 'tab1', Text::_('COM_REDSHOP_DETAILS'));
        ?>
            <div class="row box box-primary">
                <div class="col-6">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php echo Text::_('COM_REDSHOP_DETAILS'); ?>
                        </h3>
                    </div>
                    <table class="admintable" width="100%">

                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_NAME'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="shipping_rate_name" id="shipping_rate_name"
                                    size="32" maxlength="250" value="<?php echo $this->detail->shipping_rate_name; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_WEIGHT_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_weight_start"
                                    id="shipping_rate_weight_start" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_weight_start
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_WEIGHT_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_weight_end"
                                    id="shipping_rate_weight_end" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_weight_end
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_VOLUME_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_volume_start"
                                    id="shipping_rate_volume_start" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_volume_start
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_VOLUME_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_volume_end"
                                    id="shipping_rate_volume_end" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_volume_end
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_LENGTH_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_length_start"
                                    id="shipping_rate_length_start" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_length_start
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_LENGTH_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_length_end"
                                    id="shipping_rate_length_end" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_length_end
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_WIDTH_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_width_start"
                                    id="shipping_rate_width_start" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_width_start
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_WIDTH_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_width_end"
                                    id="shipping_rate_width_end" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_width_end
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_HEIGHT_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_height_start"
                                    id="shipping_rate_height_start" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_height_start
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_HEIGHT_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_height_end"
                                    id="shipping_rate_height_end" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_height_end
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_ORDERTOTAL_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_ordertotal_start"
                                    id="shipping_rate_ordertotal_start" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_ordertotal_start
                                    ); ?>" />
                            </td>
                        </tr>

                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_ORDERTOTAL_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_ordertotal_end"
                                    id="shipping_rate_ordertotal_end" size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_ordertotal_end
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_ZIP_START'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_zip_start"
                                    id="shipping_rate_zip_start" size="32" maxlength="250"
                                    value="<?php echo $this->detail->shipping_rate_zip_start; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_ZIP_END'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" step="any" name="shipping_rate_zip_end"
                                    id="shipping_rate_zip_end" size="32" maxlength="250"
                                    value="<?php echo $this->detail->shipping_rate_zip_end; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_COUNTRY'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['shipping_rate_country']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_STATE'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['shipping_rate_state']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPINGRATE_PRODUCT'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['shipping_product']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_CATEGORY'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['shipping_rate_on_category']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHOPPER_GROUP'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['shipping_rate_on_shopper_group']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_VALUE'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="number" name="shipping_rate_value" id="shipping_rate_value"
                                    size="32" maxlength="250" value="<?php echo RedshopHelperProduct::redpriceDecimal(
                                        $this->detail->shipping_rate_value
                                    ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_PRIORITY'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="shipping_rate_priority"
                                    id="shipping_rate_priority" size="32" maxlength="250"
                                    value="<?php echo $this->detail->shipping_rate_priority; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_RATE_FOR'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['company_only']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_SHIPPING_VAT_GROUP_LBL'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php echo $this->lists['shipping_tax_group_id']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_ADD_VAT'); ?>:
                                </label>
                            </td>
                            <td>
                                <?php

                                $checked = '';

                                if ($this->detail->apply_vat) {
                                    $checked = "checked='checked'";
                                }
                                ?>
                                <div class="form-check form-check-inline has-success">
                                    <input type="checkbox" name="apply_vat" id="apply_vat_checkbox"
                                        class="form-check-input valid form-control-success" value="1" aria-invalid="false"
                                        <?php echo $checked; ?> />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_CONSIGNOR_CARRIER_CODE'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="consignor_carrier_code"
                                    id="consignor_carrier_code" size="32" maxlength="250"
                                    value="<?php echo $this->detail->consignor_carrier_code; ?>" />
                            </td>
                        </tr>
                        <?php

                        if ($this->detail->shipping_class === 'default_shipping') {
                            ?>
                            <tr>
                                <td width="100" align="right" class="key">
                                    <label for="name">
                                        <?php echo Text::_('COM_REDSHOP_DELIVER_TYPE'); ?>:
                                    </label>
                                </td>
                                <td>
                                    <div id='changestate'>
                                        <?php echo $this->lists['deliver_type']; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="name">
                                    <?php echo Text::_('COM_REDSHOP_ECONOMIC_DISPLAYNUMBER'); ?>:
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="economic_displaynumber"
                                    id="economic_displaynumber" size="32" maxlength="250"
                                    value="<?php echo $this->detail->economic_displaynumber; ?>" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php
        echo HTMLHelper::_('bootstrap.endTab');
        if (!empty($this->lists['extra_field'])) {
            echo HTMLHelper::_('bootstrap.addTab', 'shippingRateTab', 'tab2', Text::_('COM_REDSHOP_EXTRA_FIELD')); ?>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php echo Text::_('COM_REDSHOP_EXTRA_FIELD'); ?>
                    </h3>
                </div>
                <?php
                echo $this->lists['extra_field']; ?>
            </div>
            <?php
            echo HTMLHelper::_('bootstrap.endTab');
        } else {
            echo '<input type="hidden" name="noextra_field" value="1">';
        }

        echo HTMLHelper::_('bootstrap.endTabSet');
    } ?>
    <div class="clr"></div>
</form>