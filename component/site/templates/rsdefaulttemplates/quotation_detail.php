<table border="0" cellspacing="0" cellpadding="5" width="100%">
	<tbody>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="0" cellpadding="2" width="100%">
				<tbody>
				<tr style="background-color: #cccccc">
					<th align="left">{quotation_information_lbl}{print}</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>{quotation_id_lbl} : {quotation_id}</td>
				</tr>
				<tr>
					<td>{quotation_number_lbl} : {quotation_number}</td>
				</tr>
				<tr>
					<td>{quotation_date_lbl} : {quotation_date}</td>
				</tr>
				<tr>
					<td>{quotation_status_lbl} : {quotation_status}</td>
				</tr>
				<tr>
					<td>{quotation_note_lbl} : {quotation_note}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="0" cellpadding="2" width="100%">
				<tbody>
				<tr style="background-color: #cccccc">
					<th align="left">{account_information_lbl}</th>
				</tr>
				<tr>
					<td>{account_information}{quotation_custom_field_list}</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="0" cellpadding="2" width="100%">
				<tbody>
				<tr style="background-color: #cccccc">
					<th align="left">{quotation_detail_lbl}</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="2" cellpadding="2" width="100%">
							<tbody>
							<tr>
								<td>{product_name_lbl}</td>
								<td>{note_lbl}</td>
								<td>{price_lbl}</td>
								<td>{quantity_lbl}</td>
								<td align="right">{total_price_lbl}</td>
							</tr>
							<!--  {product_loop_start} -->
							<tr>
								<td>{product_name}({product_number_lbl} - {product_number})<br/>{product_accessory}{product_attribute}{product_userfields}
								</td>
								<td>{product_wrapper}</td>
								<td>{product_price}</td>
								<td>{product_quantity}</td>
								<td align="right">{product_total_price}</td>
							</tr>
							<!--  {product_loop_end} -->
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="2" cellpadding="2" width="100%">
							<tbody>
							<tr align="left">
								<td align="left"><strong>{quotation_subtotal_lbl} : </strong></td>
								<td align="right">{quotation_subtotal}</td>
							</tr>
							<tr align="left">
								<td align="left"><strong>{quotation_tax_lbl} : </strong></td>
								<td align="right">{quotation_tax}</td>
							</tr>
							<tr align="left">
								<td align="left"><strong>{quotation_discount_lbl} : </strong></td>
								<td align="right">{quotation_discount}</td>
							</tr>
							<tr align="left">
								<td colspan="2" align="left">
									<hr/>
								</td>
							</tr>
							<tr align="left">
								<td align="left"><strong>{total_lbl} :</strong></td>
								<td align="right">{quotation_total}</td>
							</tr>
							<tr align="left">
								<td colspan="2" align="left">
									<hr/>
									<br/>
									<hr/>
								</td>
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
