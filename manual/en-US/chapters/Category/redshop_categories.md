## Working with redSHOP Categories
Categories are at the heart of organizing your product catalog within redSHOP. By grouping and segmenting your products into logically defined categories, you make it easier for your customers to better understand the range of your offerings. You have total control over the design and feel of the category pages that your customers see, and the ability to set different individual templates for each category and even offer different layouts for your product displays to make it easier to show your products in the best light. Categories can also be useful when you want to restrict certain products for certain people or companies, which can be done with shopper groups, as well as assigning mass discounts, product wrapping options and shipping rates for specific grouped products.

<hr>

### In this article you will fine

<ul>
<li><a href="#listingScreen">Overview of Category Listing screen</a>
<li><a href="#detailsScreen">Overview of Category Details screen</a>
<li><a href="#withRedshop">Working with Category within redSHOP</a>
    <ul>
    <li><a href="#createItems">Create Category items</a>
    <li><a href="#productsDiscounts">Assigning products and discounts</a>
    <li><a href="#accessSeo">Page Access and SEO</a>
    <li><a href="#packagingRates">Shipping packaging and rates</a>
    <li><a href="#categoryData">Importing and exporting category data</a>
    <li><a href="#templatesTags">Templates and tags</a>
    <li><a href="#categoryFrontend">Show Category on Frontend</a>
    </ul>
</ul>

<hr>

Firstly you have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided

<img src="./manual/en-US/chapters/category/img/administrator.png" class="example"/>

Secondly you click on Component on main menu and select on "redSHOP"

<img src="./manual/en-US/chapters/category/img/img1.png" class="example"/>

Finally webpage will display overview page administrator of redSHOP and click on Category tab 

<img src="./manual/en-US/chapters/category/img/img2.png" class="example"/>

To access the category section, click on the category button on redSHOP's main menu or click on the category list link on the left-hand side navigation panel when you're looking at the listing view anywhere else within redSHOP. You will end up directly on the category management screen, where you will see details listed for all the categories that are currently available.

<hr>

<!-- Overview of Category Listings Screen -->
<h2 id="listingScreen">Overview of Category Listings Screen</h2>
<h5>In Overview of Category Listings screen</h5>
<ul>
<li><a href="#field1">Field</a>
<li><a href="#action1">Action</a>
</ul>

<hr>

<img src="./manual/en-US/chapters/category/img/img3.png" class="example"/>

<h4 id="field1">Field</h4>

<b>(1) Category - </b>the name of the category, as it will appear on the front-end (note: subcategories will be displayed indented and with "branches", the length of the branches indicating the relationships between each one)

<b>(2) Category Description - </b>the short description given and stored in this category's details 

<b>(3) Products - </b>the number of products currently assigned to this category 

<b>(4) Order - </b>the order in which you would like your categories to appear on the front-end 

<b>(5) Published - </b>sets whether the category will be displayed on the front-end (note: subcategories will also be hidden from view if the main category is unpublished, regardless whether they are published are not themselves)

<hr>

<h4 id="action1">Action</h4>

<img src="./manual/en-US/chapters/category/img/img4.png" class="example"/>

<b>New - </b>appears when adding a new category

<b>Copy - </b>copy 1 or more items has selected. Some information of category items has copy will same with has selected before and name items will increase follow times copy 

<b>Delete - </b>delete 1 or more items has selected. Items has deleted will remove list category items 

<b>Publish - </b>it change status from unpublish to publish for items category is working

<b>Unpublish - </b>it change status from unpublish to publish for items category not working

<b>Check-in - </b>will unlock any category itemswhen someone viewing it 

<hr>

<!-- Overview of Category Details screen -->
<h2 id="detailsScreen">Overview of Category Details Screen</h2>
<h5>In Overview of Category Details screen</h5>
<ul>
<li><a href="#informationTab">Category Information tab</a>
<li><a href="#imagesTab">Category Images tab</a>
<li><a href="#seoTab">SEO tab</a>
<li><a href="#fieldsTab">Custom Fields tab</a>
<li><a href="#accessoriesTab">Accessories tab</a>
<li><a href="#action2">Action</a>
</ul>

<hr>

<h4 id="informationTab">Category Information tab</h4>

<img src="./manual/en-US/chapters/category/img/img5.png" class="example"/>

<h4>Field</h4>

