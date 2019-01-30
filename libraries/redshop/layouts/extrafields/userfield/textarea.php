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
 * @var   object  $rowData   Extra field data
 * @var   string  $uniqueId  Extra field unique Id
 * @var   string  $required  Extra field required
 */
extract($displayData);
?>

<div class="userfield_input">
	<textarea
		name="extrafieldname<?php echo $uniqueId; ?>[]"
		class="<?php echo $rowData->class; ?>"
		id="<?php echo $rowData->name; ?>"
		maxlength="<?php echo $rowData->maxlength; ?>"
		cols="<?php echo $rowData->cols; ?>"
		rows="<?php echo $rowData->rows; ?>"
		userfieldlbl="<?php echo $rowData->title; ?>"
		onkeyup="var f_value = this.value;"
		<?php echo $required; ?>
	>
	</textarea>
</div>
