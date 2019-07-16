<?php

use Configuration\ConfigurationSteps;

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

		//oder
		$this->resetIdOder                  = 'Reset Id Oder';
		$this->sendOderEmail                = 'Send Oder Email';
		$this->afterPayment                 = 'After Payment';
		$this->beforePayment                = 'Before Payment';
		$this->afterPayment2                = 'After Payment, but send before to administrator';
		$this->enableInVoiceEmail           = 'Enable In Voice Email';
		$this->sendMailToCustomerInOder     = 'Send mail to customer in oder';
		$this->Yes                          = 'Yes';
		$this->No                           = 'No';


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
		$I = new ConfigurationSteps($scenario);
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

		$I->wantTo('Test reset id oder');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->resetIdOder, 0);

		$I->wantTo('Test send oder email After Payment');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->sendOderEmail, $this->afterPayment);

		$I->wantTo('Test send oder email After Payment, but send before to administrator');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->sendOderEmail, $this->afterPayment2);

		$I->wantTo('Test send oder email Before Payment');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->sendOderEmail, $this->beforePayment);

		$I->wantTo('Test Enable In Voice Email Yes');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->enableInVoiceEmail, $this->Yes);

		$I->wantTo('Test Enable In Voice Email No');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->enableInVoiceEmail, $this->No);

		$I->wantTo('Test Send mail to customer in oder Yes');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->sendMailToCustomerInOder, $this->Yes);

		$I->wantTo('Test Send mail to customer in oder No');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->sendMailToCustomerInOder, $this->No);
	}
}