## redSHOP Configuration - Integration
This section covers configuration settings for services that redSHOP has native integration support for. These include analytics (Google Analytics), SMS notifications (Clickatell), shipping (Post Danmark) and accounting (e-conomic). The controls are grouped together into four sections accordingly: "Google Analytics", "Clickatell",  and "e-conomic". (Note that while these are being offered at present, more planned integrations will appear in this section as redSHOP develops over time.)

<hr>

### In this article you will fine:

<ul>
<li><a href="#gls">Gls Configuration</a>
<li><a href="#clickatell">Clickatell</a>
<li><a href="#pacsoft">Pacsoft</a>
<li><a href="#e-conomic">e-conomic</a>
</ul>

<hr>

### Overview Integration Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img63.png" class="example"/>

<hr>

<!-- Gls Configuration -->
<h2 id="gls">Gls Configuration</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img64.png" class="example"/>

<ul>
<li><b>GLS Customer ID - </b>The Web Property ID provided to the shop administrator in their Google Analytics account that will be used to collect and send various statistics pertaining to the online store to the related Google Analytics account. More information about Google Analytics and the services provided is available on the Google Analytics website.
</ul>

<hr>

<!-- Clickatell -->
<h2 id="clickatell">Clickatell</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img65.png" class="example"/>

<ul>
<li><b>Clickatell SMS Service - </b>Sets whether the Clickatell SMS Service will be enabled and used by the online store. Clickatell is an online service that offers shop administrators various tools to interact with their customers, in redSHOP's case the ability to send out bulk mobile SMS messages with order status updates to customers who have placed orders in the online store. Setting this option to "Yes" will enable the use of the Clickatell SMS service, and requires that the relevant details be filled in by the shop administrator in order to access the Clickatell account from which SMS messages will be sent. When enabled, Clickatell will send order status updates by SMS to the phone number that the customer had entered during the registration / checkout process, therefore the customer must have provided this information for them to receive the SMS. More information about Clickatell and its services is available on the Clickatell website.<br>
<b>Available options: </b>Yes, No

<li><b>Clickatell Username - </b>The username of the Clickatell account that will be used to send order status update bulk SMS messages to customers.

<li><b>Clickatell Password - </b>The password to the Clickatell account that will be used to send order status update bulk SMS messages to customers.

<li><b>Type your Clickatell API ID/KEY - </b>The API ID / key that was provided to the owner of the Clickatell account that will be used to send order status update bulk SMS messages to customers. This key is necessary in authorizing SMS messages to be sent.

<li><b>Order Status for sending SMS - </b>Sets the status a customer's order must be set to in order to trigger the Clickatell SMS message being sent to the customer. The shop administrator can select from a list of all available stasus levels in the "Order Status" section.
</ul>

<hr>

<!-- Pacsoft -->
<h2 id="pacsoft">Pacsoft</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img66.png" class="example"/>

<ul>
Sets whether redSHOP will integrated with your Pacsoft shipping account.

<li><b>Integration With Pacsoft - </b>Available options: No,Yes

<li><b>Pacsoft customer ID</b>

<li><b>Pacsoft Password</b>

<li><b>Pacsoft sender address</b>

<li><b>Pacsoft Sender Postal Code</b>

<li><b>Generate PackSoft Label - </b>Available options: Generate Parcel Manually from Order Listing Page,  Generate Parcel Automatically on Selected Status.  Using the second choice, the dropdown of selected status applies with A <b>vailable options:</b> Pending, Confirmed, Cancelled, Refunded, Shipped, Ready for Delivery, Ready for 1st Delivery, Ready for 2nd Delivery, Awaiting credit card payment, Awaiting Paypal payment, Awaiting bank transfer,Payment received, Reclamation, Partially shipped, Returned, Partially returned, Partially reclamation. 

<li><b>Show Product Detail - Available options: </b>No,Yes

<li><b>Enable track and trace email - Available options: </b>No,Yes

<li><b>nable SMS from webpack - Available options:</b> No,Yes
</ul>

<hr>

<!-- e-conomic -->
<h2 id="e-conomic">e-conomic</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img67.png" class="example"/>

<ul>
<li><b>e-conomic Integration - </b>Sets whether an integration between redSHOP and the e-conomic accounting service is enabled and in use by the online store. As the description displayed in this section reads: "e-conomic is a web based accounting program specifically developed for small or medium sized companies. Since e-conomic is web based you will never have to install bulky software, it is accessible anywhere, you can cooperate with your auditor or external accountant via the internet and you will never have to install new software. e-conomic makes it easy to keep track of your finances and reduces your paperwork.". The e-conomic service is available in more than 10 countries, including Denmark, Spain, Sweden, France and the United Kingdom. More information about e-conomic and the services is available on the e-conomic website.

Enabling this integration will result in a new "Accounting" section appearing on redSHOP's Main Menu and in the left-hand navigation panel, as well as a new "E-conomic accounting" button and link in each respective section. 
<br><b>Available options: </b>No,Yes

<li><b>Choice of Book Invoice - </b>Available options: Directly Book Invoice, Manually Book Invoice,  Book Invoice on Selected Order Status.

<b>NOTICE: </b>If you are using DRAFT features you must finalize invoices from redSHOP or invoice numbers in redSHOP and e-conomic will not match.

<li><b>e-conomic book invoice number -  Available options: </b>Same as Order Number, Sequentially in e-conomic (No Match Up with Order Number)

<li><b>Default e-conomic Account Group - </b>(Select: from available accounting groups)

<li><b>Store Attributes as Products in e-conomic - Available options: </b>Yes, No

<li><b>Short error messages - Available options: </b>Yes, No
</ul>

<hr>

<h6>Last updated on July 22, 2019</h6>