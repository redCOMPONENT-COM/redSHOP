## redSHOP Configuration - Categories
In this section the global configuration for Categories in redSHOP is set.  The section contains the "Main Category Settings", "Category Suffixes", "Next/Previous", "Template", "Image Settings" and "Default Images".

<hr>

### In this article you will fine:

<ul>
<li><a href="#main-category">Main Category Settings</a>
<li><a href="#category">Category Suffixes</a>
<li><a href="#next">Next/Previous</a>
<li><a href="#template">Template</a>
<li><a href="#image">Image Settings</a>
<li><a href="#default">Default Images</a>
</ul>

<hr>

### Overview Categories Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img10.png" class="example"/>

<hr>

<!-- Main Category Settings -->
<h2 id="main-category">Main Category Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img11.png" class="example"/>

<ul>
<li><b>Default Category Sorting - </b>The default order in which categories are sorted and displayed on category pages within the product catalog on the front end. The customer can modify this ordering to their preference when the "Sort Order" dropdown selection box is available on the category page, however the shop administrator can set the initial / default sorting according to:
    <ul>
    <li><b>Category - </b>this setting will list categories based on alphabetical order
    <li><b>Newest - </b>this setting will list categories based on the date they were created and published, starting with the latest and ending with the oldest
    <li><b>Order - </b>this setting will list categories based on the "Order" value the shop administrator has assigned to them in the back end.
    </ul>

<li><b>No. of Categories per page - </b>The number of categories displayed on category pages within the product catalog. If there are more categories to be displayed than the value set here, such as when there are 12 categories and this value has been set to 9, then pagination links will appear and the categories will be displayed on two pages, such as 9 on "Page One" and 3 on "Page Two". The shop administrator can set this value for all categories in general, however it is possible to overrule this value and specify the number of categories to appear on a per-category basis using the "Standard Category" layout menu items.

<li><b>Enter message you want to give when a product is discontinued - </b>The message that is displayed when a product has been marked as "discontinued" and is no longer available for purchase. Products are "discontinued" when the shop administrator has set the "Product is discontinued" setting to "Yes" within the product details in the back end.

<li><b>Front Page Category Page Introtext - </b>The content of the "introductory" text that is displayed when customers land on the top-level main category page (accessible through the "Standard Category Layout To Show All Category Product" menu item). The shop administrator can design the template for this top-level main category page using the "Frontpage Category" template in the "Templates" section, and the {category_frontpage_introtext} template tag to output the introductory content stored in this field.
</ul>

<hr>

<!-- Next/Previous -->
<h2 id="next">Next/Previous</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img12.png" class="example"/>

<ul>
<li><b>Prefix for Return to Category - </b>Allows the shop adminstrator to append any desired prefix (such as letters, numbers or characters) to the "Return to Category" link that is displayed within the product details page on the front-end. Entering the characters ^^ in this field, for example, once saved will result in the link being displayed as "^^ Return to Category".

<li><b>Prefix for Default text link to Previous - </b>Allows the shop administrator to append any desired prefix (such as letters, numbers or characters) to the "Previous" pagination link wherever pagination links are displayed within the product catalog on the front-end. Entering the characters << in this field, for example, once saved will result in the link being displayed as "<< Previous". Note that the "Previous" text link will only appear if "Default Type of Link to Previous/Next" has been set to "Default Type".

<li><b>Suffix for Default text Link to Next - </b>Allows the shop administrator to append any desired suffix (such as letters, numbers or characters) to the "Next" pagination link wherever pagination links are displayed within the product catalog on the front-end. Entering the characters >> in this field, for example, once saved will result in the link being displayed as "Next >>". Note that the "Next" text link will only appear if "Default Type of Link to Previous/Next" has been set to "Default Type".

<li><b>Custom Link to Previous - </b>Allows the shop administrator to use a different word to represent the "Previous" link that will appear wherever pagination links are displayed within the product catalog on the front-end. Note that this custom word will only be used if "Default Type of Link to Previous/Next" has been set to "Custom Link".

<li><b>Custom Link to Next - </b>Allows the shop administrator to use a different word to represent the "Next" link that will appear wherever pagination links are displayed within the product catalog on the front-end. Note that this custom word will only be used if "Default Type of Link to Previous/Next" has been set to "Custom Link".

