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
 * @var   object  $rowData       Extra field data
 * @var   string  $required      Extra field required
 * @var   string  $uniqueId      Extra field unique Id
 * @var   array   $fieldCheck    Extra field check
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
