<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class SupplierManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class SupplierManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    private $supplierNameField = ['id' => "jform_name"];
    private $supplierEmailId = ['id' => "jform_email"];
    private $supplierSuccessMessage = "Item saved.";
    private $supplierResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";
    private $searchField = ['id' => 'filter_search'];
    /**
     * Function to Add a New Supplier
     *
     * @return void
     */
    public function addSupplier($supplierName, $supplierEmail)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click('New');
        $I->waitForElement($this->supplierNameField, 30);
        $I->fillField($this->supplierNameField, $supplierName);
        $I->fillField($this->supplierEmailId, $supplierEmail);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click('Save & Close');
        $I->waitForText($this->supplierSuccessMessage, 60, '.alert-success');
        $I->see($this->supplierSuccessMessage, '.alert-success');
        $I->searchSupplier($supplierName);
        $I->see($supplierName, $this->supplierResultRow);
    }
    public function searchSupplier($supplierName)
    {
        $I = $this;
        $I->wantTo('Search the Supplier');
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->waitForText('Supplier Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearching($supplierName);
    }
    public function editSupplier($supplierName, $supplierUpdatedName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->click($supplierName);
        $I->waitForElement($this->supplierNameField, 30);
        $I->fillField($this->supplierNameField, $supplierUpdatedName);
        $I->click('Save & Close');
        $I->waitForText($this->supplierSuccessMessage, 60, '.alert-success');
        $I->see($this->supplierSuccessMessage, '.alert-success');
        $I->searchSupplier($supplierUpdatedName);
        $I->see($supplierUpdatedName, $this->supplierResultRow);
    }
    public function deleteSupplier($supplierName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->checkAllResults();
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForText("1 item successfully deleted", 60, '.alert-success');
        $I->see("1 item successfully deleted", '.alert-success');
        $I->fillField($this->searchField, $supplierName);
        $I->pressKey($this->searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($supplierName, $this->supplierResultRow);
    }
}