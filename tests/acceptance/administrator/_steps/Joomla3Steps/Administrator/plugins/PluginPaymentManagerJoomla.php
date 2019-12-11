<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Administrator\plugins;
use AcceptanceTester\AdminManagerJoomla3Steps;
use PluginManagerJoomla3Page;

/**
 * Class PluginPaymentManagerJoomla
 * @since 2.1.2
 */
class PluginPaymentManagerJoomla extends AdminManagerJoomla3Steps
{
	/**
	 * @param $pluginName
	 * @param $vendorID
	 * @param $secretWord
	 * @throws \Exception
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
		$I->waitForElementVisible( PluginManagerJoomla3Page:: $vendorID , 30);
		$I->fillField( PluginManagerJoomla3Page:: $vendorID , $vendorID);
		$I->fillField(PluginManagerJoomla3Page::$secretWords, $secretWord);
		$I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $accessId
	 * @param $transactionKey
	 * @param $md5Key
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function configAuthorizeDPMPlugin($pluginName, $accessId, $transactionKey, $md5Key)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->checkExistenceOf($pluginName);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->waitForElementVisible($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->click($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldAccessId, 60);
		$I->fillField(PluginManagerJoomla3Page::$fieldAccessId, $accessId);
		$I->fillField(PluginManagerJoomla3Page::$fieldTransactionID, $transactionKey);
		$I->fillField(PluginManagerJoomla3Page::$fieldMd5Key, $md5Key);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldTestMode, 60);
		$I->click( PluginManagerJoomla3Page::$fieldTestMode);

		// Choosing Test Mode to Yes
		$I->waitForElementVisible(PluginManagerJoomla3Page::$optionTestModeYes, 60);
		$I->click(PluginManagerJoomla3Page::$optionTestModeYes);
		$I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $operand
	 * @param $paymentPrice
	 * @param $discountType
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configBankTransferDiscountPlugin($pluginName, $operand, $paymentPrice, $discountType)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->checkExistenceOf($pluginName);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->waitForElementVisible($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->click($pluginName);

		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldPaymentOprand, 60);
		$I->fillField(PluginManagerJoomla3Page::$fieldPaymentOprand, $operand);

		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldPaymentPrice, 60);
		$I->fillField(PluginManagerJoomla3Page::$fieldPaymentPrice, $paymentPrice);

		switch ($discountType)
		{
			case 'Percentage':
				$I->waitForElementVisible(PluginManagerJoomla3Page::$optionPercentage, 30);
				$I->click(PluginManagerJoomla3Page::$optionPercentage);
				break;

			case 'Total':
				$I->waitForElementVisible(PluginManagerJoomla3Page::$optionTotal, 30);
				$I->click(PluginManagerJoomla3Page::$optionTotal);
				break;
		}

		// Click Save & Close
		$I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $customerID
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configEWAYPlugin($pluginName, $customerID)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$searchResultRow, 30);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->click($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$customerID, 30);
		$I->fillField(PluginManagerJoomla3Page::$customerID, $customerID);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$visa, 30);
		$I->click(PluginManagerJoomla3Page::$visa);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$masterCard, 30);
		$I->click(PluginManagerJoomla3Page::$masterCard);

		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $secretKey
	 * @param $publishableKey
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configStripePlugin($pluginName, $secretKey, $publishableKey)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$searchResultRow, 30);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->click($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$secretKey, 30);
		$I->fillField(PluginManagerJoomla3Page::$secretKey, $secretKey);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$publishableKey, 30);
		$I->fillField(PluginManagerJoomla3Page::$publishableKey, $publishableKey);

		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $accessId
	 * @param $transactionKey
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configAuthorizePlugin($pluginName, $accessId, $transactionKey)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->checkExistenceOf($pluginName);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->waitForElementVisible($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->click($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldAccessId, 60);
		$I->fillField(PluginManagerJoomla3Page::$fieldAccessId, $accessId);
		$I->fillField(PluginManagerJoomla3Page::$fieldTransactionID, $transactionKey);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldTestMode, 60);
		$I->click( PluginManagerJoomla3Page::$fieldTestMode);

		// Choosing Test Mode to Yes
		$I->waitForElementVisible(PluginManagerJoomla3Page::$optionTestModeYes, 60);
		$I->click(PluginManagerJoomla3Page::$optionTestModeYes);

		// Select Credit Cards
		$I->waitForElement(PluginManagerJoomla3Page::$advancedTag, 60);
		$I->click(PluginManagerJoomla3Page::$advancedTag);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$visa, 60);
		$I->click(PluginManagerJoomla3Page::$visa);

		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $email
	 * @param $passWord
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configMoneyBookerPlugin($pluginName, $email, $passWord)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$searchResultRow, 30);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->waitForElementVisible(['link' => $pluginName], 30);
		$I->click(['link' => $pluginName]);

		$I->waitForElementVisible(PluginManagerJoomla3Page::$merchantEmail, 30);
		$I->fillField(PluginManagerJoomla3Page::$merchantEmail, $email);

		$I->waitForElementVisible(PluginManagerJoomla3Page::$merchantPassword, 30);
		$I->fillField(PluginManagerJoomla3Page::$merchantPassword, $passWord);

		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $publicKey
	 * @param $privateKey
	 * @param $environment
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configPaymillPlugin($pluginName, $publicKey, $privateKey, $environment)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$searchResultRow, 30);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->waitForElementVisible(['link' => $pluginName], 30);
		$I->click(['link' => $pluginName]);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldPublicKey, 30);
		$I->fillField(PluginManagerJoomla3Page::$fieldPublicKey, $publicKey);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$fieldPrivateKey, 30);
		$I->fillField(PluginManagerJoomla3Page::$fieldPrivateKey, $privateKey);
		$I->selectOptionInChosen(PluginManagerJoomla3Page::$labelEnvironment, $environment);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$discountTypeTotal, 30);
		$I->click(PluginManagerJoomla3Page::$discountTypeTotal);
		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $priceDiscount
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function configCheckoutBankTransferPlugin($pluginName, $priceDiscount)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->searchForItem($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$firstCheck, 30);
		$I->see($pluginName);
		$I->waitForElementVisible(['link' => $pluginName], 30);
		$I->click(['link' => $pluginName]);

		$I->waitForElementVisible( PluginManagerJoomla3Page::$fieldPaymentPrice, 30);
		$I->fillField( PluginManagerJoomla3Page::$fieldPaymentPrice, $priceDiscount);
		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @param $priceDiscount
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function returnConfigCheckoutBankTransferPlugin($pluginName, $priceDiscount)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->searchForItem($pluginName);
		$I->waitForElementVisible(PluginManagerJoomla3Page::$firstCheck, 30);
		$I->see($pluginName);
		$I->waitForElementVisible(['link' => $pluginName], 30);
		$I->click(['link' => $pluginName]);

		$I->waitForElementVisible( PluginManagerJoomla3Page::$fieldPaymentPrice, 30);
		$I->fillField(PluginManagerJoomla3Page::$fieldPaymentPrice, '');
		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $pluginName
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function configEANTranferPayment($pluginName)
	{
		$I = $this;
		$I->amOnPage(PluginManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem($pluginName);
		$pluginManagerPage = new PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->checkExistenceOf($pluginName);
		$I->waitForText($pluginName, 30, PluginManagerJoomla3Page::$searchResultRow);
		$I->waitForElementVisible($pluginManagerPage->searchResultPluginName($pluginName), 30);
		$I->click($pluginName);
		$I->clickToolbarButton(PluginManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page::$idInstallSuccess);
	}
}
