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
 */
class CheckoutOnFrontEnd extends ProductCheckoutManagerJoomla3Steps
{
	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @param $price1
	 * @param $price2
	 * @param $total
	 * @param array $attributes
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function testProductAttributeWithVatCheckout($userName, $password, $productName, $categoryName, $price1, $price2, $total, $attributes = array())
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;

		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$usePage = new FrontEndProductManagerJoomla3Page();
		$length = count($attributes);
		$I->wantToTest($length);

		for($x = 0;  $x < $length; $x ++ )
		{
			$attribute  = $attributes[$x];
			$I->waitForElementVisible($usePage->attributeDropdown(1), 30);
			$I->click($usePage->attributeDropdown(1));
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$attributeSearchFirst, 30);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$attributeSearchFirst, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$attributeSearchFirst, $attribute['attributeName']);
			$I->wait(0.5);
			$I->pressKey(FrontEndProductManagerJoomla3Page::$attributeSearchFirst, \Facebook\WebDriver\WebDriverKeys::ENTER);
			$I->wait(0.5);
			$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

			try
			{
				$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, FrontEndProductManagerJoomla3Page::$selectorSuccess);
			}
			catch (\Exception $e)
			{
				$I->click($usePage->attributeDropdown(1));
				$I->fillField(FrontEndProductManagerJoomla3Page::$attributeSearchFirst, $attribute['attributeName']);
				$I->wait(0.5);
				$I->pressKey(FrontEndProductManagerJoomla3Page::$attributeSearchFirst, \Facebook\WebDriver\WebDriverKeys::ENTER);
				$I->wait(0.5);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
			}
		}

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$quantity1 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$quantity1);
		$quantity2 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$quantity2);
		$total =$currencySymbol.((int)$quantity1*$price1 +$price2*(int)$quantity2).$decimalSeparator.$NumberZero;
		$subTotal = $currencySymbol. (((int)$quantity1*$price1 +$price2*(int)$quantity2)*1.25).$decimalSeparator.$NumberZero;
		$vatPrice = $currencySymbol. (((int)$quantity1*$price1 +$price2*(int)$quantity2)*0.25).$decimalSeparator.$NumberZero;
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		try
		{
			$I->waitForText($total, 30);
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
	 * @since 2.1.0
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
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal, 30);
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

	/**
	 * @param $addressDetail
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function addressInformationCompany($addressDetail)
	{
		$I = $this;
		$I->comment('Business');
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);

		try
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $addressDetail['email']);
		}catch (\Exception $e)
		{
			$I->reloadPage();
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $addressDetail['email']);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $addressDetail['companyName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idBusinessNumber, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $addressDetail['vatNumber']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $addressDetail['firstName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyLastName, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $addressDetail['lastName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $addressDetail['address']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $addressDetail['postalCode']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $addressDetail['city']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $addressDetail['phone']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idEanNumber, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $addressDetail['eanNumber']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$countryCode1, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$countryCode1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$searchCountryInput, 2);
		$I->fillField(FrontEndProductManagerJoomla3Page::$searchCountryInput, $addressDetail['country']);
		$I->pressKey(FrontEndProductManagerJoomla3Page::$searchCountryInput, \Facebook\WebDriver\WebDriverKeys::ENTER);
	}

	/**
	 * @param $user
	 * @param $productName
	 * @param $categoryName
	 * @param $price
	 * @param $vatAmount
	 * @param $function
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function testProductWithUserMultiGroup($user, $productName, $categoryName, $price, $vatAmount, $function)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;

		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$total = $currencySymbol.($price + ($price * $vatAmount)).$decimalSeparator.$NumberZero;
		$subTotal = $currencySymbol.$price.$decimalSeparator.$NumberZero;
		$vatPrice = $currencySymbol.($price*$vatAmount).$decimalSeparator.$NumberZero;

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30);

		switch ($function)
		{
			case 'Login':
				$I->doFrontEndLogin($user['userName'], $user['password']);
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
				$I->seeElement(['link' => $productName]);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText($subTotal, 30);
				$I->waitForText($vatPrice, 30);
				$I->waitForText($total, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$acceptTerms);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
				$I->waitForText($subTotal, 30);
				$I->waitForText($vatPrice, 30);
				$I->waitForText($total, 30);
				$I->seeElement(['link' => $productName]);
				$I->doFrontendLogout();
				break;

			case 'NoOnePage':
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
				$I->seeElement(['link' => $productName]);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$newCustomerSpan);
				$I->wait(1);

				if($user['shopperGroup'] == 'Default Private')
				{
					$I->addressInformation($user);
					$I->shippingInformation($user);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$proceedButtonId, 30);
					$I->click(FrontEndProductManagerJoomla3Page::$proceedButtonId);
					$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal, 30);
					$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
					$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
					$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
				}else
				{
					$I->addressInformationCompany($user);
					$I->shippingInformation($user);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$proceedButtonId, 30);
					$I->click(FrontEndProductManagerJoomla3Page::$proceedButtonId);
					$I->waitForElement(FrontEndProductManagerJoomla3Page::$billingFinal, 30);
					$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
					$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
					$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
				}

				$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
				$I->seeElement($productFrontEndManagerPage->product($productName));
				$I->waitForText($subTotal, 30);
				$I->waitForText($vatPrice, 30);
				$I->waitForText($total, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
				$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
				$I->waitForText($subTotal, 30);
				$I->waitForText($vatPrice, 30);
				$I->waitForText($total, 30);
				break;

			case 'OnePage':
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
				$I->seeElement(['link' => $productName]);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 30);

				if($user['shopperGroup'] == 'Default Private')
				{
					$I->addressInformation($user);
				}else
				{
					$I->addressInformationCompany($user);
				}

				$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
				$I->scrollTo($productFrontEndManagerPage->product($productName));
				$I->seeElement($productFrontEndManagerPage->product($productName));
				$I->waitForText($subTotal, 30);
				$I->waitForText($vatPrice, 30);
				$I->waitForText($total, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
				$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
				$I->waitForText($subTotal, 30);
				$I->see($subTotal);
				$I->waitForText($vatPrice, 30);
				$I->see($vatPrice);
				$I->waitForText($total, 30);
				$I->see($total);
				break;
		}
	}

	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $price
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkOutWithoutAcceptTermsConditions($productName, $categoryName, $price)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;

		for($b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$total = $currencySymbol.$price.$decimalSeparator.$NumberZero;
		$subTotal = $currencySymbol.$price.$decimalSeparator.$NumberZero;

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
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 30);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->scrollTo($productFrontEndManagerPage->product($productName));
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->waitForText($subTotal, 30);
		$I->waitForText($total, 30);
		$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
		$I->waitForText($subTotal, 30);
		$I->waitForText($total, 30);
	}

	/**
	 * @param $productName
	 * @param $manufacturerMenuItem
	 * @param $price
	 * @param $addressDetail
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function CheckoutProductInManufacturerDetail($productName, $manufacturerMenuItem, $price, $addressDetail)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;

		for($b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$total = $currencySymbol.$price.$decimalSeparator.$NumberZero;
		$subTotal = $currencySymbol.$price.$decimalSeparator.$NumberZero;

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($manufacturerMenuItem));
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName),30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, AdminJ3Page::$selectorSuccess);
		$I->see(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, AdminJ3Page::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 30);
		$I->addressInformation($addressDetail);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->scrollTo($productFrontEndManagerPage->product($productName));
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->waitForText($subTotal, 30);
		$I->waitForText($total, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
		$I->waitForText($subTotal, 30);
		$I->waitForText($total, 30);
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $price
	 * @param $wrappingName
	 * @param $wrappingPrice
	 * @param $addressDetail
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function checkoutProductHaveWrapping($categoryName, $productName, $price, $wrappingName, $wrappingPrice, $addressDetail)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$numberZero = null;

		for($b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$numberZero = $numberZero."0";
		}

		$total = $currencySymbol.$price.$decimalSeparator.$numberZero;
		$subTotal = $currencySymbol.($price + $wrappingPrice).$decimalSeparator.$numberZero;
		$wrappingPrice = $currencySymbol.$wrappingPrice.$decimalSeparator.$numberZero;

		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, AdminJ3Page::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->waitForText($wrappingName, 30);
		$I->waitForText($wrappingPrice, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 60);
		$I->addressInformation($addressDetail);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->scrollTo($productFrontEndManagerPage->product($productName));
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->waitForText($subTotal, 30);
		$I->waitForText($wrappingPrice, 30);
		$I->waitForText($total, 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 10, FrontEndProductManagerJoomla3Page::$orderReceiptTitle);
		$I->seeElement($productFrontEndManagerPage->finalCheckout($productName));
		$I->waitForText($subTotal, 30);
	}
}
