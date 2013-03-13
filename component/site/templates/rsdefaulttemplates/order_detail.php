<div class="product_print">{print}</div>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="5">
	<tbody>
	<tr>
		<td colspan="2">
			<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
				<tbody>
				<tr style="background-color: #cccccc">
					<th align="left">{discount_type_lbl}</th>
				</tr>
				<tr>
					<td>{discount_type}</td>
				</tr>
				<tr style="background-color: #cccccc;">
					<th align="left">{order_information_lbl}</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>{order_id_lbl} : {order_id}</td>
				</tr>
				<tr>
					<td>{order_number_lbl} : {order_number}</td>
				</tr>
				<tr>
					<td>{order_date_lbl} : {order_date}</td>
				</tr>
				<tr>
					<td>{order_status_lbl} : {order_status}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
				<tbody>
				<tr style="background-color: #cccccc;">
					<th align="left">{billing_address_information_lbl}</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>{billing_address}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
				<tbody>
				<tr style="background-color: #cccccc;">
					<th align="left">{shipping_address_information_lbl}</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>{shipping_address}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
				<tbody>
				<tr style="background-color: #cccccc;">
					<th align="left">{order_detail_lbl}</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>
						<table style="width: 100%;" border="0" cellspacing="2" cellpadding="2">
							<tbody>
							<tr>
								<td>{copy_orderitem_lbl}</td>
								<td>{product_name_lbl}</td>
								<td>{note_lbl}</td>
								<td>{price_lbl}</td>
								<td>{quantity_lbl}</td>
								<td align="right">{total_price_lbl}</td>
							</tr>
							<!-- {product_loop_start} -->
							<tr>
								<td>{copy_orderitem}</td>
								<td>{product_name}<br/>{product_attribute}{product_accessory}{product_userfields}</td>
								<td>{product_wrapper}</td>
								<td>{product_price}</td>
								<td>{product_quantity}</td>
								<td align="right">{product_total_price}</td>
							</tr>
							<!-- {product_loop_end} -->
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>{customer_note_lbl}: {customer_note}</td>
				</tr>
				<tr>
					<td>{requisition_number_lbl}: {requisition_number}</td>
				</tr>
				<tr>
					<td>
						<table class="cart_calculations" border="0" cellspacing="0" cellpadding="0">
							<tbody>
							<tr class="tdborder">
								<td><b>Product Subtotal excl vat:</b></td>
								<td width="100">{product_subtotal_excl_vat}</td>
							</tr>

							<!-- {if discount} -->
							<tr class="tdborder">
								<td>{discount_lbl}</td>
								<td width="100">{discount}</td>
							</tr>
							<!-- {discount end if} -->

							<tr>
								<td><b>Shipping with vat:</b></td>
								<td width="100">{shipping}</td>
							</tr>
							<!-- {if vat}-->
							<tr class="tdborder">
								<td>{vat_lbl}</td>
								<td width="100">{tax}</td>
							</tr>
							<!-- {vat end if} -->
							<!-- {if payment_discount} -->
							<tr>
								<td>{payment_discount_lbl}</td>
								<td width="100">{payment_order_discount}</td>
							</tr>
							<!-- {payment_discount end if} -->
							<tr>
								<td>
									<div class="singleline"><strong>{total_lbl}:</strong></div>
								</td>
								<td width="100">
									<div class="singleline">{order_total}</div>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left">{reorder_button}
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>