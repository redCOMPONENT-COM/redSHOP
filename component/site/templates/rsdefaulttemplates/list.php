<h1 class="category_main_title">{category_main_name}</h1>
<div class="category_main_toolbar">
	<div>{filter_by_lbl}{filter_by}</div>
	<div>{order_by_lbl}{order_by}</div>
	<div>{template_selector_category_lbl}{template_selector_category}</div>
</div>
{if subcats}
<div id="categories">
	{category_loop_start}
	<div class="categories_box">
		<div class="category_image">{category_thumb_image}</div>
		<div class="category_description">
			<h2 class="category_title">{category_name}</h2>
		</div>
		<div class="category_description">{category_readmore}</div>
		<div class="category_description">{category_description}</div>
	</div>
	{category_loop_end}
	<div class="clr"></div>
</div>
{subcats end if}
<div class="clr"></div>
<div class="category_box_wrapper">{product_loop_start}
	<div class="category_box_outside_row">
		<div class="category_product_image">{product_thumb_image}</div>
		<div class="category_product_name">
			<h3>{product_name}</h3>

			<p>{product_rating_summary}</p>

			<p>{product_s_desc}</p>
		</div>
		<div class="category_product_container">
			<div class="category_product_price">{product_price}</div>
			<div id="add_to_cart_all">{form_addtocart:add_to_cart1}</div>
		</div>
	</div>
	{product_loop_end}
</div>
<div class="pagination">{pagination}</div>