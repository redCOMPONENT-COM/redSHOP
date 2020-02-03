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
 * @param   integer  $productId                  Product id
 * @param   integer  $relProductId               related product id
 * @param   integer  $accessoryId                Accessory id
 * @param   string   $accessoryChecked           Accessory checked
 * @param   string   $prefix                     Layout prefix
 * @param   string   $accessoryPriceWithoutVAT   Accessory price without vat
 * @param   string   $accessoryPrice             Accessory price
 * @param   string   $commonId                   common id
 *
 */
extract($displayData);
?>

<input type='checkbox'
       name='accessory_id_<?php echo $prefix . $productId ?>[]'
       onClick='calculateTotalPrice(<?php echo $productId ?>,<?php echo $relProductId ?>);'
       totalattributs='<?php echo count($attributes) ?>'
       accessoryprice='<?php echo $accessoryPrice ?>'
       accessorywithoutvatprice='<?php echo $accessoryPriceWithoutVAT ?>'
       id='accessory_id_<?php echo $commonId ?>'
       value='<?php echo $accessoryId ?>'
    <?php echo $accessoryChecked ?>
/>