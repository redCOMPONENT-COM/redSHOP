<table border="0">
	<tbody>
	<tr>
		<td>{welcome_introtext}</td>
	</tr>
	<tr>
		<td class="account_billinginfo">
			<table border="0" cellspacing="10" cellpadding="10" width="100%">
				<tbody>
				<tr valign="top">
					<td width="40%">{account_image}<strong>{account_title}</strong><br/><br/>
						<table border="0" cellspacing="0" cellpadding="2">
							<tbody>
							<tr>
								<td class="account_label">{fullname_lbl}</td>
								<td class="account_field">{fullname}</td>
							</tr>
							<tr>
								<td class="account_label">{state_lbl}</td>
								<td class="account_field">{state}</td>
							</tr>
							<tr>
								<td class="account_label">{country_lbl}</td>
								<td class="account_field">{country}</td>
							</tr>
							<tr>
								<td class="account_label">{vatnumber_lbl}</td>
								<td class="account_field">{vatnumber}</td>
							</tr>
							<tr>
								<td class="account_label">{ean_number_lbl}</td>
								<td class="account_field">{ean_number}</td>
							</tr>
							<tr>
								<td class="account_label">{email_lbl}</td>
								<td class="account_field">{email}</td>
							</tr>
							<tr>
								<td class="account_label">{company_name_lbl}</td>
								<td class="account_field">{company_name}</td>
							</tr>
							<tr>
								<td colspan="2">{edit_account_link}</td>
							</tr>
							</tbody>
						</table>
					</td>
					<td>
						<table border="0">
							<tbody>
							<tr>
								<td>{order_image}<strong>{order_title}</strong></td>
							</tr>
							<!-- {order_loop_start} -->
							<tr>
								<td>{order_index} {order_id} {order_detail_link}</td>
							</tr>
							<!-- {order_loop_end} -->
							<tr>
								<td>{more_orders}</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td class="account_shippinginfo">{shipping_image}<strong>{shipping_title}</strong> <br/><br/>
						<table border="0">
							<tbody>
							<tr>
								<td>{edit_shipping_link}</td>
							</tr>
							</tbody>
						</table>
					</td>
					<td>
						<table border="0">
							<tbody>
							<tr>
								<td>{quotation_image}<strong>{quotation_title}</strong></td>
							</tr>
							<!-- {quotation_loop_start} -->
							<tr>
								<td>{quotation_index} {quotation_id} {quotation_detail_link}</td>
							</tr>
							<!-- {quotation_loop_end} -->
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>{product_serial_image}<strong>{product_serial_title}</strong><br/><br/>
						<table border="0">
							<tbody>
							<!-- {product_serial_loop_start} -->
							<tr>
								<td>{product_name} {product_serial_number}</td>
							</tr>
							<!-- {product_serial_loop_end} -->
							</tbody>
						</table>
					</td>
					<td>
						<table border="0">
							<tbody>
							<tr>
								<td>{coupon_image}<strong>{coupon_title}</strong></td>
							</tr>
							<!--  {coupon_loop_start} -->
							<tr>
								<td>{coupon_code_lbl} {coupon_code}</td>
							</tr>
							<tr>
								<td>{coupon_value_lbl} {coupon_value}</td>
							</tr>
							<!-- {coupon_loop_end} -->
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>{wishlist_image}<strong>{wishlist_title}</strong><br/><br/>
						<table border="0">
							<tbody>
							<tr>
								<td>{edit_wishlist_link}</td>
							</tr>
							</tbody>
						</table>
					</td>
					<td>{compare_image}<strong>{compare_title}</strong> <br/><br/>
						<table border="0">
							<tbody>
							<tr>
								<td>{edit_compare_link}</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>{logout_link}</td>
					<td>{tag_image}<strong>{tag_title}</strong><br/><br/>
						<table border="0">
							<tbody>
							<tr>
								<td>{edit_tag_link}</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
