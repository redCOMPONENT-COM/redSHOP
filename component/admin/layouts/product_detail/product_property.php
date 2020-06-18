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

if (count($displayData['divAttribute']) > 0) {
    $d = &$displayData['divAttribute'];
}

extract($displayData);

if (count($d) > 0) {
    $divAttribute = &$d;
}

?>

<?php echo RedshopLayoutHelper::render('product_detail.row_property', ['a' => $a, 'p' => $p, 'pr' => $pr]); ?>

<!-- Sub-property Bar -->
<?php echo RedshopLayoutHelper::render('product_detail.bar_subproperty', ['a' => $a, 'p' =>  $p, 'dataId' => 'new-subproperty-bar']) ?>

<!-- Sub Properties -->
<div class="div_subproperties" style="display:none;" child-of="property_id_<?php echo $p->property_id ?>">
    <?php $subProperties = $p->subvalue; ?>
    <?php $spr = true; ?>

    <?php echo RedshopLayoutHelper::render('product_detail.bar_subproperty',  [
        'a' => $a,
        'p' => $p
    ]) ?>

    <!-- subproperty sample -->
    <?php echo RedshopLayoutHelper::render('product_detail.product_subproperty', [
        'a' => $a,
        'p' => $p
    ]) ?>
    <!-- end subproperty sample -->

    <?php foreach ($subProperties as $sp) : ?>
        <?php
        $divAttribute[$a['attribute_id']][$p->property_id][$sp->subattribute_color_id] = [
            'name' => $sp->subattribute_color_name
        ];
        ?>
        <?php $spr = !$spr; ?>
        <?php echo RedshopLayoutHelper::render('product_detail.product_subproperty', [
            'a' => $a,
            'p' => $p,
            'sp' => $sp,
            'spr' => $spr
        ]) ?>
    <?php endforeach; ?>
</div>