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
 */
extract($displayData);
?>

<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel; ?>
</td>
<td>
	<table>
		<tr>
			<?php foreach ($fieldCheck as $key => $field) : ?>
				<?php if (in_array($field->value_id, $checkData)): ?>
					<?php $class = 'class="pointer imgClass_' . $sectionId . ' selectedimg"'; ?>
				<?php else: ?>
					<?php $class = 'class="pointer imgClass_' . $sectionId . '"'; ?>
				<?php endif; ?>
				<td>
					<div class="userfield_input">
						<img 
							id="<?php echo $field->value_id; ?>"
							name="imgField[]"
							src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field->field_name; ?>"
							title="<?php echo $field->field_value; ?>"
							alt="<?php echo $field->field_value; ?>"
							onclick="javascript:setProductUserFieldImage(<?php echo $field->value_id; ?>, <?php echo $sectionId; ?>, <?php echo $field->field_id; ?>, this);"
							<?php echo $class; ?>
						>
					</div>
				</td>
			<?php endforeach; ?>
		</tr>
		<input 
			type="hidden"
			name="imgFieldId<?php echo $rowData->id; ?>"
			id="imgFieldId<?php echo $rowData->id; ?>"
			value="<?php echo $value; ?>"
		>
	</table>
</td>
