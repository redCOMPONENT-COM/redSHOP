<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/8/2017
 * Time: 2:00 PM
 */

namespace AcceptanceTester;


class MassDiscountManagerJoomla3Steps extends AdminManagerJoomla3Steps
{

    public function addMassDiscount($massDiscountName , $amountValue, $nameProduct ){
        $I=$this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click('New');
        $I->verifyNotices(false,$this->checkForNotices(),'product mass discount new');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\MassDiscountManagerPage::$name,$massDiscountName);
        $I->fillField(\MassDiscountManagerPage::$valueAmount,$amountValue);
        $I->click(\MassDiscountManagerPage::$pathNameProduct);
        $I->click('Save');

    }
}