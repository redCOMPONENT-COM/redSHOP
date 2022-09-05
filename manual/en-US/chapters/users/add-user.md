## Add User
The function allows administrator to add a new user account that will display in User management tab.

<hr>

### In this article you will fine

<ul>
<li><a href="#overview">Overview of User Management Detail</a>
    <ul>
    <li><a href="#general">General User Information tab</a>
    <li><a href="#billing">Billing Information tab</a>
    <li><a href="#actions">Actions</a>
    </ul>

<li><a href="#working">Working with Add User within redSHOP</a>
    <ul>
    <li><a href="#private-1">Private Customer</a>
    <li><a href="#company">Company Customer</a>
    </ul>

<li><a href="#create">Create User on Frontend</a>
    <ul>
    <li><a href="#private-2">Private Customer</a>
    <li><a href="#business">Business</a>
    </ul>
    
<li><a href="#account">Account Management on Frontend</a>
<li><a href="#edit">Edit Account Information on frontend</a>
<li><a href="#add">Add/Edit address on Frontend</a>

</ul>

<hr>

You have one web-site use Joomla and installed redSHOP component. Access administrator web-site page by (username/password) has been provided.

<img src="./manual/en-US/chapters/users/img/administrator.png" class="example"/><br><br>

User clicks on Components on main menu and then select on "redSHOP" tabs.

<img src="./manual/en-US/chapters/users/img/img1.png" class="example"/><br><br>

On Administrator page click on User tab and select "Add User" items in menu left.

<img src="./manual/en-US/chapters/users/img/img5.png" class="example"/><br><br>

<hr>

<!-- Overview of User Management Detail -->
<h2 id="overview">Overview of User Management Detail</h2>

<h4 id="general">General User Information tab:</h4>

<h5>Fields</h5>

<img src="./manual/en-US/chapters/users/img/img6.png" class="example"/><br><br>

<ul>
<li><b>User name - </b>the username of the customer's account. When creating a new account, the data entering will be checked for validation to make sure if it is ready for use.

<li><b>New Password - </b>indicates the current password. Password can be either reset by entering the new one. 

<li><b>Confirm New Password - </b>password needs to be entered again for confirmation.

<li><b>Email - </b>the e-mail address of user and is assigned to their account. A e-mail address will be also checked for validation to make sure if it is ready for use.

<li><b>Shopper Groups - </b>selects the shopper group that this customer belongs to.

<li><b>Group - </b>(multiple select) indicates the Joomla! access level group; the groups assigned to this customer account will determine the access grant to the site.

<li><b>Block User - </b>enable/disable user access to site (Yes/No).

<li><b>Registered as - </b>indicates type of user as "Private Customer" and "Company Customer". If you are a representation of a company, "Company customer" would be chosen.

<li><b>Receive System Email - </b>enable/disable send the register email to user (Yes/No).
</ul>

<hr>

<h4 id="billing">Billing Information tab: </h4>

The billing information associated with your credit/debit cards. So that, an user information should have more options.

<h5>Fields</h5>

<img src="./manual/en-US/chapters/users/img/img7.png" class="example"/><br><br>

<ul>
<li><b>First Name - </b>the first name of the customer the bill will be addressed to.

<li><b>Last Name - </b>the last name of the customer the bill will be addressed to.

<li><b>Address - </b>the address the customer uses to receive their bills.

<li><b>City - </b>the city in which the above address is located.

<li><b>Country - </b>the country in which the above address is located. To customize, go to "Configuration" ->"Configuration"->"General tab" and add more countries at "Retail Countries".

<li><b>State -  </b>the state or province within the country selected.

<li><b>Phone - </b>the phone number of user. 

<li><b>Zipcode - </b>the zip code or area code that applies to the customer's address.
</ul>

<hr>

<h5>For "Company Customers" only</h5>

<img src="./manual/en-US/chapters/users/img/img8.png" class="example"/><br><br>

<ul>
<li><b>Company name -  </b>name of the company the user works for (Company Customers only)

<li><b>EAN Number -</b> EAN is one of many types of product codes, strings of several digits that identify an exact product on the market. Sellers use codes like EAN and UPC to make item processing at the checkout counter faster and facilitate managing items in a business’s inventory. EAN is primarily used in European nations, but the code is extremely similar to the UPC system used in North America.

<li><b>VAT Number - </b>the VAT number that the companies registered.

<li><b>Tax Exempt - </b>whether the customer is exempt from tax calculations when placing orders.

<li><b>User requested VAT exempt -  </b>whether the customer has ever placed a request for tax exemption.

<li><b>Tax Exempt Approved - </b>whether any requests for tax exemption were approved.
</ul>

<hr>

<h4 id="actions">Actions</h4>

<img src="./manual/en-US/chapters/users/img/img9.png" class="example"/><br><br>

<ul>
<li><b>Save - </b>apply the changes to the current user account.

