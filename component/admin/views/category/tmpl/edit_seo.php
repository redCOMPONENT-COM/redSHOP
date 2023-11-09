<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo Text::_('COM_REDSHOP_META_DATA_TAB'); ?>
                </h3>
            </div>
            <div class="box-body">
                <?php foreach ($this->form->getFieldset('seo') as $field): ?>
                    <?php if ($field->hidden): ?>
                        <?php echo $field->input ?>
                    <?php endif; ?>
                    <?php echo $this->form->renderField($field->fieldname) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>