<?php
/**
 * Shipping rate page .
 */

class ShippingCest
{
    public function __construct()
    {
        $this->shipping = array();
        $this->shippingName = 'TestingShippingRate' . rand(99, 999);
        $this->shippingNameEdit = $this->shippingName . "edit";
        $this->shippingNameSaveClose = "TestingSave" . rand(1, 100);
        $this->shippingRate = rand(1, 100);
        $this->shippingRateEdit = rand(100, 1000);
        $this->weightStart = "";
        $this->weightEnd = "";
        $this->volumeStart = "";
        $this->volumeEnd = "";
        $this->shippingRateLenghtStart = "";
        $this->shippingRateLegnhtEnd = "";
        $this->shippingRateWidthStart = "";
        $this->shippingRateWidthEnd = "";
        $this->shippingRateHeightStart = "";
        $this->shippingRateHeightEnd = "";
        $this->orderTotalStart = "";
        $this->orderTotalEnd = "";
        $this->zipCodeStart = "";
        $this->zipCodeEnd = "";
        $this->country = "";
        $this->shippingRateProduct = "";
        $this->shippingPriority = "";
        $this->pickup = "pick";
        $this->shipping['name'] = $this->shippingName;
        $this->shipping['weightEnd'] = $this->weightEnd;
        $this->shipping['weightStart'] = $this->weightStart;
        $this->shipping['volumeStart'] = $this->volumeStart;
        $this->shipping['volumeEnd'] = $this->volumeEnd;
        $this->shipping['shippingRateLegnhtEnd'] = $this->shippingRateLegnhtEnd;
        $this->shipping['shippingRateLenghtStart'] = $this->shippingRateLenghtStart;
        $this->shipping['shippingRateWidthStart'] = $this->shippingRateWidthStart;
        $this->shipping['shippingRateWidthEnd'] = $this->shippingRateWidthEnd;
        $this->shipping['shippingRateHeightEnd'] = $this->shippingRateHeightEnd;
        $this->shipping['shippingRateHeightStart'] = $this->shippingRateHeightStart;
        $this->shipping['orderTotalStart'] = $this->orderTotalStart;
        $this->shipping['orderTotalEnd'] = $this->orderTotalEnd;
        $this->shipping['zipCodeStart'] = $this->zipCodeStart;
        $this->shipping['zipCodeEnd'] = $this->zipCodeEnd;
        $this->shipping['shippingPriority'] = $this->shippingPriority;
    }

    public function createShippingRate(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create a shipping Rate');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShippingSteps($scenario);
        $I->createShippingRateStandard($this->shipping,'save');
        $this->shipping['name'] = $this->shippingNameSaveClose;
        $I->createShippingRateStandard($this->shipping,'saveclose');
    }

    public function editShippingRateStandard(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Edit a shipping Rate');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShippingSteps($scenario);
        $I->editShippingRateStandard($this->shippingName, $this->shippingNameEdit, $this->shippingRateEdit, 'save');
        $I->editShippingRateStandard($this->shipping['name'], $this->shippingName, $this->shippingRate, 'saveclose');
    }

}