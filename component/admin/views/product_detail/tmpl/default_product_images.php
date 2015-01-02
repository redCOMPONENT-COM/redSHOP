<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<table class="admintable">
	<tr>
		<td class="key">
			<span
				class="editlinktip hasTip"
				title="<?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_IMAGE'); ?>"
			>
				<label for="product_full_image">
					<?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE'); ?>
				</label>
			</span>
		</td>
		<td>
			/<input type="file" name="product_full_image" id="product_full_image" size="25" />
			<div class="button2-left">
				<div class="image">
					<?php $ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs'); ?>
					<a class="modal" href="<?php echo $ilink; ?>" rel="{handler: 'iframe', size: {x: 900, y: 500}}">
						<?php echo JText::_('COM_REDSHOP_IMAGE'); ?>
					</a>
				</div>
			</div>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td>
		<?php
			if ($this->detail->product_id > 0) :

				$ilink = JRoute::_(
					'index.php?tmpl=component&option=com_redshop&view=media&section_id=' .
					$this->detail->product_id .
					'&showbuttons=1&media_section=product'
				);
			?>
			<div class="button2-left">
				<div class="image">
					<a class="modal" title="Image" href="<?php echo $ilink; ?>" rel="{handler: 'iframe', size: {x: 950, y: 500}}">
						<?php echo JText::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES');?>
					</a>
				</div>
			</div>
			<?php else:
				$msgList = array('msgList' => array('message' => array(JText::_('COM_REDSHOP_ADDITIONAL_IMAGES_NOTICE'))));
				echo RedshopLayoutHelper::render('system.message', $msgList);
			endif; ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php
			$style_img  = 'style="display: none;"';
			$image_path = 'product/' . trim($this->detail->product_full_image);

			if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $image_path) && trim($this->detail->product_full_image) != "")
			{
				$style_img = 'style="display: block;"';
			}
			?>
			<div id="image_dis">
				<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $image_path ?>" id="image_display" <?php echo $style_img;?>  border="0" width="200"/>
				<?php
				// ToDo Solve the mistery, why hidden element below doesn't have value attribute?
				?>
				<input type="hidden" name="product_image" id="product_image"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $image_path)) : ?>
				<label for="image_delete">
					<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
				</label>
				<input type="checkbox" name="image_delete" id="image_delete">
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="product_thumb_image">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE'); ?>
			</label>
		</td>
		<td>
			<input type="file" name="product_thumb_image" id="product_thumb_image" size="25">
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_THUMB_IMAGE'),
				JText::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div id="dynamic_field1">
			</div>
			<?php $image_path = 'product/' . trim($this->detail->product_thumb_image); ?>

			<?php if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $image_path) && trim($this->detail->product_thumb_image) != "") : ?>
				<div id="image_dis">
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $image_path; ?>" id="thumb_image_display"/>
				</div>
			<?php endif; ?>

		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $image_path)) : ?>
				<label for="thumb_image_delete">
					<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_THUMB_IMAGE');?>
				</label>
				<input type="checkbox" id="thumb_image_delete" name="thumb_image_delete" />
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="product_back_full_image">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_BACK_IMAGE'); ?>
			</label>
		</td>
		<td>
			<input type="file" name="product_back_full_image" id="product_back_full_image" size="25" />
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_BACK_IMAGE'),
				JText::_('COM_REDSHOP_PRODUCT_BACK_IMAGE'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php $back_image_path = 'product/' . trim($this->detail->product_back_full_image); ?>

			<?php if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $back_image_path) && trim($this->detail->product_back_full_image) != "") : ?>
				<div id="image_dis">
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $back_image_path; ?>" id="back_image_display" border="0" width="200"/>
				</div>
			<?php endif; ?>

		</td>
	</tr>
	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $back_image_path)) : ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="checkbox" id="back_image_delete" name="back_image_delete" />
				<label for="back_image_delete">
					<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
				</label>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td class="key">
			<label for="product_back_thumb_image">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE'); ?>
			</label>
		</td>
		<td>
			<input type="file" name="product_back_thumb_image" id="product_back_thumb_image" size="25" />
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_BACK_THUMB_IMAGE'),
				JText::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php $back_thumb_image_path = 'product/' . trim($this->detail->product_back_thumb_image); ?>

			<?php if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $image_path) && trim($this->detail->product_back_thumb_image) != "") : ?>
				<div id="image_dis">
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $back_thumb_image_path; ?>" id="thumb_back_image_display"/>
				</div>
			<?php endif; ?>

		</td>
	</tr>
	<?php if(is_file(REDSHOP_FRONT_IMAGES_RELPATH . $back_thumb_image_path)) : ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="checkbox" id="back_thumb_image_delete" name="back_thumb_image_delete" />
				<label for="back_thumb_image_delete">
					<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_THUMB_IMAGE');?>
				</label>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td class="key">
			<label for="product_preview_image">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE'); ?>
			</label>
		</td>
		<td>
			<input type="file" name="product_preview_image" id="product_preview_image" size="25" />
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE'),
				JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php $product_preview_image = 'product/' . trim($this->detail->product_preview_image); ?>

			<?php if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $product_preview_image) && trim($this->detail->product_preview_image) != "") : ?>
				<div id="image_dis">
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $product_preview_image; ?>" id="preview_image_display"/>
				</div>
			<?php endif; ?>

		</td>
	</tr>
	<?php if(is_file(REDSHOP_FRONT_IMAGES_RELPATH . $product_preview_image)) : ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="checkbox" id="preview_image_delete" name="preview_image_delete" />
				<label for="preview_image_delete">
					<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
				</label>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td class="key">
			<label for="product_preview_back_image">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE'); ?>
			</label>
		</td>
		<td>
			<input type="file" name="product_preview_back_image" id="product_preview_back_image" size="25" />
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_BACK_IMAGE'),
				JText::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php $product_preview_back_image = 'product/' . trim($this->detail->product_preview_back_image); ?>

			<?php if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $product_preview_back_image)  && trim($this->detail->product_preview_back_image) != "") : ?>
				<div id="image_dis">
					<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $product_preview_back_image; ?>" id="preview_back_image_display"/>
				</div>
			<?php endif; ?>

		</td>
	</tr>
	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $product_preview_back_image)) : ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="checkbox" id="preview_back_image_delete" name="preview_back_image_delete" />
				<label for="preview_back_image_delete">
					<?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
				</label>
			</td>
		</tr>
	<?php endif; ?>

</table>
