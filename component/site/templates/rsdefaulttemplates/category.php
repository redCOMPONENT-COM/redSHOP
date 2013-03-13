<div>{print}</div>
<div>{filter_by_lbl}{filter_by}</div>
<div>{redproductfinderfilter_formstart} {redproductfinderfilter:rp_myfilter} {redproductfinderfilter_formend}</div>
<div>{order_by_lbl}{order_by}</div>
<div>{template_selector_category_lbl}{template_selector_category}</div>
<div>{product_price_slider}</div>
<p></p>
<div class="category_main_description">{category_main_name}</div>
<div class="category_main_description">{category_main_thumb_image}</div>
<div class="category_main_description">{category_main_description}</div>
<div>{if subcats}
	<div>{category_loop_start}
		<div id="categories">
			<div style="float: left; width: 200px;">
				<div class="category_image">{category_thumb_image}</div>
				<div class="category_description">
					<h2 class="category_title">{category_name}</h2>
				</div>
				<div class="category_description">{category_readmore}</div>
				<div class="category_description">{category_description}</div>
			</div>
		</div>
		{category_loop_end}
	</div>
	{subcats end if}
</div>
<div class="category_box_wrapper">{product_loop_start}
	<div class="category_box_outside">
		<div class="category_box_inside">
			<table border="0">
				<tbody>
				<tr>
					<td>
						<div class="category_product_image">{product_thumb_image}</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="category_product_title">
							<h3>{product_name}</h3>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="category_product_price">{product_price}</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="category_product_readmore">{read_more}</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="category_product_addtocart">{attribute_template:attributes}</div>
					</td>
				</tr>
				<tr>
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
