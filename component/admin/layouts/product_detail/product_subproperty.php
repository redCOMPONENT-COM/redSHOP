<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('a.joom-box');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

extract($displayData);
?>

<div class="row-fluid row<?php echo (int) $spr; ?>" <?php if (empty($sp)) : ?> id="subproperty_id_new_<?php echo $p->property_id ?>" style="display: none" <?php else : ?> id="subproperty_id_<?php echo $sp->subattribute_color_id ?>" <?php endif ?> data="<?php echo base64_encode(json_encode($sp)); ?>" type="subproperty" data-id="<?php echo $sp->subattribute_color_id ?>" dependency="<?php echo $sp->dependency ?>">
    <div class="td span1" style="color: darkgreen" data-content="subattribute_color_id"><i><?php echo $sp->subattribute_color_id ?></i></div>
    <div class="td span1" data-content="subattribute_color_image">
        <?php
        if (isset($sp->subattribute_color_image) && $sp->subattribute_color_image != '' && JFile::exists(
            REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $sp->subattribute_color_image
        )) {
            $thumbUrl = RedshopHelperMedia::getImagePath(
                $sp->subattribute_color_image,
                '',
                'thumb',
                'subcolor',
                50,
                0,
                Redshop::getConfig()->get(
                    'USE_IMAGE_SIZE_SWAPPING'
                )
            );
        ?>
            <img src="<?php echo $thumbUrl; ?>" />
        <?php
        } else {
        ?>
            <img src="" style="display: none;" />
        <?php
        }
        ?>
    </div>
    <div class="td span1">
        <a class="joom-box btn btn-small" href="<?php echo JRoute::_(
                                                    'index.php?tmpl=component&option=com_redshop&view=media&section_id='
                                                        . $sp->subattribute_color_id . '&showbuttons=1&media_section=subproperty'
                                                ); ?>" rel="{handler: 'iframe', size: {x: 950, y: 500}}" title="">
            <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>media16.png" alt="" />
        </a>
        <a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" title="" href="<?php echo JRoute::_(
                                                                                                                'index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $sp->subattribute_color_id . '&cid=' . $productId . '&section=subproperty'
                                                                                                            ); ?>">
            <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>discountmanagmenet16.png" alt="" />
        </a>
        <?php if (Redshop::getConfig()->get(
            'USE_STOCKROOM'
        )) : ?>
            <a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" href="<?php echo JRoute::_(
                                                                                                            'index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $sp->subattribute_color_id . '&cid=' . $productId
                                                                                                        ); ?>&layout=productstockroom&property=subproperty">
                <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>stockroom16.png" />
            </a>
        <?php endif; ?>
    </div>
    <div class="td span2" data-content="subattribute_color_name">
        <?php echo $sp->subattribute_color_name ?? '' ?>
        <?php if (isset($sp->subattribute_color_number) && $sp->subattribute_color_number != '') : ?>
            <div style="font-weight: lighter; text-decoration: darkgreen;">
                <?php echo \JText::_('COM_REDSHOP_PROPERTY_NUMBER') ?>:<?php echo $sp->subattribute_color_number ?>
            </div>
        <?php endif ?>
    </div>
    <div class="td span1" data-content="oprand"><?php echo $sp->oprand ?? "<i>n/a</i>" ?></div>
    <div class="td span1" data-content="subattribute_color_price"><?php echo $sp->subattribute_color_price ?? '' ?></div>
    <div class="td span1" data-content="setdefault_selected">
        <?php $checked = isset($sp->setdefault_selected) && $sp->setdefault_selected == 1 ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span1" data-content="subattribute_published">
        <?php $checked = isset($sp->subattribute_published) && $sp->subattribute_published == 1 ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span1" data-content="hide">
        <?php $checked = isset($sp->hide) && $sp->hide == 1 ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span2">
        <div class="btn-edit-inrow">
            <span class="icon-edit btn-functionality zoom" attribute-id="<?php echo $a['attribute_id'] ?>" property-id="<?php echo $p->property_id ?>"></span>
            <span class="icon-minus btn-functionality zoom"></span>
        </div>
    </div>
</div>