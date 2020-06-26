<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use \ConfigurationPage as ConfigurationPage;
use GiftCardCheckoutPage;
use FrontEndProductManagerJoomla3Page;
use CheckoutChangeQuantityProductPage;

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
	 * @param   string $function       One Step Checkout: no/yes
	 *
	 * @return void
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkOutProductWithBankTransfer($addressDetail, $shipmentDetail, $productName, $categoryName, $function)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Front End Page');
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, FrontEndProductManagerJoomla3Page::$selectorMessage);
		$I->see(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, FrontEndProductManagerJoomla3Page::$selectorMessage);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		switch ($function)
		{
			case 'no':
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
					$I->click(FrontEndProductManagerJoomla3Page::$newCustomerSpan);
					$I->wait(1);
					$I->addressInformation($addressDetail);
					$I->shippingInformation($shipmentDetail);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$proceedButtonId, 30);
					$I->click(FrontEndProductManagerJoomla3Page::$proceedButtonId);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
					$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
					$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
					break;

			case 'yes':
					$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 30);
					$I->addressInformation($addressDetail);
					$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
					$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
					break;
		}

		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
	}

	/**
	 * Function to fill in Details related to Address Information
	 *
	 * @param   Array $addressDetail Address Detail Array
	 *
	 * @return void
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addressInformation($addressDetail)
	{
		$I = $this;
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressEmail, 60);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressFirstName, 60);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $addressDetail['firstName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressLastName, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $addressDetail['lastName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressAddress, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $addressDetail['address']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $addressDetail['email']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $addressDetail['postalCode']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $addressDetail['city']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $addressDetail['phone']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$countryCode1, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$countryCode1);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$searchCountryInput, 5);
		$I->fillField(FrontEndProductManagerJoomla3Page::$searchCountryInput, $addressDetail['country']);
		$I->wait(0.5);
		$I->pressKey(FrontEndProductManagerJoomla3Page::$searchCountryInput, \Facebook\WebDriver\WebDriverKeys::ENTER);
	}

	/**
	 * Function to fill in Detail related to shipping Address
	 *
	 * @param   Array $shippingDetail Shipping Information Array
	 *
	 * @return void
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function shippingInformation($shippingDetail)
	{
		$I = $this;
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$shippingFirstName, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingFirstName, $shippingDetail['firstName']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingLastName, $shippingDetail['lastName']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingAddress, $shippingDetail['address']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingPostalCode, $shippingDetail['postalCode']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingCity, $shippingDetail['city']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingPhone, $shippingDetail['phone']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$countryCode2, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$countryCode2);
		$I->fillField(FrontEndProductManagerJoomla3Page::$searchCountryInput, $shippingDetail['country']);
		$I->pressKey(FrontEndProductManagerJoomla3Page::$searchCountryInput, \Facebook\WebDriver\WebDriverKeys::ENTER);
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
	 * @throws \Exception
	 */
	public function checkoutProductWithPayPalPayment($addressDetail, $shipmentDetail, $payPalAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Front End Page');
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(\PayPalPluginManagerJoomla3Page::$payPalPaymentOptionSelectOnCheckout);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
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
	 * Function to Test Checkout Process of a Product using the Braintree Payment Plugin
	 *
	 * @param   Array  $addressDetail         Address Detail
	 * @param   Array  $shipmentDetail        Shipping Address Detail
	 * @param   Array  $checkoutAccountDetail 2Checkout Account Detail
	 * @param   string $productName           Name of the Product
	 * @param   string $categoryName          Name of the Category
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function checkoutProductWithBraintreePayment($addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Front End Page');
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(['xpath' => "//div[@id='rs_payment_braintree']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
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
	 * @throws \Exception
	 */
	public function checkoutProductWithBeanStreamPayment($addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Front End Page');
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$this->addressInformation($addressDetail);
		$this->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal);
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
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText('Order placed', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('Order placed', "//div[@class='alert alert-success']");
	}

	/**
	 * @param       $userName
	 * @param       $password
	 * @param       $productName
	 * @param       $categoryName
	 * @param array $discount
	 * @param array $orderInfo
	 * @param       $applyDiscount
	 * @param array $orderInfoSecond
	 *
	 * @throws \Exception
	 */
	public function checkoutProductCouponOrVoucherOrDiscount($userName, $password, $productName, $categoryName, $discount = array(), $orderInfo = array(), $applyDiscount, $orderInfoSecond = array(), $haveDiscount = array())
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->wait(0.5);
		try{
			$I->waitForElement(GiftCardCheckoutPage::$selectorSuccess, 30);
			$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage, 30, GiftCardCheckoutPage::$selectorSuccess);
		}catch (\Exception $e)
		{
		}
		$I->amOnPage(GiftCardCheckoutPage::$cartPageUrL);
		$I->see($productName);
		if (isset($discount['allow']))
		{
			if ($discount['allow'] == ConfigurationPage::$discountVoucherCoupon)
			{
				if ($applyDiscount == 'couponCode')
				{
					if($haveDiscount == 'no')
					{
						$I->wantToTest('Just one kinds of discount will apply');
						$I->wantToTest('We have voucher discount and coupon discount but We should apply coupon');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
						$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
						$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
						$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
						$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

						$I->wantTo('Add voucher ');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorMessage);
						$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorMessage);
					}

					if($haveDiscount == 'yes')
					{
						$I->wantToTest('Just one kinds of discount will apply');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageInvalid, 10, GiftCardCheckoutPage::$selectorError);
						$I->see(GiftCardCheckoutPage::$messageInvalid, GiftCardCheckoutPage::$selectorError);
						$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
						$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
						$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

						$I->wantTo('Add voucher ');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageInvalid, 10, GiftCardCheckoutPage::$selectorError);
						$I->see(GiftCardCheckoutPage::$messageInvalid, GiftCardCheckoutPage::$selectorError);
					}

				}
				if ($applyDiscount == 'voucherCode')
				{
					if($haveDiscount == 'no')
					{
						$I->wantToTest('Just one kinds of discount will apply');
						$I->wantToTest('We have voucher discount and coupon discount but We should apply voucher');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
						$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
						$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
						$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
						$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

						$I->wantTo('Add coupon ');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorMessage);
						$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorMessage);
					}

					if($haveDiscount == 'yes')
					{
						$I->wantToTest('Just one kinds of discount will apply');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageInvalid, 10, GiftCardCheckoutPage::$selectorError);
						$I->see(GiftCardCheckoutPage::$messageInvalid, GiftCardCheckoutPage::$selectorError);
						$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
						$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
						$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

						$I->wantTo('Add coupon ');
						$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
						$I->click(GiftCardCheckoutPage::$couponButton);
						$I->waitForText(GiftCardCheckoutPage::$messageInvalid, 10, GiftCardCheckoutPage::$selectorError);
						$I->see(GiftCardCheckoutPage::$messageInvalid, GiftCardCheckoutPage::$selectorError);
					}

				}
			}
			else if($discount['allow'] == ConfigurationPage::$discountVoucherSingleCouponSingle ||
				$discount['allow'] == ConfigurationPage::$discountAndVoucherOrCoupon)
			{
				if ($applyDiscount == 'couponCode')
				{
					$I->comment('Apply 1 coupon and 1 voucher');
					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);
					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfoSecond['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfoSecond['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfoSecond['priceEnd'], GiftCardCheckoutPage::$priceEnd);
				}
				else
				{
					$I->comment('Apply 1 voucher and 1 coupon');
					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);
					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfoSecond['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfoSecond['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfoSecond['priceEnd'], GiftCardCheckoutPage::$priceEnd);
				}
			}
			else
			{
				$I->wantToTest('Just apply discount with one coupon and multi voucher');
				if (($applyDiscount == 'couponCode'))
				{
					$I->comment('Apply 1 coupon and 2 voucher');
					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);

					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCodeSecond']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfoSecond['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfoSecond['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfoSecond['priceEnd'], GiftCardCheckoutPage::$priceEnd);
				}
				else
				{
					$I->comment('Apply 1 voucher and 2 coupon');
					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['voucherCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCode']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);

					$I->fillField(GiftCardCheckoutPage::$couponInput, $discount['couponCodeSecond']);
					$I->click(GiftCardCheckoutPage::$couponButton);
					$I->waitForText(GiftCardCheckoutPage::$messageValid, 10, GiftCardCheckoutPage::$selectorSuccess);
					$I->see(GiftCardCheckoutPage::$messageValid, GiftCardCheckoutPage::$selectorSuccess);
					$I->see($orderInfoSecond['priceTotal'], GiftCardCheckoutPage::$priceTotal);
					$I->see($orderInfoSecond['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
					$I->see($orderInfoSecond['priceEnd'], GiftCardCheckoutPage::$priceEnd);
				}
			}
		}

