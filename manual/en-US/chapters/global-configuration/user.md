## redSHOP Configuration - User
This section covers more configuration settings regarding how the store handles customer account registrations and the messages that should be displayed to customers when they're logged in. There are also additional controls regarding shopper groups, and in particular if the store should be set up as a "portal shop". The controls are grouped together into two sections: "Registration" and "Shopper Groups".We've written and article to go over each tab in the configuration.  The following list will give you a quick overview and link to each article for more in depth reading.  Each of the provided links will open a new tab in your browser, keeping this page open for reference

<hr>

### In this article you will fine:

<ul>
<li><a href="#registration">Registration</a>
<li><a href="#shopper-groups">Shopper Groups</a>
</ul>

<hr>

### Overview User Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img7.png" class="example"/>

<hr>

<!-- Registration -->
<h2 id="registration">Registration</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img8.png" class="example"/>

<ul>
<li><b>Registration Method - </b>Selects the way the store deals with account registrations when guests or customers without accounts proceed with the checkout process to complete their orders. While there are essential details required during the checkout process to monitor and track orders, the customer does not necessarily need to create an account to checkout. This is referred to as a "Guest checkout". However, there are benefits to creating an account, including being able to more easily make orders and track them in future. The store administrator can choose from four behaviors:
    <ul>
    <li><b>Normal account creation - </b>the customer will be required to create an account on the site in order to continue with the checkout process. redSHOP will use the account name to refer to the order holde
    <li><b>Without account creation - </b>the customer can proceed through the checkout process without being required to create an account, in which case redSHOP will use the First and Last Names entered in the order details to refer to the order holder.
    <li><b>Optional account creation - </b>the customer will be given the option to create an account if they so choose, however it will not be required to proceed through the checkout process.
    <li><b>Silent account creation - </b>the customer will not be offered an option to create an account, however an account will be created on their behalf as soon as the checkout process is complete and their first order is confirmed.
    </ul>

<li><b>Create New User upon Registration Default - </b>Available options: Yes, No

<li><b>Email verification - </b>Sets whether the customer must verify their account registration before redSHOP will allow them to log in and continue with the checkout process (or any other section that requires the customer be logged in). An email will be sent to the customer after they register for an account, and the activation link within that email must be clicked to confirm their intent to register and complete the account registration process.

<li><b>Available options: </b>Yes, No

<li><b>New customer preselected - </b>Sets whether redSHOP will display the "New Customer" registration form panel first whenever a customer enters the checkout process as a guest or when they're not logged in. There are two panels that can appear on redSHOP's Account Login / Registration page in the initial stages of the checkout process: one for existing users to log in, and another to offer users the ability to register for accounts. By default, the "Account Login" panel is displayed first, this option allows the shop administrator to force the second panel displayed first. 

<li><b>Available options: </b>Yes, No

<li><b>Terms And Conditions - </b>Allows the shop administrator to select the Joomla! article that contains the online store's "Terms and Conditions".

<li><b>Who Can Register - </b>Sets the type of customers that redSHOP can create accounts for, and the default shopper group into which new customers will be assigned. The shop administrator can specify whether only "Private" or "Company" customers can register for accounts, in which case the registration form with the appropriate details will appear, or if "Both" types of customer can register for accounts, in which case there will be separate "Private customer" and "Company customer" registration forms available that the customer can select via the radio buttons that appear labelled accordingly. 

<li><b>Available options: </b>Private Customers, Company Customers, Both

<li><b>Default Customer Type - </b>When the setting for "Who can Register" has been set to "Both", this sets which of the two customer type registration forms will be pre-selected and be displayed first. This setting should reflect the main type of customer that the shop deals with, as this conveniently displays the most relevant registration form first accordingly.

<li><b>"My Page" Welcome Text - </b>Sets the message that will be displayed on the customer's "My Page" account page. redSHOP offers customers who have accounts a page from where they can access further details and options pertaining to their accounts, such as previous and current orders and quotations, tags and more. This page is accessible through the "Account Layout" menu item, and the message entered here for this "Welcome Text" will be displayed at the top of this "Account Layout" page.

<li><b>Introtext for Private Customers - </b>Sets the message that will be displayed to customers who selected the "Private" radio button when creating their account during the checkout "registration" process. 

<li><b>Introtext for Company Customers - </b>Sets the message that will be displayed to customers who selected the "Company" radio button when creating their account during the checkout "registration" process.
</ul>

<hr>

<!-- Shopper Groups -->
<h2 id="shopper-groups">Shopper Groups</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img9.png" class="example"/>

<ul>
<li><b>Portal Shop - </b>Sets whether redSHOP should act as a "Portal Shop", which will only allow access to customers that are logged in and only allow access to those areas of the shop that have been designated accessible to the customer's shopper group. Designed for situations where the online store caters for different customers and different markets, enabling this feature will force redSHOP to consider the access levels defined in the customer's shopper group settings and, in conjunction with the shopper group related modules, display only the categories and products available to that shopper group. As each customer / shopper group will have access to different areas, redSHOP will dynamically modify the online store and related modules for each customer, so multiple customers from multiple groups can be logged into the same store but only have access to specific sections defined by the shop administrator. More information about shopper group access levels is available in redSHOP'sShopper Group section. 

<li><b>Available options: </b>Yes, No

<li><b>Default Private Shopper Group - </b>Sets the "Private" shopper group that new customers selecting the "Private customer" option will be assigned to upon account registration. The shop administrator can select from the list of available shopper groups in the "Private customer" category. More information is available in the Shopper Group section.

<li><b>Default Company Shopper Group - </b>Sets the "Company" shopper group that new customers selecting the "Company customer" option will be assigned to upon account registration. The shop administrator can select from the list of available shopper groups in the "Company customer" category, as well as those in the "Tax Exempt customer" category, in the event that there are tax-exempt shopper groups which the administrator would care to assign customers to by default. More information is available in the Shopper Group section.

<li><b>Select Shopper Group for Unregistered Users - </b>Sets the shopper group rules which will apply to guests and unregistered users. The shop administrator can select from the list of available shopper groups. More information is available in the Shopper Group section.

<li><b>A New Shopper Group will inherit value from - </b>Sets the shopper group (and all its configured settings) that will be used as a template when creating new shopper groups in the back-end. This is a time-saver feature, as it is possible to create multiple groups using one group as reference and the shop administrator can then modify these new groups to simply adjust them accordingly. More information is available in the Shopper Group section.
</ul>

<hr>

<h6>Last updated on July 22, 2019</h6>