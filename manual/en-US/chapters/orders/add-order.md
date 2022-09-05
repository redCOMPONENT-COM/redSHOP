## Add Order
redSHOP allows you to easily create manual orders for your customers. To create a new order for your customer go to the Order link, and click on "Orders"

<img src="./manual/en-US/chapters/orders/img/img33.png" class="example"/>

<hr>

### In this article you will fine

<ul>
<li><a href="#overview-1">Overview of Order Management screen</a>
    <ul>
    <li><a href="#field-1">Field</a>
    <li><a href="#action-1">Action</a>
    </ul>

<li><a href="#overview-2">Overview of Order Details page</a>
    <ul>
    <li><a href="#field-2">Field</a>
    <li><a href="#action-2">Action</a>
    </ul>

<li><a href="#order-management">Working with Order Management within redSHOP</a>
    <ul>
    <li><a href="#create-order-1">Create order for existing customer</a>
    <li><a href="#create-order-2">Create order for new customer</a>
    <li><a href="#create-order-3">Create order on frontend</a>
    <li><a href="#show-order-1">Show order list on frontend</a>
    <li><a href="#show-order-2">Show order tracker on frontend</a>
    <li><a href="#setting">Setting one step checkout</a>
    </ul>
</ul>

<hr>

Firstly you have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided
<img src="./manual/en-US/chapters/orders/img/administrator.png" class="example"/><br><br>

Secondly you click on Component on main menu and select on "redSHOP"
<img src="./manual/en-US/chapters/orders/img/img34.png" class="example"/><br><br>

Finally webpage will display overview page administrator of redSHOP. Click on Order tab then click on Orders item
<img src="./manual/en-US/chapters/orders/img/img35.png" class="example"/><br><br>

From the Order: Order Management screen you can select an existing customer or create a new one, set billing and shipping address, add product(s) to the order, and specify a payment method

<hr>

<!-- Overview of Order Management -->
<h2 id="overview-1">Overview of Order Management</h2>

<ul>
<li><a href="#field-1">Field</a>
<li><a href="#action-1">Action</a>
</ul>

<hr>

<h4 id="field-1">Field</h4>

<img src="./manual/en-US/chapters/orders/img/img36.png" class="example"/><br><br>

<ul>
<li><b>(1) ID - </b>ID of customer

<li><b>(2) Customer - </b>Full name of customer ordered

<li><b>(3) Email - </b> Email of cusstomer

<li><b>(4) Customer type - </b>shopper group

<li><b>(5) Customer note -  </b>note of customer when order 

<li><b>(6) Status - </b>Status of the order

<li><b>(7) Payment - </b>Status of payment

<li><b>(8) Total - </b>toatal price of the order

<li><b>(9) Update Order Status -</b> to change status of the order

<li><b>(10) Date - </b>when user orders, the day will save on database

<li><b>(11) pdf document icon - </b>print order in pdf
</ul>

<hr>

<h4 id="action-1">Action</h4>

<img src="./manual/en-US/chapters/orders/img/img37.png" class="example"/><br><br>

<ul>
<li><b>New - </b>To manual create an order on backend

<li><b>Print Multiple Orders - </b>Multiple invoice is generating and can be download

<li><b>Save Status change - </b>Webpage will display popup for change status the order selected

<li><b>Export Data - </b>Export data in excel format and download to users' computer

<li><b>Delete - </b>delete order selected
</ul>

<hr>

<!-- Overview of Order Details page -->
<h2 id="overview-2">Overview of Order Details page</h2>

<ul>
<li><a href="#field-2">Field</a>
<li><a href="#action-2">Action</a>
</ul>

<hr>

<h4 id="field-2">Field</h4>

<img src="./manual/en-US/chapters/orders/img/img38.png" class="example"/>

<img src="./manual/en-US/chapters/orders/img/img39.png" class="example"/><br><br>

<ul>
<li><b>(1) Exist customer - </b>Start typing a user name to select an Existing User. For create new user leave this field empty

<li><b>(2) Create account - </b>create for new customer one account

<li><b>(3) Registered -  </b>choose group for customer 

<li><b>(4) First name -  </b>First name of the new customer

<li><b>(5) Last Name - </b>Last name of the new customer

<li><b>(6) Addrress - </b>Address of the new cusstomer. Can get for shipping address

<li><b>(7) Postal code - </b>to determine the geographical location of a specific address in a range

<li><b>(8) City -  </b>City is where customer live

