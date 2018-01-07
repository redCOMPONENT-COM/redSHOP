<?php

/**
 *
 * Configuration function
 *
 */
class Configuration
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		//setup VAT for system

		$this->country         = 'United States';
		$this->state           = 'Alabam';
		$this->vatDefault      = 'Default';
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiVAT        = 'no';

		//setup Cart setting
		$this->addcart          = 'product';
		$this->allowPreOrder    = 'yes';
		$this->cartTimeOut      = $this->faker->numberBetween(100, 10000);
		$this->nableQuation     = 'no';
		$this->enabldAjax       = 'no';
		$this->defaultCart      = null;
		$this->buttonCartLead   = 'Back to current view';
		$this->onePage          = 'no';
		$this->showShippingCart = 'no';
		$this->attributeImage   = 'no';
		$this->quantityChange   = 'no';
		$this->quantityInCart   = 0;
		$this->minimunOrder     = 0;
		$this->enableQuation    = 'no';

	}


	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function featureUsedStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test enable Stockroom in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureUsedStockRoom();
	}

	public function featureStockRoomNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test off Stockroom in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureOffStockRoom();
	}


	public function featureEditInLineYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureEditInLineYes();
	}

	public function featureEditInLineNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureEditInLineNo();
	}

	public function featureComparisonYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureComparisonYes();
	}

	public function featureComparisonNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is No  in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureComparisonNo();
	}


	public function featurePriceNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is No  in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featurePriceNo();
	}

	public function featurePriceYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is Yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featurePriceYes();
	}

	/**
	 * Function setup vat for system
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function setupVAT(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup VAT at admin');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->setupVAT($this->country, $this->state, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);
	}

	/**
	 *
	 * function setup cart setting
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function cartSetting(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup VAT at admin');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);
	}
}