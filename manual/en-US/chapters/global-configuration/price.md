## redSHOP Configuration - Price
This section covers more configuration settings regarding configuring price-related details, including the main currency displayed, tax-related and country-dependent details, as well as any types of discounts the shop administrator wishes to offer their customers. The controls are grouped together into four sections: "Price", "VAT / Tax", "Discounts" and "Discount Mail".

<hr>

### In this article you will fine:

<ul>
<li><a href="#main-price">Main Price Setting</a>
<li><a href="#vat-tax">VAT/TAX</a>
<li><a href="#gift-card">Gift Card Image Settings</a>
<li><a href="#discount">Discount</a>
<li><a href="#discount-mail">Discount Mail</a>
</ul>

<hr>

### Overview Price Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img44.png" class="example"/>

<hr>

<!-- Main Price Settings -->
<h2 id="main-price">Main Price Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img45.png" class="example"/>

<ul>
<li><b>Currency - </b>Sets the main currency the shop will operate with and display prices according to. The currency selected here will be used as the "base" currency when making price conversions using redSHOP's Currency module for customers who prefer paying in their own native currency. There are 124 currencies listed to choose from, but note that redSHOP's Currency module only supports conversion between 28 currencies. More information in theCurrencies section.

<li><b>Currency Symbol - </b>Sets the symbol that will be used to represent the main currency selected for the store. While this normally is represented as a literal symbol (such as the dollar's $), there is an opportunity to modify the output here to read something else (such as USD in the dollar's case).

<li><b>Currency Symbol Position - </b>Sets the position of the currency symbol in relation to the price. By default, redSHOP is configured to display this symbol on the left-hand side of whatever prices are displayed on both front and back ends, however the shop administrator can move the location to the right-hand side, or omit displaying the symbol entirely. 
<br><b>Available Options: </b>Front, Behind, None

<li><b>Decimal Separator - </b>Sets the punctuation mark that represents the decimal marker in prices. Customers in some countries expect the decimal marker to be represented by a comma (,), while others are used to marking them with a period (.). The shop administrator should decide which marker is more appropriate for the store's main market audience.

<li><b>Thousand Separator - </b>Similar to the Decimal Separator, this represents the marker indicating thousands. Customers in some countries are used to a period marking thousands (such as 1.000.000,00) while others expect a comma to mark them (such as 1,000,000.00). The shop administrator should decide which marker is appropriate for the store's main market audience.

<li><b>Number of Price Decimals - </b>Sets the number of decimal places that prices are stored and displayed in. Setting the number 3, for example, will display a price to 3 decimal places (such as 9.995).

<li><b>Number of Price Round - </b>Sets the number of decimal places that are used to round prices up. A value of 4, for example, will tell redSHOP to round prices up or down according to the value of the fifth digit in the price.

<li><b>Use Tax Exempt - </b>Sets whether redSHOP shall offer "tax-exemptions" to customers and display tax-exempt prices on the front and back end. Any features that are related to tax exemptions (such as configuration settings, shopper group settings, and sections and modules where prices are displayed) are dependent on this feature being set to "Yes" in order to work as expected. 
<br><b>Available Options: </b>Yes, No

<li><b>Show Tax Exempt in Front - </b>Sets whether tax-exempt prices shall be displayed to the customers on the front end. 
<br><b>Available Options: </b>Yes, No

<li><b>Apply VAT for Tax Exempt - </b>Sets whether VAT / tax will be applied to customers who have been assigned to a "tax-exempt" shopper group.
<br><b>Available Options: </b>Yes, No

<li><b>Remove Add to Cart from front (use as catalog) - </b>Sets whether the online store should act in "Catalog Mode", where the "Add-to-cart" button is hidden on all product catalog pages and within product-related modules. This feature allows shop administrators to feature their product catalog for customers to view but removes the ability to make any purchases. 
<br><b>Available Options: </b>Yes, No

<li><b>Show Price - </b>Sets whether prices will be displayed to customers on the front end. This affects all areas within redSHOP where prices are displayed, such as on product catalog pages and product-related modules. Prices will also be hidden in the cart and checkout process when this setting is enabled. This feature is useful as an addition to the "Remove Add to Cart from front (use as catalog" feature. 
<br><b>Available Options: </b>Yes, No
</ul>

