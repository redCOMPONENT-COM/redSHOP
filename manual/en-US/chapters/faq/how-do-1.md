## How do I customize the AJAX add-to-cart boxes?
You can customize the appearance of the AJAX add-to-cart boxes by adding custom CSS to your template that changes the appearance of the IDs in the following two PHP files.  You may also completely rework the layout by overriding these two template files with template overrides.  NOTE: It is not recommended that you make direct changes to these template PHP files as they will be overwritten by redSHOP updates.

In /components/com_redshop/templates/rsdefaulttemplates/ajax_cart_box.php

<pre>
&lt;div id="ajax_cart_wrapper"&gt;
     &lt;div id="ajax_cart_text"&gt;{ajax_cart_box_title}&lt;br/&gt;&lt;br/&gt;&lt;/div&gt;
     &lt;div id="ajax_cart_button_wrapper"&gt;
         &lt;div id="ajax_cart_button_inside"&gt;
             &lt;div id="ajax_cart_continue_button"&gt;{continue_shopping_button}&lt;/div&gt;
             &lt;div id="ajax_cart_show_button"&gt;{show_cart_button}&lt;/div&gt;
         &lt;/div&gt;
     &lt;/div&gt;
 &lt;/div&gt;
</pre>

and in /components/com_redshop/templates/rsdefaulttemplates/ajax_cart_detail_box.php

<pre>
&lt;div id="ajax-cart"&gt;
     &lt;div id="ajax-cart-label"&gt;
         &lt;h3&gt;Add to Cart&lt;/h3&gt;
     &lt;/div&gt;
     &lt;div id="ajax-cart-attr"&gt;{attribute_template:attributes}&lt;/div&gt;
     &lt;div id="ajax-cart-access"&gt;{accessory_template:accessory}&lt;/div&gt;
     {if product_userfield}
     &lt;div id="ajax-cart-user"&gt;{userfield-test}&lt;/div&gt;
     {product_userfield end if}
     &lt;div id="ajax-cart-label"&gt;{form_addtocart:add_to_cart2}&lt;/div&gt;
 &lt;/div&gt;
</pre>

<hr>

<h6>Last updated on January 4, 2016</h6>