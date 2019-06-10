<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CheckoutWithPluginExtension
 * @since 2.1.2
 */
class CheckoutWithPluginExtension extends CheckoutOnFrontEnd
{
	/**
	 * @param $pluginName
	 * @param $vendorID
	 * @param $secretWord
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function config2CheckoutPlugin($pluginName, $vendorID, $secretWord)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page:: $URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->seeElement(PluginManagerJoomla3Page:: $searchResultRow);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page:: $searchResultRow);
		$I->click($pluginName);
		$I->waitForElementVisible( PluginManagerJoomla3Page:: $vendorID ,30);
		$I->fillField( PluginManagerJoomla3Page:: $vendorID , $vendorID);
		$I->fillField(PluginManagerJoomla3Page::$secretWords, $secretWord);
		$I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param string $userName
	 * @param string $password
	 * @param $checkoutAccountInformation
	 * @param string $productName
	 * @param string $categoryName
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function checkoutProductWith2Checkout( $userName, $password, $checkoutAccountInformation, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText( FrontEndProductManagerJoomla3Page:: $alertSuccessMessage, 30, FrontEndProductManagerJoomla3Page:: $selectorSuccess);
		$I->see( FrontEndProductManagerJoomla3Page:: $alertSuccessMessage, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElement(FrontEndPaymentPluginPage:: $labelPayment);
		$I->waitForElementVisible(FrontEndPaymentPluginPage:: $labelPayment);
		$I->scrollTo(FrontEndPaymentPluginPage::$labelPayment);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$payment2checkout, 30);
		$I->click(FrontEndPaymentPluginPage::$payment2checkout);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndPaymentPluginPage::$secureCheckout, 30, FrontEndPaymentPluginPage:: $h1);
		$I->click(FrontEndPaymentPluginPage::$reviewCart);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$shippingAddress1, 30);
		$I->fillField(FrontEndPaymentPluginPage::$shippingAddress1, $checkoutAccountInformation['shippingAddress']);
		$I->click(FrontEndPaymentPluginPage::$shippingInformation);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$checkboxSamAsShipping, 30);
		$I->wait(0.5);
		$I->click(FrontEndPaymentPluginPage::$checkboxSamAsShipping);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$billingInformation, 30);
		$I->click(FrontEndPaymentPluginPage::$billingInformation);
		$I->waitForElement(FrontEndPaymentPluginPage::$inputCartNumber, 30);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$inputCartNumber, 30);
		$I->fillField(FrontEndPaymentPluginPage::$inputCartNumber, $checkoutAccountInformation['debitCardNumber']);
		$I->click(FrontEndPaymentPluginPage::$buttonPayment2Checkout);
		$I->waitForText(FrontEndPaymentPluginPage:: $message2CheckoutSuccess, 30, FrontEndPaymentPluginPage:: $h1);
	}
}