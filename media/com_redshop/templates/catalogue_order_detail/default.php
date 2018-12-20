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
				<tr style="background-color: #cccccc">
					<th align="left">{order_information_lbl}{print}</th>
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
				<tr style="background-color: #cccccc">
					<th align="left">{billing_address_information_lbl}</th>
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
				<tr style="background-color: #cccccc">
					<th align="left">{shipping_address_info_lbl}</th>
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
				<tr style="background-color: #cccccc">
					<th align="left">{order_detail_lbl}</th>
				</tr>
				<tr>
					<td>
						<table style="width: 100%;" border="0" cellspacing="2" cellpadding="2">
							<tbody>
							<tr>
								<td>{product_name_lbl}</td>
								<td>{note_lbl}</td>
								<td>{quantity_lbl}</td>
							</tr>
							<!--  {product_loop_start} -->
							<tr>
								<td>{product_name}{product_userfields}</td>
								<td>{customer_note}</td>
								<td>{product_quantity}</td>
							</tr>
							<!--  {product_loop_end} -->
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td></td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
