<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{cart_lbl}</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<th>{product_name_lbl}</th>
				<th><br/></th>
				<th>{product_price_excl_lbl}</th>
				<th>{quantity_lbl}</th>
				<th>{total_price_exe_lbl}</th>
				<th><br/></th>
			</tr>
			</thead>
			<tbody>
			<!-- {product_loop_start} -->
			<tr class="tdborder">
				<td>
					<div class="cartproducttitle">{product_name}</div>
					<div class="cartattribut">{product_attribute}</div>
					<div class="cartaccessory">{product_accessory}</div>
					<div class="cartwrapper">{product_wrapper}</div>
					<div class="cartuserfields">{product_userfields}</div>
					<div>{attribute_change}</div>
					{attribute_price_with_vat}
				</td>
				<td>{product_thumb_image}</td>
				<td>{product_price_excl_vat}</td>
				<td>
					<span class="update_cart">{update_cart}</span>
				</td>
				<td>{product_total_price_excl_vat}</td>
				<td>{remove_product}</td>
			</tr>
			<!-- {product_loop_end} -->
			</tbody>
		</table>
	</div>

	<div class="panel-body">
		<div class="discount_text">{discount_rule}</div>

		<div class="cart_button pull-right">
			<span class="inline">{update}</span>
			<span class="inline">{empty_cart}</span>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="cart_totals clearfix">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group cart_discount_form">
						{coupon_code_lbl}{discount_form_lbl} {discount_form}
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
					</div>
				</div>
			</div>
			<div>{checkout_button}{shop_more}</div>
		</div>
	</div>
</div>
