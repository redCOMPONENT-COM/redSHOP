<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('a.joom-box');
$data = $displayData['this'];
$productId = 0;
$attributeSetId = 0;

if (isset($data->detail->product_id))
{
	$productId = $data->detail->product_id;
}

if (isset($data->detail->attribute_set_id))
{
	$attributeSetId = $data->detail->attribute_set_id;
}

JText::script('COM_REDSHOP_TITLE');
JText::script('COM_REDSHOP_ATTRIBUTE_REQUIRED');
JText::script('COM_REDSHOP_PUBLISHED');
JText::script('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION');
JText::script('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE');
JText::script('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE');
JText::script('COM_REDSHOP_SUB_ATTRIBUTE');
JText::script('COM_REDSHOP_PRICE');
JText::script('COM_REDSHOP_NEW_SUB_PROPERTY');
JText::script('COM_REDSHOP_SUBATTRIBUTE_REQUIRED');
JText::script('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED');
JText::script('COM_REDSHOP_DELETE_ATTRIBUTE');
JText::script('COM_REDSHOP_ORDERING');
JText::script('COM_REDSHOP_DEFAULT_SELECTED');
JText::script('COM_REDSHOP_SUBPROPERTY_TITLE');
JText::script('COM_REDSHOP_WARNING_TO_DELETE');
JText::script('COM_REDSHOP_DELETE');
JText::script('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD');
JText::script('COM_REDSHOP_DROPDOWN_LIST');
JText::script('COM_REDSHOP_RADIOBOX');
JText::script('COM_REDSHOP_ADD_SUB_ATTRIBUTE');
JText::script('COM_REDSHOP_PARAMETER');
JText::script('COM_REDSHOP_PROPERTY_NUMBER');
JText::script('COM_REDSHOP_DO_WANT_TO_DELETE');
JText::script('COM_REDSHOP_ALERT_PRESELECTED_CHECK');
JText::script('COM_REDSHOP_DESCRIPTION');
?>

