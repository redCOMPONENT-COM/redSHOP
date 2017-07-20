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
        $I->click(\SupplierManagerPage::$newButton);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierName);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmail);
        $I->click(\SupplierManagerPage::$saveCloseButton);
        $I->waitForText(\SupplierManagerPage::$supplierSuccessMessage, 60, \SupplierManagerPage::$selectorSuccess);
        $I->see(\SupplierManagerPage::$supplierSuccessMessage, \SupplierManagerPage::$selectorSuccess);
        $I->searchSupplier($supplierName);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
    }

    /**
     * Function to Add a New Supplier with Save button
     *
     * @return void
     */
    public function addSupplierSave($supplierName, $supplierEmail)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$newButton);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierName);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmail);
        $I->click(\SupplierManagerPage::$saveButton);
        $I->waitForText(\SupplierManagerPage::$supplierSuccessMessage, 60, \SupplierManagerPage::$selectorSuccess);
        $I->see(\SupplierManagerPage::$supplierSuccessMessage, \SupplierManagerPage::$selectorSuccess);
        $I->searchSupplier($supplierName);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
    }

    public function checkCancelButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$newButton);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->click(\SupplierManagerPage::$cancelButton);
    }

    public function addSupplierSaveMissingName($supplierEmailId)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$newButton);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmailId);
        $I->click(\SupplierManagerPage::$saveButton);
        $I->waitForText(\SupplierManagerPage::$fieldMissing, 60, \SupplierManagerPage::$selectorMissing);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
    }

    public function addSupplierWrongEmail($supplierName, $supplierEmail)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$newButton);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierName);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmail);
        $I->click(\SupplierManagerPage::$saveButton);
        $I->waitForText(\SupplierManagerPage::$fieldEmailInvalid, 60, \SupplierManagerPage::$selectorMissing);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
    }

    public function searchSupplier($supplierName)
    {
        $I = $this;
        $I->wantTo('Search the Supplier');
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->waitForText(\SupplierManagerPage::$namePage, 30, \SupplierManagerPage::$headPage);
        $I->filterListBySearching($supplierName);
    }

    public function editSupplier($supplierName, $supplierUpdatedName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->click($supplierName);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierUpdatedName);
        $I->click(\SupplierManagerPage::$saveCloseButton);
        $I->waitForText(\SupplierManagerPage::$supplierSuccessMessage, 60, \SupplierManagerPage::$selectorSuccess);
        $I->see(\SupplierManagerPage::$supplierSuccessMessage, \SupplierManagerPage::$selectorSuccess);
    }

    public function editSupplierMissingName($supplierUpdatedName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierUpdatedName);
        $I->click($supplierUpdatedName);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, "");
        $I->click(\SupplierManagerPage::$saveButton);
        $I->waitForText(\SupplierManagerPage::$fieldMissing, 60, \SupplierManagerPage::$selectorMissing);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
    }

    public function editSupplierCheckCloseButton($supplierUpdatedName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierUpdatedName);
        $I->click($supplierUpdatedName);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->click(\SupplierManagerPage::$closeButton);
    }

    // Unpublish supplier
    public function changeSupplierState($supplierName, $state = 'unpublish')
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->wait(3);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
        $I->checkAllResults();
        if ($state == 'unpublish') {
            $I->click(\SupplierManagerPage::$supplierStatePath);
        } else {
            $I->click(\SupplierManagerPage::$supplierStatePath);
        }
    }

    public function changeSupplierStatePublish($supplierName, $state = 'publish')
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->wait(3);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
        $I->checkAllResults();
        if ($state == 'publish') {
            $I->click(\SupplierManagerPage::$supplierStatePath);
        } else {
            $I->click(\SupplierManagerPage::$supplierStatePath);
        }
    }

    public function getSupplierState($supplierName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->wait(3);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
        $text = $I->grabAttributeFrom(\SupplierManagerPage::$supplierStatePath, 'onclick');
        echo "Get status text " . $text;
        if (strpos($text, 'unpublish') > 0) {
            $result = 'published';
        } else {
            $result = 'unpublished';
        }
        echo "Status need show" . $result;
        return $result;
    }

    public function deleteSupplierCancel($supplierName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->checkAllResults();
        $I->click(\SupplierManagerPage::$deleteButton);
        $I->cancelPopup();
    }

    public function deleteSupplier($supplierName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->checkAllResults();
        $I->click(\SupplierManagerPage::$deleteButton);
        $I->acceptPopup();
        $I->waitForText(\SupplierManagerPage::$messageDeletedOneSuccess, 60, \SupplierManagerPage::$selectorSuccess);
        $I->see(\SupplierManagerPage::$messageDeletedOneSuccess, \SupplierManagerPage::$selectorSuccess);
        $I->fillField(\SupplierManagerPage::$searchField, $supplierName);
        $I->pressKey(\SupplierManagerPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($supplierName, \SupplierManagerPage::$supplierResultRow);
    }

    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$deleteButton);
        $I->acceptPopup();
    }

    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$publish);
        $I->acceptPopup();
    }

    public function unpublishAllSupplier()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\SupplierManagerPage::$unpublish);
        $I->waitForText(\SupplierManagerPage::$messageHead, 30, \SupplierManagerPage::$selectorSuccess);
    }

    public function publishAllSupplier()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\SupplierManagerPage::$publish);
        $I->waitForText(\SupplierManagerPage::$messageHead, 30, \SupplierManagerPage::$selectorSuccess);
    }

    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$unpublish);
        $I->acceptPopup();
    }

    public function checkCheckinButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->click(\SupplierManagerPage::$checkinButton);
        $I->acceptPopup();
    }

    public function checkinSupplier()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\SupplierManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\SupplierManagerPage::$checkinButton);
        $I->waitForText(\SupplierManagerPage::$messageHead, 30, \SupplierManagerPage::$selectorSuccess);
    }
}