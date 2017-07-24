<?php

/**
 */
class ManageShopperGroupAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->shopperName = 'Testing shopper ' . $this->faker->randomNumber();
        $this->shopperType = 'Default Private';
        $this->categoryName = 'Testing Category ' . $this->faker->randomNumber();
        $this->customerType = 'Company customer';
        $this->shippingRate = $this->faker->numberBetween(1, 100);
        $this->shippingCheckout = $this->faker->numberBetween(1, 100);
        $this->catalog = 'Yes';
        $this->showPrice = 'Yes';
        $this->nameShopperEdit = $this->shopperType . 'edit';
        $this->idShopperChange = '1';

    }

    public function createCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addCategorySave($this->categoryName);

    }

    /**
     *
     * Function create new Shopper groups
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends createCategory
     *
     */
    public function creatShopperGroup(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addShopperGroupsSave($this->shopperName, $this->shopperType, $this->customerType, "Templates", $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showPrice);
    }

    /**
     *
     * Function delte Shopper goups
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends creatShopperGroup
     */
    public function deleteShopperGroupsYes(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check delete Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Edit Name of Shopper groups');
        $I->deleteShopperGroupsYes();
    }

    /**
     * Function change status os shopper groups is unpublish
     * Change Shopper groups have name is Default Private
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function changeStatusUnpublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Unpublish Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Test Unpublish Shopper groups');
        $I->changeStateShopperGroups();
        $currentState = $I->getShopperGroupsStates();
        $I->verifyState('unpublished', $currentState);
    }

    /**
     * Function change status os shopper groups is publish
     * Change Shopper groups have name is Default Private
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function changeStatusPublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Publish Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Test Publish Shopper groups');
        $I->changeStateShopperGroups();
        $currentState = $I->getShopperGroupsStates();
        $I->verifyState('published', $currentState);
    }

    /**
     * Functionc check Close button inside page detail
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check Close button Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Edit Name of Shopper groups');
        $I->checkCloseButton($this->idShopperChange);
    }

    /**
     * Function edit name of shopper groups
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeStatusPublish
     */
    public function editNameShopper(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Edit Name of Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Edit Name of Shopper groups');
        $I->editShopperGroups($this->shopperType,$this->idShopperChange,$this->nameShopperEdit);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function deleteShopperGroupsNo(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check delete Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Edit Name of Shopper groups');
        $I->deleteShopperGroupsNo();
    }

    /**
     * Function check cancel button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addShopperGroupsCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check delete Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Edit Name of Shopper groups');
        $I->addShopperGroupsCancel();
    }

    public function addShopperGroupsMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check delete Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Edit Name of Shopper groups');
        $I->addShopperGroupsMissingName();
    }

    public function publishAllShopperGroups(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Publish all  Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Publish all  Shopper groups');
        $I->publishAllShopperGroups();
    }

    public function unpublishAllShopperGroups(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Unpublish all  Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('unpublish all  Shopper groups');
        $I->unpublishAllShopperGroups();
    }


    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check edit button Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->checkEditButton();
    }
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check delete button Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->checkDeleteButton();
    }
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check publish button Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->checkPublishButton();
    }
    public function checkUnPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check unpublish button Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->checkUnPublishButton();
    }

    public function deleteAllShopperGroups(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('delete all  Shopper groups');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->deleteAllShopperGroups();
    }

}