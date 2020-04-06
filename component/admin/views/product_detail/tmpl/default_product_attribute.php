<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?></h3>
            </div>
            <div class="box-body">
                <fieldset class="adminform">
                    <div class="alert alert-success" role="alert"><?php echo JText::_(
                            'COM_REDSHOP_HINT_ATTRIBUTE'
                        ); ?></div>

                    <div class="col-sm-4">
                        <?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE_SET_LBL'); ?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $this->lists['attributesSet']; ?>
                    </div>

                    <div class="col-sm-4">
                        <?php echo JText::_('COM_REDSHOP_COPY_ATTRIBUTES_FROM_ATTRIBUTE_SET'); ?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $this->lists['copy_attribute']; ?>
                    </div>

                    <a class="btn btn-success add_attribute pull-right" href="#"> <?php echo '+ ' . JText::_(
                                'COM_REDSHOP_NEW_ATTRIBUTE'
                            ); ?></a>
                </fieldset>
                <hr/>
                <?php echo RedshopLayoutHelper::render('product_detail.product_attribute', array('this' => $this)); ?>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        jQuery('#attribute_set_id').change(function () {
            jQuery.ajax({
                url: 'index.php?option=com_redshop&task==product_detail.ajaxDisplayAttributeSet',
                type: 'POST',
                dataType: 'json',
                data: {
                    attribute_copy: jQuery('[name=copy_attribute]:checked').val(),
                    product_id: <?php echo $this->detail->product_id ?>,
                    attribute_set: jQuery('#attribute_set_id').val(),
                },
                success: function (res) {
                    jQuery('[name=count_attr]').val('0');
                    jQuery('.divInspectFromHideShow').remove();

                    for (i = 0; i < res.length; i++) {
                        var objattribute = res[i];

                        if (!jQuery.isEmptyObject(objattribute)) {
                            jQuery('.add_attribute').trigger('click');

                            jQuery('[name="attribute[' + i + '][name]"]').val(objattribute.attribute_name);
                            jQuery('[name="attribute[' + i + '][attribute_description]"]').val(objattribute.attribute_description);
                            jQuery('[name="attribute[' + i + '][attribute_description]"]').val(objattribute.attribute_description);
                            jQuery('[name="attribute[' + i + '][ordering]"]').val(objattribute.ordering);

                            if (objattribute.allow_multiple_selection == 1) {
                                jQuery('[name="attribute[' + i + '][allow_multiple_selection]"]').prop('checked', true);
                            }

                            if (objattribute.hide_attribute_price) {
                                jQuery('[name="attribute[' + i + '][hide_attribute_price]"]').prop('checked', true);
                            }

                            if (objattribute.display_type == 'radio') {
                                jQuery('[name="attribute[' + i + '][display_type]"]').val('radio')
                            }

                            if (objattribute.attribute_required == 1) {
                                jQuery('[name="attribute[' + i + '][required]"]').prop('checked', true);
                            }

                            // Append attribute property
                            for (j = 0; j < objattribute.propeties.length; j++) {
                                var objPropeties = objattribute.propeties[j];

                                if (!jQuery.isEmptyObject(objPropeties)) {
                                    if (j !== 0) {
                                        jQuery('.add_property').trigger('click');
                                    }

                                    var prefixPro = '[name="attribute[' + i + '][property][' + j + ']';

                                    jQuery(prefixPro + '[name]"]').val(objPropeties.text);
                                    jQuery(prefixPro + '[ordering]"]').val(objPropeties.ordering);
                                    jQuery(prefixPro + '[oprand]"]').val(objPropeties.oprand);
                                    jQuery(prefixPro + '[price]"]').val(objPropeties.property_price);
                                    jQuery(prefixPro + '[extra_field]"]').val(objPropeties.extra_field);

                                    if (objPropeties.setdefault_selected == 1) {
                                        jQuery(prefixPro + '[default_sel]"]').prop('checked', true);
                                    }

                                    var containerProp = jQuery(prefixPro + '[name]"]').closest('.attr_tbody');

                                    if (objPropeties.subProperties.length >= 1) {
                                        for (s = 0; s < objPropeties.subProperties.length; s++) {
                                            var subProperties = objPropeties.subProperties[s];

                                            if (!jQuery.isEmptyObject(subProperties)) {

                                                jQuery(prefixPro + '[name]"]').closest('.attr_tbody').find('.add_subproperty').trigger('click');
                                                jQuery(prefixPro + '[subproperty][title]"]').val(subProperties.subattribute_color_title);

                                                var prefixSub = prefixPro + '[subproperty][' + s + ']';

                                                jQuery(prefixSub + '[name]"]').val(subProperties.subattribute_color_name);
                                                jQuery(prefixSub + '[order]"]').val(subProperties.ordering);
                                                jQuery(prefixSub + '[oprand]"]').val(subProperties.oprand);
                                                jQuery(prefixSub + '[price]"]').val(subProperties.subattribute_color_price);
                                                jQuery(prefixSub + '[number]"]').val(subProperties.subattribute_color_number);
                                                jQuery(prefixSub + '[extra_field]"]').val(subProperties.extra_field);
                                                jQuery(prefixSub + '[mainImage]"]').val(subProperties.subattribute_color_main_image);
                                                jQuery(prefixSub + '[image]"]').val(subProperties.subattribute_color_image);

                                                if (subProperties.setrequire_selected == 1) {
                                                    jQuery(prefixPro + '[req_sub_att]"]').prop('checked', true);
                                                }

                                                if (subProperties.setmulti_selected == 1) {
                                                    jQuery(prefixPro + '[multi_sub_att]"]').prop('checked', true);
                                                }

                                                if (subProperties.setdefault_selected == 1) {
                                                    jQuery(prefixSub + '[chk_propdselected]"]').prop('checked', true);
                                                }

                                                if (subProperties.setdisplay_type === 'radio') {
                                                    jQuery(prefixPro + '[setdisplay_type]"]').val('radio');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            })
        });
    })
</script>
