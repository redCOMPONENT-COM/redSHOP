<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use Codeception\Scenario;

/**
 * Class TaxRateCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class TaxRateCest
{
	/**
	 * @var  string
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $taxRateName = '';

	/**
	 * @var string
	 */
	public $taxRateNameEdit = '';

	/**
	 * @var string
	 */
	public $taxGroupName = '';

	/**
	 * @var string
	 */
	public $taxRateValue = '';

	/**
	 * @var string
	 */
	public $countryName = '';

	/**
	 * @var string
	 */
	public $stateName = '';

	/**
	 * @var integer
	 */
	public $taxRateValueNegative;

	/**
	 * @var string
	 */
	public $taxRateValueString;

	/**
	 * TaxRateCest constructor.
	 */
	public function __construct()
	{
		$this->taxRateName          = 'Testing Tax Rates Groups' . rand(1, 199);
		$this->taxRateNameEdit      = $this->taxRateName . 'Edit';
		$this->taxGroupName         = 'Testing VAT Groups690';
		$this->taxRateValue         = rand(0, 1);
		$this->countryName          = 'United States';
		$this->stateName            = 'Alabama';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';
	}

	/**
	 * Create VAT Group with
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
	}

	/**
	 * Create VAT Tax Rates
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createVATGroupSave
	 */
	public function createTaxRatesSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName);
	}

	/**
	 * Create TAX Rates missing name
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addTAXRatesMissingNameSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates Save missing name creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesMissingNameSave($this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName);
	}

	/**
	 * Create Tax Rates missing groups
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addTAXRatesMissingGroupsSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates missing groups save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesMissingGroupsSave($this->taxRateName, $this->taxRateValue);
	}

	/**
	 * Create Tax Rates missing tax value
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addTAXRatesMissingTaxValueSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX missing tax value Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesMissingTaxValueSave($this->taxRateName, $this->taxGroupName);
	}

	/**
	 * Create Tax Rates with value amount less zero
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addTAXRatesValueAmountLessZeroSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX amount less zero Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesValueAmountLessZeroSave($this->taxRateName, $this->taxGroupName, $this->taxRateValueNegative);
	}

	/**
	 * Creat TAX Rates with amount is string
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addTAXRatesValueAmountStringSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates with amount is string  Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesValueAmountStringSave(
			$this->taxRateValueString, $this->taxGroupName, $this->taxRateValueString, $this->countryName, $this->stateName
		);
	}

	/**
	 * Edit Tax Rates name try to clicks on name of TAX Rates
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createTaxRatesSave
	 */
	public function editTAXRatesName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->editTAXRatesName($this->taxRateName, $this->taxRateNameEdit);
	}

	/**
	 * Edit Tax Rates name by Edit button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends editTAXRatesName
	 */
	public function editButtonTAXRatesName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates edit with Edit button Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->editTAXRatesName($this->taxRateNameEdit, $this->taxRateName);
	}

	/**
	 * Edit Tax Rates missing name
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends editButtonTAXRatesName
	 */
	public function editTAXRatesMissingName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Edit TAX missing name in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->editTAXRatesMissingName($this->taxRateName);
	}

	/**
	 * Create tax rate with Save & Close
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function addTAXRatesSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test TAX Rates Save and Close creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSaveClose($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName);
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
		$client->searchTAXRates($this->taxRateName);
	}

	/**
	 * Check cancel button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function checkCancel(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('check Cancel creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->checkCancel();
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
	}

	/**
	 * Check delete button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function deleteButton(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test delete button in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->deleteButton();
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
	}

	/**
	 * Delete taxRate
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function deleteTAXRates(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test delete button in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxRateSteps($scenario);
		$client->deleteTAXRatesOK($this->taxRateName);
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
	}

	/**
	 * codeception use for show dialog delete VAT/TAX rates
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	/**
	public function deleteTAXRatesCancel(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test delete button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
		$client->wantTo(' Delete  button');
		$client->deleteTAXRatesCancel($this->TAXRatesName);
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
	}
	*/

	/**
	 * codeception use for show dialog delete VAT/TAX rates
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	/**
	public function deleteTAXRatesOK(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test delete button in Administrator');
		$client->doAdministratorLogin();
		$client = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
		$client->wantTo(' Delete  button');
		$client->deleteTAXRatesOK($this->TAXRatesName);
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
	}
	*/
}
