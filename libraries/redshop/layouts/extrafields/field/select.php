<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	<select 
		name="<?php echo $rowData->name; ?>" 
		class="<?php echo $rowData->class; ?>" 
		id="<?php echo $rowData->name; ?>"
		<?php echo $required; ?>
		<?php echo $requiredLabel; ?>
		<?php echo $errorMsg; ?>
	>
		<option><?php echo JText::_('COM_REDSHOP_SELECT'); ?></option>
		<?php foreach ($fieldCheck as $key => $field) : ?>
			<?php $selected = (!empty($checkData) && (in_array(urlencode($field->field_value), $checkData)) ||
				in_array($field->field_value, $checkData)) ? ' selected="selected" ' : ''; ?>
			<option <?php echo $selected; ?> value="<?php echo $field->field_value; ?>"><?php echo $field->field_name; ?></option>
		<?php endforeach; ?>
	</select>
</td>	