<li><b>Upload your image for link to previous page - </b>Allows the shop administrator to upload and use an image to represent the "Previous" link that will appear wherever pagination links are displayed within the product catalog on the front-end. The "Browse" button is used to search for and select the desired image, after which the shop administrator must then click on either "Apply" or "Save" to update the field and the image to be used, while the "Remove File" link remove the reference to the current image. Note that this image will only be used if "Default Type of Link to Previous/Next" has been set to "Image Link".

<li><b>Image for link to Next - </b>Allows the shop administrator to upload and use an image to represent the "Next" link that will appear wherever pagination links are displayed within the product catalog on the front-end. The "Browse" button is used to search for and select the desired image, after which the shop administrator must then click on either "Apply" or "Save" to update the field and the image to be used, while the "Remove File" link remove the reference to the current image. Note that this image will only be used if "Default Type of Link to Previous/Next" has been set to "Image Link".

<li><b>Default Type of Link to Previous/Next - </b>Sets the type of link that will be displayed to represent the "Previous" and "Next" links wherever pagination links are displayed within the product catalog on the front-end. The shop administrator can choose from three types, each one requiring that their related settings be filled in to ensure desired results:

<li><b>Default Link - </b>redSHOP's default, this will display the standard "Previous" and "Next" text links, with options available to append prefix and suffix to each respectively.

<li><b>Custom Link - </b>this will display the custom text words or phrases that have been assigned as replacement for the "Previous" and "Next" text links.

<li><b>Image Link - </b>this will display the images that have been uploaded and assigned as replacement for the "Previous" and "Next" text links.
</ul>

<hr>

<!-- Category Suffixes -->
<h2 id="category">Category Suffixes</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img13.png" class="example"/>

<ul>
<li><b>Max. Characters for Category Description - </b>The maximum number of characters that are displayed for the "Category Description" text on the front end, stored in the the "Category Details" in the back end. Setting this value to 100, for example, will mean only the first 100 characters of the description text will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Category Description Suffix" that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the description than is currently being displayed.

<li><b>Category Description Suffix - </b>The suffix that is appended to the "Category Description" text when the character limit set by the "Max Characters for Category Description" has been reached. Setting this value as ..., for example, will display the category description, the length defined by the above setting, followed by an ellipsis (...) to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the full description to be displayed, while letting the customer know that there is more to the description available.

<li><b>Enter maximal amount of characters for short category description - </b>The maximum number of characters that are displayed for the "Short Category Description" text on the front end, stored in the the "Category Details" in the back end. Setting this value to 100, for example, will mean only the first 100 characters of the description text will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Category Description Suffix" that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the description than is currently being displayed.

<li><b>Short Category Description Suffix - </b>The suffix that is appended to the "Category Short Description" text when the character limit set by the "Enter maximal amount of characters for short category description" has been reached. Setting this value as >>>, for example, will display the category short description, the length defined by the above setting, followed by ">>>" to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete short description to be displayed, while letting the customer know that there is more to the description available.

<li><b>Max. Characters for Category Title on Category View - </b>The maximum number of characters that are displayed for the "Category Title" text on category pages within the product catalog on the front end. Setting this value to 40, for example, will mean only the first 40 characters of the category title will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Category Title Suffix" that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the title than is currently being displayed.

<li><b>Category Title Suffix - </b>The suffix that is appended to the "Category Title" text when the character limit set by the "Max. Characters for Category Title on Category View" has been reached. Setting this value as ###, for example, will display the category title, the length defined by the above setting, followed by "###" to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the title available.

<li><b>Max. Characters for Product Title on Category View - </b>The maximum number of characters that are displayed for the "Product Title" text on category pages within the product catalog on the front end. Setting this value to 40, for example, will mean only the first 40 characters of the product title will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Suffix for Product Title on Category View" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the title than is currently being displayed.

<li><b>Suffix for Product Title on Category View - </b>The suffix that is appended to the "Product Title" text when the character limit set by the "Max. Characters for Product Title on Category View" has been reached. Setting this value as ..., for example, will display the product title, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the title available.

<li><b>Max Characters for Product Description on Category View - </b>The maximum number of characters that are displayed for the "Product Description" text on category pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the product description will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Suffix for Product Description on Category View" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the title than is currently being displayed.

<li><b>Suffix for Product Description on Category View - </b>The suffix that is appended to the "Product Description" text when the character limit set by the "Max Characters for Product Description on Category View" has been reached. Setting this value as ..., for example, will display the product title, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the title available.

