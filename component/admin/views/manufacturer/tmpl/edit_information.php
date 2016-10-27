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
$order_functions = order_functions::getInstance();
$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');

?>

<div class="row">
	<div class="col-sm-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></h3>
			</div>
			<div class="box-body">
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
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_IMAGE'); ?></h3>
			</div>
			<div class="box-body">
				<?php
				$model = $this->getModel();
				$media = RedshopHelperManufacturer::getMedia($this->item->manufacturer_id);
				$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media_detail&cid[]=' . 0 . '&section_id=' . $this->item->manufacturer_id . '&showbuttons=1&media_section=manufacturer&section_name=' . $this->item->manufacturer_name);
				if ($media)
				{
					$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media_detail&cid[]=' . $media->media_id . '&section_id=' . $this->item->manufacturer_id . '&showbuttons=1&media_section=manufacturer&section_name=' . $this->item->manufacturer_name);
					$image_path = RedshopHelperMedia::getImagePath(
						$media->media_name,
						'',
						'thumb',
						'manufacturer',
						Redshop::getConfig()->get('MANUFACTURER_THUMB_WIDTH'),
						Redshop::getConfig()->get('MANUFACTURER_THUMB_HEIGHT'),
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
				}

				?>
				<?php if (isset($image_path)) : ?>
				<div class="divimage">
					<img src="<?php echo $image_path; ?>" id="image_display" border="0"/>
					<input type="hidden" name="product_image" id="product_image"/>
				</div>
				<?php endif; ?>
				<div class="btn-toolbar">
					<a class="modal btn btn-primary" title="Image" href="<?php echo $ilink; ?>" rel="{handler: 'iframe', size: {x: 950, y: 500}}">
						<?php echo JText::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES');?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

