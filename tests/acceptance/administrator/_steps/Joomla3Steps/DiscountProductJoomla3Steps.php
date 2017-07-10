<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/19/2017
 * Time: 3:54 PM
 */

namespace AcceptanceTester;


class DiscountProductJoomla3Steps extends AdminManagerJoomla3Steps
{
    public function addDiscountProductSave($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(\DiscountProductJ3Page::$condition);
        $I->waitForElement(\DiscountProductJ3Page::$conditionSearch);
        $I->fillField(\DiscountProductJ3Page::$conditionSearch, $condition);
        $I->returnSpan($condition);

        $I->click(\DiscountProductJ3Page::$discountType);
        $I->waitForElement(\DiscountProductJ3Page::$discountTypeSearch);
        $I->fillField(\DiscountProductJ3Page::$discountTypeSearch, $type);
        $I->returnSpan($type);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(\DiscountProductJ3Page::$category);
        $I->fillField(\DiscountProductJ3Page::$categoryInput, $nameCate);
        $I->returnSpan($nameCate);

        $I->click(\DiscountProductJ3Page::$shopperGroup);
        $I->fillField(\DiscountProductJ3Page::$shopperGroupInput, $groupName);
        $I->returnSpan($groupName);

        $I->click(\DiscountProductJ3Page::$saveButton);
        $I->waitForElement(\DiscountProductJ3Page::$productPrice, 30);
    }

    public function addDiscountProductSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(\DiscountProductJ3Page::$condition);
        $I->waitForElement(\DiscountProductJ3Page::$conditionSearch);
        $I->fillField(\DiscountProductJ3Page::$conditionSearch, $condition);
        $I->returnSpan($condition);

        $I->click(\DiscountProductJ3Page::$discountType);
        $I->waitForElement(\DiscountProductJ3Page::$discountTypeSearch);
        $I->fillField(\DiscountProductJ3Page::$discountTypeSearch, $type);
        $I->returnSpan($type);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(\DiscountProductJ3Page::$category);
        $I->fillField(\DiscountProductJ3Page::$categoryInput, $nameCate);
        $I->returnSpan($nameCate);

        $I->click(\DiscountProductJ3Page::$shopperGroup);
        $I->fillField(\DiscountProductJ3Page::$shopperGroupInput, $groupName);
        $I->returnSpan($groupName);

