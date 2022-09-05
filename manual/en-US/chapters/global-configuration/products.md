## redSHOP Configuration - Products
This section outlines the global configuration settings in redSHOP for Product display and management.  It is comprised of "Unit Settings", "Download", "Wrapping Management", "Catalog Management", "Samples", "Product Template", "Accessory Products" and "Related Products Settings".

<hr>

### In this article you will fine:

<ul>
<li><a href="#products">Products</a>
    <ul>
    <li><a href="#general">General</a>
    <li><a href="#unit">Unit Settings</a>
    <li><a href="#download">Download</a>
    <li><a href="#wrapping">Wrapping Management</a>
    <li><a href="#catalog">Catalog Management</a>
    <li><a href="#samples">Samples</a>
    <li><a href="#product-template">Product Template</a>
    <li><a href="#image">Image Settings</a>
    </ul>

<li><a href="#accessory-products">Accessory Products</a>
    <ul>
    <li><a href="#accessory-settings">Accessory Product Settings</a>
    <li><a href="#accessory-image">Accessory Product Image Settings</a>
    </ul>

<li><a href="#related-products">Related Products</a>
    <ul>
    <li><a href="#related-settings">Related Product Settings</a>
    <li><a href="#related-image">Related Product Image Settings</a>
    </ul>
</ul>

<hr>

### Overview Products Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img22.png" class="example"/>

<hr>

<!-- Products -->
<h2 id="products">Products</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img23.png" class="example"/>

<hr>

<!-- General -->
<h4 id="general">General</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img24.png" class="example"/>

<ul>
<li><b>Default category - Available options:</b> Yes,No
</ul>

<hr>

<!-- Unit Settings -->
<h4 id="unit">Unit Settings</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img25.png" class="example"/>

<ul>
<li><b>Default volume unit - </b>The unit of measurement that will be used throughout the shop wherever measurements of length are displayed and concerned. As different markets are used to measuring with different units of length, the shop administrator should pick the most appropriate for their main market. The shop administrator should also bear the context of the selected unit of measurement in mind when entering values of length for both the product details as well as volume dimensions for shipping boxes and volume ranges for shipping rates. 

<li><b>Available options: </b>Millimeter, Centimeter, Inches, Feet, Meters

<li><b>Default weight unit - </b>The unit of measurement that will be used throughout the shop wherever measurements of weight are displayed and concerned. As different markets are used to measuring with different units of weight, the shop administrator should pick the most appropriate for their main market. The shop administrator should also bear the context of the selected unit of measurement in mind when entering values of weight for both the product details as well as weight ranges for shipping rates. 

<li><b>Available options: </b>Grams, Pounds, Kilos

<li><b>No. of Decimals - </b>Sets the number of decimal places that units of measurement are stored and displayed in. Setting the number 3, for example, will display a measurement of length or weight to 3 decimal places (such as 9.995).
</ul>

<hr>

<!-- Download -->
<h4 id="download">Download</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img26.png" class="example"/>

<ul>
<li><b>Maximal number of downloads - </b>The maximum number of times a customer who has purchased a downloadable product can download it. The shop administrator can set this limit for all downloadable products in general, however it is possible to overrule this limit and specify a maximum number of downloads on a per-product basis within the product's "File type" settings. More information on "File" product types is available in the Product section.

<li><b>Download Period in Days - </b>The length of time (number of days) that a customer will be able to download their downloadable product before the link "expires" and they will need to either repurchase the product or contact the shop administrator for an extension. The shop administrator can set this period of time for all downloadable products in general, however it is possible to overrule this limit and specify the length of time on a per-product basis within the product's "File type" settings. More information on "File" product types is available in the Product section.

<li><b>Download Products root - </b>The folder into which the files used to create downloadable products (such as digital goods) will be stored. By default, redSHOP configures this folder to /components/com_redshop/assets/download/product, starting from the root folder of the Joomla! site where redSHOP is installed, however this path can be modified according to the shop administrator's preference. It is recommended that the administrator modify this path, if need be, prior to uploading the files for those downloadable products, as modifying this path after files have already been uploaded can make the paths to previous uploads invalid, in which case the administrator would have to manually move all related files to the new location.
</ul>

