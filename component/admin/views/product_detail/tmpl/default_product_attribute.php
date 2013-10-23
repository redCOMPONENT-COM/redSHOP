<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$uri = JURI::getInstance();
$url = $uri->root();

JHtmlBehavior::modal();

// ToDo: This whole tmpl file is one big mess. Try to make it more simple.
?>

<table border="0">

	<tr>
		<td>

			<fieldset class="adminform">

				<legend>
					<?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?>
				</legend>

				<table class="admintable" border="0">

					<tr>
						<td colspan="2">
							<div>
								<?php echo JText::_('COM_REDSHOP_HINT_ATTRIBUTE'); ?>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="2" class="red_blue_blue">
							<?php echo JText::_('COM_REDSHOP_COPY_ATTRIBUTES_FROM_ATTRIBUTE_SET'); ?>
						</td>
					</tr>

					<tr>
						<td>
							<?php echo JText::_('COM_REDSHOP_COPY'); ?>
						</td>
						<td>
							<?php echo $this->lists['copy_attribute']; ?>
						</td>
					</tr>

					<tr>
						<td>
							<?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE_SET_LBL'); ?>
						</td>
						<td>
							<?php echo $this->lists['attributesSet']; ?>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<a class="btn_attribute"
							   href="javascript:addNewRow_attribute('attribute_table');"
							   id="new_attribute">
								<?php
									echo "+ ";
									echo JText::_('COM_REDSHOP_NEW_ATTRIBUTE');
								?>
							</a>
						</td>
					</tr>

				</table>

			</fieldset>

		</td>
	</tr>

	<tr>
		<td>

			<table border="0" id="attribute_table">

				<tr>
					<td colspan="5">
						<span id="atitle" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_TITLE'); ?>
						</span>
						<span id="atitlerequired" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED'); ?>
						</span>
						<span id="atitlepublished" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
						</span>
						<span id="spn_allow_multiple_selection" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION'); ?>
						</span>
						<span id="spn_hide_attribute_price" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE'); ?>
						</span>
						<span id="spn_display_type" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
						</span>
						<span id="aproperty" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
						</span>
						<span id="aprice" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
						</span>
						<span id="new_property" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
						</span>
						<span id="new_sub_property" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_NEW_SUB_PROPERTY'); ?>
						</span>
						<span id="sub_atitlerequired" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?>
						</span>
						<span id="sub_multiselected" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?>
						</span>
						<span id="delete_attribute" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?>
						</span>
						<span id="aimage" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_IMAGE_UPLOAD'); ?>
						</span>
						<span id="aordering" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
						</span>
						<span id="adselected" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
						</span>
						<span id="showpropertytitlespan" style="display: none;">
							<?php echo JText::_('COM_REDSHOP_SUBPROPERTY_TITLE'); ?>
						</span>
						<span id="pathimages" style="display: none;">
							<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>
						</span>
					</td>
				</tr>

				<?php
					$k = 0;
					$z = 1;
				?>

				<?php if ($this->lists['attributes'] != '') : ?>

					<?php
						foreach ($this->lists['attributes'] as $attibute_data)
						{
							$checked_required     = "";
							$multiple_selection   = "";
							$hide_attribute_price = "";
							$attribute_published  = "";
							$display_type         = $attibute_data['display_type'];
							$attribute_id         = $attibute_data['attribute_id'];

							if ($attibute_data['attribute_required'] == 1)
							{
								$checked_required = "checked='checked'";
							}

							if ($attibute_data['allow_multiple_selection'] == 1)
							{
								$multiple_selection = "checked='checked'";
							}

							if ($attibute_data['hide_attribute_price'] == 1)
							{
								$hide_attribute_price = "checked='checked'";
							}

							if ($attibute_data['attribute_published'] == 1)
							{
								$attribute_published = "checked='checked'";
							}
					?>

							<tr>
								<td>

									<table border="0" id="<?php echo "attribute_table" . $attribute_id; ?>">

										<tr>
											<td>

												<table border="0" class="blue_area" id="attribute_table">

													<tr>
														<td class="red_blue_blue td1">
															<img class="arrowimg"
																 id="arrowimg<?php echo $k ?>"
																 onclick="showhidearrow('attribute_table_pro', '<?php echo $k ?>')"
																 src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>arrow.png"
																 alt="img"
																/>
															<label for="attribute[<?php echo $k; ?>][name]">
																<?php echo JText::_('COM_REDSHOP_TITLE'); ?>
															</label>
														</td>
														<td class="td2">
															<input type="text"
																   class="text_area input_t1"
																   size="22"
																   id="attribute[<?php echo $k; ?>][name]"
																   name="attribute[<?php echo $k; ?>][name]"
																   value="<?php echo htmlspecialchars(urldecode($attibute_data['attribute_name'])); ?>"
																/>
														</td>
														<td class="td3">
															<label for="attribute[<?php echo $k; ?>][ordering]">
																<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>&nbsp;
															</label>
															<input class="input_t4"
																   type="text"
																   id="attribute[<?php echo $k; ?>][ordering]"
																   name="attribute[<?php echo $k; ?>][ordering]"
																   size="2"
																   value="<?php echo $attibute_data['ordering']; ?>"
																/>
														</td>
														<td class="td4">
															<label for="attribute[<?php echo $k; ?>][required]">
																<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED'); ?>&nbsp;
															</label>
															<input type="checkbox"
																   class="text_area"
																   size="55"
																   id="attribute[<?php echo $k; ?>][required]"
																   name="attribute[<?php echo $k; ?>][required]"
																   <?php echo $checked_required; ?>
																   value="1"
																/>
														</td>
														<td class="td5">
															<label for="attribute[<?php echo $k; ?>][published]">
																<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>&nbsp;
															</label>
															<input type="checkbox"
																   class="text_area"
																   size="55"
																   id="attribute[<?php echo $k; ?>][published]"
																   name="attribute[<?php echo $k; ?>][published]"
																   <?php echo $attribute_published; ?>
																   value="1"
																/>
														</td>
														<td class="td6">&nbsp;</td>
														<td class="td7">
															<input class="btn_attribute"
																   value="<?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?>"
																   onclick="if(ajax_delete_attribute(<?php echo $this->detail->product_id ?>,<?php echo $attribute_id; ?>,0))
																		{
																			deleteRow_attribute('<?php echo "attribute_table" . $attribute_id; ?>',
																							 'attribute_table','property_table<?php echo $k; ?>',
																							 <?php echo $attibute_data['attribute_id']; ?>);
																		}"
																   type="button"
																/>
															<input type="hidden"
																   name="attribute[<?php echo $k; ?>][id]"
																   value="<?php echo $attibute_data['attribute_id']; ?>"
																/>
														</td>
													</tr>

											</table>

										</td>
									</tr>

										<tr>
											<td>

												<table border="0" class="grey_area" id="attribute_table_pro<?php echo $k; ?>">

													<tr>
														<td class="td1">
															<a class="btn_attribute" href="javascript:addproperty('property_table<?php echo $k ?>','<?php echo $k ?>')">
																<span>
																	<?php
																		echo "+ ";
																		echo JText::_('COM_REDSHOP_ADD_SUB_ATTRIBUTE');
																	?>
																</span>
															</a>
														</td>
														<td class="td2">
															<label for="attribute[<?php echo $k; ?>][allow_multiple_selection]">
																<?php echo JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION'); ?>&nbsp;
															</label>
															<input type="checkbox"
																   size="5"
																   id="attribute[<?php echo $k; ?>][allow_multiple_selection]"
																   name="attribute[<?php echo $k; ?>][allow_multiple_selection]"
																   <?php echo $multiple_selection; ?>
																/>
														</td>
														<td class="td3"></td>
														<td class="td4"></td>
														<td class="td5">
															<label for="attribute[<?php echo $k; ?>][hide_attribute_price]">
																<?php echo JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE'); ?>&nbsp;
															</label>
															<input type="checkbox"
																   size="5"
																   id="attribute[<?php echo $k; ?>][hide_attribute_price]"
																   name="attribute[<?php echo $k; ?>][hide_attribute_price]"
																   <?php echo $hide_attribute_price; ?>
																/>
														</td>
														<td class="td6">
															<?php
																$selected_1 = '';
																$selected_2 = '';

																if ($display_type == "dropdown")
																{
																	$selected_1 = 'selected';
																}
																elseif ($display_type == "radio")
																{
																	$selected_2 = 'selected';
																}
															?>
															<label for="attribute[<?php echo $k; ?>][display_type]">
																<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>&nbsp;
															</label>
															<select id="attribute[<?php echo $k; ?>][display_type]"
																	name="attribute[<?php echo $k; ?>][display_type]">
																<option value="dropdown" <?php  echo $selected_1; ?>>
																	<?php echo JText::_('COM_REDSHOP_DROPDOWN_LIST'); ?>
																</option>
																<option value="radio" <?php  echo $selected_2; ?>>
																	<?php echo JText::_('COM_REDSHOP_RADIOBOX'); ?>
																</option>
															</select>
														</td>
														<td class="td7">&nbsp;&nbsp;</td>
													</tr>

													<tr>
														<td colspan="12">

															<table border="0" class="grey_solid_area" id="property_table<?php echo $k; ?>">

																<tbody>
																<?php
																	$y = 0;
																	$g = 0;

																	foreach ($attibute_data['property'] as $property)
																	{
																		$y++;

																		if (count($attibute_data['property'][$g]->subvalue) > 0)
																		{
																			$style = 'style="display:block;"';
																		}
																		else
																		{
																			$style = 'style="display:none;"';
																		}

																		$property_published = "";

																		if ($property->property_published)
																		{
																			$property_published = "checked='checked'";
																		}
																?>

																	<tr class="attr_tbody" id="attr_tbody<?php echo $k . $g; ?>">
																		<td>

																			<table class="attribute_value" border="0" id="property_table<?php echo $property->property_id; ?>">

																				<tr>
																					<td class="red_blue_blue td1">
																						<img class="arrowimg"
																							 id="arrowimg<?php echo $k . $g; ?>"
																							 onclick="showhidearrow('attribute_parameter_tr', '<?php echo $k . $g; ?>');
																									  showhidearrow('sub_property', '<?php echo $k . $g; ?>');"
																							 src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>arrow.png"
																							 alt="img"
																							/>
																						<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][name]">
																							<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
																						</label>
																					</td>
																					<td class="td2">
																						<input type="text"
																							   class="text_area input_t1"
																							   size="22"
																							   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][name]"
																							   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][name]"
																							   value="<?php echo htmlspecialchars(urldecode($property->property_name)); ?>"
																							/>
																					</td>
																					<td class="td3">
																						<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][order]">
																							<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>&nbsp;
																						</label>
																						<input type="text"
																							   class="text_area input_t4"
																							   size="2"
																							   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][order]"
																							   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][order]"
																							   value="<?php echo $property->ordering; ?>"
																							/>
																					</td>
																					<td class="td4">
																						<?php
																							$checked = $property->setdefault_selected ?  "checked" : '';
																						?>
																						<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][default_sel]">
																							<?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
																						</label>
																						<input type="checkbox"
																							   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][default_sel]"
																							   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][default_sel]"
																							   <?php echo $checked; ?>
																							/>
																					</td>
																					<td class="td5">
																						<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][price]">
																							<?php echo JText::_('COM_REDSHOP_PRICE'); ?>&nbsp;
																						</label>
																						<input type="text"
																							   class="text_area input_t3"
																							   size="1"
																							   id="oprand<?php echo $k . $g; ?>"
																							   value="<?php echo $property->oprand; ?>"
																							   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][oprand]"
																							   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][oprand]"
																							   maxlength="1"
																							   onchange="oprand_check(this);"
																							/>
																						<input type="text"
																							   class="text_area input_t2"
																							   size="8"
																							   value="<?php echo $property->property_price; ?>"
																							   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][price]"
																							   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][price]"
																							/>
																					</td>
																					<td class="td6">
																						<?php $property_id = $property->property_id; ?>

																						<?php if ($property_id) : ?>
																							<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][number]">
																								<?php echo JText::_('COM_REDSHOP_PROPERTY_NUMBER'); ?>&nbsp;
																							</label>
																							<input type="text"
																								   value="<?php echo $property->property_number; ?>"
																								   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][number]"
																								   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][number]"
																								   class="vpnrequired input_t5"
																								   size="14"
																								/>
																						<?php endif; ?>

																						<?php
																							$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' . $k . $g . '&layout=thumbs');
																						?>
																					</td>

																					<input type="hidden"
																						   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][property_id]"
																						   value="<?php echo $property_id; ?>"
																						/>
																					<input type="hidden"
																						   name="imagetmp[<?php echo $k; ?>][value][]"
																						   value="<?php echo $property->property_image; ?>"
																						/>
																					<input type="hidden"
																						   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][mainImage]"
																						   id="propmainImage<?php echo $k . $g; ?>" value=""
																						/>

																					<?php
																						$property_id = $property->property_id;
																						$medialink = '';

																						if ($property_id)
																						{
																							$medialink = JRoute::_(
																												'index.php?tmpl=component&option=com_redshop&view=media&section_id=' .
																												$property_id .
																												'&showbuttons=1&media_section=property'
																							);
																						}
																					?>

																					<td colspan="12" class="td7">
																						<div class="repon">

																							<table border="0" class="up_image">

																							<?php
																								$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' . $k . $g . '&layout=thumbs');
																								$property_id = $property->property_id;
																							?>

																								<tr>
																									<td rowspan="1" class="td1">
																										<a class="modal"
																										   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																										   title=""
																										   href="<?php echo $medialink; ?>"
																											>
																											<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" alt="media" />
																										</a>
																										<a class="modal"
																										   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																										   title=""
																										   href="index.php?tmpl=component&option=com_redshop&amp;view=attributeprices&amp;section_id=<?php echo $property_id ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;section=property"
																											>
																											<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>discountmanagmenet16.png" alt="media" />
																										</a>
																										<a class="modal"
																										   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																										   title=""
																										   href="index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;section_id=<?php echo $property_id ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;layout=productstockroom&amp;property=property"
																											>
																											<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>stockroom16.png" alt="media" />
																										</a>
																									</td>
																									<td class="td2">
																										<div class="button2-left">
																											<div class="image">
																												<a class="modal"
																												   title="Image"
																												   href="<?php echo $ilink; ?>"
																												   rel="{handler: 'iframe', size: {x: 900, y: 500}}"></a>
																											</div>
																										</div>
																										<span>
																											<input type="file"
																												   name="attribute_<?php echo $k ?>_property_<?php echo $g; ?>_image"
																												   id="propfile<?php echo $k . $g; ?>"
																												   value="<?php echo $property->property_image; ?>"
																												/>
																										</span>
																									</td>
																									<td class="td3">
																										<?php
																											$is_img = true;

																											if (!empty($property->property_image))
																											{
																												$property->property_image;
																												$impath = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image;
																												$impath_phy = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property->property_image;

																												if (is_file($impath_phy))
																												{
																													$is_img = false;
																													$thumbUrl = RedShopHelperImages::getImagePath(
																																	$property->property_image,
																																	'',
																																	'thumb',
																																	'product_attributes',
																																	50,
																																	0,
																																	USE_IMAGE_SIZE_SWAPPING
																																);
																										?>
																													<span id="property_image_<?php echo $property->property_id; ?>">
																														<a class="modal"
																														   title="<?php echo $property->property_image; ?>"
																														   rel="{handler: 'image', size: {}}"
																														   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image; ?>">
																															<img
																																id="propertyImage<?php echo $k . $g; ?>"
																																alt='' title=''
																																src='<?php echo $thumbUrl ?>'
																																/>
																														</a>
																													</span>
																									</td>
																									<td class="td4">
																										<input id="btn_attribute_remove_property_<?php echo $property->property_id; ?>"
																											   value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
																											   class="btn_attribute_remove" type='button'
																											   width="0"
																											   onclick="removePropertyImage('<?php echo $property->property_id; ?>','property');"
																											/>
																										<?php
																												}
																											}

																											$ilink = JRoute::_(
																														'index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' .
																														$k .
																														$g .
																														'&layout=thumbs'
																											);
																										?>

																										<?php if ($is_img == true) : ?>
																										&nbsp;
																									</td>
																									<td class="td4">
																										<img id="propertyImage<?php echo $k . $g; ?>" src="" style="display: none;" />
																										<?php endif; ?>
																									</td>
																									<td class="td5">
																										<div>
																											<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][published]">
																												<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>&nbsp;
																											</label>
																											<input type="checkbox"
																												   class="text_area"
																												   size="55"
																												   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][published]"
																												   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][published]"
																												   <?php echo $property_published; ?>
																												   value="1"
																												/>
																											<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][extra_field]">
																												<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>&nbsp;
																											</label>
																											<input type="text"
																												   class="text_area"
																												   size="8"
																												   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][extra_field]"
																												   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][extra_field]"
																												   value="<?php echo $property->extra_field; ?>"
																												/>
																										</div>
																										<div class="remove_attr">
																											<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
																												   class="btn_attribute_remove"
																												   type='button'
																												   width="0"
																												   onclick="if(ajax_delete_property(<?php echo $attribute_id ?>,<?php echo $property_id; ?>)){
																																deleteRow_property('<?php echo 'property_table' . $property->property_id; ?>',
																																				   'property_table<?php echo $k; ?>',
																																				   'sub_attribute_table<?php echo $k . $g; ?>',
																																				   '<?php echo $k . $g; ?>'
																																				  );
																															}"
																												/>
																										</div>
																									</td>
																								</tr>

																							</table>

																						</div>
																					</td>
																				</tr>

																				<?php
																					/**
																					 * This is the place to inject property value data from a product type plugin.
																					 * Plugin group is already loaded in the view.html.php and you can use $this->dispatcher.
																					 * This is used for integration with other redSHOP extensions which can extend product type.
																					 */

																					if (!empty($property->property_id))
																					{
																						$property->product = $this->detail;
																						$property->k       = $k;
																						$property->g       = $g;

																						$this->dispatcher->trigger('productTypeAttributeValue', array($property));
																					}
																				?>

																			</table>

																		</td>
																	</tr>

																	<?php $total_subattr = count($attibute_data['property'][$g]->subvalue); ?>

																	<tr class="attribute_parameter_tr"
																		id="attribute_parameter_tr<?php echo $k . $g; ?>"
																		<?php echo($total_subattr == 0 ? 'style="display:none"' : '') ?>
																		>
																		<td>

																			<table border="0" class="attribute_parameter" id="attribute_parameter<?php echo $property->property_id; ?>">

																				<tr>
																					<td>

																						<table border="0">

																							<tr <?php echo $style ?> id='showsubproperty<?php echo $k . $g; ?>'>
																								<td class="red_blue_blue td1">
																									<span>
																										<?php echo JText::_('COM_REDSHOP_TITLE'); ?>
																									</span>
																								</td>
																								<td class="td2">
																									<input class="input_t1"
																										   type="text"
																										   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][title]"
																										   size="22"
																										   value="<?php echo (count($attibute_data['property'][$g]->subvalue) > 0) ? $attibute_data['property'][$g]->subvalue[0]->subattribute_color_title : ""; ?>">
																								</td>
																								<td class="td3">&nbsp;</td>
																								<td class="td4">
																									<?php
																										$checked = $property->setrequire_selected ?  "checked" : '';
																									?>
																									<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][req_sub_att]">
																										<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?>
																									</label>
																									<input type="checkbox"
																										   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][req_sub_att]"
																										   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][req_sub_att]"
																										   <?php echo $checked;?>
																										/>
																								</td>
																								<td class="td5">
																									<?php
																										$checked = $property->setmulti_selected ?  "checked" : '';
																									?>
																									<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][multi_sub_att]">
																										<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?>
																									</label>
																									<input type="checkbox"
																										   id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][multi_sub_att]"
																										   name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][multi_sub_att]"
																										   <?php echo $checked;?>
																										/>
																								</td>
																								<td class="td6">
																									<?php
																										$selected_1 = '';
																										$selected_2 = '';

																										if ($display_type == "dropdown")
																										{
																											$selected_1 = 'selected';
																										}
																										elseif ($display_type == "radio")
																										{
																											$selected_2 = 'selected';
																										}
																									?>
																									<label for="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][setdisplay_type]">
																										<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
																									</label>
																									<select id="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][setdisplay_type]"
																											name="attribute[<?php echo $k ?>][property][<?php echo $g; ?>][setdisplay_type]">
																										<option value="dropdown" <?php  echo $selected_1; ?>>
																											<?php echo JText::_('COM_REDSHOP_DROPDOWN_LIST'); ?>
																										</option>
																										<option value="radio" <?php  echo $selected_2; ?>>
																											<?php echo JText::_('COM_REDSHOP_RADIOBOX'); ?>
																										</option>
																									</select>
																								</td>
																								<td class="td7"></td>
																							</tr>

																					</table>

																				</td>
																			</tr>

																	</table>

																</td>
															</tr>

															<tr class="sub_property" id="sub_property<?php echo $k . $g; ?>">
																<td>

																	<table border="0" id="sub_property_table<?php echo $k . $g; ?>">

																		<tr>
																			<td class="td1">
																				<a class="btn_attribute"
																				   href="javascript:showpropertytitle('<?php echo $k . $g ?>');
																						 addsubproperty('sub_attribute_table<?php echo $k . $g ?>','<?php echo $k ?>','<?php echo $g; ?>')"
																					>
																					<span id="new_sub_property">
																						<?php
																							echo "+ ";
																							echo JText::_('COM_REDSHOP_NEW_SUB_PROPERTY');
																						?>
																					</span>
																				</a>
																			</td>
																			<td>

																				<table border="0" id="sub_attribute_table<?php echo $k . $g; ?>" class="sub_attribute_table">
																				<?php
																					if ($total_subattr != 0)
																					{
																						$sub_inc = 0;
																						$sp      = 0;

																						foreach ($attibute_data['property'][$g]->subvalue as $subvalue)
																						{
																							$sub_inc++;
																							$impath    = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subvalue->subattribute_color_image;
																							$impathphy = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subvalue->subattribute_color_image;
																							$ilink     = JRoute::_(
																											'index.php?tmpl=component&option=com_redshop&view=media&fsec=subproperty&fid=' .
																											$k .
																											$z .
																											'&layout=thumbs'
																							);

																							$subattribute_published = "";

																							if ($subvalue->subattribute_published)
																							{
																								$subattribute_published = "checked='checked'";
																							}
																				?>

																					<tr>
																						<td>

																							<table border="0" id="sub_attribute_table<?php echo $subvalue->subattribute_color_id; ?>" class="subattribute">

																								<tr>
																									<td class="td2">
																										<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][name]">
																											<?php echo JText::_('COM_REDSHOP_PARAMETER'); ?>
																										</label>
																										<input type="text"
																											   class="text_area input_t2"
																											   size="8"
																											   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][name]"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][name]"
																											   value="<?php echo htmlspecialchars(urldecode($subvalue->subattribute_color_name)); ?>"
																											/>
																									</td>
																									<td class="td3">
																										<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][order]">
																											<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
																										</label>
																										<input class="input_t4"
																											   type="text"
																											   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][order]"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][order]"
																											   size="2"
																											   value="<?php echo $subvalue->ordering; ?>"
																											/>
																									</td>
																									<td class="td4">
																										<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][chk_propdselected]">
																											<?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>
																										</label>
																										<?php $checked = $subvalue->setdefault_selected ? 'checked' : ''; ?>
																										<input type="checkbox"
																											   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][chk_propdselected]"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][chk_propdselected]"
																											   <?php echo $checked; ?>
																											   value="1"
																											/>
																									</td>
																									<td class="td5">
																										<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][price]">
																											<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
																										</label>
																										<input class="input_t3"
																											   type="text"
																											   class="text_area"
																											   size="1"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][oprand]"
																											   maxlength="1"
																											   value="<?php echo $subvalue->oprand; ?>"
																											   id="oprand<?php echo $k . $g; ?>"
																											   onchange="oprand_check(this);"
																											/>
																										<input class="input_t2"
																											   type="text"
																											   class="text_area"
																											   size="8"
																											   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][price]"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][price]"
																											   value="<?php echo $subvalue->subattribute_color_price; ?>"
																											/>
																									</td>
																									<td class="td6">

																										<?php if ($subvalue->subattribute_color_id != 0) : ?>
																											<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][number]">
																												<?php echo JText::_('COM_REDSHOP_SUBPROPERTY_NUMBER'); ?>&nbsp;
																											</label>
																											<input type="text"
																												   size="14"
																												   value="<?php echo $subvalue->subattribute_color_number; ?>"
																												   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][number]"
																												   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][number]"
																												   class="vpnrequired input_t5"
																												/>
																										<?php endif; ?>

																										<input type="hidden"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][subproperty_id]"
																											   value="<?php echo $subvalue->subattribute_color_id; ?>"
																											/>
																										<input type="hidden"
																											   name="imagetmp[<?php echo $k ?>][subvalue][<?php echo $g; ?>][]"
																											   value="<?php echo $subvalue->subattribute_color_image; ?>"
																											/>
																										<input type="hidden"
																											   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp; ?>][mainImage]"
																											   id="subpropmainImage<?php echo $k . $z; ?>"
																											   value=""
																											/>
																									</td>
																									<td colspan="12" class="td7">
																										<div class="repon">

																											<table border="0" class="up_image">

																												<tr>
																													<td rowspan="3" class="td1">
																														<?php if ($subvalue->subattribute_color_id != 0) : ?>
																															<?php
																																$medialink = JRoute::_(
																																				'index.php?tmpl=component&option=com_redshop&view=media&section_id=' .
																																				$subvalue->subattribute_color_id .
																																				'&showbuttons=1&media_section=subproperty'
																																);
																															?>
																															<a class="modal" href="<?php echo $medialink; ?>" rel="{handler: 'iframe', size: {x: 950, y: 500}}" title="">
																																<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" alt="media">
																															</a>
																															<a class="modal"
																															   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																															   title=""
																															   href="index.php?tmpl=component&option=com_redshop&amp;view=attributeprices&amp;section_id=<?php echo $subvalue->subattribute_color_id; ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;section=subproperty"
																																>
																																<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>discountmanagmenet16.png" alt="media" />
																															</a>
																															<a class="modal"
																															   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
																															   title=""
																															   href="index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;section_id=<?php echo $subvalue->subattribute_color_id; ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;layout=productstockroom&amp;property=subproperty"
																																>
																																<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>stockroom16.png" alt="media" />
																															</a>
																														<?php endif; ?>
																													</td>
																													<td class="td2">
																														<div class="button2-left">
																															<div class="image">
																																<a class="modal"
																																   title="Image"
																																   href="<?php echo $ilink; ?>"
																																   rel="{handler: 'iframe', size: {x: 900, y: 500}}"
																																	></a>
																															</div>
																														</div>
																														<input type="file"
																															   name="attribute_<?php echo $k ?>_property_<?php echo $g ?>_subproperty_<?php echo $sp; ?>_image"
																															   value="<?php echo $subvalue->subattribute_color_image; ?>"
																															/>
																													</td>
																													<td class="td3">
																														<?php if (!empty($subvalue->subattribute_color_image) && is_file($impathphy)) : ?>
																															<span id="subproperty_image_<?php echo $subvalue->subattribute_color_id; ?>">
																																<a class="modal"
																																   rel="{handler: 'image', size: {}}"
																																   title="<?php echo $subvalue->subattribute_color_image; ?>"
																																   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subvalue->subattribute_color_image; ?>">
																																   <?php
																																   $thumbUrl = RedShopHelperImages::getImagePath(
																																					$subvalue->subattribute_color_image,
																																					'',
																																					'thumb',
																																					'subcolor',
																																					50,
																																					0,
																																					USE_IMAGE_SIZE_SWAPPING
																																				);
																																   ?>
																																	<img id="subpropertyImage<?php echo $k . $z; ?>"
																																		 src='<?php echo $thumbUrl; ?>'
																																		 alt=''
																																		 title=''
																																		/>
																																</a>
																															</span>
																													</td>
																													<td class="td4">
																														<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
																															   type='button'
																															   onclick="removePropertyImage('<?php echo $subvalue->subattribute_color_id; ?>','subproperty');"
																															   title="<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE'); ?>"
																															   class="btn_attribute_remove"
																															   id="btn_attribute_remove_subproperty_<?php echo $subvalue->subattribute_color_id; ?>"
																															/>
																														<?php else : ?>
																														&nbsp;
																													</td>
																													<td class="td4">
																														<img id="subpropertyImage<?php echo $k . $z; ?>" src="" style="display: none;"/>
																														<?php endif; ?>
																													</td>
																													<td class="td5">
																														<div>
																															<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp ?>][published]">
																																<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>&nbsp;
																															</label>
																															<input type="checkbox"
																																   class="text_area"
																																   size="55"
																																   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp ?>][published]"
																																   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp ?>][published]"
																																   <?php echo $subattribute_published; ?>
																																   value="1"
																																/>
																															<label for="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp ?>][extra_field]">
																																<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>&nbsp;
																															</label>
																															<input type="text"
																																   class="text_area"
																																   size="8"
																																   id="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp ?>][extra_field]"
																																   name="attribute[<?php echo $k ?>][property][<?php echo $g ?>][subproperty][<?php echo $sp ?>][extra_field]"
																																   value="<?php echo $subvalue->extra_field; ?>"
																																/>
																														</div>
																														<div class="remove_attr">
																															<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
																																   class="btn_attribute_remove"
																																   type='button'
																																   onclick="if(ajax_delete_subproperty(<?php echo $subvalue->subattribute_color_id ?>,<?php echo $property_id; ?>)){
																																			   deleteRow_subproperty('<?php echo 'sub_attribute_table' . $subvalue->subattribute_color_id ?>',
																																									 'sub_attribute_table<?php echo $k . $g ?>',
																																									 '<?php echo $subvalue->subattribute_color_id; ?>');
																																			}"
																																/>
																														</div>
																													</td>
																												</tr>

																											</table>

																										</div>
																									</td>
																								</tr>

																							</table>

																						</td>
																					</tr>

																				<?php
																						$sp++;
																						$z++;
																						}
																					}
																					else
																					{
																						$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=subproperty&fid=' . $k . $z . '&layout=thumbs');
																				?>

																					<tr style="display: none;">
																						<td>
																							<input type="text"
																								   class="text_area"
																								   size="40"
																								   name="property[<?php echo $k; ?>][subvalue][][]"
																								   value=""
																								/>
																						</td>
																						<td>
																							<label for="price[<?php echo $k; ?>][subvalue][][]">
																								<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
																							</label>
																						</td>
																						<td>
																							<input type="text"
																								   class="text_area"
																								   size="1"
																								   name="oprand[<?php echo $k; ?>][subvalue][][]"
																								   maxlength="1" value="+"
																								   id="oprand<?php echo $k . $g; ?>"
																								   onchange="oprand_check(this);"
																								/>
																						</td>
																						<td>
																							<input type="text"
																								   class="text_area"
																								   size="12"
																								   id="price[<?php echo $k; ?>][subvalue][][]"
																								   name="price[<?php echo $k; ?>][subvalue][][]"
																								   value=""
																								/>
																						</td>
																						<td>
																							<img id="subpropertyImage<?php echo $k . $z; ?>" src="" style="display: none;"/>
																							<div class="button2-left">
																								<div class="image">
																									<a class="modal" title="Image" href="<?php echo $ilink; ?>" rel="{handler: 'iframe', size: {x: 900, y: 500}}"></a>
																								</div>
																							</div>
																							<input type="file" name="image[<?php echo $k; ?>][subvalue][][]" />
																							<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
																								   class='button'
																								   type='button'
																								   width="0"
																								   onclick="deleteRow_subproperty(this,'sub_attribute_table<?php echo $k . $g; ?>');"
																								/>
																							<input type="hidden" name="property_id[<?php echo $k; ?>][subvalue][][]" value="">
																							<input type="hidden" name="imagetmp[<?php echo $k; ?>][subvalue][][]" value="">
																							<input type="hidden" name="mainImage[<?php echo $k ?>][subvalue][][]" id="subpropmainImage<?php echo $k . $g; ?>" value="">
																						</td>
																					</tr>

																				<?php
																					}
																				?>

																				</table>

																			</td>
																		</tr>

																	</table>

																</td>
															</tr>

																<?php
																	$g++;
																	}
																?>

																</tbody>

															</table>

														</td>
													</tr>

												</table>

											</td>
										</tr>

									</table>

								</td>
							</tr>

					<?php
							$k++;
						}
					?>

							<span id="delete_attribute" style="display: none;">
								<?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?>
							</span>
							<span id="aimage" style="display: none">
								<?php echo JText::_('COM_REDSHOP_IMAGE_UPLOAD'); ?>
							</span>
							<span id="aproperty" style="display: none">
								<?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
							</span>
							<span id="aprice" style="display: none">
								<?php echo JText::_('COM_REDSHOP_PRICE'); ?>
							</span>

							<tr>
								<td colspan="5">
									<input type="hidden" name="total_table" id="total_table" value="<?php echo $k; ?>" />
									<input type="hidden" name="total_g" id="total_g" value="<?php echo $g; ?>" />
									<input type="hidden" name="total_z" id="total_z" value="<?php echo $z; ?>" />
								</td>
							</tr>
				<?php else : ?>
						<?php
							$g = 1;
							$z = 1;
						?>
							<tr>
								<td colspan="5">
									<input type="hidden" name="total_table" id="total_table" value="<?php echo $k; ?>" />
									<input type="hidden" name="total_g" id="total_g" value="<?php echo $g; ?>" />
									<input type="hidden" name="total_z" id="total_z" value="<?php echo $z; ?>" />
								</td>
							</tr>
				<?php endif; ?>

			</table>

		</td>
	</tr>

</table>