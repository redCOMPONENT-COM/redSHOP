<div class="shipping-method-container" style="display: {show_when_one_rate}">
    <fieldset>
        <legend class="shipping-method-heading"><strong>{shipping_heading}</strong></legend>
        <div>{shipping_method_loop_start}
            <h3 class="shipping-method-title">{shipping_method_title}</h3>
            <div>{shipping_rate_loop_start}
                <div>{shipping_rate_name}{shipping_rate}</div>
                {shipping_rate_loop_end}
            </div>
            {shipping_method_loop_end}
        </div>
    </fieldset>
</div>
