<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
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
     *
     * @return void
     */
    public function addQuotation($nameUser, $nameProduct, $quantity)
    {
        $I = $this;
        $I->amOnPage(\QuotationManagerPage::$URL);
        $I->click(\QuotationManagerPage::$newButton);
        $I->click(\QuotationManagerPage::$userId);
        $I->waitForElement(\QuotationManagerPage::$userSearch, 30);
        $userQuotationPage = new \QuotationManagerPage();
        $I->fillField(\QuotationManagerPage::$userSearch, $nameUser);
        $I->wait(5);

        $I->click($userQuotationPage->xPathSearch($nameUser));

        $I->click(\QuotationManagerPage::$productId);
        $I->waitForElement(\QuotationManagerPage::$productsSearch, 30);
        $I->fillField(\QuotationManagerPage::$productsSearch, $nameProduct);
        $I->wait(5);
        $I->click($userQuotationPage->xPathSearch($nameProduct));

        $I->fillField(\QuotationManagerPage::$quanlityFirst, $quantity);


        $I->click(\QuotationManagerPage::$saveButton);
        $I->see(\QuotationManagerPage::$messageSaveSuccess, \QuotationManagerPage::$selectorSuccess);
    }

    public function editQuotation($newQuantity)
    {
        $I = $this;
        $I->amOnPage(\QuotationManagerPage::$URL);
        $I->click(\QuotationManagerPage::$quotationId);
        $I->waitForElement(\QuotationManagerPage::$quantityp1,30);
        $I->fillField(\QuotationManagerPage::$quantityp1, $newQuantity);
        $I->click(\QuotationManagerPage::$saveButton);
        $I->see(\QuotationManagerPage::$messageSaveSuccess, \QuotationManagerPage::$selectorSuccess);
    }

    public function deleteQuotation()
    {
        $I = $this;
        $I->amOnPage(\QuotationManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\QuotationManagerPage::$deleteButton);
        $I->see(\QuotationManagerPage::$messageDeleteSuccess, \QuotationManagerPage::$selectorSuccess);
    }

}
