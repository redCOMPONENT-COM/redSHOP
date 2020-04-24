<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class SupplierManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class SupplierSteps extends AbstractStep
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
		$client->amOnPage(SupplierPage::$url);
		$client->click(SupplierPage::$buttonNew);
		$client->waitForElement(SupplierPage::$fieldName, 30);
		$client->click(SupplierPage::$buttonCancel);
	}

	/**
	 * Bad case: Missing name
	 *
	 * @param   string $supplierEmailId Email
	 *
	 * @return  void
	 * @since 1.4.0
	 * @throws \Exception
	 */
	public function addSupplierSaveMissingName($supplierEmailId)
	{
		$client = $this;
		$client->amOnPage(SupplierPage::$url);
		$client->click(SupplierPage::$buttonNew);
		$client->waitForElement(SupplierPage::$fieldName, 30);
		$client->fillField(SupplierPage::$fieldEmail, $supplierEmailId);
		$client->click(SupplierPage::$buttonSave);
		$client->waitForText(SupplierPage::$fieldMissing, 60, SupplierPage::$selectorMissing);
		$client->waitForElement(SupplierPage::$fieldName, 30);
		$client->click(SupplierPage::$buttonCancel);
	}

	/**
	 * @param $supplierName
	 * @param $supplierEmail
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function addSupplierWrongEmail($supplierName, $supplierEmail)
	{
		$client = $this;
		$client->amOnPage(SupplierPage::$url);
		$client->click(SupplierPage::$buttonNew);
		$client->waitForElement(SupplierPage::$fieldName, 30);
		$client->fillField(SupplierPage::$fieldName, $supplierName);
		$client->fillField(SupplierPage::$fieldEmail, $supplierEmail);
		$client->click(SupplierPage::$buttonSave);
		$client->waitForText(SupplierPage::$fieldEmailInvalid, 60, SupplierPage::$selectorMissing);
		$client->waitForElement(SupplierPage::$fieldName, 30);
		$client->click(SupplierPage::$buttonCancel);
	}

	/**
	 * @param $supplierUpdatedName
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function editSupplierCheckCloseButton($supplierUpdatedName)
	{
		$client = $this;
		$client->amOnPage(SupplierPage::$url);
		$client->searchSupplier($supplierUpdatedName);
		$client->click($supplierUpdatedName);
		$client->waitForElement(SupplierPage::$fieldName, 30);
		$client->click(SupplierPage::$buttonClose);
	}

	/**
	 * @param $supplierName
	 * @since 1.4.0
	 */
	public function deleteSupplierCancel($supplierName)
	{
		$client = $this;
		$client->amOnPage(SupplierPage::$url);
		$client->searchSupplier($supplierName);
		$client->checkAllResults();
		$client->click(SupplierPage::$buttonDelete);
		$client->cancelPopup();
	}

}