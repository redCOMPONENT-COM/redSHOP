## redSHOP Custom Fields
redSHOP supplies lots of custom fields that allows user more flexible and powerful to customize the shop as.

<hr>

### In this article you will fine

<ul>
<li><a href="#overview-1">Overview of Custom Field Listings screen</a>
    <ul>
    <li><a href="#field-1">Field</a>
    <li><a href="#action-1">Action</a>
    </ul>

<li><a href="#overview-2">Overview of Custom Field Details</a>
    <ul>
    <li><a href="#field-2">Field</a>
    <li><a href="#action-2">Action</a>
    <li><a href="#type">Type</a>
        <ul>
        <li><a href="#field-addition">Field addition</a>
        </ul>
    <li><a href="#section">Section</a>
    </ul>

<li><a href="#working">Working with Custom Fields within redSHOP</a>
    <ul>
    <li><a href="#working-with-checkbox">Working With Checkbox</a>
    <li><a href="#working-with-text-area">Working With Text Area</a>
    <li><a href="#working-with-radio-button">Working With Radio button</a>
    <li><a href="#working-with-single-select">Working With Single Select</a>
    <li><a href="#working-with-text-tag-content">Working With Text Tag Content</a>
    </ul>

<li><a href="#show">Show custom filed on frontend</a>
    <ul>
    <li><a href="#show-with-checkbox">Show With Checkbox</a>
    <li><a href="#show-with-text-area">Show With Text Area</a>
    <li><a href="#show-with-radio-button">Show With Radio button</a>
    <li><a href="#show-with-single-select">Show With Single Select</a>
    <li><a href="#show-with-text-tag-content">Show With Text Tag Content</a>
    </ul>
</ul>

<hr>

Firstly you have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided

<img src="./manual/en-US/chapters/customization/img/administrator.png" class="example"/><br><br>

Secondly you click on Component on main menu and select on "redSHOP"

<img src="./manual/en-US/chapters/customization/img/img1.png" class="example"/><br><br>

Finally webpage will display overview page administrator of redSHOP

<img src="./manual/en-US/chapters/customization/img/img2.png" class="example"/><br><br>

<hr>

<!-- Overview of Custom Field Listings screen -->
<h2 id="overview-1">Overview of Custom Field Listings screen</h2>

<h4 id="field-1">Field</h4>

<img src="./manual/en-US/chapters/customization/img/img3.png" class="example"/><br><br>

<ul>
<li><b>(1) Title - </b>The title will appear under product editing and as title ofthe connected tag under template

<li><b>(2) Name - </b>name of the Custom field

<li><b>(3) Type - </b>Field type

<li><b>(4) Section - </b>where you would like to use this custom field

<li><b>(5) Group - </b>group for field

<li><b>(6) Icon Published or Unpublish - </b>view status Custom field item when this Custom field is working or not 

<li><b>(7) ID - </b>ID of the Custom field
</ul>

<hr>

<h4 id="action-1">Action</h4>

<img src="./manual/en-US/chapters/customization/img/img3.png" class="example"/><br><br>

<ul>

<li><b>New - </b>appears when adding a new the Custom field

<li><b>Delete - </b>delete 1 or more items has selected. Items has deleted will remove list the Custom field items  

<li><b>Check-in - </b>will unlock any the Custom field items when someone viewing it 

<li><b>Publish - </b>it change status from unpublish to publish for the Custom field items is working

<li><b>Unpublish - </b>it change status from publish to unpublish for the Custom field items isn't working
</ul>

<hr>

<!-- Overview of Custom Field Details -->
<h2 id="overview-2">Overview of Custom Field Details</h2>

<h4 id="field-2">Field</h4>

<img src="./manual/en-US/chapters/customization/img/img5.png" class="example"/><br><br>

<ul>
<li><b>(1) Type - </b>Select the type of custom field this will be

<li><b>(2)  Section - </b>The Section is where you would like to use this custom field

<li><b>(3) Group - </b>Select the group for the field is creating

<li><b>(4)  Name - </b>This is the name that will be used to add your custom field to Templates.  The name should be in lowercase, and an underscore ( _ ) used for spaces. On save the name will be changed to a format to work with redSHOP if not created as such, ie:   rs_custom_field_name

