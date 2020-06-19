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

<div class="row-fluid row<?php echo (int) $pr; ?>" <?php if (empty($p)) : ?> id="property_id_new_<?php echo $a['attribute_id'] ?>" style="display: none;" <?php else : ?> id="property_id_<?php echo $p->property_id ?>" <?php endif ?> type="property" data-id="<?php echo $p->property_id ?: '' ?>" data="<?php echo base64_encode(json_encode($p)); ?>" dependency="<?php echo $p->dependency ?: '' ?>">
    <div class="td span1" style="color: darkred" data-content="property_id">
        <b><?php echo $p->property_id ?: '' ?></b>
    </div>
    <div class="td span1" data-content="property_image">
        <?php if (isset($p->property_image) && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $p->property_image)) : ?>
            <?php
            $thumbUrl = RedshopHelperMedia::getImagePath(
                $p->property_image,
                '',
                'thumb',
                'product_attributes',
                50,
                0,
                Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
            );
            ?>
            <img src="<?php echo $thumbUrl; ?>" />
        <?php else : ?>
            <img src="" style="display: none;" />
        <?php endif ?>
    </div>
    <div class="td span1">
        <a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" title="" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&section_id='
                                                                                                                . $p->property_id . '&showbuttons=1&media_section=property'); ?>">
            <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>media16.png" alt="" />
        </a>
        <a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $p->property_id . '&cid=' . $productId . '&section=property'); ?>">
            <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>discountmanagmenet16.png" />
        </a>
        <?php if (Redshop::getConfig()->get('USE_STOCKROOM')) : ?>
            <a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $p->property_id . '&cid=' . $productId . '&layout=productstockroom&property=property'); ?>">
                <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>stockroom16.png" />
            </a>
        <?php endif; ?>
    </div>
    <div class="td span1" data-content="property_name">
        <div><?php echo $p->property_name ?></div>
        <?php if ($p->property_number) : ?>
            <div style="font-weight: lighter; text-decoration: darkred;"><?php echo \JText::_('COM_REDSHOP_PROPERTY_NUMBER') ?>: <?php echo $p->property_number; ?></div>
        <?php endif ?>
    </div>
    <div class="td span1" data-content="setdefault_selected">
        <?php $checked = (isset($p->setdefault_selected) && ($p->setdefault_selected == 1)) ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span1" data-content="oprand">
        <?php echo $p->oprand ?? "<i>n/a</i>" ?>
    </div>
    <div class="td span1" data-content="property_price">
        <?php echo $p->property_price ?? '' ?>
    </div>
    <div class="td span1" data-content="setrequire_selected">
        <?php $checked = isset($p->setrequire_selected) && ($p->setrequire_selected == 1) ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span1" data-content="property_published">
        <?php $checked = isset($p->property_published) && $p->property_published == 1 ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span1" data-content="setdisplay_type">
        <?php echo $p->setdisplay_type ?? "<i>n/a</i>" ?>
    </div>
    <div class="td span1" data-content="hide">
        <?php $checked = isset($p->hide) && $p->hide == 1 ? 'checked' : 'unchecked' ?>
        <span class="icon-checkbox-<?php echo $checked ?>"></span>
    </div>
    <div class="td span1">
        <button class="btn btn-collapse" target-id="property_id_<?php echo $p->property_id ?>" style="background-color: darkgreen; color: white;">
            <?php echo isset($p->subvalue) && is_array($p->subvalue)? count($p->subvalue): 0; ?>
        </button>
    </div>
    <div class="td span2">
        <div class="btn-edit-inrow">
            <span class="icon-expand btn-collapse zoom" target-id="property_id_<?php echo $p->property_id ?? '' ?>"></span>
            <span class="icon-edit btn-functionality zoom"></span>
            <span class="icon-minus btn-functionality zoom"></span>
        </div>
    </div>
</div>