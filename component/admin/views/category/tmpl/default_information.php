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
	<div class="col-sm-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_INFORMATION'); ?></h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="category_name">
						<?php echo $this->form->getLabel('category_name'); ?>
					</label>
					<?php echo $this->form->getInput('category_name'); ?>
				</div>

				<div class="form-group">
					<label for="category_parent_id">
						<?php echo $this->form->getLabel('category_parent_id'); ?>
					</label>
					<?php echo $this->form->getInput('category_parent_id'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('published'); ?>
					</label>
					<?php echo $this->form->getInput('published'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('products_per_page'); ?>
					</label>
					<?php echo $this->form->getInput('products_per_page'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('category_template'); ?>
					</label>
					<?php echo $this->form->getInput('category_template'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('category_more_template'); ?>
					</label>
					<?php echo $this->form->getInput('category_more_template'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('compare_template_id'); ?>
					</label>
					<?php echo $this->form->getInput('compare_template_id'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('category_short_description'); ?>
					</label>
					<?php echo $this->form->getInput('category_short_description'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('category_description'); ?>
					</label>
					<?php echo $this->form->getInput('category_description'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_IMAGES'); ?></h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<?php
						$section_id    = $this->item->category_id;
						$media_section = 'category';
						echo RedshopHelperMediaImage::render(
							'category_full_image',
							'category',
							$section_id,
							$media_section,
							$this->item->category_full_image,
							false
						);
					?>
					<?php echo $this->form->getInput('category_image'); ?>
				</div>

			</div>
		</div>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_BACK_IMAGE'); ?></h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<?php
						$section_id    = $this->item->category_id;
						$media_section = 'category';
						echo RedshopHelperMediaImage::render(
							'category_back_full_image',
							'category',
							$section_id,
							$media_section,
							$this->item->category_back_full_image,
							false
						);
					?>
				</div>
			</div>
		</div>
	</div>
</div>