<hr>

<!-- Wrapping Management -->
<h4 id="wrapping">Wrapping Management</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img27.png" class="example"/>

<ul>
<li><b>Default wrapping image thumb width - </b>The default width of the image thumbnail representing the product wrapping available, measured in pixels. More information on wrapping is available in theWrapping Management section.

<li><b>Default wrapping image thumb height - </b>The default height of the image thumbnail representing the product wrapping available, measured in pixels. More information on wrapping is available in theWrapping Management section.

<li><b>Autoscroll for wrapping - </b>Sets whether the wrapping options available for products is presented in a scrolling horizontal gallery on the product details page. This feature makes selecting wrapping simpler as the customer only needs to click on the image of the wrapper of their choice to select it. More information on wrapping is available in the Wrapping Management section.
<li><b>Available options: </b>No, Yes
</ul>

<hr>

<!-- Catalog Management -->
<h4 id="catalog">Catalog Management</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img28.png" class="example"/>

<ul>
<li><b>Catalog Reminder 1 - </b>The number of days after a customer has requested and signed up to receive a catalog before they receive their first catalog reminder mail. The template for this first email can be found in the Mail Center, labelled "Catalog First Reminder".

<li><b>Catalog Reminder 2 - </b>The number of days after a customer has been sent their first catalog reminder mail before they receive their second catalog reminder mail. The template for this second email can be found in the Mail Center, labelled "Catalog Second Reminder".

<li><b>Discount Duration in Days - </b>The number of days after the catalog has been sent to the customer before the discount coupon included in the mail expires. More information regarding catalog-related email templates is available in the "Mail Center" section.

<li><b>Discount percentage - </b>The percentage value of the discount coupon that is sent in the catalog mail to the customer.

<li><b>Enable Catalog Reminder - </b>Sets whether redSHOP should send catalog reminder emails to customers who have requested and signed up to receive catalogs. This feature is useful for shop administrators who want an automated way to remind customers who have not yet downloaded their catalogs to do so. 
<li><b>Available options: </b>No, Yes
</ul>

<hr>

<!-- Samples -->
<h4 id="samples">Samples</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img29.png" class="example"/>

<ul>
<li><b>Sample Reminder 1 - </b>The number of days after a customer has requested and signed up to receive a product sample before they receive their first product sample reminder mail. The template for this first email can be found in the Mail Center, labelled "Catalog Sample First Reminder".

<li><b>Sample Reminder 2 - </b>The number of days after a customer has been sent their first product sample reminder mail before they receive their second reminder mail. The template for this second email can be found in the Mail Center, labelled "Catalog Sample Second Reminder".

<li><b>Sample Reminder 3 - </b>The number of days after a customer has been sent their first catalog reminder mail before they receive their second catalog reminder mail. The template for this second email can be found in the Mail Center, labelled "Catalog Sample Third Reminder".

<li><b>Discount Duration in Days - </b>The number of days after the product sample has been sent to the customer before the discount coupon included in the mail expires. More information regarding sample-related email templates is available in the "Mail Center" section.

<li><b>Discount percentage - </b>The percentage value of the discount coupon that is sent in the product sample mail to the customer.
</ul>

<hr>

<!-- Product Template -->
<h4 id="product-template">Product Template</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img30.png" class="example"/>

<ul>
<li><b>Default Product template - </b>The default template used to display product details pages within the product catalog on the front end. The shop administrator can select from a list of available "Product" templates in the "Templates" section to apply to all products in general, however it is possible to overrule this template and specify product templates on a per-product basis within the product's details. More information on product details is available in the Product section.