<hr>

<!-- VAT/TAX  -->
<h2 id="vat-tax">VAT/TAX</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img46.png" class="example"/>

<ul>
<li><b>Default Country - </b>Selects the country that the physical store which the online store is representing is located in. As different countries have different tax rates, it is important to set the appropriate country here so the correct tax rates can be applied.

<li><b>Default State - </b>Once the Default Country above has been selected, this field will update to reflect the states that exist in that country, as listed in redSHOP's location database. Once again, the appropriate state should be selected so correct tax rates can be applied.

<li><b>Default VAT Group - </b>Sets the default tax / VAT group that redSHOP should use to calculate base taxes on prices throughout the online store. These VAT / tax groups contain any tax rates that the shop administrator has made applicable to specific countries and states, and different tax groups can be made to account for different tax rates based on specific situations. The shop administrator can select from the list of available VAT groups in the "VAT Groups" section. More information is available in redSHOP's "Taxes" section.

<li><b>Default VAT Calculation based on - </b>Sets the method in which redSHOP calculates the default amount of tax that is displayed throughout the shop. The shop administrator can select between:
    <ul>
    <li><b>Webshop - </b>tax will be calculated based on the tax rates and laws of the country that the physical store is located in. In this case, the customer will pay taxes according to the value set for the country that has been selected as the online store's "Shop Country".
    <li><b>Customer - </b>tax will be calculated based on the country / state combination in the address the customer provides during registration or checkout. (The value used to calculate tax in this instance will depend on the value configured for the "Calculation based on" setting.)
    <li><b>EU Mode - </b>tax will be calculated based on rules applying to EU countries.
    </ul>

Calculations will be made according to the tax rates configured in redSHOP's database for those countries / states. More information is available in the "Taxes" section.
<br><b>Available options: </b>Webshop, Customer, EU Mode

<li><b>Apply VAT on Discount - </b>Sets whether taxes should be applied on discounts that are applied to orders during the checkout process. Some stores are obligated to apply taxes on discounts while others aren't, so this option is up to the shop administrator's discretion.
<br><b>Available options: </b>No, Yes

<li><b>Calculation based on - </b>Sets the method in which redSHOP calculates the amount of tax the customer is expected to pay upon checkout. There are two options available:
    <ul>
    <li><b>Based on Billing Address - </b>tax will be calculated based on the country / state indicated in the customer's "Billing Address" address details.
    <li><b>Based on Shipping Address - </b>tax will be calculated based on the country / state that the order is being shipped to, indicated during the checkout process.
    </ul>

Calculations will be made according to the tax rates configured in redSHOP's database for those countries / states. More information is available in the "Taxes" section. 
<br><b>Available options: </b>Billing address, Shipping address

<li><b>Require VAT Number -  Available options: </b>No, Yes

<li><b>VAT introtext -</b>

<li><b>Introtext (without VAT) - </b>(textarea)

<li><b>Introtext (with VAT) - </b>(textarea)
</ul>

<hr>

<!-- Gift Card Image Settings -->
<h2 id="gift-card">Gift Card Image Settings</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img47.png" class="example"/>

<ul>
<li><b>Gift Card Thumb width/height - </b>

<li><b>Gift card List Thumb width/height -</b>

<li><b>Watermark Gift card Image - Available options: </b>No, Yes

<li><b>Watermark Gift card Thumb Image - Available options: </b>No, Yes
</ul>

<hr>

<!-- Discount -->
<h2 id="discount">Discount</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img48.png" class="example"/>

<ul>
<li><b>Allowed combinations of discounts - </b>Sets what combination of discounts, coupons and vouchers are allowed to be applied to orders upon checkout. Each can be applied on a one-time individual basis, restricted to using only one type at a time, or applied multiple times (such as redeeming multiple coupons in one order). The options available include:

<li><b>Discount/voucher/coupon - </b>this sets the checkout process to accept only one kind of promotion, either discount OR voucher OR coupon. If global discounts are in place and considered during checkout, vouchers and coupons will not be considered in the reduced price.

