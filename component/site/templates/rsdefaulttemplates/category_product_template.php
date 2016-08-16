<div>
	{category_loop_start}
	<div id="categories">
		<div class="categories_box">
			<div class="category_image">{category_thumb_image}</div>
			<div class="category_description">
				<h2 class="category_title">{category_name}</h2>
				{category_description}
			</div>
		</div>
	</div>
	<div class="category_box_wrapper row">
		{product_loop_start}
		<div class="category_box_outside col-sm-4">
			<div class="category_box_inside">
				<div class="category_product_image">{product_thumb_image}</div>
				<h3>{product_name}</h3>
				<div class="category_product_price">{product_price}</div>
				<div class="category_product_addtocart">{form_addtocart:add_to_cart1}</div>
			</div>
		</div>
		{product_loop_end}
	</div>
	{category_loop_end}
</div>
<div class="pagination">{pagination}</div>
