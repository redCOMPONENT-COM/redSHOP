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
 * @var   array   $displayData   Layout data.
 * @var   object  $rowData          Extra field data
 * @var   string  $required         Extra field required
 * @var   string  $uniqueId         Extra field unique Id
 * @var   array   $fieldCheck       Extra field check
 * @var   string  $checkData        Extra field check data
 */
extract($displayData);
?>
<div class="userfield_input">
	<select
		name="extrafieldname<?php echo $uniqueId; ?>[]"
		class="<?php echo $rowData->class; ?>"
		id="<?php echo $rowData->name; ?>"
		userfieldlbl="<?php echo $rowData->title; ?>"
		multiple="multiple"
		size="10"
		<?php echo $required; ?>
	>
	<option><?php echo JText::_('COM_REDSHOP_SELECT'); ?></option>
	<?php foreach ($fieldCheck as $key => $field) : ?>
		<?php $selected = (!empty($checkData) && in_array(urlencode($field->field_value), $checkData)) ? ' selected="selected" ' : ''; ?>
		<option <?php echo $selected; ?> value="<?php echo urlencode($field->field_value); ?>"><?php echo $field->field_name; ?></option>
	<?php endforeach; ?>
	</select>
</div>
