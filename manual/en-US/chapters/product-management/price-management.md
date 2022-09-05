## Working with Price Management
When user order products, prices are necessary. Price Management on redSHOP is an easy way for customers can add on product prices , discount prices for one or more products at the same time ... From there, users can easily add and edit price of products without needing to go through many steps as the Product management to add or edit product price.

<hr>

### In this article you will fine:

<ul>
<li><a href="#price-management">Working with Products Price management Listing Screen</a>
    <ul>
    <li><a href="#overview-price">Overview of Price Management Listing Screen</a>
    <li><a href="#overview-product">Overview of Product Price detail screen</a>
    <li><a href="#create-price">Create price</a>
    </ul>

<li><a href="#detail-redshop">Working with Product detail in redSHOP</a>
    <ul>
    <li><a href="#overview-price-2">Overview of Price product detail screen</a>
    <li><a href="#overview-price-3">Overview Price Management item detail</a>
    <li><a href="#create-price-2">Create price item for product detail</a>
    <li><a href="#work-redshop">Work with front-end in redSHOP</a>
    </ul>
</ul>

<hr>

</ul>
<li>Firstly you have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided
<img src="./manual/en-US/chapters/product-management/img/administrator.png" class="example"/><br><br>

<li>Secondly you click on Component on main menu and select on "redSHOP"
<img src="./manual/en-US/chapters/product-management/img/img78.png" class="example"/><br><br>

<li>Finally webpage will display overview page administrator of redSHOP and click on Product tab then select Product Price item
<img src="./manual/en-US/chapters/product-management/img/img79.png" class="example"/><br><br>
</ul>

<hr>

<!-- Working with Products Price management in redSHOP -->
<h2 id="price-management">Working with Products Price management in redSHOP</h2>

<h4 id="overview-price">Overview Products Price management list screen</h4>

<img src="./manual/en-US/chapters/product-management/img/img80.png" class="example"/><br><br>

<h4>Field</h4>

<ul>
<li><b>Product Name - </b>the name of the product 

<li><b>Product Number - </b>the model number, string or code that identifies this product 

<li><b>Price - </b>displays the price currently assigned to the product, and lets you update and save the prices of multiple products at the same time. Each product price also has a plus icon link next to it that opens up a lightbox where you can set and update prices for different shopper groups and the quantity range within which a product must be placed an order for it to apply; click on the diskette save icon in the price column to update any changes made (note: prices will be displayed with the number of decimal places set in the global configuration section) 

<li><b>Discount Price - </b>displays the discount price currently assigned to the product and lets you update and save discount prices stored from multiple products at the same time.

<li><b>Icon Plus - </b>open popup to create discount for Shopper Group have Quantity start, Quantity end, Price 

<li><b>Save button - </b>Save all information when it change
</ul>

<h4>Filters</h4>

<ul>
<li><b>Search box - </b>if you want to display specific records, type in at least two characters that appear in the product name, number or associated category of what you're looking for and click on the search button to update and refine the products listed 

<li><b>Search filter controls - </b>sets the type of information that the search box should be basing the list of results to be displayed on; it can look through product names, numbers, a combination of the two and associated categories
</ul>

<hr>

<h4 id="overview-product">Overview Product Price detail screen</h4>

<img src="./manual/en-US/chapters/product-management/img/img81.png" class="example"/><br><br>

<h4>Field</h4>

<ul>
<li><b>Shopper Group Name - </b>the groups classify of group customer when register order, depend on 

<li><b>Quantity Start - </b>the quantity allow customer buy to apply for shopper group when order with bulk

<li><b>Quantity End - </b>the quantity allow customer buy to apply for shopper group when order with bulk

<li><b>Price - </b>the price will discount when customer buy product bulk in about high quantity start, low quantity end
</ul>

<h4>Action</h4>

<ul>
<li><b>Icon Save - </b>apply the changes made to the price product details or create new item and show information change latest on the page 
</ul>

<hr>

<h4 id="create-price">Create price item</h4>

<ul>
<li>1. After access in redSHOP Administrator go to Product management then choose Product price tab
<img src="./manual/en-US/chapters/product-management/img/img82.png" class="example"/><br><br>

<li>2. User clicks on add icon at product position which want to add price
<img src="./manual/en-US/chapters/product-management/img/img83.png" class="example"/><br><br>

<li>3. User fill in some filed as Quantity Start, quantity End (with Quantity Start smaller than Quantity End), Price at Shopper group which use price. Then click on Save icon
<img src="./manual/en-US/chapters/product-management/img/img84.png" class="example"/><br><br>

<li>4. User close popup by clicking outside popup. Then click on product name which just added price
<img src="./manual/en-US/chapters/product-management/img/img85.png" class="example"/><br><br>

<li>5. Webpage will display product detail, User click on Add price to redirect to Price Management page
<img src="./manual/en-US/chapters/product-management/img/img86.png" class="example"/><br><br>

<li>Webpage will display all price of this product
<img src="./manual/en-US/chapters/product-management/img/img87.png" class="example"/><br><br>
</ul>

Video for Create Price item on Product price: <a href="https://redshop.fleeq.io/l/w3l0zu1c6n-29dmgz8ybx">Click here</a>

<hr>

<!-- Working with Product detail in redSHOP -->
<h2 id="detail-redshop">Working with Product detail in redSHOP</h2>

<h4 id="overview-price-2">Overview Products Price management list screen</h4>

