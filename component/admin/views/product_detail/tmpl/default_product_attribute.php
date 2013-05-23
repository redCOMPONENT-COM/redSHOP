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
JHTML::_('behavior.tooltip');
//$editor =& JFactory::getEditor();
JHTMLBehavior::modal();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<fieldset class="adminform" style="background-color: white;">
			<legend>
				<?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?>
			</legend>
			<table class="admintable" width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2">
						<div>
							<?php echo JText::_('COM_REDSHOP_HINT_ATTRIBUTE'); ?>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"
					    class="red_blue_blue"><?php echo JText::_('COM_REDSHOP_COPY_ATTRIBUTES_FROM_ATTRIBUTE_SET'); ?>
					</td>
				</tr>
				<tr>
					<td align="left" width="140px;"><?php echo JText::_('COM_REDSHOP_COPY'); ?>
					</td>
					<td><?php echo $this->lists['copy_attribute']; ?></td>
				</tr>
				<tr>
					<td valign="top" align="left"
					    width="140px;"><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE_SET_LBL'); ?>:
					</td>
					<td><?php echo $this->lists['attributesSet']; ?></td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 20px 0;"><a class="btn_attribute"
					                                            href="javascript:addNewRow_attribute('attribute_table')"
					                                            id="new_attribute"> <?php echo "+ ";
							echo JText::_('COM_REDSHOP_NEW_ATTRIBUTE'); ?>
						</a><br> <br>
					</td>
				</tr>

			</table>
		</fieldset>
	</td>
</tr>
<tr>
<td>
<table width="100%" cellpadding="2" border="0" id="attribute_table"
       cellspacing="2">
<tr>
	<td colspan="5" align="right"><span id="atitle"
	                                    style="display: none;"><?php echo JText::_('COM_REDSHOP_TITLE'); ?></span>
		<span id="atitlerequired"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED'); ?></span>
		<span id="atitlepublished" style="display: none;"> <?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?></span>
		<span id="spn_allow_multiple_selection"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION'); ?></span>
		<span id="spn_hide_attribute_price"
		      style="display: none;"><?php echo JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE'); ?></span>
		<span id="spn_display_type"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?></span>
		<span id="aproperty" style="display: none;"> <?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?></span>
		<span id="aprice" style="display: none;"> <?php echo JText::_('COM_REDSHOP_PRICE'); ?></span>
		<span id="new_property" style="display: none;"> <?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?></span>
		<span id="new_sub_property"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_NEW_SUB_PROPERTY'); ?></span>
		<span id="sub_atitlerequired"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?></span>
		<span id="sub_multiselected"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?></span>
		<span id="delete_attribute"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?></span>
		<span id="aimage" style="display: none;"> <?php echo JText::_('COM_REDSHOP_IMAGE_UPLOAD'); ?></span>
		<span id="aordering" style="display: none;"> <?php echo JText::_('COM_REDSHOP_ORDERING'); ?></span>
		<span id="adselected" style="display: none;"> <?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?></span>
		<span id="showpropertytitlespan"
		      style="display: none;"> <?php echo JText::_('COM_REDSHOP_SUBPROPERTY_TITLE'); ?></span>
		<span id="pathimages" style="display: none;"><?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?></span>
	</td>
