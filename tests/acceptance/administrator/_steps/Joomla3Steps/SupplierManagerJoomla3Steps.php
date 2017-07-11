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
<<<<<<< HEAD
=======
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
		$I->checkForPhpNoticesOrWarnings();
		$I->click('New');
		$I->waitForElement($this->supplierNameField, 30);
		$I->fillField($this->supplierNameField, $supplierName);
		$I->fillField($this->supplierEmailId, $supplierEmail);
		$I->checkForPhpNoticesOrWarnings();
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
		$I->checkForPhpNoticesOrWarnings();
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
		$I->checkForPhpNoticesOrWarnings();
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
>>>>>>> a89af999abc24249eece87e8900f48fe113d9b34

    /**
     * Function to Add a New Supplier
     *
     * @return void
     */
    public function addSupplier($supplierName, $supplierEmail)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierName);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmail);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('Save & Close');
        $I->waitForText(\SupplierManagerPage::$supplierSuccessMessage, 60, '.alert-success');
        $I->see(\SupplierManagerPage::$supplierSuccessMessage, '.alert-success');
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
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierName);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmail);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('Save');
        $I->waitForText(\SupplierManagerPage::$supplierSuccessMessage, 60, '.alert-success');
        $I->see(\SupplierManagerPage::$supplierSuccessMessage, '.alert-success');
        $I->searchSupplier($supplierName);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
    }


    public function checkCancelButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->click('Cancel');
    }

    public function addSupplierSaveMissingName($supplierEmailId)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmailId);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('Save');
        $I->waitForText(\SupplierManagerPage::$fieldMissing, 60, '.alert-danger');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
    }


    public function addSupplierWrongEmail($supplierName, $supplierEmail)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierName);
        $I->fillField(\SupplierManagerPage::$supplierEmailId, $supplierEmail);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('Save');
        $I->waitForText(\SupplierManagerPage::$fieldEmailInvalid, 60, '.alert-danger');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
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
        $I->checkForPhpNoticesOrWarnings();
        $I->searchSupplier($supplierName);
        $I->click($supplierName);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, $supplierUpdatedName);
        $I->click('Save & Close');
        $I->waitForText(\SupplierManagerPage::$supplierSuccessMessage, 60, '.alert-success');
        $I->see(\SupplierManagerPage::$supplierSuccessMessage, '.alert-success');

    }

    public function editSupplierMissingName($supplierUpdatedName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->searchSupplier($supplierUpdatedName);
        $I->click($supplierUpdatedName);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->fillField(\SupplierManagerPage::$supplierNameField, "");
        $I->click('Save');
        $I->waitForText(\SupplierManagerPage::$fieldMissing, 60, '.alert-danger');
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
    }


    public function editSupplierCheckCloseButton($supplierUpdatedName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->searchSupplier($supplierUpdatedName);
        $I->click($supplierUpdatedName);
        $I->waitForElement(\SupplierManagerPage::$supplierNameField, 30);
        $I->click('Close');
    }


    // Unpublish supplier

    public function changeSupplierState($supplierName, $state = 'unpublish')
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->searchSupplier($supplierName);
        $I->wait(3);
        $I->see($supplierName, \SupplierManagerPage::$supplierResultRow);
        $I->click(\SupplierManagerPage::$checkAllSupplier);

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
        $I->click(\SupplierManagerPage::$checkAllSupplier);

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
        $I->checkForPhpNoticesOrWarnings();
        $I->searchSupplier($supplierName);
        $I->checkAllResults();
        $I->click("Delete");
        $I->cancelPopup();
    }


    public function deleteSupplier($supplierName)
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->searchSupplier($supplierName);
        $I->checkAllResults();
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForText("1 item successfully deleted", 60, '.alert-success');
        $I->see("1 item successfully deleted", '.alert-success');
        $I->fillField(\SupplierManagerPage::$searchField, $supplierName);
        $I->pressKey(\SupplierManagerPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($supplierName, \SupplierManagerPage::$supplierResultRow);
    }


    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Delete");
        $I->acceptPopup();
    }

    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Publish");
        $I->acceptPopup();
    }


    public function unpublishAllSupplier()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\SupplierManagerPage::$checkAllSupplier);
        $I->click("Unpublish");
        $I->waitForText("Message", 30, '.alert-success');
    }

    public function publishAllSupplier()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\SupplierManagerPage::$checkAllSupplier);
        $I->click("Publish");
        $I->waitForText("Message", 30, '.alert-success');
    }

    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Unpublish");
        $I->acceptPopup();
    }

    public function checkCheckinButton()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Check-in");
        $I->acceptPopup();
    }

    public function checkinSupplier()
    {
        $I = $this;
        $I->amOnPage(\SupplierManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\SupplierManagerPage::$checkAllSupplier);
        $I->click("Check-in");
        $I->waitForText("Message", 30, '.alert-success');
    }
}
