<?php

/**
 *
 */
class ManageDiscountProductAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->categoryName = 'Events and Forms';
        $this->noPage = $this->faker->randomNumber();
        $this->productPrice = 120;
        $this->condition = "Higher";
        $this->type = "Percentage";
        $this->startDate = "19-06-2017";
        $this->endDate = "23-06-2017";
        $this->GroupName = "Product Name";
        $this->discountAmount = 12;
        $this->groupName = "Default Private";
    }

    /**
     *
     * Function create discount on product with save button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCategorySave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountProductSave($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryName, $this->groupName);
        $I->see("Discount Detail Saved", '.alert-success');
    }

    /**
     *
     * Function create discount on product with save and close button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addDiscountProductSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Discount Product  Save Close creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountProductSaveClose($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryName, $this->groupName);
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function check cancel button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addDiscountProductCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Check Cancel button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountProductCancelButton();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function add discount product missing amount
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addDiscountProductMissingAmountSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test add discount missing amount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountProductMissingAmountSaveClose($this->productPrice, $this->condition, $this->type, $this->startDate, $this->endDate, $this->categoryName, $this->groupName);
        $I->see("Discount", '.page-title');
    }

    /**
     *
     * Function add discount missing shopper groups
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addDiscountProductMissingShopperGroupSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test add discount missing shopper groups in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountProductMissingShopperGroupSaveClose($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryName);
        $I->see("Discount", '.page-title');
    }

    /**
     * Function add discount product have start day more than end day
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function addDiscountProductStartMoreThanEnd(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test add discount product start more than end  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountProductStartMoreThanEnd($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryName, $this->groupName);
        $I->see("Discount", '.page-title');
    }

    /**
     *
     * Function check edit button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check Edit button  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkEditButton();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function check delete button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('heck Delete button   in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkDeleteButton();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function check publish button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check Publish button  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkPublishButton();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function check unpublish button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check Unpublish button  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkUnpublishButton();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     * Function check unpublish all discount
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends createCategorySave
     *
     */
    public function checkUnpublishAll(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Unpublish all discount Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkUnpublishAll();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function check publish all discount
     *
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends checkUnpublishAll
     *
     */
    public function checkPublishAll(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Publish all discounts in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkPublishAll();
        $I->see("Product Discount Management", '.page-title');
    }

    /**
     *
     * Function delete all discounts
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends addDiscountProductSaveClose
     *
     */
    public function checkDeleteAll(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deleted all discounts in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\DiscountProductJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->checkDeleteAll();
        $I->see("Product Discount Management", '.page-title');
    }

}