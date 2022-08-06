## redSHOP Configuration - Cart
This section covers configuration settings concerning the shopping cart experience, the checkout process, and the way the store handles "stock". These include enabling support for Containers and Stockrooms, setting how orders affect stock levels (if this feature is used), enabling support for Quotations, and toggling the need for Shipping Methods (not necessary for stores selling purely digital content), among others.

<hr>

### In this article you will fine:

<ul>
<li><a href="#add-to-cart">Add To Cart Settings</a>
<li><a href="#cart-settings">Cart Settings</a>
<li><a href="#payment">Payment</a>
<li><a href="#shipping">Shipping</a>
<li><a href="#securing">Securing</a>
<li><a href="#cart-image">Cart Image Settings</a>
</ul>

<hr>

### Overview Cart Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img50.png" class="example"/>

<hr>

<!-- Add To Cart Settings -->
<h2 id="add-to-cart">Add To Cart Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img51.png" class="example"/>

<ul>
<li><b>Individual add to cart button - </b>Sets the method in which customers can add products with various attributes to the cart. By default, redSHOP is configured to display one "Add to cart" button per product, and the customer selects an attribute from the ones listed before clicking on that button to add the configured product to the cart. The alternative method would be to display "Add to cart" buttons for each individual product attribute listed, which can add "clutter" to the product details page but at the same time make it easier for customers to add products with specific attributes to the cart. As since this setting affects all products throughout the online store, the shop administrator should pick the option most suitable for their main market. 
<li><b>Available options: </b>Add to cart per product, Add to cart per attribute

<li><b>Allow Pre-order - </b>Sets whether customers have the ability to "pre-order" products, meaning add them to cart and proceed through checkout despite the product being out of stock. This feature is dependent on "Use Stockroom" being set to "Yes", as this feature relies on "virtual stock" that is not physically present yet and the "Stockroom" feature caters for that. When this feature is enabled, the shop administrator sets a date for which the stock for pre-ordered products will arrive, and this will be the date that redSHOP refers to when current stock is finished and "virtual stock" should be taken from when customers place orders. (redSHOP looks at the date and stock levels, among other factors, when considering whether to allow customers to purchase specific products.)

There are several areas involved with the "Pre-order" feature, including within the product details (Stockroom tab), stockrooms and orders placed (including the "Orders" section in the back end). More information is available in the Order Management section. 
<br><b>Available options: </b>Yes, No

<li><b>Quotation mode - </b>Sets whether redSHOP's "Quotation Mode" feature should be enabled and accessible. (Details to follow) More information is available in the Quotation Management section. 
<br><b>Available options: </b>On, Off

<li><b>Enable Add to Cart lightbox (Ajax) - </b>Sets whether to display the AJAX Cart pop-up window in a lightbox, as oppose to the standard behavior where the AJAX Cart window appears overlaying the page content. This is a purely cosmetic feature, however setting it to "Yes" to use the lightbox will make the AJAX Cart window easier to see for certain customers. 
<br><b>Available options: </b>Yes, No

<li><b>Ajax Cart Display Time - </b>The length of time, measured in milliseconds, that the AJAX Cart pop-up window is displayed before it "fades" and disappears. This AJAX Cart pop-up window appears when "AJAX Cart" has been enabled and a customer has clicked on a product's "Add to Cart" button. Setting a value of "3000" will mean the AJAX Cart pop-up window will disappear 3 seconds after it has appeared, a value of "15000" will mean 15 seconds of display, and so on. A value of "0" will display the AJAX Cart pop-up window and it will only disappear when the customer has clicked on either the "Continue Shopping" or "Proceed to Checkout" buttons that appear within the window. The shop administrator can modify the look and content of this AJAX Cart window by modifying the "AJAX Cart Box" template in redSHOP's Template section.

<li><b>Cart timeout in minutes - </b>The length of time (measured in minutes) after a customer has become "inactive" that the cart will keep the customer's cart contents stored before emptying the cart. A value of 20, for example, will instruct redSHOP to store the contents of the customer's cart until the customer has left the site or has otherwise been "inactive" on the site for a period of 20 minutes. The shop administrator should set this to their preference, in particular for situations requiring "added site security".