<li><b>Save & Close - </b>apply the changes to the user details and move to the User Management page.

<li><b>Cancel - </b>return to the User Management without saving any changes.
</ul>

<hr>

<!-- Working with type of user in redSHOP -->
<h2 id="working">Working with type of user in redSHOP</h2>

<h4 id="private-1">Private Customer</h4>

<ul>
<li>Go to backend page of REDSHOP and click "User"-> "Add user" tab 
<img src="./manual/en-US/chapters/users/img/img10.png" class="example"/><br><br>

<li>User fill data in some field as: User name, Password, Email.... select Shopper Group and Group. User choose Registered as Private Customer later.
<img src="./manual/en-US/chapters/users/img/img11.png" class="example"/><br><br>

<li>Then, click on Billing Information tab.
<img src="./manual/en-US/chapters/users/img/img12.png" class="example"/><br><br>

<li>User fill data in some field as: First Name, Last Name, Address, Phone, City, ... . After that, user click on Save button.
<img src="./manual/en-US/chapters/users/img/img13.png" class="example"/><br><br>

<li>Later, user clicks on "Close" to return User management.
<img src="./manual/en-US/chapters/users/img/img14.png" class="example"/><br><br>

<li>Finally, webpage will display user just created with Registered as Private Customer.
<img src="./manual/en-US/chapters/users/img/img15.png" class="example"/><br><br>
</ul>

Video for Create User with Type Private Customer: <a href="https://redshop.fleeq.io/l/o0aygxdd0w-tawoga7q9j">Click here</a>

<hr>

<h4 id="company">Company Customer</h4>

<ul>
<li>Go to backend page of REDSHOP and click "User" -> "Add user" tab.
<img src="./manual/en-US/chapters/users/img/img16.png" class="example"/><br><br>

<li>User fill data in some field as: User name, Password, Email.... 
<img src="./manual/en-US/chapters/users/img/img17.png" class="example"/><br><br>

<li>User select Shopper Group and Group. Then, choose Registered as Company Customer.
<img src="./manual/en-US/chapters/users/img/img18.png" class="example"/><br><br>

<li>Click on Billing Information tab
<img src="./manual/en-US/chapters/users/img/img19.png" class="example"/><br><br>

<li>User fill data in some field as: First Name, Last Name, Address, Phone, City, ... .
<img src="./manual/en-US/chapters/users/img/img20.png" class="example"/><br><br>

<li>User click on Save button.
<img src="./manual/en-US/chapters/users/img/img21.png" class="example"/><br><br>

<li>User click on Close button to return User Management.
<img src="./manual/en-US/chapters/users/img/img22.png" class="example"/><br><br>

<li>Webpage will display user just created with Registered as Company Customer.
<img src="./manual/en-US/chapters/users/img/img23.png" class="example"/><br><br>
</ul>

Video for User have Type Company Customer: <a href="https://redshop.fleeq.io/l/o0aygxdd0w-tawoga7q9j">Click here</a>

<hr>

<!-- Create User on frontend -->
<h2 id="create">Create User on frontend</h2>

<ul>
<li>Go on Joomla administrator then click on "Menus" page Menus will display and on "Menu Items" then click "New" button to create 1 items menu
<img src="./manual/en-US/chapters/users/img/img24.png" class="example"/><br><br>

<li>User fill Registrater in Menu title. Then click on Select button
<img src="./manual/en-US/chapters/users/img/img25.png" class="example"/><br><br>

<li>Webpage will show popup have title "Menu Items Type" then user select redSHOP items in popup
<img src="./manual/en-US/chapters/users/img/img26.png" class="example"/><br><br>

<li>Page will show some items in dropdown list user will scroll down and select "Registration" items. 
<img src="./manual/en-US/chapters/users/img/img27.png" class="example"/><br><br>

<li>User select Menu display on frontend then click on Save button
<img src="./manual/en-US/chapters/users/img/img28.png" class="example"/><br><br>
</ul>

<hr>

<h4 id="private-2">Private Customer</h4>

<ul>
<li>User go on frontend and click on "Registrater" on menu.
<img src="./manual/en-US/chapters/users/img/img29.png" class="example"/><br><br>

<li>Webpage will display some field for user enter. Enter all field and click on Sign up.
<img src="./manual/en-US/chapters/users/img/img30.png" class="example"/><br><br>

<li>Webpage will redirect to Home page and login with account just created.
<img src="./manual/en-US/chapters/users/img/img31.png" class="example"/><br><br>

<li>To check user exist on User Management page, go to backend page of REDSHOP and click User then select "Users" tab.
<img src="./manual/en-US/chapters/users/img/img32.png" class="example"/><br><br>
</ul>

Video for Create User on frontend with Private Customer: <a href="https://redshop.fleeq.io/l/3trnwaawwf-ryxtp50gnk">Click here</a>

<hr>

<h4 id="business">Business</h4>