<li><b>Discount + voucher/coupon - </b>this sets the checkout process to consider discounts and allow redemption of either one voucher code OR one coupon code.

<li><b>Discount + voucher (single) + coupon (single) - </b>this sets the checkout process to consider discounts as well as allowing redemption of one voucher and one coupon each. This means an order can be affected by a global discount, as well as one coupon code and one voucher code.

<li><b>Discount + voucher (multiple) + coupon (multiple) - </b>this sets the checkout process to consider discounts as well as allowing multiple vouchers and coupons to be redeemed on the order. Each voucher and coupon can be used only once per order, although it is possible to have multiple different vouchers and coupons apply.
The duration and number of times each voucher and coupon can be used will depend on each one's settings respectively. More information is available in the Discounts and Promotions section.

<li><b>Enable Coupons - </b>Sets whether coupons are available for usage throughout the store. Coupons consist of coupon codes that can be entered during checkout to take advantage of reduced price offers on whole orders. Coupons are assigned to customers, in general or to specific customers, and have controls to set durations of validity and number of uses. 
<br<b>Available Options: </b>No, Yes

<li><b>Enable Discount - Available options: </b>No, Yes

<li><b>Enable Vouchers - </b>Sets whether vouchers are available for usage throughout the store. Vouchers are similar to Coupons in that they consist of voucher codes that can be entered during checkout to take advantage of reduced price offers on products within orders. Unlike coupons however, vouchers are assigned to products, in general or to specific products, and have controls to set durations of validity and number of uses. 
<br><b>Available Options: </b>No, Yes

<li><b>Send Special discount mail - </b>Sets whether redSHOP should follow a customer's completed purchase with an email offering "special discount" for future purchases. (Details to follow.).
<br><b>Available Options: </b>No, Yes

<li><b>Calculate shipping based - </b>Sets whether redSHOP will calculate shipping rates based on the total or sub-total price of the order in checkout. Some stores prefer to calculate the cost of shipping based on the sub-total of the order (the sum of the products in the order) while other stores prefer to calculate based on the total of the order (the sum of all the costs the order involves, including taxes and discounts). This option is up to the shop administrator's discretion.  
<b>Available Options: </b>Total, Subtotal

<li><b>Value of discount coupon is percentage or total -  </b>Sets whether the value of the discount coupon sent in the mail refers to a lump sum discount or a percentage value that is applied to the customer's next order.
<b>Available Options: </b>Total, Percentage

<li><b>Amount - </b>The value of the coupon being sent in the discount mail. This value is offered as a "total" / lump-sum discount or as a percentage off the next purchase based on the value set for the above "Value of discount coupon is percentage or total" setting.
</ul>

<hr>

<!-- Discount Mail -->
<h2 id="discount-mail">Discount Mail</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img49.png" class="example"/>

<ul>
<li><b>Send discount mail - </b>Sets whether redSHOP should follow a customer's completed purchase with an email offering "special discount" for future purchases. (Details to follow.) 
<br><b>Available options: </b>No, Yes

<li><b>No of days for mail 1 After Purchase - </b>The number of days after a customer has completed their purchase before they receive their first discount mail. The template for this first email can be found in the Mail Center, labelled "First mail after order purchased".

<li><b>No of days for mail 2 After Purchase - </b>The number of days after a customer has completed their purchase before they receive their second discount mail. The template for this second email can be found in the Mail Center, labelled "Second mail after order purchased".

<li><b>No of days for mail 3 After Purchase - </b>The number of days after a customer has completed their purchase before they receive their third discount mail. The template for this third email can be found in the Mail Center, labelled "Third mail after order purchased".

<li><b>VAT after discount - </b>Sets the VAT / tax rate to be applied after the discount has been included in the order price calculations. A tax rate of 16%, for example, would be stored as a value of 0.16 (as redSHOP will factor this value into the tax calculations). Setting a value of 16 will imply to redSHOP a tax rate of 1600%, so bear this value in mind.

<li><b>Coupon Validity in Days - </b>Sets the number of days after the first discount mail has been sent before the coupon offered in the mail expires.
</ul>

<h6>Last updated on July 23, 2020</h6>