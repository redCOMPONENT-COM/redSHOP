<div>
	<div class="category_main_toolbar">
		<div class="category_main_title">{category_main_name}</div>
		<!-- <div>{filter_by_lbl}{filter_by}</div>-->
		<div>{order_by_lbl}{order_by}</div>
		<div>{template_selector_category_lbl}{template_selector_category}</div>
	</div>
	<div class="clear"> </div>
	<div class="category_box_wrapper">{product_loop_start}
		<div class="category_box_outside_row">
			<div class="category_product_image">{product_thumb_image}</div>
			<div class="category_product_name">
				<h3>{product_name}</h3>

				<p>{product_rating_summary}</p>

				<p>{product_s_desc}</p>
			</div>
			<div class="category_product_container">
				<table>
					<tbody>
					<tr>
						<td>
							<div class="category_product_price">{product_price}</div>
						</td>
						<td>
							<div id="add_to_cart_all">{form_addtocart:add_to_cart1}</div>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		{product_loop_end}
	</div>
	<div class="category_pagination">{pagination}</div>
</div>