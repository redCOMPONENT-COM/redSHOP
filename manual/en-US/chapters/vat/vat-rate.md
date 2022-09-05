## VAT/Tax Rate
Germany and France were the first countries to implement VAT, doing so in the form of a general consumption tax during World War I. A  value-added tax (VAT), known in some countries as a goods and services tax (GST), is a type of tax that is assessed incrementally. Like an income tax, it is based on the increase in value of a product or service at each stage of production or distribution. However, a VAT is collected by the end retailer and is usually a flat tax, and is therefore frequently compared to a sales tax.

VAT essentially compensates for the shared services and infrastructure provided in a certain locality by a state and funded by its taxpayers that were used in the elaboration of that product or service. Not all localities require VAT to be charged and goods and services for export may be exempted (<a href="https://en.wikipedia.org/wiki/Duty_free">duty free</a>). VAT is usually implemented as a destination-based tax, where the tax rate is based on the location of the consumer and applied to the sales price. Confusingly, the terms VAT, GST, <a href="https://en.wikipedia.org/wiki/Consumption_tax">consumption tax</a> and <a href="https://en.wikipedia.org/wiki/Sales_tax">Sales tax</a> are sometimes used interchangeably. VAT raises about a fifth of total tax revenues both worldwide and among the members of the <a href="https://en.wikipedia.org/wiki/Organisation_for_Economic_Co-operation_and_Development">Organisation for Economic Co-operation and Development</a> (OECD).[1]:14 As of 2018, 166 of the 193 countries with full UN membership employ a VAT, including all OECD members except the United States,[1]:14 which uses a <a href="https://en.wikipedia.org/wiki/Sales_tax">sales tax</a> system instead.

<hr>

### In this article you will fine

<ul>
<li><a href="#overview-1">Overview of VAT Rate Listing Screen</a>
    <ul>
    <li><a href="#field-1">Field</a>
    <li><a href="#action-1">Action</a>
    </ul>
<li><a href="#overview-2">Overview of VAT Rate Items Screen</a>
    <ul>
    <li><a href="#field-2">Field</a>
    <li><a href="#action-2">Action</a>
    </ul>
    
<li><a href="#working">Working with redSHOP</a>
    <ul>
    <li><a href="#create-1">Create VAT Group items</a>
    <li><a href="#create-2">Create VAT Rate items</a>
    <li><a href="#config">Config in redSHOP</a>
    </ul>

<li><a href="#checkout-1">Checkout product have VAT in backend</a>
<li><a href="#checkout-2">Checkout product have VAT in frontend</a>
</ul>

<hr>

Firstly you have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided

<img src="./manual/en-US/chapters/vat/img/administrator.png" class="example"/><br><br>

Secondly you click on Component on main menu and select on "redSHOP"

<img src="./manual/en-US/chapters/vat/img/img1.png" class="example"/><br><br>

Webpage will display overview page administrator of redSHOP then click on "Product" tab and select on "VAT/Tax rates"

<img src="./manual/en-US/chapters/vat/img/img11.png" class="example"/><br><br>

<hr>

<!-- Overview of VAT Rate Listing Screen -->
<h2 id="overview-1">Overview of VAT Rate Listing Screen</h2>

<h4 id="field-1">Field</h4>

<img src="./manual/en-US/chapters/vat/img/img12.png" class="example"/><br><br>

<ul>
<li><b>Name - </b>the name will apply VAT rate in shop 

<li><b>Group - </b>is the group items in VAT Group 

<li><b>Country - </b>the country items will apply VAT at place,living of user buy product in shop

<li><b>State - </b>the state items will apply VAT at place,living of user buy product in shop

<li><b>Amount - </b>the amount that the vat is worth; enter the full price value of the vat if it is a total sum, and if it is a percentage then enter a decimal value for redSHOP to use in calculations, such as a value of 0.12 to represent 12%

<li><b>ID - </b>the id all items mass discount 
</ul>

<hr>

<h4 id="action-1">Action</h4>

<img src="./manual/en-US/chapters/vat/img/img13.png" class="example"/><br><br>

