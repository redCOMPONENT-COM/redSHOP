<h1>{cart_lbl}</h1>
<table class="tdborder" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th>{product_name_lbl}</th>
		<th><br/></th>
		<th>{product_price_excl_lbl}</th>
		<th>{quantity_lbl}</th>
		<th>{total_price_exe_lbl}</th>
	</tr>
	</thead>
	<tbody>
	<!-- {product_loop_start} -->
	<div class="category_print">{attribute_price_with_vat}</div>
	<tr class="tdborder">
		<td>
			<div class="cartproducttitle">{product_name}</div>
			<div class="cartattribut">{product_attribute}</div>
			<div class="cartaccessory">{product_accessory}</div>
			<div class="cartwrapper">{product_wrapper}</div>
			<div class="cartuserfields">{product_userfields}</div>
			<div>{attribute_change}</div>
		</td>
		<td>{product_thumb_image}</td>
		<td>{product_price_excl_vat}</td>
		<td>
			<table border="0">
				<tbody>
				<tr>
					<td>{update_cart}</td>
					<td>{remove_product}</td>
				</tr>
				</tbody>
			</table>
		</td>
		<td>{product_total_price_excl_vat}</td>
	</tr>
	<!-- {product_loop_end} -->
	</tbody>
</table>
<p><strong class="discount_text"><br/></strong></p>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
		<td width="50%" valign="top">
			<table border="0">
				<tbody>
				<tr>
					<td>{update}</td>
					<td>{empty_cart}</td>
				</tr>
				<tr>
					<td class="cart_discount_form" colspan="2">{discount_form_lbl}{coupon_code_lbl}<br/>{discount_form}
					</td>
				</tr>
				</tbody>
			</table>
			<br/></td>
		<td width="50%" align="right" valign="top"><br/><br/>
			<table class="cart_calculations" border="0" width="100%">
				<tbody>
				<tr class="tdborder">
					<td><b>{product_subtotal_excl_vat_lbl}:</b></td>
					<td width="100">{product_subtotal_excl_vat}</td>
				</tr>
				<!-- {if discount}-->
				<tr class="tdborder">
					<td>{discount_lbl}</td>
					<td width="100">{discount}</td>
				</tr>
				<!-- {discount end if} -->
				<tr>
					<td><b>{shipping_with_vat_lbl}:</b></td>
					<td width="100">{shipping_excl_vat}</td>
				</tr>
				<!-- {if vat} -->
				<tr>
					<td>{vat_lbl}</td>
					<td width="100">{tax}</td>
				</tr>
				<!-- {vat end if} -->
				<!-- {if payment_discount}-->
				<tr>
					<td>{payment_discount_lbl}</td>
					<td width="100">{payment_order_discount}</td>
				</tr>
				<!-- {payment_discount end if}-->
				<tr>
					<td>
						<div class="singleline"><strong>{total_lbl}:</strong></div>
					</td>
					<td width="100">
						<div class="singleline">{total}</div>
					</td>
				</tr>
				</tbody>
			</table>

			{checkout_button}<br/><br/> {shop_more}
		</td>
	</tr>
	</tbody>
</table>