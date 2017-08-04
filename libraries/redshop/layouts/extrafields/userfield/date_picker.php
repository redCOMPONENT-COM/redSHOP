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
 */
extract($displayData);
?>
<div class="userfield_input">
	<?php
		echo JHtml::_(
			'calendar',
			'',
			'extrafieldname' . $uniqueId . '[]',
			$rowData->name . '_' . $field->value_id,
			'%d-%m-%Y',
			array(
				'class' => 'inputbox',
				'size' => $rowData->size,
				'maxlength' => $rowData->maxlength,
				'userfieldlbl' => $rowData->title,
				'required' => $required,
				'errormsg' => ''
			)
		);
	?>
</div>