<ul>
<li>User go on frontend and click on "Registreater" on menu
<img src="./manual/en-US/chapters/users/img/img33.png" class="example"/><br><br>

<li>Webpage will display some field for user enter. Enter all field and click on Sign up
<img src="./manual/en-US/chapters/users/img/img34.png" class="example"/><br><br>

<img src="./manual/en-US/chapters/users/img/img35.png" class="example"/><br><br>

<li>Webpage will redirect to Home page and login with account just created
<img src="./manual/en-US/chapters/users/img/img36.png" class="example"/><br><br>

<li>To check user exist on User Management page, go to backend page of REDSHOP and click User then select "Users" tab 
</ul>

Video for Create User on frontend with Business: <a href="https://redshop.fleeq.io/l/hvcus1wvt7-uumab7lncf">Click here</a>

<hr>

<!-- Account Management on Frontend -->
<h2 id="account">Account Management on Frontend</h2>

<ul>
<li>Go on Joomla administrator then click on "Menus" page Menus will display and on "Menu Items" then click "New" button to create 1 items menu
<img src="./manual/en-US/chapters/users/img/img37.png" class="example"/><br><br>

<li>User fill Account in Menu title, then click on Select button.
<img src="./manual/en-US/chapters/users/img/img38.png" class="example"/><br><br>

<li>Webpage will show popup have title "Menu Items Type" then user select redSHOP items in popup; then click on Account.
<img src="./manual/en-US/chapters/users/img/img39.png" class="example"/><br><br>

<li>User choose Logout Redirection Page and  select Menu display on frontend. After that, click on Save button
<img src="./manual/en-US/chapters/users/img/img40.png" class="example"/><br><br>

<li>User go on frontend and login on site with any account.
<img src="./manual/en-US/chapters/users/img/img41.png" class="example"/><br><br>

<li>User click on Account on menu.
<img src="./manual/en-US/chapters/users/img/img42.png" class="example"/><br><br>

<li>Webpage will display Account Information of the logged-in account.
<img src="./manual/en-US/chapters/users/img/img43.png" class="example"/><br><br>
</ul>

Video create Account detail in front-end: <a href="https://redshop.fleeq.io/l/91kdjv5zbq-fjhfsbzvfg">Click here</a>

<hr>

<!-- Edit Account Information on frontend -->
<h2 id="edit">Edit Account Information on frontend</h2>

<ul>
<li>User click on Edit Account Information
<img src="./manual/en-US/chapters/users/img/img45.png" class="example"/><br><br>

<li>User update data then click on Save button
<img src="./manual/en-US/chapters/users/img/img45.png" class="example"/><br><br>
</ul>

<hr>

<!-- Add/Edit address on Frontend -->
<h2 id="add">Add/Edit address on Frontend</h2>

<ul>
<li>User click on Add/Edit Address
<img src="./manual/en-US/chapters/users/img/img46.png" class="example"/><br><br>

<li>Webpage will display Add address and Back if account don't have address. Click on Add address
<img src="./manual/en-US/chapters/users/img/img47.png" class="example"/><br><br>

<li>User fill data in some field as : First Name, Last Name,.... then click on Save button.
<img src="./manual/en-US/chapters/users/img/img48.png" class="example"/><br><br>

<li>Webpage will display item just created.
<img src="./manual/en-US/chapters/users/img/img49.png" class="example"/><br><br>

<li>To edit Shipping address, click Customer's name will receive product.
<img src="./manual/en-US/chapters/users/img/img50.png" class="example"/><br><br>

<li>User update data and click on Save button.
<img src="./manual/en-US/chapters/users/img/img51.png" class="example"/><br><br>

<li>Click on Remove button to remove an address.
<img src="./manual/en-US/chapters/users/img/img52.png" class="example"/><br><br>

<li>The address has removed on account.
<img src="./manual/en-US/chapters/users/img/img53.png" class="example"/><br><br>

<li>Click back to return Account Information
<img src="./manual/en-US/chapters/users/img/img54.png" class="example"/><br><br>

<li>Click on Delete Account to remove existing account.
<img src="./manual/en-US/chapters/users/img/img55.png" class="example"/><br><br>

<li>Webpage will display popup, click Yes to accept.
<img src="./manual/en-US/chapters/users/img/img56.png" class="example"/><br><br>

<li>Try to login with account just deleted, user can not access to page.
<img src="./manual/en-US/chapters/users/img/img57.png" class="example"/><br><br>

<li>Go to User Management page in backend and filter the account just deleted. It has been removed. 
<img src="./manual/en-US/chapters/users/img/img58.png" class="example"/><br><br>

</ul>

Video for Edit account and Add/Edit Address on frontend: <a href="https://redshop.fleeq.io/l/ry888ptu4q-y6yhaagye5">Click here</a>

<hr>

<h6>Last updated on October 9, 2019</h6>