<li><b>Default Cart/Checkout Itemid - </b>The default ItemID used by the cart template and checkout process that the shop administrator can take advantage of to assign modules and other ItemID-related features to the cart and checkout templates. Whenever a menu item link has been created in Joomla!, it is assigned a menu item ID which is used to refer to the page that the menu item link is linking to, in particular that specific menu item used to link to that page. As redSHOP's internal URL links are generated dynamically and do not have ItemIDs assigned to them, unless a menu item link is created for that redSHOP page. This makes it very difficult to assign modules to those pages, as those links have no ItemID attached for reference. This feature allows the shop administrator to specify an ItemID for the cart and checkout process to use as referral, thereby allowing modules to be assigned to the cart and checkout process templates. This feature is referred to as the "Default ItemID" because it is also possible to redirect customers in specific shopper groups to cart and checkout templates using different ItemIDs, making it possible to customize the cart and checkout process for each individual shopper group. More information on setting up shopper-group specific ItemIDs is available in the "Shopper Groups" section.

<li><b>Pre order Button tooltip - </b>The message that appears when a customer lands on a product page for a product that is out of stock but indicates the item is available for "pre-order". redSHOP comes with a default tooltip message pre-installed ("Product Will Be available on {availability_date}"), designed to indicate the availability date of the product to the customer, but the shop administrator can modify this tooltip message at any time, with the ability to use certain template tags within the content of the message.

<li><b>Add to cart button leads - </b>Sets the redirection behavior of the "Add to Cart" button when the customer has clicked on it. The shop administrator can select between having the customer redirected to the cart upon clicking the "Add to Cart" button and having them redirected to the same page from which they added the product to the cart. 
<br><b>Available options: </b>Directly to cart, Back to current view
</ul>

<hr>

<!-- Cart Settings -->
<h2 id="cart-settings">Cart Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img52.png" class="example"/>

<ul>
<li><b>One step Checkout - </b>Sets whether customers will be using redSHOP's "One Step Checkout" to fill out and complete order details, as oppose to the standard checkout process that involves four steps (or screens) of details to fill out and complete. When set to "Yes", redSHOP uses the "One Step Checkout" template in the Template section to display the details required of the customer to complete prior to order confirmation; this feature can be attractive and preferable to customers as they can enter their details on one page, rather than over the course of four pages, which tends to discourage some customers from completing the checkout process.  
<b>Available options: </b>Yes, No

<li><b>Show Shipping in Cart - </b>Sets whether costs of shipping are displayed in the cart template. By default this is set to "Yes" as most online stores selling physical goods should display the costs of shipping to the customer, however in cases where shipment details are not required and shipping rates do not apply, such as in the case of online stores that sell virtual or digital goods and services, this option should be set to "No".
<br><b>Available options: </b>Yes, No

<li><b>Attribute images in cart - </b>Sets whether to display product attribute images in the cart template. By default, redSHOP is configured to display the main image of the product added to cart, even if a specific attribute has been selected. The attribute selected is mentioned in the details related to the product in the cart, however setting this option to "Yes" will also display the image of the product attribute, assuming there is an image assigned to the selected attribute. 
<br><b>Available options: </b>Yes, No

<li><b>Quantity change in cart - </b>Sets whether the customer should be able to modify the quantity of each item in the cart by modifying the quantity number in the cart's "Quantity" field. It is recommended that this setting is kept at "Yes", while setting this to "No" will remove the quantity field (even if the tags for it are present in the cart template) and leave only the "quantity arrows" to adjust quantities of products in the cart. 
<br><b>Available options: </b>Yes, No

<li><b>Quantity Max Characters - </b>The maximum number of digits that quantities consist of. Setting this value to 3, for example, will allow quantities of up to "999".

<li><b>Default product quantities - </b>The ranges of quantity that can be defined for all products when offering customers the ability to conveniently add more than one of a product at a time. The shop administrator would enter in the quantity values separated by commas, which will result in a "quantity dropdown box" being displayed on the front end product details page (given the right template tag) listed the quantities customers can select from. For example, entering the values "1, 5, 10, 20" in this field will result in the quantity dropdown box offering preset quantity selections of 1, 5, 10 and 20.

