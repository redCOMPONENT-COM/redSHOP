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
<div class="form-horizontal">
	<div class="row-fluid form-horizontal-desktop">
		<div class="span12">
			<fieldset class="details">
				<legend><?php echo JText::_('COM_REDSHOP_META_DATA_TAB'); ?></legend>

				<?php foreach ($this->form->getFieldset('seo') as $field) : ?>
					<?php if ($field->hidden) : ?>
						<?php echo $field->input;?>
					<?php endif; ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</fieldset>
		</div>
	</div>
</div>

