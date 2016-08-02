<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();
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
						<?php echo JText::_('COM_REDSHOP_CATEGORY_NAME'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_NAME'), JText::_('COM_REDSHOP_CATEGORY_NAME'), 'tooltip.png', '', '', false); ?>
					</label>
					<input class="text_area" type="text" name="category_name" id="category_name" size="32" maxlength="250" value="<?php echo $this->detail->category_name; ?>"/>
				</div>

				<div class="form-group">
					<label for="category_parent_id">
						<?php echo JText::_('COM_REDSHOP_CATEGORY_PARENT'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PARENT'), JText::_('COM_REDSHOP_CATEGORY_PARENT'), 'tooltip.png', '', '', false); ?>
					</label>
					<?php echo $this->lists['categories']; ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
					</label>
					<?php echo $this->lists['published']; ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_SHOW_PRODUCT_PER_PAGE'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_PER_PAGE'), JText::_('COM_REDSHOP_CATEGORY_NAME'), 'tooltip.png', '', '', false); ?>
					</label>
					<input class="text_area" type="text" name="products_per_page" id="products_per_page" size="32"
				           maxlength="250"
				           value="<?php echo $this->detail->products_per_page; ?>"/>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_CATEGORY_TEMPLATE'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TEMPLATE'), JText::_('COM_REDSHOP_CATEGORY_TEMPLATE'), 'tooltip.png', '', '', false); ?>
					</label>
					<?php echo $this->lists['category_template']; ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_CATEGORY_MORE_TEMPLATE'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_MORE_TEMPLATE'), JText::_('COM_REDSHOP_CATEGORY_MORE_TEMPLATE'), 'tooltip.png', '', '', false); ?>
					</label>
					<?php echo $this->lists['category_more_template']; ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY_LABEL'), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY'), 'tooltip.png', '', '', false); ?>
					</label>
					<?php echo $this->lists['compare_template_id']; ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?>
					</label>
					<?php echo $editor->display("category_short_description", $this->detail->category_short_description, '$widthPx', '$heightPx', '100', '20', false);    ?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?>
					</label>
					<?php echo $editor->display("category_description", $this->detail->category_description, '$widthPx', '$heightPx', '100', '20', false);    ?>
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
					<input type="file" name="category_full_image" id="category_full_image" size="30">

					<?php
					$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs');
					?>
					<a class="modal btn btn-primary inline" title="Image" href="<?php echo $ilink; ?>" rel="{handler: 'iframe', size: {x: 900, y: 500}}">
						<?php echo JText::_('COM_REDSHOP_SELECT_IMAGE'); ?>
					</a>

					<input type="hidden" name="category_image" id="category_image"/>
				</div>
				<div class="form-group">

					<?php
					if ($this->detail->category_full_image != "")
					{
						if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $this->detail->category_full_image))
						{
							$thumbUrl = RedShopHelperImages::getImagePath(
											$this->detail->category_full_image,
											'',
											'thumb',
											'category',
											THUMB_WIDTH,
											THUMB_HEIGHT,
											USE_IMAGE_SIZE_SWAPPING
										);
							?>
							<a class="modal"
								href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>category/<?php echo $this->detail->category_full_image ?>"
								title="" rel="{handler: 'image', size: {}}">
								<img src="<?php echo $thumbUrl ?>">
							</a>

							<label class="checkbox"><input type="checkbox" name="image_delete" >
								<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE'); ?>
							</label>
						<?php
						}
					} ?>
				</div>
			</div>
		</div>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_BACK_IMAGE'); ?></h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<input type="file" name="category_back_full_image" id="category_back_full_image" size="30">
				</div>

				<?php
				if ($this->detail->category_back_full_image != "")
				{
					if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $this->detail->category_back_full_image))
					{
						$thumbUrl = RedShopHelperImages::getImagePath(
										$this->detail->category_back_full_image,
										'',
										'thumb',
										'category',
										THUMB_WIDTH,
										THUMB_HEIGHT,
										USE_IMAGE_SIZE_SWAPPING
									);
						?>
				<div class="form-group">
					<a class="modal"
						href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>category/<?php echo $this->detail->category_back_full_image ?>"
						title="" rel="{handler: 'image', size: {}}">
						<img src="<?php echo $thumbUrl ?>">
					</a>

					<label class="checkbox"><input type="checkbox" name="image_back_delete" >
						<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE'); ?>
					</label>
				</div>
						<?php
					}
				} ?>
			</div>
		</div>
	</div>
</div>

