## redSHOP 1.5.0.6
### redSHOP 1.5.0.6 released for Joomla 3 (2015-12-22)
redCOMPONENT is pleased to announce the immediate availability of redSHOP 1.5.0.6

<hr>

### Maintenance Release

In redSHOP 1.5.0.6 is a release focused on adding compatibility with Joomla 3.x to several Joomla 2.5 redSHOP extensions. All the redSHOP extensions have been prepared for Joomla 3 with the exception of the following payment plugins that are currently in beta status: Dotpay, Giropay, Google Checkout, Img Global, Post Finnance and Cielo. We are working on release them in the next release of redSHOP.

Several minor bugs have been solved.

We recommend all our users to update.

<hr>

### Install and UPDATE instructions
To install or update redSHOP check the instructions page: [Updating redSHOP](chapters/getting-started-general/updating-redshop.md)

<hr>

### Release Notes - redSHOP - Version 1.5.0.6

<ul>
<li><a href="#bugs">Bugs</a>
<li><a href="#stories">Stories</a>
<li><a href="#newFeature">New Feature</a>
<li><a href="#improvements">Improvements</a>
<li><a href="#extensions">Extensions: Modules and Plugins</a>
<li><a href="#qaImprovements">Quality Assurance Improvements</a>
</ul>

<hr>

<h4 id="bugs">Bugs</h4>

<ul>
<li>[REDSHOP-1899] - Download product - possible to change TokenID and still download file.
<li>[REDSHOP-2079] - Renew e-conomic invoice when updating order
<li>[REDSHOP-2105] - redSHOP ignoring style settings on one-step checkout
<li>[REDSHOP-2109] - css in input fields is too wide
<li>[REDSHOP-2122] - Checkout information not showing after flooding with payment method requests
<li>[REDSHOP-2135] - Discount displayed values don't reflect actual values in checkout
<li>[REDSHOP-2137] - Discount calculator not displaying the resulting price
<li>[REDSHOP-2149] - Option "New user pre-selected" not working in checkout view
<li>[REDSHOP-2160] - Category getting indentation wrong and repeating categories
<li>[REDSHOP-2194] - notice: undefined offset. in order details
<li>[REDSHOP-2229] - Error in redshop after upgrading Joomla from 2.5 to 3.4
<li>[REDSHOP-2238] - Move label and description of extrafieldshipping and extrafieldpayment to INI
<li>[REDSHOP-2240] - Cant add attributes to cart when main product stock is zero
<li>[REDSHOP-2259] - Forms still include a "state" field even if default country doesn't have states
<li>[REDSHOP-2260] - Customer invoice email is completely blank
<li>[REDSHOP-2379] - Missing language string in global configuration.
<li>[REDSHOP-2389] - Postdanmark button doesn't show up in checkout view
<li>[REDSHOP-2390] - Notice: Undefined property: PlgRedshop_ShippingDefault_Shipping_GLSBusiness::$classname
<li>[REDSHOP-2395] - When clicking on "save+pay" in the backend, not redirect to any template.
<li>[REDSHOP-2396] - stockroomlisting view doesn't show product name in attribute section
<li>[REDSHOP-2399] - issue in stock listing
<li>[REDSHOP-2404] - Add missing string in Stockroom list
<li>[REDSHOP-2405] - Redshop uses by default Invoice PDF file template in email body
<li>[REDSHOP-2406] - redSHOP Orders resend invoice fails
<li>[REDSHOP-2407] - Error saving every shipping Method.
<li>[REDSHOP-2464] - missing right descriptions in product list
<li>[REDSHOP-2467] - PerPageLimit tag not working
<li>[REDSHOP-2472] - {property_image_without_scroller} and similar tags not working if attribute dropdown is not included
<li>[REDSHOP-2475] - Button "save" from additional file for download not working.
<li>[REDSHOP-2483] - Add to cart not working product content plugin
<li>[REDSHOP-2487] - Pagination not working if {product_price_slider} tag is present
<li>[REDSHOP-2488] - Notice: Use of undefined constant... in global configuration.
<li>[REDSHOP-2490] - undefined constant INVOICE_NUMBER_
<li>[REDSHOP-2500] - gift cards form don't show the selected images
<li>[REDSHOP-2501] - Undefined index: vatCountry
<li>[REDSHOP-2502] - Notice in orders view
<li>[REDSHOP-2503] - search box not working in mail management
<li>[REDSHOP-2515] - does not allow adding additional files to download, in a new installation of redshop
<li>[REDSHOP-2518] - Invoice PDF not looking good
<li>[REDSHOP-2521] - Non-image files cannot be added from media_detail view
<li>[REDSHOP-2525] - Product and mass discounts not behaving properly
<li>[REDSHOP-2536] - Notice: Undefined index in checkout
<li>[REDSHOP-2537] - When i click "Add to cart", it not show pop-up "Add to card"
<li>[REDSHOP-2545] - Can't switch editor in Joomla 2.5
<li>[REDSHOP-2551] - Canada postal code doesn't respect spaces, breaking code range settings
<li>[REDSHOP-2553] - remove Google Base from the menu
<li>[REDSHOP-2559] - MAC verification not working J2.5 for the DIBS DX payment plugin
<li>[REDSHOP-2567] - DIBS languages not working
<li>[REDSHOP-2577] - File Upload Permissions
<li>[REDSHOP-2589] - Error in the lateral menu of redshop.
<li>[REDSHOP-2604] - Notices in backend and cart view
<li>[REDSHOP-2605] - CSV export/import issue
<li>[REDSHOP-2619] - Issue when adding to cart related to system message
<li>[REDSHOP-2699] - Problem Sending discount order mail | after purchased order mail
<li>[REDSHOP-2700] - Missing product number in ajax search
<li>[REDSHOP-2702] - multiple attributes checkbox don’t appear
<li>[REDSHOP-2705] - Review in backend don´t save username and useremail
<li>[REDSHOP-2706] - After sending a review it redirects the iframe not parent
<li>[REDSHOP-2707] - Issue with multiple stockrooms
<li>[REDSHOP-2711] - Image not loading in media upload 'image' button view
<li>[REDSHOP-2714] - Exporting XML files, on PHP 5.5.x and above returns a deprecated error
<li>[REDSHOP-2721] - Undefined index: AccessoryAsProduct
<li>[REDSHOP-2035] - Ordering of categories - there is no save button
<li>[REDSHOP-2408] - redshop backend create discount code can't remove user again
<li>[REDSHOP-2528] - when modify a quotation it always sends mails
<li>[REDSHOP-2662] - Issue in quotation system
<li>[REDSHOP-2560] - Fix select product issue in single product menu item
<li>[REDSHOP-2585] - dashboard default selected issue in configuration
<li>[REDSHOP-2466] - Order by tag not working
<li>[REDSHOP-2523] - errors resending invoice
<li>[REDSHOP-2538] - PHP Notice in DIBS Payment Gateway - D2 Platform
<li>[REDSHOP-2582] - Fixed fatal error when open configuration view in joomla 2.5
<li>[REDSHOP-2725] - Configuration tab default selected issue with browser storage
</ul>

