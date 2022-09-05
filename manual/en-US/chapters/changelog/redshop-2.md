## redSHOP 2
redCOMPONENT is pleased to announce the immediate availability of redSHOP 2.0 This is a Improvement release. New redSHOP Backend UI is added in this release along with bug fixes.

<b>Important note: redSHOP 2.x is only compatible with Joomla 3.x.</b> If your site is still in Joomla 2.5 or early note that your version is not any more maintained and update is recommended.

<hr>

### Install and UPDATE instructions
To install or update redSHOP check the instructions page: [Updating redSHOP](chapters/getting-started-general/updating-redshop.md)

<hr>

### Changelog - redSHOP - Version 2.0

<ul>
<li><a href="#bugs">Bugs</a>
<li><a href="#stories">Stories</a>
<li><a href="#newFeature">New Feature</a>
<li><a href="#improvements">Improvements</a>
<li><a href="#task">Task</a>
<li><a href="#subTask">Sub-Task</a>
<li><a href="#modulesReleased">Modules Released in 2.0</a>
<li><a href="#pluginsReleased">Plugins Released in 2.0</a>
</ul>

<hr>

<h4 id="bugs">Bugs</h4>

<ul>
<li>[REDSHOP-1540] - User import doesn't work
<li>[REDSHOP-1735] - Alignment of info icons and tick fields
<li>[REDSHOP-1998] - Discount price not work on some products
<li>[REDSHOP-2519] - UX issue when editing a user from admin
<li>[REDSHOP-2531] - redshop payment express not working when the option (is credit card) is no.
<li>[REDSHOP-2681] - [b/c] Search menu item do not open search view
<li>[REDSHOP-2728] - problem sending download mail after changing order info from backend
<li>[REDSHOP-2780] - Update quickpay plugin
<li>[REDSHOP-2793] - Category order_by parameter
<li>[REDSHOP-2811] - Improve Question test, failing due to Selenium Update
<li>[REDSHOP-2838] - No name in product review by guest
<li>[REDSHOP-2847] - wrong call to ini constant in user edit
<li>[REDSHOP-2880] - Notice: Undefined variable: product_old_price_excl_vat
<li>[REDSHOP-2902] - wrong redirect when username already exist
<li>[REDSHOP-2931] - Publishing and unpublishing xml export fails in list
<li>[REDSHOP-2942] - Ajax cart box not show to choose product attribute when adding to cart from module redshop featured product
<li>[REDSHOP-2944] - missing .ini string in Giftcard search box
<li>[REDSHOP-2946] - stock value not working
<li>[REDSHOP-2950] - PHP 7 notice in addorder_detail view
<li>[REDSHOP-2953] - Wrong codification in statistics module
<li>[REDSHOP-2960] - Shows wrong stock amount in product
<li>[REDSHOP-2977] - Update Paypal checkout acceptance test
<li>[REDSHOP-2980] - Some labels of quotation mail do not work
<li>[REDSHOP-2993] - bug MVC plugin redshop1.6.1
<li>[REDSHOP-2997] - MySQL Error on dashboard when no order
<li>[REDSHOP-3005] - Clicking on wrapper button from product list view is not working
<li>[REDSHOP-3006] - Select list width is small in template manager
<li>[REDSHOP-3011] - Backend new UI - Hide toolbar buttons based on config settings
<li>[REDSHOP-3053] - Missing language strings in epay payment plugin
<li>[REDSHOP-3070] - Product prices hidden.
<li>[REDSHOP-3072] - Error in the sidebar menu
<li>[REDSHOP-3100] - Click on "PDF icon" in order view should generate and download PDF, not show a box where you can download it
<li>[REDSHOP-3112] - Product custom field view
<li>[REDSHOP-3114] - Missing texts in shipping management
<li>[REDSHOP-3134] - Updating redSHOP from 1.5.0.5.3 to 2.0 results in fatal error - see attached image
<li>[REDSHOP-3173] - Can't save Partially paid status in order
<li>[REDSHOP-3186] - Install latest Redshop dev on Joomla clean site : Fatal error: Class 'redhelper' not found
<li>[REDSHOP-3188] - Edit custom field : Trigger at the Option Value works incorrectly
<li>[REDSHOP-3193] - Warnings and notices after upgrade from 1.5 to 2.0 on Joomla 3
<li>[REDSHOP-3199] - Broken backend template when update order status in backend.
<li>[REDSHOP-3202] - Unparsed tags in edit user on checkout
<li>[REDSHOP-3207] - Category pages results in 500 - see description
<li>[REDSHOP-2966] - compare module do not work
<li>[REDSHOP-2967] - Can't use "related products"
<li>[REDSHOP-2968] - Refactor catalog
</ul>

<hr>

<h4 id="stories">Stories</h4>

