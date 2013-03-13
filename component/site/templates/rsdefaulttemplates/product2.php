<div class="product">
	<div class="next-prev">
		<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
			<tbody>
			<tr>
				<td style="width: 33%; height: 20px;" align="left" valign="middle">{navigation_link_left}</td>
				<td style="width: 33%; height: 20px;" align="center" valign="middle">{returntocategory}</td>
				<td style="width: 33%; height: 20px;" align="right" valign="middle">{navigation_link_right}</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="product_rating_summary">{product_rating_summary}</div>
	<div class="product_writereview">{form_rating}</div>
	<div class="product_box">
		<div class="product_box_inside">
			<div class="product_box_left">
				<div class="product_image">{product_thumb_image}<br/>{view_full_size_image_lbl}</div>
				<div class="product_more_images">{more_images}</div>
			</div>
			<div class="product_box_right">
				<div id="product_price" class="product_price">{product_price}</div>
				<div class="product_attributter">{attribute_template:attributes}</div>
				{if product_userfield}
				<div class="product_userfield">{userfield-test}</div>
				{product_userfield end if}
				<div class="product_accessory">{accessory_template:accessory}</div>
				<div class="product_addtocart">{form_addtocart:add_to_cart2}</div>
				<div class="product_manufacturer_link">{manufacturer_link}</div>
				<div class="product_question_link">{ask_question_about_product}</div>
			</div>
		</div>
		<div class="product_desc_wrapper">
			<div class="product_title">
				<h2>{product_name}</h2>
			</div>
			<div class="product_desc_short">{product_s_desc}</div>
			<div class="product_desc_full">{product_desc}</div>
		</div>
		<div class="product_related_products">{related_product:related_products}</div>
	</div>
</div>
<p>{question_loop_start}{question} - {question_owner} - {question_date}{answer_loop_start}{answer} - {answer_owner} -
	{answer_date}{answer_loop_end}{question_loop_end}</p>