<hr>

<h4 id="stories">Stories</h4>

<ul>
<li>[REDSHOP-1878] - test redSHOP 1.5 compatibility on shipping plugins
<li>[REDSHOP-2013] - track compatibility with redSHOP core
<li>[REDSHOP-2045] - possible error with mass disccounts version 2
<li>[REDSHOP-2235] - Create packages for features that need more than one plugin
<li>[REDSHOP-2251] - Add shopper group ID to PayGate payment plugin
<li>[REDSHOP-2252] - Add shopper group ID to Payson payment plugin
<li>[REDSHOP-2312] - move all modules and special plugins to redSHOP 1.5
<li>[REDSHOP-2321] - Fix a new non performant steps in ManageStates at Joomla 3.x
<li>[REDSHOP-2393] - AustraliaPost sandbox missing user details
<li>[REDSHOP-2398] - add refund system in moneybookers
<li>[REDSHOP-2402] - Make labels for radio buttons in attribute view
<li>[REDSHOP-2411] - Improve postDanmark plugin description
<li>[REDSHOP-2474] - create release notes for 1.5.0.6 release
<li>[REDSHOP-2489] - issue when updating from redSHOP 1.2 sites
<li>[REDSHOP-2498] - Relatively path images in some template mails not turned to absolute, when mail send.
<li>[REDSHOP-2522] - Updating coupon query to use JDatabase format
<li>[REDSHOP-2526] - Update mollie payment plugin API
<li>[REDSHOP-2552] - Use different back transfer plugins
<li>[REDSHOP-2556] - Add language string support for shipping name in economic
<li>[REDSHOP-2557] - Not possible to choose single product from product menu item type
<li>[REDSHOP-2558] - Fix translation for required notice
<li>[REDSHOP-2561] - increase build performance with Composer
<li>[REDSHOP-2564] - Adding support to change order item image using plugin
<li>[REDSHOP-2565] - quotation creation issues
<li>[REDSHOP-2571] - Using Dynamic js validation error message for extrafield
<li>[REDSHOP-2573] - Update JoomlaBrowser to remove installation folder
<li>[REDSHOP-2575] - e-conomic Payment Gateways Merchant Fee handling for credit entry
<li>[REDSHOP-2578] - wrong calls too setError
<li>[REDSHOP-2583] - Unable add to cart with sef url
<li>[REDSHOP-2590] - Fix quotation_subtotal_excl_vat tag in quotation email not exclude tax
<li>[REDSHOP-2606] - message should have error type
<li>[REDSHOP-2607] - discounts in cart are not showed
<li>[REDSHOP-2632] - Using link in product name for acymailing template
<li>[REDSHOP-2634] - issue with window.onload using jquery
<li>[REDSHOP-2637] - Support PDF libraries, which use namespaces
<li>[REDSHOP-2654] - Adding unique index order_id for order payment
<li>[REDSHOP-2666] - Allow extrafield showing in backend
<li>[REDSHOP-2674] - Cant copy products from product view
<li>[REDSHOP-2680] - Wrong redSHOP newest product
<li>[REDSHOP-2688] - Export Quotation - Get wrong user information.
<li>[REDSHOP-2689] - Can't rearrange product order
<li>[REDSHOP-2692] - Updating new API url for e-conomic
<li>[REDSHOP-2717] - Fix required attribute issue
</ul>

