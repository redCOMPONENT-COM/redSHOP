<table border="0" cellspacing="2" cellpadding="2" width="100%">
	<tbody>
	<tr>
		<td>
			<fieldset class="adminform">
				<legend>{billing_address_information_lbl}</legend>
				{edit_billing_address} <br/>{billing_address}
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset class="adminform">
				<legend>{shipping_address_information_lbl}</legend>
				{shipping_address}
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0">
				<tbody>
				<tr>
					<td>{shippingbox_template:shipping_box}</td>
				</tr>
				<tr>
					<td>{shipping_template:shipping_method}</td>
				</tr>
				<tr>
					<td>{payment_template:payment_method}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0">
				<tbody>
				<tr>
					<td>{checkout_template:checkout}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
