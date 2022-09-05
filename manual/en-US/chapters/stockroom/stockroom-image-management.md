## Stockroom Image Management
Imagine user have some products out of stock without message, images to warning. It is really hard to find out where we should put it.

Images of stockroom should be saved and managed in a system. This page help user to define and manage images easily. 

<hr>

### In this article you will fine

<ul>
<li><a href="#listingScreen">Overview of Stock Image List Screen</a>
    <ul>
    <li><a href="#field">Field</a>
    <li><a href="#action">Action</a> 
    </ul>

<li><a href="#workingStockImage">Working Stock Image with redSHOP</a>
    <ul>
    <li><a href="#createItems">Create Stock Image Item</a>
    <li><a href="#productDetail">Display in Product Detail</a>
    </ul>
</ul>

<hr>

<ul>
<li>Firstly, you have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided.
<img src="./manual/en-US/chapters/stockroom/img/administrator.png" class="example"/><br><br>

<li>Secondly, you click on Component on main menu and select on "redSHOP".
<img src="./manual/en-US/chapters/stockroom/img/img16.png" class="example"/><br><br>

<li>Webpage will display overview page administrator of redSHOP then click on "Stockroom" tab and select on "Stock Image List" item.
<img src="./manual/en-US/chapters/stockroom/img/img17.png" class="example"/><br><br>
</ul>

<!-- Overview Stock Image List Screen -->
<h2 id="listingScreen">Overview Stock Image List Screen</h2>

<img src="./manual/en-US/chapters/stockroom/img/img18.png" class="example"/>

<h4 id="field">Field</h4>

<ul>
<li><b>Search field - </b>you can search a stockroom

<li><b>Filter field - </b>filter all product list as: name, SKU, Category, Product, Attribute value, Sub attribute value.

<li><b>Stockroom Image Tooltip -  </b>the tooltip of Image stockroom.

<li><b>Stock Amount Image - </b>the image of the stockroom that will appear on the front-end.

<li><b>Stock Quantity - </b>the limited condition for stockroom.

<li><b>Stock Amount - </b>operations for stockroom.

<li><b>Stockroom - </b>the name of stockroom in the shop.
</ul>

<hr>

<h4 id="action">Action</h4>

<img src="./manual/en-US/chapters/stockroom/img/img19.png" class="example"/><br><br>

<ul>
<li><b>New -</b> Add a new stock image record, users will move to add the new stock image details to the product catalog.

<li><b>Edit - </b>Edit stock image items.

<li><b>Delete - </b>Delete 1 or more items has selected. 
</ul>

<hr>

<!-- Working Stock Image with redSHOP -->
<h2 id="workingStockImage">Working Stock Image with redSHOP</h2>

<h4 id="createItems">Create Stock Image item</h4>

<ul>
<li>1. Click on "New" button In Stock Image management.
<img src="./manual/en-US/chapters/stockroom/img/img20.png" class="example"/><br><br>

<li>2. Webpage show "Stock Amount Image: [New]" page.
<img src="./manual/en-US/chapters/stockroom/img/img21.png" class="example"/><br><br>

<li>3. User will fill in Name is "Out of Stock" then click on "Save & Close" button.
<img src="./manual/en-US/chapters/stockroom/img/img22.png" class="example"/><br><br>

<li>4. Stock image just created has name "Out of stock" disappears on management list.
<img src="./manual/en-US/chapters/stockroom/img/img23.png" class="example"/>
</ul>

<hr>

<h4 id="productDetail">Display in Product Detail</h4>

<ul>
<li>5. Go to Inventory for all Stockroom, edit quantity some products and click on "Save" icon later.
<img src="./manual/en-US/chapters/stockroom/img/img24.png" class="example"/><br><br>

<li>6. Go to Template from Customization, then select "product" item and add "{product_stock_amount_image}" tag.
<img src="./manual/en-US/chapters/stockroom/img/img25.png" class="example"/><br><br>

<li>7. After clicking on "Save and Close" button, goes to product detail and view product out of stock image.
<img src="./manual/en-US/chapters/stockroom/img/img26.png" class="example"/><br><br>

<li>8. Similiarly for In Stock room image if user wish to display In stock status for product detail.
<img src="./manual/en-US/chapters/stockroom/img/img27.png" class="example"/>
</ul>

Video for Stockroom Image Management  <a href="https://redshop.fleeq.io/l/meoqc3afcu-tkqwsf26ui">Click here</a>

<hr>

<h6>Last updated on October 16, 2019</h6>