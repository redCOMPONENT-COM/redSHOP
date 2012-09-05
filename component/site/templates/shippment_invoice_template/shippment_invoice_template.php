<table style="width: 100%;" border="0" cellpadding="5" cellspacing="0">
<tbody>
<tr>
<td colspan="2">
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr style="background-color: #cccccc;"><th align="left">{order_information_lbl}</th></tr>
<tr></tr>
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
<tr>
<td>{customer_note_lbl} : {customer_note}</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td colspan="2">
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr style="background-color: #cccccc;"><th align="left">{billing_address_information_lbl}</th></tr>
<tr></tr>
<tr>
<td>{billing_address}</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td colspan="2">
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr style="background-color: #cccccc;"><th align="left">{shipping_address_info_lbl}</th></tr>
<tr></tr>
<tr>
<td>{shipping_address}</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td colspan="2">
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr style="background-color: #cccccc;"><th align="left">{order_detail_lbl}</th></tr>
<tr></tr>
<tr>
<td>
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr>
<td>{product_name_lbl}</td>
<td>{note_lbl}</td>
<td>{price_lbl}</td>
<td>{quantity_lbl}</td>
<td align="right">{total_price_lbl}</td>
</tr>
{product_loop_start}
<tr>
<td>
<p>{product_name}({product_number})<br />{product_userfields}<br />{product_attribute}<br />{product_accessory}</p>
<p>{product_attribute_number}</p>
</td>
<td>{product_note}</td>
<td>{product_price}</td>
<td>{product_quantity}</td>
<td align="right">{product_tot_price}</td>
</tr>
{product_loop_end}</tbody>
</table>
</td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td>
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr align="left">
<td align="left"><strong>{order_subtotal_lbl} : </strong></td>
<td align="right">{order_subtotal}</td>
</tr>
{if vat}
<tr align="left">
<td align="left"><strong>{vat_lbl} : </strong></td>
<td align="right">{order_tax}</td>
</tr>
{vat end if} {if discount}
<tr align="left">
<td align="left"><strong>{discount_lbl} : </strong></td>
<td align="right">{order_discount}</td>
</tr>
{discount end if}
<tr align="left">
<td align="left"><strong>{shipping_lbl} : </strong></td>
<td align="right">{order_shipping}</td>
</tr>
<tr align="left">
<td colspan="2" align="left"><hr /></td>
</tr>
<tr align="left">
<td align="left"><strong>{total_lbl} :</strong></td>
<td align="right">{order_total}</td>
</tr>
<tr align="left">
<td colspan="2" align="left"><hr /><br /><hr /></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td colspan="2">
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr style="background-color: #cccccc;"><th align="left">Payment Status</th></tr>
<tr></tr>
<tr>
<td>{order_payment_status}<br />{shipping_method_lbl}: {shipping_method}</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td colspan="2">
<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
<tbody>
<tr style="background-color: #cccccc;"><th align="left">Order url</th></tr>
<tr></tr>
<tr>
<td>{order_detail_link}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>