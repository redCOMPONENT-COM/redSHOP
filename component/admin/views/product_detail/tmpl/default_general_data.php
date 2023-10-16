<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');
HTMLHelper::script('com_redshop/jquery.inputmask.min.js', ['relative' => true]);

$priceDecimal   = Redshop::getConfig()->get('PRICE_DECIMAL', '.');
$priceThousand  = Redshop::getConfig()->get('THOUSAND_SEPERATOR', ',');
$editor         = \Joomla\CMS\Editor\Editor::getInstance();
$calendarFormat = Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d');
$config         = JFactory::getConfig();
$tz             = new \DateTimeZone($config->get('offset'));

$media = RedshopEntityProduct::getInstance($this->detail->product_id)->getMedia();

$fullMediaId = 0;
$fullImage   = $this->detail->product_full_image;

foreach ($media->getAll() as $mediaItem) {
    if ($mediaItem->get('media_name') == $this->detail->product_full_image) {
        $fullImage   = $mediaItem->get('media_name');
        $fullMediaId = $mediaItem->getId();
    }
}
?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $.ajax({
                type: "POST",
                url: "index.php?option=com_redshop&view=product_detail&task=product_detail.ajaxGetAllProductNumber",
                data: {
                    '<?php echo JSession::getFormToken() ?>': 1,
                    "product_id": <?php echo $this->detail->product_id ?>
                },
                dataType: "json",
                success: function (data) {
                    document.formvalidator.setHandler("productNumber", function (value) {
                        return !data.includes(value);
                    });
                }
            });

            if ($("input[name=not_for_sale]:checked").val() == 1) {
                $("#not_for_sale_showprice").show();
            } else {
                $("#not_for_sale_showprice").hide();
            }

            $("input[name=not_for_sale]").change(function () {
                if ($(this).val() == 1) {
                    $("#not_for_sale_showprice").show(500);
                } else {
                    $("#not_for_sale_showprice").hide(500);
                }
            });

            $.extend(true, Dropzone.prototype.defaultOptions, {
                processing: function processing(file) {
                    var reloading_img = '<div class="image  wait-loading" ><img src="' + redSHOP.RSConfig._('SITE_URL') + '/media/com_redshop/images/reloading.gif" alt="" border="0" ></div>';
                    $('#general_data > .row').css("opacity", 0.2);
                    $('#general_data').prepend(reloading_img);

                    if (file.previewElement) {
                        file.previewElement.classList.add("dz-processing");
                        if (file._removeLink) {
                            return file._removeLink.textContent = this.options.dictCancelUpload;
                        }
                    }
                },

                success: function success(file) {
                    $('.wait-loading').remove();
                    $('#general_data > .row').css("opacity", 1);

                    if (file.previewElement) {
                        return file.previewElement.classList.add("dz-success");
                    }
                }
            });

            $("#product_price,#discount_price").inputmask({
                "alias": "numeric",
                "groupSeparator": '<?php echo $priceThousand ?>',
                "autoGroup": true,
                "digits": '<?php echo $priceDecimal ?>',
                "digitsOptional": false,
                "rightAlign": 0,
                "autoUnmask": true,
                "removeMaskOnSubmit": true
            });

            //SqueezeBox.presets.onClose = function (e) {
            //    if (this.options.classWindow == 'additional-media-popup') {
            //        var reloading_img = '<div class="image" style="text-align: center;"><img src="' + redSHOP.RSConfig._('SITE_URL') + '/media/com_redshop/images/reloading.gif" alt="" border="0" ></div>';
			//
            //        <?php //JFactory::getApplication()->setUserState(
            //        'com_redshop.product_detail.selectedTabPosition',
            //        'general_data'
            //    )  ?>
			//
            //        $('#general_data').html(reloading_img);
            //        window.location.reload();
            //    }
            //};
        });
    })(jQuery);
