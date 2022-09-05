## E-Conomic Integration
redSHOP provides a seamless integration with the accounting system <a href="https://www.reviso.com/blog/e-conomic-uk-is-now-reviso/">e-conomic</a>

With the integration you can:

<ul>
<li>Sync your entire product catalogue to e-conomic
<li>Create full products of all variants in e-conomic
<li>Sync redSHOP users as debtors in e-conomic
<li>Automatically or manually generate invoices to send to the customer (and store owner, if desired)
<li>Include shipping costs in invoices
<li>Integrate with your redSHOP payment processor for accepting payment of invoices.
</ul>

Setting up the integration requires configuration in the redSHOP Configuration, in the Integration tab, as well as installation of the e-conomic plugin for redSHOP.

To get it all set up follow these steps:

<ul>
<li>Download & Install
    <ul>
    <!-- We need update the link-->
    <li><a href="http://docs-en.helpscoutdocs.com/article/62-i-purchased-a-product-how-do-i-download-it">Download and install</a> the e-Conomic Plugin for redSHOP.
    </ul>

<li>Configure The Plugin
    <ul>
    <li>Go to Joomla Admin -> Extensions -> Plugin Manager, search for e-conomic to open the plugin and add your e-conomic account details:
    <img src="./manual/en-US/chapters/e-conomic-integration/img/img4.png" class="example"/><br><br>
    </ul>

<li>Configure redSHOP
    <ul>
    <li>Go to your redSHOP Admin and click on Configuration:
    <img src="./manual/en-US/chapters/e-conomic-integration/img/img5.png" class="example"/><br><br>
    <li>Go to the Integration tab:
    <img src="./manual/en-US/chapters/e-conomic-integration/img/img6.png" class="example"/><br><br>
    <li>Set your preferences:
    <img src="./manual/en-US/chapters/e-conomic-integration/img/img7.png" class="example"/>
    </ul>
</ul>

<hr>

### E-Conomic Setting Options

<ul>
<li><b>e-conomic Integration</li></b>
This setting will set the integration on, or off. Default: Off

<li><b>Choice of Book Invoice</li></b>
Select how you would like to book invoices.  Options are:
    <ul>
    <li>Directly Book Invoice</li>
    Book the invoice as soon as the order is placed.<br><br>
    <li>Manually Book Invoice</li>
    Allow a redSHOP Admin to manually book all invoices.<br><br>
    <li>Book Invoice on Selected Order Status</li>
    Select a status to book the invoices when an order reaches the defined status.
    </ul>

<li><b>e-conomic Book Invoice Number</li></b>
Set how you would like the invoice numbers to be done in e-conomic.  Options are: 
    <ul>
    <li>Same as Order Number
    <li>Sequentially in e-conomic (No match up with order number)
    </ul>

<li><b>Default e-conomic Account Group</li></b>
Select the default account group

<li><b>Store Attributes as Product in e-conomic</li></b>
Select whether you want product attributes to be added / synced in e-conomic as products.  Option are:
    <ul>
    <li>No
    <li>Store Attributes as Products in e-conomic
    <li>Store Attributes and Products in e-conomic
    </ul>

<li><b>Short error messages</li></b>
If set to Yes a SOAP exception message will be shown directly, otherwise it will show simple short error messages.
</ul>

<hr>

<h6>Last updated on July 19, 2019</h6>