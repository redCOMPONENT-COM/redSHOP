<div class="product_print">{print}</div>
<table class="cartproducts fixcheckout">
    <thead>
    <tr>
        <th class="cart_product_thumb_image"> </th>
        <th class="cart_product_name">{product_name_lbl}</th>
        <th class="cart_product_price">
            <table width="100%">
                <tbody>
                <tr>
                    <th class="tdproduct_price">{product_price_excl_lbl}</th>
                    <th class="tdupdatecart">{quantity_lbl}</th>
                    <th class="tdproduct_total">{total_price_exe_lbl}</th>
                </tr>
                </tbody>
            </table>
        </th>
    </tr>
    </thead>
    <tbody>
    <!-- {product_loop_start} -->
    <div class="category_print">{attribute_price_without_vat}</div>
    <tr>
        <td class="cart_product_thumb_image">{product_thumb_image}</td>
        <td class="cart_product_name">{attribute_price_with_vat}
            <div class="cartproducttitle">{product_name}</div>
            <div class="cartattribut">{product_attribute}</div>
            <div class="cartaccessory">{product_accessory}</div>
            <div class="cartwrapper">{product_wrapper}</div>
            <div class="cartuserfields">{product_userfields}</div>
        </td>
        <td class="cart_product_price">
            <table width="100%">
                <tbody>
                <tr>
                    <td class="tdproduct_price">{product_price_excl_vat}</td>
                    <td class="tdupdatecart">{product_quantity}</td>
                    <td class="tdproduct_total">{product_total_price_excl_vat}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <!-- {product_loop_end} -->
    </tbody>
</table>
<p><br/><br/></p>
<table class="cart_calculations" width="100%">
    <tbody>
    <tr>
        <td><strong>Product Subtotal:</strong></td>
        <td width="100">{product_subtotal}</td>
        <td><strong>Product Subtotal excl vat:</strong></td>
        <td width="100">{product_subtotal_excl_vat}</td>
    </tr>
    <tr>
        <td><strong>Shipping with vat:</strong></td>
        <td width="100">{shipping}</td>
        <td><strong>Shipping excl vat:</strong></td>
        <td width="100">{shipping_excl_vat}</td>
    </tr>
    <!-- {if discount} -->
    <tr>
        <td>{discount_lbl}</td>
        <td width="100">{discount}</td>
        <td>{discount_lbl}</td>
        <td width="100">{discount_excl_vat}</td>
    </tr>
    <!-- {discount end if} -->
    <tr>
        <td><strong>{totalpurchase_lbl}:</strong></td>
        <td width="100">{order_subtotal}</td>
        <td><strong>{subtotal_excl_vat_lbl} :</strong></td>
        <td width="100">{order_subtotal_excl_vat}</td>
    </tr>
    <!-- {if vat} -->
    <tr>
        <td>{vat_lbl}</td>
        <td width="100">{tax}</td>
        <td>{vat_lbl}</td>
        <td width="100">{sub_total_vat}</td>
    </tr>
    <!-- {vat end if} --> <!-- {if payment_discount}-->
    <tr>
        <td>{payment_discount_lbl}</td>
        <td width="100">{payment_order_discount}</td>
    </tr>
    <!-- {payment_discount end if}-->
    <tr>
        <td><strong>{shipping_lbl}</strong></td>
        <td width="100">{shipping}</td>
        <td><strong>{shipping_lbl}</strong></td>
        <td width="100">{shipping_excl_vat}</td>
    </tr>
    <tr>
        <td>
            <div class="singleline"><strong>{total_lbl}:</strong></div>
        </td>
        <td width="100">
            <div class="singleline">{order_total}</div>
        </td>
        <td>
            <div class="singleline"><strong>{total_lbl}:</strong></div>
        </td>
        <td width="100">
            <div class="singleline">{total_excl_vat}</div>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <p>{shipping_method_lbl} <strong>{shipping_method}</strong></p>

            <p>{payment_status}</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
                <tbody>
                <tr style="background-color: #cccccc;">
                    <th align="left">{billing_address_information_lbl}</th>
                </tr>
                <tr>
                    <td>{billing_address}</td>
                </tr>
                </tbody>
            </table>
        </td>

        <td colspan="2">
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
                <tbody>
                <tr style="background-color: #cccccc;">
                    <th align="left">{shipping_address_information_lbl}</th>
                </tr>
                <tr>
                    <td>{shipping_address}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>