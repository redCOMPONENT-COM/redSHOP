<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$fullImage   = '';
$fullMediaId = 0;
$backImage   = '';
$backMediaId = 0;

$media = RedshopEntityCategory::getInstance($this->item->id)->getMedia();

foreach ($media->getAll() as $mediaItem)
{
	if ($mediaItem->get('scope') == 'full')
	{
		$fullImage   = $mediaItem->get('media_name');
		$fullMediaId = $mediaItem->getId();
	}
    elseif ($mediaItem->get('scope') == 'back')
	{
		$backImage   = $mediaItem->get('media_name');
		$backMediaId = $mediaItem->getId();
	}
}
?>

<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_INFORMATION'); ?></h3>
            </div>
            <div class="box-body">
				<?php echo $this->form->renderField('name') ?>
				<?php echo $this->form->renderField('parent_id') ?>
				<?php echo $this->form->renderField('published') ?>
				<?php echo $this->form->renderField('products_per_page') ?>
				<?php echo $this->form->renderField('template') ?>
				<?php echo $this->form->renderField('more_template') ?>
				<?php echo $this->form->renderField('compare_template_id') ?>
				<?php echo $this->form->renderField('short_description') ?>
				<?php echo $this->form->renderField('description') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_IMAGES'); ?></h3>
            </div>
            <div class="box-body">
                <div class="">
					<?php
					echo RedshopHelperMediaImage::render(
						'category_full_image',
						'category',
						$this->item->id,
						'category',
						$fullImage,
						false,
						true,
						$fullMediaId
					);
					?>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_BACK_IMAGE'); ?></h3>
            </div>
            <div class="box-body">
                <div class="">
					<?php
					echo RedshopHelperMediaImage::render(
						'category_back_full_image',
						'category',
						$this->item->id,
						'category',
						$backImage,
						false,
						true,
						$backMediaId
					);
					?>
                </div>
            </div>
        </div>
        <div class="box box-primary form-horizontal">
            <div class="box-body">
				<?php echo $this->form->renderField('created_by') ?>
				<?php echo $this->form->renderField('created_date') ?>
				<?php echo $this->form->renderField('modified_by') ?>
				<?php echo $this->form->renderField('modified_date') ?>
            </div>
        </div>
    </div>
	<?php echo $this->form->getInput('checked_out'); ?>
	<?php echo $this->form->getInput('checked_out_time'); ?>
	<?php echo $this->form->getInput('level'); ?>
	<?php echo $this->form->getInput('lft'); ?>
	<?php echo $this->form->getInput('rgt'); ?>
</div>

