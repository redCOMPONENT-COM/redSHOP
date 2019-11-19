<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use QuotationManagerPage as QuotationManagerPage;
use UserManagerJoomla3Page as UserManagerJoomla3Page;
/**
 * Class QuotationManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class QuotationManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a New Quotation
	 * since 2.1.2
	 * @return void
	 */
	public function addQuotation($nameUser, $nameProduct, $quantity)
	{
		$I = $this;
		$I->amOnPage(QuotationManagerPage::$URL);
		$I->click(QuotationManagerPage::$buttonNew);
		$I->click(QuotationManagerPage::$userId);
		$I->waitForElement(QuotationManagerPage::$userSearch, 30);
		$userQuotationPage = new QuotationManagerPage();
		$I->fillField(QuotationManagerPage::$userSearch, $nameUser);
		$I->amOnPage(\QuotationManagerPage::$URL);
		$I->click(\QuotationManagerPage::$buttonNew);
		$I->click(\QuotationManagerPage::$userId);
		$I->waitForElement(\QuotationManagerPage::$userSearch, 30);
		$I->fillField(\QuotationManagerPage::$userSearch, $nameUser);
		$I->waitForElement($userQuotationPage->xPathSearch($nameUser), 30);
		$I->click($userQuotationPage->xPathSearch($nameUser));
		$I->scrollTo(QuotationManagerPage::$newProductLink);
		$I->waitForElementVisible(QuotationManagerPage::$productId, 30);
		$I->click(QuotationManagerPage::$productId);
		$I->wait(0.5);
		$I->waitForElementVisible(QuotationManagerPage::$productsSearch, 30);
		$I->fillField(QuotationManagerPage::$productsSearch, $nameProduct);
		$I->waitForElement($userQuotationPage->xPathSearch($nameProduct), 60);
		$I->click($userQuotationPage->xPathSearch($nameProduct));
		$I->fillField(QuotationManagerPage::$quanlityFirst, $quantity);
		$I->click(QuotationManagerPage::$buttonSave);
		try{
			$I->waitForText(QuotationManagerPage::$messageSaveSuccess,5, QuotationManagerPage::$selectorSuccess);
		}catch (\Exception $e)
		{
			$I->click(QuotationManagerPage::$buttonSave);
			$I->see(QuotationManagerPage::$messageSaveSuccess, QuotationManagerPage::$selectorSuccess);
		}
	}

	/**
	 * @param $newQuantity
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function editQuotation($newQuantity)
	{
		$I = $this;
		$I->amOnPage(QuotationManagerPage::$URL);
		$I->click(QuotationManagerPage::$quotationId);
		$I->waitForElement(QuotationManagerPage::$quantityp1,30);
		$I->scrollTo(QuotationManagerPage::$quantityp1);
		$I->addValueForField(QuotationManagerPage::$quantityp1, $newQuantity, 5);
		$I->click(QuotationManagerPage::$buttonSave);
		$I->scrollTo(QuotationManagerPage::$quantityp1);
		$I->seeInField(QuotationManagerPage::$quantityp1, $newQuantity);
	}

	/**
	 * @param $status
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function editStatus($status)
	{
		$I = $this;
		$I->amOnPage(QuotationManagerPage::$URL);
		$I->click(QuotationManagerPage::$quotationId);
		$I->waitForElement(QuotationManagerPage::$quantityp1,30);
		$I->click(QuotationManagerPage::$quotationStatusDropDown);
		$I->waitForElement(QuotationManagerPage::$quotationStatusSearch, 30);
		$I->fillField(QuotationManagerPage::$quotationStatusSearch, $status);
		$userQuotationPage = new QuotationManagerPage();
		$I->waitForElement($userQuotationPage->xPathSearch($status), 30);
		$I->click($userQuotationPage->xPathSearch($status));
		$I->click(QuotationManagerPage::$buttonSave);
		$I->click(QuotationManagerPage::$buttonSaveClose);
		$I->waitForText($status, 30, QuotationManagerPage::$quotationStatus);
	}

	/**
	 * Funcution detele Quotation
	 * sine 2.1.2
	 */
	public function deleteQuotation()
	{
		$I = $this;
		$I->amOnPage(QuotationManagerPage::$URL);
		$I->checkAllResults();
		$I->click(QuotationManagerPage::$buttonDelete);
	}

	/**
	 * @param $firstname
	 * @param $lastName
	 * @param $address
	 * @param $postalCode
	 * @param $city
	 * @param $stringPhone
	 * @param $email
	 * @param $userName
	 * @param $passWord
	 * @throws \Codeception\Exception\ModuleException
	 * since 2.1.2
	 */
	public function checkPhoneNumber($firstname, $lastName, $address, $postalCode, $city, $stringPhone, $email, $userName, $passWord)
	{
		$I = $this;
		$I->amOnPage(QuotationManagerPage::$URL);
		$I->click(QuotationManagerPage::$buttonNew);
		$I->waitForElementVisible(UserManagerJoomla3Page::$firstName, 30);
		$I->fillField(UserManagerJoomla3Page::$firstName, $firstname);
		$I->fillField(UserManagerJoomla3Page::$lastName, $lastName);
		$I->fillField(UserManagerJoomla3Page::$address, $address);
		$I->fillField(UserManagerJoomla3Page::$zipcode, $postalCode);
		$I->fillField(UserManagerJoomla3Page::$city, $city);
		$I->fillField(UserManagerJoomla3Page::$phone , $stringPhone);
		$I->fillField(QuotationManagerPage::$email, $email);
		$I->fillField(UserManagerJoomla3Page::$userName, $userName);
		$I->fillField(UserManagerJoomla3Page::$newPassword, $passWord);
		$I->fillField(UserManagerJoomla3Page::$confirmNewPassword, $passWord);
		$I->click(UserManagerJoomla3Page::$saveButton);
		$I->seeInPopup(QuotationManagerPage::$enterPhoneNumber);
	}
}