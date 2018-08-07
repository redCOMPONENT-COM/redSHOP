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
	public static $checkoutFinalStep = "//input[@id='checkout_final']";

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
	public static $radioCompany = "//input[@billing_type='company']";

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
     * Function to get the Path $position for Attribute Dropdown List
     *
	 * @param $position
	 *
	 * @return array
	 */
	public function attributeDropdown($position)
	{
		$xpath = "//span[@id='select2-chosen-'.$position.'']";

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
}
