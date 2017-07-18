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
 */
extract($displayData);
?>

<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel; ?>
</td>
<td>
	<?php foreach ($fieldCheck as $key => $field) : ?>
		<?php $checked = (!empty($checkData) &&in_array(urlencode($field->field_value), $checkData)) ? ' checked="checked" ' : ''; ?>
		<label>
			<input 
				type="checkbox"
				id="<?php echo $rowData->name . '_' . $field->value_id; ?>"
				name="<?php echo $rowData->name; ?>"
				class="<?php echo $rowData->class; ?>"
				value="<?php echo urlencode($field->field_value); ?>"
				<?php echo $required; ?>
				<?php echo $requiredLabel; ?>
				<?php echo $errorMsg; ?>
				<?php echo $checked; ?>
			/>
			<span><?php echo $field->field_value ?></span>
		</label>
	<?php endforeach; ?>
</td>