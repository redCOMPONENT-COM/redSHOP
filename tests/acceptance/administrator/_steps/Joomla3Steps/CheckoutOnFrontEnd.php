<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;

/**
 * Class ProductCheckoutFrontEndManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.0
 */class CheckoutOnFrontEnd extends ProductCheckoutManagerJoomla3Steps
{
	/**
	 * @param             $userName
	 * @param             $password
	 * @param             $productName
	 * @param             $categoryName
	 * @param             $subTotal
	 * @param             $vatPrice
	 * @param             $total
	 * @param array       $attributes
	 *
	 * @throws \Exception
	 */
	public function testProductAttributeWithVatCheckout($userName, $password, $productName, $categoryName, $price1, $price2, $total, $attributes = array())
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(\ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(\ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(\ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;
		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$usePage = new \FrontEndProductManagerJoomla3Page();
		$length = count($attributes);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$attribute  = $attributes[$x];
			$I->waitForElementVisible($usePage->attributeDropdown(1), 30);
			$I->click($usePage->attributeDropdown(1));
			$I->waitForElement(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, 30);
			$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, 30);
			$I->fillField(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, $attribute['attributeName']);
			$I->wait(0.5);
			$I->pressKey(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, \Facebook\WebDriver\WebDriverKeys::ENTER);
			$I->wait(0.5);
			$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
			$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
			$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
			try
			{
				$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, \FrontEndProductManagerJoomla3Page::$selectorSuccess);
			}
			catch (\Exception $e)
			{
				$I->click($usePage->attributeDropdown(1));
				$I->fillField(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, $attribute['attributeName']);
				$I->wait(0.5);
				$I->pressKey(\FrontEndProductManagerJoomla3Page::$attributeSearchFirst, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->wait(0.5);
				$I->waitForElement(\FrontEndProductManagerJoomla3Page::$addToCart, 30);
				$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
			}
		}

		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$quantity1 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$quantity1);
		$quantity2 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$quantity2);
		$total =$currencySymbol.((int)$quantity1*$price1 +$price2*(int)$quantity2).$decimalSeparator.$NumberZero;
		$subTotal = $currencySymbol. (((int)$quantity1*$price1 +$price2*(int)$quantity2)*1.25).$decimalSeparator.$NumberZero;
		$vatPrice = $currencySymbol. (((int)$quantity1*$price1 +$price2*(int)$quantity2)*0.25).$decimalSeparator.$NumberZero;
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		try
		{
			$I->waitForText($total, 30);
		} catch (\Exception $e)
		{
			$I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
		}
		$I->waitForText($subTotal, 30);
		$I->waitForText($vatPrice, 30);
		$I->waitForText($total, 30);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
		$I->seeElement(['link' => $productName]);
		$I->doFrontendLogout();
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @since 2.1.2
	 * @throws Exception
	 */
	public function addToCart($categoryName, $productName)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		}
	}

	/**
	 * @param $categoryName
	 * @param $titleSEO
	 * @param $keySEO
	 * @param $descriptionSEO
	 * @param $productName
	 * @param $titleSEOPD
	 * @param $headingSEO
	 * @throws Exception
	 */
	public function checkSEOCategoryProduct($categoryName,$titleSEO, $keySEO, $descriptionSEO, $productName, $titleSEOPD,$headingSEO,$customerInformation)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->waitForText($categoryName,30);
		$usePage = new CategoryPage();
		$I->waitForElementVisible($usePage->imageCategory($categoryName),30);
		$I->click($usePage->imageCategory($categoryName));
		$I->seeInPageSource($titleSEO);
		$I->seeInPageSource($keySEO);
		$I->seeInPageSource($descriptionSEO);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForText($productName,30, ProductManagerPage::$h1);
		$I->seeInPageSource($titleSEOPD);
		$I->seeInPageSource($headingSEO);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		try
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		}
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $checkoutURL);
		$I->comment('checkout with private');
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $productNameAccessories
	 * @param $function
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function ConfigurationProductAccessoryOnCart($categoryName, $productName, $productNameAccessories, $function)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForText($categoryName, 30);
		$I->click($categoryName);
		$I->waitForText($productNameAccessories, 30);
		$I->click($productNameAccessories);
		$I->waitForText($productNameAccessories, 30);
		$I->see($productName);
		$I->waitForElementVisible(ConfigurationPage::$addAccessory, 30);
		$I->click(ConfigurationPage::$addAccessory);
		$I->waitForElementVisible(ConfigurationPage::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

		switch ($function)
		{
			case 'Yes':
				$I = $this;
				$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5);
				$I->amOnPage(ProductManagerPage::$cartPageUrL);
				$I->waitForText($productNameAccessories, 30, FrontEndProductManagerJoomla3Page::$nameProduct1OnCart);
				$I->see($productName);
				$I->waitForText($productName, 30, FrontEndProductManagerJoomla3Page::$nameProduct2OnCart);
				$I->see($productNameAccessories);
				break;
			case 'No':
				$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 5);
				$I->amOnPage(ProductManagerPage::$cartPageUrL);
				$I->waitForText($productName, 30, FrontEndProductManagerJoomla3Page::$nameProduct1OnCart);
				$I->see($productName);
				$I->waitForText($productNameAccessories, 30, FrontEndProductManagerJoomla3Page::$nameProduct1OnCart);
				$I->see($productNameAccessories);
				break;
		}
	}

	/**
	 * @param $addressDetail
	 * @param $shipmentDetail
	 * @param $productName
	 * @param $categoryName
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkOutWithBankTransfer($addressDetail, $shipmentDetail, $productName, $categoryName)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, AdminJ3Page::$selectorSuccess);
		$I->see(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, AdminJ3Page::$selectorSuccess);
		$I->amOnPage(GiftCardCheckoutPage::$cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(AdminJ3Page::$checkoutButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$newCustomerSpan,30);
		$I->click(FrontEndProductManagerJoomla3Page::$newCustomerSpan);

		$I->addressInformation($addressDetail);
		$I->shippingInformation($shipmentDetail);
		$I->click(FrontEndProductManagerJoomla3Page::$proceedButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->click(AdminJ3Page::$checkoutButton);

		try
		{
			$I->waitForElement($productFrontEndManagerPage->product($productName),5);
		}catch (\Exception $e)
		{
			$I->click(AdminJ3Page::$checkoutButton);
			$I->waitForElement($productFrontEndManagerPage->product($productName),5);
		}

		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
	}
}
