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
 * @param   object    $thumbUrl               Thumb url
 * @param   object    $imageUrl               Image url
 * @param   integer   $accessoryImage         Accessory image
 * @param   integer   $accessoryId            Accessory id
 * @param   integer   $accessoryWidthThumb    Accessory width thumb
 * @param   integer   $accessoryHeightThumb   Accessory height thumb

 */

extract($displayData);
?>

<a id='a_main_image<?php echo $accessoryId ?>' href='<?php echo $imageUrl ?>' title='' class="modal" rel="{handler: 'image', size: {}}">
	<img id='main_image<?php echo $accessoryId ?>' class='redAttributeImage' src='<?php echo $thumbUrl ?>' />
</a>
<input type='hidden' name='acc_main_imgwidth' id='acc_main_imgwidth' value='<?php echo $accessoryWidthThumb ?>'>
<input type='hidden' name='acc_main_imgheight' id='acc_main_imgheight' value='<?php echo $accessoryHeightThumb ?>'>
