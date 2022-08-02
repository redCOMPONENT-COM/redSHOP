## redSHOP 2.1.3
redCOMPONENT is pleased to announce the immediate availability of redSHOP 2.1.3 This is a maintenance release with bug fixes, refactoring and improvements.

<b>Important note: redSHOP 2.x is only compatible with Joomla 3.x.</b> If your site is still in Joomla 2.5 or early note that your version is not any more maintained and update is recommended.

<hr>

### Install and UPDATE instructions
To install or update redSHOP check the instructions page: [Updating redSHOP](chapters/getting-started-general/updating-redshop.md)

<hr>

### Changelog - redSHOP - Version 2.1.3

<ul>
<li>KON-981 Implement replacement for special discount (label + price tag) in order receipt page, order mail, print</li>
<li>Fix wrong product's sef url on order detail/ receipt, order mail</li>
<li>Refactor product search functionality (module &amp; component)</li>
<li>Fix attribute icon image is hidden in product attribute layout in backend</li>
<li>KON-1038 Login redirects to missing page</li>
<li>REDCOMSITE-451 Fix save customfield with type is editor</li>
<li>REDSHOP-5603 Create automated test for GLS Business Shipping</li>
<li>REDSHOP-5632 Newsletter management (backend) - reset button does not reset product &amp; category select list (past selected items still exist after clicking on reset))</li>
<li>REDSHOP-5559 [Module] - redSHOP - Who Bought</li>
<li>REDSHOP-5630 Custom field - Create new field type 'Joomla Articles Related'</li>
<li>REDSHOP-5569 Modules - ShopperGroup Product</li>
<li>REDSHOP-5626 After editing and save billing, shipping address via modal: need fixing bug and improvement</li>
<li>REDSHOP-5629 Update and run code for OderBackendWithForeignCountryCest.php file</li>
<li>REDSHOP-5628 make correction to function getAjaxDetailBox</li>
<li>[Economic] Migrate some bug fixes (economic) for Kontormoebler project to Redshop core</li>
<li>REDSHOP-4266 Missing {order_tax} and {order_discount} in default template of order mail</li>
<li>REDSHOP-4339 Fix filter newsletter show error</li>
<li>HOD-257, OTMC-188 : [Order Receipt] - Page Title (shown on browser tab) should make sense with the context</li>
<li>REDSHOP-5619 Custom fields which having type radio, checkbox, single select, multi-select show value instead of name on frontend</li>
<li>REDSHOP-5550 Create/Edit order in admin - State Name select list does not display on country selection</li>
<li>REDSHOP-4661: Configuration - Feature setting -Wishlist: should hide 2 fields below it if we click "No" on "Enable Wishlist"</li>
<li>REDSHOP-5615 Print Multiple Order need to be one page per order</li>
<li>REDSHOP-5612 Sub property image should show the default image on front page when no image added</li>
<li>REDSHOP-5422: order detail admin - Select field of shipping method rate list displays text mixed with html tag</li>
<li>REDSHOP-5609 Print Multi Order - show error</li>
<li>REDSHOP-4051 Stock Image Management (backend) - Can not accept when value of Stock Quantity is negative number</li>
<li>YI-144 Add wishlist - Reload page more time after save name wishlist</li>
<li>REDSHOP-5614 [Fields for Private Shipping Address/Company Shipping Address ] - Not work when change Private Person /Business</li>
<li>YI-147 add trigger event for wishlist product</li>
<li>YI-113 Redirect Home page after login success</li>
<li>REDSHOP-5607 Product Attributes layout in backend - Fixing some bugs after applying new layout</li>
<li>REDSHOP-5608 Support PR fail drone</li>
<li>REDSHOP-5601 Automation for module shipping Default GLS</li>
<li>HOD-205: Export Order CSV missing Requisition Number note</li>
<li>REDSHOP-5598 : Backend Product list - Filter product incorrect due to wrong query</li>
<li>REDSHOP-5596: Wrong menu item id when access product page from "Preview" button in backend</li>
<li>REDSHOP-2628 Test for SKRILL (MONEY BOOKER) Payment Plugin</li>
<li>REDSHOP-5591 Add tag {relproduct_rating_summary} for template relate product</li>
<li>REDSHOP-2086 Build quick tests for menu items</li>
<li>REDSHOP-5586 Refactor manufacturer</li>
<li>REDSHOP-5567: Add product to Wishlist - wrong param getting code</li>
<li>REDCOMSITE-394 Fix selector users_info_id not work when no shipping address</li>
<li>REDSHOP-5579: Can not get the custom field when checking out the product (typo mistake)</li>
<li>REDSHOP-5578 Manufacturer list view (backend) - improve the display of column Description</li>
<li>REDSHOP-5572: RedMassCart show error when user click add to cart</li>
<li>REDSHOP-5574 Hard code strings in Add order detail</li>
<li>OTMC-151 add params for event onAfterSubmitAjaxCartdetail</li>
<li>Add to cart from html replacing - wrong html markup</li>
<li>PALMARINE-1852 Fix add cart have accessory page search</li>
<li>REDCOMSITE-373 Fix checksum paypal</li>
<li>KOL-98: Billing/shipping js validation - validation on retype email (email2) does not work</li>
<li>REDSHOP-5121 Update codeception for Shipping Rate</li>
<li>REDSHOP-5555 Module Multi Currencies</li>
<li>REDSHOP-2788 VAT is changed depending on the user</li>
<li>KON-909 Cancel quickpay payment not working</li>
<li>OTMC-104 Plus/ minus icon on cart items - extend shortcode replacement for custom view other than cart</li>
<li>Automation : Run stable redSHOP</li>
<li>SWD-128: Voucher/Coupon working incorrect because of twice submit</li>
<li>SWD-129 Add plugin trigger event when replacing cart template</li>
<li>REDSHOP-5518 Order detail show wrong price total after update Special discount and Discount</li>
<li>SDM-127: Apply coupon/voucher on cart page - multi language does not work</li>
<li>REDSHOP-5542 Fix display module cart when delete item cart</li>
<li>REDSHOP-5476: Move export Full Order CSV to Import/Export REDSHOP</li>
<li>REDSHOP-5406 Import Shopper group product price, Shopper group attribute price - Not show date time</li>
<li>REDSHOP-5530 Fix save as copy product not work correctly in some scenarios</li>
<li>Bug fix: Configuration input -&gt; wrong input name &amp; id of 'attribute_scroller_thumb_height'</li>
<li>REDSHOP-4836 Refactor - edit inline need check value empty</li>
<li>SDM-118 Fix install translations 'redshop_category' in redcore</li>
<li>REDSHOP-5274 "Lightbox" option for product image does not work on frontend</li>
<li>[Automation] remove check Image for category and product</li>
<li>REDSHOP-5531 Run OrderBackendProductAttributeCest</li>
<li>PALMARINE-1479 replace cart template code -&gt; wrong html markup in some place</li>
<li>REDSHOP-5529 Installation problem: Class 'Redshop' not found</li>
<li>REDSHOP-5528 show irrelevant message color when cancel payment</li>
<li>DREAM-85 add product to cart on category view - product still be added even if it has required attributes</li>
<li>REDSHOP-4641 Orders Settings Configuration</li>
<li>PALMARINE-1771 Remove sort by media name at product list view (backend)</li>
<li>REDSHOP-5248 VAT bad case - follow task REDSHOP-5133 and update for codeception</li>
<li>REDSHOP-5521 Fix fail random on server many times</li>
<li>REDSHOP-4505 Coupon Detail - Can not accept when start date &gt; end date</li>
<li>REDSHOP-5502 Verify when user clicks on " Two - way related product" is "No" and "Yes"</li>
<li>REDSHOP-5501 "Enable Accessory as individual product in cart" is "Yes" and "No"</li>
<li>REDSHOP-5454 Codeception - Add product price in product detail</li>
<li>REDSHOP-1767 Create integration test for products and categories (frontend-backend) with upload image and SEO</li>
<li>REDSHOP-5513 Issue import/export product and attribute</li>
<li>REDSHOP-5473 Create Order - Show message should clearly when user click on 'Save &amp; Close' button</li>
<li>REDSHOP-5466 Template management - Create new template -&gt; error message is not clear when "template_section" is not assigned</li>
<li>REDSHOP-5506 REDSHOP- VAT in front-end of Sub-Attributes is not correct</li>
<li>REDSHOP-5511 Website redirect to the home page instead of the payment window after checkout</li>
<li>REDSHOP-4989 Codeception for Discount on product price page for sub category REDSHOP-4987</li>
<li>REDSHOP-2635 Test for Bank Transfer Discount Payments for redSHOP</li>
<li>REDSHOP-4915 Codeception - Wrapping - check invalid field for price</li>
<li>REDSHOP-5509 Codeception Fix fail setup</li>
<li>REDSHOP-5424 Enable all export/import plugins by default (when install/upgrade redshop)</li>
<li>REDSHOP-5495 Category Accessories can not be saved when clicking on button</li>
<li>KON-843 VAT/Tax error when creating order backend with foreign country</li>
<li>PALMARINE-1616 Statistic - Customer, Product, Order - Charts do not display</li>
<li>REDSHOP-4446 Samples Management - Left sidebar looks broken &amp; sample detail can be saved successfully even with empty fields value provided</li>
<li>REDSHOP-5470 Backend Product Prices - Quantity product can input negative numbers</li>
<li>REDSHOP-5500 User clicks on " Display Out Of Stock Attribute Data" is "Yes" and "No"</li>
<li>REDSHOP-5497 Custom field value (multi-checkbox) does not display on product page (frontend)</li>
<li>REDSHOP-5498 Discount calculator does not work in frontend</li>
<li>REDSHOP-5485 Browser Chrome/Egde - 'Save as Copy' button duplicate product after user click on it</li>
<li>REDSHOP-2533 Create Tests for Payment Plugin Authorize DPM</li>
<li>REDSHOP-5484 Order detail/receipt - print button result in displaying urls on print window pop-up</li>
<li>REDSHOP-5481 Product Custom field (multiselect checkbox) - field values not showing correctly after assigning values</li>
<li>REDSHOP-2128 2 Checkout Payment Plugin Tests</li>
<li>PALMARINE-1278 Fix page not found issue</li>
<li>REDSHOP-5120 Some front pages get php notice</li>
<li>REDSHOP-5475 Category's image is not copied when copying 1 category item (button "Copy" at category list backend))</li>
<li>REDSHOP-5181 Check with phone number is string</li>
<li>KON-429 &amp; KON-831 fix problem with accessory attributes required when adding product into order (backend))</li>
<li>REDSHOP-5465 Custom Field - Lost options after click Save button (type "checkbox")</li>
<li>REDSHOP-5469 Product price - Show message when user fill in correct quantity product</li>
<li>REDSHOP-5468 Make system test running faster and stable</li>
<li>KON-827 Product detail backend - change some inputs type to number and fix validation on "Maximum quantity per order"</li>
<li>DREAM-39 Product's link on cart page should have correct Itemid</li>
<li>REDSHOP-5072 Codeception - One page checkout with missing data</li>
<li>REDSHOP-5464: Product detail - Show 5 product on first page and bug redSHOP</li>
<li>KON-821 Copy product - default main image not display &amp; missing ordering of product images (on new product)</li>
<li>REDSHOP-5361 Codeception - Support checkout VAT and attribute</li>
<li>REDSHOP-5456 Automation - check general - make sure the project run stable on server</li>
<li>REDSHOP-5459 Import csv - the progress bar (in percent) show incorrectly</li>
<li>REDSHOP-5458 Check with 3.9.6 joomla version</li>
<li>KON-810 Economic error when storeDebtor</li>
<li>PALMARINE-1531: Stock status in wrong frontend</li>
<li>REDSHOP-5155 Create trigger event for 3rd view in sh404sef</li>
<li>KON-805 Error on manufacturer page (frontend) due to wrong query</li>
<li>KON-804 Edit shipping or billing address in order detail backend - save button not take effect on click</li>
<li>PALMARINE-1484: The name of accessory products does not display on front page</li>
<li>DREAM-6 Implement the 'register by email to receive notification email when product is in stock again'</li>
<li>REDSHOP-5445 Discount Product - Multi select list of categories not show subcategories</li>
<li>REDSHOP-5440 Copy product - Lost product attribute's images and non-synced published statuses</li>
<li>REDSHOP-5434: Delete product apply voucher, price in cart incorrect.</li>
<li>REDSHOP-5436 Date time field - error "Failed to parse time string" when saving discount (happened with many date time redshop format)</li>
<li>REDSHOP-5427 Order total discount -&gt; wrong parsing html on front-end for discount amount (money value + currency display)</li>
<li>PALMARINE-1420 Optimize query in "getStockAmountImage" by changing to inner join, much better in performance</li>
<li>REDSHOP-5426 Product image do not show in product detail page in backend</li>
<li>REDSHOP-4541 Front End page - View Product - The price of product is changed</li>
<li>REDSHOP-4628 Required attribute should be validate at server side</li>
<li>REDSHOP-4171 Check image exists</li>
<li>REDSHOP-4482 Mass discount edit (backend) - insert asterisk * for the label field "Products" (mandatory field)</li>
<li>REDSHOP-4486 Mas Discount - Error messages are somewhat confusing</li>
<li>REDSHOP-4333 Add Attribute Price - Missing language when add new attribute price</li>
<li>REDSHOP-4883 Stockroom management (backend) - add JS validation for min, max delivery time</li>
<li>REDSHOP-4531 Gift Card Management - Edit form - Fix blank form displays when click on Image Review</li>
<li>REDSHOP-4494 Gift Card Management - Delete Gift Card need show popup to commit action delete WIP</li>
<li>REDSHOP-4084 Templates - '{attribute_price_without_vat}' tag working incorrect</li>
<li>REDSHOP-4517 Add option to chose between bar chart or line chart - standard should be bar chart</li>
<li>REDSHOP-5400: php 7.3 - remove php notice on view product on frontend REDSHOP-5400</li>
<li>REDSHOP-5345: Admin Product Attribute - fix failure when adding Product Attribute, fix PHP notice</li>
<li>REDSHOP-5383: fix php notice on view orders - frontend</li>
<li>REDSHOP-4243: The button "add to compare" and compared product links still displays on product page, even though 'compare product' feature is disabled in redshop config (Feature setting)</li>
<li>REDSHOP-5373 Improvement the orderding function on Adnmin Product List. Same on Category view</li>
<li>REDSHOP-4537 Create new user in redshop admin - missing validation for password input field</li>
<li>REDSHOP-4885: Currency - should not allow to delete a currency which is already set as default currency in redshop configuration</li>
<li>REDSHOP-5371: Change background color for error message when user click on "Send Download mail" button in order backend</li>
<li>Revert "REDSHOP-4672 Shipping methods management - Remove checkbox column"</li>
<li>REDSHOP-4593 Import and Export Attribute - Need import attribute value and property</li>
<li>REDSHOP-5013 Statistic "Newest Products" - Improve display when filtering calendar field</li>
<li>REDSHOP-4492: Configuration - Cannot accept any rule discount for product when Discount is " No"</li>
<li>REDSHOP-4591 Voucher edit inline - prevent to update data when input invalid amount value of voucher</li>
<li>REDSHOP-4027 Media - Can not add video</li>
<li>REDSHOP-4570 Shopper groups management - Insert search feature for this page</li>
<li>REDSHOP-5362 {order_shipping_shop_location} isn't replaced</li>
<li>REDSHOP-5360: Configuration - general tab - website show <code>Invalid Email</code> when "Administrator Email" include space on email field</li>
<li>REDSHOP-5359 Backend access management - it should remember the prior selected tab (of user group) on save</li>
<li>PALMARINE-1277 Fix tcpdf error</li>
<li>REDSHOP-5302 php 7.2 - php notice on manufacture detail on frontend</li>
<li>REDSHOP-4745 fields - Country selection box not working</li>
<li>REDSHOP-4657 Stockroom - "pre-order" does not work</li>
<li>REDSHOP-4503: Create coupon when client buy gift card is wrong</li>
<li>REDSHOP-4746 Custom field type "Media" missing button "save" and "save &amp; close"</li>
<li>REDSHOP-5349 Order Backend - Return order list when user click "Save without mail" button</li>
<li>Statistics - Filter function does not work for Total Visitors &amp; Total Page Views &amp; Most Popular (Viewed Products)</li>
<li>REDSHOP-5202 Question Management - Lost Style for NO_MATCHING_RESULTS</li>
<li>REDSHOP-5232 add trigger events for order detail changes in admin (extend historical record for Logman)</li>
<li>REDSHOP-5348 Fix for checking product user field in Cart::addNormalProduct</li>
<li>REDSHOP-3757 Shopping with Shopper Group</li>
<li>REDSHOP-2014 customize newsletter mail sign up</li>
<li>REDSHOP-4206: Avg order amount per customer - form need show better</li>
<li>REDSHOP-3534 Tag in template section 'Account template' not work</li>
<li>Fix gulp task release:plugin --group {group} --name {name} for a single plugin</li>
<li>REDSHOP-3967 Media - Create Media 'Media type' and 'File' do not match - Don't reload all information</li>
<li>REDSHOP-4397 Customer Input - Rating - show dialog to confirm action delete</li>
<li>REDSHOP-5342 after creating new user in redshop, the data of just created user shows on next creation of user</li>
<li>REDSHOP-5339 Discover duplicated <code>jQuery(document).ready(function(){...</code> in src/assets/com_redshop/js/redshop.validation.js</li>
<li>REDSHOP-5134 Codeception for Order status - Update all cases for order status</li>
<li>RSC-314 Module search not sending ajax request when we enable ajax search</li>
<li>REDSHOP-4218 Configuration -&gt; User -&gt; Tooltip for "Accept Terms &amp; conditions" is not translated</li>
<li>REDSHOP-4131 Csv Import Categories - set parent of imported item to Root node in case the parent_id column is not set or 0 in csv</li>
<li>REDSHOP-5336 Remove redundant character '&gt;' in the title of questions Management</li>
<li>REDSHOP-5323 move import/export "Newsletter Subscribers" plugins</li>
<li>REDSHOP-5332 Some view have Reset button work wrong when item have select</li>
<li>PALMARINE-1096 Fix pagination</li>
<li>PALMARINE-1095 Fix search on empty keyword</li>
<li>REDSHOP-5334 [Product detail] Pre-chosen does not applied for image</li>
<li>REDSHOP-5329 Lost language when user go on voucher detail</li>
<li>REDSHOP-5333 It should remember cart when user log out then log in again</li>
<li>REDSHOP-3419 Refactor zipcode</li>
<li>REDSHOP-5293: Wrong calculation when applying coupon/voucher at cart page Priority WIP</li>
<li>REDSHOP-4871 - Update icons COPY on for tool bar copy button</li>
</ul>

<hr>

<h6>Last updated on November 29, 2019</h6>