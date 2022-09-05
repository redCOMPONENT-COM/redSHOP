## redSHOP 2.0.3
redCOMPONENT is pleased to announce the immediate availability of redSHOP 2.0.3 This is an Improvement release with bug fixes.

<b>Important note: redSHOP 2.x is only compatible with Joomla 3.x.</b> If your site is still in Joomla 2.5 or early note that your version is not any more maintained and update is recommended.

<hr>

### Install and UPDATE instructions
To install or update redSHOP check the instructions page: [Updating redSHOP](chapters/getting-started-general/updating-redshop.md)

<hr>

### Changelog - redSHOP - Version 2.0.3

<ul>
<li><a href="#bugs">Bugs</a>
<li><a href="#stories">Stories</a>
<li><a href="#newFeature">New Feature</a>
<li><a href="#improvements">Improvements</a>
<li><a href="#task">Task</a>
<li><a href="#subTask">Sub-Task</a>
</ul>

<hr>

<h4 id="bugs">Bugs</h4>

<ul>
<li>[REDSHOP-3263] - Single select custom field in redshop miss language on admin
<li>[REDSHOP-3280] - Allow create mass discount WITHOUT product
<li>[REDSHOP-3302] - Backend/ Menu item - Trigger at Select Product doesn't work
<li>[REDSHOP-3325] - Custom field display data incorrect
<li>[REDSHOP-3328] - {category_short_desc} {category_description} are not working
<li>[REDSHOP-3329] - Send button in Edit/ Create new the Question detail is working incorrectly
<li>[REDSHOP-3333] - Can not fetch template Private/ Company billing when switching language
<li>[REDSHOP-3334] - Send button is working same the Create New a Question
<li>[REDSHOP-3341] - Checkout page is broken after installed "default GLS" plugin
<li>[REDSHOP-3344] - Notice error in wizard
<li>[REDSHOP-3345] - Edit product: Can't add Related product
<li>[REDSHOP-3392] - Remove duplicated config param "Add to cart button leads", fix wrong way of getting this parametter
<li>[REDSHOP-3402] - Missing the language at " PRODUCT_OUT_OF_STOCK"
<li>[REDSHOP-3405] - Must addIncludePath to get the right model call in some files
<li>[REDSHOP-3406] - Frontend/Backend - Ask question - Cannot create new question
<li>[REDSHOP-3421] - Backend - Config - Duplicate "Add to cart button leads" and work incorrectly
<li>[REDSHOP-3424] - Resend Order Mail : Message is wrong when send mail failed
<li>[REDSHOP-3426] - GIFTCARD: Table 'redshop_giftcard' doesn't exist SQL=SHOW FULL COLUMNS FROM 'redshop_giftcard'
<li>[REDSHOP-3427] - Create new Gift card have occurred an error
<li>[REDSHOP-3429] - Update redSHOP : Can't DROP 'id'; check that column/key exists SQL=ALTER TABLE 'redshop_country' DROP INDEX 'id';
<li>[REDSHOP-3430] - Upgrade throw errors from 2.0 to latest version
<li>[REDSHOP-3431] - Javascript error while adding cart
<li>[REDSHOP-3433] - SQL error while adding cart
<li>[REDSHOP-3436] - SQL error in checkout view
<li>[REDSHOP-3437] - Error when purchasing giftcard
<li>[REDSHOP-3439] - Can't save custom fields in product
<li>[REDSHOP-3440] - Occurred an error after send the question about product
<li>[REDSHOP-3441] - Update Category Parent in Category information
<li>[REDSHOP-3442] - Remove " dot " in front subcategory
<li>[REDSHOP-3449] - Order list: View PDF order.
<li>[REDSHOP-3450] - Can't received change status order mail
<li>[REDSHOP-3451] - Add Mass discount is working incorrect
<li>[REDSHOP-3459] - Auto create order when status of Quotation changed to "Ordered"
<li>[REDSHOP-3478] - Module redSHOP Categories - Cause SQL Error
<li>[REDSHOP-3479] - Create menu item - Can't get item-id for product page
<li>[REDSHOP-3486] - Module redSHOP Megamenu - Add ordering params
<li>[REDSHOP-3493] - [Add Order] - Display incorrect message
<li>[REDSHOP-3494] - Edit Category Redshop Mod. View image attached
<li>[REDSHOP-3498] - Manual create order at backend with notice message success but NO order created
<li>[REDSHOP-3499] - Ordering by Name in redSHOP Mega Menu is working incorrect
<li>[REDSHOP-3500] - PostDanmark Shipping Plugin - Can not display google map.
<li>[REDSHOP-3506] - Missing language "{shipping_address_info_lbl" in invoice mail
<li>[REDSHOP-3508] - Missing the language at PLG_REDSHOP_SHIPPING_POSTDANMARK
<li>[REDSHOP-3511] - Management mail: Can't scroll to end of page
<li>[REDSHOP-3515] - redSHOP 2.0.0.4 - Edit product detail on admin cannot save data for custom field
<li>[REDSHOP-3516] - Update the number of product in stockroom is wrong
<li>[REDSHOP-3518] - Admin view - Remove useless blank space in the bottom
<li>[REDSHOP-3521] - Product's Thumbnail in Categories
<li>[REDSHOP-3524] - Product, Category page - Remove the warning
<li>[REDSHOP-3525] - Edit/New product : Remove the message
<li>[REDSHOP-3526] - Create new order in admin - Can't un-check and select shipping address
<li>[REDSHOP-3538] - PostDanmark shipping plugin - fixing wrong syntax in getting google api key
<li>[REDSHOP-3547] - Wysiwyg custom field uses Joomla Default Editor
<li>[REDSHOP-3548] - Create new order in admin - Error saving order details
<li>[REDSHOP-3549] - Edit order detail in admin - Fatal error: Cannot use object of type stdClass
<li>[REDSHOP-3552] - Lost config in redshop when update version 1.5.0.5.3 to 2.0.x
<li>[REDSHOP-3553] - Upgrade 1.3-> 2.0.0.6 - Delete user in admin : An error has occurred.
<li>[REDSHOP-3566] - Product Discount Management - Create new mass discount - New button is working incorrect
<li>[REDSHOP-3577] - Install redshop 2.0.0.6 : Remove warning
<li>[REDSHOP-3593] - {category_main_name} do not display
<li>[REDSHOP-3596] - Can not submit product rating
<li>[REDSHOP-3607] - Bugs 0 - Call to undefined method JViewLegacy::addViewHelperPath() when upgrade new version redshop
<li>[REDSHOP-3628] - Order Management - Orders List- Edit Order- Add Product Incorrect
<li>[REDSHOP-3637] - Order Status Management - Delete order status - Need to check order status before delete.
<li>[REDSHOP-3668] - Menu item - Edit menu - Option tab - Missing the language "COM_RESHOP_HOW_MANY_PRODUCTS "
<li>[REDSHOP-3670] - Remove warning after installed redSHOP 2.0.0.6
<li>[REDSHOP-3684] - Product - Can't save "custom field" with Selection Based On Selected Conditions type. View description
<li>[REDSHOP-3707] - Shipping - Wrappings - "New" button wrapping have some trouble when click checkbox of the wrapping before clicks on "New" button
<li>[REDSHOP-3709] - Can not change or show category image in view "category detail" (admin)
<li>[REDSHOP-3718] - Products - VAT/Tax Groups - Create new VAT/Tax Group need have "Save" button
<li>[REDSHOP-3725] - Update the language. View image attached
<li>[REDSHOP-3726] - Order - Edit order - 500 JHtmlBehavior::joombox not found.
<li>[REDSHOP-3735] - Currency Management - Delete currency - Need check currency at "Configuration" before delete currency.
<li>[REDSHOP-3736] - Update redSHOP 2.0.0.6 : SQL error processing query
<li>[REDSHOP-3744] - Error in Query
<li>[REDSHOP-3763] - Customer Input - Questions - Create new question have answers
<li>[REDSHOP-3770] - Move checksum MD5 to admin and add XML for MD5 checksum
<li>[REDSHOP-3811] - Shipping Rates - Limit by countries does not work
<li>[REDSHOP-3812] - Checkout page - Don't load user billing information when click on edit.
<li>[REDSHOP-3818] - Subproperty price include VAT not show correctly because of wrong logic
<li>[REDSHOP-3892] - Copy product
<li>[REDSHOP-3917] - Refactor Export features
<li>[REDSHOP-3933] - Mail - New mail - Can not accept when user fills in wrong "Mail BCC"
<li>[REDSHOP-3952] - Fatal error: Cannot instantiate abstract class RedshopHelperWorld in /opt/lampp/htdocs/j36/libraries/redshop/helper/world.php on line 52
</ul>

