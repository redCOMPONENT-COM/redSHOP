<?php
/**
 * Shipping rate page .
 */

class ShippingCest
{
	public function __construct()
	{
		$this->shippingName = 'TestingShippingRate' . rand(99, 999);
		$this->shippingNameEdit=$this->shippingName."edit";
		$this->shippingNameSaveClose="TestingSave".rand(1,100);
		$this->shippingRate= rand(1,100);
		$this->shippingRateEdit=rand(100,1000);
		$this->weightStart="";
		$this->weightEnd="";
		$this->volumeStart="";
		$this->volumeEnd="";
		$this->shippingRateLenghtStart="";
		$this->shippingRateLegnhtEnd="";
		$this->shippingRateWidthStart="";
		$this->shippingRateWidthEnd="";
		$this->shippingRateHeightStart="";
		$this->shippingRateHeightEnd="";
		$this->orderTotalStart="";
		$this->orderTotalEnd="";
		$this->zipCodeStart="";
		$this->zipCodeEnd="";
		$this->country="";
		$this->shippingRateProduct="";
		$this->shippingCategory ="";
		$this->shippingShopperGroups ="";
		$this->shippingPriority="";
		$this->shippingRateFor="";
		$this->shippingVATGroups="";
		$this->pickup="pick";

	}
	public function createShippingRate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->createShippingRateStandard($this->shippingName,$this->shippingRate,$this->weightStart,$this->weightEnd,$this->volumeStart,$this->volumeEnd,$this->shippingRateLenghtStart,$this->shippingRateLegnhtEnd,$this->shippingRateWidthStart,$this->shippingRateWidthEnd,$this->shippingRateHeightStart, $this->shippingRateHeightEnd
			,$this->orderTotalStart,  $this->orderTotalEnd,$this->zipCodeStart,$this->zipCodeEnd, $this->country,$this->shippingRateProduct,$this->shippingCategory ,
	 $this->shippingShopperGroups ,$this->shippingPriority,$this->shippingRateFor,$this->shippingVATGroups,'save');

		$I->createShippingRateStandard($this->shippingNameSaveClose,$this->shippingRate,$this->weightStart,$this->weightEnd,$this->volumeStart,$this->volumeEnd,$this->shippingRateLenghtStart,$this->shippingRateLegnhtEnd,$this->shippingRateWidthStart,$this->shippingRateWidthEnd,$this->shippingRateHeightStart, $this->shippingRateHeightEnd
			,$this->orderTotalStart,  $this->orderTotalEnd,$this->zipCodeStart,$this->zipCodeEnd, $this->country,$this->shippingRateProduct,$this->shippingCategory ,
			$this->shippingShopperGroups ,$this->shippingPriority,$this->shippingRateFor,$this->shippingVATGroups,'saveclose');
	}

	public function editShippingRateStandard(AcceptanceTester $I,$scenario ){

		$I->wantTo('Edit a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->editShippingRateStandard($this->shippingName,$this->shippingNameEdit, $this->shippingRateEdit,'save');
		$I->editShippingRateStandard($this->shippingNameEdit,$this->shippingName, $this->shippingRate,'saveclose');
	}

	public function deleteShippingRate(AcceptanceTester $I, $scenario){
		$I->wantTo('Edit a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->deleteShippingRate($this->shippingName);
	}
}