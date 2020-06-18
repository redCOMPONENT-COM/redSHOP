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
<div class="row-fluid row0" id="attribute_id_new" product-id="<?php echo $productId ?>" attribute-id="new" type="attribute" data="" style="display: none">
    <div class="td span1" data-content="attribute_id"><b>0</b>
    </div>
    <div class="td span2" data-content="attribute_name">
        New
    </div>
    <div class="td span1" data-content="attribute_required">
        <span class="icon-checkbox-unchecked"></span>
    </div>
    <div class="td span1" data-content="allow_multiple_selection">
        <span class="icon-checkbox-unchecked"></span>
    </div>
    <div class="td span1" data-content="attribute_published">
        <span class="icon-checkbox-unchecked"></span>
    </div>
    <div class="td span1" data-content="display_type">
    </div>
    <div class="td span1" data-content="hide">
        <span class="icon-checkbox-unchecked"></span>
    </div>
    <div class="td span1">
        <button class="btn btn-collapse" target-id="attribute_id_new" style="background-color: darkred; color: white;">
            0
        </button>
    </div>
    <div class="td span2">
        <div class="btn-edit-inrow">
            <span class="icon-expand btn-collapse zoom" target-id="attribute_id_new"></span>
            <span class="icon-edit btn-functionality zoom"></span>
            <span class="icon-minus btn-functionality zoom"></span>
        </div>
    </div>
</div>