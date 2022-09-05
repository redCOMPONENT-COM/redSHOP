## Sync product from redSHOP to economic

<ul>
<li>Product created from redSHOP administrator will be synced to E-conomic as "Product"

<li>Sync Product Group to E-conomic, which product group a product is belong to being set in the field "e-conomic Account Group" as below screenshot:
<img src="./manual/en-US/chapters/e-conomic-integration/img/img3.png" class="example"/><br><br>

<li>If the product group does not exist on E-conomic, the system will send a request to E-conomic to create a new one, using below API call:<br>
https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?op=ProductGroup_CreateFromData <br><br>

<li>If the product group exists on E-conomic,  the system will send a request to E-conomic to update the existing one, using below API call:<br>
https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?op=ProductGroup_UpdateFromData <br><br>

<li>Sync product data to E-conomic: create new product to E-conomic (when creating new Product in redSHOP) or update info of existing product to E-conomic (in case we edit a redSHOP product).<br>
https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?op=ProductGroup_CreateFromData <br><br>

https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?op=Product_UpdateFromData
</ul>

<hr>

<h6>Last updated on August 14, 2020</h6>