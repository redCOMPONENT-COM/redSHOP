<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.modal', 'a.joom-box');

if ($this->item->giftcard_id)
{
	$this->form->setValue('giftcard_price', null, RedshopHelperProduct::redpriceDecimal($this->item->giftcard_price));
	$this->form->setValue('giftcard_value', null, RedshopHelperProduct::redpriceDecimal($this->item->giftcard_value));
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
	class="adminform form-validate form-horizontal"
	enctype="multipart/form-data">

	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAIL') ?></h3>
				</div>
				<div class="box-body">
					<?php echo $this->form->renderField('giftcard_name') ?>
					<?php echo $this->form->renderField('customer_amount') ?>
					<?php echo $this->form->renderField('giftcard_price') ?>
					<?php echo $this->form->renderField('giftcard_value') ?>
					<?php echo $this->form->renderField('giftcard_validity') ?>
					<?php echo $this->form->renderField('free_shipping') ?>
					<?php echo $this->form->renderField('published') ?>
				</div>
			</div>
			<?php if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION')) : ?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ECONOMIC'); ?></h3>
				</div>
				<div class="panel-body">
					<?php echo $this->form->renderField('accountgroup_id') ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_OTHER_INFORMATION') ?></h3>
				</div>
				<div class="box-body">
					<?php echo $this->form->renderField('giftcard_bgimage_file') ?>
					<div class="form-group">
						<div class="col-md-2">
						</div>
						<div class="col-md-10">
							<?php
							$value = $this->item->giftcard_bgimage;
							$giftCardImagePath = RedshopHelperMedia::getImagePath(
								$value,
								'',
								'thumb',
								'giftcard',
								100,
								100,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							?>
							<?php if ($value) : ?>
								<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'giftcard/' . $value; ?>">
							<?php endif; ?>
									<img src="<?php echo $giftCardImagePath;?>" class="img-polaroid">
							<?php if ($value) : ?>
								</a>
							<?php endif; ?>
							<?php echo $this->form->getInput('giftcard_bgimage') ?>
						</div>
					</div>
					<?php echo $this->form->renderField('giftcard_image_file') ?>
					<div class="form-group">
						<div class="col-md-2">
						</div>
						<div class="col-md-10">
							<?php
							$value = $this->item->giftcard_image;
							$giftCardImagePath = RedshopHelperMedia::getImagePath(
								$value,
								'',
								'thumb',
								'giftcard',
								100,
								100,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							?>
							<?php if ($value) : ?>
								<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'giftcard/' . $value; ?>">
							<?php endif; ?>
									<img src="<?php echo $giftCardImagePath;?>" class="img-polaroid">
							<?php if ($value) : ?>
								</a>
							<?php endif; ?>
							<?php echo $this->form->getInput('giftcard_image') ?>
						</div>
					</div>
					<?php echo $this->form->renderField('giftcard_desc') ?>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('giftcard_id') ?>
	<?php echo JHtml::_('form.token'); ?>
</form>