<hr>

<h4 id="newFeature">New Feature</h4>

<ul>
<li>[REDSHOP-2157] - new tag {invoice_number} allowing to configure the invoice number in orders
</ul>

<hr>

<h4 id="improvements">Improvements</h4>

<ul>
<li>[REDSHOP-2148] - Having "shipping same as billing" checkbox checked by default
<li>[REDSHOP-2306] - Form fields break outside container
<li>[REDSHOP-2307] - Include loading gif to Eway plugin waiting screen
<li>[REDSHOP-2310] - Make fields when registering to be customizable (required or not)
<li>[REDSHOP-2349] - Improve frontend Error/Warning/Notice frontend Checker
<li>[REDSHOP-2382] - Adding constants in extrafield class
<li>[REDSHOP-2403] - rename afterUpdateStock event
<li>[REDSHOP-2479] - Invoice not booked message should be warning (e-conomic)
<li>[REDSHOP-2657] - Choosing dynamic helper for pdf library
<li>[REDSHOP-2622] - Adding from-to date filter in order listing backend
</ul>

<hr>

<h4 id="extensions">Extensions: Modules and Plugins</h4>

<ul>
<li>[REDSHOP-2413] - release mod_redshop_currencies.xml for redSHOP 1.5.0.6
<li>[REDSHOP-2414] - release mod_redshop_pricefilter.xml for redSHOP 1.5.0.6
<li>[REDSHOP-2416] - release mod_redproducts3d for redSHOP 1.5.0.6
<li>[REDSHOP-2417] - release mod_redproducttab for redSHOP 1.5.0.6
<li>[REDSHOP-2418] - release mod_redshop_products for redSHOP 1.5.0.6
<li>[REDSHOP-2419] - release mod_redshop_products_slideshow for redSHOP 1.5.0.6
<li>[REDSHOP-2420] - release mod_redshop_search for redSHOP 1.5.0.6
<li>[REDSHOP-2421] - release mod_redshop_who_bought for redSHOP 1.5.0.6
<li>[REDSHOP-2423] - release plugins/redshop_payment/quickbook for redSHOP 1.5.0.6
<li>[REDSHOP-2424] - release plugins/redshop_payment/rs_payment_2checkout for redSHOP 1.5.0.6
<li>[REDSHOP-2425] - release plugins/redshop_payment/rs_payment_amazoncheckout for redSHOP 1.5.0.6
<li>[REDSHOP-2426] - release plugins/redshop_payment/rs_payment_authorize for redSHOP 1.5.0.6
<li>[REDSHOP-2427] - release plugins/redshop_payment/rs_payment_authorize_dpm for redSHOP 1.5.0.6
<li>[REDSHOP-2428] - release plugins/redshop_payment/rs_payment_banktransfer for redSHOP 1.5.0.6
<li>[REDSHOP-2429] - release plugins/redshop_payment/rs_payment_banktransfer_discount for redSHOP 1.5.0.6
<li>[REDSHOP-2430] - release plugins/redshop_payment/rs_payment_beanstream for redSHOP 1.5.0.6
<li>[REDSHOP-2431] - release plugins/redshop_payment/rs_payment_braintree for redSHOP 1.5.0.6
<li>[REDSHOP-2432] - release plugins/redshop_payment/rs_payment_ceilo for redSHOP 1.5.0.6
<li>[REDSHOP-2433] - release plugins/redshop_payment/rs_payment_dibspaymentmethod for redSHOP 1.5.0.6
<li>[REDSHOP-2434] - release plugins/redshop_payment/rs_payment_dibsv2 for redSHOP 1.5.0.6
<li>[REDSHOP-2435] - release plugins/redshop_payment/rs_payment_dotpay for redSHOP 1.5.0.6
<li>[REDSHOP-2436] - release plugins/redshop_payment/rs_payment_eantransfer for redSHOP 1.5.0.6
<li>[REDSHOP-2437] - release plugins/redshop_payment/rs_payment_epayv2 for redSHOP 1.5.0.6
<li>[REDSHOP-2438] - release plugins/redshop_payment/rs_payment_eway for redSHOP 1.5.0.6
<li>[REDSHOP-2439] - release plugins/redshop_payment/rs_payment_eway3dsecure for redSHOP 1.5.0.6
<li>[REDSHOP-2440] - release plugins/redshop_payment/rs_payment_giropay for redSHOP 1.5.0.6
<li>[REDSHOP-2441] - release plugins/redshop_payment/rs_payment_googlecheckout for redSHOP 1.5.0.6
<li>[REDSHOP-2442] - release plugins/redshop_payment/rs_payment_imglobal for redSHOP 1.5.0.6
<li>[REDSHOP-2443] - release plugins/redshop_payment/rs_payment_ingenico for redSHOP 1.5.0.6
<li>[REDSHOP-2444] - release plugins/redshop_payment/rs_payment_mollieideal for redSHOP 1.5.0.6
<li>[REDSHOP-2445] - release plugins/redshop_payment/rs_payment_moneris for redSHOP 1.5.0.6
<li>[REDSHOP-2446] - release plugins/redshop_payment/rs_payment_moneybooker for redSHOP 1.5.0.6
<li>[REDSHOP-2447] - release plugins/redshop_payment/rs_payment_payflowpro/ for redSHOP 1.5.0.6
<li>[REDSHOP-2448] - release plugins/redshop_payment/rs_payment_paygate for redSHOP 1.5.0.6
<li>[REDSHOP-2449] - release plugins/redshop_payment/rs_payment_payment_express for redSHOP 1.5.0.6
<li>[REDSHOP-2450] - release plugins/redshop_payment/rs_payment_paymill/ for redSHOP 1.5.0.6
<li>[REDSHOP-2451] - release plugins/redshop_payment/rs_payment_paypal for redSHOP 1.5.0.6
<li>[REDSHOP-2452] - release plugins/redshop_payment/rs_payment_paypalpro for redSHOP 1.5.0.6
<li>[REDSHOP-2453] - release plugins/redshop_payment/rs_payment_payson for redSHOP 1.5.0.6
<li>[REDSHOP-2454] - release plugins/redshop_payment/rs_payment_postfinance for redSHOP 1.5.0.6
<li>[REDSHOP-2455] - release plugins/redshop_payment/rs_payment_quickpay for redSHOP 1.5.0.6
<li>[REDSHOP-2456] - release plugins/redshop_payment/rs_payment_rapid_eway for redSHOP 1.5.0.6
<li>[REDSHOP-2457] - release plugins/redshop_payment/rs_payment_sagepay for redSHOP 1.5.0.6
<li>[REDSHOP-2458] - release plugins/redshop_payment/rs_payment_sagepay_vps for redSHOP 1.5.0.6
<li>[REDSHOP-2459] - release plugins/redshop_payment/rs_payment_worldpay for redSHOP 1.5.0.6
<li>[REDSHOP-2460] - release plugins/redshop_shipping/default_shipping for redSHOP 1.5.0.6
<li>[REDSHOP-2461] - release plugins/redshop_shipping/default_shipping_gls for redSHOP 1.5.0.6
<li>[REDSHOP-2462] - release plugins/redshop_shipping/default_shipping_glsbusiness for redSHOP 1.5.0.6
<li>[REDSHOP-2463] - release plugins/redshop_shipping/ups for redSHOP 1.5.0.6
<li>[REDSHOP-2057] - update the Amazon plugin tag to make it ready for redSHOP 1.5
<li>[REDSHOP-2179] - Test Custom Fedex Shipping plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2181] - Test GLS-Default Shipping plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2183] - Test USPS plugin Shipping plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2191] - Test GLS-bussines Shipping plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2192] - Test Custom GLS Shipping plugin
<li>[REDSHOP-2200] - Test mod_redshop_productcompare
<li>[REDSHOP-2275] - Test banktransfer payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2276] - Test banktransfer_discount payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2280] - Test eway3dsecure payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2286] - Test RapidEway payment plugin and add compatibility with redSHOP 1.5
<li>[REDSHOP-2311] - Discontinue Dibs payment plugin
<li>[REDSHOP-2326] - Test ERPImportExport/ERPImportExport_specialemail_1.0. and add compatibility with redSHOP 1.5
<li>[REDSHOP-2327] - Test Plugin economic_economic and add compatibility with redSHOP 1.5
<li>[REDSHOP-2328] - Test Plugin highrisehq.com and add compatibility with redSHOP 1.5
<li>[REDSHOP-2332] - Test Plugin /redshop_product/redshop_product_mergeimage and add compatibility with redSHOP 1.5
<li>[REDSHOP-2339] - Test Plugin /redshop_veis_registration/rs_veis_registration and add compatibility with redSHOP 1.5
<li>[REDSHOP-2342] - Release /system_quickbook_1.0
<li>[REDSHOP-2347] - Refactor Plugin /xmap/xmap_com_redshop and add compatibility with redSHOP 1.5
<li>[REDSHOP-2524] - Update mollideal plugin to redSHOP 1.5
<li>[REDSHOP-2120] - Allow the Paypal plugin to add the transaction fee to the order
<li>[REDSHOP-2620] - rs_payment_epayv2 Order receipt get wrong itemid
<li>[REDSHOP-2630] - Paypal integration changes
<li>[REDSHOP-2547] - The plugin for joomla smart search to preindex products from redSHOP to speed up the search function
<li>[REDSHOP-2401] - Review Authorize DPM plugin
<li>[REDSHOP-1913] - Fix Payment plugin EWAY UK does not work
<li>[REDSHOP-2058] - Paypal dividing shipping in all purchased objects
<li>[REDSHOP-2082] - Quickpay sends order id
<li>[REDSHOP-2477] - Standard Shipping danish language string has wrong value
<li>[REDSHOP-2482] - epay plugin complaining about currency if only Dankort and eDankort is selected
<li>[REDSHOP-2484] - Sagepay protocols updating to v3 July 31, Plugin needs to be updated?
<li>[REDSHOP-2225] - Add new options for epay cardtypes
<li>[REDSHOP-2325] - Pacsoft doesnt create a label for this shipping method
<li>[REDSHOP-2381] - Bug when plugin invoicepdf disable - still able to use button relate with plugin
<li>[REDSHOP-2665] - PayPal Payments Pro plugin not authorizing properly
</ul>

<hr>

<h4 id="qaImprovements">Quality Assurance Improvements</h4>

<ul>
<li>[REDSHOP-2410] - Add robo.li support
<li>[REDSHOP-2357] - check if Warning/Notice/Error checker can be done with seeInSource
<li>[REDSHOP-2562] - check why a error in codecept do not cause a fail in travis
<li>[REDSHOP-2581] - Bug in 2checkout test
<li>[REDSHOP-2658] - fix test ProductsCheckoutAuthorizeDPMCest::testAuthorizeDPMPaymentPlugin
<li>[REDSHOP-2480] - improve locators in current tests
<li>[REDSHOP-2544] - tests: avoid duplicate transactions
<li>[REDSHOP-2576] - create test for banktransfer2
<li>[REDSHOP-2593] - review paypal plugin false positives
<li>[REDSHOP-2668] - Braintree test is outside checkout folder
<li>[REDSHOP-2409] - Add JoomlaBrowser support to current tests
<li>[REDSHOP-2670] - Fix php version in TravisCI tests (now 5.3)
<li>[REDSHOP-2588] - Add TestStatistics extension
<li>[REDSHOP-2708] - Avoid false positive when filling category filter
</ul>

<hr>

<h6>Last updated on January 13, 2016</h6>