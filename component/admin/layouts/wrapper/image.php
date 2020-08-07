<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('behavior.modal', 'a.joom-box');
/**
 * Layout variables
 * ======================================
 *
 * @var  object $item
 */
extract($displayData);
?>
<div class="form-group row-fluid ">
	<div class="col-md-10">
		<label id="jform_category_id-lbl" class="col-md-2 control-label hasPopover"><?php echo JText::_('COM_REDSHOP_WRAPPER_IMAGE'); ?></label>
		<input type="file" name="image" id="image" size="77"/>
	</div>
	<div class="col-md-10">
		<label id="jform_category_id-lbl" class="col-md-2 control-label hasPopover"></label>
		<?php
		if (null != $item->image) : ?>
			<div>
				<?php
				$imagePath      = REDSHOP_FRONT_IMAGES_ABSPATH . 'wrapper/' . $item->image;
				$imageThumbPath = RedshopHelperMedia::getImagePath(
					$item->image,
					'',
					'thumb',
					'wrapper',
					Redshop::getConfig()->get('THUMB_WIDTH'),
					Redshop::getConfig()->get('THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				?>
				<a
					href="<?php
					echo $imagePath; ?>"
					id="image_display_href" class="joom-box"
					rel="{handler: 'image', size: {x: 570, y: 400}}">
					<img
						src="<?php
						echo $imageThumbPath; ?>"
						id="image_display" border="0"
						width="200"/>
				</a>
			</div>
		<?php
		endif; ?>
	</div>
	<input type="hidden" name="image_tmp" id="image_tmp"/>
	<input type="hidden" name="image" id="image"
	       value="<?php
	       echo $item->image; ?>"/>

</div>