<li><b>(5) Title - </b>The title vill appear under product editing and as title of the connected tag under template editing

<li><b>(6)  CSS Class - </b>You can apply a class to this field to allow styling it with CSS

<li><b>(7)  Display in Product List - </b>No/yes.  Yes is default.  Yes will show the custom field data when viewing the product list in the backend

<li><b>(8)  Show in Checkout - </b>No/Yes. Yes is default. Yes will show the custom field data in the checkout page

<li><b>(9)  Show in Front - </b>No/Yes. Yes is default.  This option allows you to show data from this custom field in the frontend of the shop. *It is necessary to first add the field name to the template for the view you'd like to display this data in.  More on that to later in this tutorial

<li><b>(10)  Required - </b>No/yes. Yes is default

<li><b>(11)  Published - </b>No/Yes.  Yes is default

<li><b>(12) Description - </b>Add a description for this custom field if you'd like
</ul>

<hr>

<h4 id="action-2">Action</h4>

<img src="./manual/en-US/chapters/customization/img/img6.png" class="example"/><br><br>

<ul>
<li><b>Save - </b>apply the changes made to the custom field details or create new item and show information change latest on the page 

<li><b>Save & Close - </b>apply the changes made to the custom field details or create new and return to the custom field listing screen 

<li><b>Save & New - </b>apply the changes made to the custom field details or create new and return to the custom field create page new items other 

<li><b>Cancel - </b>return to the custom field listing screen without saving any changes
</ul>

<hr>

<h4 id="type">Type</h4>

<img src="./manual/en-US/chapters/customization/img/img7.png" class="example"/><br><br>

<ul>
<li>Select : option default

<li>Checkbox
<img src="./manual/en-US/chapters/customization/img/img8.png" class="example"/><br><br>

<li>Country selection box

<li>Date picker

<li>Documents

<li>Image
<img src="./manual/en-US/chapters/customization/img/img9.png" class="example"/><br><br>

<li>Image with link
<img src="./manual/en-US/chapters/customization/img/img10.png" class="example"/><br><br>

<li>Media

<li>Multiple select box
<img src="./manual/en-US/chapters/customization/img/img11.png" class="example"/><br><br>

<li>Radio buttons
<img src="./manual/en-US/chapters/customization/img/img12.png" class="example"/><br><br>

<li>Selections Based on Selected Conditions

<li>Single Select
<img src="./manual/en-US/chapters/customization/img/img13.png" class="example"/><br><br>

<li>Text Tag Content
<img src="./manual/en-US/chapters/customization/img/img14.png" class="example"/><br><br>

<li>Text Area
<img src="./manual/en-US/chapters/customization/img/img15.png" class="example"/><br><br>

<li>WYSIWYG
</ul>

<hr>

<h5 id="field-addition">Field addition</h5>

<ul>
<li><b>Size - </b>This is the width of the field in pixels

<li><b>Maximum Length - </b>This is the maximum number of characters you wish to allow in this field.

<li><b>Columns - </b>Relevant only for Text Area or Multi-select field types.

<li><b>Rows - </b>Relevant only for Text Area or Multi-select field types

<li><b>Option name - </b>This is the name of the option. The name should be in lowercase, and an underscore ( _ ) used for spaces. On save the name will be changed to a format to work with redSHOP if not created as such, ie:  rs_custom_field_name

<li><b>Option value - </b>value of the option

<li><b>Action</b></li>
<img src="./manual/en-US/chapters/customization/img/img16.png" class="example"/><br><br>

<li><b>Add option - </b>Webpage will display an row of the option
</ul>

<hr>

<h4 id="section">Section</h4>

<img src="./manual/en-US/chapters/customization/img/img17.png" class="example"/><br><br>

<ul>
<li>Product - Option Default
<img src="./manual/en-US/chapters/customization/img/img18.png" class="example"/><br><br>

<li>Category
<img src="./manual/en-US/chapters/customization/img/img19.png" class="example"/><br><br>

<li>Private Billing Address
<img src="./manual/en-US/chapters/customization/img/img20.png" class="example"/><br><br>

<li>Company Billing Address
<img src="./manual/en-US/chapters/customization/img/img21.png" class="example"/><br><br>

<li>Sample
<img src="./manual/en-US/chapters/customization/img/img22.png" class="example"/><br><br>

