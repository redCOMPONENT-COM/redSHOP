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
$fields = $form->getFieldset('awards');
?>
<?php if (count($fields) > 0): ?>
    <?php foreach ($fields as $field): ?>
        <?php if (isset($post[$field->getAttribute('name')])): ?>
            <?php $value = $post[$field->getAttribute('name')];?>
            <?php $field->setValue($value, true); ?>
        <?php endif ?>
        <?php echo $field->renderField() ?>
    <?php endforeach ?>
<?php endif ?>
