<?php

/**
 *
 * Configuration function
 *
 */
use AcceptanceTester\ConfigurationManageJoomla3Steps;
class ManageConfigurationAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		//setup VAT for system

		$this->country = 'United States';
		$this->state = 'Alabam';
		$this->vatDefault = 'Default';
		$this->vatCalculation = 'Webshop';
		$this->vatAfter = 'after';
		$this->vatNumber = $this->faker->numberBetween(1, 10);
		$this->calculationBase = 'billing';
		$this->requiVAT = 'no';

		//setup Cart setting
		$this->addcart = 'product';
		$this->allowPreOrder = 'yes';
		$this->cartTimeOut = $this->faker->numberBetween(100, 10000);
		$this->nableQuation = 'no';
		$this->enabldAjax = 'no';
		$this->defaultCart = null;
		$this->buttonCartLead = 'Back to current view';
		$this->onePage = 'no';
		$this->showShippingCart = 'no';
		$this->attributeImage = 'no';
		$this->quantityChange = 'no';
		$this->quantityInCart = 0;
		$this->minimunOrder = 0;
		$this->enableQuation = 'no';

		//user shopper registration
		$this->registerMethod='Normal account creation';
		$this->createNewUser='no';
		$this->emailVerify='no';
		$this->showTerm='perOrder';
		$this->whoCan='Both';
		$this->defaultCustomer='Private customer';
		$this->checkoutLogin='Sliders';
		//user shopper groups
		$this->portal='no';
		$this->privateGroup='Default Private';
		$this->companyGroups='Default Company';
		$this->shopperGroupsUnregistered='Default Private';
		$this->newGroupsInherit='Default Private';

	}


	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function featureUsedStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Start stook room ');
		$I->featureUsedStockRoom();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureStockRoomNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Off stook room ');
		$I->featureOffStockRoom();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function featureEditInLineYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureEditInLineNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureComparisonYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureComparisonYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureComparisonNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is No  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is No ');
		$I->featureComparisonNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function featurePriceNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is No  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is No ');
		$I->featurePriceNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function featurePriceYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is Yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featurePriceYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	/**
	 *
	 * Function setup vat for system
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function setupVAT(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup VAT at admin');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->setupVAT($this->country, $this->state, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);

	}

	/**
	 *
	 * function setup cart setting 
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function cartSetting(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup VAT at admin');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);
	}

	public function userRegistration(AcceptanceTester $I, $scenario){
		$I->wantTo('setup user registration at admin');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->registration($this->registerMethod, $this->createNewUser,$this->emailVerify,$this->showTerm,$this->whoCan,$this->defaultCustomer,$this->checkoutLogin);
	}
	public function shopperGroups(AcceptanceTester $I,  $scenario){
		$I->wantTo('setup user registration at admin');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->shopperGroups($this->portal,$this->privateGroup,$this->companyGroups,$this->shopperGroupsUnregistered,$this->newGroupsInherit);
	}

}