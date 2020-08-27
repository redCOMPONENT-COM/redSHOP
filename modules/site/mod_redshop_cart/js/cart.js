function deleteCartItem(idx, token, urlRedirect, callback)
{
    var data = {};
    data["idx"] = idx;
    data[token] = 1;

    jQuery.ajax({
        type: "POST",
        data: data,
        url: urlRedirect,
        success: function (data) {
            responce = data.split("`");

            if (jQuery('#mod_cart_total') && responce[1]) {
                jQuery('#mod_cart_total').html(responce[1]);
            }

            if (jQuery('#rs_promote_free_shipping_div') && responce[2]) {
                jQuery('#rs_promote_free_shipping_div').html(responce[2]);
            }

            if (jQuery('#mod_cart_checkout_ajax')) {
                jQuery('#mod_cart_checkout_ajax').css("display", "inline-block");
            }

            if (typeof callback == 'function') {
                callback(responce);
            } else {
                window.location.reload();
            }
        }
    });
}