<li><b>(9) Country - </b>Country is where customer live

<li><b>(10) State -</b> State is where customer live

<li><b>(11) Phone - </b>Phone number of the new customer 

<li><b>(12) Shipping same as Billing - </b>when this checked, shipping address same as address which entered in Address (6)

<li><b>(13) Select Shipping Address - </b>To create other address for shipping

<li><b>(14) Email - </b>Email address of the new customer

<li><b>(15) User name - </b>will be use to login on Joomla

<li><b>(16) New password - </b>create password to protect accout of customer

<li><b>(17) Confirm password - </b>fill in to this field same as New password, create account, else webpage will diaplay notification
</ul>

<img src="./manual/en-US/chapters/orders/img/img40.png" class="example"/><br><br>

<ul>
<li><b>(1) Name - </b>Name of the product. Click on name to choose product

<li><b>(2) Note - </b>the accompanying services of the product 

<li><b>(3) Price without VAT/Tax - </b>Original price of the product

<li><b>(4) VAT/Tax - </b>Value-added tax

<li><b>(5) Price - </b>price added VAT/Tax, however it is not the total price

<li><b>(6) Quantity - </b>is the amount of that product will buy

<li><b>(7) Total - </b>the total  price

<li><b>(8) Subtotal - </b>the total price 

<li><b>(9) VAT/Tax - </b>Value-added tax

<li><b>(10) Discount - </b>Optional. If applicable, add the amount to discount to the price without VAT

<li><b>(11) Special Discounts - </b>Discounts and special opportunities not valid on previous purchases.

<li><b>(12) Shipping - </b>the shipping price

<li><b>(13) Total -</b> the finally price
</ul>

<hr>

<h4 id="action-2">Action</h4>

<img src="./manual/en-US/chapters/orders/img/img41.png" class="example"/><br><br>

<ul>
<li><b>Apply user - </b>save information of the customer

<li><b>Cancel - </b>return to the order managerment screen without saving any changes
</ul>

<img src="./manual/en-US/chapters/orders/img/img42.png" class="example"/><br><br>

<ul>
<li><b>Save + Pay - </b>Save the order and manually enter payment info.  This is great for taking orders on the phone, for instance.

<li><b>Save without email - </b>this will save the order and will not send an order invoice to the customer.

<li><b>Save & Close - </b>this will save the order and send an invoice email to the customer, which will include the full order details and a link to your shop to log in and make payment.
</ul>

<hr>

<!-- Working with Order Management within redSHOP -->
<h2 id="order-management">Working with Order Management within redSHOPt</h2>

<ul>
<li><a href="#create-order-1">Create order for existing customer</a>
<li><a href="#create-order-2">Create order for new customer</a>
<li><a href="#create-order-3">Create order on frontend</a>
<li><a href="#show-order-1">Show order list on frontend</a>
<li><a href="#show-order-2">Show order tracker on frontend</a>
<li><a href="#setting">Setting one step checkout</a>
</ul>

<hr>

<h4 id="create-order-1">Create order for existing customer</h4>

<ul>
<li>Go to backend page of redSHOP. Then choose User and click on Add User tab
<img src="./manual/en-US/chapters/orders/img/img43.png" class="example"/><br><br>

<li>User fill data in some field as: user name, password, email.... then, click on Billing Address
<img src="./manual/en-US/chapters/orders/img/img44.png" class="example"/><br><br>

<li>User continue fill data some field as: First name, last name, Address.... Click on Save button when fill
<img src="./manual/en-US/chapters/orders/img/img45.png" class="example"/><br><br>

<li>After that, choose Order and click on Orders tab.
<img src="./manual/en-US/chapters/orders/img/img46.png" class="example"/><br><br>

<li>Click on New button 
<img src="./manual/en-US/chapters/orders/img/img47.png" class="example"/><br><br>

<li>Webpage will display: Existing User, Create Account, First name, Last name, .... 
<img src="./manual/en-US/chapters/orders/img/img48.png" class="example"/><br><br>

<li>User clicks on Existing User combobox, search field will appear. 
<img src="./manual/en-US/chapters/orders/img/img49.png" class="example"/><br><br>

<li>User fills in search field by username or customer-name and select customer want to create Order.
<img src="./manual/en-US/chapters/orders/img/img50.png" class="example"/><br><br> 

<li>Webpage will display all customer information: First name, Last name, Address ... 
<img src="./manual/en-US/chapters/orders/img/img51.png" class="example"/><br><br>

