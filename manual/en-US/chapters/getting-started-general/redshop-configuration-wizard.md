## redSHOP Configuration Wizard
The Configuration Wizard consists of a six-section guide that offers the shop administrator the ability to configure basic details required to initially set up redSHOP. The Configuration Wizard can be accessed from three areas:

<ul>
<li>The "Configuration Wizard" button that appears after installing redSHOP

<li>The "Configuration Wizard" button on the top-right hand corner of redSHOP's Main Menu

<li>The "Start Configuration Wizard" link within redSHOP's left-hand "Configuration" navigation panel
</ul>

<hr>

<b>This feature is aimed at:</b>

<ul>
<li>Beginners and newcomers who are setting up their redSHOP for the first time (as this wizard hides many of the additional details that are not necessary),

<li>Those who are re-installing redSHOP and choose to modify their basic configuration settings

<li>Those who prefer to configure redSHOP and are not comfortable with the "Global Configuration" screen
</ul>

There are six sections in the "Configuration Wizard", the first is an introductory screen followed by four sections, each giving the shop administrator the opportunity to configure general settings, set the shop's terms and conditions, configure user account settings, and adjust the shop's price-related settings, before arriving at the last "Finished" section.

At any stage while guided through the wizard, the current section will be indicated in the section guide on the left side of the wizard. There are also three links available to navigate between sections:  "Previous" in the top left corner of the wizard, and in the top-right are "Next" to move to the next section being viewed and "Skip Wizard" to leave the wizard.

<hr>

## Step 1: Welcome to Configuration Wizard
This section introduces the user to the wizard and what will be covered. There are links to redSHOP's documentation and our support system as well as the GNU General Public License that redSHOP is released under. A short notice is also displayed to remind administrators to check for updates, although the upcoming "Automatic Update" system will assist administrators in keeping their redSHOP installations up to date.

##### Click on "Next" to proceed to Step 2.

<hr>

## Step 2: General settings
The first step in setting up redSHOP is collecting some basic information on the store being created. This basic information involves collecting the following details. (Note: there will be opportunity to modify these details after the wizard has been completed by restarting the wizard or accessing theGlobal Configuration section.)

<b>Shop Name - </b>The name of the online store.

<b>Administrator Email - </b>The email address of the store administrator, the one responsible for operating the online store. If there are multiple administrators, enter their email addresses separated by commas.

<b>Shop Country - </b>Selects the country that the physical store is located in. This is relevant for tax purposes.

<b>Default Shipping Country - </b>Selects the country whose market the online store will mainly cater for. This is usually the same country selected for Shop Country.

<b>Countries you will sell to - </b>Select the countries that the shop will allow taking sales from. Orders will be accepted from customers whose addresses reside in the countries approved and selected in this list. Multiple countries can be selected by holding down the "Ctrl" key when clicking on the list of countries available to choose from.

<b>Date Format - </b>Sets the format in which dates will be stored and displayed within redSHOP, on both the front and back ends. There are 26 formats to choose from, and the date format selected should be both store and market appropriate.

<b>Enable Invoice Email - </b>Sets whether an invoice email (including a breakdown of order details) should be sent automatically whenever an order is created the online store. This is normally set to "No" by default, but the shop administrator can set this to their preference. In the "Global Configuration" section, it can be further specified whether these invoice emails should be sent only to customers, only to the administrator, or both.

<b>Available options:</b> Yes, No

##### Click on "Next" to proceed to Step 3.

<hr>

## Step 3: Terms And Conditions

The next step in setting up redSHOP is outlining the terms and conditions that customers should be made aware of before making any purchases.

These terms and conditions are to be stored in a Joomla! article which redSHOP will refer to and display a link to during the checkout process, and it is possible to configure redSHOP to require the customer having confirmed reading these terms and conditions before the checkout process will proceed any further.

If a Joomla! article containing the store's terms and conditions exists prior to launching the Configuration Wizard, the administrator can use the Selectbutton to open up a window listing all Joomla! articles available and click on the appropriate article to set it.

If the article has not yet been written, there is an Add link which when clicked will open a new window redirecting to an empty Joomla! article, where the administrator can enter and store the terms and conditions. Once the details have been entered and the article saved, the administrator will have to return to the "Configuration Wizard" window and use the Select button mentioned above to select the new article.

Note that as the terms and conditions are stored in a Joomla! article, the contents can be organized and formatted using HTML tags in whatever fashion the administrator so chooses to display them.

##### Click on "Next" to proceed to Step 4.

<hr>