<li>Manufacturer
<img src="./manual/en-US/chapters/customization/img/img23.png" class="example"/><br><br>

<li>Shipping
<img src="./manual/en-US/chapters/customization/img/img24.png" class="example"/><br><br>

<li>Product user field
<img src="./manual/en-US/chapters/customization/img/img25.png" class="example"/><br><br>

<li>Gift Card user field
<img src="./manual/en-US/chapters/customization/img/img26.png" class="example"/><br><br>

<li>Private Shipping Address
<img src="./manual/en-US/chapters/customization/img/img27.png" class="example"/><br><br>

<li>Company Shipping Address
<img src="./manual/en-US/chapters/customization/img/img28.png" class="example"/><br><br>

<li>Product Date Picker
<img src="./manual/en-US/chapters/customization/img/img29.png" class="example"/><br><br>

<li>Quotation
<img src="./manual/en-US/chapters/customization/img/img30.png" class="example"/><br><br>

<li>Payment Gateway
<img src="./manual/en-US/chapters/customization/img/img31.png" class="example"/><br><br>

<li>Shipping Gateway
<img src="./manual/en-US/chapters/customization/img/img32.png" class="example"/><br><br>

<li>Order
<img src="./manual/en-US/chapters/customization/img/img33.png" class="example"/><br><br>
</ul>

<hr>

<!-- Working with Custom Fields within redSHOP -->
<h2 id="working">Working with Custom Fields within redSHOP</h2>

### In this article

<ul>
<li><a href="#working-with-checkbox">Working With Checkbox</a>
<li><a href="#working-with-text-area">Working With Text Area</a>
<li><a href="#working-with-radio-button">Working With Radio button</a>
<li><a href="#working-with-single-select">Working With Single Select</a>
<li><a href="#working-with-text-tag-content">Working With Text Tag Content</a>
</ul>

<hr>

<h4 id="working-with-checkbox">Working With Checkbox</h4>

<ul>
<li>Go to backend page of REDSHOP and click Customization then select "Custom Fields" tab
<img src="./manual/en-US/chapters/customization/img/img34.png" class="example"/><br><br>

<li>User click on combobox Type, select type for field
<img src="./manual/en-US/chapters/customization/img/img35.png" class="example"/><br><br>

<li>Example, click on Checkbox to choose type of field is checkbox
<img src="./manual/en-US/chapters/customization/img/img36.png" class="example"/><br><br>

<li>User fill in some option of the field
<img src="./manual/en-US/chapters/customization/img/img37.png" class="example"/><br><br>

<li>User click on combobox Section, select section for field
<img src="./manual/en-US/chapters/customization/img/img38.png" class="example"/><br><br>

<li>User fill in Name field, that will be used to add your custom field to Templates
<img src="./manual/en-US/chapters/customization/img/img39.png" class="example"/><br><br>

<li>User fill in Title field. Then User click on Save & Close button
<img src="./manual/en-US/chapters/customization/img/img40.png" class="example"/><br><br>

<li>And the result after creating field item
<img src="./manual/en-US/chapters/customization/img/img41.png" class="example"/><br><br>
</ul>

Video for Create Custom field With Checkbox type: <a href="https://redshop.fleeq.io/l/t2uqwe7vss-l6zd64213s">Click here</a>

<hr>

<h4 id="working-with-text-area">Working With Text area</h4>

<ul>
<li>Go to backend page of REDSHOP and click Customization then select "Custom Fields" tab
<img src="./manual/en-US/chapters/customization/img/img42.png" class="example"/><br><br>

<li>User click on combobox Type, select type for field
<img src="./manual/en-US/chapters/customization/img/img43.png" class="example"/><br><br>

<li>Click on Text area to choose type of field is Text area
<img src="./manual/en-US/chapters/customization/img/img44.png" class="example"/><br><br>

<li>User click on combobox Section, select section for field. And fill in some field as: Name, Title, Column, Row... Then click on Save button
<img src="./manual/en-US/chapters/customization/img/img45.png" class="example"/><br><br>

<li>And the result after creating field item.
<img src="./manual/en-US/chapters/customization/img/img46.png" class="example"/><br><br>
</ul>

Video for Create Custom field With Textarea type: <a href="https://redshop.fleeq.io/l/549a1rrwnz-v2hkcb72ks">Click here</a>

