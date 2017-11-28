<div class="category_print">{print}</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{cart_lbl}</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th>{product_name_lbl}</th>
				<th></th>
				<th>{product_price_excl_lbl}</th>
				<th>{quantity_lbl}</th>
				<th>{total_price_exe_lbl}</th>
			</tr>
			</thead>
			<tbody>
			<!-- {product_loop_start} -->
			<tr class="tdborder">
				<td>
					<div class="cartproducttitle">{product_name}</div>
					<div class="cartproducttitle">{product_old_price}</div>
					<div class="cartattribut">{product_attribute}</div>
					<div class="cartaccessory">{product_accessory}</div>
					<div class="cartwrapper">{product_wrapper}</div>
					<div class="cartuserfields">{product_userfields}</div>
					{attribute_price_without_vat}
				</td>
				<td>{product_thumb_image}</td>
				<td>{product_price_excl_vat}</td>
				<td>{update_cart}</td>
				<td>{product_total_price_excl_vat}</td>
			</tr>
			<!-- {product_loop_end} -->
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
						<label>{customer_note_lbl}:</label>
						{customer_note}
					</div>

					<div class="form-group cart_requisition_number">
						<label>{requisition_number_lbl}:</label>
						{requisition_number}
					</div>
				</div>

				<div class="col-sm-6">
					<div class="redshop-login form-horizontal">
						<div class="form-group">
							<label class="col-sm-6">{product_subtotal_excl_vat_lbl}:</label>
							<div class="col-sm-6">{product_subtotal_excl_vat}</div>
						</div>

						<!-- {if discount}-->
						<div class="form-group">
							<label class="col-sm-6">{discount_lbl}:</label>
							<div class="col-sm-6">{discount}</div>
						</div>
						<!-- {discount end if}-->

						<div class="form-group">
							<label class="col-sm-6">{shipping_with_vat_lbl}:</label>
							<div class="col-sm-6">{shipping_excl_vat}</div>
						</div>

						<!-- {if vat}-->
						<div class="form-group">
							<label class="col-sm-6">{vat_lbl}:</label>
							<div class="col-sm-6">{tax}</div>
						</div>
						<!-- {vat end if} -->

						<!-- {if payment_discount}-->
						<div class="form-group">
							<label class="col-sm-6">{payment_discount_lbl}:</label>
							<div class="col-sm-6">{payment_order_discount}</div>
						</div>
						<!-- {payment_discount end if}-->

						<div class="form-group total">
							<label class="col-sm-6">{total_lbl}:</label>
							<div class="col-sm-6">{total}</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<div class="checkbox"><label>{newsletter_signup_chk}{newsletter_signup_lbl}</label></div>
								<div class="checkbox">{terms_and_conditions:width=500 height=450}</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div>{checkout_button}{shop_more}</div>
		</div>

	</div>
</div>