<b>Category - </b>the name of the category, as you would like it to appear on the front-end 

<b>Category parent - </b>offers the ability to make this category a subcategory of another one. To make this the main category, leave this option sets to -Top-, otherwise select the category you would want to group this one under. 

<b>Published - </b>sets whether the category and be displayed on the front-end (note: subcategories will also be hidden from view if the main category is unpublished, regardless whether they are published are not themselves) 

<b>No. of products per page - </b>if there is a large number of products assigned to this category, the number of pages that customers will go through when looking through this category will be determined by the number of products that are displayed on each page 

<b>Template - </b>using a drop-down menu listing all available category templates, this setting lets you assign the template that will be used when displaying this category on the front-end 

<b>Allowed templates - </b>allows you to assign multiple category templates to be made available to the customer to view this category using different layouts. Hold down the control key while clicking on the category templates available to make your selection, and save your changes. Then go over to the redSHOP template section and make sure that somewhere in your category template, in an appropriate location, appears the {template_selector_category} tag, which when displayed on the front-end will output a drop-down menu with the layouts your customers can choose from 

<b>Product Comparison Template - </b>sets the template that will be used when customers select products to compare against each other from within the same category; this feature can be useful as you can design different comparison tables for products in different categories. The "enable product comparison" setting needs to be set to yes for you to take advantage of this feature.

<b>Short Description - </b>a space to store a brief description of the category and/or the products in it; you can insert text, links, HTML and calls for plug-ins or modules Description - a space to store a more elaborate description of the category and/or the products in it; you can insert text, links, HTML and calls for plug-ins or modules

<hr>

<h4 id="imagesTab">Category Images tab</h4>

<img src="./manual/en-US/chapters/category/img/img6.png" class="example"/>

<h4>Field</h4>

<b>Category Image - </b>the image assigned to represent the category when displayed on the front-end; you can either upload an image from your PC using the browse button or select one from redSHOP's media library using the image button. Category image dimensions can be adjusted in redSHOP's global configuration section, and can be displayed within category templates using the relevant template tags, such as {category_main_thumb_image} or {category_thumb_image}. 

<b>Back Image - </b>an alternative image assigned to represent the category when displayed on the front-end, used when appropriate.

<hr>

<h4 id="seoTab">SEO tab</h4>

<img src="./manual/en-US/chapters/category/img/img7.png" class="example"/>

<h4>Field</h4>

<b>SEO Page Title - </b>the title of the category page, as it will be displayed in search engine results and in the browser's title bar or tab 

<b>SEO Page Heading - </b>the main heading for this categories page

<b>SEF Url - </b>the search engine friendly URL that will be used instead of the standard Joomla! URL that is generated for this category page 

<b>SEO Keywords - </b>a space to insert the meta-keywords that are related to this category page 

<b>SEO Page Description - </b>a space to insert the method description that is related to this category page 

<b>Meta Language Setting - </b>a space to include the languages for which these SCO settings will apply for this category page, indicated by the Joomla! type language and country codes separated by commas, for example "en-GB, fr-FR" 

<b>SEO Robot Info - </b>a space to include specific instructions for search engine web crawlers and bots , if the instructions for this page differ from the general instructions that can be put in redSHOP's global configuration SCO tab

<hr>

<h4 id="fieldsTab">Custom Fields tab</h4>

<img src="./manual/en-US/chapters/category/img/img8.png" class="example"/>

This is the space where all custom category fields that are relevant to this category will appear. More information on what custom fields are and how they work and are used is available in the custom fields section, but in general to assign custom fields to a category you need to set the relevant custom fields to belong in the category section and that the tags for those fields appear somewhere in the category template that is currently assigned to that category.

<hr>

<h4 id="accessoriesTab">Accessories tab</h4>
<h5>In Accessories tab</h5>
<ul>
<li><a href="#field2">Field</a>
<li><a href="#action2">Action</a>
</ul>

With this feature, you can assign accessory products for all products within this category as well as their price conditions. The accessories that you assign to this category will appear as accessories on the product pages of the products within, even if they have not been assigned to the product specifically. If an accessory has been assigned to a product twice, that is in the category accessories tab and the product accessories tab, the price rules stored in the product accessories tab will be the ones redSHOP uses.

<img src="./manual/en-US/chapters/category/img/img9.png" class="example"/>