<hr>

<h4 id="working-with-radio-button">Working With Radio button</h4>

<ul>
<li>Go to backend page of REDSHOP and click Customization then select "Custom Fields" tab.
<img src="./manual/en-US/chapters/customization/img/img47.png" class="example"/><br><br>

<li>User click on combobox Type, select type for field. Click on Radio button to choose type of field is Radio button. 
<img src="./manual/en-US/chapters/customization/img/img48.png" class="example"/><br><br>

<li>Then User fill in some option of the field.
<img src="./manual/en-US/chapters/customization/img/img49.png" class="example"/><br><br>

<li>User click on combobox Section, select section for field.
<img src="./manual/en-US/chapters/customization/img/img50.png" class="example"/><br><br>

<li>User fill in some field as: Name, title... then click on Save & Close button.
<img src="./manual/en-US/chapters/customization/img/img51.png" class="example"/><br><br>

<li>And the result after creating field item.
<img src="./manual/en-US/chapters/customization/img/img52.png" class="example"/><br><br>
</ul>

Video for Create Custom field With Radio button type: <a href="https://redshop.fleeq.io/l/cfcwevjlsu-sfl5js3anw">Click here</a>

<hr>

<h4 id="working-with-single-select">Working With Single Select</h4>

<ul>
<li>Go to backend page of REDSHOP and click Customization then select "Custom Fields" tab.
<img src="./manual/en-US/chapters/customization/img/img53.png" class="example"/><br><br>

<li>User click on combobox Type, select type for field.
<img src="./manual/en-US/chapters/customization/img/img54.png" class="example"/><br><br>

<li>User click on Single Select to choose type of field is Single Select. Then User fill in some option of the field.
<img src="./manual/en-US/chapters/customization/img/img55.png" class="example"/><br><br>

<li>User click and choose Section of the field. After that, User fill in some field as: Name, title... then click on Save & Close button.
<img src="./manual/en-US/chapters/customization/img/img56.png" class="example"/><br><br>

<li>And the result after creating field item.
<img src="./manual/en-US/chapters/customization/img/img57.png" class="example"/><br><br>
</ul>

Video for Create Custom field With Single select type: <a href="https://redshop.fleeq.io/l/p671ahbgxu-suxs3q4oq3">Click here</a>

<hr>

<h4 id="working-with-text-tag-content">Working With Text Tag Content</h4>

<ul>
<li>Go to backend page of REDSHOP and click Customization then select "Custom Fields" tab.
<img src="./manual/en-US/chapters/customization/img/img58.png" class="example"/><br><br>

<li>User click on combobox Type, select type for field.
<img src="./manual/en-US/chapters/customization/img/img59.png" class="example"/><br><br>

<li>User click on Text Tag Content to choose type of field is Text Tag Content.
<img src="./manual/en-US/chapters/customization/img/img60.png" class="example"/><br><br>

<li>User click and choose section of field.
<img src="./manual/en-US/chapters/customization/img/img61.png" class="example"/><br><br>

<li>User fill in some field as: Name, title... then click on Save & Close button.
<img src="./manual/en-US/chapters/customization/img/img62.png" class="example"/><br><br>

<li>And the result after creating field item.
<img src="./manual/en-US/chapters/customization/img/img63.png" class="example"/><br><br>
</ul>

Video for Create Custom field With Text Tag Context type: <a href="https://redshop.fleeq.io/l/rmgsxosn4v-nbctenvgl0">Click here</a>

<hr>

<!-- Show custom filed on frontend -->
<h2 id="show">Show custom filed on frontend</h2>

### In this article

<ul>
<li><a href="#show-with-checkbox">Show With Checkbox</a>
<li><a href="#show-with-text-area">Show With Text Area</a>
<li><a href="#show-with-radio-button">Show With Radio button</a>
<li><a href="#show-with-single-select">Show With Single Select</a>
<li><a href="#show-with-text-tag-content">Show With Text Tag Content</a>
</ul>

<hr>

<h4 id="show-with-checkbox">Show With Checkbox</h4>

<ul>
<li>User click on Customization and choose Templates tab.
<img src="./manual/en-US/chapters/customization/img/img64.png" class="example"/><br><br>

