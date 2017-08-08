<?php
/**
 */


class ManagerVATGroupAdministratorCest
{
	public function __construct()
	{
		$this->VATGroupName          = 'Testing VAT Groups' . rand(100, 999);
		$this->VATGroupNameSaveClose = 'Testing VAT Groups' . rand(10, 100);
		$this->VATGroupNameEdit      = "Testing VAT Edit";
	}

	public function deleteButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Delete button without choice vat creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Test check Delete button without choice vat');
		$I->deleteButton();
		$I->see("VAT / Tax Group Management", '.page-title');
	}

	public function publishButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test check publish button without choice vat creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Test check publish button without choice vat');
		$I->publishButton();
		$I->see("VAT / Tax Group Management", '.page-title');
	}

	public function unpublishButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test check unpublish button without choice vat creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Test check unpublish  button without choice vat');
		$I->unpublishButton();
		$I->see("VAT / Tax Group Management", '.page-title');
	}

	public function createVATGroupSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Save creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create VAT groups  Save button');
		$I->addVATGroupsSave($this->VATGroupName);
		$I->see("item saved", '.alert-success');
	}

	public function addVATGroupsSaveClose(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create VAT groups  Save $ Close  button');
		$I->addVATGroupsSaveClose($this->VATGroupNameSaveClose);
		$I->see("VAT / Tax Group Management", '.page-title');
	}


	public function editVATGroupsWithoutName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create VAT groups  Save $ Close  button');
		$I->editVATGroupsWithoutName($this->VATGroupNameSaveClose);
	}


	public function editVATGroupsName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->editVATGroupsName($this->VATGroupNameSaveClose, $this->VATGroupNameEdit);
		$I->searchVATGroup($this->VATGroupNameEdit);
		$I->wait(3);
		$I->see($this->VATGroupNameEdit, \VATGroupManagerJoomla3Page::$resultRow);
	}

	public function editVATGroupsNameSaveClose(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->editVATGroupsNameSaveClose($this->VATGroupNameEdit, $this->VATGroupNameSaveClose);
		$I->see("VAT / Tax Group Management", '.page-title');
		$I->searchVATGroup($this->VATGroupNameSaveClose);
		$I->wait(3);
		$I->see($this->VATGroupNameEdit, \VATGroupManagerJoomla3Page::$resultRow);
	}

	public function addVATGroupsCancel(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Cancel creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create VAT groups  Cancel button');
		$I->addVATGroupsCancel();
		$I->see("VAT / Tax Group Management", '.page-title');
	}

	public function unpublishAllGroups(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Unpublish all VAT/tax Group in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Unpublish all VAT/tax Group a Category');
		$I->unpublishAllGroups();
		$I->see("VAT / Tax Group Management", '.page-title');
	}


	public function publishAllVATGroups(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish all VAT/tax Group in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Publish all VAT/tax Group a Category');
		$I->publishAllGroups();
		$I->see("VAT / Tax Group Management", '.page-title');
	}

	public function unpublishVATGroupsWithName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('UnPublish  VAT/tax Group in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Unpublish all VAT/tax Group a Category');
		$I->changeVATGroupState($this->VATGroupNameSaveClose);
		$currentState = $I->getVATGroupsState($this->VATGroupNameSaveClose);
		$I->verifyState('unpublished', $currentState);
	}

	public function publishAllVATGroupsWithName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish  VAT/tax Group in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Publish  VAT/tax Group a Category');
		$I->changeVATGroupState($this->VATGroupNameSaveClose);
		$currentState = $I->getVATGroupsState($this->VATGroupNameSaveClose);
		$I->verifyState('published', $currentState);
	}


	public function deleteVATGroupCancel(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish  VAT/tax Group in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Publish  VAT/tax Group a Category');
		$I->deleteVATGroupCancel($this->VATGroupNameSaveClose);
	}

	public function deleteVATGroupOK(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish  VAT/tax Group in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Publish  VAT/tax Group a Category');
		$I->deleteVATGroupOK($this->VATGroupNameSaveClose);
	}

	public function addVATGroupsMissingName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test VAT Groups  Save creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create VAT groups  Save button');
		$I->addVATGroupsMissingName();
	}
}