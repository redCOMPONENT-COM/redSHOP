<?php
/**
 * Shipping rate page .
 */

class ShippingCest
{
	public function __construct()
	{
		// Shipping info
		$this->shippingMethod           = 'redSHOP - Standard Shipping';
		$this->shipping                 = array();
		$this->shipping['shippingName'] = 'TestingShippingRate' . rand(99, 999);
		$this->shipping['shippingRate'] = 10;

		$this->shippingSaveClose                 = array();
		$this->shippingSaveClose['shippingName'] = 'TestingShippingRate' . rand(99, 999);
		$this->shippingSaveClose['shippingRate'] = 10;


		$this->shippingNameEdit      = $this->shippingName . "edit";
		$this->shippingNameSaveClose = "TestingSave" . rand(1, 100);
		$this->shippingRateEdit      = rand(100, 1000);


	}

	public function createShippingRate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->wantTo('Check create new Shipping rate with save button');
		$I->createShippingRateStandard($this->shippingMethod, $this->shipping, 'save');

		$I->wantTo('Check create new Shipping rate with save & close button');
		$I->createShippingRateStandard($this->shippingMethod, $this->shippingSaveClose, 'saveclose');

	}

	public function editShippingRateStandard(AcceptanceTester $I, $scenario)
	{

		$I->wantTo('Edit a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->editShippingRateStandard($this->shipping['shippingName'], $this->shippingNameEdit, $this->shippingRateEdit, 'save');
		$I->editShippingRateStandard($this->shippingNameEdit, $this->shipping['shippingName'], $this->shipping['shippingRate'], 'saveclose');
	}

}