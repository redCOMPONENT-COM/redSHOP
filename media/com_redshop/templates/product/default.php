<div class="product_detail">
	<div class="gd_navigation">
		<span class="gd_nav_left">{navigation_link_left}</span>
		<span class="gd_nav_right">{navigation_link_right}</span>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="product_image">{product_thumb_image}</div>

			<div class="product_more_images">{more_images}</div>

			<div class="redshop_links">

				<div class="manufacturer_link">{manufacturer_link}</div>

				<div class="wishlist_link">
					{wishlist_link}
				</div>

				<div class="ask_question_about_product">
					{ask_question_about_product}
				</div>

				<div class="product_writereview">{form_rating}</div>

				{compare_product_div}

			</div>
		</div>

		<div class="col-sm-6">
			<div class="product_title">
				<h1>{product_name}</h1>
			</div>

			<div class="product_rating_summary">{product_rating_summary}</div>

			<div class="stock_status">{stock_status:instock:outofstock}</div>

			<div class="product_price" id="product_price">
				{if product_on_sale}
				<div class="product_oldprice">
					{product_old_price}
				</div>
				{product_on_sale end if}

				{product_price}
				<span class="vat_info">{vat_info}</span>
			</div>

			{attribute_template:attributes}

			<div class="product_desc_short">{product_s_desc}</div>

			<div class="product_addtocart">
				{form_addtocart:add_to_cart1}
			</div>

			<div class="compare_products_button">
				{compare_products_button}
			</div>

			{accessory_template:accessory}

		</div>
	</div>
	<div class="product_desc_full">{product_desc}</div>
	{related_product:related_products}
</div>