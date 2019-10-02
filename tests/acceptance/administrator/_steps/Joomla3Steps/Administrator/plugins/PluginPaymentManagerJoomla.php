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
		$I->waitForElementVisible( PluginManagerJoomla3Page:: $vendorID ,30);
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
		$I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
	}

    public function configCheckoutBankTransferPlugin($pluginName, $priceDiscount)
    {
        $I = $this;
        $I->amOnPage(PluginManagerJoomla3Page:: $URL);
        $I->searchForItem($pluginName);
        $pluginManagerPage = new PluginManagerJoomla3Page;
        $I->waitForElement($pluginManagerPage->searchResultPluginName($pluginName), 30);
        $I->waitForElementVisible(PluginManagerJoomla3Page:: $searchResultRow, 30);
        $I->waitForText($pluginName, 30, PluginManagerJoomla3Page:: $searchResultRow);
        $I->click($pluginName);
        $I->waitForElementVisible( PluginManagerJoomla3Page:: $fieldPaymentPrice ,30);
        $I->fillField( PluginManagerJoomla3Page:: $fieldPaymentPrice , $priceDiscount);
        $I->click( PluginManagerJoomla3Page::$optionPercentage);
        $I->clickToolbarButton(PluginManagerJoomla3Page:: $buttonSaveClose);
        $I->waitForText(PluginManagerJoomla3Page::$pluginSaveSuccessMessage, 30, PluginManagerJoomla3Page:: $idInstallSuccess);
    }
}