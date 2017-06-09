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
 * @param   string  $required         Extra field required
 * @param   string  $uniqueId         Extra field unique Id
 * @param   string  $fieldCheck       Extra field check
 * @param   string  $checkData        Extra field check data
 */
extract($displayData);
?>
<?php foreach ($fieldCheck as $key => $field) : ?>
	<?php $checked = (@in_array(urlencode($field->field_value), $checkData)) ? ' checked="checked" ' : ''; ?>
	<div class="userfield_input">
		<label>
			<input 
				type="radio"
				id="<?php echo $rowData->name . '_' . $field->value_id; ?>"
				name="extrafieldname<?php echo $uniqueId; ?>[]"
				class="<?php echo $rowData->class; ?>"
				value="<?php echo urlencode($field->field_value); ?>"
				userfieldlbl="<?php echo $rowData->title; ?>"
				<?php echo $required; ?>
				<?php echo $checked; ?>
			/>
			<span><?php echo $field->field_value ?></span>
		</label>
	</div>
<?php endforeach; ?>
