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
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

	/**
	 * Test delete button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function checkButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Delete button without choice vat creation in Administrator');
		$client = new TaxGroupSteps($scenario);
        $client->checkButton('delete');
        $client->checkButton('publish');
        $client->checkButton('unpublish');

        $client->wantTo('Test VAT Groups and clicks on Cancel in Administrator');
        $client->addVATGroupsCancel();
        $client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);

        $client->wantTo('Test VAT Groups  Save (Missing Name) creation in Administrator');
        $client->addVATGroupsMissingName();
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
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);
		$client->see(\TaxGroupPage::$messageItemSaveSuccess, \TaxGroupPage::$selectorSuccess);

        $client->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
        $client->addVATGroupsSaveClose($this->taxGroupNameSaveClose);
        $client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);

        $client->wantTo('Test VAT Groups - Edit without name in Administrator');
        $client = new TaxGroupSteps($scenario);
        $client->editVATGroupsWithoutName($this->taxGroupNameSaveClose);

        $client->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
        $client = new TaxGroupSteps($scenario);
        $client->editVATGroupsName($this->taxGroupNameSaveClose, $this->taxGroupNameEdit);
        $client->searchVATGroup($this->taxGroupNameEdit);
        $client->wait(3);
        $client->see($this->taxGroupNameEdit, \TaxGroupPage::$resultRow);

        $client->wantTo('Test VAT Groups  Save $ Close creation in Administrator');
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
	public function unpublishAllGroups(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Unpublish all VAT/tax Group in Administrator');
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
		$client = new TaxGroupSteps($scenario);
		$client->deleteVATGroupCancel($this->taxGroupNameSaveClose);

        $client->wantTo('Delete VAT/tax Group - OK in Administrator');
        $client->deleteVATGroupOK($this->taxGroupNameSaveClose);
	}
}
