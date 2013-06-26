<table class="tdborder" style="width: 100%;" border="0" cellspacing="0" cellpadding="5">
	<tbody>
	<tr>
		<th>{product_name_lbl} {print}</th>
		<th></th>
		<th>{quantity_lbl}</th>
	</tr>
	<!--  {product_loop_start} -->
	<tr>
		<td>{product_name}<br/>{product_userfields}</td>
		<td>{product_thumb_image}</td>
		<td>{product_quantity}</td>
	</tr>
	<!--  {product_loop_end} -->
	</tbody>
</table>
<table class="order_details" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
		<td width="50%" align="left" valign="top">
			<p>{order_number_lbl}{order_number}<strong>{delivery_time_lbl} {delivery_time}</strong></p>

			<p>{order_id}{order_id_lbl}</p>

			<p>{print} Print denne side</p>
		</td>
	</tr>
	</tbody>
</table>
