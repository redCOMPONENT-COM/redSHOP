<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');

?>
<form
	action="index.php?option=com_redshop&view=attribute&task=attribute.edit&attribute_id=<?php echo $this->item->attribute_id; ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
>
	<div class="form-horizontal">
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="details">
					<legend><?php echo JText::_('COM_REDSHOP_DETAIL'); ?></legend>
					<?php foreach ($this->form->getFieldset('details') as $field): ?>
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
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="attribute_id" value="<?php echo $this->item->attribute_id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
