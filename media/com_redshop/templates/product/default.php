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
            <div class="col-md-3 product-icons">
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
                    <span class="normal-stock text-success hasTooltip" title=""
                        data-original-title="We have plenty of Stock for this product">In Stock</span>
                </div>
                <div class="product-sku-cont col-sm-6">
                    {product_number}
                </div>
            </div>
        </div>
        <div class="product-short-desc-cont">
            {product_s_desc}
        </div>
        <div class="product-attributes-cont">
            <div class="attributes">
                {attribute_template:attributes}
            </div>

            {accessory_template:accessory}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 product-wishlist-cont">
        <div class="wishlist_link">
            {wishlist_link}
        </div>
    </div>
    <div class="col-md-4 product-compare-cont">
        {compare_products_button}
    </div>
    <div class="col-md-4 product-addtocart-cont">
        <div class="product_addtocart">
            {form_addtocart:add_to_cart1}
        </div>
    </div>
</div>
<ul class="nav nav-tabs" id="ex1" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="ex1-tab-Description" data-mdb-toggle="tab" href="#ex1-tabs-Description" role="tab"
            aria-controls="ex1-tabs-Description" aria-selected="true">Description</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="ex1-tab-Reviews" data-mdb-toggle="tab" href="#ex1-tabs-Reviews" role="tab"
            aria-controls="ex1-tabs-Reviews" aria-selected="false">Reviews</a>
    </li>
</ul>
<div class="tab-content" id="ex1-content">
    <div class="tab-pane fade show active" id="exDescription-tabs-Description" role="tabpanel" aria-labelledby="exDescription-tab-Description">
        <div class="product_desc_full">{product_desc}</div>
    </div>
    <div class="tab-pane fade" id="ex1-tabs-Reviews" role="tabpanel" aria-labelledby="ex1-tab-Reviews">
        {product_rating}
        {form_rating}
        {form_rating_without_lightbox}
    </div>
</div>
{related_product:related_products}