</script>
<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_PRODUCT_INFORMATION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="product_name" id="product_name-lbl">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_NAME'); ?>
                                <span class="star text-danger"> *</span>
                            </label>
                            <input class="form-control"
                                   type="text"
                                   name="product_name"
                                   id="product_name"
                                   size="32"
                                   maxlength="250"
                                   value="<?php echo htmlspecialchars($this->detail->product_name); ?>"/>
                        </div>

                        <div class="form-group">
                            <label for="product_number" id="product_number-lbl">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_NUMBER') ?><span
                                        class="star text-danger"> *</span>
                                <?php echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_NUMBER'),
                                    Text::_('COM_REDSHOP_PRODUCT_NUMBER')
                                ); ?>
                            </label>
                            <input class="form-control validate-productNumber"
                                   type="text" name="product_number" id="product_number" size="32" maxlength="250"
                                   value="<?php echo $this->detail->product_number; ?>"
                            />
                            <span class="text-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="categories">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?>
                                <span class="star text-danger"> *</span>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_CATEGORY'),
                                    Text::_('COM_REDSHOP_PRODUCT_CATEGORY')
                                );
                                ?>
                            </label>
                            <?php echo $this->lists['categories']; ?>
                        </div>

                        <div class="form-group">
                            <label for="product_type">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_TYPE'); ?>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_PRODUCT_TYPE_TIP'),
                                    Text::_('COM_REDSHOP_PRODUCT_TYPE')
                                );
                                ?>
                            </label>
                            <?php echo $this->lists['product_type']; ?>
                        </div>

                        <div class="form-group">
                            <label for="product_template">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?>
                                <span class="star text-danger"> *</span>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_TEMPLATE'),
                                    Text::_('COM_REDSHOP_PRODUCT_TEMPLATE')
                                );
                                ?>
                            </label>
                            <?php echo $this->lists['product_template']; ?>
                        </div>

                        <div class="form-group">
                            <label for="manufacturer_id">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_MANUFACTURER'); ?>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_MANUFACTURER'),
                                    Text::_('COM_REDSHOP_PRODUCT_MANUFACTURER')
                                );
                                ?>
                            </label>
                            <?php echo $this->lists['manufacturers']; ?>
                        </div>

                        <div class="form-group">
                            <label for="published0"><?php echo Text::_('COM_REDSHOP_PUBLISHED'); ?></label>
                            <?php echo $this->lists['published']; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="product_price">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_PRICE'); ?>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_PRICE'),
                                    Text::_('COM_REDSHOP_PRODUCT_PRICE')
                                );
                                ?>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><?php echo Redshop::getConfig()->get(
                                        'REDCURRENCY_SYMBOL'
                                    ) ?></span>
                                <input class="form-control" type="text" name="product_price" id="product_price"
                                       size="10" maxlength="10" value="<?php echo $this->detail->product_price ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product_tax_group_id">
                                <?php echo Text::_('COM_REDSHOP_PRODUCT_TAX_GROUP'); ?>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_TAX'),
                                    Text::_('COM_REDSHOP_PRODUCT_TAX_GROUP')
                                );
                                ?>
                            </label>
                            <?php echo $this->lists['product_tax_group_id']; ?>
                        </div>

                        <div class="form-group">
                            <label for="discount_price">
                                <?php echo Text::_('COM_REDSHOP_DISCOUNT_PRICE'); ?>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PRICE'),
                                    Text::_('COM_REDSHOP_DISCOUNT_PRICE')
                                );
                                ?>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><?php echo Redshop::getConfig()->get(
                                        'REDCURRENCY_SYMBOL'
                                    ) ?></span>
                                <input class="form-control" type="text" name="discount_price" id="discount_price"
                                       size="10"
                                       maxlength="10" style="text-align: left;"
                                       value="<?php echo $this->detail->discount_price; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="discount_stratdate"><?php echo Text::_(
                                    'COM_REDSHOP_DISCOUNT_START_DATE'
                                ); ?></label>
                            <?php
                            $startDate = null;

                            if ($this->detail->discount_stratdate) {
                                $startDate = is_numeric($this->detail->discount_stratdate) ?
                                    date_create_from_format('U', $this->detail->discount_stratdate)->setTimezone(
                                        $tz
                                    )->format($calendarFormat)
                                    : $this->detail->discount_stratdate;
                            }

                            echo HtmlHelper::_(
                                'redshopcalendar.calendar',
                                $startDate,
                                'discount_stratdate',
                                'discount_stratdate',
                                $calendarFormat,
                                array('class' => 'form-control', 'size' => '15', 'maxlength' => '19'),
                                null,
                                $config->get('offset')
                            );
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="discount_enddate"><?php echo Text::_(
                                    'COM_REDSHOP_DISCOUNT_END_DATE'
                                ); ?></label>
                            <?php
                            $endDate = null;

                            if ($this->detail->discount_enddate) {
                                $endDate = is_numeric($this->detail->discount_enddate) ?
                                    date_create_from_format('U', $this->detail->discount_enddate)->setTimezone(
                                        $tz
                                    )->format($calendarFormat)
                                    : $this->detail->discount_enddate;
                            }

                            echo HtmlHelper::_(
                                'redshopcalendar.calendar',
                                $endDate,
                                'discount_enddate',
                                'discount_enddate',
                                $calendarFormat,
                                array('class' => 'form-control', 'size' => '15', 'maxlength' => '19'),
                                null,
                                $config->get('offset')
                            );
                            ?>
                        </div>

                        <?php $display = "";
                        if (!$this->detail->discount_stratdate || !$this->detail->discount_enddate) : ?>
                            <?php $display = 'style="display: none"' ?>
                        <?php endif; ?>

                        <div class="alert alert-info" <?php echo $display ?>>
                            <?php
                            $isProductOnSale = ($this->detail->product_on_sale) ? Text::_('JYES') : Text::_('JNO');
                            echo Text::sprintf('COM_REDSHOP_PRODUCT_ON_SALE_HINT', $isProductOnSale);
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="product_tax_group_id">
                                <?php echo Text::_('JTAG'); ?>
                                <?php
                                echo HtmlHelper::_('redshop.tooltip',
                                    Text::_('JTAG'),
                                    Text::_('JTAG_DESC')
                                );
                                ?>
                            </label>
                            <?php echo $this->lists['jtags']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_DESCRIPTION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label><?php echo Text::_('COM_REDSHOP_FULL_DESCRIPTION'); ?></label>
                    <?php echo $editor->display(
                        "product_desc",
                        $this->detail->product_desc,
                        '100%',
                        '400px !important',
                        '100',
                        '20'
                    ); ?>
                </div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label><?php echo Text::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?></label>
                    <?php echo $editor->display(
                        "product_s_desc",
                        $this->detail->product_s_desc,
                        '100%',
                        '400px !important',
                        '100',
                        '20'
                    ); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_PRODUCT_IMAGE'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <?php echo RedshopHelperMediaImage::render(
                        'product_full_image',
                        'product',
                        $this->detail->product_id,
                        'product',
                        $fullImage,
                        false,
                        false,
                        $fullMediaId
                    ) ?>
                </div>
                <?php if ($this->detail->product_id > 0) : ?>
                    <?php $ilink = 'index.php?tmpl=component&option=com_redshop&view=media&section_id='
                        . $this->detail->product_id . '&showbuttons=1&media_section=product'; ?>
                    <div class="form-group">
                        <button type="button"
								class="joom-box btn btn-primary ModalProductDetailButton"
								data-url="<?php echo Redshop\IO\Route::_($ilink, false) ?>">
                            <?php echo Text::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES'); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_PRODUCT_BACK_IMAGE'); ?></h3>
            </div>
            <div class="box-body">
                <?php
                echo RedshopLayoutHelper::render(
                    'component.image',
                    array(
                        'id'        => 'product_back_full_image',
                        'deleteid'  => 'back_image_delete',
                        'displayid' => 'back_image_display',
                        'type'      => 'product',
                        'image'     => $this->detail->product_back_full_image
                    )
                );
                ?>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_PRODUCT_MEASURES'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="product_volume">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_VOLUME'); ?>
                        (<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?><sup>3</sup>)
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_VOLUME'),
                            Text::_('COM_REDSHOP_PRODUCT_VOLUME')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="text"
                           name="product_volume"
                           id="product_volume"
                           size="10"
                           maxlength="10"
                           value="<?php echo RedshopHelperProduct::redunitDecimal($this->detail->product_volume); ?>"
                    />
                </div>

                <div class="form-group">
                    <label for="product_length">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_LENGTH'); ?>
                        (<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>)
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_LENGTH'),
                            Text::_('COM_REDSHOP_PRODUCT_LENGTH')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="text"
                           name="product_length"
                           id="product_length"
                           size="10"
                           maxlength="10"
                           value="<?php echo RedshopHelperProduct::redunitDecimal($this->detail->product_length); ?>"
                    />
                </div>

                <div class="form-group">
                    <label for="product_width">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_WIDTH'); ?>
                        (<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>)
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_WIDTH'),
                            Text::_('COM_REDSHOP_PRODUCT_WIDTH')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="text"
                           name="product_width"
                           id="product_width"
                           size="10"
                           maxlength="10"
                           value="<?php echo RedshopHelperProduct::redunitDecimal($this->detail->product_width); ?>"
                    />
                </div>

                <div class="form-group">
                    <label for="product_height">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_HEIGHT'); ?>
                        (<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>)
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_HEIGHT'),
                            Text::_('COM_REDSHOP_PRODUCT_HEIGHT')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="text"
                           name="product_height"
                           id="product_height"
                           size="10"
                           maxlength="10"
                           value="<?php echo RedshopHelperProduct::redunitDecimal($this->detail->product_height); ?>"
                    />
                </div>

                <div class="form-group">
                    <label for="product_diameter">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_DIAMETER'); ?>
                        (<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>)
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_DIAMETER'),
                            Text::_('COM_REDSHOP_PRODUCT_DIAMETER')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="text"
                           name="product_diameter"
                           id="product_diameter"
                           size="10"
                           maxlength="10"
                           value="<?php echo RedshopHelperProduct::redunitDecimal($this->detail->product_diameter); ?>"
                    />
                </div>

                <div class="form-group">
                    <label for="weight">
                        <?php echo Text::_('COM_REDSHOP_WEIGHT_LBL'); ?>
                        (<?php echo Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'); ?>)
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_WEIGHT'),
                            Text::_('COM_REDSHOP_WEIGHT_LBL')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="text"
                           name="weight"
                           id="weight"
                           size="10"
                           maxlength="10"
                           value="<?php echo RedshopHelperProduct::redunitDecimal($this->detail->weight); ?>"
                    />
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_ADDITIONAL_INFORMATION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="supplier_id">
                        <?php echo Text::_('COM_REDSHOP_SUPPLIER'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_SUPPLIER'),
                            Text::_('COM_REDSHOP_SUPPLIER')
                        );
                        ?>
                    </label>
                    <?php echo $this->lists['supplier']; ?>
                </div>

                <div class="form-group">
                    <label for="product_parent_id">
                        <?php echo Text::_('COM_REDSHOP_PARENT_PRODUCT'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PARENT_PRODUCT'),
                            Text::_('COM_REDSHOP_PARENT_PRODUCT')
                        );
                        ?>
                    </label>
                    <?php
                    echo HtmlHelper::_(
                        'redshopselect.search',
                        \Redshop\Product\Product::getProductById($this->detail->product_parent_id),
                        'product_parent_id',
                        array(
                            'select2.options'     => array(
                                'multiple'    => 'false',
                                'placeholder' => Text::_('COM_REDSHOP_PARENT_PRODUCT')
                            ),
                            'option.key'          => 'product_id',
                            'option.text'         => 'product_name',
                            'select2.ajaxOptions' => array('typeField' => ', parent:1, product_id:' . $this->detail->product_id)
                        )
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label for="product_special0">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_SPECIAL'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_SPECIAL'),
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_SPECIAL_LBL')
                        );
                        ?>
                    </label>
                    <?php echo $this->lists['product_special']; ?>
                </div>

                <div class="form-group">
                    <label for="expired0">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_EXPIRED'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRED'),
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRED_LBL')
                        );
                        ?>
                    </label>
                    <?php echo $this->lists['expired']; ?>
                </div>

                <div class="form-group">
                    <label for="not_for_sale0">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE'),
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE_LBL')
                        );
                        ?>
                    </label>
                    <?php echo $this->lists['not_for_sale']; ?>
                </div>

                <div class="form-group" id="not_for_sale_showprice">
                    <label for="not_for_sale_showprice0">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE_SHOWPRICE'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE_SHOWPRICE'),
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE_SHOWPRICE_LBL')
                        );
                        ?>
                    </label>
                    <?php echo $this->lists['not_for_sale_showprice']; ?>
                </div>

                <div class="form-group">
                    <label for="preorder">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_PREORDER'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREORDER'),
                            Text::_('COM_REDSHOP_PRODUCT_PREORDER')
                        );
                        ?>
                    </label>
                    <?php echo $this->lists['preorder']; ?>
                </div>

                <div class="form-group">
                    <label for="minimum_per_product_total">
                        <?php echo Text::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_MINIMUM_PER_PRODUCT_TOTAL'),
                            Text::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="number"
                           name="minimum_per_product_total"
                           id="minimum_per_product_total"
                           min="0"
                           oninput="validity.valid || (value='');"
                           size="10"
                           maxlength="10"
                           value="<?php echo $this->detail->minimum_per_product_total; ?>"/>
                </div>

                <?php if (Redshop::getConfig()->get('ALLOW_PRE_ORDER')) : ?>
                    <div class="form-group">
                        <label>
                            <?php echo Text::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL'); ?>
                            <?php
                            echo HtmlHelper::_('redshop.tooltip',
                                Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_AVAILABILITY_DATE'),
                                Text::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL')
                            );
                            ?>
                        </label>
                        <?php
                        $availability_date = "";

                        if ($this->detail->product_availability_date) {
                            $availability_date = date("d-m-Y", $this->detail->product_availability_date);
                        }

                        echo HtmlHelper::_(
                            'calendar',
                            $availability_date,
                            'product_availability_date',
                            'product_availability_date',
                            $calendarFormat,
                            array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19')
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="min_order_product_quantity">
                        <?php echo Text::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_MINIMUM_ORDER_PRODUCT_QUANTITY'),
                            Text::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="number"
                           name="min_order_product_quantity"
                           id="min_order_product_quantity"
                           min="0"
                           oninput="validity.valid || (value='');"
                           size="10"
                           maxlength="10"
                           value="<?php echo $this->detail->min_order_product_quantity; ?>"
                    />
                </div>

                <div class="form-group">
                    <label for="max_order_product_quantity">
                        <?php echo Text::_('COM_REDSHOP_MAXIMUM_ORDER_PRODUCT_QUANTITY_LBL'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_MAXIMUM_ORDER_PRODUCT_QUANTITY'),
                            Text::_('COM_REDSHOP_TOOLTIP_MAXIMUM_ORDER_PRODUCT_QUANTITY')
                        );
                        ?>
                    </label>
                    <input class="form-control"
                           type="number"
                           name="max_order_product_quantity"
                           id="max_order_product_quantity"
                           min="0"
                           oninput="validity.valid || (value='');"
                           size="10"
                           maxlength="10"
                           value="<?php echo @$this->detail->max_order_product_quantity; ?>"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