<li>Update cutomer information (or not) , then clicks on Apply user
<img src="./manual/en-US/chapters/orders/img/img52.png" class="example"/><br><br>

<li>Webpage will display: Save + Pay, Save without mail, Save & Close, Order Details, Order Information
<img src="./manual/en-US/chapters/orders/img/img53.png" class="example"/><br><br>

<li>User scroll down page and see: Order Details and Order Information
<img src="./manual/en-US/chapters/orders/img/img54.png" class="example"/><br><br>

<li>User fill in the Order details and Order information

<li><b>Order details: </b>Clicks on Product name combobox, fill in search product name. Choose product.
<img src="./manual/en-US/chapters/orders/img/img55.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img56.png" class="example"/><br><br>

<li>Webpage will display all information of the selected product on the corresponding fields. 
<img src="./manual/en-US/chapters/orders/img/img57.png" class="example"/><br><br> 

<li><b>Add Product - </b>Webpage will display an additional row to add a product to the order details
<img src="./manual/en-US/chapters/orders/img/img58.png" class="example"/><br><br>

<li><b>Remove Product - </b>Webpage will delete the newly added item.
<img src="./manual/en-US/chapters/orders/img/img59.png" class="example"/><br><br>

<li><b>Order Information: </b>Select Payment Method, Shipping Method, fill in some field.
<img src="./manual/en-US/chapters/orders/img/img60.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img61.png" class="example"/><br><br>

<li>User clicks on Save + Pay
<img src="./manual/en-US/chapters/orders/img/img62.png" class="example"/><br><br>

<li>Webpage will send mail to customer and display all information of the order.
<img src="./manual/en-US/chapters/orders/img/img63.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img64.png" class="example"/><br><br>
</ul>

Video work for create orderfor exists customer on backend: <a href="https://redshop.fleeq.io/l/c7nuespack-l45h19tjlc">Click here</a>

<hr>

<h4 id="create-order-2">Create order for new customer</h4>

If you want to manually create an order for a custom who does not yet exist in your shop database, you can create the customer first, and then create the order for them as outlined above.

<ul>
<li>Go to backend page of redSHOP. Then choose Order and click on Orders tab
<img src="./manual/en-US/chapters/orders/img/img65.png" class="example"/><br><br>

<li>Click on New button 
<img src="./manual/en-US/chapters/orders/img/img66.png" class="example"/><br><br>

<li>Webpage will display: Existing User, Create Account, First name, Last name, .... 
<img src="./manual/en-US/chapters/orders/img/img67.png" class="example"/><br><br>

<li>To create the customer simply leave the default Yes option selected in the Create Account field.
<img src="./manual/en-US/chapters/orders/img/img68.png" class="example"/><br><br>

<li>Fill in the user's Billing Address, User name, and password fields, and click Apply user,  A new user will be created and login details sent to the user, and you can then create the order for the newly added user.
<img src="./manual/en-US/chapters/orders/img/img69.png" class="example"/><br><br>

<li>Click on Apply user. Then, User scroll down page and see: Order Details and Order Information
<img src="./manual/en-US/chapters/orders/img/img70.png" class="example"/><br><br>

<li>User fill in the Order details and Order information

<li>Order details: Clicks on Product name combobox, fill in search product name. Choose product.
<img src="./manual/en-US/chapters/orders/img/img71.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img72.png" class="example"/><br><br>

<li>Webpage will display all information of the selected product on the corresponding fields.  
<img src="./manual/en-US/chapters/orders/img/img73.png" class="example"/><br><br>

<li>Order Information: Select Payment Method, Shipping Method, fill in some field.
<img src="./manual/en-US/chapters/orders/img/img74.png" class="example"/><br><br>

<li>After that, click on Save & pay to save change of the order
<img src="./manual/en-US/chapters/orders/img/img75.png" class="example"/><br><br>
</ul>

Video work for create order for new user on backend: <a href="https://redshop.fleeq.io/l/5lozeyq72z-9xrec50z3p">Click here</a>

<hr>

<h4 id="create-order-3">Create order on Frontend</h4>

<ul>
<li>Go on Joomla administrator
<img src="./manual/en-US/chapters/orders/img/img76.png" class="example"/><br><br>

<li>then click on Menus -> Main Menu
<img src="./manual/en-US/chapters/orders/img/img77.png" class="example"/><br><br>

<li>Click New button to create items menu
<img src="./manual/en-US/chapters/orders/img/img78.png" class="example"/><br><br>