<li><b>Link Continue Shopping - </b>The URL link that the shop administrator would like the customer to be redirected to when they click on the "Continue Shopping" button in either the AJAX Cart pop-up window or the cart template. By default the field is left blank, which leaves the default behaviour of the "Continue Shopping" button that redirects the customers back to the main top-level category page (i.e redSHOP's main "Frontpage Category" template).

<li><b>Minimum Order Total - </b>The minimum order total that the products in the order need to amount to before the customer will be allowed to proceed through the checkout process. This feature is useful when the shop administrator wishes to enforce a minimum amount that customers need to order to make purchases, such as ............. . This amount is relative to the main currency being used by the online store (the currency selected for the "Currency" setting), meaning the amount will change and be converted accordingly if the customer chooses to view the product catalog and/or make the order payment in a different currency.
</ul>

<hr>

<!-- Payment -->
<h2 id="payment">Payment</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img53.png" class="example"/>

<ul>
<li><b>Payment Calculation based on - Available options: </b>Total, Subtotal

<li><b>First Invoice Number - </b>The number that redSHOP uses as the first numerical value when generating an invoice number. For example, setting this to "1520000" will tell redSHOP to generate the first invoice number as 1520000 and every invoice after that adding a number to this base number, to generate the example 1520001 and 1520002 accordingly.
</ul>

<hr>

<!-- Shipping -->
<h2 id="shipping">Shipping</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img54.png" class="example"/>

<ul>
<li><b>Show checkbox Use same Address for Billing and Shipping - </b>Sets whether the checkbox marking the "Shipping Address same as Billing Address" during the checkout process should be pre-checked. By default this checkbox is left unchecked and up to the customer to select in the event that the address they want to ship the order to is the same as the billing address they provided, in which case the Billing Address details will be copied to the "Shipping Address" details. Setting this option to "Yes" will have the checkbox pre-checked when the customer is going through the checkout process, meaning the Billing Address details will have already been copied into the Shipping Address details section, and the customer will then have to uncheck this checkbox and fill in Shipping Details manually if the addresses are not the same. The shop administrator should configure this accordingly. 
<br><b>Available options: </b>Yes, No

<li><b>Enable Shipping Method - </b>Sets whether the checkout process will requires shipping methods and shipping details be taken from the customer in order to complete the order. By default, redSHOP sets this option to "Yes" as most online stores selling physical goods require shipment details to be taken down (such as preferred method of shipment and the address the order is sent to), however this option should be set to "No" in cases of online stores selling virtual or digital products and services which do not require shipment details. Setting this to "No" will hide the shipping method and shipping address sections of the checkout process. 
<br><b>Available options: </b>Yes, No

<li><b>Split delivery cost - </b>

<li><b>Delivery time difference for split delivery calculation - </b>

<li><b>Delivery Rule (in week) -</b>
</ul>

<hr>

<!-- Securing -->
<h2 id="securing">Securing</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img55.png" class="example"/>

<ul>
<li><b>SSL URL in Checkout - </b>Sets whether the front end checkout process should be routed through a secure HTTPS link using SSL. By default, this option has been set to "No", however if sensitive information such as credit card details are being entered during the checkout process, it is recommended (and actually legally required) to set this option to "Yes" as an added layer of data security. This feature depends on the shop administrator having correctly set up the SSL security certificate on their site, as enabling this feature without the certificate in place will cause access issues during the checkout process. More information on this is available in the Security and Information Safety section. 
<br><b>Available options: </b>Yes, No

<li><b>SSL in Backend - </b>Sets whether redSHOP's back end should be routed through a secure HTTPS link using SSL. By default, this option has been set to "No" as most shop administrators should have in place some security measures to protect access to the back end and store data. However, if sensitive information such as credit card details are being entered in the back end, it is recommended to set this option to "Yes" as an added layer of data security. This feature depends on the shop administrator having correctly set up the SSL security certificate on their site, as enabling this feature without the certificate in place will cause access issues. More information on this is available in the Security and Information Safety section. 
<br><b>Available options: </b>Yes, No

<li><b>Cart Reservation Message - </b>The message that customers will receive when they attempt to add a product that's almost out of stock to the cart when the last one in stock has been "reserved" in another customer's cart. Essentially, this message is to inform the customer that the product they are attempting to add to cart is no longer available as another customer has taken the last piece.
</ul>

<hr>

<!-- Cart Image Settings -->
<h2 id="cart-image">Cart Image Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img56.png" class="example"/>

<ul>
<li><b>Product Thumb in Cart - </b>width/height<br><br>

<li><b>Watermark in Cart thumb -  </b>Available options: Yes, No<br><br>

<li><b>Add to Cart Image - </b>The image that is used throughout the online store for the "Add to Cart" button. <br><br>

redSHOP comes with an image for this button by default, however the shop administrator can replace this image with another using the "Remove" link to remove the currently assigned image and the "Browse" button to select a replacement image. The image currently assigned is displayed as a preview, which will update when a new image has been uploaded. To upload the new image, the path to the image should be set and the shop administrator click on either the "Apply" or "Save" buttons on the top-right hand corner of the "Global Configuration" section.<br><br>

<li><b>Add to Cart Background - </b>The image that is used throughout the online store for the background of the "Add to Cart" button.<br><br>

redSHOP comes with an image for this button by default, however the shop administrator can replace this image with another using the "Remove" link to remove the currently assigned image and the "Browse" button to select a replacement image. The image currently assigned is displayed as a preview, which will update when a new image has been uploaded. To upload the new image, the path to the image should be set and the shop administrator click on either the "Apply" or "Save" buttons on the top-right hand corner of the "Global Configuration" section.<br><br>

<li><b>Request Quote Image - </b>The image that is used throughout the online store for the "Request Quote" button. This button is only displayed when "Quotation Mode" has been set to "On" and all related settings have been configured.<br><br>

redSHOP comes with an image for this button by default, however the shop administrator can replace this image with another using the "Remove" link to remove the currently assigned image and the "Browse" button to select a replacement image. The image currently assigned is displayed as a preview, which will update when a new image has been uploaded. To upload the new image, the path to the image should be set and the shop administrator click on either the "Apply" or "Save" buttons on the top-right hand corner of the "Global Configuration" section.<br><br>

<li><b>Request Quote Background Image - </b>The image that is used throughout the online store for the background of the "Request Quote" button. This button is only displayed when "Quotation Mode" has been set to "On" and all related settings have been configured.<br><br>

redSHOP comes with an image for this button by default, however the shop administrator can replace this image with another using the "Remove" link to remove the currently assigned image and the "Browse" button to select a replacement image. The image currently assigned is displayed as a preview, which will update when a new image has been uploaded. To upload the new image, the path to the image should be set and the shop administrator click on either the "Apply" or "Save" buttons on the top-right hand corner of the "Global Configuration" section.<br><br>

<li><b>Add to cart update image - </b>The image that is used in the cart template for the "Update Quantity" button.<br><br>

redSHOP comes with an image for this button by default, however the shop administrator can replace this image with another using the "Remove" link to remove the currently assigned image and the "Browse" button to select a replacement image. The image currently assigned is displayed as a preview, which will update when a new image has been uploaded. To upload the new image, the path to the image should be set and the shop administrator click on either the "Apply" or "Save" buttons on the top-right hand corner of the "Global Configuration" section.<br><br>

<li><b>Add to cart delete image - </b>The image that is used in the cart template for the "Delete Product" button.<br><br>

redSHOP comes with an image for this button by default, however the shop administrator can replace this image with another using the "Remove" link to remove the currently assigned image and the "Browse" button to select a replacement image. The image currently assigned is displayed as a preview, which will update when a new image has been uploaded. To upload the new image, the path to the image should be set and the shop administrator click on either the "Apply" or "Save" buttons on the top-right hand corner of the "Global Configuration" section.
</ul>

<hr>

<h6>Last updated on Jul 19, 2019</h6>