It has integration in product detail when user going on product detail page it have some button as "Save", "Save & Close", "Save & New", "Save & Copy", "Close", "Preview", "Add price". This function is integrated in "Add price" button when user click on "Add price" button

<img src="./manual/en-US/chapters/product-management/img/img88.png" class="example"/><br><br>

<h4>Field</h4>

<img src="./manual/en-US/chapters/product-management/img/img89.png" class="example"/><br><br>

<ul>
<li><b>(1) Name - </b>the name of product 

<li><b>(2) Shopper groups - </b>the shopper group for customer has setup discount of shop owner 

<li><b>(3) Quantity start - </b>the quantity start in order if low quantity start has setup user not get discount. 

<li><b>(4) Quantity end - </b>the quantity start in order if high quantity end has setup user not get discount 

<li><b>(5) Price - </b>the price currently stored for this product, displayed with the currency symbol of the store currency set in the global configuration screen 

<li><b>(6) Discount Price - </b>the discount price of product but it should lower price origin
</ul>

<h4>Action</h4>

<img src="./manual/en-US/chapters/product-management/img/img90.png" class="example"/><br><br>

<ul>
<li><b>New - </b>appears when adding a new manufacturer record, click on this to add the new manufacturers details to the product catalog 

<li><b>Edit - </b>edit item when want change product price items 

<li><b>Delete - </b>delete 1 or more items has selected. Items has deleted will remove list items product price 
</ul>

<hr>

<h4 id="overview-price-3">Overview Price Management item detail</h4>

<img src="./manual/en-US/chapters/product-management/img/img91.png" class="example"/><br><br>

<h4>Field</h4>

<ul>
<li><b>Name - </b>the name product item

<li><b>Shopper Group Name - </b>the shopper group can apply when user order quantity bulk 

<li><b>Product Price - </b>the price of product 

<li><b>Quantity Start - </b>the quantity start can apply price

<li><b>Quantity End - </b>the quantity end apply price 

<li><b>Discount Price - </b>the price rest when user buy product 

<li><b>Start Date - </b>the date start apply price when user order quantity bulk 

<li><b>End Date - </b>the date stop apply price when user order quantity bulk 
</ul>

<h4>Action</h4>

<img src="./manual/en-US/chapters/product-management/img/img92.png" class="example"/><br><br>

<ul>
<li><b>Save - </b>apply the changes made to the product price details or create new item and show information change latest on the page 

<li><b>Save & Close - </b>apply the changes made to the product price details or create new and return to the product price listing screen 

<li><b>Cancel - </b>return to the product price listing screen without saving any changes

<li><b>Close - </b>return to the product price listing screen without saving any changes
</ul>

<hr>

<h4 id="create-price-2">Create price item for product detail</h4>

<ul>
<li>1. After access in redSHOP Administrator go to Product management
<img src="./manual/en-US/chapters/product-management/img/img93.png" class="example"/><br><br>

<li>Then user clicks on New button to create 1 product item
<img src="./manual/en-US/chapters/product-management/img/img94.png" class="example"/><br><br>

<li>2. Fill in some field in product page and click on "Save" button then click on "Add price" button webpage will show price management
<img src="./manual/en-US/chapters/product-management/img/img95.png" class="example"/><br><br>

<li>3. Price management will display and click on "New" button to setup discount when user buy product with quantity large
<img src="./manual/en-US/chapters/product-management/img/img96.png" class="example"/><br><br>

<li>4. Fill in some field in page with shopper group, quantity start, quantity end, price discount, start date, end date ... and click on "Save & Close" button 
<img src="./manual/en-US/chapters/product-management/img/img97.png" class="example"/><br><br>

<li>Webpage will display product item just created
<img src="./manual/en-US/chapters/product-management/img/img98.png" class="example"/><br><br>
</ul>

Video working for price product: <a href="https://redshop.fleeq.io/l/lg5e3dzawf-1mzo2fgked">Click here</a>

<hr>

<h4 id="work-redshop">Work with front-end in redSHOP</h4>

<ul>
<li>1. User go to front-end and login account has create in back-end 
<img src="./manual/en-US/chapters/product-management/img/img99.png" class="example"/><br><br>

<li>2. Go to product has created and setup price discount
<img src="./manual/en-US/chapters/product-management/img/img100.png" class="example"/><br><br>

<li>3. User click add to cart and go to cart 
<img src="./manual/en-US/chapters/product-management/img/img101.png" class="example"/><br><br>

<li>4. Change quantity product is "20" and click on load icon
<img src="./manual/en-US/chapters/product-management/img/img102.png" class="example"/><br><br>

<li>5. Total Price will discount to "1.600,00" and check product price is 80,00
<img src="./manual/en-US/chapters/product-management/img/img103.png" class="example"/><br><br>

<li>6. Click on "Checkout" button to checkout cart page. View total price again and product price
<img src="./manual/en-US/chapters/product-management/img/img104.png" class="example"/><br><br>

<li>7. Select on Accept Terms & conditions and click on "Checkout: final step" button
<img src="./manual/en-US/chapters/product-management/img/img105.png" class="example"/><br><br> 

<li>8. Webpage show Order Receipt page it show Total is "1.600,00"
<img src="./manual/en-US/chapters/product-management/img/img106.png" class="example"/><br><br> 
</ul>

Video working for redSHOP on frontend: <a href="https://redshop.fleeq.io/l/pmk5f82aea-fyhks6q08h">Click here</a>

<hr>

<h6>Last updated on November 15, 2019</h6>