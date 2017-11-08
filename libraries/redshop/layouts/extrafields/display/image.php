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
 * @param   array   $link       Image selected
 * @param   array   $hover      Image hover
 * @param   object  $value      Image value
 * @param   string  $imageLink  Image link
 * @param   object  $data       Customfield data
 */
extract($displayData);
$imgLink = !empty($link[$value->value_id]) ? 'href="' . $link[$value->value_id] . '"' : '';
?>
<a class="imgtooltip" <?php echo $imgLink; ?>>
	<img src="<?php echo $imageLink ?>" title="<?php echo $value->field_value; ?>" alt="<?php echo $value->field_value; ?>">
	<span>
		<div class="spnheader">
			<?php echo $data->title; ?>
		</div>
		<div class="spnalttext">
			<?php echo $hover[$value->value_id]; ?>
		</div>
	</span>
</a>
