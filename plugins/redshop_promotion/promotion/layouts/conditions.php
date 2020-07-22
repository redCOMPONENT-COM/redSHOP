<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
extract($displayData);
$fieldSet = 'conditions';
$fields = $form->getFieldset($fieldSet);
?>
<?php if (count($fields) > 0): ?>
    <?php foreach ($fields as $field): ?>
        <?php if (!empty($post[$field->getAttribute('name')])): ?>
            <?php $value = $post[$field->getAttribute('name')];?>
            <?php $field->setValue($value, true); ?>
        <?php endif ?>
        <?php if (($field->name == "promotion_type") && ($promotionId > 0)): ?>
            <?php $field->disabled = true ?>
        <?php endif ?>
        <?php echo $field->renderField() ?>
    <?php endforeach ?>
<?php endif ?>

<?php $fieldSet .= !empty($post['promotion_type']) ? '_' . $post['promotion_type']: '_amount_product' ?>
<?php $fields = $form->getFieldset($fieldSet); ?>
<?php foreach ($fields as $field): ?>
    <?php if (!empty($post[$field->getAttribute('name')])): ?>
        <?php $value = $post[$field->getAttribute('name')];?>
        <?php $field->setValue($value, true); ?>
    <?php endif ?>
    <?php echo $field->renderField() ?>
<?php endforeach ?>

<?php $fieldSet = 'datetime' ?>
<?php $fields = $form->getFieldset($fieldSet); ?>
<?php foreach ($fields as $field): ?>
    <?php if (!empty($post[$field->getAttribute('name')])): ?>
        <?php $value = $post[$field->getAttribute('name')];?>
        <?php $field->setValue($value, true); ?>
    <?php endif ?>
    <?php echo $field->renderField() ?>
<?php endforeach ?>
