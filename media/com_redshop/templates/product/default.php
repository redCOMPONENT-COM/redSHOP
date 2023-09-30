<div class="row product-detail">
    <div class="col-md-4">
        <figure class="">
            <div class="">
                <div class="product_image">{product_thumb_image}</div>
                <div class="product_more_images">{more_images}</div>
            </div>
        </figure>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-9 product_title">
                <h1>{product_name}</h1>
            </div>
            <div class="col-md-3">
                {send_to_friend}
                {print}
            </div>
        </div>
        <div class="product-rating-cont clearfix">
            {product_rating_summary}
        </div>
        <div class="product-price-stock-sku-cont">
            <div class="product-price-stock-sku-cont">
                <div class="stock_status">{stock_status:instock:outofstock}</div>

                <div class="product_price" id="product_price">
                    {if product_on_sale}
                    <div class="product_oldprice">
                        {product_old_price}
                    </div>
                    {product_on_sale end if}
                    <div class="product_main_price">
                        {product_price}
                    </div>
                    <span class="vat_info">{vat_info}</span>
                </div>
            </div>
            <div class="product-stock-sku-cont row row-condensed">
                <div class="product-stock-cont col-sm-6">
                    <span class="normal-stock text-success hasTooltip" title="" data-original-title="We have plenty of Stock for this product">In Stock</span>
                </div>
                <div class="product-sku-cont col-sm-6">
                    {product_number}
                </div>
            </div>
        </div>
        <div class="product-short-desc-cont">
            {product_s_desc}
        </div>
        <div class="product-addtocart-cont">
            <div class "attributes">
                {attribute_template:attributes}
            </div>

            {accessory_template:accessory}

            <div class="product_addtocart">
                {form_addtocart:add_to_cart1}
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 product-wishlist">
                {wishlist_link}
            </div>
            <div class="col-md-3 product-compare">
                {compare_products_button}
            </div>
        </div>
    </div>
</div>
<div class="product_desc_full">{product_desc}</div>
{related_product:related_products}