<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{shipping_heading}</h3>
	</div>
	<div class="panel-body">
		{shipping_method_loop_start}
		<h4>{shipping_method_title}</h4>
			{shipping_rate_loop_start}
			<div class="shipping_rate radio">{shipping_rate_name}<span>{shipping_rate}</span></div>
			{shipping_rate_loop_end}
		{shipping_method_loop_end}
	</div>
</div>
