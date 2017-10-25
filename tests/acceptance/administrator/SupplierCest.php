<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class SupplierCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class SupplierCest
{
	/**
	 * @var  string
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $supplierName = '';

	/**
	 * @var string
	 */
	public $supplierNameSave = '';

	/**
	 * @var string
	 */
	public $supplierUpdatedName = '';

	/**
	 * @var string
	 */
	public $supplierEmail = '';

	/**
	 * @var string
	 */
	public $supplierEmailSave = '';

	/**
	 * ManageSupplierAdministratorCest constructor.
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->supplierName        = $this->faker->bothify('ManageSupplierAdministratorCest ?##?');
		$this->supplierNameSave    = $this->faker->bothify('ManageSupplierSaveAdministratorCest ?##?');
		$this->supplierUpdatedName = $this->faker->bothify('Supplier Updated Name ?##?');
		$this->supplierEmail       = $this->faker->email();
		$this->supplierEmailSave   = $this->faker->email();
	}

	/**
	 *
	 * Function check delete button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   object            $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function checkDeleteButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Delete button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->checkDeleteButton();
	}

	/**
	 *
	 * Function ccheck publish button
	 *
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function checkPublishButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Publish button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->checkPublishButton();
	}

	/**
	 *
	 * Function check unpublish button
	 *
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function checkUnpublishButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Unpublish button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->checkUnpublishButton();
	}

	/**
	 *
	 * Function check checkin button
	 *
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function checkCheckinButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test CheckiIn button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->checkCheckinButton();
	}

	/**
	 *
	 * Function add supplier when missing name
	 *
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function addSupplierSaveMissingName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier Missing Name creation in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->addSupplierSaveMissingName($this->supplierEmail);
	}

//    /*
//     * This case will not run because at code pass this issues .
//     */
//    public function addSupplierWrongEmail(AcceptanceTester $client, $scenario)
//    {
//        $client->wantTo('Test Supplier email with wrong email creation in Administrator');
//        $client->doAdministratorLogin();
//        $client = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
//        $client->addSupplierWrongEmail($this->supplierName,$this->supplierName);
//    }

	/**
	 * Function to Test Supplier Creation in Backend
	 * Create supplier with save and close button
	 *
	 */
	public function createSupplier(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier creation with save and close button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->addSupplier($this->supplierName, $this->supplierEmail);
	}

	/**
	 * Function create supplier with save button
	 *
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function createSupplierSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier creation with save in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->addSupplierSave($this->supplierNameSave, $this->supplierEmailSave);
	}

	/**
	 * Function check cancel button
	 *
	 * @param AcceptanceTester $client
	 * @param                  $scenario
	 */
	public function checkCancelButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier check cancel button inside in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->checkCancelButton();
		$client->see(\SupplierPage::$namePage, \SupplierPage::$selectorPageTitle);
	}


	//unpublish
	public function unpublishAllSupplier(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test unpublish all supplier Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->unpublishAllSupplier();
		$client->see(\SupplierPage::$namePage, \SupplierPage::$selectorPageTitle);
	}


	//publish
	public function publishAllSupplier(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test publish all supplier Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->publishAllSupplier();
		$client->see(\SupplierPage::$namePage, \SupplierPage::$selectorPageTitle);
	}

	//unpublish
	public function checkinSupplier(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test check in all supplier Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->checkinSupplier();
		$client->see(\SupplierPage::$namePage, \SupplierPage::$selectorPageTitle);
	}

	public function changeSupplierStateUnpublish(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if update status to unpublish of Supplier gets Updated in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->wantTo(' update status of Supplier');
		$client->changeSupplierState($this->supplierNameSave, 'unpublish');
		$currentState = $client->getSupplierState($this->supplierNameSave);
		$client->verifyState('unpublished', $currentState);
	}

	public function changeSupplierStatePublish(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test change publish Supplier gets Updated in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->wantTo(' update status of Supplier');
		$client->changeSupplierStatePublish($this->supplierNameSave, 'publish');
		$currentState = $client->getSupplierState($this->supplierNameSave);
		$client->verifyState('published', $currentState);
	}

	/**
	 * Function to Test Supplier Update in Backend
	 *
	 */
	public function editSupplier(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier update name  in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->editSupplier($this->supplierName, $this->supplierUpdatedName);
	}


	/**
	 * Function to Test Supplier Update in Backend
	 *
	 */
	public function editSupplierMissingName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier update missing name in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->editSupplierMissingName($this->supplierUpdatedName);
	}


	public function editSupplierCheckCloseButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier check Close button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->editSupplierCheckCloseButton($this->supplierUpdatedName);
	}

	/**
	 * Function to Test Supplier Update in Backend
	 *
	 */
	public function deleteSupplierCancel(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier Deletion Then clicks cancel button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->deleteSupplierCancel($this->supplierUpdatedName);
		$client->see(\SupplierPage::$namePage, \SupplierPage::$selectorPageTitle);

	}

	/**
	 * Function to Test Supplier Update in Backend
	 *
	 */
	public function deleteSupplier(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Supplier Deletion in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\SupplierSteps($scenario);
		$client->deleteSupplier($this->supplierUpdatedName);
	}
}
