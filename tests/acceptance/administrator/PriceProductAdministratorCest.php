<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/29/2017
 * Time: 2:06 PM
 */
class PriceProductAdministratorCest
{

    public function createCategorySave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Change Price of Product in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\PriceProductManagerJoomla3Page($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addCategorySave($this->categoryName);
        $I->see("item successfully saved", '.alert-success');
    }


}