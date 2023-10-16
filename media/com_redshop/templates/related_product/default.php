<div class="product-rating-header">
    <h4>{relproduct_header}</h4>
</div>
<div class="product-rating-content">
    <div class="row related_product_wrapper category_box_wrapper">
        {related_product_start}
        <div class="category_box_outside col-sm-6 col-md-4">
            <div class="category_box_inside">
                <div class="category_product_image">{relproduct_image}</div>
                <div class="category_product_title">
                    <a href="{read_more_link}" class="category_product_link">
                        {relproduct_name}
                    </a>
                </div>
                <div class="category_product_price">
                    {if product_on_sale}
                    <div class="category_product_oldprice">
                        {relproduct_old_price}
                    </div>
                    {product_on_sale end if}

                    {relproduct_price}

                </div>
                <div class="category_product_addtocart">{form_addtocart:add_to_cart1}</div>
            </div>
        </div>

        {related_product_end}
    </div>
</div>