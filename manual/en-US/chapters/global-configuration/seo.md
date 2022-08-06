## redSHOP Configuration - SEO
This section covers configuration settings regarding the handling of search-engine friendly URLs (SEF) for all links related to redSHOP and its product catalog. These include specifying the details that appear in generated SEF URLs, the auto-generation of metadata for pages which have not specified metadata, and the "templates" that are used (assisted by tags) to generate default metadata for categories, products and manufacturers. The controls are grouped together into four sections: "General", "Categories", "Products", and "Manufacturers".

<hr>

### In this article you will fine:

<ul>
<li><a href="#general">General</a>
<li><a href="#category">Category</a>
<li><a href="#products">Products</a>
<li><a href="#manufacturer">Manufacturer</a>
<li><a href="#tags">Available SEO Tags</a>
    <ul>
    <li><a href="#tags1">Available Tags for Page Titles</a>
    <li><a href="#tags2">Available Tags for Headings</a>
    <li><a href="#tags3">Available Tags for Page Descriptions</a>
    <li><a href="#tags4">Available Tags for Keywords</a>
    </ul>
</ul>

<hr>

### Overview SEO Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img68.png" class="example"/>

<hr>

<!-- General -->
<h2 id="general">General</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img69.png" class="example"/>

<ul>
<li><b>Enable SEF Product Numbers - </b>Sets whether product numbers should be included when generating SEF URLs for product details pages.  More information is available in the "SEO" section. 
<br><b>Available options: </b>Yes, No

<li><b>Generate Joomla SEF Url by - </b>Sets whether SEF URLs, when Joomla! has been selected to handle generation of SEF URLs, should be generated using the ID or the Name of the product / category / manufacturer when browsing the product catalog.
<br><b>Available options: </b>ID, Name

<li><b>Category in SEF Url - </b>Sets whether category names should be included when generating SEF URLs for pages within the product catalog that involve categories, such as category and product details pages. An example of a URL when this setting has been set to "Yes" looks like this: .................................. More information is available in the "SEO" section. 
<br><b>Available options: </b>Yes, No

<li><b>Auto generated Meta Data - </b>Sets whether redSHOP should automatically generate meta data for category, manufacturer and product detail pages if they do not already have meta data values assigned to them. The meta data generated includes page titles, page headings, page descriptions and meta keywords. More information is available in the "SEO" section. 
<br><b>Available options: </b>Yes, No

<li><b>SEO Language - </b>The language that SEO generated content (such as metadata and SEF URLs) will be presented in. The default language set here is English, indicated by "en-GB", but the shop administrator should enter the language code for the preferred language, as it is written for Joomla! language packs, however the language pack that the code refers to must be installed and enabled before redSHOP can use it. More information on language-related settings is available in the "Language Support" section.
</ul>

<hr>

<!-- Category -->
<h2 id="category">Category</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img70.png" class="example"/>

<ul>
<li><b>SEO Page Titles - </b>The template that will be used to generate the page titles on category pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{categoryname} | {shopname}", which will generate a category page title that appears as "Name of category | Name of online store". redSHOP will use the template defined here to generate all category page titles, unless the shop administrator has set a different specific template within the "SEO" tab of the category's details in the back end.

<li><b>SEO Page Headings - </b>The template that will be used to generate the page headings on category pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{categoryname}", which will generate a category heading that appears as "Name of category". redSHOP will use the template defined here to generate all category page headings, unless the shop administrator has set a different specific template within the "SEO" tab of the category's details in the back end.

<li><b>SEO Page Descriptions - </b>The template that will be used to generate the meta descriptions for category pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{categoryname} - {shopname}", which will generate the meta description for the category page that appears as "Name of category - Name of online store". redSHOP will use the template defined here to generate the meta descriptions for all category pages, unless the shop administrator has set a different specific template within the "SEO" tab of the category's details in the back end.

<li><b>SEO Keywords - </b>The template that will be used to generate the meta keywords for category pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{categoryname}, {shopname}", which will generate meta keywords for the category page that appear as "Name of category, name of online store". redSHOP will use the template defined here to generate the meta keywords on all category pages, unless the shop administrator has set a different specific template within the "SEO" tab of the category's details in the back end.
</ul>

<hr>

<!-- Products -->
<h2 id="products">Products</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img71.png" class="example"/>

<ul>
<li><b>SEO Page Titles - </b>The template that will be used to generate the page titles on product details pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{productname} | {categoryname} | {productname} from {shopname}", which will generate a product details page title that appears as "Name of product | Name of Category | Name of online store". redSHOP will use the template defined here to generate all product page titles, unless the shop administrator has set a different specific template within the "SEO" tab of the product's details in the back end.

<li><b>SEO Page Headings - </b>The template that will be used to generate the page headings on product details pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{productname}", which will generate a product heading that appears as "Name of product". redSHOP will use the template defined here to generate all product page titles, unless the shop administrator has set a different specific template within the "SEO" tab of the product's details in the back end.

<li><b>SEO Page Descriptions - </b>The template that will be used to generate the meta descriptions for product details pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{productname} - {categoryname} - {productname} from {shopname}", which will generate the meta description for the product details page that appears as "Name of product - Name of category - Name of product from Name of online store". redSHOP will use the template defined here to generate the meta descriptions on all product details pages, unless the shop administrator has set a different specific template within the "SEO" tab of the product's details in the back end.

