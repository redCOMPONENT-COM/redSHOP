<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TaxGroupSteps;
use Codeception\Scenario;

/**
 * Class TaxGroupCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class TaxGroupCest
{
	/**
	 * @var string
	 */
	public $taxGroupName;

	/**
	 * @var string
	 */
	public $taxGroupNameSaveClose;

	/**
	 * @var string
	 */
	public $taxGroupNameEdit;

	/**
	 * TaxGroupCest constructor.
	 */
	public function __construct()
	{
		$this->taxGroupName          = 'Testing VAT Groups' . rand(100, 999);
		$this->taxGroupNameSaveClose = 'Testing VAT Groups' . rand(10, 100);
		$this->taxGroupNameEdit      = "Testing VAT Edit";
	}

	/**
	 * Test delete button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function deleteButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Delete button without choice vat creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->deleteButton();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 * Test publish button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function publishButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test check publish button without choice vat creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->publishButton();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 * Test un-publish button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function unpublishButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test check unpublish button without choice vat creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->unpublishButton();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function createVATGroupSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups  Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);
		$client->see(\TaxGroupPage::$messageItemSaveSuccess, \TaxGroupPage::$selectorSuccess);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addVATGroupsSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSaveClose($this->taxGroupNameSaveClose);
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function editVATGroupsWithoutName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups - Edit without name in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->editVATGroupsWithoutName($this->taxGroupNameSaveClose);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function editVATGroupsName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->editVATGroupsName($this->taxGroupNameSaveClose, $this->taxGroupNameEdit);
		$client->searchVATGroup($this->taxGroupNameEdit);
		$client->wait(3);
		$client->see($this->taxGroupNameEdit, \TaxGroupPage::$resultRow);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function editVATGroupsNameSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->editVATGroupsNameSaveClose($this->taxGroupNameEdit, $this->taxGroupNameSaveClose);
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
		$client->searchVATGroup($this->taxGroupNameSaveClose);
		$client->wait(3);
		$client->see($this->taxGroupNameSaveClose, \TaxGroupPage::$resultRow);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addVATGroupsCancel(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups  Cancel creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsCancel();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function unpublishAllGroups(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Unpublish all VAT/tax Group in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->unpublishAllGroups();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function publishAllVATGroups(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Publish all VAT/tax Group in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->publishAllGroups();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function unpublishVATGroupsWithName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('UnPublish all VAT/tax Group in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->changeVATGroupState($this->taxGroupNameSaveClose);
		$currentState = $client->getVATGroupsState($this->taxGroupNameSaveClose);
		$client->verifyState('unpublished', $currentState);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function publishAllVATGroupsWithName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Publish all VAT/tax Group in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->changeVATGroupState($this->taxGroupNameSaveClose);
		$currentState = $client->getVATGroupsState($this->taxGroupNameSaveClose);
		$client->verifyState('published', $currentState);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function deleteVATGroupCancel(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Delete VAT/tax Group - Cancel in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->deleteVATGroupCancel($this->taxGroupNameSaveClose);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function deleteVATGroupOK(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Delete VAT/tax Group - OK in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->deleteVATGroupOK($this->taxGroupNameSaveClose);
	}

	/**
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addVATGroupsMissingName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test VAT Groups  Save (Missing Name) creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsMissingName();
	}
}
