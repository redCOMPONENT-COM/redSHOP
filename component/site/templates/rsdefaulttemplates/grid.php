<h1 class="category_main_title">{category_main_name}</h1>
<div class="category_main_toolbar row">
	<div class="col-sm-3">{total_product_lbl}: {total_product}</div>
	<div class="col-sm-3">{filter_by_lbl}{filter_by}</div>
	<div class="col-sm-4">{order_by_lbl}{order_by}</div>
	<div class="col-sm-2">{template_selector_category_lbl}{template_selector_category}</div>
</div>
{if subcats}
<div class="category_front_wrapper row">
	{category_loop_start}
	<div class="category_front col-sm-4">
		<div class="category_front_inside">
			<div class="category_front_image">{category_thumb_image}</div>
			<div class="category_front_title">
				<h3>{category_name}</h3>
			</div>
		</div>
	</div>
	{category_loop_end}
</div>
{subcats end if}
<div class="clr"></div>
<div class="category_box_wrapper row grid">
	{product_loop_start}
	<div class="category_box_outside col-sm-6 col-md-4">
		<div class="category_box_inside">
			<div class="category_product_image">{product_thumb_image}</div>
			<div class="category_product_title"><h3>{product_name}</h3></div>
			<div class="category_product_price">
				{if product_on_sale}
				<div class="category_product_oldprice">
					{product_old_price}
				</div>
				{product_on_sale end if}

				{product_price}

			</div>
			<div class="category_product_addtocart">{form_addtocart:add_to_cart1}</div>
		</div>
	</div>
	{product_loop_end}
</div>
<div class="pagination">{pagination}</div>
