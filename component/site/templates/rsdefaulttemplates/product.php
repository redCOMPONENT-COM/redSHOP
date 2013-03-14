<div class="product">
	<div class="gd_navigation"><span class="gd_nav_left">{navigation_link_left}</span><span class="gd_nav_right">{navigation_link_right}</span>
	</div>

	<div class="redSHOP_product_box clearfix">
		<div class="redSHOP_product_box_left">
			<div class="product_image">{product_thumb_image}</div>
			<div class="product_more_images">{more_images}</div>
			<div class="redSHOP_links">
				<span>{ask_question_about_product}</span>

				<div>{manufacturer_link}</div>
			</div>
		</div>
		<div class="redSHOP_product_box_right">
			<div class="redSHOP_product_detail_box">
				<div class="product_title clearfix">
					<h3>{product_name}</h3>
				</div>
				<div class="product_desc_short">{product_s_desc}</div>
			</div>

			<div class="addtocart_box">
				<div class="addtocart_area">
					<div class="cardiv1">

						{attribute_template:attributes}

						{accessory_template:accessory}
					</div>
					<div class="cardiv2">
						<div class="clearfix pricebox">
							<div class="stockholder">{stock_status:instock:outofstock}</div>

							<div class="areacart">

								<div id="product_price"><span class="product_price_val">{product_price}</span>
									<span class="eks_vat">{vat_info}</span>
								</div>
								<div class="product_addtocart">
									<div id="add_to_cart_all">{form_addtocart:add_to_cart2}</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>&nbsp;</div>
<div class="product_box_outside">
	<div style="border-bottom: 1px solid #eee;"><h4>Description</h4></div>
	<div class="product_desc">
		<div class="product_desc_full">{product_desc}</div>
	</div>
</div>

<div class="gd_header"><h4>Customer Reviews</h4></div>
<div class="gd_content clearfix">
	<div>{product_rating_summary}</div>
	<div>{form_rating}</div>
</div>

<div>&nbsp;</div>
{related_product:related_products}