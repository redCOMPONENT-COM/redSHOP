<fieldset class="adminform">
	<legend>{order_detail_lbl}</legend>
	<table class="admintable" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
		<thead>
		<tr>
			<th width="40%" align="left">{product_name_lbl}</th>
			<th width="35%"></th>
			<th width="25%">{quantity_lbl}</th>
		</tr>
		</thead>
		<tbody>
		<!--  {product_loop_start} -->
		<tr>
			<td>
				<div class="cartproducttitle">{product_name}</div>
				<div class="cartattribut">{product_attribute}</div>
				<div class="cartaccessory">{product_accessory}</div>
				<div class="cartwrapper">{product_wrapper}</div>
				<div class="cartuserfields">{product_userfields}</div>
			</td>
			<td align="center">{product_thumb_image}</td>
			<td align="center">{update_cart}</td>
		</tr>
		<!--  {product_loop_end} -->
		</tbody>
	</table>
</fieldset>
<p>{customer_note_lbl}:{customer_note}</p>
<fieldset class="adminform">
	<legend>{billing_address_information_lbl}</legend>
	{billing_address} {quotation_custom_field_list}
</fieldset>
<table border="0">
	<tbody>
	<tr>
		<td align="center">{cancel_btn}</td>
		<td align="center">{request_quotation_btn}</td>
	</tr>
	</tbody>
</table>