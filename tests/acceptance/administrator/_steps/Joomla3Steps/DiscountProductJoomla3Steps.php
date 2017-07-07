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
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(['xpath' => '//div[@id="s2id_condition"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $condition);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_discount_type"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $type);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $type . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $type . "')]"]);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(['xpath' => "//div[@id='s2id_category_ids']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"], $nameCate);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);

        $I->click(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li//input"], $groupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);

        $I->click("Save");
        $I->waitForElement(\DiscountProductJ3Page::$productPrice, 30);
    }

    public function addDiscountProductSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(['xpath' => '//div[@id="s2id_condition"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $condition);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_discount_type"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $type);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $type . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $type . "')]"]);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(['xpath' => "//div[@id='s2id_category_ids']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"], $nameCate);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);

        $I->click(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li//input"], $groupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);

        $I->click("Save & Close ");
    }

    public function addDiscountProductCancelButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Cancel");
    }

    public function addDiscountProductMissingAmountSaveClose($productPrice, $condition, $type, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(['xpath' => '//div[@id="s2id_condition"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $condition);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_discount_type"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $type);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $type . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $type . "')]"]);

        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(['xpath' => "//div[@id='s2id_category_ids']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"], $nameCate);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);

        $I->click(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li//input"], $groupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);

        $I->click("Save & Close ");
        $I->acceptPopup();
    }

    public function addDiscountProductMissingShopperGroupSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(['xpath' => '//div[@id="s2id_condition"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $condition);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_discount_type"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $type);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $type . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $type . "')]"]);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(['xpath' => "//div[@id='s2id_category_ids']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"], $nameCate);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click("Save & Close ");
        $I->acceptPopup();
    }

    public function addDiscountProductStartMoreThanEnd($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(['xpath' => '//div[@id="s2id_condition"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $condition);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_discount_type"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $type);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $type . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $type . "')]"]);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$startDate, $endDate);
        $I->fillField(\DiscountProductJ3Page::$endDate, $startDate);

        $I->click(['xpath' => "//div[@id='s2id_category_ids']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"], $nameCate);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);

        $I->click(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li//input"], $groupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);

        $I->click("Save & Close ");
        $I->acceptPopup();
    }

    public function addDiscountProductStartString($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $nameCate, $groupName)
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Discount Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\DiscountProductJ3Page::$productPrice, $productPrice);

        $I->click(['xpath' => '//div[@id="s2id_condition"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $condition);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $condition . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $condition . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_discount_type"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $type);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $type . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $type . "')]"]);

        $I->fillField(\DiscountProductJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountProductJ3Page::$endDate, $endDate);

        $I->click(['xpath' => "//div[@id='s2id_category_ids']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"], $nameCate);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCate . "')]"]);

        $I->click(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li//input"], $groupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $groupName . "')]"]);
        $I->fillField(\DiscountProductJ3Page::$startDate, "string");
        $I->click("Save & Close ");
        $I->acceptPopup();
    }

    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("Edit");
        $I->acceptPopup();
    }

    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("Delete");
        $I->acceptPopup();
    }

    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("Publish");
        $I->acceptPopup();
    }

    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->click("Unpublish");
        $I->acceptPopup();
    }
    public function checkUnpublishAll(){
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->checkAllResults();
        $I->click("Unpublish");
        $I->see("Discount Detail UnPublished Successfully", '.alert-success');
    }

    public function checkPublishAll(){
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->checkAllResults();
        $I->click("Publish");
        $I->see("Discount Detail Published Successfully", '.alert-success');
    }

    public function checkDeleteAll(){
        $I = $this;
        $I->amOnPage(\DiscountProductJ3Page::$URL);
        $I->checkAllResults();
        $I->click("Delete");
        $I->see("Discount Detail Deleted Successfully", '.alert-success');
    }

}