<ul>
<li><b>New - </b>appears when adding a new VAT Rate record, click on this to add the new VAT Rate details to the VAT Rate catalog 

<li><b>Delete - </b>delete 1 or more items has selected. Items has deleted will remove list items VAT Rate 

<li><b>Publish - </b>it change status from unpublish to publish for items VAT rate is continue using 

<li><b>Unpublish - </b>it change status from publish to publish for items VAT rate is stop using 

<li><b>Check-in - </b>will unlock any items manufacturer when someone viewing it 
</ul>

<hr>

<!-- Overview of VAT Rate Items Screen -->
<h2 id="overview-2">Overview of VAT Rate Items Screen</h2>

<h4 id="field-2">Field</h4>

<img src="./manual/en-US/chapters/vat/img/img14.png" class="example"/><br><br>

<ul>
<li><b>Name - </b>the name of the VAT rate 

<li><b>Group - </b>the name of group VAT

<li><b>Country - </b>the name of the country apply VAT 

<li><b>State - </b>the name of the state will apply VAT 

<li><b>Amount -  </b>the amount that the VAT is worth; enter the full price value of the VAT if it is a total sum, and if it is a percentage then enter a decimal value for redSHOP to use in calculations, such as a value of 0.12 to represent 12%

<li><b>EU Country - </b>if country, state is in EU groups we choose "yes"
</ul>

<hr>

<h4 id="action-2">Action</h4>

<img src="./manual/en-US/chapters/vat/img/img15.png" class="example"/><br><br>

<ul>
<li><b>Save - </b>saves any changes made and refreshes the page and show data same when user fill in 

<li><b>Save & Close - </b>saves any changes made, redirects to the listing screen and show items have just create 

<li><b>Save & New - </b>saves any changes made, redirects to the page create new

<li><b>Cancel - </b>returns to the listing screen without saving any changes when on in new create item view 
</ul>

<hr>

<hr>

<!-- Working with redSHOP  -->
<h2 id="working">Working with redSHOP </h2>

<h4 id="create-1">Create VAT Group items</h4>

<ul>
<li>Go to backend of redSHOP click on Product tab and select on VAT/Tax Group 
<img src="./manual/en-US/chapters/vat/img/img16.png" class="example"/><br><br>

<li>Webpage show VAT/Tax Group management and click "New" button 
<img src="./manual/en-US/chapters/vat/img/img17.png" class="example"/><br><br>

<li>Page create VAT/Tax display and fill in VAT/Tax Group name is "Asian" then click on "Save & Close" button
<img src="./manual/en-US/chapters/vat/img/img18.png" class="example"/><br><br>

<li>Then click on "Save & Close" button 
<img src="./manual/en-US/chapters/vat/img/img19.png" class="example"/><br><br>
</ul>

Video for VAT/Tax Group in redSHOP: <a href="https://redshop.fleeq.io/l/kr9w4yj1zl-p23w9rwlvm">Click here</a>

<hr>

<h4 id="create-2">Create VAT Rate items</h4>

<ul>
<li>Select on VAT/Tax rate when create VAT/Tax items 
<img src="./manual/en-US/chapters/vat/img/img20.png" class="example"/><br><br>

<li>Webpage show VAT/Tax Group management and click "New" button 
<img src="./manual/en-US/chapters/vat/img/img21.png" class="example"/><br><br>

<li>Fill in VAT/Tax Name is "Viet Nam", Group, Country, State, Amount, EU Country with Group is "Asian" in VAT Rate page 
<img src="./manual/en-US/chapters/vat/img/img22.png" class="example"/><br><br>

<li>Click on "Save" button 
<img src="./manual/en-US/chapters/vat/img/img23.png" class="example"/><br><br>

<li>Web display items have name "Viet Nam" in VAT Rate page 
<img src="./manual/en-US/chapters/vat/img/img24.png" class="example"/><br><br>
</ul>

Video for create VAT Rate: <a href="https://redshop.fleeq.io/l/zld1sql3uo-ovq7joeivi">Click here</a>

<hr>

