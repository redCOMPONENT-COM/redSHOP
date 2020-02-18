<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductFrontEndJoomla3Page
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class FrontEndProductManagerJoomla3Page extends AdminJ3Page
{
	// Include url of current page

	/**
	 * @var string
	 */
	public static $URL = '/index.php?option=com_redshop';

	/**
	 * @var string
	 */
	public static $cartPageUrL = "index.php?option=com_redshop&view=cart";

	/**
	 * @var string
	 */
	public static $quotation = "/index.php?option=com_redshop&view=quotation";

	/**
	 * @var string
	 */
	public static $addQuotation = "//input[@name='addquotation']";

	/**
	 * @var string
	 */
	public static $categoryDiv = "//div[@id='redshopcomponent']";

	/**
	 * @var string
	 */
	public static $productList = "//div[@id='redcatproducts']";

	/**
	 * @var string
	 */
	public static $addToCart = "//span[contains(text(), 'Add to cart')]";

	/**
	 * @var string
	 */

	public static $addToCompare = "//input[@name='rsProductCompareChk']";

	/**
	 * @var string
	 */
	public static $showProductToCompare = "//a[text() = 'Show Products To Compare']";

	/**
	 * @var string
	 */
	public static $alertMessageDiv = "//div[@class='alert alert-success']";

	/**
	 * @var string
	 */
	public static $alertSuccessMessage = "Product has been added to your cart.";

	/**
	 * @var string
	 */
	public static $alterOutOfStock = "Sorry, This product is out of stock....";

	/**
	 * @var string
	 */
	public static $addQuotationSuccess = 'Quotation detail has been sent successfully';

	/**
	 * @var string
	 */
	public static $checkoutURL = "/index.php?option=com_redshop&view=checkout";

	/**
	 * @var string
	 */
	public static $headBilling = 'Billing Address Information';

	/**
	 * @var string
	 */
	public static $newCustomerSpan = "//span[text() = 'New customer? Please Provide Your Billing Information']";

	/**
	 * @var string
	 */
	public static $checkoutButton = "//input[@class='greenbutton btn btn-primary']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $proceedButton = 'Proceed';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $proceedButtonId = "#submitbtn";

	/**
	 * @var string
	 */
	public static $addressEmail = "#private-email1";

	/**
	 * @var string
	 */
	public static $userEmail = "//input[@id='user_email']";

	/**
	 * @var string
	 */
	public static $addressFirstName = "//input[@id='private-firstname']";

	/**
	 * @var string
	 */
	public static $addressLastName = "//input[@id='private-lastname']";

	/**
	 * @var string
	 */
	public static $addressAddress = "//input[@id='private-address']";

	/**
	 * @var string
	 */
	public static $addressPostalCode = "//input[@id='private-zipcode']";

	/**
	 * @var string
	 */
	public static $addressCity = "//input[@id='private-city']";

	/**
	 * @var string
	 */
	public static $addressCountry = "//select[@id='rs_country_country_code']";

	/**
	 * @var string
	 */
	public static $addressState = "//select[@id='state_code']";

	/**
	 * @var string
	 */
	public static $addressPhone = "//input[@id='private-phone']";

	/**
	 * @var string
	 */
	public static $shippingFirstName = "//input[@id='firstname_ST']";

	/**
	 * @var string
	 */
	public static $shippingLastName = "//input[@id='lastname_ST']";

	/**
	 * @var string
	 */
	public static $shippingAddress = "//input[@id='address_ST']";

	/**
	 * @var string
	 */
	public static $shippingPostalCode = "//input[@id='zipcode_ST']";

	/**
	 * @var string
	 */
	public static $shippingCity = "//input[@id='city_ST']";

	/**
	 * @var string
	 */
	public static $countryId = "#rs_country_country_code";

	/**
	 * @var string
	 */
	public static $selectSecondCountry = "//select[@id='rs_country_country_code']/option[2]";

	/**
	 * @var string
	 */
	public static $shippingCountry = "//select[@id='country_code_ST']";

	/**
	 * @var string
	 */
	public static $shippingState = "//select[@id='state_code_ST']";

	/**
	 * @var string
	 */
	public static $shippingPhone = "//input[@id='phone_ST']";

	/**
	 * @var string
	 */
	public static $billingFinal = "//h3[text() = 'Bill to information']";

	/**
	 * @var string
	 */
	public static $termAndConditions = "//input[@id='termscondition']";

	/**
	 * @var string
	 */
	public static $termAndConditionsId = 'termscondition';

	/**
	 * @var string
	 */
	public static $checkoutFinalStep = "#checkout_final";

	/**
	 * @var string
	 */
	public static $orderReceiptTitle = "//h1[contains(text(), 'Order Receipt')]";

	/**
	 * @var string
	 */
	public static $orderReceipt = "Order Receipt";

	/**
	 * @var array
	 */
	public static $idAddAccount = "//label//input[@id='createaccount']";

	/**
	 * @var array
	 */
	public static $idUserNameOneStep = "//input[@id='onestep-createaccount-username']";

	/**
	 * @var array
	 */
	public static $idPassOneStep = "//input[@id='password1']";

	/**
	 * @var array
	 */
	public static $idPassConfirmOneStep = "//input[@id='password2']";

	/**
	 * @var array
	 */
	public static $radioCompany = "//input[@id='toggler2']";

	/**
	 * @var array
	 */
	public static $radioIDCompany = "//input[@id='toggler2']";

	/**
	 * @var array
	 */
	public static $radioPrivate = "//input[@billing_type='private']";

	/**
	 * @var array
	 */
	public static $idCompanyName = "//input[@id='company_name']";

	/**
	 * @var array
	 */
	public static $idCompanyNameOnePage = "//input[@id='company-company_name']";

	/**
	 * @var array
	 */
	public static $idCompanyAddressOnePage = "//input[@id='company-address']";

	/**
	 * @var array
	 */
	public static $idCompanyEmailOnePage = "//input[@id='company-email1']";

	/**
	 * @var array
	 */
	public static $idCompanyZipCodeOnePage = "//input[@id='company-zipcode']";

	/**
	 * @var array
	 */
	public static $idCompanyCityOnePage = "//input[@id='company-city']";

	/**
	 * @var array
	 */
	public static $idCompanyPhoneOnePage = "//input[@id='company-phone']";

	/**
	 * @var array
	 */
	public static $idBusinessNumber = "//input[@id='vat_number']";

	/**
	 * @var array
	 */
	public static $idEanNumber = "//input[@id='ean_number']";

	/**
	 * @var array
	 */
	public static $idCompanyFirstName = "//input[@id='company-firstname']";

	/**
	 * @var array
	 */
	public static $idCompanyLastName = "//input[@id='company-lastname']";

	/**
	 * @var string
	 */
	public static $searchProductRedShop = "//div[@class='product_search']";

	/**
	 * @var string
	 */
	public static $inputSearchProductRedShop = "//input[@id='keyword']";

	/**
	 * @var string
	 */
	public static $buttonSearchProductRedShop = "//input[@id='Search']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $locatorMessageEnterUser = "#onestep-createaccount-username-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $enableCreateAccount = "jQuery('#createaccount').click()";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $shippingMethod = "//strong[contains(text(),'Shipping Method')]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $locatorMessagePassword = "#password1-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $locatorMessageConfirmPassword = "#password2-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $locatorMessageEAN = "#ean_number-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $locatorMessageAcceptTerms= "#termscondition-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $locatorMessagePayment = "#payment_method_id-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterUser = "Please enter username";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageFieldRequired = "This field is required";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterEmail = "Please enter email address";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterCompanyName = "Please enter company name";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterFirstName = "Please enter first name";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterLastName = "Please enter last name";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterAddress = "Please enter address";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterCity = "Please enter city";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEnterPhone = "Please specify a valid phone number";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageSelectPayment = "Select Payment Method";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageAcceptTerms = "Please accept Terms and conditions before clicking in the Checkout button.";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEmailInvalid = "Please enter a valid email address.";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageEAN = "Enter only 13 digits without spaces";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageRelated = 'You may also interested in this/these product(s)';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $selectorEmailInvalid = '//label[@for = "private-email1"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $paymentEWAY = "//div[@id='rs_payment_eway']//label//input";

	/**
	 * @param $name
	 * @since 2.1.2
	 * @return string
	 */
	public function locatorMessagePrivate($name)
	{
		$xpath = "#private-$name-error";
		return $xpath;
	}

	/**
	 * @param $name
	 * @since 2.1.2
	 * @return string
	 */
	public function locatorMessageCompany($name)
	{
		$xpath = "#company-$name-error";
		return $xpath;
	}

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $quantity1 = "//tr[1]//td[4]//span[1]//label[1]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $quantity2 = "//tr[2]//td[4]//span[1]//label[1]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $labelPayment = "//h3[contains(text(),'Payment Method')]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $quantityFieldCart = '//input[@name="quantity"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $totalFinalCheckout = '(//div[@class="form-group total"])/div';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $errorAddToCart = 'Product was not added to cart';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $paymentBankTransferDiscount = "//input[@value='rs_payment_banktransfer_discount']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $submitCurrent = 'Change Currency';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productPrice = '#product_price';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $priceDenmark = 'DKK';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $page = 'Product Front End Page';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $currencyChooseButton = '#product_currency';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $mostSoldProducts = 'Most Sold Products';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $latestProducts = 'Latest Products';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $newestProducts = 'Newest Products';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $nameProductNewest = '(//div[@class =\'current\']/dd/div/div/p/a)[1]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $namProductsLatest = '(//div[@class =\'current\']/dd/div/div/p/a)[2]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $nameProductSold = '(//div[@class =\'current\']/dd/div/div/p/a)[3]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $discount = '#mod_redmainprice';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $valueDiscount = '//div[@class="mod_discount_main"]//td[2]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $shippingWithVat = '#spnShippingrate';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $iconShippingRate = "//input[@onclick=\"javascript:onestepCheckoutProcess(this.name,'giaohangnhanh');\"]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $iconShippingGLS = "//input[@onclick=\"javascript:onestepCheckoutProcess(this.name,'default_shipping_gls');\"]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $iconShippingGLSBusiness = "//input[@onclick=\"javascript:onestepCheckoutProcess(this.name,'default_shipping_glsbusiness');\"]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $iconSelfPickup = "//input[@onclick=\"javascript:onestepCheckoutProcess(this.name,'self_pickup');\"]";

	/**
	 * Function to get the Path $position for Attribute Dropdown List
	 *
	 * @param $position
	 *
	 * @return string
	 */
	public function attributeDropdown($position)
	{
		$xpath = "//span[@id='select2-chosen-$position']";

		return $xpath;
	}

	/**
	 * Function to get the Path $position for Attribute Dropdown Search
	 *
	 * @param $position
	 *
	 * @return string
	 */
	public function attributeDropdownSeach($position)
	{
		$xpath = "//input[@id='s2id_autogen'.$position.'_search']";

		return $xpath;
	}
	/**
	 * @var array
	 */
	public static $attributeSearchFirst = "//input[@id='s2id_autogen1_search']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $radioShippingRate = '//label[@class="radio inline"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $radioPayment = '//label[@class="radio"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $quantityOrderReceipt = '//div[@class="update_cart"]';

	/**
	 * Function to get the Path for Category on the FrontEnd Page
	 *
	 * @param   String $categoryName Name of the Category
	 *
	 * @return string
	 */
	public function productCategory($categoryName)
	{
		$path = "//a[text() = '" . $categoryName . "']";

		return $path;
	}

	/**
	 * Function to get the Path for Product
	 *
	 * @param   String $productName Name of the Product
	 *
	 * @return string
	 */
	public function product($productName)
	{
		$path = "//a[text() = '" . $productName . "']";

		return $path;
	}

	/**
	 * Function to return path of the Product on the Final Receipt Page
	 *
	 * @param   String $productName Name of the Product
	 *
	 * @return string
	 */
	public function finalCheckout($productName)
	{
		$path = "//div/a[text()='" . $productName . "']";

		return $path;
	}

	/**
	 * Function to get Path $productName in Product
	 *
	 * @param String $productName Name of the Product
	 *
	 * @return string
	 */
	public function productName($productName)
	{
		$path = "//div[text()='" . $productName . "']";

		return $path;
	}

	/**
	 * @param $position
	 * @return string
	 * @since 2.1.3
	 */
	public function nameRedSHOPProduct($position)
	{
		$xpath = "(//div[@class='mod_redshop_products_title'])[$position]";

		return $xpath;
	}

	/**
	 * Shopper group product header in frontend
	 * @var string
	 * @since 2.1.3
	 */
	public static $shopperGroupProductHeader = "redSHOP - ShopperGroup Product";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $selectorPageHeader = "//h3[contains(text(),'redSHOP - ShopperGroup Product')]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $priceWhoBought = '.priceWhoBought';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $btnAddToCartWhoBought = '.addToCartWhoBought';

	/**
	 * @param $name
	 * @return string
	 * @since 2.1.3
	 */
	public function nameModule($name)
	{
		$xpath = "//h3[contains(text(),'$name')]";
		return $xpath;
	}

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $eanPayment = "#rs_payment_eantransfer2";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $klarnaPayment = "#klarna2";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $fieldPNO = "#rs_pno";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $tableCart = "//table[@class='table table-striped']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $columnProduct = "//table[@class='table table-striped']//tbody/tr/td[1]";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $buttonEmptyCart = '//input[@onclick = "document.empty_cart.submit();"]';
}
