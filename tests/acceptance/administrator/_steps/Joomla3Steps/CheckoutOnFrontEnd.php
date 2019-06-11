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
	 * @param $pluginName
	 * @param $accessId
	 * @param $transactionKey
	 * @param $md5Key
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function configAuthorizeDPMPlugin($pluginName, $accessId, $transactionKey, $md5Key)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem($pluginName);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 60);
		$I->checkExistenceOf($pluginName);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page:: $searchResultRow);
		$I->click($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldAccessId, 60);
		$I->fillField(PluginManagerJoomla3Page:: $fieldAccessId , $accessId);
		$I->fillField(PluginManagerJoomla3Page:: $fieldTransactionID, $transactionKey);
		$I->fillField(PluginManagerJoomla3Page:: $fieldMd5Key , $md5Key);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldTestMode, 60);
		$I->click( PluginManagerJoomla3Page::$fieldTestMode);

		// Choosing Test Mode to Yes
		$I->waitForElementVisible(PluginManagerJoomla3Page::$optionTestModeYes, 60);
		$I->click(PluginManagerJoomla3Page::$optionTestModeYes);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('Plugin saved.', ['id' => 'system-message-container']);
	}

	/**
	 * @param $user
	 * @param $password
	 * @param $checkoutAccountDetail
	 * @param string $productName
	 * @param string $categoryName
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function checkoutProductWithAuthorizeDPMPayment( $user, $password, $checkoutAccountDetail, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($user,$password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 60);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 60, FrontEndProductManagerJoomla3Page:: $selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible("//h3[contains(text(),'Payment Method')]");
		$I->click(FrontEndProductManagerJoomla3Page::$paymentAuthorizeDPM);
		$I->waitForElement(FrontEndProductManagerJoomla3Page:: $cardName, 60);
		$I->fillField(FrontEndProductManagerJoomla3Page:: $cardName, $checkoutAccountDetail['customerName']);
		$I->fillField(FrontEndProductManagerJoomla3Page:: $cardNumber, $checkoutAccountDetail['debitCardNumber']);
		$I->fillField( FrontEndProductManagerJoomla3Page::$cardCode, $checkoutAccountDetail['cvv']);
		$I->selectOption(FrontEndProductManagerJoomla3Page:: $selectExpireMonth,$checkoutAccountDetail['cardExpiryMonth']);
		$I->selectOption(FrontEndProductManagerJoomla3Page:: $selectExpireYear, $checkoutAccountDetail['cardExpiryMonth']);
		$I->click(FrontEndProductManagerJoomla3Page::$typeCard);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 60);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->dontSeeInCurrentUrl('checkout');
	}
}