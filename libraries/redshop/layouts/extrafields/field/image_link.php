<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object  $rowData          Extra field data
 * @param   string  $extraFieldLabel  Extra field label
 * @param   string  $required         Extra field required
 * @param   string  $requiredLabel    Extra field required label
 * @param   string  $errorMsg         Extra field error message
 * @param   string  $fieldCheck       Extra field check
 * @param   string  $checkData        Extra field check data
 * @param   string  $value            Extra field value
 * @param   string  $sectionId        Extra field section Id
 * @param   string  $imageLink        Extra field image link
 * @param   string  $imageHover       Extra field image hover
 */
extract($displayData);
?>

<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel; ?>
</td>
<td>
	<table>
		<?php foreach ($fieldCheck as $key => $field) : ?>
			<tr>
				<?php if (in_array($fieldCheck[$c]->value_id, $checkData)): ?>
					<?php $class = 'class="pointer imgClass_' . $sectionId . ' selectedimg"'; ?>
					<?php $style = 'display: block;'; ?>
					<?php $strImageLink = $imageLink[$field->value_id]; ?>
					<?php $altText = $imageHover[$field->value_id]; ?>
				<?php else: ?>
					<?php $class = 'class="pointer imgClass_' . $sectionId . '"'; ?>
					<?php $style = 'display: none;'; ?>
					<?php $strImageLink = ''; ?>
					<?php $altText = ''; ?>
				<?php else: ?>
				<?php endif; ?>
				<td>
					<div class="userfield_input">
						<img 
							id="<?php echo $field->value_id; ?>"
							name="imgfield[]"
							src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field->field_name; ?>"
							title="<?php echo $field->field_value; ?>"
							alt="<?php echo $field->field_value; ?>"
							onclick="javascript:setProductUserFieldImage(<?php echo $field->value_id; ?>, <?php echo $sectionId; ?>, <?php echo $field->field_id; ?>, this);"
							<?php echo $class; ?>
						>
					</div>
				</td>
				<td>
					<div id="hover_link<?php echo $field->value_id; ?>" style="<?php echo $style; ?>">
						<table>
							<tr>
								<td valign="top" width="100" align="right" class="key">
									<?php echo JText::_('COM_REDSHOP_IMAGE_HOVER'); ?>
								</td>
								<td>
									<input 
										type="text"
										name="image_hover<?php echo $field->field_id; ?>"
										value="<?php $altText; ?>"
									>
								</td>
							</tr>
							<tr>
								<td valign="top" width="100" align="right" class="key">
									<?php echo JText::_('COM_REDSHOP_IMAGE_LINK'); ?>
								</td>
								<td>
									<input 
										type="text"
										name="image_link<?php echo $field->field_id; ?>"
										value="<?php $strImageLink; ?>"
									>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
		<input 
			type="hidden"
			name="imgFieldId<?php echo $rowData->id; ?>"
			id="imgFieldId<?php echo $rowData->id; ?>"
			value="<?php echo $value; ?>"
		>
	</table>
	
