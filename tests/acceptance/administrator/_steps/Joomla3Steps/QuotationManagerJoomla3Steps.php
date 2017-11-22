<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use QuotationManagerPage as QuotationManagerPage;
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
        $I->amOnPage(QuotationManagerPage::$URL);
        $I->click(QuotationManagerPage::$buttonNew);
        $I->click(QuotationManagerPage::$userId);
        $I->waitForElement(QuotationManagerPage::$userSearch, 30);
        $userQuotationPage = new QuotationManagerPage();
        $I->fillField(QuotationManagerPage::$userSearch, $nameUser);
        $I->waitForElement($userQuotationPage->xPathSearch($nameUser), 30);

        $I->click($userQuotationPage->xPathSearch($nameUser));

        $I->click(QuotationManagerPage::$productId);
        $I->waitForElement(QuotationManagerPage::$productsSearch, 30);
        $I->fillField(QuotationManagerPage::$productsSearch, $nameProduct);
        $I->waitForElement($userQuotationPage->xPathSearch($nameProduct), 30);
        $I->click($userQuotationPage->xPathSearch($nameProduct));
        $I->fillField(QuotationManagerPage::$quanlityFirst, $quantity);


        $I->click(QuotationManagerPage::$buttonSave);
        $I->see(QuotationManagerPage::$messageSaveSuccess, QuotationManagerPage::$selectorSuccess);
    }

    public function editQuotation($newQuantity)
    {
        $I = $this;
        $I->amOnPage(QuotationManagerPage::$URL);
        $I->click(QuotationManagerPage::$quotationId);
        $I->waitForElement(QuotationManagerPage::$quantityp1,30);
        $I->fillField(QuotationManagerPage::$quantityp1, $newQuantity);
        $I->click(QuotationManagerPage::$buttonSave);
        $I->see(QuotationManagerPage::$messageSaveSuccess, QuotationManagerPage::$selectorSuccess);
    }
    
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

    public function deleteQuotation()
    {
        $I = $this;
        $I->amOnPage(QuotationManagerPage::$URL);
        $I->checkAllResults();
        $I->click(QuotationManagerPage::$buttonDelete);
        $I->see(QuotationManagerPage::$messageDeleteSuccess, QuotationManagerPage::$selectorSuccess);
    }

}
