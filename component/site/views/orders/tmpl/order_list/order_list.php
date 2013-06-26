<table border="0" cellspacing="5" cellpadding="5" width="100%">
	<tbody>
	<tr>
		<th>{order_id_lbl}</th>
		<th>{product_name_lbl}</th>
		<th>{total_price_lbl}</th>
		<th>{order_date_lbl}</th>
		<th>{order_status_lbl}</th>
		<th>{order_detail_lbl}</th>
	</tr>
	<!--  {product_loop_start} -->
	<tr>
		<td style="background-color: #d7d7d4">{order_id}</td>
		<td style="background-color: #d7d7d4">{order_products}</td>
		<td style="background-color: #d7d7d4">{order_total}</td>
		<td style="background-color: #d7d7d4">{order_date}</td>
		<td style="background-color: #d7d7d4">{order_status}</td>
		<td style="background-color: #d7d7d4">{order_detail_link}{reorder_link}</td>
	</tr>
	<!--  {product_loop_end} -->
	</tbody>
</table>
{pagination}