<li><b>Max Characters for Short Product Description on Category View - </b>The maximum number of characters that are displayed for the "Product Short Description" text on category pages within the product catalog on the front end. Setting this value to 100, for example, will mean only the first 100 characters of the product short description will be displayed, even if the text cuts off mid-word or mid-sentence. This feature should be used in conjunction with the "Suffix for Short Product Description on Category View" setting that offers the shop administrator the opportunity to append a symbol or other character to indicate to the customer that there is more to the title than is currently being displayed.

<li><b>Suffix for Short Product Description on Category View - </b>The suffix that is appended to the "Product Short Description" text when the character limit set by the "Max Characters for Short Product Description on Category View" has been reached. Setting this value as ..., for example, will display the product title, the length defined by the above setting, followed by "..." to imply there is more text available that cannot be displayed at the moment. This is useful for when the shop administrator has limited space on the web page for the complete title to be displayed, while letting the customer know that there is more text to the title available.
</ul>

<hr>

<!-- Template -->
<h2 id="template">Template</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img14.png" class="example"/>

<ul>
<li><b>Default Category Template - </b>The default template used to display category pages within the product catalog on the front end. The shop administrator can select from a list of available "Category" templates in the "Templates" section to apply to all categories in general, however it is possible to overrule this template and specify category templates on a per-category basis within the category's details. More information on category details is available in the Category section.

<li><b>Main Category List Template - </b>The default template used to display the main top-level category page within the product catalog on the front end. The shop administrator can select from a list of available "Frontpage Category" templates in the "Templates" section to apply to all categories in general, however it is possible to overrule this template and specify category templates on a per-category basis within the category's details. More information on category details is available in the Category section.
</ul>

<hr>

<!-- Image Settings -->
<h2 id="image">Image Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img15.png" class="example"/>

<ul>
<li><b>Show category image in lightbox - </b>Sets whether to display category images in a lightbox when the customer clicks on them for a view of a "bigger picture", the image assigned as the category's main image, within the category page. When set to "Yes" the customer will view the category main image within a lightbox, as oppose to when set to "No" (the default) where the image will open in a new window instead. 

<li><b>Watermark Category Image - </b>Available options: Yes,  No

<li><b>Watermark Category Thumb Image - </b>Available  options: Yes, No
</ul>

<hr>

<!-- Default Images -->
<h2 id="default">Default Images</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img16.png" class="example"/>

<ul>
<li><b>Category default Image - </b>The default image that is displayed for a category that does not yet have an image assigned to it. The shop administrator can use the "Browse" button to search for and select a desired image and the "Remove File" link to remove the path to the currently selected image. A thumbnail preview image is displayed when an image has been assigned and saved.

<li><b>Product Default Image - </b>The default image that is displayed for a product that does not yet have an image assigned to it. The shop administrator can use the "Browse" button to search for and select a desired image and the "Remove File" link to remove the path to the currently selected image. A thumbnail preview image is displayed when an image has been assigned and saved.

<li><b>Watermark - </b>The image that is used to "watermark" all image displayed throughout the product catalog. A "watermark" is a signature image that is stamped onto images displayed to mark them as having come from the online store the customer is visiting, in essence to "brand" any images displayed that the customer might want to download for reference or referral. The shop administrator can use the "Browse" button to search for and select a desired image and the "Remove File" link to remove the path to the currently selected image. A thumbnail preview image is displayed when an image has been assigned and saved. This image will be used for which ever images the shop administrator wishes to watermark, and the controls for watermarking various types of image in the product catalog are available in the Global Configuration "Images" tab. This setting only holds the image used to watermark, images will only be watermarked if the shop administrator has set that image type to be watermarked.

<li><b>Product Out of Stock Image - </b>The image that is displayed to indicate that a product is "out of stock". The shop administrator can use the "Browse" button to search for and select a desired image and the "Remove File" link to remove the path to the currently selected image. A thumbnail preview image is displayed when an image has been assigned and saved.

<li><b>Product Detail LightBox Close Button Image - </b>The image that appears on the top-right corner of the lightbox displaying product detail images that the customer should click on to close it. The shop administrator can use the "Browse" button to search for and select a desired image and the "Remove File" link to remove the path to the currently selected image. A thumbnail preview image is displayed when an image has been assigned and saved.
</ul>

<hr>

<h6>Last updated on July 22, 2019</h6>