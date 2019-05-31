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
	 * @since 2.2.0
	 * @throws Exception
	 */
	public function addToCart($categoryName,$productName)
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
	}

	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @param $missing
	 * @param $function
	 *
	 * @since 2.2.0
	 * @throws \Exception
	 */
	public function onePageCheckoutMissing($productName, $categoryName,$customerInformation, $missing, $function)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
		if ($missing == 'user')
		{
			if ($function == 'createAccount')
			{
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idAddAccount, 30);
				$I->executeJS("jQuery('#createaccount').click()");
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idUserNameOneStep, 30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$idUserNameOneStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idUserNameOneStep, 30);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterUser, 30, FrontEndProductManagerJoomla3Page:: $locatorMessageEnterUser);
				$I->waitForText(FrontEndProductManagerJoomla3Page:: $messageFieldRequired, 30, FrontEndProductManagerJoomla3Page:: $locatorMessagePassword);
				$I->waitForText(FrontEndProductManagerJoomla3Page:: $messageFieldRequired, 30, FrontEndProductManagerJoomla3Page::$locatorMessageConfirmPassword);
			}
			if ($function == 'private')
			{
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterEmail, 30, FrontEndProductManagerJoomla3Page::locatorMessagePrivate("email1"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterFirstName, 30, FrontEndProductManagerJoomla3Page::locatorMessagePrivate("firstname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterLastName, 30, FrontEndProductManagerJoomla3Page::locatorMessagePrivate("lastname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterAddress, 30, FrontEndProductManagerJoomla3Page::locatorMessagePrivate("address"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterCity, 30, FrontEndProductManagerJoomla3Page::locatorMessagePrivate("city"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, FrontEndProductManagerJoomla3Page::locatorMessagePrivate("phone"));
			}
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(1);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
                $I->wait(0.5);
                $I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterEmail, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("email1"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterCompanyName, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("company_name"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterFirstName, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("firstname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterLastName, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("lastname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterAddress, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("address"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterCity, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("city"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageFieldRequired, 30, FrontEndProductManagerJoomla3Page::$locatorMessageEAN);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, FrontEndProductManagerJoomla3Page::locatorMessageCompany("phone"));
			}
		}
		if ($missing == 'acceptTerms')
		{
			if ($function == 'private')
			{
				$I->comment('checkout with private');
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 30,FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
			}
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(1);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 30, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
			}
		}
		if ($missing == 'payment')
		{
			if ($function == 'private')
			{
				$I->comment('checkout with private');
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageSelectPayment, 30, FrontEndProductManagerJoomla3Page::$locatorMessagePayment);
			}
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(1);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageSelectPayment, 30, FrontEndProductManagerJoomla3Page::$locatorMessagePayment);
			}
		}
		if ($missing == 'wrongEmail')
		{
			if ($function == 'private')
			{
				$I->comment('checkout with private');
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEmailInvalid,30, FrontEndProductManagerJoomla3Page:: locatorMessagePrivate("email1"));
			}
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(1);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage,30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);

				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEmailInvalid, 30, FrontEndProductManagerJoomla3Page:: locatorMessageCompany("email1"));
			}
		}
		if ($missing == 'wrongPhone')
		{
			if ($function == 'private')
			{
				$I->comment('checkout with private');
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, FrontEndProductManagerJoomla3Page:: locatorMessagePrivate("phone"));
			}
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(1);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);

				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, FrontEndProductManagerJoomla3Page:: locatorMessageCompany("phone"));
			}
		}
		if ($missing == 'wrongEAN')
		{
			if ($function == 'business') {
				$I->comment('Business');
				$I->wantToTest($function);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
				$I->wait(1);
				$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
				try
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}catch (\Exception $e)
				{
					$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				}
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);

				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, $customerInformation['companyName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idBusinessNumber, $customerInformation['businessNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyFirstName, $customerInformation['firstName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyLastName, $customerInformation['lastName']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyAddressOnePage, $customerInformation['address']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyZipCodeOnePage, $customerInformation['postalCode']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyCityOnePage, $customerInformation['city']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyPhoneOnePage, $customerInformation['phone']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idEanNumber, $customerInformation['eanNumber']);
				$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEAN, 30, FrontEndProductManagerJoomla3Page:: $locatorMessageEAN);
			}
		}
	}
}

