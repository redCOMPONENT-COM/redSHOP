<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_META_DATA_TAB'); ?></h3>
            </div>
            <div class="box-body">
				<?php foreach ($this->form->getFieldset('seo') as $field) : ?>
					<?php if ($field->hidden) : ?>
						<?php echo $field->input ?>
					<?php endif; ?>
                    <div class="form-group row-fluid">
						<?php echo $this->form->renderField($field->fieldname) ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
