## Mails
To define and manage almost templates mails in redSHOP, we supply a Mail Managemnet page which allows user easily create and manage a template mail other and apply it to the shop. 

<hr>

### In this article you will fine

<ul>
<li><a href="#overview-1">Overview of Mails Management Screen</a>
    <ul>
    <li><a href="#field-1">Field</a>
    <li><a href="#action-1">Action</a>
    </ul>

<li><a href="#overview-2">Overview Mail Template Details screen</a>
    <ul>
    <li><a href="#field-2">Field</a>
    <li><a href="#action-2">Action</a>
    </ul>

<li><a href="#working">Working Mail with redSHOP</a>
</ul>

<hr>

You have one web-site use Joomla and installed redSHOP component. Access administrator web-site page by (username/password) has been provided.

<img src="./manual/en-US/chapters/communication/img/administrator.png" class="example"/><br><br>

User clicks on Components on main menu and then select on "redSHOP" tabs.

<img src="./manual/en-US/chapters/communication/img/img1.png" class="example"/><br><br>

Next, Webpage will display overview page administrator of redSHOP, user clicks on "Communication" tab and select on "Mails" item. 

<img src="./manual/en-US/chapters/communication/img/img2.png" class="example"/><br><br>

<hr>

<!-- Overview Mails Management Screen  -->
<h2 id="overview-1">Overview Mails Management Screen </h2>

<h4 id="field-1">Field</h4>

<img src="./manual/en-US/chapters/communication/img/img3.png" class="example"/><br><br>

<ul>
<li><b>Mail Name – </b>the name given that identifies and describes this specific mail template.

<li><b>Mail Subject – </b>the title for this email template, as it will appear in the subject line of the email the customer will receive.

<li><b>Mail Section - </b>the section within redSHOP where emails are triggered and for which this template offers a specific layout to use when sending those emails.

<li><b>Publish – </b>sets whether this mail template should be available to assign to emails sent.
</ul>

<hr>

<h4 id="action-1">Action</h4>

<img src="./manual/en-US/chapters/communication/img/img4.png" class="example"/><br><br>

<ul>
<li><b>Reset – </b>clears any mail section or mail name filters that are limiting the results being displayed and updates to display all mail templates.

<li><b>New - </b>takes you to the add mail template screen where you can create a new mail template.

<li><b>Edit - </b>takes you to the edit mail template screen where you can modify an existing template's details. The screen can be reached either by clicking on the template name or clicking on the checkbox next to their name followed by the edit button.

<li><b>Delete - </b>removes the mail template from the database, this is a permanent delete.

<li><b>Publish - </b>lets you set templates to be active and available for usage, with either single or multiple template records selected for publishing.

<li><b>Unpublish - </b>lets you set templates to be disabled and unavailable, with either single or multiple template records selected for unpublishing.
</ul>

<hr>

<!-- Overview Mail Template Details screen  -->
<h2 id="overview-2">Overview Mail Template Details screen</h2>

<h4 id="field-2">Field</h4>

<img src="./manual/en-US/chapters/communication/img/img5.png" class="example"/><br><br>

<ul>
<li><b>Mail Name – </b>sets the name that identifies and describes this specific mail template.

<li><b>Mail Subject – </b>sets the title for this email template, as it will appear in the subject line of the email the customer will receive when this template is used.

<li><b>Mail Section - </b>sets the section within redSHOP where emails are triggered and for which this template offers a specific layout to use when sending those emails; each mail section has different context-sensitive template tags that are available to use when crafting the template content and design.

<li><b>Mail BCC – </b>sets the email address of the account that will be sent copies of the emails generated and sent to customers whenever this template is used to send mail (the email address will not appear in the email the customer receives).

<li><b>Publish – </b>sets whether this mail template should be available to assign to emails sent.

<li><b>Available options: </b>Yes, No.

<li><b>Body – </b>the space where the content of the mail template is stored; this space accepts text, images, HTML and specific mail-triggered Joomla! plugin calls; you can use a variety of template-specific tags in the body of the template, each set of tags is listed at the bottom of this screen.
</ul>

<hr>

<h4 id="action-2">Action</h4>

<img src="./manual/en-US/chapters/communication/img/img6.png" class="example"/><br><br>

<ul>
<li><b>Save - </b>saves changes and refreshes the page.

<li><b>Save & Close - </b>saves changes and redirects to the listing screen.

<li><b>Cancel - </b>go back to Mail Managemnet page without saving anything.
</ul>

<!-- Working Mail with redSHOP  -->
<h2 id="working">Working Mail with redSHOP</h2>

<ul>
<li>Go to Mail Management page, search "Order Mail" and then click on to view the details.
<img src="./manual/en-US/chapters/communication/img/img7.png" class="example"/><br><br>

<li>Go to frontend page and add some products to cart then do checkout successfullly.
<img src="./manual/en-US/chapters/communication/img/img8.png" class="example"/><br><br>

<li>User do check the details of order email that defined in advance in Mail Managemnet page.
<img src="./manual/en-US/chapters/communication/img/img9.png" class="example"/><br><br>

<li>To customize the order mail, user goes to detail and edits some fields.
<img src="./manual/en-US/chapters/communication/img/img10.png" class="example"/><br><br>

<li>Add tag {order_id_lbl}: {order_id}, {order_number_lbl}: {order_number} then change color Total price.
<img src="./manual/en-US/chapters/communication/img/img11.png" class="example"/><br><br>

<li>Add to cart again on frontend page.
<img src="./manual/en-US/chapters/communication/img/img12.png" class="example"/><br><br>

<li>Re-check the email again that applied the changes.
<img src="./manual/en-US/chapters/communication/img/img13.png" class="example"/><br><br>
</ul>

Video for change Order Mail: <a href="https://redshop.fleeq.io/l/bbqmlephqy-f5hu5camor">Click here</a>

<hr>

<h6>Last updated on October 16, 2019</h6>