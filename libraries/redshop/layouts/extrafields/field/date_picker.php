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
 * @var   array  $displayData     Extra field data
 * @var   object $rowData         Extra field data
 * @var   string $extraFieldLabel Extra field label
 * @var   string $required        Extra field required
 * @var   string $requiredLabel   Extra field required label
 * @var   string $errorMsg        Extra field error message
 * @var   string $date            Extra field date
 */
extract($displayData);
?>

<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel ?>
</td>
<td>
	<?php echo JHtml::_(
		'redshopcalendar.calendar',
		$date,
		$rowData->name,
		$rowData->name,
		null,
		array('class' => 'form-control', 'size' => $rowData->size > 0 ? $rowData->size : 20, 'maxlength' => '15')
	);
	?>
</td>
