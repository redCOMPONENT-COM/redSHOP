<?php

use Configuration\ConfigurationSteps;

/**
 *
 * Configuration function
 *
 */
class ConfigurationCest
{
	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder1;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder2;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder3;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder4;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder5;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder6;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder7;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder8;

	/**
	 * @var array
	 * @since
	 */
	protected $configurationOder9;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder10;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $configurationOder11;


	/**
	 * ConfigurationCest constructor.
	 * @since 2.1.3
	 */
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

		//Configuration Oder Reset Id Oder
		$this->configurationOder1 =
			[
				'resetIdOder'                   => 'Reset Id Oder',//
			];

		//Configuration Oder Oder Email After Payment
		$this->configurationOder2 =
			[
				'sendOderEmail'                 => 'Send Oder Email',
				'afterPayment'                  => 'After Payment',
			];

		//Configuration Oder Send Oder email After Payment, But Send Before To Administrator
		$this->configurationOder3 =
			[
				'sendOderEmail'                 => 'Send Oder Email',
				'afterPayment2'                 => 'After Payment, but send before to administrator',
			];


		//Configuration Oder Send Oder Email Before Payment
		$this->configurationOder4 =
			[
				'sendOderEmail'                 => 'Send Oder Email',
				'beforePayment'                 => 'Before Payment',
			];

		//Configuration Oder Enable In Voice Email Yes, None
		$this->configurationOder5 =
			[
				'enableInVoiceEmail'            => 'Enable In Voice Email',
				'Yes'                           => 'Yes',
				'None'                          => 'None',
			];

		//Configuration Oder Enable In Voice Email Yes, Administrator
		$this->configurationOder6 =
			[
				'enableInVoiceEmail'            => 'Enable In Voice Email',
				'Yes'                           => 'Yes',
				'Administrator'                 => 'Administrator',
			];

		//Configuration Oder Enable In Voice Email Yes, Customer
		$this->configurationOder7 =
			[
				'enableInVoiceEmail'            => 'Enable In Voice Email',
				'Yes'                           => 'Yes',
				'Customer'                      => 'Customer',
			];

		//Configuration Oder Enable In Voice Email Yes, Both
		$this->configurationOder8 =
			[
				'enableInVoiceEmail'            => 'Enable In Voice Email',
				'Yes'                           => 'Yes',
				'Both'                          => 'Both',
			];

		//Configuration OderEnable In Voice Email, No
		$this->configurationOder9 =
			[
				'enableInVoiceEmail'            => 'Enable In Voice Email',
				'No'                            => 'No',
			];

		//Configuration Oder Send Mail To Customer In Oder, Yes
		$this->configurationOder10 =
			[
				'sendMailToCustomerInOder'      => 'Send mail to customer in oder',
				'Yes'                           => 'Yes',
			];

		//Configuration Oder Send Mail To Customer In oder, No
		$this->configurationOder11 =
			[
				'sendMailToCustomerInOder'      => 'Send mail to customer in oder',
				'No'                            => 'No',
			];
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
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

		$I->wantTo('Test Configuration Oder Reset Id Oder');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder1);

		$I->wantTo('Test Configuration Oder Oder Email After Payment');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder2);

		$I->wantTo('Test Configuration Oder Send Oder email After Payment, But Send Before To Administrator');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder3);

		$I->wantTo('Test Configuration Oder Send Oder Email Before Payment');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder4);

		$I->wantTo('Test Configuration Oder Enable In Voice Email Yes None');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder5);

		$I->wantTo('Test Configuration Oder Enable In Voice Email Yes Administrator');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder6);

		$I->wantTo('Test Configuration Oder Enable In Voice Email Yes Customer');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder7);

		$I->wantTo('Test Configuration Oder Enable In Voice Email Yes Both');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder8);

		$I->wantTo('Test Configuration OderEnable In Voice Email No');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder9);

		$I->wantTo('Test Configuration Oder Send Mail To Customer In Oder Yes');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder10);

		$I->wantTo('Test Configuration Oder Send Mail To Customer In Oder No');
		$I = new ConfigurationSteps($scenario);
		$I->ConfigurationOder($this->configurationOder11);
	}
}