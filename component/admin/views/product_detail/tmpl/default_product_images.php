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
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGES'); ?></h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="product_thumb_image">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE'); ?>
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
					</label>
					<?php
					echo RedshopLayoutHelper::render(
						'component.image',
						array(
							'id'        => 'product_thumb_image',
							'deleteid'  => 'thumb_image_delete',
							'displayid' => 'thumb_image_display',
							'type'      => 'product',
							'image'     => $this->detail->product_thumb_image
						)
					);
					?>
				</div>

				<div class="form-group">
					<label for="product_back_full_image">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_BACK_IMAGE'); ?>
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
					</label>
					<?php
					echo RedshopLayoutHelper::render(
						'component.image',
						array(
							'id'        => 'product_back_full_image',
							'deleteid'  => 'back_image_delete',
							'displayid' => 'back_image_display',
							'type'      => 'product',
							'image'     => $this->detail->product_back_full_image
						)
					);
					?>
				</div>

				<div class="form-group">
					<label for="product_back_thumb_image">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE'); ?>
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
					</label>
					<?php
					echo RedshopLayoutHelper::render(
						'component.image',
						array(
							'id'        => 'product_back_thumb_image',
							'deleteid'  => 'back_thumb_image_delete',
							'displayid' => 'thumb_back_image_display',
							'type'      => 'product',
							'image'     => $this->detail->product_back_thumb_image
						)
					);
					?>
				</div>

				<div class="form-group">
					<label for="product_preview_image">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE'); ?>
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
					</label>
					<?php
					echo RedshopLayoutHelper::render(
						'component.image',
						array(
							'id'        => 'product_preview_image',
							'deleteid'  => 'preview_image_delete',
							'displayid' => 'preview_image_display',
							'type'      => 'product',
							'image'     => $this->detail->product_preview_image
						)
					);
					?>
				</div>

					<div class="form-group">
					<label for="product_preview_image">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE'); ?>
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
					</label>
					<?php
					echo RedshopLayoutHelper::render(
						'component.image',
						array(
							'id'        => 'product_preview_back_image',
							'deleteid'  => 'preview_back_image_delete',
							'displayid' => 'preview_back_image_display',
							'type'      => 'product',
							'image'     => $this->detail->product_preview_back_image
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
