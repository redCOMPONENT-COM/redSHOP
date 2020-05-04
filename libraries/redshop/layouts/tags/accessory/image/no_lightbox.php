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
 * @param   string   $accessoryProductLink  Accessory product link
 * @param   string   $thumbUrl              Thumb url
 * @param   integer  $accessoryId           Accessory Id
 * @param   integer  $accessoryWidthThumb   Accessory width thumb
 * @param   integer  $accessoryHeightThumb  Accessory height thumb
 */
extract($displayData);
?>

<a href='<?php echo $accessoryProductLink ?>'>
    <img id='main_image<?php echo $accessoryId ?>' class='redAttributeImage' src='<?php echo $thumbUrl ?>'/>
</a>
<input type='hidden' name='acc_main_imgwidth' id='acc_main_imgwidth' value='<?php echo $accessoryWidthThumb ?>'>
<input type='hidden' name='acc_main_imgheight' id='acc_main_imgheight' value='<?php echo $accessoryHeightThumb ?>'>
