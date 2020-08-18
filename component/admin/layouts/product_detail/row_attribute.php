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

<div class="row-fluid row<?php echo (int) $rowLevel ?>" id="attribute_id_<?php echo $a['attribute_id'] ?>" product-id="<?php echo $productId ?>" data-id="<?php echo $a['attribute_id'] ?>" type="attribute" data="<?php echo base64_encode(json_encode($a)); ?>" dependency="<?php echo $a['dependency'] ?? '' ?>">
    <div class="td span1" data-content="attribute_id"><b><?php echo $a['attribute_id'] ?></b>
    </div>
    <div class="td span2" data-content="attribute_name">
        <?php echo $a['attribute_name'] ?>
    </div>
    <div class="td span1" data-content="attribute_required">
        <?php $color = $a['attribute_required'] == 1 ? 'checked' : 'unchecked'; ?>
        <span class="icon-checkbox-<?php echo $color ?>"></span>
    </div>
    <div class="td span1" data-content="allow_multiple_selection">
        <?php $color = $a['allow_multiple_selection'] == 1 ? 'checked' : 'unchecked'; ?>
        <span class="icon-checkbox-<?php echo $color ?>"></span>
    </div>
    <div class="td span1" data-content="attribute_published">
        <?php $color = $a['attribute_published'] == 1 ? 'checked' : 'unchecked'; ?>
        <span class="icon-checkbox-<?php echo $color ?>"></span>
    </div>
    <div class="td span1" data-content="display_type">
        <?php echo $a['display_type'] ?>
    </div>
    <div class="td span1" data-content="hide">
        <?php $color = (isset($a['hide']) && ($a['hide'] == 1)) ? 'checked' : 'unchecked'; ?>
        <span class="icon-checkbox-<?php echo $color ?>"></span>
    </div>
    <div class="td span1">
        <button class="btn btn-collapse" target-id="attribute_id_<?php echo $a['attribute_id'] ?? '' ?>" style="background-color: darkred; color: white;">
            <?php echo count($a['property']); ?>
        </button>
    </div>
    <div class="td span2">
        <div class="btn-edit-inrow">
            <span class="icon-expand btn-collapse zoom" target-id="attribute_id_<?php echo $a['attribute_id'] ?>"></span>
            <span class="icon-edit btn-functionality zoom"></span>
            <span class="icon-minus btn-functionality zoom"></span>
        </div>
    </div>
</div>