<div class="row-fluid mainTableAttributes" id="mainTableAttributes">
<input type="hidden" value="<?php echo count($data->lists['attributes']); ?>" name="count_attr" class="count_attr"/>
<?php
if ($data->lists['attributes'])
{
	foreach ($data->lists['attributes'] as $keyAttr => $attributeData)
	{
		$displayType = $attributeData['display_type'];
		$attributeId = $attributeData['attribute_id'];
		$checkedRequired = ($attributeData['attribute_required'] == 1) ? ' checked="checked"' : '';
		$multipleSelection = ($attributeData['allow_multiple_selection'] == 1) ? ' checked="checked"' : '';
		$hideAttributePrice = ($attributeData['hide_attribute_price'] == 1) ? ' checked="checked"' : '';
		$attributePublished = ($attributeData['attribute_published'] == 1) ? ' checked="checked"' : '';
		$attrPref = 'attribute[' . $keyAttr . ']';
		?>
		<div class="span12 attribute_table divInspectFromHideShow">
		<input type="hidden" name="<?php echo $attrPref; ?>[count_prop]"
			   class="count_prop" value="<?php echo count($attributeData['property']); ?>"/>
		<input type="hidden" value="<?php echo $keyAttr; ?>"
			   name="<?php echo $attrPref; ?>[key_attr]" class="key_attr"/>

		<div class="span12 oneAttribute">
			<div class="span2">
				<a href="#" class="showhidearrow">
					<img class="arrowimg" src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>arrow.png" alt=""/>
					<?php echo JText::_('COM_REDSHOP_TITLE'); ?>
				</a>
			</div>
			<div class="span2">
				<input type="text"
					   class="input-small"
					   name="<?php echo $attrPref; ?>[name]"
					   value="<?php echo $attributeData['attribute_name']; ?>"/>
			</div>
			<div class="span2">
				<?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?>
				<input class="text-center input-small" type="text" name="<?php echo $attrPref; ?>[attribute_description]"
					   value="<?php echo $attributeData['attribute_description']; ?>"/>
			</div>
			<div class="span2">
				<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
				<input class="text-center input-xmini" type="text" name="<?php echo $attrPref; ?>[ordering]"
					   value="<?php echo $attributeData['ordering']; ?>"/>
			</div>
			<div class="span2">
				<label class="checkbox inline"><?php
					echo JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED'); ?>
					<input type="checkbox" name="<?php echo $attrPref; ?>[required]"<?php
					echo $checkedRequired; ?>
						   value="1"/>
				</label>
			</div>
			<div class="span2">
				<label class="checkbox inline"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
					<input type="checkbox"
						   name="<?php echo $attrPref; ?>[published]"
						<?php echo $attributePublished; ?>
						   value="1"/>
				</label>
			</div>
			<div class="span1">
				<input class="btn btn-danger delete_attribute btn-small"
					   id="deleteAttribute_<?php echo $attributeId; ?>_<?php
						echo $productId; ?>_<?php
						echo $attributeSetId; ?>"
					   value="<?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?>" type="button"/>
				<input type="hidden" name="<?php echo $attrPref; ?>[id]" value="<?php
				echo $attributeData['attribute_id']; ?>"/>
			</div>
		</div>
		<div class="span12 attribute_table_pro divFromHideShow">
		<div class="row-fluid attrSecondRow">
			<div class="span2">
				<a class="btn btn-success add_property btn-small">
					+ <?php echo JText::_('COM_REDSHOP_ADD_SUB_ATTRIBUTE'); ?>
				</a>
			</div>
			<div class="span3">
				<label class="checkbox inline"><?php echo JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION'); ?>
					<input type="checkbox" value="1"
						   name="<?php echo $attrPref; ?>[allow_multiple_selection]"
						<?php echo $multipleSelection; ?> /></label>
			</div>
			<div class="span3">
				<label class="checkbox inline"><?php echo JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE'); ?>
					<input type="checkbox" value="1"
						   name="<?php echo $attrPref; ?>[hide_attribute_price]"
						<?php echo $hideAttributePrice; ?> />
				</label>
			</div>
			<div class="span3">
				<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
				<select
					name="<?php echo $attrPref; ?>[display_type]" class="input-medium">
					<option value="dropdown"
						<?php echo ($displayType == 'dropdown') ? 'selected' : ''; ?>>
						<?php echo JText::_('COM_REDSHOP_DROPDOWN_LIST'); ?>
					</option>
					<option value="radio"
						<?php echo ($displayType == 'radio') ? 'selected' : ''; ?>>
						<?php echo JText::_('COM_REDSHOP_RADIOBOX'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="row-fluid property_table">
		<?php
		foreach ($attributeData['property'] as $keyProperty => $property)
		{
			$propertyId = $property->property_id;
			$totalSubProp = (isset($property->subvalue)) ? count($property->subvalue) : 0;
			$propertyPublished = ($property->property_published == 1) ? 'checked="checked"' : '';
			$style = ($totalSubProp) ? 'style="display:block;"' : 'style="display:none;"';
			$propPref = $attrPref . '[property][' . $keyProperty . ']';
			?>
			<div class="row-fluid attr_tbody divInspectFromHideShow">
			<input type="hidden" value="<?php echo $totalSubProp; ?>"
				   name="<?php echo $propPref; ?>[count_subprop]" class="count_subprop"/>
			<input type="hidden" value="<?php echo $keyProperty; ?>"
				   name="<?php echo $propPref; ?>[key_prop]" class="key_prop"/>

			<div class="row-fluid">
			<div class="row-fluid">
				<div class="span1">
					<a href="#" class="showhidearrow">
						<img class="arrowimg"
							 src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>arrow.png" alt=""/>
						<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
					</a>
				</div>
				<div class="span2">
					<input type="text"
						   class="input-small"
						   name="<?php echo $propPref; ?>[name]"
						   value="<?php echo $property->property_name; ?>"/>
					<input type="hidden" name="<?php echo $propPref; ?>[property_id]"
						   value="<?php echo $propertyId; ?>"/>
					<input type="hidden" id="propertyImageName<?php echo $keyAttr . $keyProperty; ?>"
						   name="<?php echo $propPref; ?>[property_image]"
						   value="<?php echo $property->property_image; ?>"/>
					<input type="hidden"
						   name="<?php echo $propPref; ?>[mainImage]"
						   id="propmainImage<?php echo $keyAttr . $keyProperty; ?>" value=""
						/>
				</div>
				<div class="span2">
					<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
					<input type="text" class="text-center input-xmini"
						   name="<?php echo $propPref; ?>[order]"
						   value="<?php echo $property->ordering; ?>"/>
				</div>
				<div class="span2">
					<label class="checkbox inline" name="<?php echo $propPref; ?>[preselected]"><?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
						<input type="checkbox" value="1"
							   name="<?php echo $propPref; ?>[default_sel]"
							<?php echo ($property->setdefault_selected == 1) ? 'checked="checked"' : ''; ?> />
					</label>
				</div>
				<div class="span2">
					<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
					<input
						type="text" class="text-center input-xmini"
						value="<?php echo $property->oprand; ?>"
						name="<?php echo $propPref; ?>[oprand]"
						onchange="javascript:oprand_check(this);"/>
					<input
						type="text" class="input-mini"
						value="<?php echo $property->property_price; ?>"
						name="<?php echo $propPref; ?>[price]"/>
				</div>
				<div class="span3">
					<?php echo JText::_('COM_REDSHOP_PROPERTY_NUMBER'); ?>
					<input type="text" class="vpnrequired input-medium"
						   value="<?php echo $property->property_number; ?>"
						   name="<?php echo $propPref; ?>[number]"/>
				</div>
			</div>
			<br/>
			<div class="row-fluid">
				<div class="span2">
					<a class="joom-box btn btn-small" rel="{handler: 'iframe', size: {x: 950, y: 500}}"
					   title=""
					   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&section_id='
						. $propertyId . '&showbuttons=1&media_section=property'); ?>">
						<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" alt=""/>
					</a>
					<a class="joom-box btn btn-small"
					   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
					   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $propertyId . '&cid=' . $productId . '&section=property'); ?>">
						<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>discountmanagmenet16.png"/>
					</a>
					<?php if (Redshop::getConfig()->get('USE_STOCKROOM')): ?>
						<a class="joom-box btn btn-small"
						   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
						   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $propertyId . '&cid=' . $productId . '&layout=productstockroom&property=property'); ?>">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>stockroom16.png"/>
						</a>
					<?php endif; ?>
				</div>
				<div class="span3">
					<div class="button2-left">
						<div class="image">
							<a class="joom-box"
							   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' . $keyAttr . $keyProperty . '&layout=thumbs'); ?>"
							   rel="{handler: 'iframe', size: {x: 900, y: 500}}"></a>
						</div>
					</div>
					<input type="file" value=""
						   name="attribute_<?php echo $keyAttr; ?>_property_<?php
						   echo $keyProperty; ?>_image"/>
				</div>
				<div class="span1">
					<?php
					if ($property->property_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property->property_image))
					{
						$thumbUrl = RedShopHelperImages::getImagePath(
							$property->property_image,
							'',
							'thumb',
							'product_attributes',
							50,
							0,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
						?>
						<a class="joom-box"
						   rel="{handler: 'image', size: {}}"
						   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image; ?>">
							<img id="propertyImage<?php echo $keyAttr . $keyProperty; ?>"
								src="<?php echo $thumbUrl; ?>"/>
						</a>
						<br />
						<input id="deletePropertyMainImage_<?php echo $property->property_id; ?>_<?php
							echo $keyAttr . $keyProperty; ?>"
							   value="<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE'); ?>"
							   class="btn btn-small deletePropertyMainImage" type="button" />
					<?php
					}
					else
					{
						?>
						<img id="propertyImage<?php echo $keyAttr . $keyProperty; ?>" src="" style="display: none;" />
						<?php
					}
					?>
				</div>
				<div class="span2">
					<label class="checkbox inline"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
						<input type="checkbox" value="1" <?php echo $propertyPublished; ?>
							   name="<?php echo $propPref; ?>[published]"/>
					</label>
				</div>
				<div class="span2">
					<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>
					<input type="text" class="input-small"
						   name="<?php echo $propPref; ?>[extra_field]"
						   value="<?php echo $property->extra_field; ?>"/>
				</div>
				<div class="span2">
					<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
						   id="deleteProperty_<?php echo $propertyId; ?>_<?php
							echo $attributeId; ?>"
						   class="btn btn-danger delete_property btn-small" type="button"/>
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
			<br/>

			<div class="span12 attribute_parameter_tr divFromHideShow">
				<div class="row-fluid">
					<div class="row-fluid showsubproperty"
						 style="<?php echo ($totalSubProp == 0) ? 'display:none;' : ''; ?>">
						<div class="span1">
							<?php echo JText::_('COM_REDSHOP_TITLE'); ?>
						</div>
						<div class="span2">
							<input class="input-small" type="text"
								   name="<?php echo $propPref; ?>[subproperty][title]"
								   value="<?php echo (isset($property->subvalue) && count($property->subvalue) > 0) ? $property->subvalue[0]->subattribute_color_title : ''; ?>">
						</div>
						<div class="span3">
							<label class="checkbox inline"><?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?>
								<input type="checkbox" value="1"
									   name="<?php echo $propPref; ?>[req_sub_att]"
									<?php echo ($property->setrequire_selected == 1) ? 'checked' : ''; ?>>
							</label>
						</div>
						<div class="span3">
							<label
								class="checkbox inline"><?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?>
								<input
									type="checkbox" value="1"
									name="<?php echo $propPref; ?>[multi_sub_att]"
									<?php echo ($property->setmulti_selected == 1) ? 'checked' : ''; ?>>
							</label>
						</div>
						<div class="span3">
							<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
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
					</div>
					<div class="row-fluid">
						<div class="span12 sub_property_table">
							<div class="row-fluid">
								<div class="span1">
									<a class="btn btn-success add_subproperty btn-small" href="#">
										<?php echo "+ " . JText::_('COM_REDSHOP_NEW_SUB_PROPERTY'); ?>
									</a>
								</div>
								<div class="span11">
									<div class="row-fluid sub_attributes_table">
										<?php
										if ($totalSubProp != 0)
										{
											foreach ($property->subvalue as $keySubProp => $subProperty)
											{
												$subPropertyPublished = ($subProperty->subattribute_published == 1) ? 'checked="checked"' : '';
												$subPropPref = $propPref . '[subproperty][' . $keySubProp . ']';
												?>
												<div class="row-fluid sub_attribute_table">
													<div class="span2">
														<?php echo JText::_('COM_REDSHOP_PARAMETER'); ?>
														<input type="text" class="input-small"
															   name="<?php echo $subPropPref; ?>[name]"
															   value="<?php echo $subProperty->subattribute_color_name; ?>">
														<input type="hidden"
															   name="<?php echo $subPropPref; ?>[subproperty_id]"
															   value="<?php echo $subProperty->subattribute_color_id; ?>"/>
														<input type="hidden" id="subPropertyImageName<?php echo $keyAttr . $keyProperty; ?>"
															   name="<?php echo $subPropPref; ?>[image]"
															   value="<?php echo $subProperty->subattribute_color_image; ?>"
															/>
														<input type="hidden"
															   name="<?php echo $subPropPref; ?>[mainImage]"
															   id="subpropmainImage<?php echo $keyAttr . $keySubProp; ?>"
															   value=""
															/>
													</div>
													<div class="span2">
														<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
														<input
															class="text-center input-xmini" type="text"
															name="<?php echo $subPropPref; ?>[order]"
															value="<?php echo $subProperty->ordering; ?>">
													</div>
													<div class="span2">
														<label
															class="inline checkbox"><?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
															<input
																type="checkbox" value="1"
																name="<?php echo $subPropPref; ?>[chk_propdselected]"
																<?php echo ($subProperty->setdefault_selected == 1) ? 'checked' : ''; ?> />
														</label>
													</div>
													<div class="span3">
														<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
														<input type="text" class="input-xmini text-center"
															   name="<?php echo $subPropPref; ?>[oprand]"
															   value="<?php echo $subProperty->oprand; ?>"
															   onchange="javascript:oprand_check(this);"/>
														<input type="text" class="input-mini"
															   name="<?php echo $subPropPref; ?>[price]"
															   value="<?php echo $subProperty->subattribute_color_price; ?>"/>
													</div>
													<div class="span3">
														<?php echo JText::_('COM_REDSHOP_SUBPROPERTY_NUMBER'); ?>
														<input type="text" size="14" class="vpnrequired input-medium"
															   value="<?php echo $subProperty->subattribute_color_number; ?>"
															   name="<?php echo $subPropPref; ?>[number]"/>
													</div>
													<div class="span12 subAttrMedia">
														<div class="row-fluid">
															<div class="span2">
																<a class="joom-box btn btn-small"
																   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&section_id='
																	. $subProperty->subattribute_color_id . '&showbuttons=1&media_section=subproperty'); ?>"
																   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																   title="">
																	<img
																		src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png"
																		alt=""/>
																</a>
																<a class="joom-box btn btn-small"
																   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																   title=""
																   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' . $subProperty->subattribute_color_id . '&cid=' . $productId . '&section=subproperty'); ?>">
																	<img
																		src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>discountmanagmenet16.png"
																		alt=""/>
																</a>
																<?php if (Redshop::getConfig()->get('USE_STOCKROOM')): ?>
																	<a class="joom-box btn btn-small"
																	   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																	   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=' . $subProperty->subattribute_color_id . '&cid=' . $productId); ?>&layout=productstockroom&property=subproperty">
																		<img
																			src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>stockroom16.png"/>
																	</a>
																<?php endif; ?>
																</div>
															<div class="span3">
																<div class="button2-left">
																	<div class="image">
																		<a class="joom-box"
																		   href="<?php echo JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=subproperty&fid=' . $keyAttr . $keySubProp . '&layout=thumbs'); ?>"
																		   rel="{handler: 'iframe', size: {x: 900, y: 500}}"
																			></a>
																	</div>
																</div>
																<input type="file" value=""
																	   name="attribute_<?php echo $keyAttr; ?>_property_<?php echo $keyProperty; ?>_subproperty_<?php echo $keySubProp; ?>_image"/>
															</div>
															<div class="span1">
																<?php
																if ($subProperty->subattribute_color_image != '' && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subProperty->subattribute_color_image))
																{
																	$thumbUrl = RedShopHelperImages::getImagePath(
																		$subProperty->subattribute_color_image,
																		'',
																		'thumb',
																		'subcolor',
																		50,
																		0,
																		Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
																	);
																	?>
																	<a class="joom-box"
																	   rel="{handler: 'image', size: {}}"
																	   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subProperty->subattribute_color_image; ?>">
																		<img id="subpropertyImage<?php echo $keyAttr . $keySubProp; ?>"
																			src="<?php echo $thumbUrl; ?>"/>
																	</a>
																	<br />
																	<input value="<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE'); ?>"
																		   type="button"
																		   class="btn btn-small deleteSubPropertyMainImage"
																		   id="deleteSubPropertyMainImage_<?php echo $subProperty->subattribute_color_id; ?>_<?php
																		echo $keyAttr . $keySubProp; ?>"
																		/>
																<?php
																}
																else
																{
																	?>
																	<img id="subpropertyImage<?php echo $keyAttr . $keySubProp; ?>" src="" style="display: none;"/>
																<?php
																}
																?>
															</div>
															<div class="span2">
																<label
																	class="checkbox inline"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
																	<input
																		type="checkbox" <?php echo $subPropertyPublished; ?>
																		name="<?php echo $subPropPref; ?>[published]"
																		value="1"/>
																</label>
															</div>
															<div class="span2">
																<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>
																<input type="text" class="input-small"
																	   name="<?php echo $subPropPref; ?>[extra_field]"
																	   value="<?php echo $subProperty->extra_field; ?>"/>
															</div>
															<div class="span2">
																<input
																	id="deleteSubProp_<?php echo $subProperty->subattribute_color_id; ?>_<?php
																	echo $property->property_id; ?>"
																	value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
																	class="btn btn-danger delete_subproperty btn-small"
																	type="button"/>
															</div>
														</div>
													</div>
												</div>
											<?php
											}
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			</div>
			</div>
		<?php
		}
		?>
		</div>
		</div>
		</div>
	<?php
	}
}
?>
</div>