//		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressEmail,30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$saveInfoUser, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$saveInfoUser);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		if (isset($orderInfoSecond))
		{
			$I->wantToTest('Goes on second order');
			$I->waitForElement(GiftCardCheckoutPage::$priceTotal, 30);
			$I->see($orderInfoSecond['priceTotal'], GiftCardCheckoutPage::$priceTotal);
			$I->see($orderInfoSecond['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
			$I->see($orderInfoSecond['priceEnd'], GiftCardCheckoutPage::$priceEnd);
		}
		else
		{
			$I->wantToTest('Goes on first order');
			$I->waitForElement(GiftCardCheckoutPage::$priceTotal, 30);
			$I->see($orderInfo['priceTotal'], GiftCardCheckoutPage::$priceTotal);
			$I->see($orderInfo['priceDiscount'], GiftCardCheckoutPage::$priceDiscount);
			$I->see($orderInfo['priceEnd'], GiftCardCheckoutPage::$priceEnd);

		}
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->doFrontendLogout();
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
	 * @throws \Exception
	 */
	public function checkoutWithDiscount($productName, $categoryName, $subTotal, $discount, $total)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$selectorSuccess, 60, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{

		}
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->see($subTotal, FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->see($discount, FrontEndProductManagerJoomla3Page::$priceDiscount);
		$I->see($total, FrontEndProductManagerJoomla3Page::$priceEnd);
	}

	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @param $ShippingRate
	 * @param $Total
	 *
	 * @throws \Exception
	 */
	public function checkoutSpecificShopperGroup($userName, $password, $productName, $categoryName, $ShippingRate, $Total)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 10);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->wait(1);
		try
		{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10,FrontEndProductManagerJoomla3Page::$selectorMessage);
		}catch (\Exception $addToCart)
		{
			$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		}
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);

		try{
			$I->waitForText($ShippingRate, 5, FrontEndProductManagerJoomla3Page::$shippingRate);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
			$I->waitForText($ShippingRate, 60, FrontEndProductManagerJoomla3Page::$shippingRate);
		}

		$I->waitForText($Total, 10, FrontEndProductManagerJoomla3Page::$priceEnd);
	}

	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $subtotal
	 * @param $Total
	 *
	 * @throws \Exception
	 */
	public function checkProductInsideStockRoom($productName, $categoryName, $subtotal, $Total)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 10);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		}

		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alterOutOfStock, 60, FrontEndProductManagerJoomla3Page::$selectorError);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$priceTotal, 30);
		$I->see($subtotal, FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->see($Total, FrontEndProductManagerJoomla3Page::$priceEnd);
	}

	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $userEmail
	 *
	 * @throws \Exception
	 */
	public function checkoutQuotation($productName, $categoryName, $userEmail)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		}
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$quotation);
		$I->click(FrontEndProductManagerJoomla3Page::$addQuotation);
		$I->acceptPopup();
		$I->fillField(FrontEndProductManagerJoomla3Page::$userEmail, $userEmail);
		$I->click(FrontEndProductManagerJoomla3Page::$addQuotation);
		$I->see(FrontEndProductManagerJoomla3Page::$addQuotationSuccess, FrontEndProductManagerJoomla3Page::$alertMessageDiv);
	}

	/**
	 * @param $categoryName
	 * @param $productFirst
	 * @param $productSecond
	 *
	 * @throws \Exception
	 */
	public function comparesProducts($categoryName, $productFirst, $productSecond)
	{
		$I       = $this;
		$usePage = new FrontEndProductManagerJoomla3Page();
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productFirst), 30);
		$I->click($productFrontEndManagerPage->product($productFirst));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCompare, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCompare);

		try{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$addToCompare);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$addToCompare);
		}
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$showProductToCompare, 30);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$showProductToCompare);
		$I->wait(0.5);

		try
		{
			$I->waitForElement($usePage->productName($productFirst), 5);
		}catch (\Exception $e)
		{
			$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
			$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
			$I->click($productFrontEndManagerPage->productCategory($categoryName));
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
			$I->waitForElement($productFrontEndManagerPage->product($productFirst), 30);
			$I->click($productFrontEndManagerPage->product($productFirst));
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCompare, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$addToCompare);
		}

		$I->wait(1);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->wait(0.5);
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productSecond), 30);
		$I->wait(0.5);
		$I->click($productFrontEndManagerPage->product($productSecond));
		$I->wait(0.5);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCompare, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCompare);
		$I->wait(1);

	}

	/**
	 * @param        $userName
	 * @param        $password
	 * @param        $productName
	 * @param        $categoryName
	 * @param        $subtotal
	 * @param        $total
	 * @param        $customerInformation
	 * @param        $function
	 * @param string $account
	 *
	 * Method test one page step checkout is business or private
	 *
	 * @throws \Exception
	 */
	public function onePageCheckout($userName, $password, $productName,$categoryName,$subtotal,$total,$customerInformation, $function, $account = 'yes')
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart,30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

		try{
			$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage,5, GiftCardCheckoutPage::$selectorSuccess);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		}
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElement(['link' => $productName],30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);

		if ($account == 'yes')
		{
			$I->comment($userName);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$idAddAccount, 30);
			$I->executeJS("jQuery('#createaccount').click()");
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idUserNameOneStep, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idUserNameOneStep, $userName);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$idPassOneStep, 30);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idPassOneStep, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idPassOneStep, $password);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idPassConfirmOneStep, 30);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$idPassConfirmOneStep, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idPassConfirmOneStep, $password);
		}
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(2);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}

				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idBusinessNumber, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyLastName, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idEanNumber, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
				$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));

				try
				{
					$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			}else
			{
				$I->comment('checkout with private');
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressEmail, 60);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
				$I->waitForText($total, 30, FrontEndProductManagerJoomla3Page::$priceEnd);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
				$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));

				try
				{
					$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				}

				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
			}
	}

	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @param $ShippingRate
	 * @param $Total
	 *
	 * @throws \Exception
	 */
	public function checkoutOnePageWithLogin($userName, $password, $productName, $categoryName, $ShippingRate, $Total)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 10);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

		try{
			$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage, 60, GiftCardCheckoutPage::$selectorSuccess);
		}catch (\Exception $e)
		{
		}

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		  try{
			  $I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		  }catch (Exception $e)
		  {
			  $I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		  }

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressAddress);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
		$I->click(FrontEndProductManagerJoomla3Page::$buttonSave);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 30, null);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForText($Total, 30, FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
	}

	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @param $subTotal
	 * @param $vatPrice
	 * @param $total
	 *
	 * @throws \Exception
	 */
	public function testProductWithVatCheckout($userName, $password, $productName, $categoryName, $subTotal, $vatPrice, $total)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

		try {
			$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		} catch (\Exception $e) {
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		}
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		try
		{
			$I->waitForText($total, 10);
		} catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		}
		$I->waitForText($subTotal, 30);
		$I->waitForText($vatPrice, 30);
		$I->waitForText($total, 30);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->waitfortext(FrontEndProductManagerJoomla3Page::$orderReceipt, 30);
		$I->seeElement(['link' => $productName]);
	}

	/**
	 * @param       $userName
	 * @param       $product
	 * @param array $attributes
	 * @param       $category
	 * @param       $subTotal
	 * @param       $vatPrice
	 * @param       $total
	 * @param       $shipping
	 *
	 * @throws \Exception
	 */
	public function checkoutAttributeShopperUser($userName, $product,$attributes = array(), $category, $subTotal, $vatPrice, $total, $shipping)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $userName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($category));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($product));

		$length = count($attributes);
		$I->comment("show $length");

		$usePage = new FrontEndProductManagerJoomla3Page();

		for($x = 0; $x <$length; $x ++)
		{
			$attribute  = $attributes[$x];
			$I->waitForElement($usePage->attributeDropdown($x +1), 30);
			$I->click($usePage->attributeDropdown($x +1));
			$I->waitForElementVisible($usePage-> attributeDropdownSearch($x +1), 30);
			$I->fillField($usePage->attributeDropdownSearch($x +1), $attribute['attributeName']);
			$I->pressKey($usePage->attributeDropdownSearch($x +1), \Facebook\WebDriver\WebDriverKeys::ENTER);

		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

		$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage,10, GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $product]);
		$I->see($subTotal, FrontEndProductManagerJoomla3Page::$priceTotal);
		$I->waitForText($vatPrice, 30, FrontEndProductManagerJoomla3Page::$priceVAT);
		$I->waitForText($total, 30, FrontEndProductManagerJoomla3Page::$priceEnd);
	}

	/**
	 * @param $categoryparent
	 * @param $categorychild
	 * @param $productname
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkDiscountWithCategoryChild($categoryparent, $categorychild, $productname, $total)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryparent));
		$I->click($productFrontEndManagerPage->productCategory($categorychild));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productname), 30);
		$I->click($productFrontEndManagerPage->product($productname));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$selectorSuccess, 60, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{
		}
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productname]);
		$I->see($total, FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->waitForText($total, 30 ,FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		try {
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$billingFinal, 30);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		}catch (\Exception $e)
		{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
		}
		$I->waitForElement($productFrontEndManagerPage->product($productname), 30);
		$I->seeElement($productFrontEndManagerPage->product($productname));
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText($total, 30 ,FrontEndProductManagerJoomla3Page::$totalFinalCheckout);
		$I->see($total, FrontEndProductManagerJoomla3Page::$totalFinalCheckout);
	}

	/**
	 * @param $productname
	 * @param $categoryname
	 * @param $discountprice
	 * @param $quantity
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkoutProductwithAddPrice($productname, $categoryname, $discountprice, $quantity, $total)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryname));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productname), 30);
		$I->click($productFrontEndManagerPage->product($productname));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$selectorSuccess, 60 , FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{
		}
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productname]);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$quantityFieldCart);
		$I->click(CheckoutChangeQuantityProductPage::$quantityField);
		$I->pressKey(CheckoutChangeQuantityProductPage::$quantityField, \Facebook\WebDriver\WebDriverKeys::BACKSPACE);
		$quantity = str_split($quantity);
		foreach ($quantity as $char) {
			$I->pressKey(CheckoutChangeQuantityProductPage::$quantityField, $char);
		}
		$I->waitForElement(CheckoutChangeQuantityProductPage::$updateCartButton, 30);
		$I->click(CheckoutChangeQuantityProductPage::$updateCartButton);
		$I->waitForText($total, 30, FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->see($total, FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		try {
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$billingFinal, 30);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		}catch (\Exception $e)
		{
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
			$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
		}
		$I->waitForElement($productFrontEndManagerPage->product($productname), 30);
		$I->seeElement($productFrontEndManagerPage->product($productname));
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->see($total, FrontEndProductManagerJoomla3Page::$totalFinalCheckout);
		$I->waitForText($total, 30 ,FrontEndProductManagerJoomla3Page::$totalFinalCheckout);
	}

	/**
	 * @param $addressDetail
	 * @param $giftCardName
	 * @param $userName
	 * @param $password
	 * @param $firstName
	 * @param $email
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function checkoutGiftCard($addressDetail, $giftCardName, $userName, $password, $firstName, $email)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(GiftCardCheckoutPage::$pageCart);
		$I->waitForElement(['link' => $giftCardName], 60);
		$I->click(['link' => $giftCardName]);
		$I->waitForElement(GiftCardCheckoutPage::$reciverName);
		$I->fillField(GiftCardCheckoutPage::$reciverName, $firstName);
		$I->fillField(GiftCardCheckoutPage::$reciverEmail, $email);
		$I->waitForElementVisible(GiftCardCheckoutPage::$addToCart, 30);
		$I->click(GiftCardCheckoutPage::$addToCart);
		$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage, 60, GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$alertSuccessMessage, GiftCardCheckoutPage::$selectorSuccess);
		$I->amOnPage(GiftCardCheckoutPage::$cartPageUrL);
		$I->seeElement(['link' => $giftCardName]);

		$I->click(GiftCardCheckoutPage::$checkoutButton);
		$I->waitForElement(GiftCardCheckoutPage::$paymentPayPad, 30);
		$I->click(GiftCardCheckoutPage::$paymentPayPad);

		$I->click(GiftCardCheckoutPage::$checkoutButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $addressDetail['email']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $addressDetail['firstName']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $addressDetail['lastName']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $addressDetail['address']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $addressDetail['postalCode']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $addressDetail['city']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $addressDetail['phone']);
		$I->click(GiftCardCheckoutPage::$buttonSave);

		$I->waitForElement(GiftCardCheckoutPage::$addressLink, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$paymentPayPad, 30);
		$I->click(GiftCardCheckoutPage::$paymentPayPad);
		$I->waitForElementVisible(GiftCardCheckoutPage::$checkoutButton, 30);
		$I->click(GiftCardCheckoutPage::$checkoutButton);

		$I->waitForElementVisible(GiftCardCheckoutPage::$acceptTerms, 30);
		$I->click(GiftCardCheckoutPage::$acceptTerms);
		$I->waitForElementVisible(GiftCardCheckoutPage::$checkoutFinalStep, 30);
		$I->click(GiftCardCheckoutPage::$checkoutFinalStep);
	}
}