<h4 id="config">Config in redSHOP </h4>

<ul>
<li>Click on Configuration tab and select on "redSHOP Configuration" item
<img src="./manual/en-US/chapters/vat/img/img25.png" class="example"/><br><br>

<li>Webpage display Configuration page and user click on "Price" tab in menu left 
<img src="./manual/en-US/chapters/vat/img/img26.png" class="example"/><br><br>

<li>User find "VAT" and select Default Country is "Viet Nam", Default VAT Group "Asian" and click on "Save" button 
<img src="./manual/en-US/chapters/vat/img/img27.png" class="example"/><br><br>

<li>Hover on icon product and click on "Product Management" item 
<img src="./manual/en-US/chapters/vat/img/img28.png" class="example"/><br><br>

<li>Click on "New"  button to create new product, fill in all field and select VAT Group is "Asian. 
<img src="./manual/en-US/chapters/vat/img/img29.png" class="example"/><br><br>

<li>Click on "Save" button 
<img src="./manual/en-US/chapters/vat/img/img30.png" class="example"/><br><br>

<li>Hover on "User" icon and choose "Users"item
<img src="./manual/en-US/chapters/vat/img/img31.png" class="example"/><br><br>

<li>Create User with Country is "Viet Nam" it have condition apply VAT just create 
<img src="./manual/en-US/chapters/vat/img/img32.png" class="example"/><br><br>
</ul>

<hr>

<!-- Checkout product have VAT in back-end  -->
<h2 id="checkout-1">Checkout product have VAT in back-end</h2>

<ul>
<li>In User management page click on "Order" icon and select on "Orders" item 
<img src="./manual/en-US/chapters/vat/img/img33.png" class="example"/><br><br>

<li>Click on "New" button to create 1 order with product just created 
<img src="./manual/en-US/chapters/vat/img/img34.png" class="example"/><br><br>

<li>Fill in user name on field has text "Start typing a user name to select an Existing User. For create new user leave this field empty:"
<img src="./manual/en-US/chapters/vat/img/img35.png" class="example"/><br><br>

<li>Then click "Apply" button 
<img src="./manual/en-US/chapters/vat/img/img36.png" class="example"/><br><br>

<li>Scroll down page and fill in product have just create. See product price is 100,00, VAT/tax is 10,00, Total is 110,00
<img src="./manual/en-US/chapters/vat/img/img37.png" class="example"/><br><br>

<li>Click "Save + Pay" button webpage will show order detail it show product price is 100,00, VAT/tax is 10,00, Total is 110,00
<img src="./manual/en-US/chapters/vat/img/img38.png" class="example"/><br><br>
</ul>

Video for checkout in back-end: <a href="https://redshop.fleeq.io/l/k2znq9210y-xeagipbjil">Click here</a>

<hr>

<!-- Checkout product have VAT in front-end  -->
<h2 id="checkout-2">Checkout product have VAT in front-end</h2>

<ul>
<li>Go to front-end and login with user name/password has created before 
<img src="./manual/en-US/chapters/vat/img/img39.png" class="example"/><br><br>

<li>Go to product detail has created and add to cart it.
<img src="./manual/en-US/chapters/vat/img/img40.png" class="example"/><br><br>

<li>Go to Cart page to view price excl VAT: 100,00, Tax: 10,00, Total: 110,00 then click on "Checkout" button
<img src="./manual/en-US/chapters/vat/img/img41.png" class="example"/><br><br>

<li>Webpage will show order checkout page it have Total is 110,00 
<img src="./manual/en-US/chapters/vat/img/img42.png" class="example"/><br><br>

<li>Select on checkbox "Accept Term & condition" and click "Checkout: final step" button 

<li>Webpage show Order receipt page and check again price VAT, Total 
<img src="./manual/en-US/chapters/vat/img/img43.png" class="example"/><br><br>

</ul>

Video for checkout in front-end: <a href="https://redshop.fleeq.io/l/sqkkkh8wn5-5d7zcdlnm8">Click here</a>

<hr>

<h6>Last updated on August 13, 2019</h6>