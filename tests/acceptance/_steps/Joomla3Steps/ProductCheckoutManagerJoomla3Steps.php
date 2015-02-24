<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ProductCheckoutManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ProductCheckoutManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Checkout a Product with Bank Transfer
	 *
	 * @param   Array   $addressDetail   Address Detail Array
	 * @param   Array   $shipmentDetail  Shipment Detail Array
	 * @param   string  $productName     Name of the Product which we are going to Checkout
	 * @param   string  $categoryName    Name of the Product Category
	 *
	 * @return void
	 */
	public function checkOutProductWithBankTransfer($addressDetail, $shipmentDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Front End Page');
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$alertMessageDiv);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, \FrontEndProductManagerJoomla3Page::$alertMessageDiv);
		$I->see(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, \FrontEndProductManagerJoomla3Page::$alertMessageDiv);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$checkoutURL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(\FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement($productFrontEndManagerPage->finalCheckout($productName), 30);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
	}

	/**
	 * Function to fill in Details related to Address Information
	 *
	 * @param   Array  $addressDetail  Address Detail Array
	 *
	 * @return void
	 */
	public function addressInformation($addressDetail)
	{
		$I = $this;
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressEmail);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressEmail, $addressDetail['email']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressFirstName, $addressDetail['firstName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressLastName, $addressDetail['lastName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, $addressDetail['address']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, $addressDetail['postalCode']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, $addressDetail['city']);
		$I->selectOption(\FrontEndProductManagerJoomla3Page::$addressCountry, $addressDetail['country']);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressState, 20);
		$I->selectOption(\FrontEndProductManagerJoomla3Page::$addressState, $addressDetail['state']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, $addressDetail['phone']);
	}

	/**
	 * Function to fill in Detail related to shipping Address
	 *
	 * @param   Array  $shippingDetail  Shipping Information Array
	 *
	 * @return void
	 */
	public function shippingInformation($shippingDetail)
	{
		$I = $this;
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingFirstName, $shippingDetail['firstName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingLastName, $shippingDetail['lastName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingAddress, $shippingDetail['address']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingPostalCode, $shippingDetail['postalCode']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingCity, $shippingDetail['city']);
		$I->selectOption(\FrontEndProductManagerJoomla3Page::$shippingCountry, $shippingDetail['country']);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$shippingState, 20);
		$I->selectOption(\FrontEndProductManagerJoomla3Page::$shippingState, $shippingDetail['state']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingPhone, $shippingDetail['phone']);
	}
}
