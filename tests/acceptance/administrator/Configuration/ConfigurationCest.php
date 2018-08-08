<?php

/**
 *
 * Configuration function
 *
 */
class ConfigurationCest
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

    /**
     * @param AcceptanceTester $I
     */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function allCaseAtConfigurations(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test enable Stockroom in Administrator');
		$I = new AcceptanceTester\ConfigurationSteps($scenario);
		$I->featureUsedStockRoom();

		$I->wantTo('Test off Stockroom in Administrator');
		$I->featureOffStockRoom();

		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I->featureEditInLineYes();

		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I->featureEditInLineNo();

		$I->wantTo('Test Comparison is yes  in Administrator');
		$I->featureComparisonYes();

		$I->wantTo('Test Comparison is No  in Administrator');
		$I->featureComparisonNo();

		$I->wantTo('Show Price is No  in Administrator');
		$I->featurePriceNo();

		$I->wantTo('Show Price is Yes  in Administrator');
		$I->featurePriceYes();

		$I->wantTo('setup VAT at admin');
		$I->setupVAT($this->country, $this->state, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);

		$I->wantTo('setup VAT at admin');
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);
	}
}