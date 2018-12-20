<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{order_detail_lbl}</h3>
	</div>

	<div class="table-responsive">
		<table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th width="40%" align="left">{product_name_lbl}</th>
				<th width="35%"></th>
				<th width="25%">{quantity_lbl}</th>
			</tr>
			</thead>
			<tbody>
			<!--  {product_loop_start} -->
			<tr>
				<td>
					<div class="cartproducttitle">{product_name}</div>
					<div class="cartattribut">{product_attribute}</div>
					<div class="cartaccessory">{product_accessory}</div>
					<div class="cartwrapper">{product_wrapper}</div>
					<div class="cartuserfields">{product_userfields}</div>
				</td>
				<td align="center">{product_thumb_image}</td>
				<td align="center">{update_cart}</td>
			</tr>
			<!--  {product_loop_end} -->
			</tbody>
		</table>
	</div>
	<div class="panel-body">
		<div class="redshop-login form-horizontal">
			<div class="form-group">
				<label class="col-xs-6">{customer_note_lbl}:</label>
				<div class="col-xs-6">{customer_note}</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{billing_address_information_lbl}</h3>
	</div>
	<div class="panel-body">
		{billing_address}
		{quotation_custom_field_list}
	</div>
</div>

<div class="quotation_detail_btn">
	{cancel_btn}{request_quotation_btn}
</div>