<li>Then user click on section selected in field just create.
<img src="./manual/en-US/chapters/customization/img/img65.png" class="example"/><br><br>

<li>Webpage will display template detail include custom field just created.
<img src="./manual/en-US/chapters/customization/img/img66.png" class="example"/><br><br>

<li>User add Name of the field on Description. After that, click on Save button.
<img src="./manual/en-US/chapters/customization/img/img67.png" class="example"/><br><br>

<li>User clicks on Products and choose Product Management tab.
<img src="./manual/en-US/chapters/customization/img/img68.png" class="example"/><br><br>

<li>User choose the product that want to display field.
<img src="./manual/en-US/chapters/customization/img/img69.png" class="example"/><br><br>

<li>Webpage will display Product edit details, then click on Custom Fields.
<img src="./manual/en-US/chapters/customization/img/img70.png" class="example"/><br><br>

<li>User choose value that want to display on front end. Then click on Save button.
<img src="./manual/en-US/chapters/customization/img/img71.png" class="example"/><br><br>

<li>After save success, click on Review button.
<img src="./manual/en-US/chapters/customization/img/img72.png" class="example"/><br><br>

<li>Webpage will redirect to frontend page and show product details with field added
<img src="./manual/en-US/chapters/customization/img/img73.png" class="example"/><br><br>
</ul>

Video for Show Custom field With Checkbox: <a href="https://redshop.fleeq.io/l/cy2t88pb3z-qrezy2c6aw">Click here</a>

<hr>

<h4 id="show-with-text-area">Show With Text Area</h4>

<ul>
<li>User click on Customization and choose Templates tab.
<img src="./manual/en-US/chapters/customization/img/img74.png" class="example"/><br><br>

<li>Then user click on section selected in field just create.
<img src="./manual/en-US/chapters/customization/img/img75.png" class="example"/><br><br>

<li>Webpage will display template detail include custom field just created.
<img src="./manual/en-US/chapters/customization/img/img76.png" class="example"/><br><br>

<li>User add Name of the field on Description. After taht, click on Save button.
<img src="./manual/en-US/chapters/customization/img/img77.png" class="example"/><br><br>

<li>User click on Products and choose Product Management tab.
<img src="./manual/en-US/chapters/customization/img/img78.png" class="example"/><br><br>

<li>User choose the product that want to display field.
<img src="./manual/en-US/chapters/customization/img/img79.png" class="example"/><br><br>

<li>Webpage will display Product edit details, then click on Custom Fields.
<img src="./manual/en-US/chapters/customization/img/img80.png" class="example"/><br><br>

<li>User choose value that want to display on front end. Then click on Save button.
<img src="./manual/en-US/chapters/customization/img/img81.png" class="example"/><br><br>

<li>After save success, click on Review button.
<img src="./manual/en-US/chapters/customization/img/img82.png" class="example"/><br><br>

<li>Webpage will redirect to frontend page and show product details with field added.
<img src="./manual/en-US/chapters/customization/img/img83.png" class="example"/><br><br>
</ul>

Video for Show Custom field With Text Area: <a href="https://redshop.fleeq.io/l/olmb4ajpsu-ve6hxvqnud">Click here</a>

<hr>

<h4 id="show-with-radio-button">Show With Radio button</h4>

<ul>
<li>User click on Customization and choose Templates tab.
<img src="./manual/en-US/chapters/customization/img/img84.png" class="example"/><br><br>

<li>Then user click on section selected in field just create.
<img src="./manual/en-US/chapters/customization/img/img85.png" class="example"/><br><br>

<li>Webpage will display template detail include custom field just created.
<img src="./manual/en-US/chapters/customization/img/img86.png" class="example"/><br><br>

<li>User add Name of the field on Description. After taht, click on Save button.
<img src="./manual/en-US/chapters/customization/img/img87.png" class="example"/><br><br>

<li>User click on Products and choose Product Management tab.
<img src="./manual/en-US/chapters/customization/img/img88.png" class="example"/><br><br>

<li>User choose the product that want to display field.
<img src="./manual/en-US/chapters/customization/img/img89.png" class="example"/><br><br>

<li>Webpage will display Product edit details, then click on Custom Fields.
<img src="./manual/en-US/chapters/customization/img/img90.png" class="example"/><br><br>

