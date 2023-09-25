<div class="shipping-method-container" style="display: {show_when_one_rate}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{shipping_heading}</h3>
        </div>
        {shipping_method_loop_start}
        <div class="panel-body">
            <div class="shipping_method_name">{shipping_method_title}</div>
            {shipping_rate_loop_start}
            <div class="shipping_method radio">
                <div class="shipping_method_name">
                    {shipping_rate_name}{shipping_rate}
                </div>
            </div>
            {shipping_rate_loop_end}
        </div>
        {shipping_method_loop_end}
    </div>
</div>