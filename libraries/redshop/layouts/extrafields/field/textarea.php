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
 * @param   string  $textValue        Extra field value
 */
extract($displayData);
?>

<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel; ?>
</td>
<td>
	<textarea
		id="<?php echo $rowData->name; ?>"
		name="<?php echo $rowData->name; ?>"
		class="<?php echo $rowData->class; ?>"
		cols="<?php echo $rowData->cols; ?>"
		rows="<?php echo $rowData->rows; ?>"
	>
		<?php echo htmlspecialchars($textValue); ?>
	</textarea>
</td>