<ul>
<li>[REDSHOP-2074] - update screenshots at redSHOP landing page
<li>[REDSHOP-2273] - move Payment plugins to redSHOP 1.5
<li>[REDSHOP-2542] - EPAY Payment Plugin gets Stuck After Final Checkout
<li>[REDSHOP-2986] - Design - backend UI for redSHOP
<li>[REDSHOP-2988] - Implement - backend UI for redSHOP
<li>[REDSHOP-2989] - Can not remove compare products item from view=product&layout=compare
<li>[REDSHOP-3091] - Admin > Improve layout for order detail
<li>[REDSHOP-3180] - Hide top menu at dashboard on mobile
<li>[REDSHOP-3197] - Don't enable Payment, Shipping plugin when update redSHOP, even it was deleted
<li>[REDSHOP-3203] - Admin > Improve tags layout on SEO
</ul>

<hr>

<h4 id="newFeature">New Feature</h4>

<ul>
<li>[REDSHOP-2282] - Test Googlecheckout payment plugin and add compatibility with redSHOP 1.6
<li>[REDSHOP-2354] - Include voucher and discount information in invoice and order info
<li>[REDSHOP-2392] - Universal Analytics
<li>[REDSHOP-2617] - Estimate for 1-step checkout
<li>[REDSHOP-2826] - Verify new Paypal guidelines for Paypal Payments Pro
<li>[REDSHOP-2976] - Incorporate PayPal Vault feature in paypal credit card plugin
<li>[REDSHOP-3087] - Notifications for product has amount below specific amount.
</ul>

<hr>

<h4 id="improvements">Improvements</h4>

<ul>
<li>[REDSHOP-1081] - Add apply button to quotation creation
<li>[REDSHOP-1144] - Security Testing of RedShop WEB application
<li>[REDSHOP-1921] - add a link to the dashboard
<li>[REDSHOP-1984] - Move all helpers call to autoload
<li>[REDSHOP-2065] - Order detail view needs a clean up
<li>[REDSHOP-2218] - Allow related products template to include custom field tags
<li>[REDSHOP-2473] - Issue adding Medias
<li>[REDSHOP-2609] - Create Payment plugin for INGENICO
<li>[REDSHOP-2645] - Manufacturers template has no pagination by default
<li>[REDSHOP-2653] - Layout for cart module has hard coded path, cannot be overridden
<li>[REDSHOP-2790] - Coupon code must be by default in "Giftcard mail" in the "Mail center".
<li>[REDSHOP-2799] - Use Google Recaptcha in Ask Question
<li>[REDSHOP-2802] - Sucuri malware detector warns about barcode.php
<li>[REDSHOP-2845] - missing search bar in Vouchers list
<li>[REDSHOP-2855] - install PDF library as extra package
<li>[REDSHOP-2870] - Add search filter for Stockimage
<li>[REDSHOP-2879] - Vouchers field "product" should be required
<li>[REDSHOP-2888] - Post Danmark Shipping plugin should display select list for mobile
<li>[REDSHOP-2904] - GetProductList funtion - as it's double
<li>[REDSHOP-2919] - Using correct return type for default user info
<li>[REDSHOP-2920] - Remove unused variables from admin
<li>[REDSHOP-2937] - Fix to get clean ajax response
<li>[REDSHOP-2952] - redSHOP statistics fails to calculate sale
<li>[REDSHOP-2961] - Update tests to codeception 2.2
<li>[REDSHOP-2962] - Refactor error handling
<li>[REDSHOP-2963] - change getProductById($product_id) for Redshop::product($product_id); Admin
<li>[REDSHOP-2969] - Payment process fail when the checkbox "Require CVD number for credit card transactions" in admin page of Beanstream is checked
<li>[REDSHOP-2978] - Add field "number of items sold" in best seller products statistic
<li>[REDSHOP-2991] - Educate developer by throwing exception for deprecated argument
<li>[REDSHOP-2994] - Paypal Credit Card Payment: Negative vat issue
<li>[REDSHOP-3018] - Correct Quotation language constants
<li>[REDSHOP-3025] - Misstypo of config param name "COMARE", instead of "COMPARE"
<li>[REDSHOP-3052] - Move products helpers call to autoload
<li>[REDSHOP-3055] - Move Captcha helpers call to autoload
<li>[REDSHOP-3056] - Move Cart helpers call to autoload
<li>[REDSHOP-3057] - Move Cron helpers call to autoload
<li>[REDSHOP-3058] - Move Extra Field helpers call to autoload
<li>[REDSHOP-3059] - Move google analytic helpers call to autoload
<li>[REDSHOP-3060] - Move site helper of helpers call to autoload
<li>[REDSHOP-3061] - Move redshop js helpers call to autoload
<li>[REDSHOP-3062] - Move statistics helpers call to autoload
<li>[REDSHOP-3063] - Move user helpers call to autoload
<li>[REDSHOP-3064] - Remove zip helper
<li>[REDSHOP-3080] - Rewrite Country and states dropdown
<li>[REDSHOP-3082] - Fix mistype of "userfiled" in redshop repository, need to be changed to "userfield"
<li>[REDSHOP-3092] - Admin > Improvement Order List layout
<li>[REDSHOP-3094] - Admin > Welcome message for user.
<li>[REDSHOP-3200] - Use Joomla Robots instead of redSHOP Robots
<li>[REDSHOP-2271] - All Extensions compatible with redSHOP 1.5
</ul>

