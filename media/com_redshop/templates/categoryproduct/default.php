<div>
	<div>{print}</div>
	<div>{filter_by_lbl}{filter_by}</div>
	<div>{order_by_lbl}{order_by}</div>
	<p>{category_loop_start}</p>

	<div style="border: 1px solid;">
		<div id="categories">
			<div style="width: 200px;">
				<div class="category_image">{category_thumb_image}</div>
				<div class="category_description">
					<h4 class="category_title">{category_name}</h4>
					{category_description}
				</div>
			</div>
		</div>
		<div class="category_box_wrapper clearfix">{product_loop_start}
			<div class="category_product_box_outside">
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
	</div>
	<p>{category_loop_end}</p>

	<div class="pagination">{pagination}</div>
</div>
