<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductFrontEndJoomla3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class FrontEndProductManagerJoomla3Page extends AdminJ3Page
{
	// Include url of current page
	public static $URL = '/index.php?option=com_redshop';

	public static $cartPageUrL = "index.php?option=com_redshop&view=cart";

	public static $quotation = "/index.php?option=com_redshop&view=quotation";

	public static $addQuotation = '//input[@name=\'addquotation\']';

	public static $categoryDiv = "//div[@id='redshopcomponent']";

	public static $productList = "//div[@id='redcatproducts']";

	public static $addToCart = "//span[contains(text(), 'Add to cart')]";
	
	public static $addToCompare = ['xpath' => '//label[@class=\'checkbox\']'];

	public static $showProductToCompare = ['xpath' => '//a[text() = \'Show Products To Compare\']'];

	public static $alertMessageDiv = "//div[@class='alert alert-success']";

	public static $alertSuccessMessage = "Product has been added to your cart.";

	public static $alterOutOfStock="Sorry, This product is out of stock....";

	public static $addQuotationSuccess = 'Quotation detail has been sent successfully';

	public static $checkoutURL = "/index.php?option=com_redshop&view=checkout";

	public static $headBilling = 'Billing Address Information';

	public static $newCustomerSpan = "//span[text() = 'New customer? Please Provide Your Billing Information']";
	
	public static $addressEmail = "#private-email1";
	
	public static $userEmail = ['xpath' => '//input[@id=\'user_email\']'];

	public static $addressFirstName = "//input[@id='private-firstname']";

	public static $addressLastName = "//input[@id='private-lastname']";

	public static $addressAddress = "//input[@id='private-address']";

	public static $addressPostalCode = "//input[@id='private-zipcode']";

	public static $addressCity = "//input[@id='private-city']";

	public static $addressCountry = "//select[@id='rs_country_country_code']";

	public static $addressState = "//select[@id='state_code']";

	public static $addressPhone = "//input[@id='private-phone']";

	public static $shippingFirstName = "//input[@id='firstname_ST']";

	public static $shippingLastName = "//input[@id='lastname_ST']";

	public static $shippingAddress = "//input[@id='address_ST']";

	public static $shippingPostalCode = "//input[@id='zipcode_ST']";

	public static $shippingCity = "//input[@id='city_ST']";

	public static $countryId = ['id' => 'rs_country_country_code'];
	
	public static $selectSecondCountry = ['xpath' => '//select[@id=\'rs_country_country_code\']/option[2]'];

	public static $shippingCountry = "//select[@id='country_code_ST']";

	public static $shippingState = "//select[@id='state_code_ST']";

	public static $shippingPhone = "//input[@id='phone_ST']";

	public static $billingFinal = "//h3[text() = 'Bill to information']";

	public static $termAndConditions = "//input[@id='termscondition']";

	public static $checkoutFinalStep = "//input[@id='checkout_final']";

	public static $orderReceiptTitle = "//h1[contains(text(), 'Order Receipt')]";

	public static $orderReceipt = "Order Receipt";

	/**
	 * @var array
	 */
	public static $idAddAccount = ['xpath' => '//input[@id=\'createaccount\']'];

	/**
	 * @var array
	 */
	public static $idUserNameOneStep = ['xpath' => '//input[@id=\'onestep-createaccount-username\']'];

	/**
	 * @var array
	 */
	public static $idPassOneStep = ['xpath' => '//input[@id=\'password1\']'];

	/**
	 * @var array
	 */
	public static $idPassConfirmOneStep = ['xpath' => '//input[@id=\'password2\']'];

	/**
	 * @var array
	 */
	public static $radioCompany = ['xpath' => '//input[@billing_type=\'company\']'];

	/**
	 * @var array
	 */
	public static $radioPrivate = ['xpath' => '//input[@billing_type=\'private\']'];

	/**
	 * @var array
	 */
	public static $idCompanyName = ['xpath' => '//input[@id=\'company_name\']'];

	/**
	 * @var array
	 */
	public static $idCompanyNameOnePage = ['xpath' => '//input[@id=\'company-company_name\']'];

	/**
	 * @var array
	 */
	public static $idCompanyAddressOnePage = ['xpath' => '//input[@id=\'company-address\']'];
	
	/**
	 * @var array
	 */
	public static $idCompanyEmailOnePage = ['xpath' => '//input[@id=\'company-email1\']'];

	/**
	 * @var array
	 */
	public static $idCompanyZipCodeOnePage = ['xpath' => '//input[@id=\'company-zipcode\']'];

	/**
	 * @var array
	 */
	public static $idCompanyCityOnePage = ['xpath' => '//input[@id=\'company-city\']'];

	/**
	 * @var array
	 */
	public static $idCompanyPhoneOnePage = ['xpath' => '//input[@id=\'company-phone\']'];

	/**
	 * @var array
	 */
	public static $idBusinessNumber = ['xpath' => '//input[@id=\'vat_number\']'];

	/**
	 * @var array
	 */
	public static $idEanNumber = ['xpath' => '//input[@id=\'ean_number\']'];

	/**
	 * @var array
	 */
	public static $idCompanyFirstName  =   ['xpath' => '//input[@id=\'company-firstname\']'];

	/**
	 * @var array
	 */
	public static $idCompanyLastName  =   ['xpath' => '//input[@id=\'company-lastname\']'];


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
	 * @param $productName
	 * @return string
	 */
	public function productName($productName)
	{
		$path = "//div[text()='" . $productName . "']";

		return $path;
	}
}
