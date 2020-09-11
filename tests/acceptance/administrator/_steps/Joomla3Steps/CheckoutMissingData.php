<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CheckoutMissingData
 * @package  CheckoutMissingData
 * @since    2.1.2
 */
class CheckoutMissingData extends CheckoutOnFrontEnd
{
	/**
	 * @param $customerInformation
	 * @since 2.1.2
	 * @throws \Exception
	 */
	public function fillInformationBusiness($customerInformation)
	{
		$I = $this;
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);

		try
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
		}catch (\Exception $e)
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioIDCompany, 30);
			$I->wait(0.5);
			$I->click(FrontEndProductManagerJoomla3Page::$radioIDCompany);
			$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);
			$I->fillField(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, $customerInformation['email']);
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
	}

	/**
	 * @param $customerInformation
	 * @since 2.1.2
	 * @throws \Exception
	 */
	public function fillInformationPrivate($customerInformation)
	{
		$I = $this;
		$I->comment('checkout with private');
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 60);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressFirstName, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressLastName, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressAddress, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressPostalCode, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressCity, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressPhone, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
	}

	/**
	 * @param $shippingAddress
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function fillShippingAddress($shippingAddress)
	{
		$I = $this;
		$I->comment('Add Shipping Address');
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingFirstName, $shippingAddress['firstName1']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingLastName, $shippingAddress['lastName1']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingAddress, $shippingAddress['address1']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingPostalCode, $shippingAddress['postalCode1']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingCity, $shippingAddress['city1']);
		$I->fillField(FrontEndProductManagerJoomla3Page::$shippingPhone, $shippingAddress['phone1']);
	}

	/**
	 * @param $productName
	 * @param $customerInformation
	 * @param $missing
	 *
	 * @since 2.1.2
	 * @throws \Exception
	 */
	public function onePageCheckoutMissingWithUserBusiness($productName, $customerInformation, $missing)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
		$I->comment('Business');
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
		$I->wait(1);
		$I->click(FrontEndProductManagerJoomla3Page::$radioCompany);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyNameOnePage, 30);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$idCompanyEmailOnePage, 30);

		switch ($missing)
		{
			case 'user':
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterEmail, 30, $productFrontEndManagerPage->locatorMessageCompany("email1"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterCompanyName, 30, $productFrontEndManagerPage->locatorMessageCompany("company_name"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterFirstName, 30, $productFrontEndManagerPage->locatorMessageCompany("firstname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterLastName, 30, $productFrontEndManagerPage->locatorMessageCompany("lastname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterAddress, 30, $productFrontEndManagerPage->locatorMessageCompany("address"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterCity, 30, $productFrontEndManagerPage->locatorMessageCompany("city"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageFieldRequired, 30, FrontEndProductManagerJoomla3Page::$locatorMessageEAN);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, $productFrontEndManagerPage->locatorMessageCompany("phone"));
				break;

			case 'acceptTerms':
				$I->fillInformationBusiness($customerInformation);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 30, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
				break;

			case 'payment':
				$I->fillInformationBusiness($customerInformation);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageSelectPayment, 10, FrontEndProductManagerJoomla3Page::$locatorMessagePayment);

				break;

			case 'wrongEmail':
				$I->fillInformationBusiness($customerInformation);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEmailInvalid, 30, $productFrontEndManagerPage->locatorMessageCompany("email1"));
				break;

			case 'wrongPhone':
				$I->fillInformationBusiness($customerInformation);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, $productFrontEndManagerPage->locatorMessageCompany("phone"));
				break;

			case 'wrongEAN':
				$I->fillInformationBusiness($customerInformation);
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
				break;
		}
	}

	/**
	 * @param $productName
	 * @param $customerInformation
	 * @param $missing
	 *
	 * @since 2.1.2
	 * @throws \Exception
	 */
	public function onePageCheckoutMissingWithUserPrivate($productName, $customerInformation, $missing)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElement(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);

		switch ($missing)
		{
			case 'user':
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 60);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterEmail, 30, $productFrontEndManagerPage->locatorMessagePrivate("email1"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterFirstName, 30, $productFrontEndManagerPage->locatorMessagePrivate("firstname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterLastName, 30, $productFrontEndManagerPage->locatorMessagePrivate("lastname"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterAddress, 30, $productFrontEndManagerPage->locatorMessagePrivate("address"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterCity, 30, $productFrontEndManagerPage->locatorMessagePrivate("city"));
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, $productFrontEndManagerPage->locatorMessagePrivate("phone"));
				break;

			case 'acceptTerms':
				$I->fillInformationPrivate($customerInformation);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
				$I->wait(0.5);
				$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 30, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
				break;

			case 'payment':
				$I->fillInformationPrivate($customerInformation);
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

				try
				{
					$I->waitForText(FrontEndProductManagerJoomla3Page::$messageSelectPayment, 10, FrontEndProductManagerJoomla3Page::$locatorMessagePayment);
				}
				catch (Exception $e)
				{
					$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
					$I->wait(0.5);
					$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
					$I->waitForText(FrontEndProductManagerJoomla3Page::$messageSelectPayment, 30, FrontEndProductManagerJoomla3Page::$locatorMessagePayment);
				}
				break;

			case 'wrongEmail':
				$I->comment('checkout with private');
				$I->fillInformationPrivate($customerInformation);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEmailInvalid, 30, $productFrontEndManagerPage->locatorMessagePrivate("email1"));
				break;

			case 'wrongPhone':
				$I->fillInformationPrivate($customerInformation);
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
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageEnterPhone, 30, $productFrontEndManagerPage->locatorMessagePrivate("phone"));
				break;
		}
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $voucherCode
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkoutWithVoucherInvalid($categoryName, $productName, $voucherCode)
	{
		$I = $this;
		$I->addToCart($categoryName, $productName);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->fillField(GiftCardCheckoutPage::$couponInput, $voucherCode);
		$I->click(GiftCardCheckoutPage::$couponButton);
		$I->waitForText(GiftCardCheckoutPage::$messageInvalid, 10, GiftCardCheckoutPage::$selectorError);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonEmptyCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonEmptyCart);
	}
}