<li><b>Default sorting of products - </b>The default order in which products are sorted and displayed on category pages within the product catalog on the front end. The customer can modify this ordering to their preference when the "Product Sort Order" dropdown selection box is available on the category page, however the shop administrator can set the initial / default sorting according to:
    <ul>
    <li><b>Product Name - </b>this setting will list products based on alphabetical order
    <li><b>Price, ascending - </b>this setting will list products based on price in ascending order, starting with the lowest price and ending with the highest
    <li><b>Price, descending - </b>this setting will list products based on price in descending order, starting with the highest price and ending with the lowest
    <li><b>Product Number - </b>this setting will list products based on their assigned product number (SKU), starting with the lowest number and ending with the highest
    <li><b>Newest - </b>this setting will list products based on the date they were created and published, starting with the latest and ending with the oldest
    <li><b>Order - </b>this setting will list products based on the "Order" value the shop administrator has assigned to them in the back end.
    </ul>

<li><b>Display out of stock after normal products - </b>the orders product will show from product have status In stock to Out of stock when user select on "Yes". Click "No" when page will show messy product In Stock and Out of Stock

<li><b>Display Out of Stock Attribute Data - </b>Available options: Yes, No
</ul>

<hr>

<!-- Image Settings -->
<h4 id="image">Image Settings</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img31.png" class="example"/>

<ul>
<li><b>Show Product Images in Lightbox  - </b>Sets whether to display product images in a lightbox when the customer clicks on them for a view of a "bigger picture", the image assigned as the product's main image, within the product details page. When set to "Yes" the customer will view product images within a lightbox, as oppose to when set to "No" (the default) where the image will open in a new window instead.  
<li><b>Available options: </b>Yes, No  

<li><b>Show Product Detail Image in Lightbox - </b>Sets whether to display the main product image in a lightbox when the customer clicks on it for a view of a "bigger picture", the image assigned as the product's main image, within the product details page. When set to "Yes" the customer will view the product main image within a lightbox, as oppose to when set to "No" (the default) where the image will open in a new window instead.
<li><b>Available options: </b>Yes, No

<li><b>Show Additional Product Images in Lightbox - </b>Sets whether to display additional product images in a lightbox when the customer clicks on them for a view of a "bigger picture", any additional images assigned to the product, within the product details page. When set to "Yes" the customer will view the category main image within a lightbox, as oppose to when set to "No" (the default) where the image will open in a new window instead. 
<li><b>Available options: </b>Yes, No

<li><b>Product Main Image width/height -</b>

<li><b>Product Main Image 2 width/height -</b>

<li><b>Product Main Image 3 width/height -</b>

<li><b>Product Additional Image  width/height -</b>

<li><b>Product Additional Image 2 width/height -</b>

<li><b>Product Additional Image 3 width/height -</b>

<li><b>Water Mark Product Image - Available options: </b>Yes, No

<li><b>Water Mark Product Thumb Image - Available options: </b>Yes, No

<li><b>Water Mark Product additional Image - Available options:</b> Yes, No

<li><b>Product Hover Image Enable - Available options: </b>Yes, No

<li><b>Additional Hover Image Enable - Available options: </b>Yes, No

<li><b>Product Hover Image width/height -</b>

<li><b>Product preview image width/height -</b>

<li><b>Product preview category view image width/height -</b>

<li><b>Attribute scroller thumb image width/height -</b>

<li><b>No. of thumbs in attribute scroller - </b>The number of images (or image thumbnails rather) that appear in the "Attribute Scroller" on the product page, the "Attribute Scroller" being a horizontal image gallery representing the product attributes available. The "Attribute Scroller" can be useful in offering graphical representations of products with specific attributes, although for the gallery to work each attribute needs to have an image assigned to it. Customers can click on the image of the product attribute to select it, as oppose to the standard method of selecting from a list or set of radio buttons / checkboxes. The shop administrator can display this scroller using the "" tag in the product details template. More information on attributes is available in the Product section.