<li>User choose value that want to display on front end. Then click on Save button.
<img src="./manual/en-US/chapters/customization/img/img91.png" class="example"/><br><br>

<li>After save success, click on Review button.
<img src="./manual/en-US/chapters/customization/img/img92.png" class="example"/><br><br>

<li>Webpage will redirect to frontend page and show product details with field added.
<img src="./manual/en-US/chapters/customization/img/img93.png" class="example"/><br><br>
</ul>

Video for Show Custom field With Radio button: <a href="https://redshop.fleeq.io/l/k3lt6cismd-2wx2e2ll4l">Click here</a>

<hr>

<h4 id="show-with-single-select">Show With Single Select</h4>

<ul>
<li>User click on Customization and choose Templates tab.
<img src="./manual/en-US/chapters/customization/img/img94.png" class="example"/><br><br>

<li>Then user click on section selected in field just create.
<img src="./manual/en-US/chapters/customization/img/img95.png" class="example"/><br><br>

<li>Webpage will display template detail include custom field just created.
<img src="./manual/en-US/chapters/customization/img/img96.png" class="example"/><br><br>

<li>User add Name of the field on Description. After that, click on Save button.
<img src="./manual/en-US/chapters/customization/img/img97.png" class="example"/><br><br>

<li>User click on Products and choose Product Management tab.
<img src="./manual/en-US/chapters/customization/img/img98.png" class="example"/><br><br>

<li>User choose the product that want to display field.
<img src="./manual/en-US/chapters/customization/img/img99.png" class="example"/><br><br>

<li>Webpage will display Product edit details, then click on Custom Fields.
<img src="./manual/en-US/chapters/customization/img/img100.png" class="example"/><br><br>

<li>User click on Select.
<img src="./manual/en-US/chapters/customization/img/img101.png" class="example"/><br><br>

<li>User choose value that want to display on front end. 
<img src="./manual/en-US/chapters/customization/img/img102.png" class="example"/><br><br>

<li>Then click on Save button.
<img src="./manual/en-US/chapters/customization/img/img103.png" class="example"/><br><br>

<li>After save success, click on Review button.
<img src="./manual/en-US/chapters/customization/img/img104.png" class="example"/><br><br>

<li>Webpage will redirect to frontend page and show product details with field added.
<img src="./manual/en-US/chapters/customization/img/img105.png" class="example"/><br><br>
</ul>

Video for Show Custom field With Single Select: <a href="https://redshop.fleeq.io/l/8l57ic9oy7-phauefeklg">Click here</a>

<hr>

<h4 id="show-with-text-tag-content">Show With Text Tag Content</h4>

<ul>
<li>User click on Customization and choose Templates tab.
<img src="./manual/en-US/chapters/customization/img/img106.png" class="example"/><br><br>

<li>Then user click on section selected in field just create.
<img src="./manual/en-US/chapters/customization/img/img107.png" class="example"/><br><br>

<li>Webpage will display template detail include custom field just created.
<img src="./manual/en-US/chapters/customization/img/img108.png" class="example"/><br><br>

<li>User add Name of the field on Description. After taht, click on Save button.
<img src="./manual/en-US/chapters/customization/img/img109.png" class="example"/><br><br>

<li>User click on Products and choose Product Management tab.
<img src="./manual/en-US/chapters/customization/img/img110.png" class="example"/><br><br>

<li>User choose the product that want to display field.
<img src="./manual/en-US/chapters/customization/img/img111.png" class="example"/><br><br>

<li>Webpage will display Product edit details, then click on Custom Fields.
<img src="./manual/en-US/chapters/customization/img/img112.png" class="example"/><br><br>

<li>User choose value that want to display on front end. Then click on Save button.
<img src="./manual/en-US/chapters/customization/img/img113.png" class="example"/><br><br>

<li>After save success, click on Review button.       
<img src="./manual/en-US/chapters/customization/img/img114.png" class="example"/><br><br>

<li>Webpage will redirect to frontend page and show product details with field added.    
<img src="./manual/en-US/chapters/customization/img/img115.png" class="example"/><br><br>
</ul>

Video for Show Custom field With Text Tag Content: <a href="https://redshop.fleeq.io/l/jxe3crbro4-juajcae82o">Click here</a>

<hr>

<h6>Last updated on October 16, 2019</h6>