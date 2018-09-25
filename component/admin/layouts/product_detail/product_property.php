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

$propertyId = $property->property_id;
$totalSubProp = (isset($property->subvalue)) ? count($property->subvalue) : 0;
$propertyPublished = ($property->property_published == 1) ? 'checked="checked"' : '';
$style = ($totalSubProp) ? 'style="display:block;"' : 'style="display:none;"';

$propertyImage = '';
$propertyImageThumb = '';

if ($property->property_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property->property_image))
{
	$propertyImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image;

	$propertyImageThumb = RedshopHelperMedia::getImagePath(
		$property->property_image,
		'',
		'thumb',
		'product_attributes',
		100,
		0,
		Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
	);
}

$mainImage = '';
$mainImageThumb = '';

if ($property->property_main_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $property->property_main_image))
{
	$mainImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $property->property_main_image;

	$mainImageThumb = RedshopHelperMedia::getImagePath(
		$property->property_main_image,
		'',
		'thumb',
		'property',
		120,
		0,
		Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
	);
}

?>
<a href="#" class="showhidearrow">
	<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
	<img class="arrowimg" src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>arrow.png" alt=""/>
</a>
<div class="attr_tbody form-inline divInspectFromHideShow">
	<input type="hidden" value="<?php echo $totalSubProp; ?>" name="<?php echo $propPref; ?>[count_subprop]" class="count_subprop" />
	<input type="hidden" value="<?php echo $keyProperty; ?>" name="<?php echo $propPref; ?>[key_prop]" class="key_prop" />
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
				</label>
				<input type="text" class="input-medium" name="<?php echo $propPref; ?>[name]" value="<?php echo $property->property_name; ?>" />
				<input type="hidden" name="<?php echo $propPref; ?>[property_id]" value="<?php echo $propertyId; ?>" />
				<input type="hidden" id="propertyImageName<?php echo $keyAttr . $keyProperty; ?>" name="<?php echo $propPref; ?>[property_image]" value="<?php echo $property->property_image; ?>" />
				<input type="hidden" name="<?php echo $propPref; ?>[mainImage]" id="propmainImage<?php echo $keyAttr . $keyProperty; ?>" value="" />
			</div>
		
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
				</label>
				<input type="text" class="text-center input-xmini" value="<?php echo $property->oprand; ?>" name="<?php echo $propPref; ?>[oprand]" onchange="javascript:oprand_check(this);" />
				<input type="text" class="input-medium" value="<?php echo $property->property_price; ?>" name="<?php echo $propPref; ?>[price]" />
			</div>

		</div>

		<div class="col-sm-4">
			<a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $propertyId . '&cid=' . $productId . '&section=property'); ?>">
						<img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>discountmanagmenet16.png"/> <?php echo JText::_('COM_REDSHOP_ADD_PRICE_LBL'); ?>
					</a>
			<?php if (Redshop::getConfig()->get('USE_STOCKROOM')): ?>
			<a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}" href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $propertyId . '&cid=' . $productId . '&layout=productstockroom&property=property'); ?>">
								<img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>stockroom16.png"/> <?php echo JText::_('COM_REDSHOP_ACTION_MANAGE_STOCKROOM'); ?>
							</a>
			<?php endif; ?>
		</div>

	</div>
	<div class="row">

		<div class="col-sm-4">
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_REDSHOP_PROPERTY_NUMBER'); ?>
				</label>
				<input type="text" class="vpnrequired input-medium" value="<?php echo $property->property_number; ?>" name="<?php echo $propPref; ?>[number]" />
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>
				</label>
				<input type="text" class="input-medium" name="<?php echo $propPref; ?>[extra_field]" value="<?php echo $property->extra_field; ?>" />
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
				</label>
				<input type="text" class="text-center input-xmini" name="<?php echo $propPref; ?>[order]" value="<?php echo $property->ordering; ?>" />
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4">

			<label>
				<?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE'); ?>
			</label>

			<div class="row ">

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

		<div class="col-sm-4">

			<label>
				<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_IMAGE'); ?>
			</label>

			<div class="row">
				<div class="imageBlock">
					<?php
					if ($propertyImage)
					{
						
						?>

						<div class="col-sm-6">
							<a class="joom-box" rel="{handler: 'image', size: {}}" href="<?php echo $propertyImage; ?>">
							<img id="propertyImage<?php echo $keyAttr . $keyProperty; ?>"
								src="<?php echo $propertyImageThumb; ?>"/>
						</a>
						<br />
						<input id="deletePropertyMainImage_<?php echo $property->property_id; ?>_<?php
							echo $keyAttr . $keyProperty; ?>" value="<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE'); ?>" class="btn btn-small deletePropertyMainImage" type="button" />

						</div>
						<?php
					}
					else
					{
						?>
							<img id="propertyImage<?php echo $keyAttr . $keyProperty; ?>" src="" style="display: none;" />
							<?php
					}
					?>

				
					<div class="col-sm-6">
						<input type="file" value="" name="attribute_<?php echo $keyAttr; ?>_property_<?php echo $keyProperty; ?>_image" />
					</div>
				</div>
			</div>

			
			
		</div>
	</div>

	<?php
		/**
		 * This is the place to inject property value data from a product type plugin.
		 * Plugin group is already loaded in the view.html.php and you can use $data->dispatcher.
		 * This is used for integration with other redSHOP extensions which can extend product type.
		 */

		if ($productId && !empty($property->property_id))
		{
			$property->product = $data->detail;
			$property->k       = $keyAttr;
			$property->g       = $keyProperty;

			$data->dispatcher->trigger('productTypeAttributeValue', array($property));
		}
		?>

	<div class="row">

		<div class="col-sm-4">
			<div class="form-group">
				<label name="<?php echo $propPref; ?>[preselected]">
					<?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
				</label>
				<input type="checkbox" value="1"
					name="<?php echo $propPref; ?>[default_sel]"
				<?php echo ($property->setdefault_selected == 1) ? 'checked="checked"' : ''; ?> />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
				</label>
				<input type="checkbox" value="1" <?php echo $propertyPublished; ?> name="<?php echo $propPref; ?>[published]"/>
			</div>
		</div>
		<div class="col-sm-8">
			<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" id="deleteProperty_<?php echo $propertyId; ?>_<?php
								echo $attributeId; ?>" class="btn btn-danger delete_property btn-small" type="button" />
			<a class="btn btn-success add_subproperty btn-small" href="#"><?php echo "+ " . JText::_('COM_REDSHOP_NEW_SUB_PROPERTY'); ?></a>
			
		</div>
	</div>


	<div class="attribute_parameter_tr divFromHideShow ">
		<div class="row showsubproperty" style="<?php echo ($totalSubProp == 0) ? 'display:none;' : ''; ?>">
				<div class="col-sm-6">
					<div class="form-group">
						<label>
							<?php echo JText::_('COM_REDSHOP_PROPERTY_NAME'); ?>
						</label>
						<input class="" type="text"
						   name="<?php echo $propPref; ?>[subproperty][title]"
						   value="<?php echo (isset($property->subvalue) && count($property->subvalue) > 0) ? $property->subvalue[0]->subattribute_color_title : ''; ?>">
					</div>

					<div class="form-group">
						<label><?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?></label>
						<input type="checkbox" value="1"
							name="<?php echo $propPref; ?>[default_sel]"
						<?php echo ($property->setdefault_selected == 1) ? 'checked="checked"' : ''; ?> />
					</div>
				</div>
				
				<div class="col-sm-6">
					<div class="form-group">
						<label>
							<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
						</label>
						<select
							name="<?php echo $propPref; ?>[setdisplay_type]" class="input-medium">
							<option value="dropdown"
								<?php echo ($property->setdisplay_type == 'dropdown') ? 'selected' : ''; ?>>
								<?php echo JText::_('COM_REDSHOP_DROPDOWN_LIST'); ?>
							</option>
							<option value="radio"
								<?php echo ($property->setdisplay_type == 'radio') ? 'selected' : ''; ?>>
								<?php echo JText::_('COM_REDSHOP_RADIOBOX'); ?>
							</option>
						</select>
					</div>

					<div class="form-group">
						<label>
							<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?>
						</label>
						<input
							type="checkbox" value="1"
							name="<?php echo $propPref; ?>[multi_sub_att]"
							<?php echo ($property->setmulti_selected == 1) ? 'checked' : ''; ?>>
					</div>

				
				</div>
			
			</div>

			<?php
			if ($totalSubProp != 0)
			{ ?>

				<div class="sub_attribute_table">

				<?php
				foreach ($property->subvalue as $keySubProp => $subProperty)
				{
				
					$subPropPref = $propPref . '[subproperty][' . $keySubProp . ']';
				
					echo RedshopLayoutHelper::render(
						'product_detail.product_subproperty', 
						array(
							'subPropPref' => $subPropPref,
							'subProperty' => $subProperty
						)
					);
				
				}

				?>

				</div>
			<?php }
			?>
			
				
				
			
	</div>
	
</div>
