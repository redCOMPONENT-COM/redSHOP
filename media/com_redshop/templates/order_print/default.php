<p style="text-align: justify;"></p>
<table style="width: 100%; vertical-align: top;" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td style="vertical-align: top; width: 36%;">
				<p style="color: #332b24;">{order_id_lbl}: <strong>{order_id}</strong> <br />{order_number_lbl}: <strong>{order_number}</strong></p>
			</td>
			<td style="vertical-align: top; width: 30%;">
				<p style="color: #332b24;">{order_date_lbl}: <strong>{order_date}</strong> <br />{order_status_lbl}: <strong>{order_status}</strong></p>
			</td>
			<td style="vertical-align: top; width: 33%;"><img style="float: right;" alt="logo" src="http://redcomponent.com/images/redcomponent_logo.png" /></td>
		</tr>
		<tr>
			<td style="height: 10px;"> </td>
			<td style="height: 10px;"> </td>
			<td style="height: 10px;"> </td>
		</tr>
		<tr>
			<td style="vertical-align: top;">
				<strong>{shipping_address_information_lbl}</strong><br />
				{shipping_address}
				<p style="color: #332b24;"><strong>{payment_lbl}</strong> <br />{payment_method}: {order_payment_status}</p>
			</td>
			<td style="vertical-align: top;">
				<strong>{billing_address_information_lbl}</strong><br />
				{billing_address}
			</td>
			<td style="vertical-align: top;">
				<p style="text-align: right;"><span style="color: #332b24;">redCOMPONENT</span> <br /><span style="color: #332b24;">Blangstedgaardsvej 1</span> <br /><span style="color: #332b24;">5220 Odense SØ<br /></span><span style="color: #332b24;">Danmark</span> <br /><span style="color: #332b24;"> Tlf. +45 23 888 777<br /></span></p>
			</td>
		</tr>
	</tbody>
</table>
<table style="width: 100%; vertical-align: top;" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td style="height: 10px;"> </td>
			<td style="height: 10px;"> </td>
			<td style="height: 10px;"> </td>
		</tr>
	</tbody>
</table>
<table style="width: 100%; vertical-align: middle; border: 1px solid #ded6d0;" cellpadding="5" cellspacing="0">
	<tbody>
		<tr>
			<td style="vertical-align: middle; background-color: #ded6d0; width: 10%;">
				<p style="text-align: justify;"></p>
			</td>
			<td style="vertical-align: middle; background-color: #ded6d0; width: 30%;">
				<p style="text-align: left;">{product_name_lbl}</p>
			</td>
			<td style="vertical-align: middle; background-color: #ded6d0; width: 20%;">
				<p style="text-align: right;">{quantity_lbl} </p>
			</td>
			<td style="vertical-align: middle; background-color: #ded6d0; width: 20%;">
				<p style="text-align: right;">{price_lbl}</p>
			</td>
			<td style="vertical-align: middle; background-color: #ded6d0; width: 20%;">
				<p style="text-align: right;">{total_price_lbl}</p>
			</td>
		</tr>
		<!-- {product_loop_start} --> <!-- {attribute_price_with_vat} -->
		<tr>
			<td style="vertical-align: middle; text-align: center; border-bottom: 1px solid #ded6d0; padding: 10px 0;" valign="top">
				{product_thumb_image}
			</td>
			<td style="vertical-align: middle; text-align: left; border-bottom: 1px solid #ded6d0;" valign="top">
				{product_name}
				<table border="0">
					<!--{product_attribute_loop_start}-->
					<tr class="attribute">
						<td colspan="2">{product_attribute_name}: {product_attribute_value}</td>
						<td class="price" style="text-align: center;">{product_attribute_calculated_price}</td>
						<td colspan="1"> </td>
					</tr>
					<!--{product_attribute_loop_end}-->
				</table>
			</td>
			<td style="vertical-align: middle; text-align: right; border-bottom: 1px solid #ded6d0;" valign="top">
				{product_quantity}
			</td>
			<td style="vertical-align: middle; text-align: right; border-bottom: 1px solid #ded6d0;" valign="top">
				{product_price}
			</td>
			<td style="vertical-align: middle; text-align: right; border-bottom: 1px solid #ded6d0;" valign="top">
				{product_total_price}
			</td>
		</tr>
		<!-- {product_loop_end} -->
	</tbody>
</table>
<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td>
				<p style="text-align: right; color: #292a35;">
					<br />{order_subtotal_lbl}: {order_subtotal}<br /> <!-- {if discount}-->{discount_lbl} {order_discount} <br /> <!-- {discount end if}-->{shipping_lbl} {order_shipping} <br />{order_transfee_label} {order_transfee} <br /><strong><br />{total_lbl}: </strong><strong>{order_total_incl_transfee}</strong> <br /> <!-- {if vat} -->{vat_lbl}: {order_tax} <!-- {vat end if} -->
				</p>
			</td>
		</tr>
	</tbody>
</table>
<p>{customer_note_lbl}: {customer_note} <br /> {requisition_number_lbl}: {requisition_number}</p>