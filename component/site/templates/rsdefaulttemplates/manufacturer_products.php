<div class="manufacturer_name">{manufacturer_name}</div>
<div class="manufacturer_image">{manufacturer_image}</div>
<div class="manufacturer_description">{manufacturer_description}</div>
<div id="category_header">
	<div class="category_order_by">{order_by}</div>
</div>
<div class="category_box_wrapper">
	{product_loop_start}
	<div class="category_box_outside">
		<div class="category_box_inside">

			<table border="0">
				<tbody>
				<tr>
					<td colspan="2">
						<div class="category_product_image">{product_thumb_image}</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="category_product_title">
							<h3>{product_name}</h3>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="category_product_price">{product_price}</div>
					</td>
					<td>
						<div class="category_product_addtocart">{form_addtocart:add_to_cart1}</div>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	{product_loop_end}
</div>
<div class="category_pagination">{pagination}</div>
