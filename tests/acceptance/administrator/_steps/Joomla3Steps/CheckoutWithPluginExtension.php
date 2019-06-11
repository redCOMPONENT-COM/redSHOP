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
		$I->waitForElementVisible(PluginManagerJoomla3Page:: $searchResultRow, 30);
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
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible(FrontEndPaymentPluginPage:: $labelPayment, 30);
		$I->scrollTo(FrontEndPaymentPluginPage::$labelPayment);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$payment2checkout, 30);
		$I->wait(0.5);
		$I->click(FrontEndPaymentPluginPage::$payment2checkout);
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
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		try
		{
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}
		$I->waitForText(FrontEndPaymentPluginPage::$secureCheckout, 30, FrontEndPaymentPluginPage:: $h1);
		$I->click(FrontEndPaymentPluginPage::$reviewCart);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$shippingAddress1, 30);
		$I->fillField(FrontEndPaymentPluginPage::$shippingAddress1, $checkoutAccountInformation['shippingAddress']);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$shippingInformation, 30);
		$I->click(FrontEndPaymentPluginPage::$shippingInformation);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$checkboxSamAsShipping, 30);
		$I->wait(0.5);
		$I->click(FrontEndPaymentPluginPage::$checkboxSamAsShipping);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$billingInformation, 30);
		$I->wait(0.5);
		$I->click(FrontEndPaymentPluginPage::$billingInformation);
		$I->waitForElement(FrontEndPaymentPluginPage::$inputCartNumber, 30);
		$I->waitForElementVisible(FrontEndPaymentPluginPage::$inputCartNumber, 30);
		$I->fillField(FrontEndPaymentPluginPage::$inputCartNumber, $checkoutAccountInformation['debitCardNumber']);
		$I->click(FrontEndPaymentPluginPage::$buttonPayment2Checkout);
		$I->waitForText(FrontEndPaymentPluginPage:: $message2CheckoutSuccess, 30, FrontEndPaymentPluginPage:: $h1);
	}
}