<li>User fill in Menu title: Products then click on Select in field menu items type
<img src="./manual/en-US/chapters/orders/img/img79.png" class="example"/><br><br>

<li>Webpage will show popup have title Menu Items Type then user select redSHOP items in popup
<img src="./manual/en-US/chapters/orders/img/img80.png" class="example"/><br><br>

<li>Page will show some items in dropdown list. Choose Category Details item
<img src="./manual/en-US/chapters/orders/img/img81.png" class="example"/><br><br>

<li>User clicks on Select Category
<img src="./manual/en-US/chapters/orders/img/img82.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img83.png" class="example"/><br><br>

<li>Select Menu display on frontend. Then, User clicks on Save button
<img src="./manual/en-US/chapters/orders/img/img84.png" class="example"/><br><br>

<li>Create Cart menu-item with same steps
<img src="./manual/en-US/chapters/orders/img/img85.png" class="example"/><br><br>

<li>Go on frontend, login on fronend
<img src="./manual/en-US/chapters/orders/img/img86.png" class="example"/><br><br>

<li>Click on Products on menu
<img src="./manual/en-US/chapters/orders/img/img87.png" class="example"/><br><br>

<li>Webpage will display products, click on Add to cart one or more product
<img src="./manual/en-US/chapters/orders/img/img88.png" class="example"/><br><br>

<li>Click on Cart on menu
<img src="./manual/en-US/chapters/orders/img/img89.png" class="example"/><br><br>

<li>Webpage will display all cart information. Click on Checkout
<img src="./manual/en-US/chapters/orders/img/img90.png" class="example"/><br><br>

<li>Edit Billing Address Information and choose Shipping Address Information. If you want a new shipping address, click the Add address button
<img src="./manual/en-US/chapters/orders/img/img91.png" class="example"/><br><br>

<li>Webpage will display popup Shipping Address. Enter all field and save address.
<img src="./manual/en-US/chapters/orders/img/img92.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img93.png" class="example"/><br><br>

<li>Choose Payment Method, click Accept checkbox. Click on Checkout
<img src="./manual/en-US/chapters/orders/img/img94.png" class="example"/><br><br>

<li>Webpage will display Accept checkbox and Checkout: Final step. Choose Accept checkbox and click on Checkout: Final step
<img src="./manual/en-US/chapters/orders/img/img95.png" class="example"/><br><br>
</ul>

<b>One step checkout :</b> <a href="#setting">Setting One step checkout</a>

Video work for create order on frontend: <a href="https://redshop.fleeq.io/l/27xxd96l5e-45x697vi4g">Click here</a>

<hr>

<h4 id="show-order-1">Show Order list on frontend</h4>

<ul>
<li>Go on Joomla administrator then click on Menus page Menus will display and on Menu Items 
<img src="./manual/en-US/chapters/orders/img/img96.png" class="example"/><br><br>

<li>then click New button to create 1 items menu
<img src="./manual/en-US/chapters/orders/img/img97.png" class="example"/><br><br>

<li>User fill in Menu title: Orders then click on Select in field menu items type
<img src="./manual/en-US/chapters/orders/img/img98.png" class="example"/><br><br>

<li>Webpage will show popup have title Menu Items Type then user select redSHOP items in popup.
<img src="./manual/en-US/chapters/orders/img/img99.png" class="example"/><br><br>

<li>Page will show some items in dropdown list user will scroll down and select Orders items. 
<img src="./manual/en-US/chapters/orders/img/img100.png" class="example"/><br><br>

<li>After click "Orders" items popup will close then user clicks on Select OrderList Template and select order_list. 
<img src="./manual/en-US/chapters/orders/img/img101.png" class="example"/><br><br>

<li>Select Menu display on frontend
<img src="./manual/en-US/chapters/orders/img/img102.png" class="example"/><br><br>

<li>Save menu item, go on frontend and login (To view the order list, user need to login)
<img src="./manual/en-US/chapters/orders/img/img103.png" class="example"/><br><br>

<li>Click on Orders on menu
<img src="./manual/en-US/chapters/orders/img/img104.png" class="example"/><br><br>

<li>Webpage will will display all order
<img src="./manual/en-US/chapters/orders/img/img105.png" class="example"/><br><br>

<li><b>Details - </b>will sdisplay all order information
<img src="./manual/en-US/chapters/orders/img/img106.png" class="example"/><br><br>

