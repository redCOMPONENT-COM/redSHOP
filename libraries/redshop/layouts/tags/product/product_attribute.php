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
 * @param   int   $productId          Product id
 * @param   int   $propertyId         Property id
 * @param   int   $subPropertyId      Sub property id
 * @param   bool  $isAjax             Layout use for ajax request
 * @param   array $productStockStatus Product status array
 */
extract($displayData);

?>
<?php if (!empty($productAttribute)) : ?>
<div id="product_attribute_id" class="product_attribute_class">
	<?php echo $productAttribute ?>
</div>
<?php endif; ?>
