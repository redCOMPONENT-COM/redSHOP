<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Plugin.Content.Redshop_Product
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;
extract($displayData);
?>
<div class="mod_redshop_products">
	<table border="0">
		<tbody>
			<tr>
				<td>
					<div class="mod_redshop_products_image">{product_thumb_image}</div></td></tr><tr><td><div class="mod_redshop_products_title">{product_name}</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="mod_redshop_products_price">{product_price}</div></td></tr><tr><td><div class="mod_redshop_products_readmore">{read_more}</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>{attribute_template:attributes}</div></td></tr><tr><td><div class="mod_redshop_product_addtocart">{form_addtocart:add_to_cart1}</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
