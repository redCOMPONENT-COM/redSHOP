<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;

extract($displayData);

$propertyId        = $property->property_id;
$totalSubProp      = (isset($property->subvalue)) ? count($property->subvalue) : 0;
$propertyPublished = ($property->property_published == 1) ? 'checked="checked"' : '';
$style             = ($totalSubProp) ? 'style="display:block;"' : 'style="display:none;"';

$propertyImage      = '';
$propertyImageThumb = '';

if (
    $property->property_image && File::exists(
        REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property->property_image
    )
) {
    $propertyImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image;

    $propertyImageThumb = RedshopHelperMedia::getImagePath(
        $property->property_image,
        '',
        'thumb',
        'product_attributes',
        100,
        0,
        Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
    );
}

$mainImage      = '';
$mainImageThumb = '';

if (
    $property->property_main_image && File::exists(
        REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $property->property_main_image
    )
) {
    $mainImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $property->property_main_image;

    $mainImageThumb = RedshopHelperMedia::getImagePath(
        $property->property_main_image,
        '',
        'thumb',
        'property',
        120,
        0,
        Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
    );
}

?>
<a href="#" class="showhidearrow">
    <?php echo Text::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>: <span class="propertyName">
        <?php echo $property->property_name; ?>
    </span>
    <img class="arrowimg" src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>arrow_d.png" alt="" />
