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
class SupplierSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Supplier
	 *
	 * @param   string  $supplierName   Supplier name
	 * @param   string  $supplierEmail  Supplier email
	 *
	 * @return void
	 */
	public function addSupplier($supplierName, $supplierEmail)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonNew);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->fillField(\SupplierPage::$fieldName, $supplierName);
		$client->fillField(\SupplierPage::$fieldEmail, $supplierEmail);
		$client->click(\SupplierPage::$buttonSaveClose);
		$client->waitForText(\SupplierPage::$messageItemSaveSuccess, 60, \SupplierPage::$selectorSuccess);
		$client->see(\SupplierPage::$messageItemSaveSuccess, \SupplierPage::$selectorSuccess);
		$client->searchSupplier($supplierName);
		$client->see($supplierName, \SupplierPage::$resultRow);
	}

	/**
	 * Function to Add a New Supplier with Save button
	 *
	 * @param   string  $supplierName   Supplier name
	 * @param   string  $supplierEmail  Supplier email
	 *
	 * @return void
	 */
	public function addSupplierSave($supplierName, $supplierEmail)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonNew);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->fillField(\SupplierPage::$fieldName, $supplierName);
		$client->fillField(\SupplierPage::$fieldEmail, $supplierEmail);
		$client->click(\SupplierPage::$buttonSave);
		$client->waitForText(\SupplierPage::$messageItemSaveSuccess, 60, \SupplierPage::$selectorSuccess);
		$client->see(\SupplierPage::$messageItemSaveSuccess, \SupplierPage::$selectorSuccess);
		$client->searchSupplier($supplierName);
		$client->see($supplierName, \SupplierPage::$resultRow);
	}

	/**
	 * @return void
	 */
	public function checkCancelButton()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonNew);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->click(\SupplierPage::$buttonCancel);
	}

	public function addSupplierSaveMissingName($supplierEmailId)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonNew);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->fillField(\SupplierPage::$fieldEmail, $supplierEmailId);
		$client->click(\SupplierPage::$buttonSave);
		$client->waitForText(\SupplierPage::$fieldMissing, 60, \SupplierPage::$selectorMissing);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
	}

	public function addSupplierWrongEmail($supplierName, $supplierEmail)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonNew);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->fillField(\SupplierPage::$fieldName, $supplierName);
		$client->fillField(\SupplierPage::$fieldEmail, $supplierEmail);
		$client->click(\SupplierPage::$buttonSave);
		$client->waitForText(\SupplierPage::$fieldEmailInvalid, 60, \SupplierPage::$selectorMissing);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
	}

	public function searchSupplier($supplierName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->waitForText(\SupplierPage::$namePage, 30, \SupplierPage::$headPage);
		$client->filterListBySearching($supplierName);
	}

	public function editSupplier($supplierName, $supplierUpdatedName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchSupplier($supplierName);
		$client->click($supplierName);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->fillField(\SupplierPage::$fieldName, $supplierUpdatedName);
		$client->click(\SupplierPage::$buttonSaveClose);
		$client->waitForText(\SupplierPage::$messageItemSaveSuccess, 60, \SupplierPage::$selectorSuccess);
		$client->see(\SupplierPage::$messageItemSaveSuccess, \SupplierPage::$selectorSuccess);
	}

	public function editSupplierMissingName($supplierUpdatedName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchSupplier($supplierUpdatedName);
		$client->click($supplierUpdatedName);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->fillField(\SupplierPage::$fieldName, "");
		$client->click(\SupplierPage::$buttonSave);
		$client->waitForText(\SupplierPage::$fieldMissing, 60, \SupplierPage::$selectorMissing);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
	}

	public function editSupplierCheckCloseButton($supplierUpdatedName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchSupplier($supplierUpdatedName);
		$client->click($supplierUpdatedName);
		$client->waitForElement(\SupplierPage::$fieldName, 30);
		$client->click(\SupplierPage::$buttonClose);
	}

	// Unpublish supplier
	public function changeSupplierState($supplierName, $state = 'unpublish')
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->searchSupplier($supplierName);
		$client->wait(3);
		$client->see($supplierName, \SupplierPage::$resultRow);
		$client->checkAllResults();
		$client->click(\SupplierPage::$statePath);
	}

	public function changeSupplierStatePublish($supplierName, $state = 'publish')
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->searchSupplier($supplierName);
		$client->wait(3);
		$client->see($supplierName, \SupplierPage::$resultRow);
		$client->checkAllResults();
		$client->click(\SupplierPage::$statePath);
	}

	public function getSupplierState($supplierName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->searchSupplier($supplierName);
		$client->wait(3);
		$client->see($supplierName, \SupplierPage::$resultRow);
		$text = $client->grabAttributeFrom(\SupplierPage::$statePath, 'onclick');
		echo "Get status text " . $text;

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}

		echo "Status need show" . $result;

		return $result;
	}

	public function deleteSupplierCancel($supplierName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchSupplier($supplierName);
		$client->checkAllResults();
		$client->click(\SupplierPage::$buttonDelete);
		$client->cancelPopup();
	}

	public function deleteSupplier($supplierName)
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchSupplier($supplierName);
		$client->checkAllResults();
		$client->click(\SupplierPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\SupplierPage::$messageItemDeleteSuccess, 60, \SupplierPage::$selectorSuccess);
		$client->see(\SupplierPage::$messageItemDeleteSuccess, \SupplierPage::$selectorSuccess);
		$client->fillField(\SupplierPage::$searchField, $supplierName);
		$client->pressKey(\SupplierPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($supplierName, \SupplierPage::$resultRow);
	}

	public function checkDeleteButton()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonDelete);
		$client->acceptPopup();
	}

	public function checkPublishButton()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonPublish);
		$client->acceptPopup();
	}

	public function unpublishAllSupplier()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->checkAllResults();
		$client->click(\SupplierPage::$buttonUnpublish);
		$client->waitForText(\SupplierPage::$messageHead, 30, \SupplierPage::$selectorSuccess);
	}

	public function publishAllSupplier()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->checkAllResults();
		$client->click(\SupplierPage::$buttonPublish);
		$client->waitForText(\SupplierPage::$messageHead, 30, \SupplierPage::$selectorSuccess);
	}

	public function checkUnpublishButton()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonUnpublish);
		$client->acceptPopup();
	}

	public function checkCheckinButton()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\SupplierPage::$buttonCheckIn);
		$client->acceptPopup();
	}

	public function checkinSupplier()
	{
		$client = $this;
		$client->amOnPage(\SupplierPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->checkAllResults();
		$client->click(\SupplierPage::$buttonCheckIn);
		$client->waitForText(\SupplierPage::$messageHead, 30, \SupplierPage::$selectorSuccess);
	}
}