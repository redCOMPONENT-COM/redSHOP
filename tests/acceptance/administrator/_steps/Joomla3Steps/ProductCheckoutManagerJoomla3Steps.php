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
use \ConfigurationPage as ConfigurationPage;

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
		$I->click(\FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText('Order Receipt', 10, \FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
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

	public function checkoutProductCouponOrVoucherOrDiscount($userName, $password, $productName, $categoryName, $discount = array(), $orderInfo = array(), $applyDiscount, $orderInfoSecond = array())
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\GiftCardCheckoutPage::$alertSuccessMessage, 60, \GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(\GiftCardCheckoutPage::$cartPageUrL);
		$I->see($productName);
		if (isset($discount['allow']))
		{
			if ($discount['allow'] == ConfigurationPage::$discountVoucherCoupon
				|| $discount['allow'] == ConfigurationPage::$discountAndVoucherOrCoupon
				|| $discount['allow'] == ConfigurationPage::$discountVoucherSingleCouponSingle)
			{
				if ($applyDiscount == 'couponCode')
				{
					$I->wantToTest('Just one kinds of discount will apply');
					$I->wantToTest('We have voucher discount and coupon discount but We should apply coupon');
					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);
					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);

					$I->see($orderInfo['priceTotal'], \GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], \GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], \GiftCardCheckoutPage::$priceEnd);

					$I->wantTo('Add voucher again ');
					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);

					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorMessage);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorMessage);
				}
				if ($applyDiscount == 'voucherCode')
				{
					$I->wantToTest('Just one kinds of discount will apply');
					$I->wantToTest('We have voucher discount and coupon discount but We should apply coupon');
					$I->wantTo('Add voucher again ');
					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);
					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], \GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], \GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], \GiftCardCheckoutPage::$priceEnd);

					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);
					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorMessage);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorMessage);
				}
			}
			else
			{
				$I->wantToTest('Just apply discount with one coupon and multi voucher');
				if (($applyDiscount == 'couponCode'))
				{
					$I->comment('Apply 1 coupon and 2 voucher');
					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);
					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], \GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], \GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], \GiftCardCheckoutPage::$priceEnd);

					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);

					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);

					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);

				}
				else
				{
					$I->comment('Apply 1 voucher and 2 coupon');
					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);
					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], \GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], \GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], \GiftCardCheckoutPage::$priceEnd);

					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);

					$I->fillField(\GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(\GiftCardCheckoutPage::$couponButton);

					$I->waitForText(\GiftCardCheckoutPage::$messageValid, 10, \GiftCardCheckoutPage::$selectorSuccess);
					$I->see(\GiftCardCheckoutPage::$messageValid, \GiftCardCheckoutPage::$selectorSuccess);
				}
			}
		}

		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressEmail);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');

		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$saveInfoUser, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$saveInfoUser);
		$I->click(\FrontEndProductManagerJoomla3Page::$paymentPayPad);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);

		if (isset($orderInfoSecond))
		{

			$I->wantToTest('Goes on second order');
			$I->see($orderInfoSecond['priceTotal'], \GiftCardCheckoutPage::$priceTotal);
			$I->see($orderInfoSecond['priceDiscount'], \GiftCardCheckoutPage::$priceDiscount);
			$I->see($orderInfoSecond['priceEnd'], \GiftCardCheckoutPage::$priceEnd);
		}
		else
		{
			$I->wantToTest('Goes on first order');
			$I->see($orderInfo['priceTotal'], \GiftCardCheckoutPage::$priceTotal);
			$I->see($orderInfo['priceDiscount'], \GiftCardCheckoutPage::$priceDiscount);
			$I->see($orderInfo['priceEnd'], \GiftCardCheckoutPage::$priceEnd);

		}
		$I->click(\FrontEndProductManagerJoomla3Page::$acceptTerms);
	}

	/**
	 * Checkout product with discount
	 *
	 * @param string $productName
	 * @param string $categoryName
	 * @param string $subTotal
	 * @param string $discount
	 * @param string $total
	 *
	 * @return void
	 */
	public function checkoutWithDiscount($productName, $categoryName, $subTotal, $discount, $total)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$selectorSuccess, 30);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->see($subTotal, \FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->see($discount, \FrontEndProductManagerJoomla3Page::$priceDiscount);
		$I->see($total, \FrontEndProductManagerJoomla3Page::$priceEnd);
	}

	public function checkoutSpecificShopperGroup($userName, $password, $productName, $categoryName, $ShippingRate, $Total)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->pauseExecution();
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressEmail);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
		$I->click(\FrontEndProductManagerJoomla3Page::$buttonSave);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutButton, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->click(\FrontEndProductManagerJoomla3Page::$paymentPayPad);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutButton, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);

//		$I->see($subtotal, \FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->pauseExecution();
		$I->see($ShippingRate, \FrontEndProductManagerJoomla3Page::$shippingRate);
		$I->see($Total, \FrontEndProductManagerJoomla3Page::$priceEnd);
	}


	public function checkProductInsideStockRoom($productName, $categoryName, $subtotal, $Total)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alterOutOfStock, 60, \FrontEndProductManagerJoomla3Page::$selectorError);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$priceTotal, 30);
		$I->see($subtotal, \FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->see($Total, \FrontEndProductManagerJoomla3Page::$priceEnd);
	}

	public function checkoutQuotation($productName, $categoryName, $userEmail)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$quotation);
		$I->click(\FrontEndProductManagerJoomla3Page::$addQuotation);
		$I->acceptPopup();
		$I->fillField(\FrontEndProductManagerJoomla3Page::$userEmail, $userEmail);
		$I->click(\FrontEndProductManagerJoomla3Page::$addQuotation);
		$I->see(\FrontEndProductManagerJoomla3Page::$addQuotationSuccess, \FrontEndProductManagerJoomla3Page::$alertMessageDiv);
	}

	public function comparesProducts($categoryName, $productFirst, $productSecond)
	{
		$I       = $this;
		$usePage = new \FrontEndProductManagerJoomla3Page();
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productFirst), 30);
		$I->click($productFrontEndManagerPage->product($productFirst));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCompare, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCompare);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$showProductToCompare, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$showProductToCompare);
		$I->waitForElement($usePage->productName($productFirst), 30);

		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productSecond), 30);
		$I->click($productFrontEndManagerPage->product($productSecond));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCompare, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCompare);

		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$showProductToCompare, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$showProductToCompare);
		$I->waitForElement($usePage->productName($productFirst), 30);
		$I->waitForElement($usePage->productName($productSecond), 30);
	}

	public function onePageCheckout($productName,$categoryName,$subtotal,$Total,$customerInformation, $function)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart,30);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);

		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$radioCompany, 30);
		if ($function == 'business')
		{
			$I->pauseExecution();
			$I->click(\FrontEndProductManagerJoomla3Page::$radioCompany);
			$I->waitForElement(\FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
			$I->waitForElement(\FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);

			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);

			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
			$I->click(\FrontEndProductManagerJoomla3Page::$acceptTerms);
			$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}else{
			$I->fillField(\FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
		}
	}

	public function onePageCheckoutLogin($userName, $password, $productName, $categoryName, $ShippingRate, $Total)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$acceptTerms);

		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressEmail);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
		$I->click(\FrontEndProductManagerJoomla3Page::$buttonSave);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->see($ShippingRate, \FrontEndProductManagerJoomla3Page::$shippingRate);
		$I->see($Total, \FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->click(\FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->pauseExecution();
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
	}

}