</tr>
<?php
$k = 0;
$z = 1;
if ($this->lists['attributes'] != '')
{
	foreach ($this->lists['attributes'] as $attibute_data)
	{
		$checked_required     = "";
		$multiple_selection   = "";
		$hide_attribute_price = "";
		$attribute_published  = "";
		$display_type         = $attibute_data['display_type'];
		$attribute_id         = $attibute_data['attribute_id'];
		if ($attibute_data['attribute_required'] == 1)
			$checked_required = "checked='checked'";
		if ($attibute_data['allow_multiple_selection'] == 1)
			$multiple_selection = "checked='checked'";
		if ($attibute_data['hide_attribute_price'] == 1)
			$hide_attribute_price = "checked='checked'";
		if ($attibute_data['attribute_published'] == 1)
			$attribute_published = "checked='checked'";



		?>
		<tr>
		<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0"
		       id="<?php echo "attribute_table" . $attribute_id; ?>">
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0"
				       cellpadding="0" class="blue_area" id="attribute_table">
					<tr>
						<td class="red_blue_blue td1" align="left">
							<img class="arrowimg" id="arrowimg<?php echo $k ?>"
							     onclick="showhidearrow('attribute_table_pro', '<?php echo $k ?>')"
							     src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>arrow.png" alt="img"/>
							<span><?php echo JText::_('COM_REDSHOP_TITLE'); ?></span>
						</td>

						<td align="right" class="td2">
							<input type="text" class="text_area input_t1" size="22"
							       name="attribute[<?php echo $k; ?>][name]"
							       value="<?php echo htmlspecialchars(urldecode($attibute_data['attribute_name'])); ?>">
						</td>

						<td align="right" nowrap="nowrap" class="td3">
							<span><?php echo JText::_('COM_REDSHOP_ORDERING'); ?>:&nbsp;</span>
							<input class="input_t4" type="text" name="attribute[<?php echo $k; ?>][ordering]" size="2"
							       value="<?php echo $attibute_data['ordering']; ?>">
						</td>

						<td align="right" class="td4">
							<span><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED'); ?>:&nbsp;</span>
							<input type="checkbox" class="text_area" size="55"
							       name="attribute[<?php echo $k; ?>][required]" <?php echo $checked_required; ?>
							       value="1">
						</td>

						<td align="right" class="td5">
							<span><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:&nbsp;</span>
							<input type="checkbox" class="text_area" size="55"
							       name="attribute[<?php echo $k; ?>][published]" <?php echo $attribute_published; ?>
							       value="1"></td>

						<td class="td6">&nbsp;</td>

						<td align="right" class="td7">
							<input
								style="cursor: pointer; float: right;" class="btn_attribute"
								value="<?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?>"
								onclick="if(ajax_delete_attribute(<?php echo $this->detail->product_id ?>,<?php echo $attribute_id; ?>,0)){deleteRow_attribute('<?php echo "attribute_table" . $attribute_id; ?>','attribute_table','property_table<?php echo $k; ?>',<?php echo $attibute_data['attribute_id']; ?>);}"
								type="button"/> <input type="hidden"
							                           name="attribute[<?php echo $k; ?>][id]"
							                           value="<?php echo $attibute_data['attribute_id']; ?>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
		<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grey_area"
		       id="attribute_table_pro<?php echo $k; ?>">
		<tr>
			<td class="td1" align="left" style="padding-left: 0px !important;">
				<a class="btn_attribute"
				   href="javascript:addproperty('property_table<?php echo $k; ?>','<?php echo $k; ?>')">
					<span><?php echo "+ ";
						echo JText::_('COM_REDSHOP_ADD_SUB_ATTRIBUTE'); ?></span>
				</a>
			</td>

			<td align="right" class="td2">
				<span> <?php echo JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION'); ?>:&nbsp;</span>
				<input type="checkbox" size="5"
				       name="attribute[<?php echo $k; ?>][allow_multiple_selection]"
					<?php echo $multiple_selection; ?>><!-- &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_( 'COM_REDSHOP_TOOLTIP_ALLOW_MULTIPLE_PROPERTY_SELECTION' ), JText::_( 'COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION' ), 'tooltip.png', '', '', false); ?> -->

			</td>

			<td class="td3"></td>
			<td class="td4"></td>


			<td align="right" class="td5">
				<span> <?php echo JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE'); ?>:&nbsp;</span>
				<input type="checkbox" size="5"
				       name="attribute[<?php echo $k; ?>][hide_attribute_price]"
					<?php echo $hide_attribute_price; ?>>
			</td>


			<td align="right" class="td6" nowrap="nowrap">
				<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>:&nbsp;
				<select
					name="attribute[<?php echo $k; ?>][display_type]">
					<option value="dropdown"
						<?php if ($display_type == "dropdown")
						{
							echo "selected";
						}?>>
						Dropdown List
					</option>
					<option value="radio"
						<?php if ($display_type == "radio")
						{
							echo "selected";
						}?>>
						<?php echo JText::_('COM_REDSHOP_RADIOBOX'); ?>
					</option>
				</select>
			</td>


			<td class="td7">&nbsp;&nbsp;</td>
		</tr>
		<tr>
		<td colspan="12">

		<table width="100%" border="0" cellspacing="0"
		       cellpadding="0" class="grey_solid_area"
		       id="property_table<?php echo $k; ?>">

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
			if ($property->property_published == 1)
				$property_published = "checked='checked'";

			?>


			<tr valign="top" class="attr_tbody" id="attr_tbody<?php echo $k . $g; ?>">
			<td>
			<table class="attribute_value" width="100%" border="0"
			       cellspacing="0" cellpadding="0"
			       id="property_table<?php echo $property->property_id; ?>">
			<tr valign="top">
			<td class="red_blue_blue td1" align="left">
				<img class="arrowimg" id="arrowimg<?php echo $k ?><?php echo $g; ?>"
				     onclick="showhidearrow('attribute_parameter_tr', '<?php echo $k ?><?php echo $g; ?>'); showhidearrow('sub_property', '<?php echo $k ?><?php echo $g; ?>')"
				     src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>arrow.png" alt="img"/>
				<span> <?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?></span>
			</td>
			<td class="td2" align="right">
				<input type="text"
				       class="text_area input_t1" size="22"
				       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][name]"
				       value="<?php echo htmlspecialchars(urldecode($property->property_name)); ?>">
			</td>
			<td align="right" nowrap="nowrap" class="td3">
				<span> <?php echo JText::_('COM_REDSHOP_ORDERING'); ?>:&nbsp;</span>

				<input style="margin: 0px;" type="text" class="text_area input_t4"
				       size="2"
				       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][order]"
				       value="<?php echo $property->ordering; ?>">
			</td>
			<td nowrap="nowrap" align="right" class="td4">
				<span> <?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>:</span>
				<input type="checkbox"
				       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][default_sel]"
					<?php
					if ($property->setdefault_selected == 1)
					{
						echo "checked";
					}
					?>>
			</td>
			<td align="right" class="td5">
				<span> <?php echo JText::_('COM_REDSHOP_PRICE'); ?>:&nbsp;</span>
				<input
					type="text" class="text_area input_t3" size="1"
					style="text-align: center;"
					id="oprand<?php echo $k . $g; ?>"
					value="<?php echo $property->oprand; ?>"
					name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][oprand]"
					maxlength="1"
					onchange="javascript:oprand_check(this);">
				<input
					type="text" class="text_area input_t2" size="8"
					value="<?php echo $property->property_price; ?>"
					name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][price]">
			</td>

			<td align="right" class="td6"><?php
				$property_id = $property->property_id;
				if ($property_id)
				{
					?> <span> <?php echo JText::_('COM_REDSHOP_PROPERTY_NUMBER'); ?>:&nbsp;
																			</span> <?php
				}
				?>
				<?php

				if ($property_id)
				{
					?> <input type="text"
					          value="<?php echo $property->property_number; ?>"
					          name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][number]"
					          class="vpnrequired input_t5"
					          size="14"><!-- &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_( 'COM_REDSHOP_TOOLTIP_PROPERTY_NUMBER' ), JText::_( 'COM_REDSHOP_PROPERTY_NUMBER' ), 'tooltip.png', '', '', false); ?> -->

				<?php
				}
				$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' . $k . $g . '&layout=thumbs');
				?>
			</td>

			<input type="hidden"
			       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][property_id]"
			       value="<?php echo $property_id; ?>">
			<input type="hidden" name="imagetmp[<?php echo $k; ?>][value][]"
			       value="<?php echo $property->property_image; ?>">
			<input type="hidden"
			       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][mainImage]"
			       id="propmainImage<?php echo $k . $g; ?>" value="">


			<?php
			$property_id = $property->property_id;
			if ($property_id)
			{
				$medialink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $property_id . '&showbuttons=1&media_section=property');
				/*index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;fsec=property&amp;section_id=<?php echo $property_id;?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;layout=property_images&amp;showbuttons=1 */

				?>

			<?php } ?>
			<td align="left" colspan="12" class="td7">
				<div class="repon">
					<table width="100%" border="0" cellspacing="0"
					       cellpadding="0" class="up_image">
						<?php
						$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' . $k . $g . '&layout=thumbs');
						$property_id = $property->property_id;
						?>
						<tr valign="top">

							<td rowspan="1" align="right" nowrap="nowrap" class="td1"
							    style="padding-right: 10px;"><a class="modal"
							                                    rel="{handler: 'iframe', size: {x: 950, y: 500}}"
							                                    title=""
							                                    href="<?php echo $medialink; ?>"> <img
										src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png"
										align="absmiddle" alt="media">
								</a> <a class="modal"
							            rel="{handler: 'iframe', size: {x: 950, y: 500}}"
							            title=""
							            href="index.php?tmpl=component&option=com_redshop&amp;view=attributeprices&amp;section_id=<?php echo $property_id; ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;section=property">
									<img
										src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>discountmanagmenet16.png"
										align="absmiddle" alt="media">
								</a> <a class="modal"
							            rel="{handler: 'iframe', size: {x: 950, y: 500}}"
							            title=""
							            href="index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;section_id=<?php echo $property_id; ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;layout=productstockroom&amp;property=property">
									<img
										src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>stockroom16.png"
										align="absmiddle" alt="media">
								</a>
							</td>

							<td class="td2" align="left"><span>
																								<div
																									class="button2-left">
																									<div class="image">
																										<a class="modal"
																										   title="Image"
																										   href="<?php echo $ilink; ?>"
																										   rel="{handler: 'iframe', size: {x: 900, y: 500}}"></a>
																									</div>
																								</div>
																						</span> <span> <input
										type="file"
										name="attribute_<?php echo $k; ?>_property_<?php echo $g; ?>_image"
										id="propfile<?php echo $k . $g; ?>"
										value="<?php echo $property->property_image; ?>">
																						</span>
							</td>
							<td class="td3" align="left"><?php $is_img = true;
								if ($property->property_image != "")
								{
								$property->property_image;
								$impath = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image;
								$impath_phy = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property->property_image;

								if (is_file($impath_phy))
								{
								$is_img = false;
								?> <span id="property_image_<?php echo $property->property_id; ?>">

																								<a class="modal"
																								   title="<?php echo $property->property_image; ?>"
																								   rel="{handler: 'image', size: {}}"
																								   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property->property_image; ?>">
																									<img
																										id="propertyImage<?php echo $k . $g; ?>"
																										alt='' title=''
																										src='<?php echo $url ?>components/com_redshop/helpers/thumb.php?filename=product_attributes/<?php echo $property->property_image ?>&newxsize=50&newysize=0&swap=1'>
																								</a>
																							</span>
							</td>
							<td class="td4" align="left">
								<input
									id="btn_attribute_remove_property_<?php echo $property->property_id; ?>"
									value="Remove"
									class="btn_attribute_remove" type='button'
									width="0"
									onclick="javascript:removePropertyImage('<?php echo $property->property_id; ?>','property');"/>

								<?php
								}
								}
								$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' . $k . $g . '&layout=thumbs');
								?>

								<?php if ($is_img == true) { ?>
								&nbsp;</td>
							<td class="td4" align="left">
								<img id="propertyImage<?php echo $k . $g; ?>" src=""
								     style="display: none;"/>
								<?php } ?>
							</td>

							<td align="right" class="td5" style="padding-right: 25px;">
								<!-- Begin: Implement VietNam Team Code -->
								<div>
									<span><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:&nbsp;</span>
									<input type="checkbox" class="text_area" size="55"
									       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][published]" <?php echo $property_published; ?>
									       value="1">
									<span><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>&nbsp;</span>
									<input type="text" class="text_area" size="8"
											name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][extra_field]" value="<?php echo $property->extra_field; ?>">
								</div>
								<!-- End -->

								<div class="remove_attr">
									<input value="Delete"
									       class="btn_attribute_remove" type='button'
									       width="0"
									       onclick="if(ajax_delete_property(<?php echo $attribute_id; ?>,<?php echo $property_id; ?>)){deleteRow_property('<?php echo 'property_table' . $property->property_id; ?>','property_table<?php echo $k; ?>','sub_attribute_table<?php echo $k . $g; ?>','<?php echo $k . $g; ?>');}"/>
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

			<!-- START MY CODE -->

			<?php $total_subattr = count($attibute_data['property'][$g]->subvalue); ?>

			<tr class="attribute_parameter_tr"
			    id="attribute_parameter_tr<?php echo $k . $g; ?>" <?php echo($total_subattr == 0 ? 'style="display:none"' : '') ?>>
				<td>
					<table width="100%" border="0" cellspacing="0"
					       cellpadding="0" class="attribute_parameter"
					       id="attribute_parameter<?php echo $property->property_id; ?>">
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0"
								       cellpadding="0">
									<tr valign="top">

										<div <?php echo $style ?>
											id='showsubproperty<?php echo $k . $g; ?>'>
											<td class="red_blue_blue td1" align="left">
												<span
													style="padding-left: 10px;"><?php echo JText::_('COM_REDSHOP_TITLE'); ?></span>

											</td>
											<td class="td2" align="right"><input class="input_t1" type="text"
											                                     name="attribute[<?php echo $k; ?>][property][<?php echo $g ?>][subproperty][title]"
											                                     size="22"
											                                     value="<?php echo (count($attibute_data['property'][$g]->subvalue) > 0) ? $attibute_data['property'][$g]->subvalue[0]->subattribute_color_title : ""; ?>">
											</td>
											<td class="td3">&nbsp;</td>
											<td nowrap="nowrap" align="right" class="td4">
												<span><?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_REQUIRED'); ?>
													:</span>
												<input type="checkbox"
												       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][req_sub_att]"
													<?php
													if ($property->setrequire_selected == 1)
													{
														echo "checked";
													}
													?>><!-- &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_( 'COM_REDSHOP_TOOLTIP_SUBATTRIBUTE_REQUIRED' ), JText::_( 'COM_REDSHOP_SUBATTRIBUTE_REQUIRED' ), 'tooltip.png', '', '', false); ?> -->
											</td>
											<td nowrap="nowrap" class="td5" align="right">
												<span><?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED'); ?>
													:</span>
												<input
													type="checkbox"
													name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][multi_sub_att]"
													<?php
													if ($property->setmulti_selected == 1)
													{
														echo "checked";
													}
													?>><!-- &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_( 'COM_REDSHOP_TOOLTIP_SUBATTRIBUTE_MULTISELECTED' ), JText::_( 'COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED' ), 'tooltip.png', '', '', false); ?> -->
											</td>
											<td align="right" class="td6">
												<span><?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
													:</span>
												<select
													name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][setdisplay_type]">
													<option value="dropdown"
														<?php if ($property->setdisplay_type == "dropdown")
														{
															echo "selected";
														}?>>
														Dropdown List
													</option>
													<option value="radio"
														<?php if ($property->setdisplay_type == "radio")
														{
															echo "selected";
														}?>>
														<?php echo JText::_('COM_REDSHOP_RADIOBOX'); ?>
													</option>

												</select>
											</td>

											<td class="td7"></td>
										</div>
									</tr>
								</table>
							</td>
						</tr>


					</table>
				</td>
			</tr>



			<tr class="sub_property" id="sub_property<?php echo $k . $g; ?>">
			<td style="padding:0px;">
			<table width="100%" border="0" cellspacing="0"
			       cellpadding="0" id="sub_property_table<?php echo $k . $g; ?>">
			<tr>
			<td valign="top"
			    style="border-right: 1px solid #CCCCCC;" class="td1">
				<a class="btn_attribute"
				   href="javascript:showpropertytitle('<?php echo $k . $g; ?>'); addsubproperty('sub_attribute_table<?php echo $k . $g; ?>','<?php echo $k; ?>','<?php echo $g; ?>')">
																					<span
																						id="new_sub_property"> <?php echo "+ ";
																						echo JText::_('COM_REDSHOP_NEW_SUB_PROPERTY'); ?>
																				</span>
				</a>
			</td>
			<td style="padding:0px;">
			<table width="100%" border="0" cellspacing="0"
			       cellpadding="0"
			       id="sub_attribute_table<?php echo $k . $g; ?>" class="sub_attribute_table">
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
					$ilink     = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&fsec=subproperty&fid=' . $k . $z . '&layout=thumbs');

					$subattribute_published = "";
					if ($subvalue->subattribute_published == 1)
						$subattribute_published = "checked='checked'";


					?>
					<tr>
						<td style="padding:0px;">
							<table width="100%" border="0"
							       style="border-bottom: 1px solid #CCCCCC;"
							       cellspacing="0" cellpadding="0"
							       id="sub_attribute_table<?php echo $subvalue->subattribute_color_id; ?>"
							       align="left" class="subattribute">
								<tr valign="top">

									<td class="td2" align="right" nowrap="nowrap">
										<?php echo JText::_('COM_REDSHOP_PARAMETER'); ?>:
										<input type="text"
										       class="text_area input_t2" size="8"
										       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][name]"
										       value="<?php echo htmlspecialchars(urldecode($subvalue->subattribute_color_name)); ?>">
									</td>

									<td class="td3" align="right" nowrap="nowrap">
										<span><?php echo JText::_('COM_REDSHOP_ORDERING'); ?>:</span>
										<input
											class="input_t4" type="text"
											name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][order]"
											size="2"
											value="<?php echo $subvalue->ordering; ?>"></td>
									<td class="td4" align="right" nowrap="nowrap">
										<span><?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED'); ?>:</span>
										<input
											type="checkbox"
											name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][chk_propdselected]"
											<?php if ($subvalue->setdefault_selected == 1)
											{
												echo "checked";
											}?>
											value="1"/></td>
									<td align="right" class="td5" nowrap="nowrap">
										<span><?php echo JText::_('COM_REDSHOP_PRICE'); ?>:</span>
										<input
											class="input_t3" type="text" class="text_area"
											size="1"
											name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][oprand]"
											maxlength="1"
											value="<?php echo $subvalue->oprand; ?>"
											style="text-align: center;"
											id="oprand<?php echo $k . $g; ?>"
											onchange="javascript:oprand_check(this);">
										<input
											class="input_t2" type="text" class="text_area"
											size="8"
											name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][price]"
											value="<?php echo $subvalue->subattribute_color_price; ?>">
									</td>

									<!-- START MY CODE -->
									<td align="right" class="td6"
									    nowrap="nowrap"><?php if ($subvalue->subattribute_color_id != 0)
										{
											?> <?php echo JText::_('COM_REDSHOP_SUBPROPERTY_NUMBER'); ?>:&nbsp;
										<?php } ?>
										<?php if ($subvalue->subattribute_color_id != 0)
										{
											?> <input type="text" size="14"
											          value="<?php echo $subvalue->subattribute_color_number; ?>"
											          name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][number]"
											          class="vpnrequired input_t5"><!-- &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_( 'COM_REDSHOP_TOOLTIP_SUBPROPERTY_NUMBER' ), JText::_( 'COM_REDSHOP_SUBPROPERTY_NUMBER' ), 'tooltip.png', '', '', false); ?> -->
										<?php } ?> <input type="hidden"
										                  name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][subproperty_id]"
										                  value="<?php echo $subvalue->subattribute_color_id; ?>">
										<input type="hidden"
										       name="imagetmp[<?php echo $k; ?>][subvalue][<?php echo $g; ?>][]"
										       value="<?php echo $subvalue->subattribute_color_image; ?>">
										<input type="hidden"
										       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][mainImage]"
										       id="subpropmainImage<?php echo $k . $z; ?>"
										       value="">
									</td>
									<!-- END MY CODE -->


									<td align="left" colspan="12" class="td7">

										<div class="repon">
											<table width="100%" border="0" cellspacing="0"
											       cellpadding="0" class="up_image">
												<tr valign="top">

													<td rowspan="3" align="right" nowrap="nowrap" class="td1"
													    style="padding-right: 10px;"><?php if ($subvalue->subattribute_color_id != 0)
														{
															$medialink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $subvalue->subattribute_color_id . '&showbuttons=1&media_section=subproperty');
															/*index3.php?option=com_redshop&amp;view=product_detail&amp;fsec=subproperty&amp;section_id=<?php echo $subvalue->subattribute_color_id;?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;layout=property_images&amp;showbuttons=1*/
															?>
															<a class="modal"
															   href="<?php echo $medialink; ?>"
															   rel="{handler: 'iframe', size: {x: 950, y: 500}}"
															   title=""><img
																	src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png"
																	align="absmiddle" alt="media"> </a> <a
															class="modal"
															rel="{handler: 'iframe', size: {x: 950, y: 500}}"
															title=""
															href="index.php?tmpl=component&option=com_redshop&amp;view=attributeprices&amp;section_id=<?php echo $subvalue->subattribute_color_id; ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;section=subproperty">
															<img
																src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>discountmanagmenet16.png"
																align="absmiddle" alt="media">
														</a> <a class="modal"
															    rel="{handler: 'iframe', size: {x: 950, y: 500}}"
															    title=""
															    href="index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;section_id=<?php echo $subvalue->subattribute_color_id; ?>&amp;cid=<?php echo $this->detail->product_id; ?>&amp;layout=productstockroom&amp;property=subproperty">
															<img
																src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>stockroom16.png"
																align="absmiddle" alt="media">
														</a> <?php } ?>
													</td>

													<td class="td2"><span>
																														<div
																															class="button2-left">
																															<div
																																class="image">
																																<a class="modal"
																																   title="Image"
																																   href="<?php echo $ilink; ?>"
																																   rel="{handler: 'iframe', size: {x: 900, y: 500}}"></a>
																															</div>
																														</div>
																												</span>
														<input type="file"
														       name="attribute_<?php echo $k; ?>_property_<?php echo $g; ?>_subproperty_<?php echo $sp; ?>_image"
														       value="<?php echo $subvalue->subattribute_color_image; ?>">

													</td>
													<td class="td3" align="left">
														<?php if ($subvalue->subattribute_color_image != "" && is_file($impathphy)) { ?>
														<span
															id="subproperty_image_<?php echo $subvalue->subattribute_color_id; ?>">
																															<a class="modal"
																															   rel="{handler: 'image', size: {}}"
																															   title="<?php echo $subvalue->subattribute_color_image; ?>"
																															   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subvalue->subattribute_color_image; ?>">
																																<img
																																	id="subpropertyImage<?php echo $k . $z; ?>"
																																	src='<?php echo $url ?>components/com_redshop/helpers/thumb.php?filename=subcolor/<?php echo $subvalue->subattribute_color_image; ?>&newxsize=50&newysize=0&swap=1'
																																	alt=''
																																	title=''>


																															</a>
																													</span>
													</td>

													<td class="td4" align="left">
														<input value="Remove" type='button' width="0"
														       onclick="javascript:removePropertyImage('<?php echo $subvalue->subattribute_color_id; ?>','subproperty');"
														       title="<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE'); ?>"
														       class="btn_attribute_remove"
														       id="btn_attribute_remove_subproperty_<?php echo $subvalue->subattribute_color_id; ?>"/>
														<?php } else { ?>
														&nbsp;</td>
													<td class="td4" align="left">
														<img id="subpropertyImage<?php echo $k . $z; ?>" src=""
														     style="display: none;"/>
														<?php } ?>
													</td>
													<td align="right" class="td5" style="padding-right: 25px;">
														<!-- Begin:Implement VietNam Team Code -->
														<div>
															<span><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
																:&nbsp;</span>
															<input type="checkbox" class="text_area" size="55"
															       name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][published]" <?php echo $subattribute_published; ?>
															       value="1">
															<span><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD'); ?>&nbsp;</span>
															<input type="text" class="text_area" size="8"
																	name="attribute[<?php echo $k; ?>][property][<?php echo $g; ?>][subproperty][<?php echo $sp; ?>][extra_field]" value="<?php echo $subvalue->extra_field; ?>">
														</div>
														<!-- End: -->
														<div class="remove_attr">
															<input
																value="Delete" class="btn_attribute_remove"
																type='button' width="0"
																onclick="if(ajax_delete_subproperty(<?php echo $subvalue->subattribute_color_id; ?>,<?php echo $property_id; ?>)){deleteRow_subproperty('<?php echo 'sub_attribute_table' . $subvalue->subattribute_color_id; ?>','sub_attribute_table<?php echo $k . $g; ?>','<?php echo $subvalue->subattribute_color_id; ?>');}"/>
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
					<td><input type="text" class="text_area" size="40"
					           name="property[<?php echo $k; ?>][subvalue][][]"
					           value="">
					</td>
					<td><span><?php echo JText::_('COM_REDSHOP_PRICE'); ?>
																						</span>
					</td>
					<td><input type="text" class="text_area" size="1"
					           name="oprand[<?php echo $k; ?>][subvalue][][]"
					           maxlength="1" value="+"
					           style="text-align: center;"
					           id="oprand<?php echo $k . $g; ?>"
					           onchange="javascript:oprand_check(this);">
					</td>
					<td><input type="text" class="text_area" size="12"
					           name="price[<?php echo $k; ?>][subvalue][][]"
					           value="">
					</td>
					<td><img id="subpropertyImage<?php echo $k . $z; ?>"
					         src="" style="display: none;"/> <span>
																								<div
																									class="button2-left">
																									<div class="image">
																										<a class="modal"
																										   title="Image"
																										   href="<?php echo $ilink; ?>"
																										   rel="{handler: 'iframe', size: {x: 900, y: 500}}"></a>
																									</div>
																								</div>
																						</span>

						<input type="file"
						       name="image[<?php echo $k; ?>][subvalue][][]"> <input
							value="Remove" class='button' type='button'
							width="0"
							onclick="deleteRow_subproperty(this,'sub_attribute_table<?php echo $k . $g; ?>');"/>
						<input type="hidden"
						       name="property_id[<?php echo $k; ?>][subvalue][][]"
						       value=""> <input type="hidden"
						                        name="imagetmp[<?php echo $k; ?>][subvalue][][]"
						                        value=""> <input type="hidden"
						                                         name="mainImage[<?php echo $k; ?>][subvalue][][]"
						                                         id="subpropmainImage<?php echo $k . $g; ?>" value="">
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

			</td>
			</tr>
			<!-- End MY CODE -->

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

		<?php $k++;
	} ?>

	<span id="delete_attribute" style="display: none;"><?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?></span>
	<span id="aimage" style="display: none"><?php echo JText::_('COM_REDSHOP_IMAGE_UPLOAD'); ?>
				</span>
	<span id="aproperty" style="display: none"><?php echo JText::_('COM_REDSHOP_SUB_ATTRIBUTE'); ?>
				</span>
	<span id="aprice" style="display: none"><?php echo JText::_('COM_REDSHOP_PRICE'); ?>
				</span>
	<tr>
		<td colspan="5"><input type="hidden" name="total_table"
		                       id="total_table" value="<?php echo $k; ?>"> <input type="hidden"
		                                                                          name="total_g" id="total_g"
		                                                                          value="<?php echo $g; ?>"> <input
				type="hidden" name="total_z" id="total_z" value="<?php echo $z; ?>">
		</td>
	</tr>
<?php
}
else
{
	$g = 1;
	$z = 1;
	?>
	<tr>
		<td colspan="5"><input type="hidden" name="total_table"
		                       id="total_table" value="<?php echo $k; ?>"> <input type="hidden"
		                                                                          name="total_g" id="total_g"
		                                                                          value="<?php echo $g; ?>"> <input
				type="hidden" name="total_z" id="total_z" value="<?php echo $z; ?>">
		</td>
	</tr>
<?php } ?>

</table>
</td>
</tr>
</table>