<hr>

<h4 id="field2">Field</h4>

<b>(1) Product Source - </b>an Ajax-powered input box from where you can look up products to add as accessories; type at least three characters that appear in the name of the product you're looking for and wait a moment for a drop-down panel with related search results to appear, then click on the name of the product to select it.

<b>(2) Product Name - </b>the name of the product

<b>(3) Normal Price - </b>the normal price currently assigned to this product

<b>(4) Sign (+/-) - </b>sets whether an amount should be added or removed from the normal price to result in the promotional price 

<b>(5) Amount added - </b>the value of the amount that should be added to or removed from the normal price 

<b>(6) Default Order - </b>the order in which you would like the accessories displayed on the product page 

<b>(7) Delete - </b>removes the accessory product assignment

<hr>

<h4 id="action2">Action</h4>

<img src="./manual/en-US/chapters/category/img/img10.png" class="example"/>

<b>Save - </b>saves changes made and refreshes the page 

<b>Save & Close - </b>saves changes made and redirect back to the category listing screen 

<b>Save & New - </b>saves changes made and return to the category create page new items other 

<b>Cancel - </b>returns to the category listing screen without saving changes

Once you have added a few categories to your product catalog, you can start making use of all the category related features and modules, as well as configuring the look and feel of the templates that will be used to display categories on the front-end.

<hr>

<!-- Working with categories within redSHOP -->
<h2 id="withRedshop">Working with categories within redSHOP</h2>

An elaborate product catalog can benefit from a well structured category system; with the instructions above you can create an unlimited number of categories and nested subcategories.

<h4>Create Category Items</h4>

<ul>
<li>Go to backend page of REDSHOP and select "Categories" tab
<img src="./manual/en-US/chapters/category/img/img11.png" class="example"/><br><br>

<li>Category page will display Overview of Category Listings screen. Click on "New" button to create item
<img src="./manual/en-US/chapters/category/img/img12.png" class="example"/><br><br>

<li>User fill in some field as: Category, No. of Products per Page, Templates, Category Images
<img src="./manual/en-US/chapters/category/img/img13.png" class="example"/><br><br>

<li>Fill in SEO page title, SEO page heading.
<img src="./manual/en-US/chapters/category/img/img14.png" class="example"/><br><br>

<li>Click on Save button
<img src="./manual/en-US/chapters/category/img/img15.png" class="example"/><br><br>
</ul>

Video for create Category items: <a href="https://redshop.fleeq.io/l/c9vh9e92pn-uibe25l1fp">Click here</a>

<hr>

<!-- Assigning products and discounts -->
<h2 id="productsDiscounts">Assigning products and discounts</h2>
<h5>In Assigning products and discounts</h5>
<ul>
<li><a href="#createProductCategory">Create product have category just create</a>
<li><a href="#massDiscount">Mass discount</a>
<li><a href="#productPriceDiscount">Product price discount</a>
</ul>

To assign a product to a category, head over to the product listing page within the product section and open up the desired product details. Then from within the "Product Information" tab, simply look for the "product category" setting and choose the category in which to assign this product. Selecting the -Top- category will assign the product to the main front-page category, while holding down control on the keyboard and clicking on multiple category names will assign the product to all those categories. Be mindful of assigning products to multiple categories, especially when taking into consideration shopper group access levels, category specific promotions and category dependent shipping rates.

<hr>

<h4 id="createProductCategory">Create product have category just create</h4>

<ul>
<li>Go to backend page of redSHOP and click Products then select "Product Management" tab. After webpage display Product management, user click on New button
<img src="./manual/en-US/chapters/category/img/img16.png" class="example"/><br><br>

<li>User fill in some field as: name, product number, product category, product price, product image, ... => Click "Save" button
<img src="./manual/en-US/chapters/category/img/img17.png" class="example"/><br><br>

<li>After save item, click on "Preview" button go to front-end view product detail and see it in category "Dress"
<img src="./manual/en-US/chapters/category/img/img18.png" class="example"/><br><br>

<li>Go to front-end view product detail and see it in category "Dress"
<img src="./manual/en-US/chapters/category/img/img19.png" class="example"/><br><br>
</ul>

Video for add category in Product Category when user create new product: <a href="https://redshop.fleeq.io/l/su81e6uu6h-3lhiqlbx4q">Click here</a>