## Step 4: User Settings
The next step in setting up redSHOP is configuring how the store handles customer account registrations, and the messages that should be displayed to customers when they're logged in, dependent on their assigned shopper group. The fields that collect this information includes:

<b>Registration Method - </b>Selects the way the store deals with account registrations when guests or customers without accounts proceed with the checkout process to complete their orders. While there are essential details required during the checkout process to monitor and track orders, the customer does not necessarily need to create an account to checkout. This is referred to as a "Guest checkout". However, there are benefits to creating an account, including being able to more easily make orders and track them in future. The store administrator can choose from four behaviours:

<b>Normal account creation - </b>the customer will be required to create an account on the site in order to continue with the checkout process. redSHOP will use the account name to refer to the order holder.

<b>Without account creation - </b>the customer can proceed through the checkout process without being required to create an account, in which case redSHOP will use the First and Last Names entered in the order details to refer to the order holder.

<b>Optional account creation - </b>the customer will be given the option to create an account if they so choose, however it will not be required to proceed through the checkout process.

<b>Silent account creation - </b>the customer will not be offered an option to create an account, however an account will be created on their behalf as soon as the checkout process is complete and their first order is confirmed.

<b>"My Page" Welcome Text - </b>Sets the message that will be displayed on the customer's "My Page" account page. redSHOP offers customers who have accounts a page from where they can access further details and options pertaining to their accounts, such as previous and current orders and quotations, tags and more. This page is accessible through the "Account Layout" menu item, and the message entered here for this "Welcome Text" will be displayed at the top of this "Account Layout" page.

<b>Introtext for Private Customers - </b>Sets the message that will be displayed to customers who selected the "Private" radio button when creating their account during the checkout "registration" process. This message will appear when..............

<b>Introtext for Company Customers - </b>Sets the message that will be displayed to customers who selected the "Company" radio button when creating their account during the checkout "registration" process. This message will appear when..............

More information about "Private" and "Company" customers can be found in the Shopper Group section.

##### Click on "Next" to proceed to Step 5.

<hr>

## Step 5: Price settings

The last step in setting up redSHOP involves configuring price-related details, including the main currency displayed, tax-related and country-dependent details, as well as any types of discounts the shop administrator wishes to offer their customers. Details are collected in fields separated into these three categories:

### Price

<b>Currency - </b>Sets the main currency the shop will operate with and display prices according to. The currency selected here will be used as the "base" currency when making price conversions using redSHOP's Currency module for customers who prefer paying in their own native currency. There are 124 currencies listed to choose from, but note that redSHOP's Currency module only supports conversion between 28 currencies. More information in theCurrencies section.

<b>Currency Symbol - </b>Sets the symbol that will be used to represent the main currency selected for the store. While this normally is represented as a literal symbol (such as the dollar's $), there is an opportunity to modify the output here to read something else (such as USD in the dollar's case). By default, redSHOP is configured to display this currency symbol on the left-hand side of whatever prices are displayed on both front and back ends, however there is an opportunity to change this in the "Global Configuration" section.

<b>Decimal Separator - </b>Sets the punctuation mark that represents the decimal marker in prices. Customers in some countries expect the decimal marker to be represented by a comma (,), while others are used to marking them with a period (.). The shop administrator should decide which marker is more appropriate for the store's main market audience.

<b>Thousand Separator - </b>Similar to the Decimal Separator, this represents the marker indicating thousands. Customers in some countries are used to a period marking thousands (such as 1.000.000,00) while others expect a comma to mark them (such as 1,000,000.00). The shop administrator should decide which marker is appropriate for the store's main market audience.

<b>Number of Price Decimals - </b>Sets the number of decimal places that prices are stored and displayed in. Setting the number 3, for example, will display a price to 3 decimal places (such as 9.995).

### VAT / TAX

<b>Enter your default VAT country and state: Default Country - </b>Selects the country that the physical store which the online store is representing is located in. As different countries have different tax rates, it is important to set the appropriate country here so the correct tax rates can be applied.

<b>Default State - </b>Once the Default Country above has been selected, this field will update to reflect the states that exist in that country, as listed in redSHOP's location database. Once again, the appropriate state should be selected so correct tax rates can be applied.

<b>Calculation based on - </b>Sets the method in which redSHOP calculates the amount of tax the customer is expected to pay upon checkout. There are two options available:

<b>Based on Billing Address - </b>tax will be calculated based on the country / state indicated in the customer's "Billing Address" address details.