        $I->click(\DiscountProductJ3Page::$saveCloseButton);
    }

    public function addDiscountProductCancelButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Cancel");
    }

    public function addDiscountProductMissingAmountSaveClose($productPrice, $condition, $type, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(\DiscountProductJ3Page::$condition);
        $I->waitForElement(\DiscountProductJ3Page::$conditionSearch);
        $I->fillField(\DiscountProductJ3Page::$conditionSearch, $condition);
        $I->returnSpan($condition);

        $I->click(\DiscountProductJ3Page::$discountType);
        $I->waitForElement(\DiscountProductJ3Page::$discountTypeSearch);
        $I->fillField(\DiscountProductJ3Page::$discountTypeSearch, $type);
        $I->returnSpan($type);

        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(\DiscountProductJ3Page::$category);
        $I->fillField(\DiscountProductJ3Page::$categoryInput, $nameCate);
        $I->returnSpan($nameCate);

        $I->click(\DiscountProductJ3Page::$shopperGroup);
        $I->fillField(\DiscountProductJ3Page::$shopperGroupInput, $groupName);
        $I->returnSpan($groupName);


        $I->click(\DiscountProductJ3Page::$saveCloseButton);
        $I->acceptPopup();
    }

    public function addDiscountProductMissingShopperGroupSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(\DiscountProductJ3Page::$condition);
        $I->waitForElement(\DiscountProductJ3Page::$conditionSearch);
        $I->fillField(\DiscountProductJ3Page::$conditionSearch, $condition);
        $I->returnSpan($condition);

        $I->click(\DiscountProductJ3Page::$discountType);
        $I->waitForElement(\DiscountProductJ3Page::$discountTypeSearch);
        $I->fillField(\DiscountProductJ3Page::$discountTypeSearch, $type);
        $I->returnSpan($type);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(\DiscountProductJ3Page::$category);
        $I->fillField(\DiscountProductJ3Page::$categoryInput, $nameCate);
        $I->returnSpan($nameCate);

        $I->click(\DiscountProductJ3Page::$saveCloseButton);
        $I->acceptPopup();
    }

    public function addDiscountProductStartMoreThanEnd($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(\DiscountProductJ3Page::$condition);
        $I->waitForElement(\DiscountProductJ3Page::$conditionSearch);
        $I->fillField(\DiscountProductJ3Page::$conditionSearch, $condition);
        $I->returnSpan($condition);

        $I->click(\DiscountProductJ3Page::$discountType);
        $I->waitForElement(\DiscountProductJ3Page::$discountTypeSearch);
        $I->fillField(\DiscountProductJ3Page::$discountTypeSearch, $type);
        $I->returnSpan($type);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $endDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $startDate);

        $I->click(\DiscountProductJ3Page::$category);
        $I->fillField(\DiscountProductJ3Page::$categoryInput, $nameCate);
        $I->returnSpan($nameCate);

        $I->click(\DiscountProductJ3Page::$shopperGroup);
        $I->fillField(\DiscountProductJ3Page::$shopperGroupInput, $groupName);
        $I->returnSpan($groupName);

        $I->click(\DiscountProductJ3Page::$saveCloseButton);
        $I->acceptPopup();
    }

    public function addDiscountProductStartString($productPrice, $condition, $type, $discountAmount, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), \DiscountProductJ3Page::$namePageDiscount);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(\DiscountProductJ3Page::$condition);
        $I->waitForElement(\DiscountProductJ3Page::$conditionSearch);
        $I->fillField(\DiscountProductJ3Page::$conditionSearch, $condition);
        $I->returnSpan($condition);

        $I->click(\DiscountProductJ3Page::$discountType);
        $I->waitForElement(\DiscountProductJ3Page::$discountTypeSearch);
        $I->fillField(\DiscountProductJ3Page::$discountTypeSearch, $type);
        $I->returnSpan($type);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(\DiscountProductJ3Page::$category);
        $I->fillField(\DiscountProductJ3Page::$categoryInput, $nameCate);
        $I->returnSpan($nameCate);

        $I->click(\DiscountProductJ3Page::$shopperGroup);
        $I->fillField(\DiscountProductJ3Page::$shopperGroupInput, $groupName);
        $I->returnSpan($groupName);

        $I->fillField(\DiscountProductJ3Page::$startDate, "string");
        $I->click(\DiscountProductJ3Page::$saveCloseButton);
        $I->acceptPopup();
    }

    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$editButton);
        $I->acceptPopup();
    }

    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$deleteButton);
        $I->acceptPopup();
    }

    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$publishButton);
        $I->acceptPopup();
    }

    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click(\DiscountProductJ3Page::$unpublishButton);
        $I->acceptPopup();
    }
    public function checkUnpublishAll(){
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->checkAllResults();
        $I->click(\DiscountProductJ3Page::$unpublishButton);
        $I->see(\DiscountProductJ3Page::$messageUnpublishSuccess, \DiscountProductJ3Page::$selectorSuccess);
    }

    public function checkPublishAll(){
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->checkAllResults();
        $I->click(\DiscountProductJ3Page::$publishButton);
        $I->see(\DiscountProductJ3Page::$messagePublishSuccess, \DiscountProductJ3Page::$selectorSuccess);
    }

    public function checkDeleteAll(){
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->checkAllResults();
        $I->click(\DiscountProductJ3Page::$deleteButton);
        $I->see(\DiscountProductJ3Page::$messageDeleteSuccess, \DiscountProductJ3Page::$selectorSuccess);
    }

    public function returnSpan($condition)
    {
        $I = $this;
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);
    }

}