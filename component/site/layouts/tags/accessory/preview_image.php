<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   int     $accessoryId     Accessory ID
 * @param   string  $imageUrl        Image URL
 * @param   object  $productInfo     Product information
 */
extract($displayData);
?>

<img id="accessory_preview_image_<?php echo $accessoryId; ?>" src="<?php echo $imageUrl; ?>" alt="<?php echo $productInfo->product_name; ?>">