</a>
<div class="attr_tbody form-inline divInspectFromHideShow" style="display: none">
    <input type="hidden" value="<?php echo $totalSubProp; ?>" name="<?php echo $propPref; ?>[count_subprop]"
        class="count_subprop" />
    <input type="hidden" value="<?php echo $keyProperty; ?>" name="<?php echo $propPref; ?>[key_prop]"
        class="key_prop" />
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>
                    <?php echo Text::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
                </label>
                <input type="text" class="form-control propertyInput" name="<?php echo $propPref; ?>[name]"
                    value="<?php echo $property->property_name; ?>" />
                <input type="hidden" name="<?php echo $propPref; ?>[property_id]" value="<?php echo $propertyId; ?>" />
                <input type="hidden" id="propertyImageName<?php echo $keyAttr . $keyProperty; ?>"
                    name="<?php echo $propPref; ?>[property_image]" value="<?php echo $property->property_image; ?>" />
                <input type="hidden" name="<?php echo $propPref; ?>[mainImage]"
                    id="propmainImage<?php echo $keyAttr . $keyProperty; ?>" value="" />
            </div>

        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>
                    <?php echo Text::_('COM_REDSHOP_PRICE'); ?>
                </label>

                <div class="priceInput">
                    <?php echo JHtml::_(
                        'select.genericlist',
                        $data->lists['prop_oprand'],
                        $propPref . '[oprand]',
                        'class="input-xmini"',
                        'value',
                        'text',
                        $property->oprand
                    ); ?>

                    <input type="text" class="form-control" value="<?php echo $property->property_price; ?>"
                        name="<?php echo $propPref; ?>[price]" />
                </div>

            </div>

        </div>

        <div class="col-sm-4">
            <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>discountmanagmenet16.png" />
            <?php echo
                RedshopLayoutHelper::render(
                    'modal.button',
                    [
                        'selector' => 'ModalAddPropertyPrice',
                        'params'   => [
                            'title'       => Text::_('COM_REDSHOP_ADD_PRICE_LBL'),
                            'footer'      => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                </button>',
                            'buttonText'  => Text::_('COM_REDSHOP_ADD_PRICE_LBL'),
                            'buttonClass' => 'btn btn-secondary btn-sm',
                            'url'         => Redshop\IO\Route::_(
                                'index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $propertyId .
                                '&cid=' . $productId . '&section=property'
                            ),
                            'modalWidth'  => '80',
                            'bodyHeight'  => '60',
                        ]
                    ]
                ); ?>
            <?php if (Redshop::getConfig()->get('USE_STOCKROOM')): ?>
                <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>stockroom16.png" />
                <?php echo
                    RedshopLayoutHelper::render(
                        'modal.button',
                        [
                            'selector' => 'ModalManagePropertyStockroom',
                            'params'   => [
                                'title'       => Text::_('COM_REDSHOP_ACTION_MANAGE_STOCKROOM'),
                                'footer'      => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                </button>',
                                'buttonText'  => Text::_('COM_REDSHOP_ACTION_MANAGE_STOCKROOM'),
                                'buttonClass' => 'btn btn-secondary btn-sm',
                                'url'         => Redshop\IO\Route::_(
                                    'index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $propertyId .
                                    '&cid=' . $productId . '&layout=productstockroom&property=property'
                                ),
                                'modalWidth'  => '80',
                                'bodyHeight'  => '60',
                            ]
                        ]
                    ); ?>
            <?php endif; ?>
        </div>

    </div>
    <div class="row">

        <div class="col-sm-4">
            <div class="form-group">
                <label>
                    <?php echo Text::_('COM_REDSHOP_PROPERTY_NUMBER'); ?>
                </label>
                <input type="text" class="vpnrequired form-control" value="<?php echo $property->property_number; ?>"
                    name="<?php echo $propPref; ?>[number]" />
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>
                    <?php echo Text::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>
                </label>
                <input type="text" class="form-control" name="<?php echo $propPref; ?>[extra_field]"
                    value="<?php echo $property->extra_field; ?>" />
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>
                    <?php echo Text::_('COM_REDSHOP_ORDERING'); ?>
                </label>
                <input type="number" class="form-control" name="<?php echo $propPref; ?>[order]"
                    value="<?php echo $property->ordering; ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">

            <label>
                <?php echo Text::_('COM_REDSHOP_PRODUCT_IMAGE'); ?>
            </label>
            <div class="row ">
                <div class="imageBlock">
                    <?php if ($mainImage) { ?>
                        <?php echo
                            RedshopLayoutHelper::render(
                                'modal.a',
                                [
                                    'selector' => 'ModalMainPropertyImage',
                                    'params'   => [
                                        'title'      => Text::_('COM_REDSHOP_UPLOAD'),
                                        'footer'     => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                </button>',
                                        'aContent'   => RedshopLayoutHelper::render(
                                            'joomla.html.image',
                                            ['src' => $mainImageThumb, 'alt' => 'Property main image']
                                        ),
                                        'aClass'     => '',
                                        'url'        => $mainImage,
                                        'modalWidth' => '50',
                                        'bodyHeight' => '70',
                                    ]
                                ]
                            ); ?>
                    <?php } ?>

                    <?php echo
                        RedshopLayoutHelper::render(
                            'modal.button',
                            [
                                'selector' => 'ModalUploadPropertyImage',
                                'params'   => [
                                    'title'       => Text::_('COM_REDSHOP_UPLOAD'),
                                    'footer'      => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                </button>',
                                    'buttonText'  => Text::_('COM_REDSHOP_UPLOAD'),
                                    'buttonClass' => 'btn btn-secondary btn-sm',
                                    'url'         => Redshop\IO\Route::_(
                                        'index.php?tmpl=component&option=com_redshop&view=media&section_id='
                                        . $propertyId . '&showbuttons=1&media_section=property'
                                    ),
                                    'modalWidth'  => '80',
                                    'bodyHeight'  => '60',
                                ]
                            ]
                        ); ?>
                </div>
            </div>
        </div>

        <div class="col-sm-4">

            <label>
                <?php echo Text::_('COM_REDSHOP_ATTRIBUTE_IMAGE'); ?>
            </label>

            <div class="row">
                <div class="imageBlock">
                    <?php
                    if ($propertyImage) {
                        ?>
                        <?php echo
                            RedshopLayoutHelper::render(
                                'modal.a',
                                [
                                    'selector' => 'ModalPropertyImage',
                                    'params'   => [
                                        'title'      => Text::_('COM_REDSHOP_UPLOAD'),
                                        'footer'     => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                </button>',
                                        'aContent'   => RedshopLayoutHelper::render(
                                            'joomla.html.image',
                                            ['src' => $propertyImageThumb, 'alt' => 'Property image thumb', 'id' => 'propertyImage' . $keyAttr . $keyProperty]
                                        ),
                                        'aClass'     => '',
                                        'url'        => $propertyImage,
                                        'modalWidth' => '50',
                                        'bodyHeight' => '70',
                                    ]
                                ]
                            ); ?>
                        <input id="deletePropertyMainImage_<?php echo $property->property_id; ?>_<?php
                           echo $keyAttr . $keyProperty; ?>" value="<?php echo Text::_('COM_REDSHOP_REMOVE_IMAGE'); ?>"
                            class="btn btn-secondary btn-sm" type="button" />
                        <?php
                    } else {
                        ?>
                        <img id="propertyImage<?php echo $keyAttr . $keyProperty; ?>" src="" style="display: none;" />
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="file" id="formFile"
                            name="attribute_<?php echo $keyAttr; ?>_property_<?php echo $keyProperty; ?>_image">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    /**
     * This is the place to inject property value data from a product type plugin.
     * Plugin group is already loaded in the view.html.php and you can use $data->dispatcher.
     * This is used for integration with other redSHOP extensions which can extend product type.
     */

    if ($productId && !empty($property->property_id)) {
        $property->product = $data->detail;
        $property->k       = $keyAttr;
        $property->g       = $keyProperty;

        $data->dispatcher->trigger('productTypeAttributeValue', array($property));
    }
    ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label name="<?php echo $propPref; ?>[preselected]">
                    <?php echo Text::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
                </label>
                <input class="form-check-input" type="checkbox" value="1" name="<?php echo $propPref; ?>[default_sel]"
                    <?php echo ($property->setdefault_selected == 1) ? 'checked="checked"' : ''; ?> />
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>
                    <?php echo Text::_('COM_REDSHOP_PUBLISHED'); ?>
                </label>
                <input class="form-check-input" type="checkbox" value="1" <?php echo $propertyPublished; ?>
                    name="<?php echo $propPref; ?>[published]" />
            </div>
        </div>
        <div class="col-sm-8">
            <input value="<?php echo Text::_('COM_REDSHOP_DELETE'); ?>" id="deleteProperty_<?php echo $propertyId; ?>_<?php
                  echo $attributeId; ?>" class="btn btn-danger delete_property" type="button" />
            <a class="btn btn-success add_subproperty" href="#">
                <?php echo "+ " . Text::_(
                    'COM_REDSHOP_NEW_SUB_PROPERTY'
                ); ?>
            </a>

        </div>
    </div>


    <div class="attribute_parameter_tr divFromHideShow ">
        <div class="row showsubproperty" style="<?php echo ($totalSubProp == 0) ? 'display:none;' : ''; ?>">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>
                        <?php echo Text::_('COM_REDSHOP_PROPERTY_NAME'); ?>
                    </label>
                    <input class="" type="text" name="<?php echo $propPref; ?>[subproperty][title]" value="<?php echo (isset($property->subvalue) && count(
                           $property->subvalue
                       ) > 0) ? $property->subvalue[0]->subattribute_color_title : ''; ?>">
                </div>

                <div class="form-check">
                    <label>
                        <?php echo Text::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?>
                    </label>
                    <input class="form-check-input" type="checkbox" value="1"
                        name="<?php echo $propPref; ?>[req_sub_att]" <?php echo ($property->setrequire_selected == 1) ? 'checked="checked"' : ''; ?> />
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>
                        <?php echo Text::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
                    </label>
                    <select name="<?php echo $propPref; ?>[setdisplay_type]" class="form-control">
                        <option value="dropdown" <?php echo ($property->setdisplay_type == 'dropdown') ? 'selected' : ''; ?>>
                            <?php echo Text::_('COM_REDSHOP_DROPDOWN_LIST'); ?>
                        </option>
                        <option value="radio" <?php echo ($property->setdisplay_type == 'radio') ? 'selected' : ''; ?>>
                            <?php echo Text::_('COM_REDSHOP_RADIOBOX'); ?>
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <?php echo Text::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?>
                    </label>
                    <input class="form-check-input" type="checkbox" value="1"
                        name="<?php echo $propPref; ?>[multi_sub_att]" <?php echo ($property->setmulti_selected == 1) ? 'checked' : ''; ?>>
                </div>


            </div>

        </div>

        <div class="sub_attribute_table">

            <?php
            if ($totalSubProp != 0) { ?>
                <?php
                foreach ($property->subvalue as $keySubProp => $subProperty) {
                    $subPropPref = $propPref . '[subproperty][' . $keySubProp . ']';

                    echo RedshopLayoutHelper::render(
                        'product_detail.product_subproperty',
                        array(
                            'subPropPref' => $subPropPref,
                            'subProperty' => $subProperty,
                            'keyAttr'     => $keyAttr,
                            'keyProperty' => $keyProperty,
                            'keySubProp'  => $keySubProp,
                            'property'    => $property,
                            'productId'   => $productId,
                            'data'        => $data
                        )
                    );
                }

                ?>
            <?php }
            ?>

        </div>


    </div>

</div>