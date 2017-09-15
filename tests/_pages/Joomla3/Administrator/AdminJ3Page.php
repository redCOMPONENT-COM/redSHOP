<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Abstract Class Core J3 Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
abstract class AdminJ3Page
{

	public static $buttonStatic = ['xpath' => "//body//div[2]//section//div//div//div//div//p[3]/a[3]"];
	/**
	 * @var string
	 */
	public static $namePage = "";

	/**
	 * @var string
	 */
	public static $url;

	/**
	 * @var string
	 */
	public static $messageHead = "Message";

	/**
	 * @var string
	 */
	public static $messageItemSaveSuccess = "Item saved.";

	/**
	 * @var string
	 */
	public static $messageItemDeleteSuccess = "1 item successfully deleted";

	/**
	 * @var string
	 */
	public static $resultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

	/**
	 * @var array
	 */
	public static $searchField = ['id' => 'filter_search'];

	/**
	 * @var string
	 */
	public static $namePath = "//div[@class='table-responsive']/table/tbody/tr/td[2]";

	/**
	 * @var string
	 */
	public static $statePath = "//div[@class='table-responsive']/table/tbody/tr/td[6]/a";

	/**
	 * @var array
	 */
	public static $headPage = ['xpath' => "//h1"];

	/**
	 * @var string
	 */
	public static $selectorSuccess = ".alert-success";

	/**
	 * @var string
	 */
	public static $selectorError=".alert-error";

	/**
	 * @var string
	 */
	public static $selectorMissing = '.alert-danger';

	/**
	 * @var string
	 */
	public static $selectorPageTitle = '.page-title';

	/**
	 * @var string
	 */
	public static $buttonSaveClose = "Save & Close";

	/**
	 * @var string
	 */
	public static $buttonSave = "Save";

	/**
	 * @var string
	 */
	public static $buttonDelete = "Delete";

	/**
	 * @var string
	 */
	public static $buttonNew = "New";

	/**
	 * @var string
	 */
	public static $buttonCancel = "Cancel";

	/**
	 * @var string
	 */
	public static $buttonPublish = "Publish";

	/**
	 * @var string
	 */
	public static $buttonUnpublish = "Unpublish";

	/**
	 * @var string
	 */
	public static $buttonEdit = "Edit";

	/**
	 * @var string
	 */
	public static $buttonReset = "Reset";

	/**
	 * @var string
	 */
	public static $buttonCheckIn = "Check-in";

	/**
	 * @var string
	 */
	public static $buttonClose = "Close";

// Include url of current page
// Fontend checkout first name

	/**
	 * @var string
	 */
	public static $URL = '/index.php?option=com_redshop';

	public static $URLLoginAdmin = '/administrator/index.php';
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
	public static $alertMessageDiv = "//div[@class='alert alert-success']";
	/**
	 * @var string
	 */
	public static $alertSuccessMessage = "Product has been added to your cart.";
	/**
	 * @var string
	 */
	public static $addressEmail = "#email1";

	/**
	 * @var string
	 *
	 */
	public static $addressFirstName = "//input[@id='firstname']";

	/**
	 * @var string
	 *
	 */
	public static $addressLastName = "//input[@id='lastname']";

	/**
	 * @var string
	 */
	public static $addressAddress = "//input[@id='address']";

	/**
	 * @var string
	 */
	public static $addressPostalCode = "//input[@id='zipcode']";

	/**
	 * @var string
	 */
	public static $addressCity = "//input[@id='city']";

	/**
	 * @var string
	 */
	public static $addressCountry = "//select[@id='country_code']";

	/**
	 * @var string
	 */
	public static $addressState = "//select[@id='state_code']";

	/**
	 * @var string
	 */
	public static $addressPhone = "//input[@id='phone']";
	/**
	 * @var string
	 */
	public static $termAndConditions = "//input[@id='termscondition']";
	/**
	 * @var string
	 */
	public static $checkoutFinalStep = "//input[@id='checkout_final']";
	/**
	 * @var string
	 */
	public static $checkoutButton = "//input[@value='Checkout']";
	/**
	 * @var array
	 */
	public static $paymentPayPad = ['xpath' => "//div[@id='rs_payment_paypal']//label//input"];
	/**
	 * @var array
	 */
	public static $acceptTerms = ['xpath' => "//div//label//input[@id='termscondition']"];
	/**
	 * @var string
	 */
	public static $priceTotal = "//div[@id='redshopcomponent']/div[2]/div/div/div[1]/div[2]/div/div[1]/div";
	/**
	 * @var string
	 */
	public static $priceDiscount = "//div[@id='redshopcomponent']/div[2]/div/div/div[1]/div[2]/div/div[2]/div";
	/**
	 * @var array
	 */
	public static $priceEnd = ['id' => 'spnTotal'];

	public static $shippingRate=['id'=>'spnShippingrate'];

	/**
	 *
	 * Function get value
	 * @param $value
	 * @return array
	 */
	public static function returnChoice($value)
	{
		$path = ['xpath' => "//span[contains(text(), '" . $value . "')]"];
		return $path;
	}

}