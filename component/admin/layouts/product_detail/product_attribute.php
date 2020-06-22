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

$data = $displayData['this'];

$productId = 0;

if (isset($data->detail->product_id)) {
    $productId = $data->detail->product_id;
} else {
    $productId = \JFactory::getApplication()->input->get('cid')[0];
}

$view = \JFactory::getApplication()->input->get('view');

$attributes = [];

if (isset($data->lists['attributes'])) {
    $attributes = $data->lists['attributes'];
}

$divAttribute = [];

?>
<!-- Modal area -->
<?php echo RedshopLayoutHelper::render('product_detail.modal', ['productId' => $productId]) ?>
<!-- END modal area -->

<div class="adminlist">
    <!-- Attribute Head bar -->
    <?php echo RedshopLayoutHelper::render('product_detail.bar_attribute', []) ?>

    <!-- Attribute sample -->
    <?php echo RedshopLayoutHelper::render('product_detail.sample', ['productId' => $productId]) ?>

    <!-- List out attributes -->
    <?php $rowLevel = true; ?>
    <?php if (count($attributes) > 0): ?>
    <?php foreach ($attributes as $a) : ?>
        <?php $divAttribute[$a['attribute_id']] = ['name' => $a['attribute_name']]; ?>
        <?php $rowLevel = !$rowLevel; ?>

        <?php echo RedshopLayoutHelper::render('product_detail.row_attribute', ['rowLevel' => $rowLevel, 'a' => $a, 'productId' => $productId]) ?>
        <!-- Property Sample Bar -->
        <?php echo RedshopLayoutHelper::render('product_detail.bar_property', ['a' => $a, 'p' =>  ($p ?? null), 'dataId' => 'new-property-bar']) ?>

        <?php echo RedshopLayoutHelper::render('product_detail.product_property', []) ?>

        <!-- List out Properties -->
        <div class="div_properties" data-type="properties" child-of="attribute_id_<?php echo $a['attribute_id'] ?>">
            <?php $properties = $a['property']; ?>

            <?php echo RedshopLayoutHelper::render('product_detail.bar_property', ['a' => $a]); ?>

            <!-- property bar -->
            <?php echo RedshopLayoutHelper::render('product_detail.product_property', ['a' => $a]) ?>

            <!-- end property sample -->
            <?php $pr = true; ?>
            <?php foreach ($properties as $p) : ?>
                <?php $divAttribute[$a['attribute_id']][$p->property_id] = ['name' => $p->property_name]; ?>
                <?php $pr = !$pr; ?>
                <?php echo RedshopLayoutHelper::render('product_detail.product_property', ['a' => $a, 'p' => $p, 'pr' => $pr, 'divAttribute' => &$divAttribute, 'productId' => $productId]) ?>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
    <?php endif ?>
</div>

<!-- init Data for JS -->
<?php echo RedshopLayoutHelper::render('product_detail.init_data', ['divAttribute' => $divAttribute, 'productId' => $productId, 'view' => $view]) ?>

<!-- loader for waiting AJAX complete -->
<?php echo RedshopLayoutHelper::render('product_detail.loader', []) ?>