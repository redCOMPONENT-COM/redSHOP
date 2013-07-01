<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
?>
<table style="width: 100%;" class="tbl_buynow" id="tblplan" border="0" cellpadding="0px" cellspacing="0px">
	<tbody>
	<tr>
		<td colspan="8">
			<div>{subscription_detail_frontpage_introtext}</div>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<div></div>
		</td>
		<td>
			<div>{subscription_name:15}</div>
		</td>
		<td>
			<div>{subscription_name:16}</div>
		</td>
		<td colspan="3">
			<div>{subscription_single_sale}</div>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<!-- <div>{add_check_to_favourites}</div> -->
		</td>
		<td>
			<div>{subscription_add_to_cart:15}</div>
		</td>
		<td>
			<div>{subscription_add_to_cart:16}</div>
		</td>
		<td colspan="3">
			<!-- <div>{subscription_buy_check}</div> -->
		</td>
	</tr>
	<tr>
		<td>
			<div>{subscription_product_type_lb}</div>
		</td>
		<td colspan="2">
			<div>{subscription_product_feature_lb}</div>
		</td>
		<td>
			<div>{subscription_price:15}</div>
		</td>
		<td>
			<div>{subscription_price:16}</div>
		</td>
		<td colspan="3">
			<div>{from_single_sale_label}</div>
		</td>
	</tr>
	<!-- {subscription_product_main_loop_start} -->
	<tr>
		<td>{subscription_product_main_type}</td>
		<td><strong>{subscription_product_main_name}</strong><br/>
			<span>{subscription_product_filename_main}</span><br>
			<span>{subscription_product_release_date_main}</span>
		</td>
		<td>
			<div>{subscription_product_main_icon}</div>
		</td>
		<td>
			<div>{check_product_main_in_subscription:15}</div>
		</td>
		<td>
			<div>{check_product_main_in_subscription:16}</div>
		</td>
		<td>
			<div>{subscription_product_main_price}</div>
		</td>
		<td>
			<div>{subscription_product_main_add_to_cart}</div>
		</td>
		<!-- <td>{subscription_product_main_checkbox}</td> -->
	</tr>
	<!-- {subscription_product_main_loop_end} -->
	<!-- {subscription_category_loop_start} -->
	<tr>
		<td colspan="8">
			<strong>{subscription_category_name}</strong>
		</td>
	</tr>
	<!-- {product_subscription_category_loop_start} -->
	<tr>
		<td>{subscription_product_category_type}</td>
		<td>{subscription_product_category_name}<br>
			<span>{subscription_product_filename}</span><br>
			<span>{subscription_product_release_date}</span>
		</td>
		<td>
			<div>{subscription_product_category_icon}</div>
		</td>
		<td>
			<div>{check_product_category_in_subscription:15}</div>
		</td>
		<td>
			<div>{check_product_category_in_subscription:16}</div>
		</td>
		<td>
			<div>{subscription_product_category_price}</div>
		</td>
		<td>
			<div>{subscription_product_category_add_to_cart}</div>
		</td>
		<!-- <td>{subscription_product_category_checkbox}</td> -->
	</tr>
	<!-- {product_subscription_category_loop_end} -->
	<!-- {subscription_category_loop_end} -->
	<tr>
		<td colspan="3">
			<div></div>
		</td>
		<td>{subscription_price:15}</td>
		<td>{subscription_price:16}</td>
		<td id="total_id">$0</td>
	</tr>
	</tbody>
</table>