<li><b>Reorder - </b>if user clicks on it, webpage will show popup with nitification: "Are you sure want to reorder? It will empty Current cart."
<img src="./manual/en-US/chapters/orders/img/img107.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img108.png" class="example"/><br><br>

<li>When user accept popup (Click on " OK")
<img src="./manual/en-US/chapters/orders/img/img109.png" class="example"/><br><br>

<li>Webpage will navigate to Cart page
<img src="./manual/en-US/chapters/orders/img/img110.png" class="example"/><br><br>
</ul>

User updates cart and order again <a href="#create-order-3">Create order on frontend</a>

Video work for show order list on frontend: <a href="https://redshop.fleeq.io/l/hdcx45evfd-6rln220f2s">Click here</a>

<hr>

<h4 id="show-order-2">Show Order tracker on frontend</h4>

<ul>
<li>Go on Joomla administrator then click on Menus page Menus will display and on Menu Items then click New button to create 1 items menu

<li>User fill in Menu title: Order Tracker then click on Select in field menu items type.

<li>Webpage will show popup have title Menu Items Type then user select redSHOP items in popup.

<li>Page will show some items in dropdown list user will scroll down and select Order Tracker items. 
<img src="./manual/en-US/chapters/orders/img/img111.png" class="example"/><br><br>

<li>After click Order Tracker items popup will close then user select Menu display on frontend
<img src="./manual/en-US/chapters/orders/img/img112.png" class="example"/><br><br>

<li>User go on frontend, login and click on Order Tracker on menu
<img src="./manual/en-US/chapters/orders/img/img113.png" class="example"/><br><br>

<li>Webpage will show Orser ID search tool and Go button
<img src="./manual/en-US/chapters/orders/img/img114.png" class="example"/><br><br>

<li>User fill in ID of the order want to track. Click on Go
<img src="./manual/en-US/chapters/orders/img/img115.png" class="example"/><br><br>

<li>Webpage will show order information: Order ID, Order Number, Order Item, ...
<img src="./manual/en-US/chapters/orders/img/img116.png" class="example"/><br><br>

<li>When user clicks on Details
<img src="./manual/en-US/chapters/orders/img/img117.png" class="example"/><br><br>

<li>Webpage will display Order Information, Billing Address Information, order item Information, ... (To view the order list, user need to login)
<img src="./manual/en-US/chapters/orders/img/img118.png" class="example"/><br><br>
</ul>

Video work for show order on frontend: <a href="https://redshop.fleeq.io/l/hdcx45evfd-6rln220f2s">Click here</a>

<hr>

<h4 id="setting">Setting One step checkout</h4>

<ul>
<li>Go to Component -> redSHOP. Click on Configuration -> redSHOP Configuration. 
<img src="./manual/en-US/chapters/orders/img/img119.png" class="example"/><br><br>

<li>Click on Cart/Checkout. At One step Checkout, click on Yes button. Click on Save
<img src="./manual/en-US/chapters/orders/img/img120.png" class="example"/><br><br>

<li>Go on frontend, login on fronend
<img src="./manual/en-US/chapters/orders/img/img121.png" class="example"/><br><br>

<li>Click on Products on menu
<img src="./manual/en-US/chapters/orders/img/img122.png" class="example"/><br><br>

<li>Webpage will display products, click on Add to cart one or more product
<img src="./manual/en-US/chapters/orders/img/img123.png" class="example"/><br><br>

<li>Click on Cart on menu
<img src="./manual/en-US/chapters/orders/img/img124.png" class="example"/><br><br>

<li>Webpage will display all cart information. Click on Checkout
<img src="./manual/en-US/chapters/orders/img/img125.png" class="example"/><br><br>

<li>Edit Billing Address Information and choose Shipping Address Information. If you want a new shipping address, click the Add address button
<img src="./manual/en-US/chapters/orders/img/img126.png" class="example"/><br><br>

<li>Webpage will display popup Shipping Address. Enter all field and save address.
<img src="./manual/en-US/chapters/orders/img/img127.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/orders/img/img128.png" class="example"/><br><br>

<li>Choose Payment Method, click Accept checkbox. Click on Checkout: Final step
<img src="./manual/en-US/chapters/orders/img/img129.png" class="example"/><br><br>
</ul>

Video work for create order on frontend with one step checkout: <a href="https://redshop.fleeq.io/l/edh1vdowdo-bns0w6tbhu">Click here</a>

<hr>

<h6>Last updated on September 14, 2020</h6>