<hr>

<h4 id="stories">Stories</h4>

<ul>
<li>[REDSHOP-3504] - Do not store User information after create new quotation
</ul>

<hr>

<h4 id="newFeature">New Feature</h4>

<ul>
<li>[REDSHOP-3245] - Orders Statistic
<li>[REDSHOP-3391] - Fatal error: Call to a member function getFieldsBySection() on a non-object in /home/staging/public_html/administrator/components/com_redshop/helpers/extra_field.php on line 59 rride/helper/override.php(116) : eval()'d code on line 60
<li>[REDSHOP-3418] - Add "Drag n Drop" library for upload image.
<li>[REDSHOP-3476] - Create module redSHOP Mega Menu
<li>[REDSHOP-3527] - Product custom field type ="Single select" did not display on front-end
<li>[REDSHOP-3535] - Create tag to render youtube video in product detail view
<li>[REDSHOP-3537] - Favoritlister - It should be possible to mark a "variant" as favorite
<li>[REDSHOP-3567] - Plugin: Add LOGman events to redSHOP Config
</ul>

<hr>

<h4 id="improvements">Improvements</h4>

<ul>
<li>[REDSHOP-3339] - Make compability for redshop config variables.
<li>[REDSHOP-3340] - Create B/C javascript
<li>[REDSHOP-3349] - View category which one no exists
<li>[REDSHOP-3352] - Country: Re-structure Table.
<li>[REDSHOP-3355] - Admin > Helper > redMediaHelper Move to library
<li>[REDSHOP-3360] - Admin > Helper > redshophelperimages and thumbnail to Libraries > Helper > Media
<li>[REDSHOP-3361] - Move sh404sef into plugin.
<li>[REDSHOP-3362] - Should clean user's file uploaded list after product added to cart
<li>[REDSHOP-3363] - Add fully support Gulp script for redSHOP
<li>[REDSHOP-3367] - Admin > Helper > product_category into libraries/redshop/helper/category.php
<li>[REDSHOP-3368] - [Add Order] - Removed 'You must provide a user login name.' message when focus to ''Username' field
<li>[REDSHOP-3370] - Fix wrong syntax in attribute.js (causing add to cart issue on Safari)
<li>[REDSHOP-3373] - State: Re-structure Table.
<li>[REDSHOP-3375] - Add base class for refactor
<li>[REDSHOP-3377] - Admin / Helper / economic to Library > Economic / economic
<li>[REDSHOP-3378] - Admin / Helper / extra_field >>> Library / Helper / extrafields
<li>[REDSHOP-3379] - Admin / Helper / LeftMenu to Library / Menu / Left_Menu
<li>[REDSHOP-3380] - Admin / Helper / Menu to Library / Menu / Menu
<li>[REDSHOP-3381] - Admin / Helper / order_functions >>>> Library / Helper / order
<li>[REDSHOP-3382] - Admin / Helper / Product >>>> Library / Helper / Product
<li>[REDSHOP-3383] - Admin / Helper / QuotationHelper >>>> Library / helper / quotation
<li>[REDSHOP-3385] - Admin / Helper / redshopmail >>>> Library / Helper / Mail
<li>[REDSHOP-3386] - Admin / Helper / redTemplate >>>> Library / Helper / template
<li>[REDSHOP-3387] - Admin / Helper / rsstockroomhelper >>>> Library / Helper / Stockroom
<li>[REDSHOP-3388] - Admin / Helper / shipping >>>> Library / Helper / shipping
<li>[REDSHOP-3389] - Admin / Helper / shoppergroup >>>> Library / Helper / shopper_group
<li>[REDSHOP-3393] - 404 error page show up when product is not exist.
<li>[REDSHOP-3395] - Country List - Should use the same format for search function.
<li>[REDSHOP-3403] - SUPPLIER: refactor MVC
<li>[REDSHOP-3404] - QUESTION: refactor MVC
<li>[REDSHOP-3435] - Backend: Remove and hide something not used.
<li>[REDSHOP-3444] - sh404sef plugin - Parse link error with Utf8 character
<li>[REDSHOP-3448] - Re-Styled Gift card in admin
<li>[REDSHOP-3477] - Backend: In Order Detail, when add additional product, the search box need search on product number too
<li>[REDSHOP-3487] - Add language for string "MOD_REDSHOP_MEGAMENU_DESCRIPTION"
<li>[REDSHOP-3507] - Change background and color for Error
<li>[REDSHOP-3509] - Change success to Success in message
<li>[REDSHOP-3519] - Admin: Add ordering feature with drag style.
<li>[REDSHOP-3529] - Reduce queries load
<li>[REDSHOP-3542] - No. of Products per Page allows '0'
<li>[REDSHOP-3551] - Improve module mega menu combine with joomla Menu
<li>[REDSHOP-3558] - Move {stockroom_detail} tag to layout
<li>[REDSHOP-3560] - REFACTOR: mod_redshop_currencies
<li>[REDSHOP-3568] - Performance optimize
<li>[REDSHOP-3573] - Fix notice message "Undefined property: stdClass::$id" in shipping rate view (admin)
<li>[REDSHOP-3575] - redSHOP plugin event - Add onPropertyAddtoCart on replacePropertyAddtoCart
<li>[REDSHOP-3580] - One step checkout
<li>[REDSHOP-3581] - Admin: Show "Taxs" feature in backend
<li>[REDSHOP-3586] - MVC Refactor: Order Statuses
<li>[REDSHOP-3590] - redSHOP plugin event - Add onProductImage to getProductImage and replaceAccessoryData
<li>[REDSHOP-3600] - Product Discount Management - Create new mass discount - Discount Start Date and Discount End Date is working incorrect
<li>[REDSHOP-3601] - Product Discount Management - Create new mass discount - Discount amount equal to zero
<li>[REDSHOP-3602] - Product Discount Management - Delete mass discount - Delete Discount don't show Dialog commit .
<li>[REDSHOP-3604] - Product Management -Detele Product - Delete Product don't show Dialog commit .
<li>[REDSHOP-3618] - You can't clear the custom field on backend
<li>[REDSHOP-3620] - Order Management - Orders - Edit Order - Edit "Billing Address Information" - "Phone" and "E- mail " format is incorrect
<li>[REDSHOP-3634] - Order Management - Order - Confirm information when click "Delete" button order.
<li>[REDSHOP-3642] - Move PDF to plugin
<li>[REDSHOP-3679] - Product - Create new/edit VAT Rates
<li>[REDSHOP-3692] - Discount - Create new "Mass discount" - Checking the form field values before submitting
<li>[REDSHOP-3708] - Shipping - Shipping Boxes - Delete shipping box need show dialog to commit action delete and show message when delete successfully.
<li>[REDSHOP-3714] - Console error 404 not found in category list view
<li>[REDSHOP-3719] - Export and Import - Multi Stockroom
<li>[REDSHOP-3729] - Configuration - redSHOP configuration - Main Price settings - Need to suitable when user choice "Currency" and "Currency Symbol"
<li>[REDSHOP-3730] - Remove Backward compability config.
<li>[REDSHOP-3733] - Updata category tag replace with sub categories
<li>[REDSHOP-3775] - Backend - Stockroom List Image - Missing image
<li>[REDSHOP-3936] - Newsletter - "Newsletter Management" form need change style + make alignment
<li>[REDSHOP-3938] - Newsletter Management - Do not show Message"COM_REDSHOP_ALREADY_CANCLE_SUBSCRIPTION"
</ul>

<hr>

<h4 id="task">Task</h4>

<ul>
<li>[REDSHOP-3171] - Add new helper replacement
<li>[REDSHOP-3291] - Product import can not change "On Sale" status
<li>[REDSHOP-3372] - Admin > Helper to Libraries
<li>[REDSHOP-3420] - Create tag loop item from parent category.
</ul>

<hr>

<h4 id="subTask">Sub-Task</h4>

<ul>
<li>[REDSHOP-3466] - Move mass discount from PRODUCT to DISCOUNT group
<li>[REDSHOP-3468] - Remove Add Mass Discount left menu item
<li>[REDSHOP-3496] - Ngan Luong plug-in
<li>[REDSHOP-3505] - Fix Locators in Wrapper Administrator Cest
<li>[REDSHOP-3769] - Replace JRequest with JInput in component/admin/controllers
</ul>

<hr>

<h6>Last updated on March 22, 2017</h6>