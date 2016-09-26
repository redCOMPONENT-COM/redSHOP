<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.modal', 'a.modal-thumb');

$producthelper = productHelper::getInstance();

if ($this->item->giftcard_id)
{
	$this->form->setValue('giftcard_price', null, $producthelper->redpriceDecimal($this->item->giftcard_price));
	$this->form->setValue('giftcard_value', null, $producthelper->redpriceDecimal($this->item->giftcard_value));
}

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "giftcard.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
		{
			Joomla.submitform(task);
		}
	};
');
?>
<form
	action="index.php?option=com_redshop&view=giftcard&task=giftcard.edit&giftcard_id=<?php echo $this->item->giftcard_id; ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data"
>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="giftcard_id" value="<?php echo $this->item->giftcard_id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="form-horizontal">
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<fieldset class="details">
					<legend><?php echo JText::_('COM_REDSHOP_DETAIL'); ?></legend>

					<?php foreach ($this->form->getFieldset('details') as $field) : ?>
						<?php if ($field->hidden) : ?>
							<?php echo $field->input;?>
						<?php endif; ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>

							<!-- Rendering Images in modal -->
							<?php if ('giftcard_bgimage' == $field->fieldname || 'giftcard_image' == $field->fieldname) : ?>
								<?php
									$giftCardImagePath = RedShopHelperImages::getImagePath(
										$field->value,
										'',
										'thumb',
										'giftcard',
										100,
										100,
										Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
									);
								?>
								<a class="modal-thumb"
								   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'giftcard/' . $field->value; ?>">
									<img src="<?php echo $giftCardImagePath;?>" class="img-polaroid">
								</a>
							<?php endif; ?>

							</div>
						</div>
					<?php endforeach; ?>
				</fieldset>
				<?php if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION')) : ?>
					<fieldset class="economic">
						<legend><?php echo JText::_('COM_REDSHOP_ECONOMIC'); ?></legend>
						<?php foreach ($this->form->getFieldset('economic') as $field) : ?>
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
				<?php endif; ?>
			</div>
			<div class="span6">
				<fieldset class="description">
					<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>
					<?php foreach ($this->form->getFieldset('description') as $field) : ?>
							<?php echo $field->input; ?>
				<?php endforeach; ?>
				</fieldset>
			</div>
		</div>
	</div>
</form>
