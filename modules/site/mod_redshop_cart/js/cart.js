function deleteCartItem(idx, token, urlRedirect)
{
    jQuery.ajax({
        type: "POST",
        data: {
            "idx": idx,
            token: "1"
        },
        url: urlRedirect,
        success: function(data) {
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
        }
    });
}