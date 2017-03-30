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
						<?php echo $this->form->getLabel('name'); ?>
					</label>
					<?php echo $this->form->getInput('name'); ?>
				</div>

				<div class="form-group">
					<label for="category_parent_id">
						<?php echo $this->form->getLabel('parent_id'); ?>
					</label>
					<?php echo $this->form->getInput('parent_id'); ?>
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
						<?php echo $this->form->getLabel('template'); ?>
					</label>
					<?php echo $this->form->getInput('template'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('more_template'); ?>
					</label>
					<?php echo $this->form->getInput('more_template'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('compare_template_id'); ?>
					</label>
					<?php echo $this->form->getInput('compare_template_id'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('short_description'); ?>
					</label>
					<?php echo $this->form->getInput('short_description'); ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo $this->form->getLabel('description'); ?>
					</label>
					<?php echo $this->form->getInput('description'); ?>
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
						$section_id    = $this->item->id;
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
						$section_id    = $this->item->id;
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
		<div class="form-group">
			<label>
				<?php echo $this->form->getLabel('created_by'); ?>
			</label>
			<?php echo $this->form->getInput('created_by'); ?>
		</div>
		<div class="form-group">
			<label>
				<?php echo $this->form->getLabel('created_date'); ?>
			</label>
			<?php echo $this->form->getInput('created_date'); ?>
		</div>
		<div class="form-group">
			<label>
				<?php echo $this->form->getLabel('modified_by'); ?>
			</label>
			<?php echo $this->form->getInput('modified_by'); ?>
		</div>
		<div class="form-group">
			<label>
				<?php echo $this->form->getLabel('modified_date'); ?>
			</label>
			<?php echo $this->form->getInput('modified_date'); ?>
		</div>
	</div>
	<?php echo $this->form->getInput('checked_out'); ?>
	<?php echo $this->form->getInput('checked_out_time'); ?>
	<?php echo $this->form->getInput('level'); ?>
	<?php echo $this->form->getInput('lft'); ?>
	<?php echo $this->form->getInput('rgt'); ?>
</div>

