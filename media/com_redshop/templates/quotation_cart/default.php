<div class="category_print">{print}</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{cart_lbl}</h3>
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
			<tr class="tdborder">
				<td>
					<div class="cartproducttitle">{product_name}</div>
					<div class="cartattribut">{product_attribute}</div>
					<div class="cartaccessory">{product_accessory}</div>
					<div class="cartwrapper">{product_wrapper}</div>
					<div class="cartuserfields">{product_userfields}</div>
				</td>
				<td>{product_thumb_image}</td>
				<td>
					<table border="0">
						<tbody>
						<tr>
							<td>{update_cart}</td>
							<td>{remove_product}</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<!--  {product_loop_end} -->
			</tbody>
		</table>
	</div>

	<div class="panel-body">
		{update}{empty_cart}{quotation_request}{shop_more}
	</div>
</div>
