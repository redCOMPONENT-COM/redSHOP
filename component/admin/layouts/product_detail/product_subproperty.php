<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

$subPropertyPublished = ($subProperty->subattribute_published == 1) ? 'checked="checked"' : '';

$subPropertyImage = '';
$subPropertyImageThumb = '';

if ($subProperty->subattribute_color_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subProperty->subattribute_color_image))
{
	$subPropertyImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subProperty->subattribute_color_image;

	$subPropertyImageThumb = RedshopHelperMedia::getImagePath(
		$subProperty->subattribute_color_image,
		'',
		'thumb',
		'subcolor',
		50,
		0,
		Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
	);
}


$mainImage = '';
$mainImageThumb = '';

if ($subProperty->subattribute_color_main_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $property->subattribute_color_main_image))
{
	$mainImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'subproperty/' . $subProperty->subattribute_color_main_image;

	$mainImageThumb = RedshopHelperMedia::getImagePath(
		$subProperty->subattribute_color_main_image,
		'',
		'thumb',
		'subproperty',
		100,
		0,
		Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
	);
}

?>

<div class="sub_property_table">
	<div class="row">
	<div class="col-sm-2">
			<label>
				<?php echo JText::_('COM_REDSHOP_ATTRIBUTES_VALUE'); ?>
			</label>
			<input type="text" class="input-small" name="<?php echo $subPropPref; ?>[name]" value="<?php echo $subProperty->subattribute_color_name; ?>">
			<input type="hidden" name="<?php echo $subPropPref; ?>[subproperty_id]" value="<?php echo $subProperty->subattribute_color_id; ?>" />
			<input type="hidden" id="subPropertyImageName<?php echo $keyAttr . $keyProperty; ?>" name="<?php echo $subPropPref; ?>[image]" value="<?php echo $subProperty->subattribute_color_image; ?>" />
			<input type="hidden" name="<?php echo $subPropPref; ?>[mainImage]" id="subpropmainImage<?php echo $keyAttr . $keySubProp; ?>" value="" />
	</div>
	<div class="col-sm-2">
		<label>
			<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
		</label>
		<input type="text" class="input-xmini text-center" name="<?php echo $subPropPref; ?>[oprand]" value="<?php echo $subProperty->oprand; ?>" onchange="javascript:oprand_check(this);" />
		<input type="text" class="input-mini" name="<?php echo $subPropPref; ?>[price]" value="<?php echo $subProperty->subattribute_color_price; ?>" />
	</div>
	<div class="col-sm-1">
		<label>
			<?php echo JText::_('COM_REDSHOP_SUBPROPERTY_NUMBER'); ?>
		</label>
		<input type="text" size="14" class="vpnrequired input-mini" value="<?php echo $subProperty->subattribute_color_number; ?>" name="<?php echo $subPropPref; ?>[number]" />
	</div>
	<div class="col-sm-1">
		<label><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?></label>
		<input type="text" class="input-mini" name="<?php echo $subPropPref; ?>[extra_field]" value="<?php echo $subProperty->extra_field; ?>" />
	</div>
	<div class="col-sm-3">
		<label>
			<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
		</label>
		<input class="text-center input-xmini" type="text" name="<?php echo $subPropPref; ?>[order]" value="<?php echo $subProperty->ordering; ?>">

		<a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" title="" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $subProperty->subattribute_color_id . '&cid=' . $productId . '&section=subproperty'); ?>">
					<img
						src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>discountmanagmenet16.png"
						alt=""/><?php echo JText::_('COM_REDSHOP_ADD_PRICE_LBL'); ?>
				</a>
				<?php if (Redshop::getConfig()->get('USE_STOCKROOM')): ?>
				<a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $subProperty->subattribute_color_id . '&cid=' . $productId); ?>&layout=productstockroom&property=subproperty">
						<img
							src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>stockroom16.png"/><?php echo JText::_('COM_REDSHOP_ACTION_MANAGE_STOCKROOM'); ?>
					</a>
				<?php endif; ?>
	</div>
	
	<div class="col-sm-1">
		<label>
			<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
			
		</label>
		<input type="checkbox" <?php echo $subPropertyPublished; ?> name="
			<?php echo $subPropPref; ?>[published]" value="1"/>
	</div>

	<div class="col-sm-1">
		<label>
			<?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
		</label>
		<input type="checkbox" value="1" name="<?php echo $subPropPref; ?>[chk_propdselected]" <?php echo ($subProperty->setdefault_selected == 1) ? 'checked' : ''; ?> />
	</div>

	<div class="col-sm-1">
		<input id="deleteSubProp_<?php echo $subProperty->subattribute_color_id; ?>_<?php
					echo $property->property_id; ?>" value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" class="btn btn-danger delete_subproperty btn-small" type="button" />
	</div>

</div>

<div class="row subAttrMedia">

	<div class="col-sm-4">

			<label>
				<?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE'); ?>
			</label>

			<div class="row">
				<div class="imageBlock">
				
					<?php
					if ($mainImage)
					{
						
						?>

						<div class="col-sm-6">
							<a class="joom-box" rel="{handler: 'image', size: {}}" href="<?php echo $mainImage; ?>">
								<img src="<?php echo $mainImageThumb; ?>"/>
							</a>
						
						</div>
						<?php
					}
					
					?>

					<div class="col-sm-6">
						<a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}"
						   title=""
						   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&section_id='
							. $propertyId . '&showbuttons=1&media_section=property'); ?>">
							<img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>media16.png" alt=""/><?php echo JText::_('COM_REDSHOP_UPLOAD'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>

	<div class="col-sm-3">

		<label>
			<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_IMAGE'); ?>
		</label>

		<div class="row">
			<div class="imageBlock">
				<?php
				if ($subPropertyImage)
				{
					
					?>

					<div class="col-sm-6">
						<a class="joom-box" rel="{handler: 'image', size: {}}" href="<?php echo $subPropertyImage; ?>">
						<img id="subpropertyImage<?php echo $keyAttr . $keySubProp; ?>"
							src="<?php echo $subPropertyImageThumb; ?>"/>
						</a>
						<br />
						<input value="<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE'); ?>" type="button" class="btn btn-small deleteSubPropertyMainImage" id="deleteSubPropertyMainImage_<?php echo $subProperty->subattribute_color_id; ?>_<?php
							echo $keyAttr . $keySubProp; ?>" />

					</div>
					<?php
				}
				else
				{
					?>
						<img id="subpropertyImage<?php echo $keyAttr . $keySubProp; ?>" src="" style="display: none;" />
					<?php
					}
				?>

			
				<div class="col-sm-6">
					<input type="file" value="" name="attribute_<?php echo $keyAttr; ?>_property_<?php echo $keyProperty; ?>_subproperty_<?php echo $keySubProp; ?>_image" />
				</div>
			</div>
		</div>

		
		
	</div>
</div>
	
</div>

