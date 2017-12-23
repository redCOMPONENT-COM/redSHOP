<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class DiscountProductSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.0
 */
class DiscountProductSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Discount
	 *
	 * @param   integer $productPrice   Discount name
	 * @param   integer $condition      Discount Amount
	 * @param   integer $type           Amount on the Discount
	 * @param   integer $discountAmount Group for the Shopper
	 * @param   string  $startDate      Type of Discount
	 * @param   string  $endDate        Discount conditions
	 * @param   string  $category       Discount conditions
	 * @param   string  $groupName      Discount conditions
	 *
	 * @return void
	 */
	public function addDiscountProductSave($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$newButton);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->chooseRadio(\DiscountProductPage::$fieldCondition, $condition);
		$client->chooseRadio(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(\DiscountProductPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(\DiscountProductPage::$buttonSave);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
	}

	/**
	 * Function to Add a New Discount with save and close button
	 *
	 * @param   integer $productPrice   Discount name
	 * @param   integer $condition      Discount Amount
	 * @param   integer $type           Amount on the Discount
	 * @param   integer $discountAmount Group for the Shopper
	 * @param   string  $startDate      Type of Discount
	 * @param   string  $endDate        Discount conditions
	 * @param   string  $category       Discount conditions
	 * @param   string  $groupName      Discount conditions
	 *
	 * @return void
	 */
	public function addDiscountProductSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$newButton);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->chooseRadio(\DiscountProductPage::$fieldCondition, $condition);
		$client->chooseRadio(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(\DiscountProductPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(\DiscountProductPage::$buttonSaveClose);
	}

	public function addDiscountToday($productPrice, $condition, $type, $discountAmount, $nameCate, $groupName)
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \DiscountProductPage::$namePageDiscount);
		$I->checkForPhpNoticesOrWarnings();
		$userDiscountPage = new\DiscountProductPage();
		$I->fillField(\DiscountProductPage::$fieldAmount, $productPrice);

		$I->click(\DiscountProductPage::$fieldCondition);
		$I->waitForElement(\DiscountProductPage::$conditionSearch);
		$I->fillField(\DiscountProductPage::$conditionSearch, $condition);

		$I->waitForElement($userDiscountPage->returnType($condition), 60);
		$I->click($userDiscountPage->returnType($condition));

		$I->click(\DiscountProductPage::$fieldDiscountType);
		$I->waitForElement(\DiscountProductPage::$discountTypeSearch);
		$I->fillField(\DiscountProductPage::$discountTypeSearch, $type);

		$I->waitForElement($userDiscountPage->returnType($type), 60);
		$I->click($userDiscountPage->returnType($type));


		$I->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$I->click(\DiscountProductPage::$fieldCategory);
		$I->fillField(\DiscountProductPage::$categoryInput, $nameCate);

		$I->waitForElement($userDiscountPage->returnType($nameCate), 60);
		$I->click($userDiscountPage->returnType($nameCate));


		$I->click(\DiscountProductPage::$fieldShopperGroup);
		$I->fillField(\DiscountProductPage::$shopperGroupInput, $groupName);

		$I->waitForElement($userDiscountPage->returnType($groupName), 60);
		$I->click($userDiscountPage->returnType($groupName));


		$I->click(\DiscountProductPage::$saveButton);
		$I->waitForElement(\DiscountProductPage::$fieldAmount, 30);
	}

	/**
	 * Function to Add a New Discount with save and close button
	 *
	 * @return void
	 */
	public function addDiscountProductCancelButton()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->click(\DiscountProductPage::$newButton);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonCancel);
	}

	public function addDiscountProductMissingAmountSaveClose($productPrice, $condition, $type, $startDate, $endDate, $nameCate, $groupName)
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \DiscountProductPage::$namePageDiscount);
		$I->checkForPhpNoticesOrWarnings();
		$userDiscountPage = new\DiscountProductPage();
		$I->fillField(\DiscountProductPage::$fieldAmount, $productPrice);

		$I->click(\DiscountProductPage::$fieldCondition);
		$I->waitForElement(\DiscountProductPage::$conditionSearch);
		$I->fillField(\DiscountProductPage::$conditionSearch, $condition);
		$I->waitForElement($userDiscountPage->returnType($condition), 60);
		$I->click($userDiscountPage->returnType($condition));

		$I->click(\DiscountProductPage::$fieldDiscountType);
		$I->waitForElement(\DiscountProductPage::$discountTypeSearch);
		$I->fillField(\DiscountProductPage::$discountTypeSearch, $type);
		$I->waitForElement($userDiscountPage->returnType($type), 60);
		$I->click($userDiscountPage->returnType($type));

		$I->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$I->fillField(\DiscountProductPage::$fieldEndDate, $endDate);

		$I->click(\DiscountProductPage::$fieldCategory);
		$I->fillField(\DiscountProductPage::$categoryInput, $nameCate);
		$I->waitForElement($userDiscountPage->returnType($nameCate), 60);
		$I->click($userDiscountPage->returnType($nameCate));

		$I->click(\DiscountProductPage::$fieldShopperGroup);
		$I->fillField(\DiscountProductPage::$shopperGroupInput, $groupName);
		$I->waitForElement($userDiscountPage->returnType($groupName), 60);
		$I->click($userDiscountPage->returnType($groupName));


		$I->click(\DiscountProductPage::$saveCloseButton);
		$I->acceptPopup();
	}

	public function addDiscountProductMissingShopperGroupSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate)
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \DiscountProductPage::$namePageDiscount);
		$I->checkForPhpNoticesOrWarnings();
		$userDiscountPage = new\DiscountProductPage();
		$I->fillField(\DiscountProductPage::$fieldAmount, $productPrice);

		$I->click(\DiscountProductPage::$fieldCondition);
		$I->waitForElement(\DiscountProductPage::$conditionSearch);
		$I->fillField(\DiscountProductPage::$conditionSearch, $condition);
		$I->waitForElement($userDiscountPage->returnType($condition), 60);
		$I->click($userDiscountPage->returnType($condition));

		$I->click(\DiscountProductPage::$fieldDiscountType);
		$I->waitForElement(\DiscountProductPage::$discountTypeSearch);
		$I->fillField(\DiscountProductPage::$discountTypeSearch, $type);
		$I->waitForElement($userDiscountPage->returnType($type), 60);
		$I->click($userDiscountPage->returnType($type));

		$I->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$I->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$I->fillField(\DiscountProductPage::$fieldEndDate, $endDate);

		$I->click(\DiscountProductPage::$fieldCategory);
		$I->fillField(\DiscountProductPage::$categoryInput, $nameCate);
		$I->waitForElement($userDiscountPage->returnType($nameCate), 60);
		$I->click($userDiscountPage->returnType($nameCate));

		$I->click(\DiscountProductPage::$saveCloseButton);
		$I->acceptPopup();
	}

	public function addDiscountProductStartMoreThanEnd($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \DiscountProductPage::$namePageDiscount);
		$I->checkForPhpNoticesOrWarnings();
		$userDiscountPage = new\DiscountProductPage();
		$I->fillField(\DiscountProductPage::$fieldAmount, $productPrice);

		$I->click(\DiscountProductPage::$fieldCondition);
		$I->waitForElement(\DiscountProductPage::$conditionSearch);
		$I->fillField(\DiscountProductPage::$conditionSearch, $condition);
		$I->waitForElement($userDiscountPage->returnType($condition), 60);
		$I->click($userDiscountPage->returnType($condition));

		$I->click(\DiscountProductPage::$fieldDiscountType);
		$I->waitForElement(\DiscountProductPage::$discountTypeSearch);
		$I->fillField(\DiscountProductPage::$discountTypeSearch, $type);
		$I->waitForElement($userDiscountPage->returnType($type), 60);
		$I->click($userDiscountPage->returnType($type));

		$I->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$I->fillField(\DiscountProductPage::$fieldStartDate, $endDate);
		$I->fillField(\DiscountProductPage::$fieldEndDate, $startDate);

		$I->click(\DiscountProductPage::$fieldCategory);
		$I->fillField(\DiscountProductPage::$categoryInput, $nameCate);
		$I->waitForElement($userDiscountPage->returnType($nameCate), 60);
		$I->click($userDiscountPage->returnType($nameCate));

		$I->click(\DiscountProductPage::$fieldShopperGroup);
		$I->fillField(\DiscountProductPage::$shopperGroupInput, $groupName);
		$I->waitForElement($userDiscountPage->returnType($groupName), 60);
		$I->click($userDiscountPage->returnType($groupName));

		$I->click(\DiscountProductPage::$saveCloseButton);
		$I->acceptPopup();
	}

	public function addDiscountProductStartString($productPrice, $condition, $type, $discountAmount, $endDate, $nameCate, $groupName)
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \DiscountProductPage::$namePageDiscount);
		$I->checkForPhpNoticesOrWarnings();
		$userDiscountPage = new\DiscountProductPage();
		$I->fillField(\DiscountProductPage::$fieldAmount, $productPrice);

		$I->click(\DiscountProductPage::$fieldCondition);
		$I->waitForElement(\DiscountProductPage::$conditionSearch);
		$I->fillField(\DiscountProductPage::$conditionSearch, $condition);
		$I->waitForElement($userDiscountPage->returnType($condition), 60);
		$I->click($userDiscountPage->returnType($condition));

		$I->click(\DiscountProductPage::$fieldDiscountType);
		$I->waitForElement(\DiscountProductPage::$discountTypeSearch);
		$I->fillField(\DiscountProductPage::$discountTypeSearch, $type);
		$I->waitForElement($userDiscountPage->returnType($type), 60);
		$I->click($userDiscountPage->returnType($type));

		$I->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$I->fillField(\DiscountProductPage::$fieldEndDate, $endDate);

		$I->click(\DiscountProductPage::$fieldCategory);
		$I->fillField(\DiscountProductPage::$categoryInput, $nameCate);
		$I->waitForElement($userDiscountPage->returnType($nameCate), 60);
		$I->click($userDiscountPage->returnType($nameCate));

		$I->click(\DiscountProductPage::$fieldShopperGroup);
		$I->fillField(\DiscountProductPage::$shopperGroupInput, $groupName);
		$I->waitForElement($userDiscountPage->returnType($groupName), 60);
		$I->click($userDiscountPage->returnType($groupName));

		$I->fillField(\DiscountProductPage::$fieldStartDate, "string");
		$I->click(\DiscountProductPage::$saveCloseButton);
		$I->acceptPopup();
	}

	public function checkEditButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$editButton);
		$I->acceptPopup();
	}

	public function checkDeleteButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$deleteButton);
		$I->acceptPopup();
	}

	public function checkPublishButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$publishButton);
		$I->acceptPopup();
	}

	public function checkUnpublishButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->click(\DiscountProductPage::$unpublishButton);
		$I->acceptPopup();
	}

	public function checkUnpublishAll()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->checkAllResults();
		$I->click(\DiscountProductPage::$unpublishButton);
		$I->see(\DiscountProductPage::$messageUnpublishSuccess, \DiscountProductPage::$selectorSuccess);
	}

	public function checkPublishAll()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->checkAllResults();
		$I->click(\DiscountProductPage::$publishButton);
		$I->see(\DiscountProductPage::$messagePublishSuccess, \DiscountProductPage::$selectorSuccess);
	}

	public function checkDeleteAll()
	{
		$I = $this;
		$I->amOnPage(\DiscountProductPage::$url);
		$I->checkAllResults();
		$I->click(\DiscountProductPage::$deleteButton);
		$I->see(\DiscountProductPage::$messageDeleteSuccess, \DiscountProductPage::$selectorSuccess);
	}
}