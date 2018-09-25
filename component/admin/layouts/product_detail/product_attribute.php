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

<div class="mainTableAttributes form-inline" id="mainTableAttributes">
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

		<a href="#" class="showhidearrow">
			<?php echo JText::_('COM_REDSHOP_TITLE'); ?>
			<img class="arrowimg" src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>arrow.png" alt=""/>
		</a>

		<div class="attribute_table divInspectFromHideShow">
			<input type="hidden" name="<?php echo $attrPref; ?>[count_prop]"
			   class="count_prop" value="<?php echo count($attributeData['property']); ?>"/>
			<input type="hidden" value="<?php echo $keyAttr; ?>"
			   name="<?php echo $attrPref; ?>[key_attr]" class="key_attr"/>

			<div class="col-sm-12 oneAttribute">
				<div class="rowAttribute">
					<div class="col-sm-5">
						<div class="form-group">
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_NAME'); ?>
							</label>
						    	<input type="text"
							   class="form-control"
							   name="<?php echo $attrPref; ?>[name]"
							   value="<?php echo $attributeData['attribute_name']; ?>"/>
						  </div>

						  <div class="form-group">
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE'); ?>
							</label>
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

						<div class="form-group">
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?>
							</label>
						    <input class="form-control" type="text" name="<?php echo $attrPref; ?>[attribute_description]"
							   value="<?php echo $attributeData['attribute_description']; ?>"/>
						</div>
					</div>
				
					
					<div class="col-sm-3">
						<div class="form-group">
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
							</label>
						    <input class="text-center input-xmini" type="text" name="<?php echo $attrPref; ?>[ordering]"
							   value="<?php echo $attributeData['ordering']; ?>"/>
						</div>	
					</div>

					<div class="col-sm-4">
						
						<div class="form-group">
							<input type="checkbox" name="<?php echo $attrPref; ?>[required]" <?php echo $checkedRequired; ?> value="1"/>
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED'); ?>
							</label>
						    
						</div>

						<div class="form-group">
							<input type="checkbox" value="1" name="<?php echo $attrPref; ?>[allow_multiple_selection]" <?php echo $multipleSelection; ?> />
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION'); ?>
							</label>
						    
						</div>

						<div class="form-group">
							<input type="checkbox" value="1" name="<?php echo $attrPref; ?>[hide_attribute_price]" <?php echo $hideAttributePrice; ?> />
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE'); ?>
							</label>
						   
						</div>

						<div class="form-group">
							<input type="checkbox" name="<?php echo $attrPref; ?>[published]" <?php echo $attributePublished; ?> value="1"/>
						    <label>
						    	<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
							</label>
						    
						</div>
					</div>
				</div>

				<input class="btn btn-danger delete_attribute btn-small"
						   id="deleteAttribute_<?php echo $attributeId; ?>_<?php
							echo $productId; ?>_<?php
							echo $attributeSetId; ?>"
						   value="<?php echo JText::_('COM_REDSHOP_DELETE_ATTRIBUTE'); ?>" type="button"/>
				<input type="hidden" name="<?php echo $attrPref; ?>[id]" value="<?php echo $attributeData['attribute_id']; ?>"/>
			</div>

		<div class="property_table">
			<a class="btn btn-success add_property btn-small">
				+ <?php echo JText::_('COM_REDSHOP_ADD_SUB_ATTRIBUTE'); ?>
			</a>
			<?php
			foreach ($attributeData['property'] as $keyProperty => $property)
			{
				$propPref = $attrPref . '[property][' . $keyProperty . ']';

				echo RedshopLayoutHelper::render(
					'product_detail.product_property', 
					array(
						'keyAttr' => $keyAttr,
						'keyProperty' => $keyProperty,
						'property' => $property,
						'propPref' => $propPref,
						'productId' => $productId,
						'data' => $data
					)
				);
			}
			?>
		</div>
		</div>
			
	<?php
	}
}
?>
</div>
