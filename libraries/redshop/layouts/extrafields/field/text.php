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
 * @var   array   $displayData      Layout data
 * @var   object  $rowData          Extra field data
 * @var   string  $extraFieldLabel  Extra field label
 * @var   string  $required         Extra field required
 * @var   string  $requiredLabel    Extra field required label
 * @var   string  $errorMsg         Extra field error message
 * @var   string  $textValue        Extra field value
 */
extract($displayData);
?>

<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel; ?>
</td>
<td>
	<input 
		type="text"
		id="<?php echo $rowData->name; ?>"
		name="<?php echo $rowData->name; ?>"
		class="<?php echo $rowData->class; ?>"
		maxlength="<?php echo $rowData->maxlength ?>"
		size="<?php echo $rowData->size > 0 ? $rowData->size : 20; ?>"
		value="<?php echo htmlspecialchars($textValue); ?>"
		<?php echo $required; ?>
		<?php echo $requiredLabel; ?>
		<?php echo $errorMsg; ?>
	/>
</td>
