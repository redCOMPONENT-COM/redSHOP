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
$fields = $form->getFieldset('test');
?>
<?php if (count($fields) > 0): ?>
    <?php foreach ($fields as $field): ?>
        <div class="form-group row-fluid ">
            <?php echo $field->label ?>
            <div class="col-md-10">
                <?php echo $field->input ?>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>
