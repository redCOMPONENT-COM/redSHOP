<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{billing_address_information_lbl}</h3>
			</div>

			<div class="panel-body">
				{edit_billing_address}
				{billing_address}
				{billing_template}
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{shipping_address_information_lbl}</h3>
			</div>

			<div class="panel-body">
				{shipping_address}
			</div>
		</div>
	</div>
</div>

{shippingbox_template:shipping_box}

{shipping_template:shipping_method}

{payment_template:payment_method}

{checkout_template:checkout}
