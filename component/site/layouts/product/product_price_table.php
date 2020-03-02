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
 * @param   object  $result     Quantity and Price list
 * @param   int     $productId  Product id
 * @param   int     $userId     User id
 */

extract($displayData);
?>
<table>
	<tr>
		<th><?php echo JText::_('COM_REDSHOP_QUANTITY'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_PRICE'); ?></th>
	</tr>
	<?php foreach ($result as $key => $value): ?>
		<?php if ($value->discount_price != 0 && $value->discount_start_date != 0 && $value->discount_end_date != 0 && $value->discount_start_date <= time() && $value->discount_end_date >= time()) : ?>
			<?php $value->product_price = $value->discount_price; ?>
		<?php endif; ?>
		<?php 
		$tax = RedshopHelperProduct::getProductTax($productId, $value->product_price, $userId);
		$price = RedshopHelperProductPrice::formattedPrice($value->product_price + $tax);
		?>
		<tr>
			<td><?php echo $value->price_quantity_start; ?> - <?php echo $value->price_quantity_end; ?></td>
			<td><?php echo $price; ?></td>
		</tr>
	<?php endforeach; ?>
</table>