<li><b>SEO Keywords - </b>The template that will be used to generate the meta keywords for product details pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{productname}, {categoryname}, {productname} from {shopname}", which will generate meta keywords for the product details page that appear as"Name of product, Name of category, Name product from Name of online store". redSHOP will use the template defined here to generate the meta keywords on all product details pages, unless the shop administrator has set a different specific template within the "SEO" tab of the product's details in the back end.
</ul>

<hr>

<!-- Manufacturer -->
<h2 id="manufacturer">Manufacturer</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img72.png" class="example"/>

<ul>
<li><b>SEO Page Titles - </b>The template that will be used to generate the page titles on manufacturer pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{manufacturer} | {shopname}", which will generate a manufacturer page title that appears as "Name of manufacturer | Name of online store". redSHOP will use the template defined here to generate all manufacturer page titles, unless the shop administrator has set a different specific template within the "SEO" tab of the manufacturer's details in the back end.

<li><b>SEO Page Headings - </b>The template that will be used to generate the page headings on manufacturer pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{manufacturer}", which will generate a manufacturer page heading that appears as "Name of manufacturer". redSHOP will use the template defined here to generate all manufacturer page headings, unless the shop administrator has set a different specific template within the "SEO" tab of the manufacturer's details in the back end.

<li><b>SEO Page Descriptions - </b>The template that will be used to generate the meta descriptions for manufacturer pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{manufacturer} - {shopname}", which will generate the meta description for a manufacturer page that appears as "Name of manufacturer - Name of online store". redSHOP will use the template defined here to generate the meta descriptions on all manufacturer pages, unless the shop administrator has set a different specific template within the "SEO" tab of the manufacturer's details in the back end.

<li><b>SEO Keywords - </b>The template that will be used to generate the meta keywords for manufacturer pages. Certain template tags can be used in this field in addition to specific text, and redSHOP comes installed with the default template that reads "{manufacturer}, {shopname}", which will generate meta keywords for the manufacturer page that appear as "Name of manufacturer, Name of online store". redSHOP will use the template defined here to generate the meta keywords on all manufacturer pages, unless the shop administrator has set a different specific template within the "SEO" tab of the manufacturer's details in the back end.
</ul>

<hr>

<!-- Available SEO Tags -->
<h2 id="tags">Available SEO Tags</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img73.png" class="example"/>

This section displays all the tags that can be used in the respective SEO-related fields in both this Global Config tab as well as the "SEO" tabs that appear in product, category and manufacturer details pages in the back end. More information on template tags is available in the "SEO" section.

<ul>
    <li><a href="#tags1">Available Tags for Page Titles</a>
    <li><a href="#tags2">Available Tags for Headings</a>
    <li><a href="#tags3">Available Tags for Page Descriptions</a>
    <li><a href="#tags4">Available Tags for Keywords</a>
    </ul>

<hr>

<h4 id="tags1">Available Tags for Page Titles </h4>

<img src="./manual/en-US/chapters/global-configuration/img/img74.png" class="example"/>

<ul>
<li><b>{productname} -- </b>Product Name 
<li><b>{manufacturer} -- </b>Manufacturer Name 
<li><b>{parentcategoryloop} -- </b>Parent Category Hierarchy 
<li><b>{categoryname} -- </b>Category Name 
<li><b>{saleprice} -- </b>Sales Price 
<li><b>{saving} -- </b>Total Saving 
<li><b>{shopname} -- </b>Shop name 
<li><b>{productsku} -- </b>Product SKU or Product Number 
<li><b>{categoryshortdesc} -- </b>Category short description 
<li><b>{productshortdesc} -- </b>Product short description
</ul>

<hr>

<h4 id="tags2">Available Tags for Headings </h4>

<img src="./manual/en-US/chapters/global-configuration/img/img75.png" class="example"/>

<ul>
<li><b>{productname} -- </b>Product Name
<li><b>{manufacturer} -- </b>Manufacturer Name 
<li><b>{categoryname} -- </b>Category Name 
<li><b>{productsku} -- </b>Product SKU or Product Number 
<li><b>{categoryshortdesc} -- </b>Category short description 
<li><b>{productshortdesc} -- </b>Product short description
</ul>

<hr>

<h4 id="tags3">Available Tags for Page Descriptions </h4>

<img src="./manual/en-US/chapters/global-configuration/img/img76.png" class="example"/>

<ul>
<li><b>{productname} -- </b>Product Name 
<li><b>{manufacturer} -- </b>Manufacturer Name 
<li><b>{categoryname} -- </b>Category Name 
<li><b>{saleprice} -- </b>Sales Price 
<li><b>{saving} -- </b>Total Saving 
<li><b>{shopname} -- </b>Shop name 
<li><b>{productsku} -- </b>Product SKU or Product Number 
<li><b>{categoryshortdesc} -- </b>Category short description
<li><b>{productshortdesc} -- </b>Product short description
</ul>

<hr>

<h4 id="tags4">Available Tags for Keywords </h4>

<img src="./manual/en-US/chapters/global-configuration/img/img77.png" class="example"/>

<ul>
<li><b>{productname} -- </b>Product Name 
<li><b>{manufacturer} -- </b>Manufacturer Name 
<li><b>{categoryname} -- </b>Category Name 
<li><b>{saleprice} -- </b>Sales Price 
<li><b>{saving} -- </b>Total Saving 
<li><b>{shopname} -- </b>Shop name 
<li><b>{productsku} -- </b>Product SKU or Product Number 
<li><b>{categoryshortdesc} -- </b>Category short description 
<li><b>{productshortdesc} -- </b>Product short description
</ul>

<hr>

<h6>Last updated on July 19, 2019</h6>