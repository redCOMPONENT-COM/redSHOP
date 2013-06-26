<div id="ajax-cart">
	<div id="ajax-cart-label">
		<h3>Add to Cart</h3>
	</div>
	<div id="ajax-cart-attr">{attribute_template:attributes}</div>
	<div id="ajax-cart-access">{accessory_template:accessory}</div>
	{if product_userfield}
	<div id="ajax-cart-user">{userfield-test}</div>
	{product_userfield end if}
	<div id="ajax-cart-label">{form_addtocart:add_to_cart2}</div>
</div>
