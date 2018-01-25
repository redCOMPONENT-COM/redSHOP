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

	public static $bankTransfer = "//input[@id='rs_payment_banktransfer0']";

	public static $termAndConditions = "//input[@id='termscondition']";

	public static $checkoutFinalStep = "//input[@id='checkout_final']";

	public static $orderReceiptTitle = "//h1[contains(text(), 'Order Receipt')]";

	public static $orderReceipt = "Order Receipt";


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
