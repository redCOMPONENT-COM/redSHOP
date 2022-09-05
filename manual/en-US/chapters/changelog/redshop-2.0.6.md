## redSHOP 2.0.6
redCOMPONENT is pleased to announce the immediate availability of redSHOP 2.0.6 This is an Improvement release with bug fixes.

<b>Important note: redSHOP 2.x is only compatible with Joomla 3.x.</b> If your site is still in Joomla 2.5 or early note that your version is not any more maintained and update is recommended.

<hr>

### Install and UPDATE instructions
To install or update redSHOP check the instructions page: [Updating redSHOP](chapters/getting-started-general/updating-redshop.md)

<hr>

### Changelog - redSHOP - Version 2.0.6

<ul>
<li><a href="#bugs">Bugs</a>
<li><a href="#improvements">Improvements</a>
<li><a href="#new">New</a>
</ul>

<hr>

<h4 id="bugs">Bugs</h4>

<ul>
<li>Fix - VAT wrong calculation based on user’s billing/shipping address.
<li>Fix - Cannot create menu item for Product detail.
<li>Fix - Generate book invoice with Economic integration.
<li>Fix - Error on checkout with Economic integration.
Improve search function with keyword and fix the return wrong result when multi-language is enabled.
<li>Fix - Discount prices are not updated when products are imported from CSV
<li>Fix - Missing product attribute data when imported from CSV.
<li>Fix – Attribute video is not shown.
<li>Fix - Cannot save Product Accessories for Category.
<li>Fix - Sometimes shipping and payment plugin cannot load the languages.
</ul>

<hr>

<h4 id="improvements">Improvements</h4>

<ul>
<li>Joomla! 3.7.1 compatible 
<li>[Admin] Implement Joomla! nested tree for Categories
<li>[Admin] Improve layout for Categories & Category Edit
<li>[Admin] Improve layout for Fields and Field Edit
<li>[Admin] Improve layout for Discounts and Discount Edit
<li>[Admin] Add new layout and structure for applying Joomla! ACL on Categories, Fields and Suppliers
<li>[Admin] Can create custom field for Order and Store in Order. Order custom field data can be replaced in Invoice pdf as well.
<li>[Admin] In order detail page, implement timeline layout for order status log
<li>[Template] Add new tag {stock_status} to render stock status of product on order
<li>[Template] Add new tag {lowest_price} to render lowest price (base on product attribute prices) of product on product list & product detail
<li>[Template] Add new tag {highest_price} to render highest price of product (base on product attribute prices) on product list & product detail
<li>[Admin] Implement inline-edit feature for Categories Management, Fields Management and Suppliers Management page. This feature can be enabled/disabled in Configuration
</ul>

<hr>

<h4 id="new">New</h4>

<ul>
<li>[Plugin] redSHOP Bundle product type: Allow to create product bundles. User can view and add to cart this bundle as a normal product
<li>[Plugin] Google Analytics support
<li>[Plugin] Generate Google microdata for product. Auto-generate Google Microdata tag with 3 types (JSON, Microdata tag and RDFa) for product
<li>[Module] redSHOP Products: Add new param for “Watched products” displaying
</ul>

<hr>

<h6>Last updated on June 7, 2017</h6>