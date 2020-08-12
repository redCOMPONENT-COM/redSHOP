<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class ManufacturerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ManufacturerSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Delete, Step\Traits\Publish;

	/**
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkCancelButton()
	{
		$client = $this;
		$client->amOnPage(ManufacturerPage::$url);
		$client->click(ManufacturerPage::$buttonNew);
		$client->waitForElement(ManufacturerPage::$fieldName, 30);
		$client->click(ManufacturerPage::$buttonCancel);
	}

	/**
	 * Bad case: Missing name
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addManufacturerMissingName()
	{
		$client = $this;
		$client->amOnPage(ManufacturerPage::$url);
		$client->click(ManufacturerPage::$buttonNew);
		$client->waitForElement(ManufacturerPage::$fieldName, 30);
		$client->fillField(ManufacturerPage::$fieldName, '');
		$client->click(ManufacturerPage::$buttonSave);
		$client->waitForText(ManufacturerPage::$fieldMissing, 60, ManufacturerPage::$selectorMissing);
		$client->waitForElement(ManufacturerPage::$fieldName, 30);
		$client->click(ManufacturerPage::$buttonCancel);
	}

	/**
	 * Bad case: Wrong email format
	 *
	 * @param   string $name  Name
	 * @param   string $email Email
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addManufacturerWrongEmail($name = '', $email = '')
	{
		$client = $this;
		$client->amOnPage(ManufacturerPage::$url);
		$client->click(ManufacturerPage::$buttonNew);
		$client->waitForElement(ManufacturerPage::$fieldName, 30);
		$client->fillField(ManufacturerPage::$fieldName, $name);
		$client->fillField(ManufacturerPage::$fieldEmail, $email);
		$client->click(ManufacturerPage::$buttonSave);
		$client->waitForText(ManufacturerPage::$fieldEmailInvalid, 60, ManufacturerPage::$selectorMissing);
		$client->waitForElement(ManufacturerPage::$fieldName, 30);
		$client->click(ManufacturerPage::$buttonCancel);
	}

	/**
	 * @param $manufacturer
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function addManufacturer($manufacturer)
	{
		$client = $this;
		$client->amOnPage(ManufacturerPage::$url);
		$client->click(ManufacturerPage::$buttonNew);
		$client->waitForElementVisible(ManufacturerPage::$fieldName, 30);
		$client->fillField(ManufacturerPage::$fieldName, $manufacturer['name']);
		$client->waitForElementVisible(ManufacturerPage::$templateID, 30);
		$client->click(ManufacturerPage::$templateID);
		$client->fillField(ManufacturerPage::$templateSearch, $manufacturer['template']);
		$client->pressKey(ManufacturerPage::$templateSearch, Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->fillField(ManufacturerPage::$fieldEmail, $manufacturer['email']);
		$client->waitForElementVisible(ManufacturerPage::$fieldProductPerPage,30);
		$client->fillField(ManufacturerPage::$fieldProductPerPage, $manufacturer['productPerPage']);
		$client->click(ManufacturerPage::$buttonSaveClose);
		$client->waitForText(ManufacturerPage::$messageItemSaveSuccess, 60, ManufacturerPage::$selectorSuccess);
	}

	/**
	 * @param $manufacturerName
	 * @throws \Exception
	 * @since 3.0.3
	 */
	public function deleteManufacturer($manufacturerName)
	{
		$I = $this;
		$I->amOnPage(ManufacturerPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(ManufacturerPage::$namePage, 30, ManufacturerPage::$h1);
		$I->filterListBySearchingProduct($manufacturerName, ManufacturerPage::$searchField);
		$I->waitForElementVisible(ManufacturerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(ManufacturerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(ManufacturerPage::$messageNoItemOnTable, 60, ManufacturerPage::$selectorSuccess);
		$I->dontSee($manufacturerName);
	}
}
