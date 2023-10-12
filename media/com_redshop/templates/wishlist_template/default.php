<div class="container">
    <div class="row wishlist_top">
        <div class="col-sm-6 wishlist_top_left">
            <div class="back_link">{back_link}</div>
        </div>
        <div class="col-sm-6 wishlist_top_right">
            <div class="mail_link">{mail_link}</div>
        </div>
    </div>
    <div class="row wishlist_list">
        {product_loop_start}
        <div class="col-sm-4 wishlist_list_outside">
            <div class="row">
                <div class="col-sm-12">
                    {product_thumb_image}
                    <h3>{product_name}</h3>
                    <div class="product_price">{product_price}</div>
                    <div>{form_addtocart:add_to_cart1}</div>
                </div>
                <div class="col-sm-12">
                    <hr>
                </div>
                <div class="col-sm-12">
                    <div class="remove_product_link">{remove_product_link}</div>
                </div>
            </div>
        </div>
        {product_loop_end}
    </div>
</div>