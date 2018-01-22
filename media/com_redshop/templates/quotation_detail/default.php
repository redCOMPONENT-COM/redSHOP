<div class="product_print">{print}</div>

<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{quotation_information_lbl}</h3>
			</div>

			<div class="panel-body">
				<div class="row">
					<label class="col-sm-6">{quotation_id_lbl}:</label>
					<div class="col-sm-6">{quotation_id}</div>
				</div>

				<div class="row">
					<label class="col-sm-6">{quotation_number_lbl}:</label>
					<div class="col-sm-6">{quotation_number}</div>
				</div>

				<div class="row">
					<label class="col-sm-6">{quotation_date_lbl}:</label>
					<div class="col-sm-6">{quotation_date}</div>
				</div>

				<div class="row">
					<label class="col-sm-6">{quotation_status_lbl}:</label>
					<div class="col-sm-6">{quotation_status}</div>
				</div>

				<div class="row">
					<label class="col-sm-6">{quotation_note_lbl}:</label>
					<div class="col-sm-6">{quotation_note}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{account_information_lbl}</h3>
			</div>

			<div class="panel-body">
				{account_information}{quotation_custom_field_list}
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{quotation_detail_lbl}</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th>{product_name_lbl}</th>
				<th>{note_lbl}</th>
				<th>{price_lbl}</th>
				<th>{quantity_lbl}</th>
				<th>{total_price_lbl}</th>
			</tr>
			</thead>
			<tbody>
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
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="cart_totals">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group cart_customer_note">
						<label>{quotation_customer_note_lbl}</label>
						{quotation_customer_note}
					</div>
				</div>

				<div class="col-sm-6">
					<div class="redshop-login form-horizontal">
						<div class="form-group">
							<label class="col-sm-6">{quotation_subtotal_lbl}:</label>
							<div class="col-sm-6">{quotation_subtotal}</div>
						</div>

						<div class="form-group">
							<label class="col-sm-6">{quotation_tax_lbl}:</label>
							<div class="col-sm-6">{quotation_tax}</div>
						</div>

						<div class="form-group">
							<label class="col-sm-6">{quotation_discount_lbl}:</label>
							<div class="col-sm-6">{quotation_discount}</div>
						</div>

						<div class="form-group">
							<label class="col-sm-6">{total_lbl}:</label>
							<div class="col-sm-6">{quotation_total}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