<hr>

<h4 id="task">Task</h4>

<ul>
<li>[REDSHOP-1232] - redSHOP invoice PDF template
<li>[REDSHOP-2052] - Error in sandbox of the 2checkout payment plugin
<li>[REDSHOP-2878] - Can't assign default image
<li>[REDSHOP-2883] - Giftcard do not cover shipping cost?
<li>[REDSHOP-2907] - Review upcoming changes in Authorize.net for possible need to update our plugin
<li>[REDSHOP-2992] - Create Notification Alert
<li>[REDSHOP-3071] - Remove redCRM support from redSHOP
<li>[REDSHOP-3073] - Remove storing cart in cookie - Cart Module
</ul>

<hr>

<h4 id="subTask">Sub-Task</h4>

<ul>
<li>[REDSHOP-627] - Move - Use Stockroom
<li>[REDSHOP-1479] - Test the checkout process with discounts, VAT,...
<li>[REDSHOP-2076] - fix Cielo plugin
<li>[REDSHOP-2263] - review status of DIBS payment plugins
<li>[REDSHOP-2274] - Test Authorise DPM payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2277] - Test dotpay payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2283] - Test ImgGlobal payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2701] - 2Checkout error in test when running in Travis
<li>[REDSHOP-2938] - Error message when can't add product to compare
<li>[REDSHOP-2939] - Global or Category comparison in products not working
<li>[REDSHOP-2983] - Remove unused variables from Modules
<li>[REDSHOP-2984] - Remove unused variables from Libraries
<li>[REDSHOP-2998] - Refactor error handling in admin
</ul>

<hr>

<h4 id="modulesReleased">Modules Released in 2.0</h4>

<ul>
<li>site/mod_redcategoryscroller
<li>site/mod_redfeaturedproduct
<li>site/mod_redmanufacturer
<li>site/mod_redproducts3d
<li>site/mod_redproductscroller
<li>site/mod_redproducttab
<li>site/mod_redshop_cart
<li>site/mod_redshop_categories
<li>site/mod_redshop_category_scroller
<li>site/mod_redshop_currencies
<li>site/mod_redshop_discount
<li>site/mod_redshop_logingreeting
<li>site/mod_redshop_productcompare
<li>site/mod_redshop_products
<li>site/mod_redshop_products_slideshow
<li>site/mod_redshop_promote_free_shipping
<li>site/mod_redshop_search
<li>site/mod_redshop_shoppergroup_category
<li>site/mod_redshop_shoppergrouplogo
<li>site/mod_redshop_shoppergroup_product
<li>site/mod_redshop_who_bought
<li>site/mod_redshop_wishlist
</ul>

<hr>

<h4 id="pluginsReleased">Plugins Released in 2.0</h4>

##### Acymailing
<ul>
<li>redshop
</ul>

##### Ajax
<ul>
<li>xmlcron
</ul>

##### Content
<ul>
<li>redshop_product
</ul>

##### E-conomic
<ul>
<li>economic
</ul>

##### Alert
<ul>
<li>alert
</ul>

##### Payment Gateway
<ul>
<li>baokim
<li>cielo
<li>dibsdx
<li>ingenico
<li>klarna
<li>mollieideal
<li>nganluong
<li>paygate
<li>paypalcreditcard
<li>payson
<li>quickbook
<li>rs_payment_2checkout
<li>rs_payment_amazoncheckout
<li>rs_payment_authorize
<li>rs_payment_authorize_dpm
<li>rs_payment_banktransfer
<li>rs_payment_banktransfer2
<li>rs_payment_banktransfer_discount
<li>rs_payment_beanstream
<li>rs_payment_braintree
<li>rs_payment_dibspaymentmethod
<li>rs_payment_eantransfer
<li>rs_payment_epayv2
<li>rs_payment_eway
<li>rs_payment_eway3dsecure
<li>rs_payment_giropay
<li>rs_payment_googlecheckout
<li>rs_payment_imglobal
<li>rs_payment_ingenico
<li>rs_payment_moneris
<li>rs_payment_payflowpro
<li>rs_payment_payment_express
<li>rs_payment_paymill
<li>rs_payment_paypal
<li>rs_payment_postfinance
<li>rs_payment_rapid_eway
<li>rs_payment_sagepay_vps
<li>rs_payment_worldpay
<li>stripe
</ul>

##### Products

<ul>
<li>canonical
<li>CreateColorImage
<li>invoicepdf
<li>postdanmark
<li>shoppergroup_tags
<li>stock_notifyemail
<li>stockroom_status
</ul>

##### Shipping Gateway

<ul>
<li>bring
<li>default_shipping
<li>default_shipping_gls
<li>default_shipping_glsbusiness
<li>fedex
<li>postdanmark
<li>self_pickup
<li>shipper
<li>ups
<li>uspsv4
</ul>

##### Search

<ul>
<li>redshop_categories
<li>redshop_products
</ul>

##### System

<ul>
<li>redgoogleanalytics
<li>redshop
</ul>

<hr>

<h6>Last updated on September 14, 2016