<li><b>No. of thumbs in sub-attribute scroller - </b>The number of images (or image thumbnails rather) that appear in the "Sub-Attribute Scroller" on the product page, the "Sub-Attribute Scroller" being a horizontal image gallery representing the product attributes available. The "Sub-Attribute Scroller" can be useful in offering graphical representations of products with specific attributes, although for the gallery to work each attribute needs to have an image assigned to it. Customers can click on the image of the product attribute to select it, as oppose to the standard method of selecting from a list or set of radio buttons / checkboxes. The shop administrator can display this scroller using the "" tag in the product details template. More information on attributes is available in the Product section.

<li><b>Pre-order Image - </b>The image that is displayed to indicate that a product is "available for pre-order". The shop administrator can use the "Browse" button to search for and select a desired image and the "Remove File" link to remove the path to the currently selected image. A thumbnail preview image is displayed when an image has been assigned and saved.
</ul>

<hr>

<!-- Accessory Products -->
<h2 id="accessory-products">Accessory Products</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img32.png" class="example"/>

<hr>

<!-- Accessory Product Settings -->
<h4 id="accessory-settings">Accessory Product Settings</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img33.png" class="example"/>

<ul>
<li><b>Show Accessory as individual product in cart - Available options: </b>Yes, No

<li><b>Show Accessory Product in Lightbox - Available options: </b>Yes, No

<li><b>Default Accessory Sorting - </b>The default order in which accessory products are sorted and displayed on the product details pages within the product catalog on the front end. The customer can modify this ordering to their preference when the "Product Sort Order" dropdown selection box is available on the product details page, however the shop administrator can set the initial / default sorting according to:
    <ul>
    <li><b>Product Name - </b>this setting will list products based on alphabetical order
    <li><b>Price, ascending - </b>this setting will list products based on price in ascending order, starting with the lowest price and ending with the highest
    <li><b>Price, descending - </b>this setting will list products based on price in descending order, starting with the highest price and ending with the lowest
    <li><b>Product Number - </b>this setting will list products based on their assigned product number (SKU), starting with the lowest number and ending with the highest
    <li><b>Newest - </b>this setting will list products based on the date they were created and published, starting with the latest and ending with the oldest
    <li><b>Order - </b>this setting will list products based on the "Order" value the shop administrator has assigned to them in the back end.
    </ul>

(Select: Product Id Asc, Product Id Desc, Accessory Id Asc, Accessory Id Desc, Accessory Price Asc, Accessory Price Desc, Ordering Asc, Ordering Desc)

<li><b>Max. Characters for Related Product Description - </b>The maximum number of characters that are displayed for the "Accessory Product Description" text on product details pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the accessory product's description will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Accessory Product description end suffix" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the description than is currently being displayed.

<li><b>Accessory Product description end suffix - </b>The suffix that is appended to the "Accessory Product Description" text when the character limit set by the "Max. Characters for Related Product Description" has been reached. Setting this value as ..., for example, will display the accessory product's title, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the description available.

<li><b>Enter max. No. Of Characters for Accessory Product Title -</b> The maximum number of characters that are displayed for the "Accessory Product Description" text on product details pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the accessory product's title will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Accessory Product Title Suffix" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the title than is currently being displayed.

<li><b>Accessory Product Title Suffix - </b>The suffix that is appended to the "Accessory Product Title" text when the character limit set by the "Enter max. No. Of Characters for Accessory Product Title" has been reached. Setting this value as ..., for example, will display the accessory product's title, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the title available.
</ul>

<hr>

<!-- Accessory Product Image Settings -->
<h4 id="accessory-image">Accessory Product Image Settings</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img34.png" class="example"/>

<ul>
<li><b>Main Accessory Thumb  width/height -</b>

<li><b>Main Accessory Thumb #2  width/height -</b>

<li><b>Main Accessory Thumb #3  width/height -</b>
</ul>

<hr>

<!-- Related Products -->
<h2 id="related-products">Related Products</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img35.png" class="example"/>

<hr>

<!-- Related Product Settings -->
<h4 id="related-settings">Related Product Settings</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img36.png" class="example"/>

