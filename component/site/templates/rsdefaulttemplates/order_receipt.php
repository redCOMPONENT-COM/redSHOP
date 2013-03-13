<div class="product_print">{print}</div>
<table class="tdborder" style="width: 100%;" border="0" cellspacing="0" cellpadding="5">
	<tbody>
	<tr>
		<th>{product_name_lbl}</th>
		<th></th>
		<th>{price_lbl}</th>
		<th>{quantity_lbl}</th>
		<th>{total_price_lbl}</th>
	</tr>
	<!--  {product_loop_start} -->
	<tr>
		<td>{product_name}<br/>{product_attribute}{product_accessory}{product_userfields}{product_wrapper}</td>
		<td>{product_thumb_image}</td>
		<td>{product_price}</td>
		<td>{product_quantity}</td>
		<td>{product_total_price}</td>
	</tr>
	<!--  {product_loop_end} -->
	</tbody>
</table>
<p><br/><br/></p>
<table class="cart_calculations" border="1">
	<tbody>
	<tr class="tdborder">
		<td><b>Product Subtotal:</b></td>
		<td width="100">{product_subtotal}</td>
		<td><b>Product Subtotal excl vat:</b></td>
		<td width="100">{product_subtotal_excl_vat}</td>
	</tr>
	<tr>
		<td><b>Shipping with vat:</b></td>
		<td width="100">{shipping}</td>
		<td><b>Shipping excl vat:</b></td>
		<td width="100">{shipping_excl_vat}</td>
	</tr>
	<!-- {if discount} -->
	<tr class="tdborder">
		<td>{discount_lbl}</td>
		<td width="100">{discount}</td>

		<td>{discount_lbl}</td>
		<td width="100">{discount_excl_vat}</td>
	</tr>

	<!-- {discount end if} -->
	<tr>
		<td><b>{totalpurchase_lbl}:</b></td>
		<td width="100">{order_subtotal}</td>
		<td><b>{subtotal_excl_vat_lbl} :</b></td>
		<td width="100">{order_subtotal_excl_vat}</td>
	</tr>

	<!-- {if vat} -->
	<tr class="tdborder">
		<td>{vat_lbl}</td>
		<td width="100">{tax}</td>
		<td>{vat_lbl}</td>
		<td width="100">{sub_total_vat}</td>
	</tr>
	<!-- {vat end if} -->
	<!-- {if payment_discount}-->
	<tr>
		<td>{payment_discount_lbl}</td>
		<td width="100">{payment_order_discount}</td>
	</tr>
	<!-- {payment_discount end if}-->
	<tr class="tdborder">
		<td><b>{shipping_lbl}</b></td>
		<td width="100">{shipping}</td>
		<td><b>{shipping_lbl}</b></td>
		<td width="100">{shipping_excl_vat}</td>
	</tr>

	<tr>
		<td>
			<div class="singleline"><strong>{total_lbl}:</strong></div>
		</td>
		<td width="100">
			<div class="singleline">{order_total}</div>
		</td>
		<td>
			<div class="singleline"><b>{total_lbl}:</b></div>
		</td>
		<td width="100">
			<div class="singleline">{total_excl_vat}</div>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<p>{shipping_method_lbl} <strong>{shipping_method}</strong></p>

			<p>{payment_status}</p>
		</td>
	</tr>
	<tr>
		<td colspan="4">{billing_address}</td>
	</tr>
	<tr>
		<td colspan="4">{shipping_address}</td>
	</tr>
	</tbody>
</table>