<b>Based on Shipping Address - </b>tax will be calculated based on the country /state that the order is being shipped to, indicated during the checkout process.

Calculations will be made according to the tax rates configured in redSHOP's database for those countries / states. More information is available in the "Taxes, Currencies and Locations" section.Available options: Billing address, Shipping address

<b>Add VAT Rates - </b>Allows the shop administrator to set tax rates for specific countries / states at this point, although tax rates can always be configured later in redSHOP's Tax section. Tax rates can be added by clicking on the "Add rates" link and selecting the country and state that the tax rate should apply to, setting whether this tax rate applies to an EU country, and finally entering the tax rate itself as a percentage value. For example, a tax rate of 16% would be stored as a value of 0.16 (as redSHOP will factor this value into the tax calculations). Setting a value of 16 will imply to redSHOP a tax rate of 1600%, so bear this value in mind.

<b>Apply VAT on Discount - </b>Sets whether taxes should be applied on discounts that are applied to orders during the checkout process. Some stores are obligated to apply taxes on discounts while others aren't, so this option is up to the shop administrator's discretion. Available options: No, Yes

### Discounts
There are generally three methods through which shop administrators can offer their customers price reductions: discounts, coupons, and vouchers.

<b>Enable Discounts - </b>Sets whether discounts are available for usage throughout the store. This refers to standard discounts that can be assigned to specific products, categories of products, or across the whole store in general (such as global discount promotions that apply to all products). Available options: No, Yes

<b>Enable Coupons - </b>Sets whether coupons are available for usage throughout the store. Coupons consist of coupon codes that can be entered during checkout to take advantage of reduced price offers on whole orders. Coupons are assigned to customers, in general or to specific customers, and have controls to set durations of validity and number of uses. Available options: No, Yes

<b>Enable Vouchers - </b>Sets whether vouchers are available for usage throughout the store. Vouchers are similar to Coupons in that they consist of voucher codes that can be entered during checkout to take advantage of reduced price offers on products within orders. Unlike coupons however, vouchers are assigned to products, in general or to specific products, and have controls to set durations of validity and number of uses. Available options: No, Yes

<b>Allowed combinations of discounts - </b>Sets what combination of discounts, coupons and vouchers are allowed to be applied to orders upon checkout. Each can be applied on a one-time individual basis, restricted to using only one type at a time, or applied multiple times (such as redeeming multiple coupons in one order). The options available include:

<b>Discount/voucher/coupon - </b>this sets the checkout process to accept only one kind of promotion, either discount OR voucher OR coupon. If global discounts are in place and considered during checkout, vouchers and coupons will not be considered in the reduced price.

<b>Discount + voucher/coupon - </b>this sets the checkout process to consider discounts and allow redemption of either one voucher code OR one coupon code.

<b>Discount + voucher (single) + coupon (single) - </b>this sets the checkout process to consider discounts as well as allowing redemption of one voucher and one coupon each. This means an order can be affected by a global discount, as well as one coupon code and one voucher code.

<b>Discount + voucher (multiple) + coupon (multiple) - </b>this sets the checkout process to consider discounts as well as allowing multiple vouchers and coupons to be redeemed on the order. Each voucher and coupon can be used only once per order, although it is possible to have multiple different vouchers and coupons apply.

The duration and number of times each voucher and coupon can be used will depend on each one's settings respectively. More information is available in the Discounts and Promotions section.

<b>Calculate shipping based on - </b>Sets whether redSHOP will calculate shipping rates based on the total or sub-total price of the order in checkout. Some stores prefer to calculate the cost of shipping based on the sub-total of the order (the sum of the products in the order) while other stores prefer to calculate based on the total of the order (the sum of all the costs the order involves, including taxes and discounts). This option is up to the shop administrator's discretion. Available options: Total, Subtotal

##### Click on "Next" to complete the wizard and proceed to the final Step 6.

<hr>

## Step 6: Finish
At this point, redSHOP has collected enough details to configure the store for basic usage. This last screen will display a message confirming the collection of all essential settings, and that redSHOP is now ready to have its product catalog added to and further shop details to be customized.

The shop administrator can now use the "Previous" button to backtrack through the Configuration Wizard sections to ensure all details are confirmed correct, or click on the "Save Changes" button to complete the wizard. A checkbox is available to install "demo content", the same sample data that gets installed upon clicking the "Install sample data" button post-install, and it can be installed by checking the box before clicking on "Save Changes".

#### Completing the wizard will redirect the admin to redSHOP's Main Menu.

<hr>

<h6>Last updated on January 4, 2016</h6>