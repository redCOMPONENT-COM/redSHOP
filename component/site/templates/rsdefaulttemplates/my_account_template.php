<div class="welcome_introtext">
	{welcome_introtext}
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{account_image} {account_title}</h3>
			</div>

			<div class="panel-body">
				{edit_account_link}
				{billing_address}
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{order_image} {order_title}</h3>
			</div>

			<div class="panel-body">
				<!-- {order_loop_start} -->
				<div class="row">
					<div class="col-sm-4">
						{order_index}{order_id}
					</div>

					<div class="col-sm-4">
						{order_total}
					</div>

					<div class="col-sm-4">
						{order_detail_link}
					</div>
				</div>
				<!-- {order_loop_end} -->

				<div class="more_orders">{more_orders}</div>
			</div>
		</div>

		{if quotation}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{quotation_image} {quotation_title}</h3>
			</div>

			<div class="panel-body">
				<!-- {quotation_loop_start} -->
				<div class="row">
					<div class="col-sm-4">
						{quotation_index}{quotation_id}
					</div>

					<div class="col-sm-8">
						{quotation_detail_link}
					</div>
				</div>
				<!-- {quotation_loop_end} -->
			</div>
		</div>
		{quotation end if}
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{shipping_image} {shipping_title}</h3>
			</div>

			<div class="panel-body">
				{edit_shipping_link}
			</div>
		</div>

		{if product_serial}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{product_serial_image} {product_serial_title}</h3>
			</div>

			<div class="panel-body">
				<!-- {product_serial_loop_start} -->
				<div class="row">
					<div class="col-sm-4">
						{product_name}
					</div>

					<div class="col-sm-8">
						{product_serial_number}
					</div>
				</div>
				<!-- {product_serial_loop_end} -->
			</div>
		</div>
		{product_serial end if}

		{if coupon}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{coupon_image} {coupon_title}</h3>
			</div>

			<div class="panel-body">
				<!-- {coupon_loop_start} -->
				<div class="row">
					<div class="col-sm-4">
						{coupon_code_lbl} {coupon_code}
					</div>

					<div class="col-sm-8">
						{coupon_value_lbl} {coupon_value}
					</div>
				</div>
				<!-- {coupon_loop_end} -->
			</div>
		</div>
		{coupon end if}

		{if wishlist}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{wishlist_image} {wishlist_title}</h3>
			</div>

			<div class="panel-body">
				{edit_wishlist_link}
			</div>
		</div>
		{wishlist end if}

		{if compare}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{compare_image} {compare_title}</h3>
			</div>

			<div class="panel-body">
				{edit_compare_link}
			</div>
		</div>
		{compare end if}

		{if tag}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{tag_image} {tag_title}</h3>
			</div>

			<div class="panel-body">
				{edit_tag_link}
			</div>
		</div>
		{tag end if}
	</div>
</div>

<div class="logout_link">{logout_link}</div>

