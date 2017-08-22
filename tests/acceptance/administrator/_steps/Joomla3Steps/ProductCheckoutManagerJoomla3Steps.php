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
	 * @param   Array  $addressDetail  Address Detail Array
	 * @param   Array  $shipmentDetail Shipment Detail Array
	 * @param   string $productName    Name of the Product which we are going to Checkout
	 * @param   string $categoryName   Name of the Product Category
	 *
	 * @return void
	 */
	public function checkOutProductWithBankTransfer( $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I=$this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->see(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->see("DKK 100,00", \GiftCardCheckoutPage::$priceTotal);
		$I->see("DKK 110,00", \GiftCardCheckoutPage::$priceEnd);
	}

	/**
	 * Function to fill in Details related to Address Information
	 *
	 * @param   Array $addressDetail Address Detail Array
	 *
	 * @return void
	 */
	public function addressInformation($addressDetail)
	{
		$I = $this;
		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressEmail);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressEmail, $addressDetail['email']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressFirstName, $addressDetail['firstName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressLastName, $addressDetail['lastName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, $addressDetail['address']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, $addressDetail['postalCode']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, $addressDetail['city']);
//		$I->selectOption(\FrontEndProductManagerJoomla3Page::$addressCountry, $addressDetail['country']);
//		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressState, 20);
//		$I->selectOption(\FrontEndProductManagerJoomla3Page::$addressState, $addressDetail['state']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, $addressDetail['phone']);
	}

	/**
	 * Function to fill in Detail related to shipping Address
	 *
	 * @param   Array $shippingDetail Shipping Information Array
	 *
	 * @return void
	 */
	public function shippingInformation($shippingDetail)
	{
		$I = $this;
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$shippingFirstName, 30);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingFirstName, $shippingDetail['firstName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingLastName, $shippingDetail['lastName']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingAddress, $shippingDetail['address']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingPostalCode, $shippingDetail['postalCode']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingCity, $shippingDetail['city']);
//		$I->selectOption(\FrontEndProductManagerJoomla3Page::$shippingCountry, $shippingDetail['country']);
//		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$shippingState, 20);
//		$I->selectOption(\FrontEndProductManagerJoomla3Page::$shippingState, $shippingDetail['state']);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$shippingPhone, $shippingDetail['phone']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Paypal Payment Plugin
	 *
	 * @param   Array  $addressDetail       Address Detail
	 * @param   Array  $shipmentDetail      Shipping Address Detail
	 * @param   Array  $payPalAccountDetail PayPal Account Detail
	 * @param   string $productName         Name of the Product
	 * @param   string $categoryName        Name of the Category
	 *
	 * @return void
	 */
	public function checkoutProductWithPayPalPayment($addressDetail, $shipmentDetail, $payPalAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Front End Page');
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(\PayPalPluginManagerJoomla3Page::$payPalPaymentOptionSelectOnCheckout);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(\PayPalPluginManagerJoomla3Page::$payWithPayPalAccountOption);
		$I->waitForElement(\PayPalPluginManagerJoomla3Page::$payPalPasswordField, 30);
		$I->fillField(\PayPalPluginManagerJoomla3Page::$payPalLoginEmailField, $payPalAccountDetail["email"]);
		$I->fillField(\PayPalPluginManagerJoomla3Page::$payPalPasswordField, $payPalAccountDetail["password"]);
		$I->click(\PayPalPluginManagerJoomla3Page::$privateComputerField);
		$I->click(\PayPalPluginManagerJoomla3Page::$submitLoginField);
		$I->waitForElement(\PayPalPluginManagerJoomla3Page::$payNowField, 30);
		$I->seeElement(\PayPalPluginManagerJoomla3Page::$payNowField);
		$I->click(\PayPalPluginManagerJoomla3Page::$payNowField);
		$I->waitForElement(\PayPalPluginManagerJoomla3Page::$paymentCompletionSuccessMessage, 30);
		$I->seeElement(\PayPalPluginManagerJoomla3Page::$paymentCompletionSuccessMessage);
	}

	/**
	 * Function to Test Checkout Process of a Product using the 2Checkout Payment Plugin
	 *
	 * @param   Array  $addressDetail         Address Detail
	 * @param   Array  $shipmentDetail        Shipping Address Detail
	 * @param   Array  $checkoutAccountDetail 2Checkout Account Detail
	 * @param   string $productName           Name of the Product
	 * @param   string $categoryName          Name of the Category
	 *
	 * @return void
	 */
	public function checkoutProductWith2CheckoutPayment($addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(['xpath' => "//div[@id='rs_payment_2checkout']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText('Secure Checkout', 20, ['xpath' => '//h1']);
		$I->see('Secure Checkout', ['xpath' => '//h1']);
		$I->click(['xpath' => "//section[@id='review-cart']/button"]);
		$I->fillField(['xpath' => "//input[@id='shipping-address-1']"], $checkoutAccountDetail['shippingAddress']);
		$I->click(['xpath' => "//section[@id='shipping-information']/button"]);
		$I->click(['xpath' => "//input[@id='same-as-shipping']"]);
		$I->click(['xpath' => "//section[@id='billing-information']/button"]);
		$I->waitForElement(['xpath' => "//input[@id='card-number']"], 30);
		$I->fillField(['xpath' => "//input[@id='card-number']"], $checkoutAccountDetail['debitCardNumber']);
		$I->click(['xpath' => "//section[@id='payment-method']/div[2]/button"]);
		$I->waitForText('Your payment has been processed', 10, '//h1');
		$I->see('Your payment has been processed', '//h1');
	}

	/**
	 * Function to Test Checkout Process of a Product using the Braintree Payment Plugin
	 *
	 * @param   Array  $addressDetail         Address Detail
	 * @param   Array  $shipmentDetail        Shipping Address Detail
	 * @param   Array  $checkoutAccountDetail 2Checkout Account Detail
	 * @param   string $productName           Name of the Product
	 * @param   string $categoryName          Name of the Category
	 *
	 * @return void
	 */
	public function checkoutProductWithBraintreePayment($addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(['xpath' => "//div[@id='rs_payment_braintree']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(['xpath' => "//input[@id='order_payment_name']"], 10);
		$I->fillField(['xpath' => "//input[@id='order_payment_name']"], $checkoutAccountDetail['customerName']);
		$I->fillField(['xpath' => "//input[@id='order_payment_number']"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['xpath' => "//input[@id='credit_card_code']"], $checkoutAccountDetail['cvv']);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='Checkout: next step']"]);
		$I->waitForText('Order placed', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('Order placed', "//div[@class='alert alert-success']");
	}

	/**
	 * Function to Test Checkout Process of a Product using the Braintree Payment Plugin
	 *
	 * @param   Array  $addressDetail         Address Detail
	 * @param   Array  $shipmentDetail        Shipping Address Detail
	 * @param   Array  $checkoutAccountDetail 2Checkout Account Detail
	 * @param   string $productName           Name of the Product
	 * @param   string $categoryName          Name of the Category
	 *
	 * @return void
	 */
	public function checkoutProductWithBeanStreamPayment($addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(['xpath' => "//div[@id='rs_payment_beanstream']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement(['xpath' => "//input[@id='order_payment_name']"], 10);
		$I->fillField(['xpath' => "//input[@id='order_payment_name']"], $checkoutAccountDetail['customerName']);
		$I->fillField(['xpath' => "//input[@id='order_payment_number']"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['xpath' => "//input[@id='credit_card_code']"], $checkoutAccountDetail['cvv']);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='Checkout: next step']"]);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText('Order placed', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('Order placed', "//div[@class='alert alert-success']");
	}

	public function checkoutProductWithCouponOrGift($productName, $categoryName, $couponCode)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\GiftCardCheckoutPage::$alertSuccessMessage, 60, \GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(\GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->fillField(\GiftCardCheckoutPage::$couponInput, $couponCode);
		$I->click(\GiftCardCheckoutPage::$couponButton);
		$I->waitForText(\GiftCardCheckoutPage::$messageInvalid, 10, \GiftCardCheckoutPage::$selectorSuccess);
		$I->see(\GiftCardCheckoutPage::$messageInvalid, \GiftCardCheckoutPage::$selectorSuccess);

		$I->see("DKK 24,00", \GiftCardCheckoutPage::$priceTotal);
		$I->see("DKK 10,00", \GiftCardCheckoutPage::$priceDiscount);
		$I->see("DKK 14,00", \GiftCardCheckoutPage::$priceEnd);
	}
}
