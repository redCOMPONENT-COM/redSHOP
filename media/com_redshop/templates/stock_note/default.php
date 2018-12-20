<table border="0" cellspacing="2" cellpadding="2" width="100%">
	<tr>
		<td>{order_id_lbl} : {order_id}</td>
		<td> {order_date_lbl} : {order_date}</td>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
	<tbody>
	<tr style="background-color: #d7d7d4">
		<th align="center">{product_name_lbl}</th>
		<th align="center">{product_number_lbl}</th>
		<th align="center">{product_quantity_lbl}</th>
	</tr>
	<!-- {product_loop_start} -->
	<tr>
		<td align="center">
			<table>
				<tr>
					<td>{product_name}</td>
				</tr>
				<tr>
					<td>{product_attribute}</td>
				</tr>
			</table>
		</td>
		<td align="center">{product_number}</td>
		<td align="center">{product_quantity}</td>
	</tr>
	<!-- {product_loop_end} -->
	</tbody>
</table>
