<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

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
				<div class="form-group">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
					</label>
					<input class="text_area" type="text" name="manufacturer_name" id="manufacturer_name" size="32"
						   maxlength="250" value="<?php echo $this->detail->manufacturer_name; ?>"/>
				</div>

				<div class="form-group">
					<label for="template">
						<?php echo JText::_('COM_REDSHOP_TEMPLATE'); ?>:
					</label>
					<?php echo $this->lists['template']; ?>
				</div>

				<div class="form-group">
					<label for="manufacturer_email">
						<?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:
					</label>
					<input class="text_area" type="text" name="manufacturer_email" id="manufacturer_email" size="32"
					       maxlength="250" value="<?php echo $this->detail->manufacturer_email; ?>"/>
				</div>

				<div class="form-group">
					<label for="manufacturer_url">
						<?php echo JText::_('COM_REDSHOP_MANUFACTURER_URL'); ?>:
					</label>
					<input class="text_area" type="text" name="manufacturer_url" id="manufacturer_url" size="32"
					       maxlength="250" value="<?php echo $this->detail->manufacturer_url; ?>"/>
				</div>

				<div class="form-group">
					<label for="product_per_page">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_PER_PAGE'); ?>:
					</label>
					<input class="text_area" type="text" name="product_per_page" id="product_per_page" size="32"
					       maxlength="250" value="<?php echo $this->detail->product_per_page; ?>"/>
				</div>

				<?php if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled) { ?>
				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_EXCLUDING_CATEGORY_LIST'); ?>:
					</label>
					<?php echo $this->lists['excluding_category_list'];?>
				</div>
				<?php }?>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</label>
					<?php echo $this->lists['published'];?>
				</div>

				<div class="form-group">
					<label>
						<?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?>:
					</label>
					<?php echo $editor->display("manufacturer_desc", $this->detail->manufacturer_desc, '$widthPx', '$heightPx', '100', '20');    ?>
				</div>
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
				$model = $this->getModel('manufacturer_detail');
				$media_id = $model->getMediaId($this->detail->manufacturer_id);

				if ($media_id)
				{
					$mediaId = $media_id->media_id;
					$mediaName = $media_id->media_name;
				}
				else
				{
					$mediaId = 0;
					$mediaName = '';
				}

				$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media_detail&cid[]=' . $mediaId . '&section_id=' . $this->detail->manufacturer_id . '&showbuttons=1&media_section=manufacturer&section_name=' . $this->detail->manufacturer_name);

				$image_path = RedShopHelperImages::getImagePath(
								$mediaName,
								'',
								'thumb',
								'manufacturer',
								Redshop::getConfig()->get('MANUFACTURER_THUMB_WIDTH'),
								Redshop::getConfig()->get('MANUFACTURER_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
				?>

				<div class="divimage">
					<img src="<?php echo $image_path; ?>" id="image_display" border="0"/>
					<input type="hidden" name="product_image" id="product_image"/>
				</div>

				<div class="btn-toolbar">
					<a class="joom-box btn btn-primary" title="Image" href="<?php echo $ilink; ?>" rel="{handler: 'iframe', size: {x: 950, y: 500}}">
						<?php echo JText::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES');?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

