<div class="gd_header"><h4>You may also interested in this/these product(s)</h4></div>
<div class="gd_content clearfix">
	<div class="related_product_wrapper category_box_wrapper row grid">
		{related_product_start}
		<div class="category_box_outside col-sm-6 col-md-4">
			<div class="category_box_inside">
				<div class="category_product_image">{relproduct_image}</div>
				<div class="category_product_title"><h3><a href="{read_more_link}">{relproduct_name}</a></h3></div>
				<div class="category_product_price">
					{if product_on_sale}
					<div class="category_product_oldprice">
						{relproduct_old_price}
					</div>
					{product_on_sale end if}

					{relproduct_price}

				</div>
				<div class="category_product_addtocart">{form_addtocart:add_to_cart1}</div>
			</div>
		</div>

		{related_product_end}
	</div>
</div>