<ul>
<li><b>Two-way related product - </b>When this has been set to "Yes", any product assigned as related to another will appear on the related product's details page as well as the original product's details page. To illustrate this, redSHOP's normal behavior in regards to related products is to display Product A and the related Product B on Product A's page, but visiting Product B's details page will not indicate that it is related to Product A. Setting this option to "Yes" will ensure that Product A will appear on Product B's page as well. 
<li><b>Available options: </b>Yes, No

<li><b>Child Product Dropdown Output - </b>Sets whether any "child products" assigned to a "parent" product will be displayed as and referred to by their product name or product number within the dropdown select box that appears on the product details page. 
<li><b>Available options: </b>Product Name, Product Number

<li><b>Parent Product should be purchasable(if any child product is added) - </b>Sets whether it is possible for customers to add "parent" products to the cart if any of the "child products" have been added to the cart as well. Normally, child products are considered "variations" of the main product, and therefore adding a child product to the cart is like adding a variation of the main product, which normally means you cannot add that main product to the cart without selecting one of its variations. Setting this option to "Yes" will allow customers to add both the main product and any of its child product variations. 
<li><b>Available options: </b>Yes, No

<li><b>Default sorting of related products - </b>The default order in which related products are sorted and displayed on the product details pages within the product catalog on the front end. The customer can modify this ordering to their preference when the "Product Sort Order" dropdown selection box is available on the product details page, however the shop administrator can set the initial / default sorting according to:
    <ul>
    <li><b>Product Name - </b>this setting will list products based on alphabetical order
    <li><b>Price, ascending - </b>this setting will list products based on price in ascending order, starting with the lowest price and ending with the highest
    <li><b>Price, descending - </b>this setting will list products based on price in descending order, starting with the highest price and ending with the lowest
    <li><b>Product Number - </b>this setting will list products based on their assigned product number (SKU), starting with the lowest number and ending with the highest
    <li><b>Newest - </b>this setting will list products based on the date they were created and published, starting with the latest and ending with the oldest
    <li><b>Order - </b>this setting will list products based on the "Order" value the shop administrator has assigned to them in the back end.
    </ul>

<li><b>Related Product Description Max. Characters - </b>The maximum number of characters that are displayed for the "Related Product Description" text on product details pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the related product's description will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Related Product Description Suffix" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the description than is currently being displayed.

<li><b>Related Product Description Suffix - </b>The suffix that is appended to the "Related Product Description" text when the character limit set by the "Related Product Description Max. Characters" has been reached. Setting this value as ..., for example, will display the related product's description, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the description available.

<li><b>Related Product Short Description Max Characters - </b>The maximum number of characters that are displayed for the "Related Product Short Description" text on product details pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the related product's short description will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Related Product Short Description Suffix" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the description than is currently being displayed.

<li><b>Related Product Short Description Suffix - </b>The suffix that is appended to the "Related Product Short Description" text when the character limit set by the "Related Product Short Description Max Characters" has been reached. Setting this value as ..., for example, will display the related product's short description, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the short description available.

<li><b>Related Product Title Max. Characters - </b>The maximum number of characters that are displayed for the "Related Product Title" text on product details pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the related product's title will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Related Product Title Suffix" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the title than is currently being displayed.

<li><b>Related Product Title Suffix - </b>The suffix that is appended to the "Related Product Title" text when the character limit set by the "Related Product Title Max. Characters" has been reached. Setting this value as ..., for example, will display the related product's title, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the title available.
</ul>

<hr>

<!-- Related Product Image Settings -->
<h4 id="related-image">Related Product Image Settings</h4>

<img src="./manual/en-US/chapters/global-configuration/img/img37.png" class="example"/>

<ul>
<li><b>Related Product Thumb width/height -</b>

<li><b>Related Product Thumb #2 width/height -</b>

<li><b>Related Product Thumb #3 width/height -</b>

</ul>

<hr>

<h6>Last updated on July 23, 2020</h6>