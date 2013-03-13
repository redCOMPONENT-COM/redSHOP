<h1>Indkøbskurv</h1>
<table class="tdborder" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th>{product_name_lbl}</th>
		<th><br/></th>
		<th>{quantity_lbl}</th>
	</tr>
	</thead>
	<tbody>
	{print} <!--  {product_loop_start} -->
	<tr class="tdborder">
		<td>
			<p>{product_name} <span class="attribut">{product_attribute} {product_accessory}</span>
				{product_wrapper}{product_userfields}</p>

			<p></p>
		</td>
		<td>{product_thumb_image}</td>
		<td>
			<table style="width: 172px; height: 46px;" border="0">
				<tbody>
				<tr>
					<td>{update_cart}{remove_product}</td>
					<td><br/></td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<!--  {product_loop_end} -->
	</tbody>
</table>
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
				</tbody>
			</table>
			<br/><br/> {coupon_code_lbl} {discount_form}
		</td>
		<td width="50%" align="right" valign="top"><br/><br/>
			<table class="cart_regnestykke" border="0">
				<tbody>
				</tbody>
			</table>

			<p>{checkout_button}<br/><br/> {shop_more}</p>
		</td>
	</tr>
	</tbody>
</table>
